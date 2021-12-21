#!/bin/sh
args=$@
docker exec -i rabbitmq /bin/sh -c "rabbitmqctl $args"
