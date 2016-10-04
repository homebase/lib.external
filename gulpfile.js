'use strict';
 
var gulp = require('gulp');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglifyjs');
var sassGlob = require('gulp-sass-glob');
var cleanCSS = require('gulp-clean-css');
var imagemin = require('gulp-imagemin');
var autoprefixer = require('gulp-autoprefixer');


// gulp watch         watching for radaris
// gulp watch --c1    watching for people-background-check.com

var basePath = '/rd/rd/www/';
if (process.argv[3] == '--c1') {
    basePath = '/rd/rdc/people-background-check.com/www/';
}

// Paths
var sassPath = basePath + 'css/sass/',
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
    .pipe(sass({outputStyle: 'compressed', includePaths: [sassPath], errLogToConsole: true}).on('error', sass.logError))
    .pipe(autoprefixer('last 2 versions'))
    .pipe(gulp.dest(cssPath));
});

gulp.task('scripts', function () {
    return gulp.src([
        jsPath + 'raw/foundation/foundation.core.js',
        jsPath + 'raw/foundation/foundation.util.*.js',
        jsPath + 'raw/foundation/foundation.tooltip.js'
    ])
    //.pipe(uglify('app.min.js', {
    //  outSourceMap: true
    //}))
    //.pipe(uglify())
    .pipe(concat('main.js'))
    .pipe(gulp.dest(jsPath));
});

gulp.task('watch', function(event) {
  console.log(sassPath);
  gulp.watch(sassPath + '**', function() {
        setTimeout(function () { 
            gulp.start('sass');
        }, 1000); // this timeout fix problem with sftp file transfer
    });
});


gulp.task('mytask', function() {
    console.log(basePath);
});