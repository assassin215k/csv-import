#!/bin/bash
args=$@
docker exec -i rabbitmq /bin/bash -c "rabbitmqctl $args"
