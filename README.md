# knowledge_city
Requirements
1. Docker

## Step to run locally

1. After cloning repo, just make sure you have Docker running in your machine.
2. Go to the root directory of this repo and run.
```bash
docker-compose up -d
```
3. Once all container are ready, run to install dependencies on php container.
```bash
docker-compose exec php composer install
```
4. Then run, so seed the db.
```bash
docker-compose exec -T db mysql --user=root --password=my_secret_pw_shh < db.sql
```
5. Frontend app should be available at
```bash
http://localhost:8000/
```

Notes:
If you get this error `The server requested authentication method unknown to the client`
just run again 
```bash
docker-compose exec -T db mysql --user=root --password=my_secret_pw_shh < db.sql
```