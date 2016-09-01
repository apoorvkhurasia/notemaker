#!/bin/bash
set -o nounset
set -e

if [[ -z ${1+x} ]] ; then
	echo "Usage: prep_deploy.sh <directory where the tar ball should be kept>"
	exit 1
fi

build_dir=$1

tar czvf website.tar src deploy.sh dev.deploy.properties prod.deploy.properties
mkdir -p $build_dir
mv website.tar $build_dir
