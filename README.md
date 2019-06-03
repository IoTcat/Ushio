# Ushio
汐 - 分布式信息支持系统  

## 设计理念
- Reliable
- Fast
- Safe
- Integrated
- Extensible
- OpenSource
- Smart

## 系统架构
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
|   |---YimianQues(简易的问卷系统)
|   |---YimianPC(笔记本上的简易系统，方便内网/外网访问与文件共享)
|   |---YimianData(提供简易的大数据展示功能)
|   |---YimianChat(简易的在线聊天平台)
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

- [fp](https://github.com/IoTcat/fp) 精确识别用户设备
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
