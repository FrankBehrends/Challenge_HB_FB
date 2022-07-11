# Challenge_HB_FB
 
# Install the Lumen-app

```
# 1. Move to the lumen-app folder
cd lumen-app

# 2. Rename .env.example in .env
mv .env.example .env

# 3. Make an Composer Update
composer update

# 4. Start and build the docker container
docker-compose --env-file .env up --build

# 5. Close the container and restart in Docker-App
control+c

# 6. Look up the name of the Doker Container, for me it was lumen-app-lumen-1
docker ps -a --format "table {{.ID}}\t{{.Names}}“

# 7. Migrate the Database
docker exec lumen-app-lumen-1 php artisan migrate

# 8. Create the admin user
docker exec lumen-app-lumen-1 php artisan db:seed --class=UserSeeder
```
# Install vuejs-app
```
# 1. Move to the vuejs-app folder
cd vuejs-app

# 2. Install npm-packages
npm install

# 3. Start and build the docker container
docker-compose up --build

# 4. Close the container and restart in Docker-App
control+c

# 5. Webseite aufrufen
http://localhost:8080/
```