#!/bin/bash
condition=$1
file=$2
if test $condition == s
then 
  echo "Running PHP Code Sniffer"
  php ./vendor/bin/phpcs --standard=phpcs.xml $file
fi

if test $condition == b
then 
  echo "Running PHP Code Beautifier"
  php ./vendor/bin/phpcbf --standard=phpcs.xml $file
fi

# Use sh sniff.sh s filename.php
