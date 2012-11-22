<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'FE User Reminder');

$tempColumns = array (
    'feusersreminder_max_reminds' => array (
        'label' => 'LLL:EXT:feusers_reminder/Resources/Private/Language/locallang_db.xml:tx_feusersreminder.maxreminds',
        'config' => array (
            'type' => 'input',
 			'size' => '10',
			'readOnly' => 1,
        )
    ),
	'feusersreminder_last_remind' => array(
		'label' => 'LLL:EXT:feusers_reminder/Resources/Private/Language/locallang_db.xml:tx_feusersreminder.lastremind',
		'config' => array(
			'type' => 'input',
			'size' => '10',
			'readOnly' => 1,
			'eval' => 'date',
		)
	),
	'crdate' => array (
		'exclude' => 1,
		'label' => 'Creation date',
		'config' => array (
			'type' => 'none',
			'format' => 'date',
			'eval' => 'date',
		)
	),
);

t3lib_div::loadTCA('fe_users');
t3lib_extMgm::addTCAcolumns('fe_users', $tempColumns, 1);
t3lib_extMgm::addToAllTCAtypes('fe_users', 'feusersreminder_max_reminds;;;;1-1-1');
t3lib_extMgm::addToAllTCAtypes('fe_users', 'feusersreminder_last_remind;;;;1-1-1');
t3lib_extMgm::addToAllTCAtypes('fe_users', 'crdate;;;;1-1-1');


$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);

/* ===========================================================================
	Registration of Tasks
=========================================================================== */
if (TYPO3_MODE == 'BE') {
	if (t3lib_extMgm::isLoaded('scheduler')) {

		// load csh file
		t3lib_extMgm::addLLrefForTCAdescr('tx_feusersreminder_scheduler', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_scheduler.xml');

		if (t3lib_div::int_from_ver(TYPO3_version) < 4007000) {
			// register scheduler task to remind users
			$TYPO3_CONF_VARS['SC_OPTIONS']['scheduler']['tasks']['Tx_FeusersReminder_Tasks_ReminderTasks'] = array (
				'extension'			=> $_EXTKEY,
				'title'				=> 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_scheduler.xml:scheduler_label.task_title_reminder',
				'description'		=> 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_scheduler.xml:scheduler_label.task_description_reminder',
				'additionalFields'	=> 'Tx_' . $extensionName . '_Tasks_SchedulerAddFields4Reminder'
			);
		}
	}
}

?>