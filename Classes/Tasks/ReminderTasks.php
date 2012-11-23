<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Christian Wolfram <c.wolfram@chriwo.de>
 *  Marc Fell <info@marc-fell.de>, Fell Media
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Description of ReminderTasks
 *
 * @package feusers_reminder
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_FeusersReminder_Tasks_ReminderTasks extends tx_scheduler_Task {

	/**
	 * email subject of reminder email
	 *
	 * @var string
	 */
	protected $emailSubject;

	/**
	 * Email address of sender
	 *
	 * @var string
	 */
	protected $senderEmail;

	/**
	 * Feusergroup to remind
	 *
	 * @var integer
	 */
	protected $group2Remind;

	/**
	 * Email body (content) with marker
	 *
	 * @var string
	 */
	protected $emailBody;

	/**
	 * max times 2 remind a user
	 *
	 * @var integer
	 */
	protected $maxTimes2Remind;

	/**
	 * timestamp
	 *
	 * @var integer
	 */
	protected $maxTimeAfterRegistration;

	/**
	 * max time between reminds
	 *
	 * @var integer
	 */
	protected $maxTimeAfterRemind;

	/**
	 *
	 * @var array
	 */
	protected $locallang;

	/**
	 *
	 * @var t3lib_mail_Message
	 */
	protected $mailObj;

	/**
	 *
	 * @var Tx_FeusersReminder_Domain_Repository_FrontendUserRepository
	 */
	protected $frontendUserRepository;



	public function execute() {

		$this->locallang			  = $this->includeLocalLang();
		$this->mailObj				  = t3lib_div::makeInstance('t3lib_mail_Message');
		$this->frontendUserRepository = t3lib_div::makeInstance('Tx_FeusersReminder_Domain_Repository_FrontendUserRepository');

		// load all users which are in usergroup
		$query = $this->frontendUserRepository->createQuery();
		$query->getQuerySettings()
				->setRespectStoragePage(FALSE)
				->setRespectEnableFields(TRUE);
		$query->matching(
			$query->logicalAnd(
				$query->contains('usergroup', intval($this->getGroup2Remind())),
				$query->lessThan('crdate', time() - $this->getMaxTimeAfterRegistration()),
				$query->lessThan('feusersreminder_max_reminds', intval($this->getMaxTimes2Remind())),
				$query->lessThan('feusersreminder_last_remind', time() - $this->getMaxTimeAfterRemind())
			)
		);
		$allUsers = $query->execute();

		if ($allUsers->count() > 0) {
			foreach ($allUsers AS $user) {
				$this->remindUsers($user);
				$this->saveRemindInformation($user);
			}
		}

		return true;
	}

	/**
	 * Send emails to user witch are not filled the diary. Bevore an email is send
	 * would be check the email adress. All user with not valid email address would
	 * be send to an administrator.
	 *
	 * @param Tx_Extbase_Domain_Model_FrontendUser $userObject
	 *
	 * return void
	 */
	protected function remindUsers(Tx_Extbase_Domain_Model_FrontendUser $userObject) {

		if (! t3lib_div::validEmail($userObject->getEmail())) {
			return;
		}

		$userMethods = get_class_methods($userObject);

		foreach ($userMethods AS $index => $method) {
			if (0 !== ($pos = strpos($method, 'get', 0))) {
				unset($userMethods[$index]);
				continue;
			}

			// remove storage object
			if (is_object($userObject->$method())) {
				unset($userMethods[$index]);
				continue;
			}

			$userKey2Method[$index]		= '{' . lcfirst(str_replace('get', '', $method)) . '}';
			$userValue2Method[$index]	= $userObject->$method();
		}
		unset($userMethods);

		// replace marker {marker} with content
		$bodyIntern	= str_replace($userKey2Method, $userValue2Method, $this->getEmailBody());

		// replace special marker {marker}. This marker are storage objects
		$bodyIntern = str_replace('{lastLogin}', $userObject->getLastlogin()->format('d.m.Y'), $bodyIntern);

		$this->mailObj
				->setTo(array($userObject->getEmail() => $userObject->getFirstName() . ' ' .$userObject->getLastName()))
				->setFrom(array($this->getSenderEmail() => $this->getSenderEmail()))
				->setSubject($this->getEmailSubject())
				->setBody($bodyIntern, 'text/plain');
		$this->mailObj->send();

		if (! $this->mailObj->isSent()) {
			$flashMessage = t3lib_div::makeInstance(
					't3lib_FlashMessage',
					sprintf($GLOBALS['LANG']->getLLL('scheduler_warning_email_send', $this->locallang),
							$userObject->getFirstName(), $userObject->getLastName(), $userObject->getEmail()),
					$GLOBALS['LANG']->getLLL('scheduler_warning_email_send.Header', $this->locallang), t3lib_FlashMessage::WARNING);
			t3lib_FlashMessageQueue::addMessage($flashMessage);
		}
	}


	/**
	 * Updae feuser data with information about last remind and
	 * number of reminds
	 *
	 * @param Tx_FeusersReminder_Domain_Model_FrontendUser $userObject
	 *
	 * return void
	 */
	protected function saveRemindInformation(Tx_FeusersReminder_Domain_Model_FrontendUser $userObject) {
		$update = array(
			'feusersreminder_last_remind' => time(),
			'feusersreminder_max_reminds' => ($userObject->getFeusersreminderMaxReminds() + 1));
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users', 'uid=' . $userObject->getUid(), $update);
	}


	/**
	 * Load the locallang file
	 *
	 * @return array
	 */
	protected function includeLocalLang() {
		$localizationParser = t3lib_div::makeInstance('t3lib_l10n_parser_llxml');
		$LOCAL_LANG			= $localizationParser->getParsedData(
			t3lib_extMgm::extPath('feusers_reminder') . 'Resources/Private/Language/locallang_scheduler.xml',
			$GLOBALS['LANG']->lang
		);

		return $LOCAL_LANG;
	}


	/**
	 *
	 * @param string $emailSubject
	 */
	public function setEmailSubject($emailSubject) {
		$this->emailSubject = $emailSubject;
	}

	/**
	 *
	 * @param string $senderEmail
	 */
	public function setSenderEmail($senderEmail) {
		$this->senderEmail = $senderEmail;
	}

	/**
	 *
	 * @param integer $group2Remind
	 */
	public function setGroup2Remind($group2Remind) {
		$this->group2Remind = $group2Remind;
	}

	/**
	 *
	 * @param string $emailBody
	 */
	public function setEmailBody($emailBody) {
		$this->emailBody = $emailBody;
	}

	/**
	 *
	 * @param integer $maxTimes2Remind
	 */
	public function setMaxTimes2Remind($maxTimes2Remind) {
		$this->maxTimes2Remind = $maxTimes2Remind;
	}

	/**
	 *
	 * @param integer $maxTimes2Remind
	 */
	public function setMaxTimeAfterRemind($maxTimeAfterRemind) {
		$this->maxTimeAfterRemind = $maxTimeAfterRemind;
	}

	/**
	 *
	 * @param integer $maxTimeAfterRegistration
	 */
	public function setMaxTimeAfterRegistration($maxTimeAfterRegistration) {
		$this->maxTimeAfterRegistration = $maxTimeAfterRegistration;
	}



	/**
	 *
	 * @return string
	 */
	public function getEmailSubject() {
		return $this->emailSubject;
	}

	/**
	 *
	 * @return string
	 */
	public function getSenderEmail() {
		return $this->senderEmail;
	}

	/**
	 *
	 * @return integer
	 */
	public function getGroup2Remind() {
		return $this->group2Remind;
	}

	/**
	 *
	 * @return string
	 */
	public function getEmailBody() {
		return $this->emailBody;
	}

	/**
	 *
	 * @return integer
	 */
	public function getMaxTimes2Remind() {
		return $this->maxTimes2Remind;
	}

	/**
	 *
	 * @return integer
	 */
	public function getMaxTimeAfterRemind() {
		return $this->maxTimeAfterRemind;
	}

	/**
	 *
	 * @return integer
	 */
	public function getMaxTimeAfterRegistration() {
		return $this->maxTimeAfterRegistration;
	}
}

?>