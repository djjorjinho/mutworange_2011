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

echo "======== RUNNING STATS DAEMONS ======="
cd ../bin
pwd
./restart_daemons &

sleep 2
./local_call statsd_slave '{"method":"etl1","params":{}}'

echo "======== RUNNING TESTS ======="

cd ../../test/
pwd
#./run

cd ../stats/test
pwd
#./run

cd ..