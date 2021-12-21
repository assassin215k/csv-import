#!/bin/bash 
args=$@
current_pwd=$PWD
docker exec php /bin/bash -c "cd $current_pwd && php $args"
