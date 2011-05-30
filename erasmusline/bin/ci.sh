#!/usr/bin/env bash
killall -9 php
./start_lampp
rm -rf mutworange
git clone git://mutworange.git.sourceforge.net/gitroot/mutworange/mutworange
cd mutworange
cd erasmusline/bin
./install.sh
