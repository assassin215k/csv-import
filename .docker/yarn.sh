#!/bin/sh
args=$@
current_pwd=$PWD
user_id=$(id -u)
docker exec -i --user $user_id node /bin/sh -c "cd $current_pwd && yarn $args"
