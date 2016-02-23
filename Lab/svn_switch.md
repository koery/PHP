#!/bin/bash
cd `dirname $0`
if [ -z "$1" ];then
  echo -e "usega svn_switch.sh 1.0.1"
  exit 0
fi
svn switch svn://svn.yedadou.cn:898/openyedadou/tags/release"$1" --force