# Ushio
IoTcat's personal distributed information support system    

[简体中文（推荐）](./zh.md)

## What is the meaning of Ushio
Ushio named after the daughter of the protagonist of Clannad. On July 18, 2019, the first studio of Kyoto Animation was set on fire, and the most tender group of people received the most cruel treatment. All I can do is pass on the spirit these people conveys. I hope to find my true self with the help of Ushio system. To find the responsibility that truly belongs to me; to find the happiness that truly belongs to me; to find the person who is truly worthy of my all-out efforts.

## Project positioning
The Chinese meaning of word Ushio is the gushing water at dusk. The idea of the Ushio system is that it can be deployed on various devices in a distributed and flexible manner, providing a tool set, operating environment, and maintenance path for my development activities. If the interdependence between the various services I developed is a spider web, then the Ushio system is the architect and maintainer of this web. In addition, some Ushio interfaces are developed and shared to the public through API. See [iotcat/ushio-api](https://github.com/iotcat/ushio-api) for details.

## Implementation

### The first generation of ushio [iotcat/ushio-cn-old:old](https://github.com/IoTcat/Ushio-cn-old/tree/old)
The first architecture was completed in July 2019. It was integrated a series of applications on the CentOS7 system of the cn.yimian.xyz server. At this time, the local file system of the host is still used.

### The second generation ushio [iotcat/ushio-linux](https://github.com/IoTcat/ushio-linux)
The second reconstruction was completed in March 2020. It was run by a Ushio user, with onedrive as the file system and the local storage as the cache system, with independent system partitions and a Linux subsystem with isolated permissions.

### The third generation ushio [iotcat/ushio-cn](https://github.com/iotcat/ushio-cn)
The third reconstruction was completed in June 2020. It was run by the root user, with onedrive as the file system, the local storage as the cache system, and a docker cluster controlled by docker-compose.

### The fourth generation ushio
The fourth reconstruction is in progress and is expected to be completed before 2020.12. On the basis of the third generation, Kubernetes and Helm are used to replace docker-compose for elastic process management, DroneCI and Github are used for continuous integration, and Kafka is used for cross-regional cluster communication.


## Design Objective
- Reliable
- Fast
- Safe
- Integrated
- Extensible
- OpenSource
- Smart

## Development Method
- Continuous reconstruction, iterative development
- Development-oriented
- Taking into account the development of quantum computing, AES256 will be mainly used and the use of RSA will be reduced


# Architecture and standards

## File system

Ushio uses git with git.yimian.xyz to manage configuration files, secret keys, credentials, database passwords, and static files that require high access speed. In addition, Ushio uses onedrive to store static files that take up a lot of space, such as video files. Ushio uses Huawei Cloud Storage to store shared files that require high-speed access between cross-regional clusters, such as certain data files. At the same time, Ushio uses the host disk to store log files, run caches and other dynamic files.

The general structure of the Ushio file system is as follows, where the `/onedrive` directory and the `/mnt/var` directory are shared and synchronized by all Ushio hosts. The `/home` directory, the `/mnt/etc` directory, the `/mnt/config` directory, and the `/mnt/docker` directory use git for management and disaster recovery, which facilitates version control and quick recovery. `/var` and `/tmp` use the system root directory address to store dynamic files and cache.

```
|Ushio-fs
|
|---|onedrive (mount using rclone)
|
|---|mnt (IoTcat/ushio-private)
|   |---config (Shared configuration file)
|   |---etc (shared local configuration file)
|   |---docker (sharing docker-compose configuration file)
|   |---var (Share Huawei Cloud Storage)
|
|---|home (with git management)
|   |---www (local high-speed website files, such as php)
|   |---opt (local development file)
|   |---lib (local shared library)
|
|---|var
|   |---log (local log)
|   |---cache (local cache)
|
|---|tmp (temporary file)
```
## Cross-regional communication
The Ushio cluster communicates data through Huawei cloud storage, mqtt distributed cluster, and Kafka message queue (to be implemented).


# Service list


## Host list

See the real-time list [here](https://monitor.yimian.xyz/)

### Important node

 - `cn.yimian.xyz`: [Main Server in China](https://github.com/IoTcat/ushio-docker/blob/master/cn.yimian.xyz/docker-compose.yml)
 - `usa.yimian.xyz`: [North American Main Server](https://github.com/IoTcat/ushio-docker/blob/master/usa.yimian.xyz/docker-compose.yml)
 - `home.yimian.xyz`: [Disaster Recovery Server](https://github.com/IoTcat/ushio-docker/blob/master/home.yimian.xyz/docker-compose.yml)


## Ushio Core Service

### Web Services
 - [api.yimian.xyz](https://api.yimian.xyz): provides public API interface
 - `log.yimian.xyz`: provides logging interface
 - `session.yimian.xyz`: provides js-session service
 - `cdn.yimian.xyz`: CDN acceleration service
 - `image.yimian.xyz`: provides image acquisition service
 - `storage.yimian.xyz`: provides file caching services
 - `danmaku.yimian.xyz`: video barrage service


### User Services
 - [login.yimian.xyz](https://login.yimian.xyz/): Provide Ushio system user login service
 - [user.yimian.xyz](https://user.yimian.xyz/): Provide user personal information management page
 - `auth.yimian.xyz`: provides Ushio user system authentication and authority management services

### IoT Services
 - `mqtt.yimian.xyz`: provides mqtt communication services
 - `ota.yimian.xyz`: provides OTA service for IoT nodes

### Other services
 - `dns.yimian.xyz`: provides dns service
 - `frp.yimian.xyz`: provides intranet penetration service
 - `docker.yimian.xyz`: provides docker image hosting service
 - `db.yimian.xyz`: mysql storage service
 - `ushio-win.yimian.xyz`: win systemd Ushio service communication interface



## Services relying on Ushio

### Public Service
 - [kms.yimian.xyz](https://github.com/iotcat/kms) Provide kms service
 - [shorturl.yimian.xyz](https://shorturl.yimian.xyz/) provides short-chain services
 - [acg.watch](https://acg.watch/) acg video website
 - [img.yimian.xyz](https://img.yimian.xyz/) Provide gallery service
 - [imgbed.yimian.xyz](https://imgbed.yimian.xyz/) Provide image bed service
 - [share.yimian.xyz](https://share.yimian.xyz/) Provide file transfer link service
 - [v2ray.yimian.xyz](https://v2ray.yimian.xyz/) Vmess circumvention service
 - [cp-acc.yimian.xyz](https://cp-acc.yimian.xyz/) Automatic public accounting system
 - [mksec.yimian.xyz](https://mksec.yimian.xyz/) Sentence memorization website
 - [proxy.yimian.xyz](https://proxy.yimian.xyz/) Provide HTTP foreign file download acceleration service
 
### Private Service

 - [www.eee.dog](https://www.eee.dog) provides blog service
 - [onedrive.yimian.xyz](https://onedrive.yimian.xyz/) Provide online disk service
 - [iotcat.me](https://iotcat.me/) iotcat homepage
 - [monitor.yimian.xyz](https://monitor.yimian.xyz/) provides server monitoring services
 - [ushio.cool](https://ushio.cool/) Provide Ushio homepage
 - [guide.yimian.xyz](https://guide.yimian.xyz/) Ushio public service navigation
 - [git.yimian.xyz](https://git.yimian.xyz/) Provide iotcat's Git warehouse mirroring service
 - [home.yimian.xyz](https://home.yimian.xyz/) Sola Smart Home System
 - [cv.yimian.xyz](https://cv.yimian.xyz/) IoTcat's web version resume
 - [pay.yimian.xyz](https://pay.yimian.xyz/) IoTcat payment page



## Important modules


### ushio-session
Provide session service based on `iotcat/js-session`.
See [iotcat/session](https://github.com/iotcat/session) for details

### ushio-js
Provide the ushio interface on the web side, and provide aplayer, fp, js-session, tips light services. See [iotcat/ushio-js](https://github.com/iotcat/ushio-js) for details

### ushio-nginx
The anti-generation software modified on the basis of nginx source code, in fact, the main effect is to make the server in the http header be `Ushio/1.16.1`. . I will further optimize nginx if I can. See [iotcat/ushio-nginx](https://github.com/iotcat/ushio-nginx) for details

### ushio-dns
Use dnsmasq to provide dns services. If you need to use it, please modify your dns host address to `114.116.85.132`, `80.251.216.25`.

### redis database
Provide caching services locally.

### mongoDB database
Provide distributed file storage. Currently it is mainly used by the barrage module.

### php-fpm
Use the `crunchgeek/php-fpm:7.3` mirror to provide php web publishing services.

### frps intranet penetration
Provide intranet penetration services for intranet hosts.

### emqx mqtt
Provide mqtt service.

### ushio-monitor
Provide server monitoring service based on serverstatus.
See [https://monitor.yimian.xyz](https://monitor.yimian.xyz) for details

### oneindex
Provide onedrive file publishing service based on oneindex.


### ushio-log
Provide log service.



# Deployment method


## Script deployment

Currently supports one-click script deployment of CentOS7. Realize the expansion server that can be automated and unattended. For example, if necessary, I can now fill in a new Ushio server in Japan or other countries within ten minutes (provided that the network is good) and start providing services. For the script, please refer to [iotcat/ushio-centos-ini](https://github.com/IoTcat/ushio-centos-ini)

------------------------------

---------------------------------
# History

## System architecture (second generation)
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
|   |---ques.yimian.xyz (questionnaire system)
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

## System architecture (first generation)
```
|Ushio
|
|---|iis
|   |
|   |---Blog (blog, record life, guide)=SEO (indexed by search engines)
|   |   |
|   |   |---YimianReading (Chasing Fan/Reading Record)
|   |   |---YimianYulu (Record your own secondary quotations)
|   |   |---WeiBlog (similar to talk)
|   |   |---YimianDev (Development Record)
|   |   |---Message Board
|   |   |---RSS
|   |   |---Note Archive System
|   |
|   |---HomePage (home page, guiding role)=SEO
|   |   |
|   |   |---YimianGuide(Navigation page)
|   |   |----Private pc browser homepage
|   |   |----Private phone browser homepage
|   |   |---Resume
|   |
|   |---ACG.WATCH (collection of animation, movies, TV series and other videos)=SEO
|   |   |
|   |   |---Collection of Blu-ray Animation/Video
|   |   |----The banned masterpiece in China (complementary with station b)
|   |
|   |---OVO.RE(图床)=SEO
|   |---YimianMsc (cross-domain uninterrupted web music service) (based on NetEase Cloud Music)
|   |---YimianCloud (private network disk + public sharing) (distributed) (intranet + external network)
|   |---iot (Internet of Things)
|   |   |
|   |   |----Electronic Device Management System
|   |
|   |---YimianQues (simple questionnaire system)
|   |---YimianPC (simple system on notebook, convenient for intranet/extranet access and file sharing)
|   |---YimianData (Provide simple big data display function)
|   |---YimianChat (simple online chat platform)
|   |---UshioFee (Ushio running cumulative cost statistics)
|   |---YimianSSR (over the wall service management interface)
|
|---|login (user management system)
|   |
|   |---iis (register, log in, retrieve password page)
|   |---Temporary user system (random/QQ/WeChat/google)
|
|---|ssr/vpn (agent, assist in providing circumvention services)
|
|---|frp (Intranet penetration service)
|
|---|iot
|   |
|   |---ota (firmware update service)
|   |---MQTT
|
|---|storage
|   |
|   |---SQL (form, log)
|   |---NoSQL (website cache, video series information)
|   |---Object storage (speed-sensitive large files)
|   |---onedrive (large files, synchronized with yimianPC)
|
|---|API
|   |
|   |---mail
|   |---sms
|   |---Cuckoo machine
|   |---pic/moe(picture)
|   |---One word
|   |---dans (Barrage Service)
|   |---Picture suppression/cutting
|   |---Video suppression/transcoding
|   |---Translation (google translate)
|   |---Search (site search + comprehensive google)
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
|   |---wiot custom training model
|   |---User classification (to push content according to their preferences)
|   |---Simple chat robot
|
|---|game (game server)
|   |
|   |---Battlefield 2
|   |---Red Alert 2 Yuri's Revenge
|   |---Sim City 5
|
|---|log (log system)
|   |
|   |---System Log
|   |---iis access log
|   |---Spider access log
|   |---api access log
|
|---|monitor (monitoring/control system)
|   |
|   |---iis (Automatically guide users to the specified page when related services are abnormal, to prevent Google from penalizing, and warn the webmaster at the same time)
|   |---Control the status of each service switch
|   |---Certificate management (automatic renewal)
|
|---|backup (backup system)
|   |
|   |---github backup
|   |---YimianPC file backup
|   |---YimianPhone file backup
|   |---Database backup
|   |---Server system mirror backup
|
|---|report (report system)
|   |
|   |---Site daily overview
|
|
```

## Core dependencies

 CentOS7.6](https://www.centos.org/) Use CentOS as the operating system
- [nodeJS](https://github.com/nodejs/node) Use NodeJS to drive the system
- [php](https://github.com/php/php-src) Use php to build iis server
- [python](https://github.com/python/cpython) Use python for back-end data processing
- [nginx](https://github.com/nginx/njs) Modified nginx as a proxy
- [fp](https://github.com/IoTcat/fp) accurately identify user equipment
- [Shadowsocks](https://github.com/shadowsocks/shadowsocks-libev) Traffic proxy system
- [typecho](https://github.com/typecho/typecho) blog framework
- [jquery](https://github.com/jquery/jquery) js web development tool
- [dplayer](https://github.com/MoePlayer/DPlayer) open source barrage video player
- [aplayer](https://github.com/MoePlayer/APlayer) Open source music player
- [rsshub](https://github.com/DIYgod/RSSHub) Provide rich rss source
- [frp](https://github.com/fatedier/frp) Provide intranet penetration
- [docute](https://github.com/egoist/docute) Quick development documentation
- [handsome](https://github.com/ihewro/typecho-theme-handsome) typecho blog theme
- [DPlayer-node](https://github.com/MoePlayer/DPlayer-node) Barrage backend

## Services used

- Huawei Cloud CDN
- Huawei Cloud Object Storage
- HUAWEI CLOUD Distributed Cache Redis
- HUAWEI CLOUD cloud database RDS
- HUAWEI CLOUD Elastic Cloud Server ECS
- Tencent Cloud Domain Name Resolution
- Tencent Cloud Cloud Communication SMS
- Tencent Cloud Domain Name Resolution
- Tencent Cloud CDN
- Tencent Cloud Cloud Server
- Tencent Cloud Serverless Cloud Functions
- Tencent Enterprise Email
- Alibaba Cloud Lightweight Application Server
- Alibaba Cloud Email Push
- Github code hosting service
- Vultr VPS
- Godaddy domain name management
- internetbs domain name management
- UptimeRobot iis monitoring service
- onedrive file storage service
- GoogleAnalytics site visit statistics
