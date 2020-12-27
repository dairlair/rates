## Local run

```shell script
# may take some time
docker-compose build 
docker-compose up
```

In another terminal run these commands:

```shell script
# Apply migrations
docker-compose run php php bin/console d:m:m
# Pull rates from sources
docker-compose run php php bin/console rates:pull
```

Now open http://localhost:4200/converter URL and explore the app!

## Pull rates

Just run the following command:
```shell script
php bin/console rates:pull
```

## REST API usage

Obtain rates list:
```shell script
curl http://127.0.0.1:8000/rates
```

Update the rate by ID:

```shell script
curl -X PUT -H "Content-Type: application/json" -d '{"rate": 1234}' http://127.0.0.1:8000/rates/1 
```

And delete rate by ID:
```shell script
curl -X DELETE http://127.0.0.1:8000/rates/1 
```