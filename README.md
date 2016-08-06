# node-pickles2-git

## Setup

```
$ composer install
$ npm install
```

## Usage

### Server Side

```js
var Px2Git = require('pickles2-git');
var px2Git = new Px2Git();
px2Git.init(
	{
		'entryScript': require('path').resolve('/path/to/.px_execute.php')
	},
	function(){
		res
			.status(200)
			.set('Content-Type', 'text/json')
			.send( JSON.stringify(null) )
			.end();
	}
);
```

クライアントサイドに設定した GPI(General Purpose Interface) Bridge から送られてきたリクエストは、 `px2Git.gpi` に渡してください。 GPIは、処理が終わると、第3引数の関数をコールバックします。 コールバック関数の引数を、クライアント側へ返却してください。

```js
px2Git.gpi(
    bridge.api,
    bridge.options,
    function(result){
        callback(result);
    }
);
```

### Client Side

```html
<div id="canvas"></div>

<!-- jquery -->
<script src="./path/to/jquery.js"></script><!-- <- option; not required -->

<!-- Pickles 2 Git -->
<link rel="stylesheet" href="/node_modules/pickles2-git/dist/pickles2-git.css" />
<script src="/node_modules/pickles2-git/dist/pickles2-git.js"></script>

<script>
var px2git = new window.pickles2Git();
px2git.init(
	{
		'elmCanvas': document.getElementById('canvas'),
		'gpiBridge': function(api, options, callback){
			// GPI(General Purpose Interface) Bridge
			// broccoliは、バックグラウンドで様々なデータ通信を行います。
			// GPIは、これらのデータ通信を行うための汎用的なAPIです。
			var rtn = false;
			$.ajax({
				'url': '/apis/px2git',
				'data': {
					'api': 'gpiBridge' ,
					'bridge': {
						'api': api ,
						'options': options
					}
				},
				'success': function(data){
					rtn = data;
				},
				'complete': function(){
					callback(rtn);
				}
			});
			return;
		}

	}, function(){
		console.log('standby!');
	}
);
</script>
```

## ライセンス - License

MIT License


## 作者 - Author

- Tomoya Koyanagi <tomk79@gmail.com>
- website: <http://www.pxt.jp/>
- Twitter: @tomk79 <http://twitter.com/tomk79/>
