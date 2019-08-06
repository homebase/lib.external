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
var clone_num = null;

var basePath = '/rd/rd/www/';
if (hasParam(process.argv, '--c1')) {
    basePath = '/rd/vhosts/rdc/people-background-check.com/www/';
} if (hasParam(process.argv, '--c2')) {
    basePath = '/rd/vhosts/rdc/backgroundcheck.run/www/';
} if (hasParam(process.argv, '--c3')) {
    basePath = '/rd/vhosts/phone/phoneowner.us/www/';
} if (hasParam(process.argv, '--c4')) {
    basePath = '/rd/vhosts/phone/phoneid.us/www/';
} if (hasParam(process.argv, '--c5')) {
    basePath = '/rd/vhosts/rdc/newenglandfacts.com/www/';
} if (hasParam(process.argv, '--licenses')) {
    basePath = '/rd/vhosts/license/licensefiles.com/www/';
} if (hasParam(process.argv, '--wellnut')) {
    basePath = '/rd/vhosts/wellnut/www/';
} if (hasParam(process.argv, '--arrestfacts')) {
    basePath = '/rd/vhosts/arrestfacts/www/';
} if (hasParam(process.argv, '--licenseswhois')) {
    basePath = '/rd/vhosts/license/licensewhois.com/www/';
} if (hasParam(process.argv, '--veripages')) {
    basePath = '/rd/vhosts/rdc/veripages.com/www/';
} if (hasParam(process.argv, '--peoplelegacy')) {
    basePath = '/rd/vhosts/peoplelegacy/www/';
} if (hasParam(process.argv, '--persontrust')) {
    basePath = '/rd/vhosts/persontrust.com/www/';
} if (hasParam(process.argv, '--federal')) {
    basePath = '/rd/vhosts/federal-data.com/www/';
} if (hasCloneParam(process.argv, '--farm_c')) {
    basePath = '/rd/vhosts/farm/sites/c' + clone_num + '/www/';
} if (hasParam(process.argv, '--homemetry')) {
    basePath = '/rd/vhosts/address/homemetry.com/www/';
} if (hasParam(process.argv, '--rehold')) {
    basePath = '/rd/vhosts/address/rehold.com/www/';
} if (hasParam(process.argv, '--cityzor')) {
    basePath = '/rd/vhosts/cityzor/www/';
} if (hasParam(process.argv, '--trustoria')) {
    basePath = '/rd/vhosts/trustoria/www/';
} if (hasParam(process.argv, '--trustoria_new')) {
    basePath = '/rd/vhosts/professions/trustoria/www/';
} if (hasParam(process.argv, '--homeflock')) {
    basePath = '/rd/vhosts/homeflock/www/';
} if (hasParam(process.argv, '--bizstanding')) {
    basePath = '/rd/vhosts/bizstanding/www/';
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

function hasParam(argv, need_param) {
    return Object.values(argv).indexOf(need_param) > -1 || false;
}

function hasCloneParam(argv, need_param) {
    var res = false;
    argv.forEach((val, index) => {
        if (val.substring(0,8) === need_param) {
            clone_num = val.substring(8);
            res = true;
        }
    });
    return res;
}
