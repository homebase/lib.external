'use strict';

var gulp = require('gulp'),
	sass = require('gulp-sass');

gulp.task('sass', function() {
  return gulp.src('../../rd/rd/www/css/sass/main.scss')
  .pipe(sass())
  .pipe(gulp.dest('../../rd/rd/www/css'))
});