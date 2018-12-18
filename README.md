# 爱客办公系统 - 免费开源

### 项目介绍
爱客办公是给企业定制开发的，基于laravel 5.5框架开发。因为是企业定制的原因，目前很多模块还需要完善。
##### 演示地址: http://demo.aikeoa.com 帐号：<code>admin</code>, 密码：<code>123456</code>
##### 交流QQ群: 79446405

### 软件架构
基于PHP框架Laravel 5.5 + MySQL

### 安装教程
1. 上传压缩包到目录，这里推荐使用宝塔面板，安装php-7.2、mysql-5.6、redis、nginx
2. 然后使用<code>composer install --no-dev</code>安装依赖
3. 如果要修改前端文件请执行<code>yarn install</code>安装依赖
4. 最后导入<code>database/aikeoa.sql</code>
5. 然后执行<code>php artisan key:generate</code>
6. 修改.env相关配置

### 使用说明
1. 等待添加