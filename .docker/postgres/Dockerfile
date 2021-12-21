FROM postgres:9.6
#FROM postgres:11

LABEL maintainer="PostGIS Project - https://postgis.net"

#ENV POSTGIS_MAJOR 2
#ENV POSTGIS_VERSION 3.0.0+dfsg-2~exp1.pgdg100+1

RUN echo "deb http://apt.postgresql.org/pub/repos/apt/ stretch-pgdg main" > /etc/apt/sources.list.d/pgdg.list &&\
    apt-get update && apt-get install wget -y &&\
    wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | \
    apt-key add - &&\
    apt-get update &&\
    apt-get install postgresql-9.6-postgis-2.5 -y

#RUN apt-get update \
#      && apt-cache showpkg postgresql-$PG_MAJOR-postgis-$POSTGIS_MAJOR \
#      && apt-get install -y --no-install-recommends \
#           postgresql-$PG_MAJOR-postgis-$POSTGIS_MAJOR \
#           postgresql-$PG_MAJOR-postgis-$POSTGIS_MAJOR-scripts \
#      && rm -rf /var/lib/apt/lists/*
#
#RUN mkdir -p /docker-entrypoint-initdb.d
#COPY ./initdb-postgis.sh /docker-entrypoint-initdb.d/postgis.sh
#COPY ./update-postgis.sh /usr/local/bin

CMD ["postgres"]
