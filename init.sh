#!/bin/bash
sudo rm -rf mysql_data
mkdir mysql_data
docker-compose up --force-recreate
