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