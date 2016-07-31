<?php
/**
 * Pickles2 px2-remove-attr CORE class
 */
namespace tomk79\pickles2\remove_attr;

/**
 * Pickles2 px2-remove-attr CORE class
 */
class main{
	private $px;

	/**
	 * HTML属性削除処理の実行
	 *
	 * Pickles2の状態を参照し、自動的に処理を振り分けます。
	 *
	 * - パブリッシュする場合、DECコメントを削除します。
	 * - プレビューの場合、DECライブラリを埋め込み、URIパラメータからDECの表示・非表示を切り替えられるようにします。
	 *
	 * @param object $px Picklesオブジェクト
	 * @param object $options オプション
	 * @return boolean true
	 */
	public static function exec( $px, $options = null ){
		require_once(__DIR__.'/simple_html_dom.php');
		if( !$px->is_publish_tool() ){
			// パブリッシュ時にのみ働きます。
			return true;
		}

		if( !@is_array($options->attrs) ){
			@$options->attrs = array();
		}
		// var_dump($options);

		foreach( $px->bowl()->get_keys() as $key ){
			$src = $px->bowl()->pull( $key );

			// HTML属性を削除
			$html = str_get_html(
				$src ,
				true, // $lowercase
				true, // $forceTagsClosed
				DEFAULT_TARGET_CHARSET, // $target_charset
				false, // $stripRN
				DEFAULT_BR_TEXT, // $defaultBRText
				DEFAULT_SPAN_TEXT // $defaultSpanText
			);
			foreach( $options->attrs as $attr ){
				$ret = $html->find('*['.$attr.']');
				foreach( $ret as $retRow ){
					// var_dump($retRow->$attr);
					$retRow->$attr = null;
				}
			}
			$src = $html->outertext;

			$px->bowl()->replace( $src, $key );
		}

		return true;
	} // exec()

}
