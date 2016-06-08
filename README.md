# app-dataporten-phpdem

Demo for authenticating with OAuth through Dataporten. 
This demo requires docker as a prerequisit.

1 - Run docker pull

```$ docker pull uninettno/app-dataporten-phpdemo```

2 - Create environment list file:

```
DATAPORTEN_CLIENTID=******-*****-*****-****-********
DATAPORTEN_CLIENTSECRET=*******-****-****-****-*******
DATAPORTEN_REDIRECTURI=http://example.org/callback
DATAPORTEN_SCOPES=userid,profile,user-id,email,groups
```

3 - Run docker
```$ docker run --env-file=YOUR_ENV_FILE -p DESIRED_PORT:80 -t uninettno/app-dataporten-phpdemo```
