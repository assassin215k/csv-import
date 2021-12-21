#!/bin/bash
args=$@
current_pwd=$PWD
user_id=$(id -u)
docker exec -i --user $user_id node /bin/bash -c "cd $current_pwd && node $args"
