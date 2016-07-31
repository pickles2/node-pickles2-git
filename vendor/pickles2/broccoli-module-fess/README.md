pickles2/broccoli-module-fess
=========

FESS document modues for [Pickles 2](http://pickles2.pxt.jp/) and broccoli-html-editor.


## Usage - 使い方

### 1. [Pickles 2](http://pickles2.pxt.jp/) をセットアップ

### 2. composer.json に追記

```
{
    "require": {
        "pickles2/broccoli-module-fess": "dev-master"
    }
}
```

### 3. composer を更新

```
$ composer update
```

### 4. px-files/config.php に追加

```
// config for Pickles2 Desktop Tool.
@$conf->plugins->px2dt->paths_module_template["FESS"] = "./vendor/pickles2/broccoli-module-fess/modules/";
```


## License

MIT License


## Author

- (C)Tomoya Koyanagi <tomk79@gmail.com>
- website: <http://www.pxt.jp/>
- Twitter: @tomk79 <http://twitter.com/tomk79/>
