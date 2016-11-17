'use strict';
 
var gulp = require('gulp');
var sourcemaps = require('gulp-sourcemaps');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var babel = require('gulp-babel');
var es2015 = require('babel-preset-es2015');
var plumber = require('gulp-plumber');
var uglify = require('gulp-uglifyjs');
var sassGlob = require('gulp-sass-glob');
var cleanCSS = require('gulp-clean-css');
var imagemin = require('gulp-imagemin');
var autoprefixer = require('gulp-autoprefixer');
var postcss      = require('gulp-postcss');


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


gulp.task('aaa', () =>
    gulp.src(cssPath + '*.css')
        .pipe(sourcemaps.init())
        .pipe(autoprefixer())
        .pipe(concat('main.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(cssPath))
);


gulp.task('sass', function () {
  return gulp
    .src(sassPath + '**/*.scss')
    .pipe(sourcemaps.init())
    //.pipe(sassGlob())



    .pipe(sass({outputStyle: 'compressed', includePaths: [sassPath], errLogToConsole: true}).on('error', sass.logError))
    // .pipe(postcss([require('postcss-flexbugs-fixes')]))
    // .pipe(autoprefixer({
    //       browsers: ['last 2 versions', 'ie >= 9', 'and_chr >= 2.3']
    //     }))
    // .pipe(sourcemaps.write())

    .pipe(autoprefixer())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(cssPath));
});



// to compile scripts for people-background-check.com run gulp scripts --c1
gulp.task('es2015', function () {
    return gulp.src([
        jsPath + 'raw/foundation/foundation.core.js',
        jsPath + 'raw/foundation/foundation.util.*.js',
        jsPath + 'raw/foundation/foundation.tooltip.js',
        jsPath + 'raw/foundation/foundation.dropdown.js',
        jsPath + 'raw/foundation/foundation.dropdownMenu.js',
        jsPath + 'raw/foundation/foundation.responsiveMenu.js',
        jsPath + 'raw/foundation/foundation.responsiveToggle.js',
        jsPath + 'raw/foundation/foundation.reveal.js',
    ])
    .pipe(sourcemaps.init())
    .pipe(babel({
            presets: [es2015]
    }))
    .pipe(concat('foundation.js'))
    .pipe(gulp.dest(jsPath));
});

gulp.task('scripts', function () {
    gulp.start('es2015');
    return gulp.src([
        jsPath + 'raw/jquery-ahm.js',
        jsPath + 'raw/jquery-run.js',
        jsPath + 'foundation.js',
        jsPath + 'raw/typeahead.js',
        jsPath + 'raw/rdcf.js',
    ])
    .pipe(concat('main.js'))
    .pipe(uglify())
    .pipe(gulp.dest(jsPath));
});


gulp.task('watch', function(event) {
  console.log(sassPath);
  gulp.watch(sassPath + '**', function() {
        setTimeout(function () { 
            gulp.start('sass');
        }, 3000); // this timeout fix problem with sftp file transfer
    });
});


gulp.task('mytask', function() {
    console.log(basePath);
});