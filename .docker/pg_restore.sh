#!/bin/bash
args=$@
PGHOST=127.0.0.1
docker exec -i postgres /bin/bash -c "PGPASSWORD=Instance@1 pg_restore $args"
