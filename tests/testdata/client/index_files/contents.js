$(window).load(function(){
	var px2git = new window.pickles2Git();
	px2git.init(
		{
			'gpiBridge': function(api, options, callback){
				// GPI(General Purpose Interface) Bridge
				// broccoliは、バックグラウンドで様々なデータ通信を行います。
				// GPIは、これらのデータ通信を行うための汎用的なAPIです。
				socket.send(
					'broccoli',
					{
						'api': 'gpiBridge' ,
						'bridge': {
							'api': api ,
							'options': options
						}
					} ,
					function(rtn){
						// console.log(rtn);
						callback(rtn);
					}
				);
				return;
			}

		}, function(){
			console.log(px2git);
		}
	);
});
