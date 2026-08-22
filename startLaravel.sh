#!/bin/sh

set -e

echo "Starting Laravel..."

if [ -f /ca-certs/caddy-root.crt ]; then
    echo "Installing Caddy Root CA..."

    cp /ca-certs/caddy-root.crt \
       /usr/local/share/ca-certificates/caddy-root.crt

    update-ca-certificates

    echo "Caddy CA installed."
else
    echo "WARNING: Caddy Root CA not found."
fi

exec "$@"