# Ushio - 分布式信息支持系统

## 序
19圣诞假期在欧洲旅游，在离开奥地利Hallstatt的OBB火车上，确定了自己分布式物联系统结构师的职业目标。2020年第一天，决定以全新的分布式理念重构自己正在运行的所有Web服务。为了便于未来新app的开发以及之前旧服务的移植，一套基于Git, Rysnc, OBS进行内容管理, 使用Docker做应用管理, 利用Redis, MongoDB, Mysql&Mysql-router数据管理, 通过MQTT实现消息推送的Ushio分布式信息支持系统内核方案被提出。在mysql读写分离的策略以及OBS挂载技术所带来的mysql和OBS存储集中化的基础上，本内核实现了IO与算力的分布式部署。 得益于核心服务全部基于nodejs以及docker, 这一内核可以部署在所有支持docker和nodejs的系统上，如大多数amd&arm架构的Linux系统以及win10 pro。