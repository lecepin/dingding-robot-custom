const crypto = require("crypto");
const fetch = require("node-fetch");

function sendMsg(
  secret,
  dingHook,
  msg,
  title,
  type = "text",
  ats = [],
  atAll = false
) {
  const now = Date.now();
  const sign = crypto
    .createHmac("sha256", secret)
    .update(now + "\n" + secret)
    .digest()
    .toString("base64");

  return fetch(`${dingHook}&timestamp=${now}&sign=${sign}`, {
    headers: { "Content-Type": "application/json" },
    method: "post",
    body: JSON.stringify({
      msgtype: type,
      markdown: {
        title,
        text: msg,
      },
      text: {
        content: msg,
      },
      at: {
        atUserIds: ats,
        isAtAll: atAll,
      },
    }),
  }).then((data) => data.json());
}

sendMsg(
  "SECc0xxxxx",
  "https://oapi.dingtalk.com/robot/send?access_token=5ec176061631e79c6328ab44xxxxx",
  "# 内容",
  "标题",
  "markdown"
).then(console.log);
// { errcode: 0, errmsg: 'ok' }
