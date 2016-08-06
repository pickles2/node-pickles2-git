/**
 * px.project.git
 */
module.exports = function() {
	var _this = this;

	var pathCommands = {
		'git': 'git'
	};
	var utils79 = require('utils79');
	var path_px2git = require('path').resolve(__dirname+'/../php/git.php');
	var entryScript,
		nodePhpBin;

	/**
	 * initialize
	 * @return {[type]} [description]
	 */
	this.init = function(options, callback){
		callback = callback || function(){}
		options = options || {};

		entryScript = options.entry_script || null;
		nodePhpBin = require('node-php-bin').get();

		callback();
		return;
	}

	/**
	 * 汎用API
	 */
	this.gpi = function(data, callback){
		callback = callback||function(){};
		// this.page_path = data.page_path;
		// console.log(this.page_path);
		var gpi = require( __dirname+'/gpi.js' );
		gpi(
			this,
			data,
			function(rtn){
				callback(rtn);
			}
		);
		return this;
	}

	function apiGen(apiName){
		return new (function(apiName){
			this.fnc = function(options, callback){
				if( arguments.length == 2 ){
					options = arguments[0];
					callback = arguments[1];
				}else{
					callback = arguments[0];
				}

				options = options||[];
				callback = callback||function(){};

				var param = {
					'method': apiName,
					'command_git': (pathCommands.git || null),
					'entryScript': entryScript,
					'options': options
				};
				// console.log(param);

				// PHPスクリプトを実行する
				var rtn = '';
				var err = '';
				nodePhpBin.script(
					[
						path_px2git,
						utils79.base64_encode(JSON.stringify(param))
					],
					{
						"success": function(data){
							rtn += data;
							// console.log(data);
						} ,
						"error": function(data){
							rtn += data;
							err += data;
							console.log(data);
						} ,
						"complete": function(data, error, code){
							setTimeout(function(){
								try {
									rtn = JSON.parse(rtn);
								} catch (e) {
									console.error('Failed to parse JSON string.');
									console.error(rtn);
									rtn = false;
								}
								console.log(rtn, err, code);
								callback(rtn, err, code);
							},500);
						}
					}
				);
				return;
			}
		})(apiName).fnc;
	}

	/**
	 * サイトマップをコミットする
	 * @return {[type]} [description]
	 */
	this.commitSitemap = new apiGen('commit_sitemaps');

	/**
	 * ページのコンテンツをコミットする
	 * @return {[type]} [description]
	 */
	this.commitContents = new apiGen('commit_contents');

	/**
	 * git status
	 * @return {[type]} [description]
	 */
	this.status = new apiGen('status');

	/**
	 * git status (contents)
	 * @return {[type]} [description]
	 */
	this.statusContents = new apiGen('status_contents');

	/**
	 * サイトマップをロールバックする
	 * @return {[type]} [description]
	 */
	this.rollbackSitemaps = new apiGen('rollback_sitemaps');

	/**
	 * ページのコンテンツをロールバックする
	 * @return {[type]} [description]
	 */
	this.rollbackContents = new apiGen('rollback_contents');

	/**
	 * git log
	 * @return {[type]} [description]
	 */
	this.log = new apiGen('log');

	/**
	 * サイトマップのコミットログを取得する
	 * @return {[type]} [description]
	 */
	this.logSitemaps = new apiGen('log_sitemaps');

	/**
	 * コンテンツのコミットログを取得する
	 * @return {[type]} [description]
	 */
	this.logContents = new apiGen('log_contents');

	/**
	 * git show
	 * @return {[type]} [description]
	 */
	this.show = new apiGen('show');

	return this;
};
