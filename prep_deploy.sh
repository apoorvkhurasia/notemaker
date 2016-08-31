#!/bin/bash
set -o nounset
set -e

build_dir=$1
tar czvf website.tar src deploy.sh dev.deploy.properties prod.deploy.properties
mkdir -p $build_dir
rm -f $build_dir/website.tar $build_dir/deploy.sh $build_dir/dev.deploy.properties $build_dir/prod.deploy.properties
mv website.tar $build_dir
