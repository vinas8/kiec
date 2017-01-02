'use strict';

var gulp       = require('gulp');
var sass       = require('gulp-sass');
var concat     = require('gulp-concat');
var uglify     = require('gulp-uglify');
var babelify   = require('babelify');
var browserify = require('browserify');
var rename     = require('gulp-rename');
var source     = require('vinyl-source-stream');
var buffer     = require('vinyl-buffer');
var cleanCSS   = require('gulp-clean-css');

var dir = {
    assets: './src/AppBundle/Resources/',
    dist: './web/',
    npm: './node_modules/',
};

gulp.task('sass', function() {
    gulp.src(dir.assets + 'style/main.scss')
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(concat('style.css'))
        .pipe(gulp.dest(dir.dist + 'css'));
});

gulp.task('scripts', function() {
    gulp.src([
            //Third party assets
            dir.npm + 'jquery/dist/jquery.min.js',
            dir.npm + 'bootstrap-sass/assets/javascripts/bootstrap.min.js',
            dir.npm + 'admin-lte/dist/js/app.min.js',
            dir.npm + 'admin-lte/plugins/datepicker/bootstrap-datepicker.js',
            dir.npm + 'admin-lte/plugins/datepicker/locales/bootstrap-datepicker.lt.js',
            dir.npm + 'admin-lte/plugins/timepicker/bootstrap-timepicker.js',
            dir.npm + 'moment/min/moment-with-locales.js',
            dir.npm + 'chart.js/dist/Chart.js',

            // Main JS file
            dir.assets + 'scripts/main.js'
        ])
        .pipe(concat('script.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.dist + 'js'));
});

gulp.task('images', function() {
    gulp.src([
            dir.assets + 'images/**'
        ])
        .pipe(gulp.dest(dir.dist + 'images'));
});

gulp.task('fonts', function() {
    gulp.src([
        dir.npm + 'bootstrap-sass/assets/fonts/**',
        dir.npm + 'font-awesome/fonts/**'
        ])
        .pipe(gulp.dest(dir.dist + 'fonts'));
});

gulp.task('adminlte', function() {
    gulp.src([
        dir.npm + 'admin-lte/dist/css/AdminLTE.min.css',
        dir.npm + 'admin-lte/dist/css/skins/skin-red.min.css',
        dir.npm + 'admin-lte/plugins/datepicker/datepicker3.css',
        dir.npm + 'admin-lte/plugins/timepicker/bootstrap-timepicker.css',
        dir.npm + 'font-awesome/css/font-awesome.min.css'
        ])
        .pipe(cleanCSS({compatibility: 'ie8', processImport: false}))
        .pipe(concat('adminlte.css'))
        .pipe(gulp.dest(dir.dist + 'css'));
});

gulp.task('timeline', function() {
    return browserify(dir.assets + 'scripts/timeline.js').transform(babelify, {presets: ["es2015", "react"]})
        .bundle()
        .pipe(source('timeline.js'))
        .pipe(buffer())
        .pipe(gulp.dest(dir.dist + 'js'));
});

gulp.task('default', ['sass', 'scripts', 'fonts', 'images', 'adminlte', 'timeline']);
