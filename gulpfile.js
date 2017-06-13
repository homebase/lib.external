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
    basePath = '/rd/rdc/people-background-check.com/www/';
} if (process.argv[3] == '--c2') {
    basePath = '/rd/rdc/backgroundcheck.run/www/';
} if (process.argv[3] == '--c3') {
    basePath = '/rd/phone/phoneowner.us/www/';
} if (process.argv[3] == '--licenses') {
    basePath = '/rd/license/licensefiles.com/www/';
} if (process.argv[3] == '--wellnut') {
    basePath = '/rd/wellnut/www/';
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
        .pipe(include())
        .pipe(concat('bundle.js'))
        .pipe(sourcemaps.init())
        .pipe(uglify().on('error', function(e){console.log(e);}))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(jsPath))
});












// var gulp = require('gulp');

// var source = require('vinyl-source-stream');
// var gutil = require('gulp-util');

// var browserify = require('browserify');
// var source = require('vinyl-source-stream');
// var buffer = require('vinyl-buffer');
// var uglify = require('gulp-uglifyjs');
// var uglify2 = require('gulp-uglify');
// var sourcemaps = require('gulp-sourcemaps'); 

// var reactify = require('reactify');
// var babelify = require('babelify');
// var watchify = require('watchify');
// var notify = require('gulp-notify');

// var sass = require('gulp-sass');
// var concat = require('gulp-concat');
// var babel = require('gulp-babel');
// var es2015 = require('babel-preset-es2015');
// var plumber = require('gulp-plumber'); 

// var sassGlob = require('gulp-sass-glob');
// var cleanCSS = require('gulp-clean-css');
// var imagemin = require('gulp-imagemin');
// var autoprefixer = require('gulp-autoprefixer');
// var postcss      = require('gulp-postcss');


// // gulp watch         watching for radaris
// // gulp watch --c1    watching for people-background-check.com
// // gulp watch --c2    watching for backgroundcheck.run
// // gulp watch --c3    watching for phoneowner.us
// // gulp watch --licenses    watching for licensefiles.com
// // gulp watch --wellnut    watching for ??

// var basePath = '/rd/rd/www/';
// if (process.argv[3] == '--c1') {
//     basePath = '/rd/rdc/people-background-check.com/www/';
// }
// if (process.argv[3] == '--c2') {
//     basePath = '/rd/rdc/backgroundcheck.run/www/';
// }
// if (process.argv[3] == '--c3') {
//     basePath = '/rd/phone/phoneowner.us/www/';
// }
// if (process.argv[3] == '--licenses') {
//     basePath = '/rd/license/licensefiles.com/www/';
// }
// if (process.argv[3] == '--wellnut') {
//     basePath = '/rd/wellnut/www/';
// }

// // Paths
// var sassPath = basePath + 'css/sass/',
//     cssPath = basePath + 'css/',
//     jsPath = basePath + 'js/',
//     rawImagePath = basePath + 'images/',
//     imagePath = basePath + 'img/';


// gulp.task('images', function() {
//   gulp.src(imagePath +'**')
//     .pipe(imagemin())
//     .pipe(gulp.dest(imagePath))
// });


// // gulp.task('aaa', () =>
// //     gulp.src(cssPath + '*.css')
// //         .pipe(sourcemaps.init())
// //         .pipe(autoprefixer())
// //         .pipe(concat('main.css'))
// //         .pipe(sourcemaps.write('.'))
// //         .pipe(gulp.dest(cssPath))
// // );


// gulp.task('sass', function () {
//   return gulp
//     .src(sassPath + '**/*.scss')
//     //.pipe(sourcemaps.init())
//     //.pipe(sassGlob())



//     .pipe(sass({outputStyle: 'compressed', includePaths: [sassPath], errLogToConsole: true}).on('error', sass.logError))
//     // .pipe(postcss([require('postcss-flexbugs-fixes')]))
//     // .pipe(autoprefixer({
//     //       browsers: ['last 2 versions', 'ie >= 9', 'and_chr >= 2.3']
//     //     }))
//     // .pipe(sourcemaps.write())

//     .pipe(autoprefixer())
//     //.pipe(sourcemaps.write('.'))
//     .pipe(gulp.dest(cssPath));
// });



// // to compile scripts for people-background-check.com run gulp scripts --c1
// gulp.task('es2015', function () {
//     return gulp.src([
//         jsPath + 'raw/foundation/foundation.core.js',
//         jsPath + 'raw/foundation/foundation.util.*.js',
//         jsPath + 'raw/foundation/foundation.tooltip.js',
//         jsPath + 'raw/foundation/foundation.dropdown.js',
//         jsPath + 'raw/foundation/foundation.dropdownMenu.js',
//         jsPath + 'raw/foundation/foundation.responsiveMenu.js',
//         jsPath + 'raw/foundation/foundation.responsiveToggle.js',
//         jsPath + 'raw/foundation/foundation.reveal.js',
//     ])
//     .pipe(sourcemaps.init())
//     .pipe(babel({
//             presets: [es2015]
//     }))
//     .pipe(concat('foundation.js'))
//     .pipe(gulp.dest(jsPath));
// });

// gulp.task('foundation', function() {
//   return browserify([
//         jsPath + 'raw/foundation.js',
//     ]) //sourceFile 
//   .bundle()
//   .pipe(source('foundation.js')) //destFile
//   .pipe(buffer())
//   // .pipe(babel({
//   //       presets: ['es2015'],
//   //       "compact" : true
//   //   }))
//   // .pipe(sourcemaps.init({loadMaps: true}))
//   // .pipe(uglify())
//   .pipe(gulp.dest(jsPath)); //destFolder
// });

// gulp.task('bootstrap', function() {
//   return browserify([
//         jsPath + 'bundle.js',
//     ]) //sourceFile 
//   .bundle()
//   .pipe(source('bootstrap.min.js')) //destFile
//   .pipe(buffer())
//   .pipe(uglify())
//   .pipe(gulp.dest(jsPath)); //destFolder
// });


// gulp.task('scriptsRadaris', function() {
//   return gulp.src([
//     jsPath + 'bootstrap.min.js',
//     jsPath + 'typeahead.js',
//     jsPath + 'view.more.js',
//     jsPath + 'rdf.js',
//     jsPath + 'radar.js',
//     jsPath + 'nlp.js',
//     jsPath + 'voting.js',
//     jsPath + 'fact.js',
//     jsPath + 'jquery-run.js',
//     jsPath + 'jquery-ahm.js',
//     jsPath + 'scrolltotop.js',
//     jsPath + 'social-likes.min.js',
//     jsPath + 'search.js',
//     jsPath + 'jquery.rating.pack.js',
//     jsPath + 'jquery.scrollto.js',
//     jsPath + 'offcanvas.js',
//     jsPath + 'filterAgeRadaris.js',
//     jsPath + 'jquery.clearInput.js',
//     jsPath + 'tabsmentions.js',
//   ])
//   .pipe(sourcemaps.init({loadMaps: true}))
//   .pipe(concat('rd.min.js'))
//   .pipe(uglify2())
//   .pipe(sourcemaps.write('./'))
//   .pipe(gulp.dest(jsPath));
// });

// gulp.task('scriptsClones', function () {
//     gulp.start('es2015');
//     return gulp.src([
//         jsPath + 'raw/jquery-ahm.js',
//         jsPath + 'raw/jquery-run.js',
//         jsPath + 'foundation.js',
//         jsPath + 'raw/typeahead.js',
//         jsPath + 'raw/rdcf.js',
//         jsPath + 'raw/social-likes.min.js',
//     ])
//     .pipe(concat('main.js'))
//     .pipe(uglify2().on('error', function(e){
//         console.log(e);
//      }))
//     .pipe(gulp.dest(jsPath));
// });


// gulp.task('watch', function(event) {
//   console.log(sassPath);
//   gulp.watch(sassPath + '**', function() {
//         setTimeout(function () { 
//             gulp.start('sass');
//         }, 1000); // this timeout fix problem with sftp file transfer
//     });
// });









