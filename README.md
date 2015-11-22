Tip's ip view
=========================

一个可以在GAE上实现的ip地理位置查询PHP程序．（当然也能在任何支持PHP的主机上实现）

# 在GAE上部署

## 使用方法

修改app.yaml，将appid更改为你自己的appid后，上传即可！  
访问https://[yourappid].appspot.com 即可 .  
绑定域名参见GAE帮助：

- https://cloud.google.com/appengine/docs/using-custom-domains-and-ssl?hl=en

## 注意事项

ip地址的定位采用了两种方法：

- 纯真IP库：通过查询纯真IP库直接获得IP地址的地理位置
- 访问ipaddresslabs.com的api获得xml格式的地理位置后显示

目前由于ipaddresslabs.com的demo license已不可用，程序仅显示纯真ip库查询结果；  
若想使用ipaddresslabs，可自行添加新的可用license（位于index.php）.

# 在PHP主机上部署

直接ftp上传即可！

# 源码

源码修改自[ip429006.com](http://ip.429006.com)，感谢！

# 相关资源

## qqwry.dat 更新

- http://www.cz88.net/ （官网）

## 纯真ip库相关

- https://github.com/gwind/ylinux/tree/master/tools/IP/QQWry

Demo
=========================

- http://ip.lonsx.cf/ (GAE)
- http://tip.hp2.jp/ (sitemix)