#!/bin/sh
args=$@
current_pwd=$PWD
docker exec php /bin/sh -c "cd $current_pwd && php $args"
