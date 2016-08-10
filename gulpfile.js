var conf = require('config');
var gulp = require('gulp');
var sass = require('gulp-sass');//CSSコンパイラ
var autoprefixer = require("gulp-autoprefixer");//CSSにベンダープレフィックスを付与してくれる
var minifyCss = require('gulp-minify-css');//CSSファイルの圧縮ツール
var uglify = require("gulp-uglify");//JavaScriptファイルの圧縮ツール
var concat = require('gulp-concat');//ファイルの結合ツール
var plumber = require("gulp-plumber");//コンパイルエラーが起きても watch を抜けないようになる
var rename = require("gulp-rename");//ファイル名の置き換えを行う
var twig = require("gulp-twig");//Twigテンプレートエンジン
var browserify = require("gulp-browserify");//NodeJSのコードをブラウザ向けコードに変換
var packageJson = require(__dirname+'/package.json');
var _tasks = [
	'pickles2-git.js',
	'.css.scss',
	'client-libs'
];

// client-libs (frontend) を処理
gulp.task("client-libs", function() {
	gulp.src(["node_modules/bootstrap/dist/fonts/**/*"])
		.pipe(gulp.dest( './dist/libs/bootstrap/dist/fonts/' ))
	;
	gulp.src(["node_modules/bootstrap/dist/js/**/*"])
		.pipe(gulp.dest( './dist/libs/bootstrap/dist/js/' ))
	;
	gulp.src(["node_modules/px2style/dist/scripts.js"])
		.pipe(gulp.dest( './dist/libs/px2style/dist/' ))
	;
	gulp.src(["node_modules/px2style/dist/images/**/*"])
		.pipe(gulp.dest( './dist/libs/px2style/dist/images/' ))
	;
});

// src 中の *.css.scss を処理
gulp.task('.css.scss', function(){
	gulp.src("src/**/*.css.scss")
		.pipe(plumber())
		.pipe(sass({
			"sourceComments": false
		}))
		.pipe(autoprefixer())
		.pipe(rename({
			extname: ''
		}))
		.pipe(rename({
			extname: '.css'
		}))
		.pipe(gulp.dest( './dist/' ))

		.pipe(minifyCss({compatibility: 'ie8'}))
		.pipe(rename({
			extname: '.min.css'
		}))
		.pipe(gulp.dest( './dist/' ))
	;
});

// pickles2-git.js (frontend) を処理
gulp.task("pickles2-git.js", function() {
	gulp.src(["src/pickles2-git.js"])
		.pipe(plumber())
		.pipe(browserify({}))
		.pipe(concat('pickles2-git.js'))
		.pipe(gulp.dest( './dist/' ))
		.pipe(concat('pickles2-git.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest( './dist/' ))
	;
});


// src 中のすべての拡張子を監視して処理
gulp.task("watch", function() {
	gulp.watch(["src/**/*","libs/**/*","tests/testdata/htdocs/index_files/main.src.js"], _tasks);
});

// ブラウザを立ち上げてプレビューする
gulp.task("preview", function() {
	require('child_process').spawn('open',[conf.origin+'/']);
});

// src 中のすべての拡張子を処理(default)
gulp.task("default", _tasks);
