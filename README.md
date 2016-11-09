# app-dataporten-phpdemo

Demo for authenticating with OAuth through Dataporten. 
This demo requires docker as a prerequisite.

1 - Run docker pull

```$ docker pull uninettno/app-dataporten-phpdemo```

2 - Create environment list file:

```
DATAPORTEN_CLIENTID=******-*****-*****-****-********
DATAPORTEN_CLIENTSECRET=*******-****-****-****-*******
DATAPORTEN_REDIRECTURI=http://localhost/cb.php
```

3 - Run docker
```$ docker run --env-file=YOUR_ENV_FILE -p DESIRED_PORT:80 -t uninettno/app-dataporten-phpdemo```
