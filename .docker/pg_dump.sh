#!/bin/bash
args=$@
PGPASSWORD=Q3qzGwTJbLU97YDv
PGHOST=127.0.0.1
docker exec -i postgres /bin/bash -c "pg_dump $args"
