<?php

    $lForumDown        = ""; # Our forums are down
    $lForumDownNotice  = ""; # Our Forum is currently down for maintenance.  It will be available again shortly.<p>We are sorry for the inconvenience.
    $lNoAuthor         = ""; # You must supply an author.
    $lNoSubject        = ""; # You must supply a subject.
    $lNoBody           = ""; # You must supply a message.
    $lNoEmail          = ""; # You did not enter a valid e-mail address.  An e-mail address is not required.<br />If you do not wish to leave your e-mail address please leave the field blank.
    $lNoEmailReply     = ""; # When requesting to be e-mailed replies, you must supply a valid e-mail address.
    $lModerated        = ""; # Moderated forum.  All posts are reviewed before posting.
    $lModeratedMsg     = ""; # This is a moderated forum.  Your post has been e-mailed to the moderator and will be reviewed as soon as possible.
    $lReplyMessage     = ""; # Reply To This Message
    $lReplyThread      = ""; # Reply To This Topic
    $lWrote            = ""; # wrote
    $lQuote            = ""; # Quote
    $lFormName         = ""; # Your Name
    $lFormEmail        = ""; # Your E-mail
    $lFormSubject      = ""; # Subject
    $lFormAttachment   = ""; # Attachment
    $lInvalidFile      = ""; # The attachment cannot contain spaces or weird characters.
    $lInvalidType      = ""; # Only files of the following type are allowed: 
    $lInvalidSize1     = ""; # The following attachment is too large: 
    $lInvalidSize2     = ""; # Attachments must be smaller than 
    $lFileExists       = ""; # A file with this name has already been uploaded. Please rename your attachment and try again.
    $lCannotAttach     = ""; # You cannot attach anything to this message.  Either you are not the author of the message or the maximum number of attachments have been attached.
    $lFormPost         = ""; # Post
    $lFormAttach       = ""; # Add Attachments
    $lFormImage        = ""; # Image
    $lAvailableForums  = ""; # Available Forums
    $lNoActiveForums   = ""; # There are no active forums.
    $lCollapseThreads  = ""; # Collapse Threads
    $lViewThreads      = ""; # View Threads
    $lReadFlat         = ""; # Flat View
    $lReadThreads      = ""; # Threaded View
    $lForumList        = ""; # Forum List
    $lMarkRead         = ""; # Mark All Read
    $lUpLevel          = ""; # Up One Level
    $lGoToTop          = ""; # Go to Top
    $lStartTopic       = ""; # New Topic
    $lSearch           = ""; # Search
    $lSearchAllWords   = ""; # All Words
    $lSearchAnyWords   = ""; # Any Word
    $lSearchPhrase     = ""; # Exact Phrase
    $lSearchLast30     = ""; # Last 30 Days
    $lSearchLast60     = ""; # Last 60 Days
    $lSearchLast90     = ""; # Last 90 Days
    $lSearchLast180    = ""; # Last 180 Days
    $lSearchAllDates   = ""; # All Dates
    $lSearchThisForum  = ""; # Search This Forum
    $lSearchAllForums  = ""; # Search All Forums
    $lForum            = ""; # forum
    $lBigForum         = ""; # Forum
    $lNewerMessages    = ""; # Newer Messages
    $lOlderMessages    = ""; # Older Messages
    $lNew              = ""; # new
    $lTopics           = ""; # Topics
    $lAuthor           = ""; # Author
    $lDate             = ""; # Date
    $lLatest           = ""; # Last Post
    $lReplies          = ""; # Replies
    $lGoToTopic        = ""; # Go to Topic
    $lGoToPost         = ""; # Go to Post
    $lPreviousMessage  = ""; # Previous Message
    $lNextMessage      = ""; # Next Message
    $lPreviousTopic    = ""; # Newer Topic
    $lNextTopic        = ""; # Older Topic
    $lSearchResults    = ""; # Search Results
    $lSearchTips       = ""; # Search Tips
    $lTheSearchTips    = ""; # AND is the default. That is, a search for <strong>dog</strong> and <strong>cat</strong> returns all messages that contain those words anywhere.<p>QUOTES (\") allow searches for phrases. That is, a search for <strong>\"dog cat\"</strong> returns all messages that contain that exact phrase, with space.<p>MINUS (-) eliminates words. That is, a seach for <strong>dog</strong> and <strong>-cat</strong> returns all messages that contain <strong>dog</strong> but not <strong>cat</strong>. You can MINUS a phrase in QUOTES, like <strong>dog -\"siamese cat\"</strong>.<p>The engine is not case-sensitive and searches the title, body, and author.
    $lNoMatches        = ""; # No matches found :(
    $lMessageBodies    = ""; # Message Bodies (slower)
    $lMoreMatches      = ""; # More Matches
    $lPrevMatches      = ""; # Previous Matches
    $lLastPostDate     = ""; # Last Post
    $lNumPosts         = ""; # Posts
    $lForumFolder      = ""; # Forum Folder
    $lEmailMe          = ""; # E-mail replies to this thread, to the address above.
    $lUseSig           = ""; # Add my signature to this post.
    $lModerator        = ""; # Moderator
    $lMember           = ""; # Member
    $lEmailAlert       = ""; # You must enter a valid e-mail address if you want replies e-mailed to you.
    $lViolationTitle   = ""; # Sorry...
    $lViolation        = ""; # Posting is not available because your IP Address, the name you entered, or the e-mail you entered was banned.  This may not be because of you.  Try another name and/or e-mail.  If you still cannot post, contact <a href=\"mailto:$DefaultEmail\">$DefaultEmail</a> for an explanation.
    $lNotFound         = ""; # The message you requested could not be found.  For assistance contact an administrator of this phorum
    $lLoginCaption     = ""; # User Login
    $lLogIn            = ""; # Log In
    $lLogOut           = ""; # Log Out
    $lRegisterCaption  = ""; # Registration
    $lRegisteredName   = ""; # The name you entered is already registered by another user.  If you are that user, please login.
    $lUserName         = ""; # User Name
    $lPassword         = ""; # Password
    $lLoginLink        = ""; # Already Registered? Login Here
    $lRegisterLink     = ""; # Need a Login? Register Here
    $lRegister         = ""; # Register
    $lLogin            = ""; # Login
    $lWebpage          = ""; # Homepage
    $lImageURL         = ""; # Image URL
    $lSignature        = ""; # Signature
    $lLoginError       = ""; # Your username and password did not match.  Please try again.
    $lRegisterThanks   = ""; # Thank you for registering.
    $lRegisterReturn   = ""; # Click here to return to the forums.
    $lDupUsername      = ""; # That user name is already in use.
    $lDupName          = ""; # That name is already in use
    $lDupEmail         = ""; # Your e-mail is already used by someone else
    $lNoPassMatch      = ""; # Passwords Don't Match
    $lPassAgain        = ""; # Again
    $lFillInAll        = ""; # You didn't fill out all mandatory fields
    $lDelMessageWarning = ""; # You are about to delete this\\nmessage and all it's children.\\n Do you want to continue?
    $lEditMyPost       = ""; # Edit My Post
    $lPostEdited       = ""; # Post Edited
    $lCantEdit         = ""; # You are not allowed to edit this post.

    //Profile Stuff
    $lUserProfile      = ""; # User Profile
    $lMyProfile        = ""; # My Profile
    $lName             = ""; # Name
    $lEmail            = ""; # E-mail
    $lEditProfile      = ""; # Edit Profile
    $lUpdateProfile    = ""; # Update Profile
    $lProfileUpdated   = ""; # Profile Updated
    $lEditProfileErrorTitle = ""; # Error/Info
    $lEditProfileError = ""; # Sorry you are not logged in and/or it's not your profile.  You can't edit it.
    $lBack             = ""; # Back
    $lNoUser           = ""; # There is no user with that Id
    $lNoId             = ""; # Please enter Id of the user
    $lNewPass          = ""; # New $lPassword
    $lPosts            = ""; # Posts
    $lModerators       = ""; # Moderator-Functions:
    $lModEdit          = ""; # Edit Post
    $lModDelete        = ""; # Delete Post
    $lEditPost         = ""; # Edit Post
    $lFormUpdate       = ""; # Update Post
    $lRequiredFields   = ""; # Fields marked with * are required.
    $lRegistrationCensor  = ""; # Some words you used are in our censor list.  Please remove them.
    $lForgotPass       = ""; # Forgot Your Password?
    $lLostPassExplain  = ""; # Enter your email address or user name below and a new password will be sent to the email address associated with your profile.
    $lEmailOrUser      = ""; # Email or Username
    $lSubmit           = ""; # Submit
    $lOf               = ""; # of
    $lLanguage         = ""; # Language

    $lUsedInPosts      = ""; # used in your posts
    $lHideEmail        = ""; # Hide my email from other users
    $lRepeat           = ""; # repeat
    $lRealName         = ""; # Real Name
    $lOnlyToChange     = ""; # Only enter to change it.  Otherwise leave blank.


    $lFormPreview      = ""; # Preview
    $lThreadClosed     = ""; # This thread is closed
    $lModCloseThread   = ""; # Close this thread
    $lModOpenThread    = ""; # (Re)Open this thread

    $lModMove          = ""; # Move Thread
    $lModMoveThreads   = ""; # Move selected thread to forum
    $lModMoveNoForums  = ""; # There's no other forum available, you cannot move threads!
    $lModHide          = ""; # Hide Message (and answers)

    $lNewPassMessage   = ""; # Your password has been updated to the new one you just entered.<br /><br />It would be a good idea to change your password to something you prefer.<br />You can enter a new password of your choice below and click '$lUpdateProfile'
    $lNewPassword      = ""; # New Password
    $lNewPassBody      = ""; # Someone (hopefully you) has requested a new password for your forum account.  If it was not you, you can ignore this email and continue using your old password.\n\nIf it was you, here is your new login for the forums.
    $lNewPassChange    = ""; # Please change the password when you login.
    $lNewPassMailed    = ""; # Your new password has been mailed to your email address.
    $lNewPassError     = ""; # The username/email address you entered could not be found.  Please try again.

    $lRegPassBody      = ""; # Thank you for registering with our forums.  Below you will find your\nusername and temporary password.  Please go to\n$forum_url/login.$ext and login.
    $lRegPassChange    = ""; # Please change the password when you login.
    $lRegPassMailed    = ""; # Your password has been mailed to your email address.
    $lRememberLogin    = ""; # Remember my login

    // This function takes a date string in the ANSI format
    // (YYYY-MM-DD HH:MM:SS) and formats it for display.
    // The default is for US English, MM-DD-YY HH:MM.
    // See http://www.php.net/manual/function.date.php
    // for options on the date() formatting function.

    function date_format($datestamp){
        global $TimezoneOffset;
        $datestamp=trim($datestamp);
        if (empty($datestamp) || $datestamp=="0000-00-00") {
            $datestamp  = ""; # 0000-00-00 00:00:00
        }
        list($date,$time) = explode(" ",$datestamp);
        list($year,$month,$day) = explode("-",$date);
        list($hour,$minute,$second) = explode(":",$time);
        $hour = $hour + $TimezoneOffset;
        $tstamp = mktime($hour,$minute,$second,$month,$day,$year);
        $sDate = date("m-d-y H:i",$tstamp);
        return $sDate;
    }

?>
