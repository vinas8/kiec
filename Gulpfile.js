'use strict';

var gulp     = require('gulp');
var sass     = require('gulp-sass');
var concat   = require('gulp-concat');
var uglify   = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');

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
            dir.npm + 'moment/min/moment-with-locales.js',
            dir.npm + 'eonasdan-bootstrap-datetimepicker/build/js/boostrap-datetimepicker.min.js',

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
        dir.npm + 'font-awesome/css/font-awesome.min.css',
        dir.npm + 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css'
        ])
        .pipe(cleanCSS({ compatibility: 'ie8' }))
        .pipe(concat('adminlte.css'))
        .pipe(gulp.dest(dir.dist + 'css'));
});

gulp.task('default', ['sass', 'scripts', 'fonts', 'images', 'adminlte']);
