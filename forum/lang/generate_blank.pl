#!/usr/bin/perl
# generates a blank langfile
# call it like that:
# perl generate_blank.pl lang/english.php

unless($ARGV[0]) { 
  print "please call the script with the path of the old-langfile.\n";
  exit;
}
open(IN,$ARGV[0]);
open(OUT,">".$ARGV[0]."_new");

while(<IN>) {
   $line=$_;
   $line_orig=$_;
   if($line =~ /\$.*=\s*\"(.*)\"\;/) {
        $line =~ s/(\$.*)=\s*\"(.*)\"\s*\;/$1 = \"\"; \# $2/;
   }
   print OUT $line;
}

close(IN);
close(OUT);
