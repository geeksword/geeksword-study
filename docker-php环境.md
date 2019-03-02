## 1. 开启Windows10中的Hyper-V

打开控制面板 - 程序和功能 - 启用或关闭Windows功能，勾选Hyper-V，然后点击确定即可\

## 2. 下载Docker for Windows10

   a. 下载地址：[Docker for Windows10](https://download.docker.com/win/stable/Docker%20for%20Windows%20Installer.exe)
   
   b.  安装启动docker后右下角有个Docker图标 -> 右键图标 -> 选择settings
   
   c. 选择选择`Daemon`，点击`Basic`改为`Advanced`，在下面的输入框中找到`registry-mirrors`属性，修改国内镜像源。（我用的阿里云Docker国内镜像）,最后点击 `Apply` 等待重启

## 3. 使用搜索框，找到Windows PowerShell，以管理员方式运行。

   a. 尽量使用管理员方式，在搭建过程中涉及到从容器中拷贝文件到宿主机，需要权限，并且以后关于docker中的命令操作都会在PowerShell中完成。
   
   b. 使用 `docker version` 查看docker版本信息，安装完成。
   
## 4. 在win10里面创建目录来存放MySql/PHP/Nginx的配置文件及日志文件和存放项目的www文件(后面将会操作映射到本机的目录)

```
D:\wnmp\mysql-conf
D:\wnmp\mysql-log
D:\wnmp\nginx-conf
D:\wnmp\nginx-log
D:\wnmp\php72-conf
D:\wnmp\php72-log
D:\wnmp\www\index.php    index.php 可以写入 phpinfo();
```

## 5. 下载mysql5.6镜像
   a. 输入命令: docker pull mysql:5.6 并且等待下载完成。
   
   b. 下载完成之后，先简单创建一个mysql容器，因为在上一步中创建的MySql/PHP/Nginx等目录中还没有对应的配置及日志文件。需要我们从容器中先拷贝到宿主机目录里。
   
   c. 先创建一个MySql的简单容器，输入命令：
```
docker run -d -p 3306:3306 -e MYSQL_ROOT_PASSWORD=123456 --name mysql mysql:5.6
```

   d. 创建成功之后，进入容器中，`docker exec -ti mysql /bin/bash`（这命令中的mysql是上一句命令中对mysql:5.6进行的重命名）找到对应的配置文件位置和日志文件位置。

   e. 将对应的日志文件及配置文件复制出来
 ```
命令1：docker cp mysql:/var/log/mysql D:\wnmp\mysql-log
命令2：docker cp mysql:/etc/mysql D:\wnmp\mysql-conf
 ```  
因为复制的是整个目录，所以复制出来的地址有点小问题，需要在Win10中调整下。

将mysql-log中的mysql目录里的所有文件复制到mysql-log目录中。也就是所将日志文件的目录深度退一层。然后，将mysql-log中的mysql目录删除，已经没用了。
   f. mysql的配置文件的复制，也同理。全部复制完成之后。我们就需要删除掉这个mysql容器。重新创建带有文件映射的mysql容器
 ```
命令1：docker stop mysql
命令2：docker rm mysql
 ```     
 
   g. 重新创建带有文件映射的mysql容器：（注意：地址映射的时候docker会要求输入宿主机密码，最好设置一个）
 ```
docker run -d -v D:\wnmp\mysql-log:/var/log/mysql/ -v D:\wnmp\mysql-conf:/etc/mysql/ -p 3306:3306 -e MYSQL_ROOT_PASSWORD=123456 --name mysql mysql:5.6
 ```      
 
   h. 创建完成之后，使用命令进入容器，`docker exec -ti mysql /bin/bash`
   
   i. mysql初始化命令，`mysql_secure_installation`,具体内容参考：[https://blog.csdn.net/u013931660/article/details/79443061](https://blog.csdn.net/u013931660/article/details/79443061)
   
   j. mysql容器配置完成，用Navicat验证下是否可以连接。（这里的主机地址要使用宿主机的IP地址，使用`ipconfig`命令，以太网适配器 vEthernet (DockerNAT),IPv4后面的就是宿主机的IP）
   
   
  以上mysql镜像及容器的配置全部完成
  
  ## 6. 下载PHP7.2镜像
   a. 命令：`docker pull php:7.2`
   
   b. 同样要复制PHP的配置文件及日志文件，所以操作方法与mysql相同。复制出来的文件目录深度同样要退一层。
   
   c. PHP的配置文件及日志文件目录地址：
```
配置文件：/usr/local/etc
日志文件：/usr/local/var/log
项目文件：/var/www/html
```
   d. 因为PHP有可能要多版本切换，所以这里的PHP容器重命名最好带有版本标识
```
docker run -d -v D:\wnmp\php-conf:/usr/local/etc -v D:\wnmp\php72-log:/usr/local/var/log -v D:\wnmp\www:/var/www/html -p 9000:9000 --link mysql:mysql --name php72 php:7.2-fpm
```   
   e. 容器创建完成之后，进入容器，`docker exec -ti php72 /bin/bash`
   
   f. 安装PHP的扩展
```
命令1：docker-php-ext-install pdo_mysql
命令2：docker-php-ext-install mysqli
安装mcrypt需要先安装libmcrypt
命令：apt-get install libmcrypt-dev libreadline-dev
再安装libmcrypt: pecl install mcrypt-1.0.1
再安装拓展：docker-php-ext-enable mcrypt
```   
   g. 查看扩展是否已经安装。`php -m`
## 7. 下载Nginx1.8.1
   a. docker pull nginx:1.8.1
   
   b. 因为同样要复制Nginx的配置文件，所以操作方法与mysql相同。复制出来的文件目录深度要退一层。（这里不建议复制nginx的日志文件，因为即使复制了最后在映射完成之后无法启动nginx容器，最好自己创建一份。）
```
配置文件：/etc/nginx/
项目文件：/var/www/html
```   
   c. Nginx容器全命令:
```
docker run -d -p 80:80 -v D:\wnmp\www:/var/www/html -v D:\wnmp\nginx-conf:/etc/nginx/ -v D:\wnmp\nginx-log:/var/log/nginx/ --link php72:phpfpm --name nginx nginx:1.8.1
```
   d. 容器创建完成之后，如果需要VIM，安装方法同mysql，进入容器，`docker exec -ti nginx /bin/bash`
   
   e. 编辑配置文件，（这里就体现出文件映射的好处了，因为不需要进入容器修改配置文件，直接可在windows中操作。）
    
    fastcgi_pass 这里面 ip地址是宿主机的IP地址，同mysql，端口号这里是9000.
    修改配置文件后，重载一下，这里有两种方法实现重载，
    第一种：进入容器中，执行，/etc/init.d/nginx reload
    第二种：在容器外中直接重启nginx容器，docker restart nginx
    
   f. 在windows浏览器中输入localhost，或者127.0.0.1、宿主机的IP地址，查看phpinfo的打印结果。