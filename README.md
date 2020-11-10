# humstack-dashboard

サーバーなどのリソースの構成管理を行い humstack にデプロイできる。

## usage

### 開発

TODO: Web サーバーも docker で動かすようにする

```
# mysql
docker-compose up -d

# key setting
php artisan key:generate

# migration
# node情報やグループ, ユーザーはSeederで登録されるので事前に編集しておく必要がある
php artisan migrate --seed

# laravel builtin server
php artisan serve
```

### 本番

...

### 環境変数

.env ファイルに指定するか環境変数で定義する

| 環境変数                | 値                    | 用途                      |
| ----------------------- | --------------------- | ------------------------- |
| HUMSTACK_API_SERVER_URL | http://localhost:8080 | humstack apiserver の URL |
