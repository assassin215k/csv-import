#!/bin/bash

if [[ $EUID -ne 0 ]]; then
  echo "This script must be run as root"
  exit 1
fi

USER="${SUDO_USER:-$USER}"
DIR=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &>/dev/null && pwd)

# Uninstall old versions
apt-get remove docker docker-engine docker.io containerd runc

# Update the apt package index and install packages to allow apt to use a repository over HTTPS
apt-get update
apt-get -y install \
  apt-transport-https \
  ca-certificates \
  curl \
  gnupg \
  lsb-release vim

## Add Docker’s official GPG key
#curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --batch --yes --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
#
## Set up the stable repository
#echo \
#  "deb [arch=amd64 signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu \
#  $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list >/dev/null

# install the latest version of Docker Engine and containerd
apt-get update && apt-get install docker-ce docker-ce-cli containerd.io -y || exit 1

if id -nG "$USER" | grep -qw "docker"; then
  echo $USER belongs to group 'docker'
else
  echo $USER does not belong to 'docker'

  # Create the docker group
  groupadd docker

  # Add your user to the docker group
  usermod -aG docker $USER
fi

## Create the postgresSql user
#if id "postgres" &>/dev/null; then
#    echo postgres user found
#else
#    echo postgres user not found
#    useradd -d /home/postgres -m -s/bin/bash -c postgres postgres
#    passwd postgres
#fi


# Set rights
chown "$USER":"$USER" $DIR -R
find $DIR -name "*.sh" -exec chmod +x {} \;
#find $DIR -name "*.sh" -exec chmod 754 {} \;

# Configure Docker to start on boot
systemctl enable docker.service
systemctl enable containerd.service

# Added alias to short usage of Docker Compose
#echo "alias dc='docker-compose -f $DIR/docker-compose.yml'" >$DIR/.sh_aliases

# Uninstall Docker Compose
rm /usr/local/bin/docker-compose

# Download the current stable release of Docker Compose
curl -L "https://github.com/docker/compose/releases/download/1.24.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose

# Apply executable permissions to the binary
chmod +x /usr/local/bin/docker-compose

# commands for Postgresql
#rm -f /usr/bin/pg_dump && chmod +x $DIR/pg_dump.sh && ln -s $DIR/pg_dump.sh /usr/bin/pg_dump
#rm -f /usr/bin/psql && chmod +x $DIR/psql.sh && ln -s $DIR/psql.sh /usr/bin/psql
#rm -f /usr/bin/pg_restore && chmod +x $DIR/pg_restore.sh && ln -s $DIR/pg_restore.sh /usr/bin/pg_restore

# commands for PHP
rm -f /usr/bin/php && chmod +x $DIR/php.sh && ln -s $DIR/php.sh /usr/bin/php
rm -f /usr/bin/composer && chmod +x $DIR/composer.sh && ln -s $DIR/composer.sh /usr/bin/composer
rm -f /usr/local/bin/composer && ln -s $DIR/composer.sh /usr/local/bin/composer
rm -f /usr/bin/phpunit && chmod +x $DIR/phpunit.sh && ln -s $DIR/phpunit.sh /usr/bin/phpunit

# commands for npm
#rm -f /usr/bin/yarn && chmod +x $DIR/yarn.sh && ln -s $DIR/yarn.sh /usr/bin/yarn
#rm -f /usr/bin/npm && chmod +x $DIR/npm.sh && ln -s $DIR/npm.sh /usr/bin/npm

# commands for Nginx
#rm -f /usr/bin/nginx && chmod +x $DIR/nginx.sh && ln -s $DIR/nginx.sh /usr/bin/nginx

# commands for Node.js
#rm -f /usr/bin/node && chmod +x $DIR/node.sh && ln -s $DIR/node.sh /usr/bin/node

# commands for rabbitMq
#rm -f /usr/bin/rabbitmqctl && chmod +x $DIR/rabbitmqctl.sh && ln -s $DIR/rabbitmqctl.sh /usr/bin/rabbitmqctl

# Stop and remove current containers
docker-compose down
docker rm -f $(docker ps -a -q)
docker volume rm $(docker volume ls -q)
docker-compose up -d --build

while true; do
  read -p "Reboot now? y/[n]" yn
  case $yn in
  [Yy]*)
    reboot
    break
    ;;
  [Nn]*)
    docker ps
    exit
    ;;
  *)
    docker ps
    exit
    ;;
  esac
done
