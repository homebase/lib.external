'use strict';
 
var gulp = require('gulp');
var sass = require('gulp-sass');
var sassGlob = require('gulp-sass-glob');
var cleanCSS = require('gulp-clean-css');
var imagemin = require('gulp-imagemin');
var autoprefixer = require('gulp-autoprefixer');

// Paths
var basePath = '/rd/rd/www/',
    sassPath = basePath + 'css/sass/',
    cssPath = basePath + 'css/',
    jsPath = basePath + 'js/',
    rawImagePath = basePath + 'images/',
    imagePath = basePath + 'img/';


gulp.task('images', function() {
  gulp.src(rawImagePath +'**')
    .pipe(imagemin())
    .pipe(gulp.dest(imagePath))
});

gulp.task('sass', function () {
  return gulp
    .src(sassPath + '**/*.scss')
    .pipe(sassGlob())
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(autoprefixer('last 2 versions'))
    .pipe(gulp.dest(cssPath));
});

gulp.task('watch', function(event) {
  gulp.watch(sassPath + '**', function() {
        setTimeout(function () { 
            gulp.start('sass');
        }, 1000); // this timeout fix problem with sftp file transfer
    });
});


