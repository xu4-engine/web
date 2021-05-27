#!/bin/sh

echo "Updating from Git..."
git pull origin master

echo "Fixing permissions"
chgrp -R xu4 .
chmod -f -R a+r .
