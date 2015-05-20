// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var jshint = require('gulp-jshint');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var minify = require("gulp-minify");
var gutil = require('gulp-util');
var notify = require("gulp-notify");


// Lint Task
gulp.task('lint', function() {
    return gulp.src('src/js/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// Compile Our Sass
gulp.task('sass', function() {
    return gulp.src([
        'src/scss/app.scss'
    ]).pipe(notify("Sass started"))
    .pipe(sass())
    .pipe(concat('app.css'))
    .pipe(gulp.dest('webroot/css'))
    .pipe(notify("Sass completed"));
});

// css used by js plugins - specific to /admin/ pages
gulp.task('admin-css', function() {
   return gulp.src([
      'bower_components/datetimepicker/jquery.datetimepicker.css',
      'bower_components/alertifyjs/dist/css/alertify.css'
   ]).pipe(concat("js.css"))
   .pipe(gulp.dest('webroot/css'));
});

// Concatenate & minifyjs for /admin/ pages
gulp.task('scripts', function() {
    return gulp.src([
        'bower_components/jquery/**/*.min.js',
        'bower_components/datetimepicker/jquery.datetimepicker.js',
        'bower_components/alertifyjs/dist/js/alertify.js',
        'bower_components/jquery-numeric/dist/jquery-numeric.js',
        'src/js/**/*.js'
    ]).pipe(notify("JS started"))
        .pipe(concat('admin.min.js'))
        .pipe(uglify().on('error', gutil.log))
        .pipe(gulp.dest('webroot/js'))
        .pipe(notify("JS completed"));
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('src/js/**/*.js', ['lint', 'js']);
    gulp.watch('src/scss/*.scss', ['sass']);
});

gulp.task('js', ['scripts', 'admin-css']);

// Default Task
gulp.task('default', ['lint', 'sass', 'js', 'watch']);