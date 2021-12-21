#!/bin/sh
args=$@
current_pwd=$PWD
user_id=$(id -u)
docker exec -i nginx /bin/sh -c "nginx $args"
