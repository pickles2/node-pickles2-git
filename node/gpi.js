/**
 * gpi.js (General Purpose Interface)
 */
module.exports = function(px2Git, data, callback){
	delete(require.cache[require('path').resolve(__filename)]);

	var _this = this;
	callback = callback || function(){};

	switch(data.api){
		case "logSitemaps":
			// sitemap のコミットログを取得する
			px2Git.logSitemaps(data.options, function(result, err, code){
				callback({
					'result': result,
					'err': err,
					'code': code
				});
			});
			break;

		default:
			callback(true);
			break;
	}

	return;
}
