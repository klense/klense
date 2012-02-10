#!/bin/bash

CMDS="msgfmt echo"
 
for i in $CMDS
do
	command -v $i >/dev/null && continue || { echo -e "\033[1m\E[31m$i command not found.";tput sgr0; exit 1; }
done


reldir=`dirname $0`
cd $reldir

echo -e '\033[1mBuilding *.mo files...';tput sgr0
for D in ../src/public/content/locales/*
do
	if [ -d "$D" ]; then
		msgfmt $D/LC_MESSAGES/klense.po -o $D/LC_MESSAGES/klense.mo 
		continue
	fi
done
echo -e '\033[1m\E[32m*.mo files built.';tput sgr0