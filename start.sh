#!/bin/sh

if [ "$1" ]
  then
    if [ $1 == 'migrate-only' ]
      then
        php artisan migrate:fresh --seed
        exit 0
    fi
fi

npx kill-port 9000
npx kill-port 5175

if [ "$1" ]
  then
    if [ $1 == 'migrate' ]
      then
        php artisan migrate:fresh --seed
    fi
fi

php artisan optimize:clear

php artisan serve --host=127.0.0.1 --port=9000 -q 1>NUL 2>NUL &

npm run dev --silent -- --port 5175 &

sleep 3s

start chrome http://127.0.0.1:9000
