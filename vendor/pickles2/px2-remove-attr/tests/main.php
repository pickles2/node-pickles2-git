<?php
/**
 * test for tomk79\px-remove-attr
 */

class mainTest extends PHPUnit_Framework_TestCase{
	private $fs;

	public function setup(){
		mb_internal_encoding('UTF-8');
		$this->fs = new tomk79\filesystem();
	}


	/**
	 * 通常のプレビューとして実行してみるテスト
	 */
	public function testStandard(){
		$output = $this->passthru( [
			'php',
			__DIR__.'/../htdocs/.px_execute.php' ,
			'-u' ,
			'Mozilla/5.0' ,
			'/' ,
		] );
		// var_dump($output);
		$this->assertTrue( $this->common_error( $output ) );
		$this->assertEquals( 1, preg_match('/'.preg_quote(' data-remove-test=', '/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote(' data-remove-test>', '/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote(' data-remove-2-test', '/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote(' data-remove-test-dont-remove', '/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote(' data-data-remove-test', '/').'/s', $output) );

	}// testStandard()

	/**
	 * パブリッシュツールとして実行してみるテスト
	 */
	public function testAsPublishTool(){
		$output = $this->passthru( [
			'php',
			__DIR__.'/../htdocs/.px_execute.php' ,
			'-u' ,
			'Mozilla/5.0(PicklesCrawler)' ,
			'/' ,
		] );
		// var_dump($output);
		$this->assertTrue( $this->common_error( $output ) );
		$this->assertEquals( 0, preg_match('/'.preg_quote(' data-remove-test=', '/').'/s', $output) );
		$this->assertEquals( 0, preg_match('/'.preg_quote(' data-remove-test>', '/').'/s', $output) );
		$this->assertEquals( 0, preg_match('/'.preg_quote(' data-remove-2-test', '/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote(' data-remove-test-dont-remove', '/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote(' data-data-remove-test', '/').'/s', $output) );

	}// testAsPublishTool()

	/**
	 * パブリッシュを実行
	 */
	public function testExecPublish(){

		$output = $this->passthru( [
			'php',
			__DIR__.'/../htdocs/.px_execute.php' ,
			'/?PX=publish.run' ,
		] );
		// var_dump($output);
		// $this->assertEquals( 0, preg_match('/'.preg_quote('data-remove-test', '/').'/is', $output) );

		// 後始末
		$output = $this->passthru( [
			'php', __DIR__.'/../htdocs/.px_execute.php', '/?PX=clearcache'
		] );

		clearstatcache();
		$this->assertTrue( $this->common_error( $output ) );
		$this->assertTrue( !is_dir( __DIR__.'/../htdocs/caches/p/' ) );

	}// testExecPublish()






	/**
	 * PHPがエラー吐いてないか確認しておく。
	 */
	private function common_error( $output ){
		if( preg_match('/'.preg_quote('Fatal', '/').'/si', $output) ){ return false; }
		if( preg_match('/'.preg_quote('Warning', '/').'/si', $output) ){ return false; }
		if( preg_match('/'.preg_quote('Notice', '/').'/si', $output) ){ return false; }
		return true;
	}


	/**
	 * コマンドを実行し、標準出力値を返す
	 * @param array $ary_command コマンドのパラメータを要素として持つ配列
	 * @return string コマンドの標準出力値
	 */
	private function passthru( $ary_command ){
		$cmd = array();
		foreach( $ary_command as $row ){
			$param = '"'.addslashes($row).'"';
			array_push( $cmd, $param );
		}
		$cmd = implode( ' ', $cmd );
		ob_start();
		passthru( $cmd );
		$bin = ob_get_clean();
		return $bin;
	}

}
