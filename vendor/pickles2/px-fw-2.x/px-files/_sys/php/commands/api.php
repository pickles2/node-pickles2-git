<?php
/**
 * PX Commands "api"
 */
namespace picklesFramework2\commands;

/**
 * PX Commands "api"
 */
class api{

	/**
	 * Picklesオブジェクト
	 */
	private $px;

	/**
	 * PXコマンド名
	 */
	private $command = array();


	/**
	 * Starting function
	 * @param object $px Picklesオブジェクト
	 */
	public static function register( $px ){
		$px->pxcmd()->register('api', function($px){
			(new self( $px ))->kick();
			exit;
		}, true);
	}

	/**
	 * constructor
	 *
	 * @param object $px Picklesオブジェクト
	 */
	public function __construct( $px ){
		$this->px = $px;
	}


	/**
	 * kick
	 */
	private function kick(){

		$this->command = $this->px->get_px_command();

		switch( @$this->command[1] ){
			case 'get':
				//各種情報の取得
				$this->api_get();
				break;
			case 'is':
				//各種情報の評価
				$this->api_is();
				break;
			// case 'dlfile':
			// 	//ファイルダウンロード
			// 	$this->api_dlfile();
			// 	break;
			// case 'ulfile':
			// 	//ファイルアップロード
			// 	$this->api_ulfile();
			// 	break;
			// case 'ls':
			// 	//ファイルの一覧を取得
			// 	$this->api_ls();
			// 	break;
			// case 'delete':
			// 	//ファイル削除
			// 	$this->api_delete();
			// 	break;
			// case 'hash':
			// 	//ハッシュ値を取得
			// 	$this->api_hash();
			// 	break;
		}

		if( !strlen( @$this->command[1] ) ){
			$this->homepage();
		}
		$this->error();
		exit;
	}


	/**
	 * ホームページを表示する。
	 *
	 * HTMLを標準出力した後、`exit()` を発行してスクリプトを終了します。
	 *
	 * @return void
	 */
	private function homepage(){
		$this->user_message('このコマンドは、外部アプリケーションへのAPIを提供します。');
		exit;
	}

	/**
	 * エラーメッセージを表示する。
	 *
	 * HTMLを標準出力した後、`exit()` を発行してスクリプトを終了します。
	 *
	 * @return void
	 */
	private function error(){
		$this->user_message('未定義のコマンドを受け付けました。');
		exit;
	}


	/**
	 * ユーザーへのメッセージを表示して終了する
	 * @param string $msg メッセージテキスト
	 */
	private function user_message($msg){
		if( $this->px->req()->is_cmd() ){
			header('Content-type: text/plain;');
			print $this->px->pxcmd()->get_cli_header();
			print $msg."\n";
			print $this->px->pxcmd()->get_cli_footer();
		}else{
			$html = '';
			ob_start(); ?>
	<p><?= htmlspecialchars($msg) ?></p>
<?php
			$html .= ob_get_clean();
			print $this->px->pxcmd()->wrap_gui_frame($html);
		}
		exit;
	}


	// /**
	//  * [API] api.dlfile.config
	//  *
	//  * 結果を標準出力した後、`exit()` を発行してスクリプトを終了します。
	//  *
	//  * @return void
	//  */
	// private function api_dlfile(){
	// 	header('Content-type: text/plain; charset=UTF-8');
	// 	$path = $this->get_target_file_path();
	// 	if( is_null($path) ){
	// 		header('HTTP/1.1 404 NotFound');
	// 		print 'Unknown or illegal path was given.';
	// 		exit;
	// 	}
	// 	if( is_dir($path) ){
	// 		header('HTTP/1.1 404 NotFound');
	// 		print 'Target path is a directory.';
	// 		exit;
	// 	}
	// 	if( !is_file($path) ){
	// 		header('HTTP/1.1 404 NotFound');
	// 		print 'Target path is Not a file.';
	// 		exit;
	// 	}
	// 	$content = $this->px->dbh()->file_get_contents($path);
	// 	print $content;
	// 	exit;
	// }//api_dlfile()

	// /**
	//  * [API] api.ulfile.*
	//  *
	//  * 結果を標準出力した後、`exit()` を発行してスクリプトを終了します。
	//  *
	//  * @return void
	//  */
	// private function api_ulfile(){
	// 	$path = $this->get_target_file_path();
	// 	if( is_null($path) ){
	// 		//nullならNG
	// 		print $this->data_convert( array('result'=>0, 'message'=>'Unknown or illegal path was given.') );
	// 		exit;
	// 	}
	// 	if( is_dir($path) ){
	// 		//対象パスにディレクトリが既に存在していたらNG
	// 		print $this->data_convert( array('result'=>0, 'message'=>'A directory exists.') );
	// 		exit;
	// 	}
	// 	if( !is_dir(dirname($path)) ){
	// 		//ディレクトリがなければ作る。
	// 		if( !$this->px->dbh()->mkdir_all(dirname($path)) ){
	// 			print $this->data_convert( array('result'=>0, 'message'=>'Disable to make parent directory.') );
	// 			exit;
	// 		}
	// 	}
	// 	if( !$this->px->dbh()->save_file( $path, $this->px->req()->get_param('bin') ) ){
	// 		print $this->data_convert( array('result'=>0, 'message'=>'Disable to save this file.') );
	// 		exit;
	// 	}
	// 	print $this->data_convert( array('result'=>1) );
	// 	exit;
	// }//api_ulfile()

	// /**
	//  * [API] api.ls.config
	//  *
	//  * 結果を標準出力した後、`exit()` を発行してスクリプトを終了します。
	//  *
	//  * @return void
	//  */
	// private function api_ls(){
	// 	switch( $this->command[2] ){
	// 		case 'content':
	// 		case 'sitemap':
	// 		case 'theme':
	// 		case 'px':
	// 			break;
	// 		default:
	// 			print $this->data_convert( array('result'=>0, 'message'=>'Illegal command "'.$this->command[2].'" was given.') );
	// 			exit;
	// 			break;
	// 	}

	// 	$path = $this->get_target_file_path();
	// 	if( is_null($path) ){
	// 		//  ターゲットがただしく指示されていない。
	// 		print $this->data_convert( array('result'=>0, 'message'=>'Unknown or illegal path was given.') );
	// 		exit;
	// 	}
	// 	if( is_file($path) ){
	// 		//  ターゲットがファイル。ディレクトリを指示しなければならない。
	// 		print $this->data_convert( array('result'=>0, 'message'=>'Target is a file. Give a directory path.') );
	// 		exit;
	// 	}
	// 	if( !is_dir($path) ){
	// 		//  ターゲットがディレクトリではない。
	// 		print $this->data_convert( array('result'=>0, 'message'=>'Directory is not exists.') );
	// 		exit;
	// 	}
	// 	if($this->command[2]=='content'){
	// 		//コンテンツディレクトリ内の除外ファイル
	// 		$tmp_path = $this->px->dbh()->get_realpath($path);
	// 		if( preg_match( '/^'.preg_quote($this->px->dbh()->get_realpath($this->px->get_conf('paths.px_dir')),'/').'/' , $this->px->dbh()->get_realpath($path) ) ){
	// 			// _PX の中身はこのAPIからは操作できない。
	// 			print $this->data_convert( array('result'=>0, 'message'=>'Directory "_PX" is not accepted to access with command "content". Please use command "px".') );
	// 			exit;
	// 		}
	// 	}

	// 	$rtn = array();
	// 	$ls = $this->px->dbh()->ls($path);
	// 	foreach( $ls as $file_name ){
	// 		if($this->command[2]=='content'){
	// 			//コンテンツディレクトリ内の除外ファイル
	// 			if( preg_match( '/^'.preg_quote($this->px->dbh()->get_realpath($this->px->get_conf('paths.px_dir')),'/').'/' , $this->px->dbh()->get_realpath($path.'/'.$file_name) ) ){
	// 				// _PX の中身はこのAPIからは操作できない。
	// 				continue;
	// 			}
	// 			if( $this->px->dbh()->get_realpath($path.'/'.$file_name) == $this->px->dbh()->get_realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/_px_execute.php') ){
	// 				//  /_px_execute.php は除外
	// 				continue;
	// 			}
	// 			if( $this->px->dbh()->get_realpath($path.'/'.$file_name) == $this->px->dbh()->get_realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/.htaccess') ){
	// 				//  /.htaccess は除外
	// 				continue;
	// 			}
	// 		}

	// 		$file_info = array(
	// 			'type'=>null,
	// 			'name'=>$file_name,
	// 		);
	// 		if( is_dir($path.'/'.$file_name) ){
	// 			$file_info['type'] = 'directory';
	// 		}elseif( is_file($path.'/'.$file_name) ){
	// 			$file_info['type'] = 'file';
	// 		}
	// 		array_push( $rtn, $file_info );
	// 	}
	// 	print $this->data_convert( $rtn );
	// 	exit;

	// }//api_ls()

	// /**
	//  * [API] api.delete.*
	//  *
	//  * 結果を標準出力した後、`exit()` を発行してスクリプトを終了します。
	//  *
	//  * @return void
	//  */
	// private function api_delete(){
	// 	switch( $this->command[2] ){
	// 		case 'content':
	// 		case 'sitemap':
	// 		case 'theme':
	// 		case 'px':
	// 			//これ以外は削除の機能は提供しない
	// 			break;
	// 		default:
	// 			print $this->data_convert( array('result'=>0, 'message'=>'Illegal command "'.$this->command[2].'" was given.') );
	// 			exit;
	// 			break;
	// 	}

	// 	//  フールプルーフ
	// 	$input_path = trim( $this->px->req()->get_param('path') );
	// 	$input_path = preg_replace('/\\\\/','/',$input_path);
	// 	$input_path = preg_replace('/\/+/','/',$input_path);
	// 	if( !strlen( $input_path ) ){
	// 		print $this->data_convert( array('result'=>0, 'message'=>'Unknown or illegal path was given.') );
	// 		exit;
	// 	}
	// 	if( $input_path == '/' ){
	// 		print $this->data_convert( array('result'=>0, 'message'=>'Unknown or illegal path was given.') );
	// 		exit;
	// 	}
	// 	//  / フールプルーフ

	// 	$path = $this->get_target_file_path();
	// 	if( is_null($path) || !file_exists($path) ){
	// 		print $this->data_convert( array('result'=>0, 'message'=>'Target file or directory is not exists.') );
	// 		exit;
	// 	}
	// 	if( !$this->px->dbh()->rm( $path ) ){
	// 		print $this->data_convert( array('result'=>0, 'message'=>'Disable to delete file or directory.') );
	// 		exit;
	// 	}
	// 	print $this->data_convert( array('result'=>1) );
	// 	exit;
	// }//api_delete()

	// /**
	//  * ダウン・アップロードファイルのパスを得る。
	//  *
	//  * api.ulfile, api.dlfile が使用します。
	//  *
	//  * @return string|null パラメータ `path` が示すファイルの絶対パス。`path` が不正な形式の場合には `null` を返します。
	//  */
	// private function get_target_file_path(){
	// 	$rtn = null;
	// 	$path = $this->px->req()->get_param('path');

	// 	$is_path = true;
	// 	if( !strlen( $path ) ){
	// 		$is_path = '/';
	// 	}
	// 	if( preg_match( '/(?:\/|^)\.\.(?:\/|$)/si', $path ) ){
	// 		$is_path = false;
	// 		return null;//←パラメータが不正な場合はすぐ抜ける
	// 	}

	// 	switch( $this->command[2] ){
	// 		case 'config':
	// 			$rtn = $this->px->get_path_conf();
	// 			break;
	// 		case 'sitemap_definition':
	// 			$rtn = $this->px->get_conf('paths.px_dir').'configs/sitemap_definition.csv';
	// 			break;
	// 		case 'content':
	// 			if(!$is_path){ return null; }
	// 			$rtn = './'.$path;
	// 			break;
	// 		case 'sitemap':
	// 			if(!$is_path){ return null; }
	// 			$rtn = $this->px->get_conf('paths.px_dir').'sitemaps/'.$path;
	// 			break;
	// 		case 'theme':
	// 			$rtn = $this->px->get_conf('paths.px_dir').'themes/'.$this->command[3].'/'.$path;
	// 			break;
	// 		case 'px':
	// 			if(!$is_path){ return null; }
	// 			$rtn = $this->px->get_conf('paths.px_dir').$path;
	// 			break;
	// 	}
	// 	return $this->px->dbh()->get_realpath($rtn);
	// }

	/**
	 * [API] api.get.*
	 *
	 * 結果を標準出力した後、`exit()` を発行してスクリプトを終了します。
	 *
	 * <dl>
	 * 	<dt>PX=api.get.version</dt>
	 * 		<dd>Pickles Framework のバージョン番号を取得します。</dd>
	 * 	<dt>PX=api.get.config</dt>
	 * 		<dd>設定オブジェクトを取得します。</dd>
	 *
	 * 	<dt>PX=api.get.sitemap</dt>
	 * 		<dd>サイトマップ全体の配列を取得します。</dd>
	 * 	<dt>PX=api.get.page_info&path={$path}</dt>
	 * 		<dd><code>$px->site()->get_page_info({$path})</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.parent</dt>
	 * 		<dd><code>$px->site()->get_parent()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.actors</dt>
	 * 		<dd><code>$px->site()->get_actors()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.role</dt>
	 * 		<dd><code>$px->site()->get_role()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.children&filter={$filter}</dt>
	 * 		<dd><code>$px->site()->get_children()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.bros&filter={$filter}</dt>
	 * 		<dd><code>$px->site()->get_bros()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.bros_next&filter={$filter}</dt>
	 * 		<dd><code>$px->site()->get_bros_next()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.bros_prev&filter={$filter}</dt>
	 * 		<dd><code>$px->site()->get_bros_prev()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.next&filter={$filter}</dt>
	 * 		<dd><code>$px->site()->get_next()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.prev&filter={$filter}</dt>
	 * 		<dd><code>$px->site()->get_prev()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.breadcrumb_array</dt>
	 * 		<dd><code>$px->site()->get_breadcrumb_array()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.dynamic_path_info&path={$path}</dt>
	 * 		<dd><code>$px->site()->get_dynamic_path_info({$path})</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.bind_dynamic_path_param&path={$path}&param={$param}</dt>
	 * 		<dd><code>$px->site()->bind_dynamic_path_param({$path}, {$param})</code> の返却値を取得します。<code>{$param}</code> には、パラメータのキーと値の組を必要分格納したオブジェクトをJSON形式の文字列で指定します。</dd>
	 *
	 * 	<dt>PX=api.get.path_homedir</dt>
	 * 		<dd><code>$px->get_path_homedir()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.path_controot</dt>
	 * 		<dd><code>$px->get_path_controot()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.path_docroot</dt>
	 * 		<dd><code>$px->get_path_docroot()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.path_content</dt>
	 * 		<dd><code>$px->get_path_content()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.path_files&path_resource={$path}</dt>
	 * 		<dd><code>$px->path_files({$path})</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.realpath_files&path_resource={$path}</dt>
	 * 		<dd><code>$px->realpath_files({$path})</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.path_files_cache&path_resource={$path}</dt>
	 * 		<dd><code>$px->path_files_cache({$path})</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.realpath_files_cache&path_resource={$path}</dt>
	 * 		<dd><code>$px->realpath_files_cache({$path})</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.realpath_files_private_cache&path_resource={$path}</dt>
	 * 		<dd><code>$px->realpath_files_private_cache({$path})</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.domain</dt>
	 * 		<dd><code>$px->get_domain()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.directory_index</dt>
	 * 		<dd><code>$px->get_directory_index()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.directory_index_primary</dt>
	 * 		<dd><code>$px->get_directory_index_primary()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.path_proc_type</dt>
	 * 		<dd><code>$px->get_path_proc_type()</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.get.href&linkto={$path_linkto}</dt>
	 * 		<dd><code>$px->href({$path_linkto})</code> の返却値を取得します。</dd>
	 * </dl>
	 *
	 * @return void
	 */
	private function api_get(){
		$sitemap_filter_options = function($px, $cmd=null){
			$options = array();
			$options['filter'] = $px->req()->get_param('filter');
			if( strlen($options['filter']) ){
				switch( $options['filter'] ){
					case 'true':
					case '1':
						$options['filter'] = true;
						break;
					case 'false':
					case '0':
						$options['filter'] = false;
						break;
				}
			}
			return $options;
		};

		switch( $this->command[2] ){
			case 'version':
				$val = $this->px->get_version();
				print $this->data_convert( $val );
				break;
			case 'config':
				$val = $this->px->conf();
				print $this->data_convert( $val );
				break;
			case 'sitemap':
				$val = $this->px->site()->get_sitemap();
				print $this->data_convert( $val );
				break;
			case 'page_info':
				$val = $this->px->site()->get_page_info($this->px->req()->get_param('path'));
				print $this->data_convert( $val );
				break;
			case 'parent':
				$val = $this->px->site()->get_parent();
				print $this->data_convert( $val );
				break;
			case 'actors':
				$val = $this->px->site()->get_actors();
				print $this->data_convert( $val );
				break;
			case 'role':
				$val = $this->px->site()->get_role();
				print $this->data_convert( $val );
				break;
			case 'children':
				$val = $this->px->site()->get_children(null, $sitemap_filter_options($this->px, $this->command[2]));
				print $this->data_convert( $val );
				break;
			case 'bros':
				$val = $this->px->site()->get_bros(null, $sitemap_filter_options($this->px, $this->command[2]));
				print $this->data_convert( $val );
				break;
			case 'bros_next':
				$val = $this->px->site()->get_bros_next(null, $sitemap_filter_options($this->px, $this->command[2]));
				print $this->data_convert( $val );
				break;
			case 'bros_prev':
				$val = $this->px->site()->get_bros_prev(null, $sitemap_filter_options($this->px, $this->command[2]));
				print $this->data_convert( $val );
				break;
			case 'next':
				$val = $this->px->site()->get_next(null, $sitemap_filter_options($this->px, $this->command[2]));
				print $this->data_convert( $val );
				break;
			case 'prev':
				$val = $this->px->site()->get_prev(null, $sitemap_filter_options($this->px, $this->command[2]));
				print $this->data_convert( $val );
				break;
			case 'breadcrumb_array':
				$val = $this->px->site()->get_breadcrumb_array();
				print $this->data_convert( $val );
				break;
			case 'dynamic_path_info':
				$val = $this->px->site()->get_dynamic_path_info($this->px->req()->get_param('path'));
				print $this->data_convert( $val );
				break;
			case 'bind_dynamic_path_param':
				$param = $this->px->req()->get_param('param');
				$param = @json_decode( $param, true );
				$val = $this->px->site()->bind_dynamic_path_param($this->px->req()->get_param('path'), $param);
				print $this->data_convert( $val );
				unset($param);
				break;
			case 'path_homedir':
				$val = $this->px->get_path_homedir();
				print $this->data_convert( $val );
				break;
			case 'path_controot':
				$val = $this->px->get_path_controot();
				print $this->data_convert( $val );
				break;
			case 'path_docroot':
				$val = $this->px->get_path_docroot();
				print $this->data_convert( $val );
				break;
			case 'path_content':
				$val = $this->px->get_path_content();
				print $this->data_convert( $val );
				break;
			case 'path_files':
				$val = $this->px->path_files($this->px->req()->get_param('path_resource'));
				print $this->data_convert( $val );
				break;
			case 'realpath_files':
				$val = $this->px->realpath_files($this->px->req()->get_param('path_resource'));
				print $this->data_convert( $val );
				break;
			case 'path_files_cache':
				$val = $this->px->path_files_cache($this->px->req()->get_param('path_resource'));
				print $this->data_convert( $val );
				break;
			case 'realpath_files_cache':
				$val = $this->px->realpath_files_cache($this->px->req()->get_param('path_resource'));
				print $this->data_convert( $val );
				break;
			case 'realpath_files_private_cache':
				$val = $this->px->realpath_files_private_cache($this->px->req()->get_param('path_resource'));
				print $this->data_convert( $val );
				break;
			case 'domain':
				$val = $this->px->get_domain();
				print $this->data_convert( $val );
				break;
			case 'directory_index':
				$val = $this->px->get_directory_index();
				print $this->data_convert( $val );
				break;
			case 'directory_index_primary':
				$val = $this->px->get_directory_index_primary();
				print $this->data_convert( $val );
				break;
			case 'path_proc_type':
				$val = $this->px->get_path_proc_type();
				print $this->data_convert( $val );
				break;
			case 'href':
				$val = $this->px->href($this->px->req()->get_param('linkto'));
				print $this->data_convert( $val );
				break;
			default:
				print $this->data_convert( array('result'=>0) );
				break;
		}
		exit;
	}

	/**
	 * [API] api.is.*
	 *
	 * 結果を標準出力した後、`exit()` を発行してスクリプトを終了します。
	 *
	 * <dl>
	 * 	<dt>PX=api.is.match_dynamic_path&path={$path}</dt>
	 * 		<dd><code>$px->site()->is_match_dynamic_path({$path})</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.is.page_in_breadcrumb&path={$path}</dt>
	 * 		<dd><code>$px->site()->is_page_in_breadcrumb({$path})</code> の返却値を取得します。</dd>
	 * 	<dt>PX=api.is.ignore_path&path={$path}</dt>
	 * 		<dd><code>$px->is_ignore_path({$path})</code> の返却値を取得します。</dd>
	 * </dl>
	 *
	 * @return void
	 */
	private function api_is(){
		switch( $this->command[2] ){
			case 'match_dynamic_path':
				$val = $this->px->site()->is_match_dynamic_path($this->px->req()->get_param('path'));
				print $this->data_convert( $val );
				break;
			case 'page_in_breadcrumb':
				$val = $this->px->site()->is_page_in_breadcrumb($this->px->req()->get_param('path'));
				print $this->data_convert( $val );
				break;
			case 'ignore_path':
				$val = $this->px->is_ignore_path($this->px->req()->get_param('path'));
				print $this->data_convert( $val );
				break;
			default:
				print $this->data_convert( array('result'=>0) );
				break;
		}
		exit;
	}

	// /**
	//  * [API] api.hash
	//  *
	//  * 結果を標準出力した後、`exit()` を発行してスクリプトを終了します。
	//  *
	//  * @return void
	//  */
	// private function api_hash(){
	// 	header('Content-type: text/csv');
	// 	if( strpos( $_SERVER['HTTP_USER_AGENT'] , 'MSIE' ) ){
	// 		#	MSIE対策
	// 		#	→こんな問題 http://support.microsoft.com/kb/323308/ja
	// 		header( 'Cache-Control: public' );
	// 		header( 'Pragma: public' );
	// 	}
	// 	header( 'Content-Disposition: attachment; filename=PxFW_apiHash_'.date('Ymd_His').'.csv' );


	// 	#	出力バッファをすべてクリア
	// 	while( @ob_end_clean() );

	// 	print '"[Pickles Framework (version:'.$this->px->get_version().')]"'."\n";
	// 	print '"--docroot"'."\n";
	// 	$this->mk_hash_list('.');
	// 	print '"--_PX"'."\n";
	// 	$this->mk_hash_list($this->px->get_conf('paths.px_dir'));
	// 	exit;
	// }
	// /**
	//  * ディレクトリ内のファイルの一覧とそのMD5ハッシュ値を再帰的に標準出力する。
	//  *
	//  * [API] api.hash 内で使用します。
	//  *
	//  * @param string $base_dir ベースディレクトリのパス
	//  * @param string $local_path ベースディレクトリ以下のパス
	//  * @return void
	//  */
	// private function mk_hash_list($base_dir,$local_path=''){
	// 	$file_list = $this->px->dbh()->ls($base_dir.$local_path);
	// 	sort($file_list);
	// 	foreach($file_list as $file_name){
	// 		if($this->px->dbh()->get_realpath($base_dir.$local_path.'/'.$file_name) == $this->px->dbh()->get_realpath($this->px->get_conf('paths.px_dir'))){
	// 			continue;
	// 		}
	// 		if(is_file($base_dir.$local_path.'/'.$file_name)){
	// 			print '"file","'.$local_path.'/'.$file_name.'","'.md5_file($base_dir.$local_path.'/'.$file_name).'"'."\n";
	// 		}elseif(is_dir($base_dir.$local_path.'/'.$file_name)){
	// 			print '"dir","'.$local_path.'/'.$file_name.'"'."\n";
	// 			$this->mk_hash_list($base_dir,$local_path.'/'.$file_name);
	// 		}
	// 		flush();
	// 	}
	// }

	// -------------------------------------

	/**
	 * データを自動的に加工して返す。
	 *
	 * @param mixed $val 加工するデータ
	 * @return string 加工されたテキストデータ
	 */
	private function data_convert($val){
		$data_type = $this->px->req()->get_param('type');
		if( !is_string($data_type) || !strlen($data_type) ){
			$data_type = 'json';
		}
		header('Content-type: application/xml; charset=UTF-8');
		if( $data_type == 'json' ){
			header('Content-type: application/json; charset=UTF-8');
		}elseif( $data_type == 'jsonp' ){
			header('Content-type: application/javascript; charset=UTF-8');
		}
		switch( $data_type ){
			case 'jsonp':
				return $this->data2jsonp($val);
				break;
			case 'json':
				return $this->data2json($val);
				break;
			case 'xml':
				return $this->data2xml($val);
				break;
		}
		// return self::data2jssrc($val);
		return json_encode($val);
	}

	/**
	 * データをXMLに加工して返す。
	 *
	 * @param mixed $val 加工するデータ
	 * @return string 加工されたテキストデータ
	 */
	private function data2xml($val){
		return '<api>'.self::xml_encode($val).'</api>';
	}

	/**
	 * データをJSONに加工して返す。
	 *
	 * @param mixed $val 加工するデータ
	 * @return string 加工されたテキストデータ
	 */
	private function data2json($val){
		// return self::data2jssrc($val);
		return json_encode($val);
	}

	/**
	 * データをJSONPに加工して返す。
	 *
	 * @param mixed $val 加工するデータ
	 * @return string 加工されたテキストデータ
	 */
	private function data2jsonp($val){
		//JSONPのコールバック関数名は、パラメータ callback に受け取る。
		$cb = trim( $this->px->req()->get_param('callback') );
		if( !strlen($cb) ){
			$cb = 'callback';
		}
		// return $cb.'('.self::data2jssrc($val).');';
		return $cb.'('.json_encode($val).');';
	}


	/**
	 * 変数をXML構造に変換する
	 *
	 * @param mixed $value 値
	 * @param array $options オプション
	 * <dl>
	 *   <dt>delete_arrayelm_if_null</dt>
	 *     <dd>配列の要素が `null` だった場合に削除。</dd>
	 *   <dt>array_break</dt>
	 *     <dd>配列に適当なところで改行を入れる。</dd>
	 * </dl>
	 * @return string XMLシンタックスに変換された値
	 */
	private static function xml_encode( $value = null , $options = array() ){

		if( is_array( $value ) ){
			#	配列
			$is_hash = false;
			$i = 0;
			foreach( $value as $key=>$val ){
				#	ArrayかHashか見極める
				if( !is_int( $key ) ){
					$is_hash = true;
					break;
				}
				if( $key != $i ){
					#	順番通りに並んでなかったらHash とする。
					$is_hash = true;
					break;
				}
				$i ++;
			}

			if( $is_hash ){
				$RTN .= '<object>';
			}else{
				$RTN .= '<array>';
			}
			if( $options['array_break'] ){ $RTN .= "\n"; }
			foreach( $value as $key=>$val ){
				if( $options['delete_arrayelm_if_null'] && is_null( $value[$key] ) ){
					#	配列のnull要素を削除するオプションが有効だった場合
					continue;
				}
				$RTN .= '<element';
				if( $is_hash ){
					$RTN .= ' name="'.htmlspecialchars( $key ).'"';
				}
				$RTN .= '>';
				$RTN .= self::xml_encode( $value[$key] , $options );
				$RTN .= '</element>';
				if( $options['array_break'] ){ $RTN .= "\n"; }
			}
			if( $is_hash ){
				$RTN .= '</object>';
			}else{
				$RTN .= '</array>';
			}
			if( $options['array_break'] ){ $RTN .= "\n"; }
			return	$RTN;
		}

		if( is_object( $value ) ){
			#	オブジェクト型
			$RTN = '';
			$RTN .= '<object>';
			$proparray = get_object_vars( $value );
			$methodarray = get_class_methods( get_class( $value ) );
			foreach( $proparray as $key=>$val ){
				$RTN .= '<element name="'.htmlspecialchars( $key ).'">';

				$RTN .= self::xml_encode( $val , $options );
				$RTN .= '</element>';
			}
			$RTN .= '</object>';
			return	$RTN;
		}

		if( is_int( $value ) ){
			#	数値
			$RTN = '<value type="int">'.htmlspecialchars( $value ).'</value>';
			return	$RTN;
		}

		if( is_float( $value ) ){
			#	浮動小数点
			$RTN = '<value type="float">'.htmlspecialchars( $value ).'</value>';
			return	$RTN;
		}

		if( is_string( $value ) ){
			#	文字列型
			$RTN = '<value type="string">'.htmlspecialchars( $value ).'</value>';
			return	$RTN;
		}

		if( is_null( $value ) ){
			#	ヌル
			return	'<value type="null"></value>';
		}

		if( is_resource( $value ) ){
			#	リソース型
			return	'<value type="undefined"></value>';
		}

		if( is_bool( $value ) ){
			#	ブール型
			if( $value ){
				return	'<value type="bool">true</value>';
			}else{
				return	'<value type="bool">false</value>';
			}
		}

		return	'<value type="undefined"></value>';

	}

	/**
	 * ダブルクオートで囲えるようにエスケープ処理する。
	 *
	 * @param string $text テキスト
	 * @return string エスケープされたテキスト
	 */
	private static function escape_doublequote( $text ){
		$text = preg_replace( '/\\\\/' , '\\\\\\\\' , $text);
		$text = preg_replace( '/"/' , '\\"' , $text);
		return	$text;
	}

}
