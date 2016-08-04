(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/**
 * pickles2Git.js
 */
var pickles2Git = {};
pickles2Git = function(){
	var _this = this;
	this.px = px;
	this.pj = pj;
	this.git = pj.git();
	var divDb = {
		'sitemaps':{
			'label':'サイトマップ'
		},
		'contents':{
			'label':'コンテンツ'
		}
	};

	/**
	 * コミットログの日付表現の標準化
	 */
	function dateFormat(date){
		var tmpDate = new Date(date);
		var fillZero = function( int ){
			return ('00000' + int).slice( -2 );
		}
		return tmpDate.getFullYear() + '-' + fillZero(tmpDate.getMonth()+1) + '-' + fillZero(tmpDate.getDate()) + ' ' + fillZero(tmpDate.getHours()) + ':' + fillZero(tmpDate.getMinutes()) + ':' + fillZero(tmpDate.getSeconds());
	} // dateFormat()

	/**
	 * ファイルの状態を判定する
	 */
	function fileStatusJudge(fileInfo){
		if(fileInfo.work_tree == '?' && fileInfo.index == '?'){
			return 'untracked';
		}else if(fileInfo.work_tree == 'A' || fileInfo.index == 'A'){
			return 'added';
		}else if(fileInfo.work_tree == 'M' || fileInfo.index == 'M'){
			return 'modified';
		}else if(fileInfo.work_tree == 'D' || fileInfo.index == 'D'){
			return 'deleted';
		}
		console.error('unknown status type;');
		console.error(fileInfo);
		return 'unknown';
	} // fileStatusJudge()

	/**
	 * コミットする
	 */
	this.commit = function( div, options, callback ){
		callback = callback || function(){};
		var $body = $('<div class="px2dt-git-commit">');
		var $ul = $('<ul class="list-group">');
		var $commitComment = $('<textarea>');

		px.progress.start({'blindness': true, 'showProgressBar': true});


		function getGitStatus(div, options, callback){
			switch( div ){
				case 'contents':
					_this.git.statusContents([options.page_path], function(result, err, code){
						callback(result, err, code);
					});
					break;
				default:
					_this.git.status(function(result, err, code){
						callback(result, err, code);
					});
					break;
			}
			return;
		}

		function gitCommit(div, options, commitComment, callback){
			switch( div ){
				case 'contents':
					_this.git.commitContents([options.page_path, commitComment], function(){
						callback();
					});
					break;
				case 'sitemaps':
					_this.git.commitSitemap([commitComment], function(){
						callback();
					});
					break;
				default:
					callback();
					break;
			}
			return;
		}

		getGitStatus(div, options, function(result, err, code){
			// console.log(result, err, code);
			if( result === false ){
				alert('ERROR: '+err);
				px.progress.close();
				callback();
				return;
			}
			$body.html('');
			$body.append( $('<p>').text('branch: ').append( $('<code>').text( result.branch ) ) );
			var list = [];
			if( div == 'contents' ){
				list = result.changes;
			}else{
				list = result.div[div];
			}
			if( !list.length ){
				alert('コミットできる変更がありません。');
				px.progress.close();
				callback();
				return;
			}
			for( var idx in list ){
				var fileStatus = fileStatusJudge(list[idx]);
				var $li = $('<li class="list-group-item">')
					.text( '['+fileStatus+'] '+list[idx].file )
					.addClass('px2dt-git-commit__stats-'+fileStatus)
				;
				$ul.append( $li );
			}
			$body.append( $ul );
			$body.append( $commitComment );

			px.dialog({
				'title': divDb[div].label+'をコミットする',
				'body': $body,
				'buttons':[
					$('<button>')
						.text('コミット')
						.attr({'type':'submit'})
						.addClass('px2-btn px2-btn--primary')
						.click(function(){
							px.progress.start({'blindness': true, 'showProgressBar': true});
							var commitComment = $commitComment.val();
							// console.log(commitComment);
							gitCommit(div, options, commitComment, function(){
								alert('コミットしました。');
								px.progress.close();
								px.closeDialog();
								callback();
							});
						}),
					$('<button>')
						.text('キャンセル')
						.addClass('px2-btn')
						.click(function(){
							px.closeDialog();
						})
				]
			});
			px.progress.close();

		});


		return this;
	} // commit()

	/**
	 * コミットログを表示する
	 */
	this.log = function( div, options, callback ){
		callback = callback || function(){};

		var $body = $('<div class="px2dt-git-commit">');
		var $ul = $('<ul class="list-group">');

		px.progress.start({'blindness': true, 'showProgressBar': true});

		function getGitLog(div, options, callback){
			switch( div ){
				case 'contents':
					_this.git.logContents([options.page_path], function(result, err, code){
						callback(result, err, code);
					});
					break;
				case 'sitemaps':
					_this.git.logSitemaps(function(result, err, code){
						callback(result, err, code);
					});
					break;
				default:
					break;
			}
			return;
		}

		function getGitRollback(div, options, hash, callback){
			switch( div ){
				case 'contents':
					_this.git.rollbackContents([options.page_path, hash], function(result, err, code){
						callback(result, err, code);
					});
					break;
				case 'sitemaps':
					_this.git.rollbackSitemaps([hash], function(result, err, code){
						callback(result, err, code);
					});
					break;
				default:
					break;
			}
			return;
		}

		getGitLog(div, options, function(result, err, code){
			// console.log(result, err, code);
			if( result === false ){
				alert('ERROR: '+err);
				px.progress.close();
				callback();
				return;
			}

			$body.html('');
			for( var idx in result ){
				(function(){
					var $li = $('<li class="list-group-item px2dt-git-commit__loglist">');
					var $row = $('<div class="px2dt-git-commit__loglist-row">');
					$row.append( $('<div class="px2dt-git-commit__loglist-row-date">').text( dateFormat(result[idx].date) ) );
					$row.append( $('<div class="px2dt-git-commit__loglist-row-title">').text( result[idx].title ) );
					$row.append( $('<div class="px2dt-git-commit__loglist-row-name">').text( result[idx].name + ' <'+result[idx].email+'>' ) );
					$row.append( $('<div class="px2dt-git-commit__loglist-row-hash">').text( result[idx].hash ) );
					var $detail = $('<div class="px2dt-git-commit__loglist-detail">')
						.hide()
						.attr({
							'data-px2dt-hash': result[idx].hash
						})
						.click(function(e){
							e.stopPropagation();
						})
					;
					$li.click(function(){
						$detail.toggle('fast', function(){
							var hash = $(this).attr('data-px2dt-hash');
							if( $detail.is(':visible') ){
								$detail.html( '<div class="px2-loading"></div>' );
								_this.git.show([hash], function(res){
									if( res.length > 2000 ){
										// 内容が多すぎると固まるので、途中までで切る。
										res = res.substr(0, 2000-3) + '...';
									}
									// console.log(res);
									$detail
										.html( '' )
										.append( $('<pre>')
											.text(res)
											.css({
												'max-height': 300,
												'overflow': 'auto'
											})
										)
										.append( $('<button>')
											.addClass('px2-btn')
											.addClass('px2-btn--primary')
											.text('このバージョンまでロールバックする')
											.click(function(){
												if( !confirm('この操作は現在の ' + divDb[div].label + ' の変更を破棄します。よろしいですか？') ){
													return;
												}
												px.progress.start({
													'blindness':true,
													'showProgressBar': true
												});
												getGitRollback(div, options, hash, function(result, err, code){
													if( result ){
														alert('ロールバックを完了しました。');
													}else{
														alert('[ERROR] ロールバックは失敗しました。');
														alert(err);
														console.error('ERROR: ' + err);
													}
													px.progress.close();
												});
												return;
											})
										)
									;
								});
							}else{
								$detail.html( '' );
							}
						});
					});
					$ul.append( $li.append($row).append($detail) );
				})();
			}
			$body.append( $ul );

			px.dialog({
				'title': divDb[div].label + 'のコミットログ',
				'body': $body,
				'buttons':[
					$('<button>')
						.text('閉じる')
						.attr({'type':'submit'})
						.addClass('px2-btn px2-btn--primary')
						.click(function(){
							px.closeDialog();
							callback();
						})
				]
			});
			px.progress.close();

		});


		return this;
	} // log()

}


if (typeof module !== 'undefined' && module.declare) {
	// Provide a CommonJS Modules/2.0 draft 8 module
	module.declare([], function(require, exports, module) {
		// Add exports from the pickles2Git exports
		for (key in pickles2Git) {
			if (pickles2Git.hasOwnProperty(key)) {
				exports[key] = pickles2Git[key];
			}
		}
	});
} else if (typeof define == 'function' && define.amd) {
	define(function() {
		return pickles2Git;
	});
} else if ( window ){
	// Export for browser use
	window.pickles2Git = pickles2Git;
} else if (typeof module !== 'undefined' && module.exports) {
	// Provide a CommonJS Modules/1.1 module
	module.exports = pickles2Git;
} else {
	console.error('pickles2Git is FAILED to define.');
}

},{}]},{},[1])