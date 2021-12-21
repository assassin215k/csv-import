#!/bin/sh
args=$@
PGPASSWORD=pass
PGHOST=127.0.0.1
docker exec -i postgres /bin/sh -c "pg_dump $args"
