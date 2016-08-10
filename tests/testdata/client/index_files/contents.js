$(window).load(function(){
	var px2git = new window.pickles2Git();
	var params = (function(url){
		var paramsArray = [];
		parameters = url.split("?");
		if( parameters.length > 1 ) {
			var params = parameters[1].split("&");
			for ( var i = 0; i < params.length; i++ ) {
				var paramItem = params[i].split("=");
				for( var i2 in paramItem ){
					paramItem[i2] = decodeURIComponent( paramItem[i2] );
				}
				paramsArray.push( paramItem[0] );
				paramsArray[paramItem[0]] = paramItem[1];
			}
		}
		return paramsArray;
	})(window.location.href);
	// console.log(params);

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
						'data': JSON.stringify({
							'api': api ,
							'options': options
						})
					},
					'method': 'post',
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
			var opts = {};
			if( params.path ){
				opts.page_path = params.page_path;
			}
			px2git[params.method](params.div, opts, function(){
				// console.log(px2git);
				console.log('pickles2Git standby.');
			});
		}
	);

});
