Created by using SHOW CREATE TABLE


CREATE TABLE `users` (
 `ID` int(10) unsigned NOT NULL auto_increment,
 `PassWord` char(32) collate latin1_german2_ci NOT NULL,
 `Email` char(60) collate latin1_german2_ci NOT NULL,
 `FirstName` char(60) character set utf8 default NULL,
 `LastName` char(60) character set utf8 default NULL,
 `EID` tinyint(4) default '1',
 `ECode` char(10) collate latin1_german2_ci default NULL,
 `EID2` tinyint(4) default '0',
 `ECode2` char(10) collate latin1_german2_ci default NULL,
 PRIMARY KEY  (`ID`),
 UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

CREATE TABLE scores (
 UserID int(10) unsigned NOT NULL,
 Assignment char(12) collate latin1_german2_ci NOT NULL,
 Score char(6) collate latin1_german2_ci NOT NULL,
 NumAttempts int(4) default 1,
 FirstAttemptTime int(10) unsigned NOT NULL,
 LastAttemptTime int(10) unsigned NOT NULL,
 PRIMARY KEY (UserID, Assignment)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;