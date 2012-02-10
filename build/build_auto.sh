#!/bin/bash

reldir=`dirname $0`
cd $reldir

./build_htaccess.sh
./build_locales.sh
