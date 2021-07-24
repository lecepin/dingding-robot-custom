# 钉钉自定义机器人

钉钉自定义被动/主动回复机器人，PHP版本。

## 使用

1. 将代码部署到你的 PHP 服务器。

2. 打开 钉钉群 -> 创建“智能群助手” -> 添加“自定义机器人”

3. 开启“加签”（加签下面的内容就是 `$DING_SECRET` 的值）和“Outgoing机制”，并填入部署服务器地址，

<img src="https://user-images.githubusercontent.com/11046969/126869057-d858abff-a001-4e8d-85ba-afb4e2182670.png" width="500" />

4. 点击”完成“，新弹窗中会出现 `Webhook`，就是 `$DING_WEB_HOOK` 的值 

<img src="https://user-images.githubusercontent.com/11046969/126869217-3a630165-dc36-4073-a6fd-7ff4630fde34.png" width="500" />

然后就可以和机器人正常交互了。

## 命令逻辑

处理的对话逻辑，可以直接在 `index.php` 中的 `$dingContent` 中获取，可自行处理逻辑。

代码中内置的逻辑可自行删除，发送消息在 `utils.php` 中封装了 `sendDingMsg` 函数。

---

更详细的机器人配置可以参考 [钉钉机器人文档](https://developers.dingtalk.com/document/app/develop-enterprise-internal-robots)
