<?php
// lang-file for the admin-area of phorum-3.4.x
// german-version started by thomas seifert (thomas@phorum.org)
// ATTENTION: NOT YET FINISHED!!!

// install-variables:
$ladmin_perm_settingsf = "Die settings/forums.php-Datei (%file%) ist nicht beschreibbar.<br />Bitte fuer den Webserver beschreibbar machen.<br />(z.Bsp. chmod 606 %file% auf Unix/Linux-Rechnern)<br /><br />";
$ladmin_perm_settingsd = "Das settings-Verzeichnis (".$GLOBALS["PHORUM"]["settings_dir"].") ist nicht beschreibbar.<br />Bitte fuer den Webserver beschreibbar machen.<br />(z.Bsp. chmod 606 ".$GLOBALS["PHORUM"]["settings_dir"]." auf Unix/Linux-Rechnern)<br /><br />";
$ladmin_perm_ok = "Rechte sind korrekt gesetzt.<br />";
$ladmin_errors = "<b>Fehler sind aufgetreten. Bitte &uuml;berpr&uuml;fen vor dem fortsetzen.</b><br />";
$lAdmin_Perm_Check = "-<b>Pr&uuml;fe Rechte:</b><br />";
$lAdmin_Perm_Ok    = " is <font class='check_ok'>[OK]</font><br \>";
$lAdmin_Perm_Bad   = " is <font class='check_bad'>[FALSCH]</font><br \><font class=error>Fehlerbehebung: Rechte auf 777 oder 666 &auml;ndern</font><br />";
$lHelp             = "Hilfe";
$lNextStep	   = "-N&auml;chster Schritt-";
$lFinish           = "-Letzter Schritt-";
$lStep1_end        = "Schritt 1: Fertig.<br \>";
$lStep2		   = "Schritt 2: Datenbanktyp.";
$lDBType	   = "Bitte Datenbanktyp ausw&auml;hlen :";
$lStep2_end	   = "Schritt 2: Fertig.<br \>";
$lStep3		   = "Schritt 3: Datenbank-Einstellungen.";
$lStep3_end	   = "<br \>Schritt 3: Fertig.<br \>";
$lStep4            = "Schritt 4: Admin-Benutzer.";
$lStep4_end	   = "Schritt 4: Fertig.<br \>";
$lStep5            = "Schritt 5: Letzter Schritt.";
$lDBServer	   = "Datenbank-Servername:";
$lDBName	   = "Datenbank-Name:";
$lDBUser	   = "Datenbank-Benutzername:";
$lDBPass	   = "Datenbank-Passwort:";
$lPhorumTable      = "Phorum - Name der Haupttabelle:";
$lUpdate           = "Hier klicken, wenn es ein Upgrade ist.<br />Bitte docs/upgrade.txt lesen f&uuml;r die Einstellungen.";
$lAttachmentDir    = "<br />Wenn Sie die Attachments auch aktualisieren wollen dann geben Sie hier bitte den Pfad (im Dateisystem) der alten Installation an.";
$lDBNote	   = "Anmerkung:  Wenn SQL-Safe-Mode aktiv ist auf dem Server, bitte Username und Passwort leer lassen.";
$lAdminUser        = "Benutzername (Login):";
$lAdminPass        = "Passwort:";
$lAdminPass2       = "(Wiederholung)";
$lPhorum_URL       = "Phorum URL:";
$lAdminEmail       = "Admin-EMail-Adresse:";
$lAdminName        = "Benutzername (angezeigter):";
$lErrorFile        = "Konnte die db-Datei nicht finden. Bitte sicherstellen, dass das db-Verzeichnis mit hochgeladen wurde.";
$lErrorDB	   = "Konnte nicht mit der Datenbank verbinden.  Bitte die Einstellungen nochmal &uuml;berpr&uuml;fen.";
$lErrorTables      = "Die Datenbank konnte die Tabellen nicht erstellen. Ist es vielleicht ein Upgrade?";
$lErrorFields      = "Bitte ALLE Felder ausf&uuml;llen";
$lErrorPass        = "Passw&ouml;rter sind nicht identisch.";
$lErrorWrongPass   = "Der Benutzer existiert aber das eingegebene Passwort ist falsch.";
$lErrorDBAdmin     = "Konnte Admin-Benutzer nicht erstellen.  Die Datenbank lieferte: ";
$lErrorURL         = "Dies ist keine g&uuml;ltige URL";
$lErrorEmail       = "Dies ist keine g&uuml;ltige EMail-Adresse";
$lErrorName        = "Der Benutzername ist LEER";
$lDB_Ok            = "Die Datenbankeinstellungen sind OK!<br />\n";
$lDB_Upgrade       = "Bringe Tabellen auf aktuellen Stand...<br />";
$lDB_Create        = "Erstelle Haupttabellen...<br />\n";
$lDB_Create_done   = "<b>Tabellen wurden erstellt!</b><br />\n";
$lUserExists       = "Der Benutzer ist bereits in der Datenbank ...<br />\n";
$lUserIsAdmin      = "$AdminUser ist schon Administrator.<br />\n";
$lUserAdmin        = "$AdminUser war bisher kein Administrator, wurde jetzt aber dazu gemacht :-).<br />\n";
$lAdminCreated     = "Admin-Benutzer erstellt<br />\n";
$lFINAL            = "Herzlichen Gl&uuml;ckwunsch!  Die Installation wurde erfolgreich abgeschlossen.<br /><a href=\"$PHP_SELF\">HIER</a> geht es zum Admin.";
// END Insall Variables;
// START Install Help text:
$lHelpTitle        = "Phorum Installations-Hilfe:";
$lCloseWindow      = "Schlieﬂen.";
$lStep1_help       = "Zuerst, Herzlich Willkomen zur Phorum-Installation.  Ich werde versuchen, Sie durch die Installation zu leiten.  Alles was Sie im ersten Schritt tun m&uuml;ssen, ist die Sprache des Installations-Scripts auszuw&auml;hlen.";
$lStep2_help       = "Am oberen Ende der Seite sehen Sie Ausgaben der letzten Aktion, was also passierte nachdem Sie den Abschicken-Knopf angeklickt haben. Im letzten Schritt hat Phorum die Schreibberechtigungen einiger wichtiger Dateien und Verzeichnisse &uuml;berpr&uuml;ft.  Falls Sie <font class='check_bad'>[FALSCH]</font> sehen bei forums.php oder settings , sollten Sie diese zu 777 oder 666 korrigieren (sollten beide funktionieren aber 666 nicht ueberall). Normalerweise wird dies mit einem FTP-Client wie WS-FTP erledigt, indem man einen Rechtsklick auf die Datei oder das Verzeichnis macht und dort \"Berechtigung\" oder \"chmod\" waehlt.  Sie muessen die Seite nicht neu laden, wenn Sie sicher sind, dass die Berechtigungen stimmen aber falls nicht, dann wuerde Phorum die Rechte nach einem \"aktualisieren\" der Seite nochmal pruefen.<br /><br /> Nachdem Sie die Rechte korrigiert haben, ist es Zeit das Datenbanksystem auszuwaehlen. Phorum unterstuetzt MySQL und PostgreSQL.  MySQL wird meist genutzt aber Sie sollten Ruecksprache mit Ihrem Hoster halten, welches Datenbanksystem installiert und fuer Sie verfuegbar ist.";
$lStep3_help       = "Ok, hier geben Sie Ihre Datenbankeinstellungen an. Bitte beachten Sie, dass eine Datenbank bereits erstellt sein muﬂ. Phorum legt hier nur seine Tabellen an!<br />Meist erhalten Sie diese Datenbankeinstellungen aus dem Einstellungsmenue Ihres Hosters.<br /><b>Datenbank-Servername</b> Ist der Hostname oder die IP-Adresse des Rechners wo die Datenbank installiert ist. Bei einem lokalen Datenbankserver ist dies meist localhost. Anderenfalls ist es auch oft die IP-Adresse z.B. 192.168.0.2 . Falls Ihr Hoster Ihnen ebenso einen anderen Port des Datenbankservers mitgeteilt hat, dann geben Sie diesen bitte wie folgt an [hostname]:[port] (Bsp. 192.168.0.2:2222).  <br \><b>Datenbank-Name</b> Ist der Name der Datenbank in die Phorum installiert werden soll. Bitte exakt (Groﬂ-/Kleinschreibung!) angeben (Oft ist der Datenbankname zusammengesetzt aus dem Usernamen oder dem Hostnamen).  <br \><b>Datenbank-Benutzername</b> und <b>Datenbank-Passwort</b> Werden normalerweise ebenso vom Hoster vorgegeben, sind oft auch die Zugangsdaten des FTP-Accounts. <br \><b>Haupttabellen-Name</b> ist der Name der Tabelle wo Phorum seine Konfigurationsdaten ablegt. Dieser hat eindeutig zu sein (keine andere Tabelle mit diesem Namen)<br \><b>Upgrade</b> fuer ein Update von einer vorherigen Installation notwendig.";
$lStep4_help       = "Wenn Sie ueber Schritt 3 hinausgekommen sind, sieht es schon gut aus. Die Datenbank funktioniert, Tabellen wurden erstellt. In diesem Schritt erstellen Sie einen Benutzer welcher vollen Adminzugriff hat. Die Felder sind recht selbsterklaerend. Falls Sie von einem Phorum mit Adminusern wie 3.3.x upgraden, dann koennen Sie Ihre alten Benutzerdaten angeben, Phorum prueft ob dieser noch Adminbenutzer ist, falls nicht wird er zum Admin gemacht.";
$lStep5_help       = "Dies ist der letzte Schritt bevor Sie Ihre Foren anlegen/verwalten koennen. Hier sollten Sie pruefen ob <b>Phorum URL</b> korrekt ist. Es sollte das Basisverzeichnis der Phorum-Installation sein (wenn man es eingibt, kommt man direkt zum Phorum).  <br />Nun ist noch die  <b>Adminemail-Adresse</b> notwendig, welche die voreingestellte EMail-Adresse und die EMail-Adresse des Adminbenutzers wird. <br \><b>Benutzername (angezeigter)</b> ist der Name des Administrators, der im Profil angezeigt wird.";
// END Install Help text.
?>
