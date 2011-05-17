#!/usr/bin/env bash

#
# install script for UNIX systems
# run ../scripts/create_user.sql on a vanilla mysql install

# main dir
dir=$(cd `dirname $0` && pwd)
dir="${dir}/../"
cd ${dir}

# main scripts
cd scripts
./run_sql

cd ..

# p8 stats
cd stats/scripts
./run_sql

cd ../bin
./restart_daemons &

sleep 2

cd ../test
./run

cd ..