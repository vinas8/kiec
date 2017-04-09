kknyga-web
============

## Projekto paleidimas

```bash
docker-compose up -d
docker-compose exec fpm composer install --prefer-dist -n
docker-compose run npm npm install
docker-compose run npm gulp
```
