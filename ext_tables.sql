#
# Table structure for table 'fe_users'
#
CREATE TABLE fe_users (
	feusersreminder_max_reminds tinyint(4) DEFAULT '0' NOT NULL,
	feusersreminder_last_remind int(11) DEFAULT '0' NOT NULL,
);