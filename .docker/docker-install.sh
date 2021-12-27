#!/bin/bash

if [[ $EUID -ne 0 ]]; then
  echo "This script must be run as root"
  exit 1
fi

USER="${SUDO_USER:-$USER}"
DIR=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &>/dev/null && pwd)

# clear and write aliases
function writeAliases() {
  echo "" >/home/$USER/.docker_aliases
#  echo alias psql="${DIR}/psql.sh" >> /home/$USER/.docker_aliases
#  echo alias pg_dump="${DIR}/pg_dump.sh" >> /home/$USER/.docker_aliases
#  echo alias pg_restore="${DIR}/pg_restore.sh" >> /home/$USER/.docker_aliases

  echo alias php="${DIR}/php.sh" >> /home/$USER/.docker_aliases
  echo alias composer="${DIR}/composer.sh" >> /home/$USER/.docker_aliases
#  echo alias phpcs="${DIR}/phpcs.sh" >> /home/$USER/.docker_aliases
#  echo alias phpcbf="${DIR}/phpcbf.sh" >> /home/$USER/.docker_aliases

#  echo alias node="${DIR}/node.sh" >> /home/$USER/.docker_aliases
#  echo alias yarn="${DIR}/yarn.sh" >> /home/$USER/.docker_aliases
#  echo alias npm="${DIR}/npm.sh" >> /home/$USER/.docker_aliases

#  echo alias nginx="${DIR}/nginx.sh" >> /home/$USER/.docker_aliases

#  echo alias rabbitmqctl="${DIR}/rabbitmqctl.sh" >> /home/$USER/.docker_aliases

  echo "Written aliases"
}

function makeExecutable() {
  find $DIR -name "*.sh" -exec chmod +x {} \;

  echo "sh scripts made executable"
}

# attach .docker_aliases
function aliases() {
  if ! grep -q ". ~/.docker_aliases" /home/$USER/.bashrc; then
    if [ ! -f /home/$USER/.docker_aliases ]; then
      touch /home/$USER/.docker_aliases

      chown $USER:$USER /home/$USER/.docker_aliases
    fi

    echo "
if [ -f ~/.docker_aliases ]; then
    . ~/.docker_aliases
fi
" >>/home/$USER/.bashrc

  fi
  writeAliases
}

aliases

makeExecutable


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
echo "alias phpunit='$DIR/../vendor/phpunit/phpunit/phpunit'" > $DIR/.sh_aliases

# Uninstall Docker Compose
rm /usr/local/bin/docker-compose

# Download the current stable release of Docker Compose
curl -L "https://github.com/docker/compose/releases/download/1.24.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose

# Apply executable permissions to the binary
chmod +x /usr/local/bin/docker-compose

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
