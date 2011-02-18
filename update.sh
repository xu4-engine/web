#!/bin/sh

echo "Updating from SVN..."
svn update

echo "Fixing permissions"
chgrp -R xu4 .
chmod -f -R a+r .

