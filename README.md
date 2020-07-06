# Ushio
汐 - 分布式信息支持系统  

## 项目由来
Ushio 汐 - 取名源自日漫Clannad主人公的女儿。2019.7.18京阿尼第一工作室遭人纵火，最温柔的一群人受到了最残忍的对待。我所能做的，只有将其所传达的精神传递下去。希望借助Ushio系统，去找到真实的自己。去找到真正属于我的责任；去找到真正属于我的幸福；去找到真正值得我全力以赴的那个人，那些人。

## 项目定位
汐，黄昏时刻的涌水。Ushio系统的设想是，可以分布式地弹性地部署在各种设备上，为我的开发行为提供工具集，运行环境，以及维护途径。如果说我所开发的诸种服务相互之间的依赖关系是一张蜘蛛网，那么Ushio系统就是这张网的构架者和维护者。此外，有一些Ushio接口通过API形式，向公众开发。详见[iotcat/ushio-api](https://github.com/iotcat/ushio-api)。

## 实现方法

### 第一代ushio [iotcat/ushio-cn-old:old](https://github.com/IoTcat/Ushio-cn-old/tree/old)
第一次架构完成于2019年7月，是由Ushio用户运行的，集成在cn.yimian.xyz服务器的CentOS7系统上的一系列应用程序。此时，仍然使用主机的文件系统。

### 第二代ushio [iotcat/ushio-linux](https://github.com/IoTcat/ushio-linux)
第二次重构完成于2020年3月，是由Ushio用户运行的，以onedrive作为文件系统，以本机为缓存系统，有独立的系统分区和权限隔离的Linux子系统。

### 第三代ushio [iotcat/ushio-cn](https://github.com/iotcat/ushio-cn)
第三次重构完成于2020年6月，是由root用户运行的，以onedrive作为文件系统，以本机为缓存系统，由docker-compose控制的docker集群。



## 一键部署

目前支持CentOS7的一键脚本部署。实现了可以自动化和无人值守的扩展服务器。比如，如果需要，我现在可以在十分钟内（前提网络好）新填一台日本或其他国家的Ushio服务器，并开始提供服务。脚本详见[iotcat/ushio-centos-ini](https://github.com/IoTcat/ushio-centos-ini)


## 设计理念
- Reliable
- Fast
- Safe
- Integrated
- Extensible
- OpenSource
- Smart

## 观点
 - 考虑到量子计算发展，将主要使用AES256，减少RSA使用


## 项目索引



## 系统架构（第三代）
```yml
version: '3'
services:

# system-level services
#--------------------------------
  nginx:
    image: iotcat/ushio-nginx
    container_name: nginx
    restart: always
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - "/mnt/etc/cn.yimian.xyz/nginx/:/etc/nginx/"
      - "/mnt/:/mnt/"
      - "/var/log/nginx/:/var/log/nginx/"
      - "/home/www/:/home/www/"
    #network_mode: "host"
    depends_on:
      - oneindex
      - php-fpm
      - frps
      - session
      - acg.watch-api
      - serverstatus
      - ushio-win-server
      - danmaku-api
      - coro-api
      - todo-ddl-api
      - upload-api
    networks:
      - default
      - php_net
      - frp_net

  dns:
    image: strm/dnsmasq
    restart: always
    volumes:
      - /mnt/config/dnsmasq/dnsmasq.conf:/etc/dnsmasq.conf
      - /mnt/config/dnsmasq/dnsmasq.d/:/etc/dnsmasq.d/
      - /mnt/config/dnsmasq/hosts.conf:/etc/hosts.conf
    ports:
      - "53:53/udp"
      - "53:53/tcp"
    cap_add:
      - NET_ADMIN
    networks:
      - dns_net

# Database
#----------------------------------
  redis:
    image: redis
    container_name: redis
    restart: always
    volumes:
      - "/tmp/redis/data/:/data/"
    ports:
      - "6379:6379"
    networks:
      - redis_net
  mongo:
    image: mongo
    container_name: mongo
    restart: always
    volumes:
      - "/var/mongo:/data/db"
    networks:
      - mongo_net


# app-level services
# --------------------------------------
  php-fpm:
    container_name: php-fpm
    image: crunchgeek/php-fpm:7.3
    restart: always
    volumes:
      - "/home/:/home/"
      - "/mnt/:/mnt/"
    networks:
      - php_net
  frps:
    image: snowdreamtech/frps
    container_name: frps
    restart: always
    volumes:
      - "/mnt/config/frp/frps.ini:/etc/frp/frps.ini"
    ports:
      - "4480:4480"
      - "4443:4443"
      - "4477:4477"
      - "4400-4440:4400-4440"
    networks:
      - frp_net
  emqx:
    image: emqx/emqx
    container_name: emqx
    restart: always
    ports:
      - "1883:1883"
      - "8083:8083"
      - "8883:8883"
      - "8084:8084"
      - "18083:18083"
    networks:
      - mqtt_net
  monitor:
    #build: https://github.com/iotcat/ushio-monitor.git
    image: iotcat/ushio-monitor
    container_name: monitor
    restart: always
    command: USER=cn.yimian.xyz
    network_mode: "host"


# common apps
# -------------------------------------
  oneindex:
    image: iotcat/oneindex
    container_name: oneindex
    restart: always
    volumes:
      - "/mnt/config/oneindex/:/var/www/html/config/"
    healthcheck:
      test: /bin/bash /healthcheck.sh
      interval: 1m
      timeout: 10s
      retries: 3

  session:
    #build: https://github.com/iotcat/ushio-session.git
    image: iotcat/ushio-session
    container_name: session
    restart: always
    networks:
      - default
      - redis_net
  acg.watch-api:
    #build: https://github.com/iotcat/acg.watch-api.git
    image: iotcat/acg.watch-api
    container_name: acg.watch-api
    restart: always
    volumes:
      - "/mnt/cache/video/:/mnt/cache/video/"
 



# local apps
# ---------------------------------------
  serverstatus:
    image: cppla/serverstatus
    container_name: serverstatus
    restart: always
    volumes:
      - "/mnt/config/serverstatus/config.json:/ServerStatus/server/config.json"
    ports:
      - "35601:35601"
  ushio-win-server:
    #build: https://github.com/iotcat/ushio-win-server.git
    image: iotcat/ushio-win-server
    container_name: ushio-win-server
    restart: always
  kms:
    #build: https://github.com/iotcat/kms-dockcer.git
    image: iotcat/kms
    container_name: kms
    restart: always
    ports:
      - "1688:1688"
  bingimgupdate-opt:
    #build: https://github.com/iotcat/bingUpdateImg-opt.git
    image: iotcat/bingimgupdate-opt
    container_name: bingimgupdate-opt
    restart: always
    volumes:
      - "/mnt/config/token/huaweicloud/:/mnt/config/token/huaweicloud/"
      - "/tmp/:/tmp/"
  danmaku-api:
    #build: https://github.com/iotcat/danmaku-api.git
    image: iotcat/danmaku-api
    container_name: danmaku-api
    restart: always
    depends_on:
      - redis
      - mongo
    networks:
      - default
      - redis_net
      - mongo_net
    environment:
      REDIS_HOST: "redis"
      REDIS_PORT: 6379
      MONGO_HOST: "mongo"
      MONGO_PORT: 27017
      MONGO_DATABASE: "danmaku"
    volumes:
      - /var/log/danmaku-api/app:/usr/src/app/logs
      - /var/log/danmaku-api/pm2:/root/.pm2/logs
  coro-api:
    #build: https://github.com/iotcat/coro-api.git
    image: iotcat/coro-api
    container_name: coro-api
    restart: always
  todo-ddl-api:
    #build: https://github.com/iotcat/todo-ddl-api.git
    image: iotcat/todo-ddl-api
    container_name: todo-ddl-api
    restart: always
    volumes:
      - "/mnt/var/todo-ddl/:/mnt/var/todo-ddl/"
  upload-api:
    #build: https://github.com/IoTcat/upload-api.git
    image: iotcat/upload-api
    container_name: upload-api
    restart: always
    volumes:
      - "/mnt/config/token/huaweicloud/:/mnt/config/token/huaweicloud/"
    tmpfs:
      - /tmp

    
    
# networks setting
# ------------------------------------
networks:
  default:

  dns_net:

  redis_net:

  mongo_net:

  php_net:

  frp_net:

  mqtt_net:

```

## 系统架构（第二代）
```
|Ushio
|
|---|core
|   |
|   |---git.yimian.xyz
|   |---docker.yimian.xyz
|   |---safe.yimian.xyz
|   |---ssl.yimian.xyz
|   |---dbkey.yimian.xyz
|   |---nginx.yimian.xyz
|   |---redis.yimian.xyz
|   |---mqtt.yimian.xyz
|   |---db.yimian.xyz
|   |---backup.yimian.xyz
|
|---|service
|   |
|   |---token.yimian.xyz
|   |---user.yimian.xyz
|   |---api.yimian.xyz
|   |---session.yimian.xyz
|   |---frp.yimian.xyz
|   |---ssr.yimian.xyz
|   |---ota.yimian.xyz
|   |---danmaku.yimian.xyz
|   |---log.yimian.xyz
|
|---|app
|   |
|   |---login.yimian.xyz
|   |---blog.yimian.xyz(www.eee.dog)
|   |---chat.yimian.xyz
|   |---home.yimian.xyz
|   |---shorturl.yimian.xyz(eee.dog)
|   |---cloud.yimian.xyz
|   |---video.yimian.xyz(acg.watch)
|   |---rss.yimian.xyz(www.eee.dog/feed)
|   |---homepage.yimian.xyz
|   |---img.yimian.xyz
|   |---imgbed.yimian.xyz
|   |---msc.yimian.xyz
|   |---resume.yimian.xyz
|   |---iot.yimian.xyz
|   |---settlement.yimian.xyz
|   |---ques.yimian.xyz(问卷系统)
|   |---vpn.yimian.xyz
|   |---data.yimian.xyz
|   |---ai.yimian.xyz
|   |---game.yimian.xyz
|   |---translate.yimian.xyz
|   |---search.yimian.xyz
|   |---report.yimian.xyz
|   |---monitor.yimian.xyz
|   |---pay.yimian.xyz
|

```

## 系统架构（第一代）
```
|Ushio
|
|---|iis
|   |
|   |---Blog(博客，记录生活，引导)=SEO(搜索引擎收录)
|   |   |
|   |   |---YimianReading(追番/阅读记录)
|   |   |---YimianYulu(记录自己的中二语录)
|   |   |---WeiBlog(类似说说)
|   |   |---YimianDev(开发记录)
|   |   |---留言板
|   |   |---RSS
|   |   |---笔记存档系统
|   |
|   |---HomePage(主页，引导作用)=SEO
|   |   |
|   |   |---YimianGuide(导航页)
|   |   |---私人pc浏览器主页
|   |   |---私人phone浏览器主页
|   |   |---Resume(简历)
|   |
|   |---ACG.WATCH(收藏的动漫，电影，电视剧等视频)=SEO
|   |   |
|   |   |---珍藏的蓝光动漫/视频
|   |   |---国内被禁的神作(与b站互补)
|   |
|   |---OVO.RE(图床)=SEO
|   |---YimianMsc(跨域不间断网页音乐服务)(基于网易云音乐)
|   |---YimianCloud(私人网盘+公共分享)(分布式)(内网+外网)
|   |---iot(物联相关)
|   |   |
|   |   |---电子器件管理系统
|   |
|   |---YimianQues(简易的问卷系统)
|   |---YimianPC(笔记本上的简易系统，方便内网/外网访问与文件共享)
|   |---YimianData(提供简易的大数据展示功能)
|   |---YimianChat(简易的在线聊天平台)
|   |---UshioFee(Ushio运行累计耗资统计)
|   |---YimianSSR(翻墙服务管理界面)
|
|---|login(用户管理系统)
|   |
|   |---iis(注册，登录，找回密码页面)
|   |---临时用户系统(随机/QQ/微信/google)
|
|---|ssr/vpn(代理，辅助提供翻墙服务)
|
|---|frp(内网穿透服务)
|
|---|iot
|   |
|   |---ota(固件更新服务)
|   |---MQTT
|
|---|storage
|   |
|   |---SQL(表单，日志)
|   |---NoSQL(网站缓存，视频系列信息)
|   |---对象存储(速度敏感的大文件)
|   |---onedrive(大文件，与yimianPC同步)
|
|---|API
|   |
|   |---mail
|   |---sms
|   |---咕咕机
|   |---pic/moe(图片)
|   |---一言
|   |---dans(弹幕服务)
|   |---图片压制/剪切
|   |---视频压制/转码
|   |---翻译(google translate)
|   |---搜索(站内搜索+综合google)
|
|---|pay
|   |
|   |---alipay
|   |---weichatpay
|   |---bitcon
|   |---paypal
|
|---|ai
|   |
|   |---wiot自定义训练模型
|   |---用户分类(投其所好推送内容)
|   |---简易的聊天机器人
|
|---|game(游戏服务器)
|   |
|   |---战地2
|   |---红色警戒2尤里复仇
|   |---模拟城市5
|
|---|log(日志系统)
|   |
|   |---系统日志
|   |---iis访问日志
|   |---蜘蛛访问日志
|   |---api访问日志
|
|---|monitor(监视/控制系统)
|   |
|   |---iis(相关服务异常时自动引导用户至指定页面，防止google惩罚，同时向站长警告)
|   |---控制各服务开关状态
|   |---证书管理(自动续费)
|
|---|backup(备份系统)
|   |
|   |---github备份
|   |---YimianPC文件备份
|   |---YimianPhone文件备份
|   |---数据库备份
|   |---服务器系统镜像备份
|
|---|report(报表系统)
|   |
|   |---站点每日概况
|
|
```

## 核心依赖

- [CentOS7.6](https://www.centos.org/) 使用CentOS作为操作系统
- [nodeJS](https://github.com/nodejs/node) 使用NodeJS驱动系统
- [php](https://github.com/php/php-src) 使用php搭建iis服务端
- [python](https://github.com/python/cpython) 使用python进行后端数据处理
- [nginx](https://github.com/nginx/njs) 改装nginx作为代理
- [fp](https://github.com/IoTcat/fp) 精确识别用户设备
- [Shadowsocks](https://github.com/shadowsocks/shadowsocks-libev) 流量代理系统
- [typecho](https://github.com/typecho/typecho) 博客框架
- [jquery](https://github.com/jquery/jquery) js网页开发工具
- [dplayer](https://github.com/MoePlayer/DPlayer) 开源弹幕视频播放器
- [aplayer](https://github.com/MoePlayer/APlayer) 开源音乐播放器
- [rsshub](https://github.com/DIYgod/RSSHub) 提供丰富的rss源
- [frp](https://github.com/fatedier/frp) 提供内网穿透
- [docute](https://github.com/egoist/docute) 快速开发说明文档
- [handsome](https://github.com/ihewro/typecho-theme-handsome) typecho博客主题
- [DPlayer-node](https://github.com/MoePlayer/DPlayer-node) 弹幕后端

## 使用的服务

- 华为云 CDN
- 华为云 对象存储
- 华为云 分布式缓存 Redis
- 华为云 云数据库 RDS
- 华为云 弹性云服务器 ECS
- 腾讯云 域名解析
- 腾讯云 云通信 短信
- 腾讯云 域名解析
- 腾讯云 CDN
- 腾讯云 云服务器
- 腾讯云 无服务器云函数
- 腾讯 企业邮箱
- 阿里云 轻量应用服务器
- 阿里云 邮件推送
- Github 代码托管服务
- Vultr VPS
- Godaddy 域名管理
- internetbs 域名管理
- UptimeRobot iis监视服务
- onedrive 文件存储服务
- GoogleAnalytics 站点访问统计
