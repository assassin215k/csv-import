#!/bin/bash
args=$@
current_pwd=$PWD
user_id=$(id -u)
docker exec -i nginx /bin/bash -c "nginx $args"
