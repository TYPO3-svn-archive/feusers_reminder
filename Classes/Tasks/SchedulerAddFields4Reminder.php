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

// stop implementation in frontend (only for backend)
if (!interface_exists(tx_scheduler_AdditionalFieldProvider)) {
	return;
}

/**
 * Description of SchedulerAddFields4Reminder
 *
 * @package feusers_reminder
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_FeusersReminder_Tasks_SchedulerAddFields4Reminder implements tx_scheduler_AdditionalFieldProvider {

	/**
	 *
	 * @param array $taskInfo
	 * @param type $task
	 * @param tx_scheduler_Module $parentObject
	 */
	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {
    	$additionalFields	= array();
		$label				= 'LLL:EXT:feusers_reminder/Resources/Private/Language/locallang_scheduler.xml:scheduler_label.';
		$cshKey				= 'tx_feusersreminder_scheduler';

        if (empty($taskInfo['maxTimes2Remind'])) {
            if ($parentObject->CMD == 'edit') {
				$taskInfo['maxTimes2Remind'] = $task->getMaxTimes2Remind();
            } else {
				$taskInfo['maxTimes2Remind'] = '';
            }
        }

        if (empty($taskInfo['maxTimeAfterRegistration'])) {
            if ($parentObject->CMD == 'edit') {
				$taskInfo['maxTimeAfterRegistration'] = $task->getMaxTimeAfterRegistration();
            } else {
				$taskInfo['maxTimeAfterRegistration'] = '';
            }
        }

        if (empty($taskInfo['maxTimeAfterRemind'])) {
            if ($parentObject->CMD == 'edit') {
				$taskInfo['maxTimeAfterRemind'] = $task->getMaxTimeAfterRemind();
            } else {
				$taskInfo['maxTimeAfterRemind'] = '';
            }
        }

        if (empty($taskInfo['group2Remind'])) {
            if ($parentObject->CMD == 'edit') {
				$taskInfo['group2Remind'] = $task->getGroup2Remind();
            } else {
				$taskInfo['group2Remind'] = '';
            }
        }

        if (empty($taskInfo['senderEmail'])) {
            if ($parentObject->CMD == 'edit') {
				$taskInfo['senderEmail'] = $task->getSenderEmail();
            } else {
				$taskInfo['senderEmail'] = '';
            }
        }

		if (empty($taskInfo['emailSubject'])) {
            if ($parentObject->CMD == 'edit') {
				$taskInfo['emailSubject'] = $task->getEmailSubject();
            } else {
				$taskInfo['emailSubject'] = '';
            }
        }

		if (empty($taskInfo['emailBody'])) {
            if ($parentObject->CMD == 'edit') {
				$taskInfo['emailBody'] = $task->getEmailBody();
            } else {
				$taskInfo['emailBody'] = '';
            }
        }



		// Write the code for the max time after registration field
        $fieldID = 'task_maxtimeafterregistration';
        $fieldCode = '<input type="input" name="tx_scheduler[maxTimeAfterRegistration]" id="' . $fieldID . '" value="' . $taskInfo['maxTimeAfterRegistration'] . '" />';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => $label . $fieldID,
			'cshKey'   => $cshKey,
			'cshLabel' => 'schedulertask_maxtimeafterregistration'
        );


		// Write the code for the max time after registration field
        $fieldID = 'task_maxtimes2remind';
        $fieldCode = '<input type="input" name="tx_scheduler[maxTimes2Remind]" id="' . $fieldID . '" value="' . $taskInfo['maxTimes2Remind'] . '" />';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => $label . $fieldID,
			'cshKey'   => $cshKey,
			'cshLabel' => 'schedulertask_maxtimes2remind'
        );


		// Write the code for the max time after remind field
        $fieldID = 'task_maxtimeafterremind';
        $fieldCode = '<input type="input" name="tx_scheduler[maxTimeAfterRemind]" id="' . $fieldID . '" value="' . $taskInfo['maxTimeAfterRemind'] . '" />';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => $label . $fieldID,
			'cshKey'   => $cshKey,
			'cshLabel' => 'schedulertask_maxtimeafterremind'
        );


		// Write the code for the sender email field
        $fieldID = 'task_senderemail';
        $fieldCode = '<input type="input" name="tx_scheduler[senderEmail]" id="' . $fieldID . '" value="' . $taskInfo['senderEmail'] . '" style="width:347px;" />';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => $label . $fieldID,
			'cshKey'   => $cshKey,
			'cshLabel' => 'schedulertask_senderemail'
        );

        $fieldID = 'task_emailsubject';
        $fieldCode = '<input type="input" name="tx_scheduler[emailSubject]" id="' . $fieldID . '" value="' . $taskInfo['emailSubject'] . '" style="width:347px;" />';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => $label . $fieldID,
			'cshKey'   => $cshKey,
			'cshLabel' => 'schedulertask_emailsubject'
        );


		// Option to determine which customer group to be deleted
		$fieldID	= 'task_group2remind';
		$groups		= $this->getFeuserGroups();
		$fieldCode	= $GLOBALS['LANG']->sL($label . 'warning_group2remind');

		if ($groups->count() > 0) {
			$fieldCode	= '<select name="tx_scheduler[group2Remind]" id="' . $fieldID . '">';
			$fieldCode	.= '<option value="0">' . $GLOBALS['LANG']->sL($label . 'select_group2remind') . '</option>';
			foreach ($groups AS $group) {
				$fieldCode .= "\t" . '<option value="' . intval($group->getUid()) . '"'
							  . ((intval($group->getUid()) == intval($taskInfo['group2Remind'])) ? ' selected="selected"' : '')
							  . '>' . $group->getTitle() . '</option>';
			}

			$fieldCode .= '</select>';
		}

		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => $label . $fieldID,
			'cshKey'   => $cshKey,
			'cshLabel' => 'schedulertask_group2remind'
		);


		// E-Mail Body
        $fieldID = 'task_emailbody';
        $fieldCode = '<textarea name="tx_scheduler[emailBody]" id="' . $fieldID . '" style="width:345px;height:150px;">' . $taskInfo['emailBody'] . '</textarea>';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => $label . $fieldID,
			'cshKey'   => $cshKey,
			'cshLabel' => 'schedulertask_emailbody'
        );

		return $additionalFields;
	}

	/**
	 *
	 * @param array $submittedData
	 * @param tx_scheduler_Module $schedulerModule
	 */
	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $schedulerModule) {
		$isValid = TRUE;

		if (! empty($submittedData['senderEmail'])) {
			$emailList = t3lib_div::trimExplode(',', $submittedData['senderEmail']);
			foreach ($emailList as $emailAdd) {
				if (!t3lib_div::validEmail($emailAdd)) {
					$isValid = FALSE;
					$schedulerModule->addMessage(
						$GLOBALS['LANG']->sL('LLL:EXT:linkvalidator/locallang.xml:tasks.validate.invalidEmail'),
						t3lib_FlashMessage::ERROR
					);
				}
			}
		}

		if (! t3lib_utility_Math::convertToPositiveInteger($submittedData['group2Remind'])) {
			$isValid = FALSE;
			$schedulerModule->addMessage(
				$GLOBALS['LANG']->sL('LLL:EXT:feusers_reminder/Resources/Private/Language/locallang_scheduler.xml:scheduler_error.invalid_group2remind'),
				t3lib_FlashMessage::ERROR
			);
		}

		if (! t3lib_utility_Math::convertToPositiveInteger($submittedData['maxTimes2Remind'])) {
			$isValid = FALSE;
			$schedulerModule->addMessage(
				$GLOBALS['LANG']->sL('LLL:EXT:feusers_reminder/Resources/Private/Language/locallang_scheduler.xml:scheduler_error.invalid_maxtimes2remind'),
				t3lib_FlashMessage::ERROR
			);
		}

		if (t3lib_utility_Math::convertToPositiveInteger($submittedData['maxTimes2Remind']) && intval($submittedData['maxTimes2Remind']) > 1) {
			if (! t3lib_utility_Math::convertToPositiveInteger($submittedData['maxTimeAfterRemind'])) {
				$isValid = FALSE;
				$schedulerModule->addMessage(
					$GLOBALS['LANG']->sL('LLL:EXT:feusers_reminder/Resources/Private/Language/locallang_scheduler.xml:scheduler_error.invalid_maxtimeafterremind'),
					t3lib_FlashMessage::ERROR
				);
			}
		}

        return $isValid;
	}

	/**
	 *
	 * @param array $submittedData
	 * @param tx_scheduler_Task $task
	 */
	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
        $task->setSenderEmail(trim($submittedData['senderEmail']));
		$task->setEmailSubject(trim($submittedData['emailSubject']));
		$task->setEmailBody(trim($submittedData['emailBody']));
		$task->setGroup2Remind(trim($submittedData['group2Remind']));
		$task->setMaxTimes2Remind(intval($submittedData['maxTimes2Remind']));
		$task->setMaxTimeAfterRegistration($submittedData['maxTimeAfterRegistration']);
		$task->setMaxTimeAfterRemind($submittedData['xaxTimeAfterRemind']);
	}

	/**
	 * Get all available frontendUserGroups
	 *
	 * @return Tx_Extbase_Domain_Model_FrontendUserGroup
	 */
	protected function getFeuserGroups() {

		$feuserGroupsRepository = t3lib_div::makeInstance('Tx_Extbase_Domain_Repository_FrontendUserGroupRepository');

		$query = $feuserGroupsRepository->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		return $query->execute();
	}
}

?>