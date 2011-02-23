<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
  <meta name="keywords" content="Ultima IV, Ultima 4, remake, Lord British, Linux, SDL, exult, party.sav" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><? if (strpos($HTTP_USER_AGENT, "Googlebot") === false) { ?>xu4 - Ultima IV<? } else { ?>xu4 - Ultima IV (Ultima 4)<? } ?></title>
  <link rel="stylesheet" type="text/css" href="css/xu4.css" />
  <link rel="top" href="http://xu4.sourceforge.net/" title="xu4" />
</head>
<body>
<?  if (is_writable("../agentlog/agentlog.txt")) {
      $FILE = fopen("../agentlog/agentlog.txt", "a");
      if ($FILE) {
          if ($HTTP_USER_AGENT != "") {
              fputs($FILE, $HTTP_USER_AGENT);
          } else {
              fputs($FILE, "no user agent");
          }
          if ($HTTP_REFERER != "") { fputs($FILE, " (referer = ".$HTTP_REFERER.")"); }
          if ($REMOTE_ADDR != "") { fputs($FILE, " (ip = ".$REMOTE_ADDR.")"); }
          fputs($FILE, "\n");
          fclose($FILE);
      }
    }
?>
<div class="title"><h3><span class="heading">xu4 - Ultima IV Recreated</span></h3></div>
<div class="menu">
  <span class="selectedmenuitem">Home</span> |
  <span class="menuitem"><a accesskey="2" href="http://sourceforge.net/projects/xu4">Sourceforge project page</a></span> |
  <span class="menuitem"><a accesskey="3" href="screenshots.html">Screenshots</a></span> |
  <span class="menuitem"><a accesskey="4" href="http://sourceforge.net/apps/phpbb/xu4/">Discussion Forum</a></span> |
  <span class="menuitem"><a accesskey="6" href="faq.html">FAQ</a></span> |
  <span class="menuitem"><a accesskey="7" href="download.php">Download</a></span> |
  <span class="menuitem"><a accesskey="8" href="links.html">Links</a></span>
</div>
<div class="divider">
  <img src="images/small-castle.png" width="96" height="32" alt="[picture of Ultima 4 castle]"/>
</div>
<div class="section" id="info">

  <p>XU4 is a remake of the computer game Ultima IV.  The goal is to make it easy and convenient to play this classic on modern operating systems.  XU4 is primarily inspired by the much more ambitious project <a href="http://exult.sourceforge.net">Exult</a>.  XU4 is a cross-platform application thanks to <acronym title="Simple DirectMedia Layer">SDL</acronym>; it's available for Windows, MacOS X, Linux and more.</p>
  <p>This project is currently under development and beta releases are available.  The game is fully playable from beginning to end.  The very latest source code can always be accessed from <a href="http://sourceforge.net/scm/?type=svn&group_id=36225">SVN</a>.  Please post a message in our discussion forum if you are interested in helping.</p>
  <p>A secondary goal of this project is to clearly document the formats of the data files from the original Ultima IV.  This work in progress is available here:  <a href="http://xu4.svn.sourceforge.net/viewvc/xu4/trunk/u4/doc/FileFormats.txt">FileFormats.txt</a></p>
  <p>Please see the <a href="http://xu4.svn.sourceforge.net/viewvc/xu4/trunk/u4/README">README</a> file for information on compiling, installing and running xu4.</p>
  <p>The original Ultima IV is freeware.  <a href="download.php">Download it here!</a></p>
</div>
<p />
<div class="section" id="news">
  <span class="heading">News:</span>
  <ul>
    <li><span class="newsitem"><span class="date">23/Dec/2010</span> - A <a href="http://sourceforge.net/apps/phpbb/xu4/">new bulletin board / forum</a> has been created for xu4. We hope the community comes back (and the spammers do not).</span></li>
    <li><span class="newsitem"><span class="date">02/Oct/2005</span> - The long-awaited third beta is out: see <a href="forum">the forum</a> for the details.  Download it <a href="download.php">here</a>.</span></li>
    <li><span class="newsitem"><span class="date">06/Jan/2005</span> - A second beta is now available, which fixes a few bugs from the first.  Get it <a href="download.php">here</a>.</span></li>
    <li><span class="newsitem"><span class="date">30/Nov/2004</span> - The first beta of <a href="download.php">xu4 1.0</a> of is now available.</span></li>
    <li><span class="newsitem"><span class="date">20/Jul/2004</span> - A <a href="faq.html">FAQ section</a> is now available on the web site. <a href="forum">Let us know</a> if there are any other questions that should listed.</span></li>
    <li><span class="newsitem"><span class="date">24/Feb/2004</span> - Version <a href="download.php">0.9</a> of xu4 is now out.  This version is more complete and stable than ever.  Ultima IV is now fully playable from beginning to end.</span></li>
    <li><span class="newsitem"><span class="date">27/Oct/2003</span> - A new release of xu4 is out: <a href="download.php">Download it here.</a></span></li>
    <li><span class="newsitem"><span class="date">15/Oct/2003</span> - xu4 has a new <a href="forum">discussion forum</a> based on Phorum.  If you have any questions or comments on Ultima IV or xu4, the xu4 users and developers would like to hear from you.</span></li>
    <li><span class="newsitem"><span class="date">10/Oct/2003</span> - A new version of <a href="download.php">Ultima IV for DOS</a> with some minor bugfixes is now available for <a href="download.php">download</a>.  This update fixes some conversation bugs that caused certain pieces of dialogue to be unreachable.</span></li>
    <li><span class="newsitem"><span class="date">29/Jul/2003</span> - <a href="download.php">xu4 0.7</a> has been released.  Please download it, give it a try, and <a href="http://sourceforge.net/tracker/?atid=417353&group_id=36225&func=browse">let us know</a> how it works!</span></li>
    <li><span class="newsitem"><span class="date">04/Jun/2003</span> - xu4 has been <a href="http://inter.zon.free.fr/zaurus_index.html#xu4">ported</a> to the Linux-based <a href="http://www.sharp-usa.com/products/TypeLanding/0,1056,112,00.html">Sharp Zaurus</a> handheld.</span></li>
    <li><span class="newsitem"><span class="date">07/May/2003</span> - A <a href="download.php">Mac OSX build of 0.6</a> is now available.</span></li>
    <li><span class="newsitem"><span class="date">07/Apr/2003</span> - Version 0.6 is <a href="download.php">out</a>.  This time with lots of vendor and combat fixes; xu4 is getting awfully close to being playable!</span></li>
    <li><span class="newsitem"><span class="date">09/Mar/2003</span> - A MacOS X build is now available <a href="download/xu4.tar.gz">here</a>!  Please report any problems you have; hopefully we'll be able to offer MacOS X releases soon.  Also check out the <a href="screenshots.html">MacOS X screenshot</a>.</span></li>
    <li><span class="newsitem"><span class="date">04/Mar/2003</span> - xu4 has been <a href="http://r2k2gate.topcities.com/">ported</a> to the Sega Dreamcast!  And in other news, the xu4 website received <a href="http://sourceforge.net/project/stats/index.php?report=months&group_id=36225">over 10,000 hits</a> last month, our best month yet.</span></li>
    <li><span class="newsitem"><span class="date">17/Feb/2003</span> - Version <a href="http://sourceforge.net/project/showfiles.php?group_id=36225">0.5</a> has just been released.  I've bumped up the version number to 0.5 since my guess is the game is about half done.  Check out this release's mostly working combat!</span></li>
    <li><span class="newsitem"><span class="date">17/Jan/2003</span> - Nightly builds for Windows are available from the <a href="http://xu4.sourceforge.net/download.php">download page</a>.  These builds aren't tested, but are the best way to try out new features in between releases.  As always, <a href="http://sourceforge.net/tracker/?atid=417353&group_id=36225&func=browse">bug reports</a> are welcome.</span></li>
    <li><span class="newsitem"><span class="date">12/Dec/2002</span> - Version <a href="http://sourceforge.net/project/showfiles.php?group_id=36225">0.09</a> is out.  This release has the very beginnings of combat, food vendors, and fixes lots of bugs.</span></li>
    <li><span class="newsitem"><span class="date">17/Nov/2002</span> - Marc Winterrowd has contributed a <a href="http://cvs.sourceforge.net/viewcvs.py/*checkout*/xu4/u4/doc/xu4wincvs.txt?rev=HEAD">tutorial</a> on how to compile the latest CVS source under Windows.</span></li>
    <li><span class="newsitem"><span class="date">08/Oct/2002</span> - <a href="http://sourceforge.net/project/showfiles.php?group_id=36225">Version 0.08</a> is out -- new features include poison, death and meditation.  Progress is being made with spells, too; Awaken, Cure, Dispel, Heal, and Resurrect all work.</span></li>
    <li><span class="newsitem"><span class="date">25/Sep/2002</span> - Ultima IV for DOS is now mirrored <a href="http://sourceforge.net/project/showfiles.php?group_id=36225">here</a>.  Origin generously allows Ultima IV to be freely distributed, and it is required to run xu4.</span></li>
    <li><span class="newsitem"><span class="date">18/Aug/2002</span> - Version <a href="http://sourceforge.net/project/showfiles.php?group_id=36225">0.07</a> has been released.  Please submit <a href="http://sourceforge.net/tracker/?atid=417353&group_id=36225&func=browse">bug reports</a> for problems you would like to see fixed.</span></li>
    <li><span class="newsitem"><span class="date">29/Jul/2002</span> - Claus Windeler writes that BeOS binaries for xu4 are available at <a href="http://www.beemulated.net">http://www.beemulated.net</a>.</span></li>
    <li><span class="newsitem"><span class="date">24/Jul/2002</span> - <a href="http://sourceforge.net/project/showfiles.php?group_id=36225">xu4 0.06</a> has been released, and new <a href="screenshots.html">screenshots</a> have been posted.  New with this version:  walls can no longer be seen through and the moongates work.</span></li>
    <li><span class="newsitem"><span class="date">16/Jul/2002</span> - Added a <a href="links.html">links page</a>.  The CVS code now has support for ships, horses, and balloons.</span></li>
    <li><span class="newsitem"><span class="date">07/Jun/2002</span> - Version 0.05 is finally out.  This release has MIDI background music, the animations on the intro screen, and some very preliminary armor/weapons vendor support.  Download it <a href="http://sourceforge.net/project/showfiles.php?group_id=36225">here</a>.</span></li>
    <li><span class="newsitem"><span class="date">15/May/2002</span> - xu4 version 0.04 has been released.  The introduction story and character creation is the primary new feature in this version.  See the <a href="screenshots.html">screenshots</a> and <a href="http://sourceforge.net/project/showfiles.php?group_id=36225">try it out</a></span></li>
    <li><span class="newsitem"><span class="date">24/Apr/2002</span> - xu4 version 0.03 has been released.  As usual, source, binaries for Windows, and binaries for i386 Linux are available.  There are lots of improvements in this version, including support for the graphics upgrade patch.  Check out the new <a href="screenshots.html">screenshots</a>, download it <a href="http://prdownloads.sourceforge.net/xu4/">here</a></span></li>
    <li><span class="newsitem"><span class="date">16/Apr/2002</span> - Version 0.02 is out.  In addition to source, binaries for Windows and (i386) Linux are available.  Download it <a href="http://prdownloads.sourceforge.net/xu4/">here</a></span></li>
    <li><span class="newsitem"><span class="date">12/Apr/2002</span> - <a href="screenshots.html">Screenshots!</a></span></li>
    <li><span class="newsitem"><span class="date">09/Apr/2002</span> - A <a href="http://prdownloads.sourceforge.net/xu4/">Windows port</a> is now is available.</span></li>
    <li><span class="newsitem"><span class="date">08/Apr/2002</span> - The very first alpha release is out!  Download it <a href="http://prdownloads.sourceforge.net/xu4/">here</a>; <a href="http://sourceforge.net/tracker/?group_id=36225">feedback</a> is welcome!</span></li>
  </ul>
</div>
<p />
<div class="footer" id="host">
  Hosted at&nbsp;
  <a href="http://sourceforge.net"><img src="http://sourceforge.net/sflogo.php?group_id=36225&type=5" width="210" height="62" alt="SourceForge Logo" /></a>
</div>

<div class="footer" id="w3">
  <a href="http://validator.w3.org/check/referer"><img src="http://www.w3.org/Icons/valid-xhtml10"
    alt="Valid XHTML 1.0!" height="31" width="88" /></a>
</div>

</body>
</html>
