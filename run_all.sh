#!/bin/bash

# Kill any process using ports 8005, 8080, 4200
for port in 8005 8080 4200; do
  pid=$(lsof -ti tcp:$port)
  if [ -n "$pid" ]; then
    echo "Killing process on port $port (PID $pid)"
    kill -9 $pid
  fi
done

# Start Laravel backend
cd "$(dirname "$0")"
echo "Starting Laravel backend on 127.0.0.1:8005..."
php artisan serve --host=127.0.0.1 --port=8005 > backend.log 2>&1 &

# Start Angular 2+ frontend
cd frontend_Angular
if [ ! -d node_modules ]; then
  echo "Installing Angular 2+ dependencies..."
  npm install
fi
echo "Starting Angular 2+ frontend on port 4200..."
npm start > ../frontend_ng.log 2>&1 &


# Start AngularJS 1.x frontend
cd ../
if [ -d frontend ]; then
  echo "Starting AngularJS 1.x frontend on port 8080..."
  php -S localhost:8080 -t frontend frontend/serve.php > ../frontend_js.log 2>&1 &
else
  echo "[Warning] frontend directory not found in frontend, skipping AngularJS 1.x server."
fi

cd ..
echo "All services started!"
echo "Backend:        http://127.0.0.1:8005"
echo "Angular 2+:     http://localhost:4200"
echo "AngularJS 1.x:  http://localhost:8080" 