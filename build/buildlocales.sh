#!/bin/bash

CMDS="msgfmt echo"
 
for i in $CMDS
do
	command -v $i >/dev/null && continue || { echo "$i command not found."; exit 1; }
done


reldir=`dirname $0`
cd $reldir


for D in ../src/public/content/locales/*
do
	if [ -d "$D" ]; then
		msgfmt $D/LC_MESSAGES/klense.po -o $D/LC_MESSAGES/klense.mo 
		continue
	fi
done
