pickles2/px2-remove-attr
=========

px2-remove-attr は、Pickles 2 に、HTMLドキュメントから指定した属性を削除する機能を提供します。
この機能はパブリッシュ実行時に働きます。プレビュー時には作用しません。


## Usage - 使い方

### 1. Pickles2 をセットアップ

### 2. composer.json に追記

```
{
    "require": {
        "pickles2/px2-remove-attr": "2.*"
    }
}
```

### 3. composer を更新

```
$ composer update
```

### 4. px-files/config.php に追加

`$conf->funcs->processor->html` にAPI設定を追加します。

```php
<?php

$conf->funcs->processor->html = [
    // HTML属性を削除する
    'tomk79\pickles2\remove_attr\main::exec('.json_encode(array(
        "attrs"=>array(
            'data-remove-test',
            'data-remove-2-test',
        ) ,
    )).')' ,
];
```

HTMLタグから、`attrs` に指定した属性を削除します(タグごと削除するのではなく、属性だけ削除します)。
`attrs` は配列です。複数の属性を指定できます。


## 開発者向け情報 - for Developer

### テスト - Test

```
$ cd {$documentRoot}
$ composer test
```
