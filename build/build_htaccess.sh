#!/bin/bash

CMDS="sed"
 
for i in $CMDS
do
	command -v $i >/dev/null && continue || { echo -e "\033[1m\E[31m$i command not found.";tput sgr0; exit 1; }
done


reldir=`dirname $0`
cd $reldir

echo -e '\033[1mBuilding .htaccess...';tput sgr0
read -e -p "Please insert index.php directory, relative to document root, starting and ending with a \"/\": " -i "/" REWRBASE
cp ../templates/htaccess ../src/public/.htaccess
sed -i "s|__REPLACE_REWRITEBASE___|$REWRBASE|g" ../src/public/.htaccess
echo -e '\033[1m\E[32m.htaccess built in src/public/.htaccess';tput sgr0
