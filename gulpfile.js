'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var uglify = require('gulp-uglify');
var babel = require('gulp-babel');
var es2015 = require('babel-preset-es2015');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps'); 
var rigger = require('gulp-rigger');
var include = require('gulp-include');
var imagemin = require('gulp-imagemin');

var basePath = '/rd/rd/www/';
if (process.argv[3] == '--c1') {
    basePath = '/rd/vhosts/rdc/people-background-check.com/www/';
} if (process.argv[3] == '--c2') {
    basePath = '/rd/vhosts/rdc/backgroundcheck.run/www/';
} if (process.argv[3] == '--c3') {
    basePath = '/rd/vhosts/phone/phoneowner.us/www/';
} if (process.argv[3] == '--c4') {
    basePath = '/rd/vhosts/phone/phoneid.us/www/';
} if (process.argv[3] == '--c5') {
    basePath = '/rd/vhosts/rdc/newenglandfacts.com/www/';
} if (process.argv[3] == '--licenses') {
    basePath = '/rd/vhosts/license/licensefiles.com/www/';
} if (process.argv[3] == '--wellnut') {
    basePath = '/rd/vhosts/wellnut/www/';
} if (process.argv[3] == '--arrestfacts') {
    basePath = '/rd/vhosts/arrestfacts/www/';
} if (process.argv[3] == '--licenseswhois') {
    basePath = '/rd/vhosts/license/licensewhois.com/www/';
} if (process.argv[3] == '--veripages') {
    basePath = '/rd/vhosts/rdc/veripages.com/www/';
} if (process.argv[3] == '--peoplelegacy') {
    basePath = '/rd/vhosts/peoplelegacy/www/';
} if (process.argv[3] == '--persontrust') {
    basePath = '/rd/vhosts/persontrust.com/www/';
} if (process.argv[3] == '--farm') {
    basePath = '/rd/vhosts/farm/www/';
}

// Paths
var sassPath = basePath + 'css/sass/',
    cssPath = basePath + 'css/',
    jsPath = basePath + 'js/',
    rawImagePath = basePath + 'images/',
    imagePath = basePath + 'img/';

gulp.task('sass', function () {
  return gulp
    .src(sassPath + '**/*.scss')
    .pipe(sass({outputStyle: 'compressed', includePaths: [sassPath], errLogToConsole: true}).on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(cssPath));
});

gulp.task('images', function() {
  gulp.src(imagePath +'**')
    .pipe(imagemin())
    .pipe(gulp.dest(imagePath))
});

gulp.task('es2015', function () { // transpiler ES2015 to ES5
	console.log(jsPath)
    // return gulp.src(jsPath+'raw/es2015.includes.js')
    // .pipe(rigger()) - babel does not work with rigger
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
            presets: [es2015],
            compact: false
    }).on('error', function(e){console.log(e);}))
    .pipe(concat('raw/es2015.js'))
    .pipe(gulp.dest(jsPath));
});

gulp.task('js', function () {
	console.log(jsPath)
    gulp.src(jsPath+'includes.js')
        .pipe(rigger())
        .pipe(concat('bundle.js'))
        .pipe(sourcemaps.init())
        .pipe(uglify().on('error', function(e){console.log(e);}))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(jsPath))
});

gulp.task('watch', function(event) {
  console.log(sassPath);
  gulp.watch(sassPath + '**', ['sass']);
});