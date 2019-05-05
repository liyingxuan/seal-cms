## [Seal SC](https://www.sealsc.com/) Cloud CMS

#### 部署
> 环境  
操作系统：CentOS 7.4+  
数据库：MySQL 5.7+
PHP：7.2

```bash
$ cd seal-sc
$ chmod -R 777 storage/
$ chmod -R 777 public/

$ vim .env
$ php artisan key:generate 

$ composer install

$ php artisan admin:install
$ php artisan jwt:secret
$ php artisan passport:install
```

配置虚拟服务器

#### 更新
```bash
$ git fetch --all
$ git reset --hard origin/master
```
