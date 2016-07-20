'use strict';

var gulp = require('gulp'),
	  sass = require('gulp-sass'),
	  cleanCSS = require('gulp-clean-css'),
    imagemin = require('gulp-imagemin');

/*Radaris needed*/
gulp.task('radaris', function() {
  return gulp.src('/rd/rd/www/css/sass/main.scss')
  .pipe(sass())
  .pipe(cleanCSS())
  .pipe(gulp.dest('/rd/rd/www/css'))
});

gulp.task('themeRadaris', function() {
  return gulp.src('/rd/rd/www/css/sass/bootstrap-theme.scss')
  .pipe(sass())
  .pipe(cleanCSS())
  .pipe(gulp.dest('/rd/rd/www/css'))
});

gulp.task('bamCss', function() {
  return gulp.src('/rd/rd/www/css/sass/bam_insider.scss')
  .pipe(sass())
  .pipe(cleanCSS())
  .pipe(gulp.dest('/rd/rd/www/css'))
});

/*Radaris needed END*/

gulp.task('compressImages', function() {
  gulp.src('/rd/rd/www/img/*.png')
    .pipe(imagemin())
    .pipe(gulp.dest('/rd/rd/www/images/'))
});

gulp.task('forum-rehold', function() {
  return gulp.src('/rd/rd/www/css/forum/sass/forum.scss')
  .pipe(sass())
  .pipe(cleanCSS())
  .pipe(gulp.dest('/rd/rd/www/css'))
});


gulp.task('watch', function () {
  gulp.watch('/rd/rd/www/css/sass/*.scss', ['radaris', 'bamCss', 'themeRadaris']);
});