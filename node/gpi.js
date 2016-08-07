/**
 * gpi.js (General Purpose Interface)
 */
module.exports = function(px2Git, data, callback){
	delete(require.cache[require('path').resolve(__filename)]);

	var _this = this;
	callback = callback || function(){};

	switch(data.api){
		case 'status':
		case 'statusContents':
		case 'commitSitemaps':
		case 'commitContents':
		case 'log':
		case 'logContents':
		case 'logSitemaps':
		case 'show':
		case 'rollbackSitemaps':
		case 'rollbackContents':
			px2Git[data.api](data.options, function(result, err, code){
				// console.log(result);
				callback({
					'result': result,
					'err': err,
					'code': code
				});
			});
			break;

		default:
			callback(false);
			break;
	}

	return;
}
