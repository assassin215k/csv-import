#!/bin/bash
args=$@
PGPASSWORD=Instance@1
PGHOST=127.0.0.1
docker exec -i postgres /bin/bash -c "psql $args"
