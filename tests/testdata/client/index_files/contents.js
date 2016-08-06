$(window).load(function(){
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
			console.log(px2git);
		}
	);
});
