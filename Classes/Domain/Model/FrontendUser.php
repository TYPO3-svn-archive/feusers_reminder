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
 * Maps FE_Users Table
 *
 * @package feusers_reminder
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_FeusersReminder_Domain_Model_FrontendUser extends Tx_Extbase_Domain_Model_FrontendUser {

	/**
	 *
	 * @var DateTime
	 */
	protected $crdate;

	/**
	 *
	 * @var integer
	 */
	protected $feusersreminderMaxReminds;

	/**
	 *
	 * @var DateTime
	 */
	protected $feusersreminderLastRemind;


	public function __construct($username = '', $password = '') {
		parent::__construct($username, $password);
	}

	/**
	 * Set the creation date
	 *
	 * @param DateTime $crdate
	 * @return void
	 */
	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Set the number of max reminds
	 *
	 * @param integer $maxReminds
	 * @return void
	 */
	public function setFeusersreminderMaxReminds($maxReminds) {
		$this->feusersreminderMaxReminds = $maxReminds;
	}

	/**
	 * Set the time of last remind
	 *
	 * @param DateTime $lastRemind
	 * @return void
	 */
	public function setFeusersreminderLastRemind($lastRemind) {
		$this->feusersreminderLastRemind = $lastRemind;
	}


	/**
	 * Get the creation time
	 *
	 * @return DateTime
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Get the number of max reminds
	 *
	 * @return integer
	 */
	public function getFeusersreminderMaxReminds() {
		return $this->feusersreminderMaxReminds;
	}

	/**
	 * Get time of last remind
	 *
	 * @return DateTime
	 */
	public function getFeusersreminderLastRemind() {
		return $this->feusersreminderLastRemind;
	}
}

?>
