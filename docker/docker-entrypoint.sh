#!/usr/bin/env bash

set -e
set -u
set -o pipefail



###
### Startup
###
echo "info" "Starting Wellms" 
exec "${@}"
