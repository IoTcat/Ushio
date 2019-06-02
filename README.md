# Ushio
汐 - 分布式信息支持系统  

## 设计理念
- Reliable
- Fast
- Safe
- Integrated
- Extensible

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
|   |---YimianMsc(跨域不间断网页音乐服务)
|   |---YimianCloud(私人网盘+公共分享)
|   |---iot(物联相关)
|   |---YimianQues(简易的问卷系统)
|
|
|---|ssr/vpn(代理，辅助提供翻墙服务)
|
|---|frp(内网穿透服务)
|
|---|iot
|   |
|   |ota(固件更新服务)
|   |MQTT
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
|   |---pic
|   |---一言
|   |---dans(弹幕服务)
|   |---图片压制
|   |---翻译(google translate)
|   |---搜索(站内搜索+综合google)
|
|---|ai
|   |
|   |---wiot自定义训练模型
|   |---用户分类(投其所好推送内容)
|
|---|game(游戏服务器)
|   |
|   |战地2
|   |红色警戒2尤里复仇
|   |模拟城市5
|
|---|log(日志系统)
|   |
|   |系统日志
|   |服务访问日志
|   |蜘蛛访问日志
|
|---|monitor(监视/控制系统)
|   |
|   |iis(相关服务异常时自动引导用户至指定页面，防止google惩罚，同时向站长警告)
|
|---|backup(备份系统)
|   |
|   |github备份
|   |YimianPC文件备份
|   |YimianPhone文件备份
|
|---|report(报表系统)
|   |
|   |站点每日概况
|
|
```
