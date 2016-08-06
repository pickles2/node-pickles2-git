/**
 * px2git.js
 */
module.exports = function(){

	var Px2Git = require('../../../../node/main.js');

	return function(req, res, next){
		console.log(req.body);

		var px2Git = new Px2Git();
		px2Git.init(
			{
				'entryScript': require('path').resolve(__dirname + '/../../px2/.px_execute.php')
			},
			function(){
				res
					.status(200)
					.set('Content-Type', 'text/json')
					.send( JSON.stringify(null) )
					.end();
			}
		);


		return;
	}

}
