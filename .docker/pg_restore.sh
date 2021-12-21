#!/bin/sh
args=$@
PGHOST=127.0.0.1
docker exec -i postgres /bin/sh -c "PGPASSWORD=pass pg_restore $args"
