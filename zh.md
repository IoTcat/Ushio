# Ushio
汐 - IoTcat的私人分布式信息支持系统     

[English Version](./README.md)

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

### 第四代ushio
第四次重构正在进行中，预计2020.12前完成。在第三代的基础上，使用Kubernetes和Helm取代docker-compose进行进程弹性管理，使用DroneCI和Github进行持续集成，使用Kafka进行跨区域集群的通信。


## 设计理念
- Reliable
- Fast
- Safe
- Integrated
- Extensible
- OpenSource
- Smart

## 开发理念
- 不断重构，迭代发展
- 面向开发
- 考虑到量子计算发展，将主要使用AES256，减少RSA使用



# 架构及标准

## 文件系统

Ushio使用git配合git.yimian.xyz管理配置文件，秘钥，凭据，数据库密码，以及对访问速度要求较高的静态文件。此外，Ushio使用onedrive存储占用空间较大的静态文件，比如视频文件等。Ushio使用Huawei Cloud Storage存储需要在跨区域集群间高速访问的共享文件，比如某些数据文件等。与此同时，Ushio使用主机磁盘存储日志文件，运行缓存等动态文件。   

Ushio文件系统通用结构如下，其中，`/onedrive`目录，`/mnt/var`目录由所有Ushio主机共享，并同步。`/home`目录，`/mnt/etc`目录，`/mnt/config`目录，以及`/mnt/docker`目录使用git作管理以及灾备，方便版本控制以及快速恢复。`/var`和`/tmp`使用系统根目录地址，存储动态文件以及缓存。

```
|Ushio-fs
|
|---|onedrive (使用rclone挂载)
|
|---|mnt (IoTcat/ushio-private)
|   |---config (共享配置文件)
|   |---etc（共享局部配置文件）
|   |---docker（共享docker-compose配置文件）
|   |---var (共享华为云存储)
|   
|---|home(使用git管理)
|   |---www (本地高速网站文件，如php)
|   |---opt (本地开发文件)
|   |---lib (本地共享库)
|   
|---|var
|   |---log (本地日志)
|   |---cache (本地缓存)
|
|---|tmp (临时文件)
```

## 跨区域通信
Ushio集群通过华为云存储, mqtt分布式集群,以及Kafka消息队列（待实现）进行数据交流。 


# 服务列表


## 主机列表

实时列表看[这里](https://monitor.yimian.xyz/)     

### 重要节点

 - `cn.yimian.xyz`: [中国区主服务器](https://github.com/IoTcat/ushio-docker/blob/master/cn.yimian.xyz/docker-compose.yml)
 - `usa.yimian.xyz`: [北美主服务器](https://github.com/IoTcat/ushio-docker/blob/master/usa.yimian.xyz/docker-compose.yml)
 - `home.yimian.xyz`: [灾备服务器](https://github.com/IoTcat/ushio-docker/blob/master/home.yimian.xyz/docker-compose.yml)


## Ushio核心服务

### 网站服务
 - [api.yimian.xyz](https://api.yimian.xyz) 提供公共API接口
 - log.yimian.xyz 提供日志记录接口
 - session.yimian.xyz 提供js-session服务
 - cdn.yimian.xyz CDN加速服务
 - image.yimian.xyz 提供图片获取服务
 - storage.yimian.xyz 提供文件缓存服务
 - danmaku.yimian.xyz 视频弹幕服务


### 用户服务
 - [login.yimian.xyz](https://login.yimian.xyz/) 提供Ushio系统用户登录服务
 - [user.yimian.xyz](https://user.yimian.xyz/) 提供用户个人信息管理页面
 - auth.yimian.xyz 提供Ushio用户系统认证和权限管理服务


### 其它服务
 - dns.yimian.xyz 提供dns服务
 - frp.yimian.xyz 提供内网穿透服务
 - mqtt.yimian.xyz 提供mqtt通信服务
 - docker.yimian.xyz 提供docker镜像托管服务
 - db.yimian.xyz mysql 存储服务
 - ushio-win.yimian.xyz win系统Ushio服务通信接口



## 依赖Ushio的服务

### 公共服务
 - [kms.yimian.xyz](https://github.com/iotcat/kms) 提供kms服务
 - [shorturl.yimian.xyz](https://shorturl.yimian.xyz/) 提供短链服务
 - [acg.watch](https://acg.watch/) acg视频网站
 - [img.yimian.xyz](https://img.yimian.xyz/) 提供图库服务
 - [imgbed.yimian.xyz](https://imgbed.yimian.xyz/) 提供图床服务
 - [share.yimian.xyz](https://share.yimian.xyz/) 提供文件转链接服务
 - [v2ray.yimian.xyz](https://v2ray.yimian.xyz/) Vmess翻墙服务
 - [cp-acc.yimian.xyz](https://cp-acc.yimian.xyz/) 公共账务自动结算系统
 - [mksec.yimian.xyz](https://mksec.yimian.xyz/) 句子背单词网站
 - [proxy.yimian.xyz](https://proxy.yimian.xyz/) 提供HTTP国外文件下载加速服务
 
### 私人服务

 - [www.eee.dog](https://www.eee.dog) 提供博客服务
 - [onedrive.yimian.xyz](https://onedrive.yimian.xyz/) 提供网盘服务
 - [iotcat.me](https://iotcat.me/) iotcat主页
 - [monitor.yimian.xyz](https://monitor.yimian.xyz/) 提供服务器监视服务
 - [ushio.cool](https://ushio.cool/) 提供Ushio主页
 - [guide.yimian.xyz](https://guide.yimian.xyz/) Ushio公共公开服务导航
 - [git.yimian.xyz](https://git.yimian.xyz/) 提供iotcat的Git仓库镜像服务
 - [home.yimian.xyz](https://home.yimian.xyz/) Sola智慧家庭系统
 - [cv.yimian.xyz](https://cv.yimian.xyz/) IoTcat的网页版简历
 - [pay.yimian.xyz](https://pay.yimian.xyz/) IoTcat的支付页面



## 重要模块


### ushio-session
基于`iotcat/js-session`提供session服务。
详见[iotcat/session](https://github.com/iotcat/session)

### ushio-js
提供网页端的ushio接口，提供aplayer, fp, js-session, tips灯服务。详见[iotcat/ushio-js](https://github.com/iotcat/ushio-js)

### ushio-nginx 
在nginx源码基础上修改而成的反代软件，其实主要实现的效果就是使得http header中的server是`Ushio/1.16.1`。。之后如果有能力我会进一步优化nginx。详见[iotcat/ushio-nginx](https://github.com/iotcat/ushio-nginx)

### ushio-dns
使用dnsmasq，提供dns服务。如需使用，请将您的dns主机地址修改为`114.116.85.132`,`80.251.216.25`。

### redis数据库
为本地提供高速缓存服务。

### mongoDB数据库
提供分布式文件存储。目前主要是由弹幕模块使用。

### php-fpm
使用`crunchgeek/php-fpm:7.3`镜像，提供php网络发布服务。

### frps内网穿透
为内网主机提供内网穿透服务。

### emqx mqtt
提供mqtt服务。

### ushio-monitor
基于serverstatus 提供服务器监控服务。
详见[https://monitor.yimian.xyz](https://monitor.yimian.xyz)

### oneindex
基于oneindex提供onedrive文件发布服务。


### ushio-log
提供日志服务。



# 部署方法


## 脚本部署

目前支持CentOS7的一键脚本部署。实现了可以自动化和无人值守的扩展服务器。比如，如果需要，我现在可以在十分钟内（前提网络好）新填一台日本或其他国家的Ushio服务器，并开始提供服务。脚本详见[iotcat/ushio-centos-ini](https://github.com/IoTcat/ushio-centos-ini)




------------------------------

---------------------------------

# 历史

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
