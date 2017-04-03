<style>
.container {
  margin-top: 40px;
  margin-bottom: 40px;
  margin-left: auto;
  margin-right: auto;
  width: 550px;
  max-width: 100%;
  border-radius: 5px;
  box-shadow: #CCC 0 4px 6px;
  overflow: hidden;
  position: relative;
}

.header {
  height: 42px;
  background-color: rgb(33, 150, 243);
  padding: 42px 0;
  text-align: center;
}

.header > img {
  max-height: 42px;
  min-height: 42px;
  display: inline-block;
}

.body {
  font-family: MicrosoftYaHei, "Hiragino Sans GB";
  padding: 40px;
  border: #DDD 1px solid;
  border-radius: 0 0 5px 5px;
  word-break: break-word;
}

.body > h1 {
  margin: 0 0 40px 0;
  font-size: 36px;
  line-height: 40px;
  font-weight: normal;
}

.body > h2 {
  margin: 0 0 40px 0;
  font-size: 24px;
  line-height: 28px;
  font-weight: normal;
}

.body > p {
  margin: 0 0 40px 0;
  font-size: 14px;
  line-height: 18px;
  font-weight: normal;
}

.body > pre {
  margin: 0 0 40px 0;
  font-size: 14px;
  line-height: 32px;
  text-align: center;
  font-weight: bold;
  border-radius: 2px;
  background: #EEE;
  border: #DDD 1px solid;
  white-space: pre-wrap;
  word-break: break-word;
}

.body > .footer {
  margin-top: 64px;
  font-size: 14px;
  line-height: 18px;

  text-align: center;
  color: #999;
}

.body > .footer a {
  text-decoration: none;
  color: #999;
}

.extra {
  font-family: MicrosoftYaHei, "Hiragino Sans GB";
  margin-top: 40px;
  color: #AAA;
  font-size: 14px;
  line-height: 24px;
}

.extra img {
  max-height: 56px;
  min-height: 56px;
  margin-bottom: 12px;
}

.center {
  text-align: center;
}

.randstr {
  background: white;
  opacity: 0.1;
  font-size: 1px;
}
</style>
@if(!isset($webView) && !isset($fromHuman))
<div class="extra">完整HTML版邮件请见：{{mp_url('/showEmail/'.$id)}}</div>
@endif
<div class="container">
  <div class="header">
    <img src="{{cdn_url('/email/munpanel.png')}}">
  </div>
  <div class="body">
    <p>{!!$content!!}</p>

    <div class="footer">
      <a href="https://www.munpanel.com">MUNPANEL</a>
      &middot;
      <a href="https://kb.bjmun.org/console-lite/">Console Lite</a>
      &middot;
      <a href="https://bjmun.org/console-it/client">Console iT Client</a>
      &middot;
      <a href="https://munl.ink">MUNLink</a>
    </div>
  </div>
</div>

<div class="extra center">
  @if(!isset($fromHuman))
  Automatic email. Do not reply.<br>
  @endif
  Sent to {{$receiver->name.' '.$receiver->address}} by MUNPANEL System.<br>
</div>
