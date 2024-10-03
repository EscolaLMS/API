#!/bin/bash
# After 60 seconds the loop will exit
timeout=120

while [ ! -f inited ];
do
  # When the timeout is equal to zero, show an error and leave the loop.
  if [ "$timeout" == 0 ]; then
    echo "ERROR: Timeout while waiting for the file 'inited'"
    exit 1
  fi

  sleep 1

  # Decrease the timeout of one
  ((timeout--))
done