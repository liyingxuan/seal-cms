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

$ php artisan apidoc:generate  # 生成API文档
```

#### 更新
```bash
$ git fetch --all
$ git reset --hard origin/master

$ php artisan apidoc:generate  # 更新API文档
```

#### 开发
```php


```

#### 文档
> 主要是注释要写清，做自动生成。  

```php
 *
 * @group Users
 * 
 * @bodyParam name string required Some description.
 * @bodyParam email string required Some description.
 * @bodyParam password string required Some description.
 * @bodyParam c_password string required Some description.
 *
 * @response {
 *     "token": "2019xxx",
 *     "email": "sealsc@sealsc.com"
 * }
 *
```

```bash
$ php artisan apidoc:generate  # 更新API文档
```