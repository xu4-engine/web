<?php
// lang-file for the admin-area of phorum-3.4.x

// install-variables:
$lTitle            = "Phorum Installation";
$lAdmin_Perm_Check = "-<b>Checking permissions:</b><br />";
$lAdmin_Perm_Ok    = " is <font class='check_ok'>[OK]</font><br \>";
$lAdmin_Perm_Bad   = " is <font class='check_bad'>[BAD]</font><br \><font class=error>Fix: change permissions to 777 or 666</font><br />";
$lHelp             = "help";
$lNextStep	   = "-Next Step-";
$lFinish           = "-Final Step-";
$lStep1_end        = "Step 1: Completed.<br \>";
$lStep2		   = "Step 2: Database Type.";
$lDBType	   = "Enter Type Of Database :";
$lStep2_end	   = "Step 2: Completed.<br \>";
$lStep3		   = "Step 3: Database Settings.";
$lStep3_end	   = "<br \>Step 3: Completed.<br \>";
$lStep4            = "Step 4: Admin User.";
$lStep4_end	   = "Step 4: Completed.<br \>";
$lStep5            = "Step 5: Last Step.";
$lDBServer	   = "Database - Server Name:";
$lDBName	   = "Database - Name:";
$lDBUser	   = "Database - User Name:";
$lDBPass	   = "Database - Password:";
$lPhorumTable      = "Phorum - Main Table Name:";
$lUpdate           = "Check here if this is an upgrade.<br />Read docs/upgrade.txt for information about some of your settings.";
$lAttachmentDir    = "If you want to upgrade attachments too, just enter the path to the attachments-dir here as it was in your old install.";
$lDBNote	   = "NOTE:  If SQL Safe Mode is in use on your server, leave the username and password emtpy.";
$lAdminUser        = "User Name:";
$lAdminPass        = "Password:";
$lAdminPass2       = "(again)";
$lPhorum_URL       = "Phorum URL:";
$lAdminEmail       = "Admin Email Address:";
$lDefaultEmail     = "Phorum Email Address:";
$lAdminName        = "Display name:";
$lErrorFile        = "Could not find or open the dbfile. Make sure u uploaded db dir.";
$lErrorDB	   = "Could not connect to database.  Check your settings again.";
$lErrorTables      = "Mysql couldn't create tables. Its an Upgrate, perhaps?";
$lErrorFields      = "Please fill in all fields";
$lErrorPass        = "Passwords do not match";
$lErrorWrongPass   = "User exists, but password is incorrect.";
$lErrorDBAdmin     = "Could not create admin user.  Database said: ";
$lErrorURL         = "That is not a valid URL";
$lErrorEmail       = "That is not a valid email address";
$lErrorName        = "Display Name is empty";
$lDB_Ok            = "Database settings OK!<br />\n";
$lDB_Upgrade       = "Upgrading tables...<br />";
$lDB_Create        = "Creating initial tables...<br />\n";
$lDB_Create_done   = "<b>Tables Created!</b><br />\n";
$lUserExists       = "User is in database...<br />\n";
$lUserIsAdmin      = "$AdminUser is already an admin.<br />\n";
$lUserAdmin        = "$AdminUser wasn't an admin, but now is :)<br />\n";
$lAdminCreated     = "Admin User Created<br />\n";
$lFINAL            = "Congratulations!  <a href=\"$PHP_SELF\">Click here</a> to go to the admin.";
// END Insall Variables;
// START Install Help text:
$lHelpTitle        = "Phorum Installation: Help";
$lCloseWindow      = "Close me.";
$lStep1_help       = "First off, welcome to Phourm Installation Script.  I will try guiding a novice Phorum Admin through installation.  All you need to on the 1st step is to choose the language of Installation, and my language as well.";
$lStep2_help       = "At the top of each installation screen you will see information on the actions that took place after you hit the button.  On last step, phorum made sure that permissions of the files that phorum will write to are correct.  If you see <font class='check_bad'>[BAD]</font> next to the forums.php or settings dir, you have to CHMOD it 777 or 666 (any should work, but 666 might not work on some servers).  It is usually done with FTP client such as SmartFTP by right clicking on the file/dir and choosing \"File Permissions\" or \"Chmod\" command.  You don't have to push \"Refresh\" button of your browser if you are sure to set correct permissions, but if you are not sure, Phorum will recheck the permissions after you refresh your browser.<br \><br \> After you fixed permissions, it is time to choose the database interface your server has.  Phorum supports MySQL and PostgreSQL.  MySQL is the most wide used, but I would suggest checking with your hosting provider, if you are not sure which one your server has installed.";
$lStep3_help       = "Ok, here you will have to enter your database information.  Different hosting companies have different setups, usually, if its virutal hosting, the Mysql/Pgsql information will be provided in their control pannel.  <br \><b>Server Name</b> is the hostname or ip of the computer that has Database installed.  If you couldn't find it in your control pannel or in the registration email that was sent from the host, its most likely localhost.  Otherwise its going to be ip inside the host i.e 192.168.0.2.  If host notified you that you have to use a port other than 3306, I reccomend that you will enter it too, such as [hostname]:[port] (Ex. 192.168.0.2:2222).  <br \><b>Database</b> field tells phorum to which particular database to connect. Database usually have to be created if your host allows multiple databases, you can name it anything you want, and make sure you put exact name of that database.  If your host doesn't allow multiple databases, it will most likely be ether same as your login name or same as your domain name (Ex.  If your domain is aeonn.com, your database name would be aeonn_com).  <br \><b>Username</b> and <b>Password</b> are usually supplied by the host, otherwise they are most likely be your ftp/control pannel user and password. <br \><b>Main Table Name</b> is the table where main phorum information is will be stored.  It has to be unique (i.e. there can't other table with the same name.)<br \><b>Upgrade</b> Checkbox is required for eveyone who is upgrading from preveous installations.";
$lStep4_help       = "If you got past Step 3, you are in good shape, your database is working, tables are created.  In this step we will create a user who will have full access to admin features.  All fields are pretty obvious.  If you are upgrading from phorum with user login (3.3 +) you might want to enter your Username and Password, so phorum could check if you are the admin or not, if user you entered is in database, but not admin, Installation will make him one, if he is an admin, Installation will not worry about admins.";
$lStep5_help       = "Last step before you can start adding forums/enabling features.  Here, you should check if the <b>Phorum URL</b> is correct.  It suppose to be the base dir of phorum (if person types it, he would get to the list of phorums or to the list of topics, if it happens to have only one forum).  <br \>Then, if its new install you will need to enter <b>Admin Email Address</b> which will become default email and an e-mail address for user specified earlier. Default email Address will be shown as Admin E-mail throughout the phorum for users to have ability to e-mail you.  <br \><b>Display Name</b> which is also visible only for people who are doing fresh install is the name which will be shown to public in forums and in profile (Ex.: Administrator, Your real name, your nick, etc).  <br \>If it's an upgrade, you will see <b>Phorum Email Address</b> will be shown as Admin E-mail throughout the phorum for users to have ability to e-mail you. It is assumed that admin e-mail and this e-mail are the same; however, you may change it right now, and later in admin.";
// END Install Help text.
?>
