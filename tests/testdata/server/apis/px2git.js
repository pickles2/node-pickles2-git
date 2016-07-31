/**
 * px2git.js
 */
module.exports = function(){

	var Px2Git = require('../../../../node/main.js');

	return function(req, res, next){
		console.log(req.body);

		res
			.status(200)
			.set('Content-Type', 'text/json')
			.send( JSON.stringify(null) )
			.end();

		return;
	}

}
