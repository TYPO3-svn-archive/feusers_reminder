<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "feusers_reminder".
 *
 * Auto generated 23-11-2012 21:39
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'FE User Reminder',
	'description' => 'Implemented a cron job for the TYPO3 scheduler. All users who have not confirmed their account receive, via e-mail a reminder to complete the registration.',
	'category' => 'be',
	'author' => 'Christian Wolfram, Marc Fell',
	'author_email' => 'c.wolfram@chriwo.de, info@marc-fell.de',
	'author_company' => '',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => 'fe_users',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.0.0',
	'constraints' => array(
		'depends' => array(
			'extbase' => '1.3.0',
			'fluid' => '1.3.0',
			'typo3' => '4.6.0-4.7.99',
			'scheduler' => '1.2.0-4.7.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:17:{s:12:"ext_icon.gif";s:4:"f728";s:14:"ext_tables.php";s:4:"8d8e";s:14:"ext_tables.sql";s:4:"a2da";s:21:"ExtensionBuilder.json";s:4:"3095";s:37:"Classes/Domain/Model/FrontendUser.php";s:4:"e255";s:52:"Classes/Domain/Repository/FrontendUserRepository.php";s:4:"25b4";s:31:"Classes/Tasks/ReminderTasks.php";s:4:"0d0b";s:45:"Classes/Tasks/SchedulerAddFields4Reminder.php";s:4:"571d";s:44:"Configuration/ExtensionBuilder/settings.yaml";s:4:"9d95";s:38:"Configuration/TypoScript/constants.txt";s:4:"d41d";s:34:"Configuration/TypoScript/setup.txt";s:4:"b99a";s:40:"Resources/Private/Language/locallang.xml";s:4:"f4fc";s:54:"Resources/Private/Language/locallang_csh_scheduler.xml";s:4:"23b9";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"f781";s:50:"Resources/Private/Language/locallang_scheduler.xml";s:4:"a5bc";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:14:"doc/manual.sxw";s:4:"8d2d";}',
);

?>