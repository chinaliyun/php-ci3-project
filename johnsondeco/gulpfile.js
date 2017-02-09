var gulp = require('gulp');
var less = require('gulp-less');
var concat = require('gulp-concat');
var order = require('gulp-order');
var minifycss = require('gulp-clean-css');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var public = './public/';




gulp.task('default',function(){
	//前台less处理
	gulp.watch('public/src/index/less/*.less', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		
		gulp.src(e.path)
			.pipe(less())
			.pipe(gulp.dest('public/dev/index/css/'));
	})
	gulp.watch('public/dev/index/css/*.css', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		
		gulp.src(e.path)
			.pipe(minifycss())
			.pipe(rename({suffix: '.min'}))
			.pipe(gulp.dest('public/dist/index/css'))
	})
	// 前台图片处理
	gulp.watch('public/src/index/images/**/*.*', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		gulp.src(e.path)
			.pipe(gulp.dest('public/dev/index/images/'))
	})
	gulp.watch('public/dev/index/images/**/*.*', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		gulp.src(e.path)
			.pipe(gulp.dest('public/dist/index/images/'))
	})
	// 后台less处理
	gulp.watch('public/src/admin/less/*.less', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		
		gulp.src(e.path)
			.pipe(less())
			.pipe(gulp.dest('public/dev/admin/css/'));
	})
	gulp.watch('public/dev/admin/css/*.css', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		
		gulp.src(e.path)
			.pipe(minifycss())
			.pipe(rename({suffix: '.min'}))
			.pipe(gulp.dest('public/dist/admin/css'))
	})
	// 后台图片处理
	gulp.watch('public/src/admin/images/*.*', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		
		gulp.src(e.path)
			.pipe(gulp.dest('public/dev/admin/images/'))
	})
	gulp.watch('public/dev/admin/images/*.*', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		
		gulp.src(e.path)
			.pipe(gulp.dest('public/dist/admin/images/'))
	})
	// Wapless处理
	gulp.watch('public/src/wap/less/*.less', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		
		gulp.src(e.path)
			.pipe(less())
			.pipe(gulp.dest('public/dev/wap/css/'));
	})
	gulp.watch('public/dev/wap/css/*.css', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		
		gulp.src(e.path)
			.pipe(minifycss())
			.pipe(rename({suffix: '.min'}))
			.pipe(gulp.dest('public/dist/wap/css'))
	})
	// Wap图片处理
	gulp.watch('public/src/wap/images/*.*', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		
		gulp.src(e.path)
			.pipe(gulp.dest('public/dev/wap/images/'))
	})
	gulp.watch('public/dev/wap/images/*.*', function(e){
		var date = new Date();
		console.log('[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + e.type + ' : ' + e.path);
		
		gulp.src(e.path)
			.pipe(gulp.dest('public/dist/wap/images/'))
	})
})


gulp.task('test',function(){
	var date = new Data();
	console.log(new Date().getHours());
	})