const gulp = require('gulp');
const concat = require('gulp-concat');
const cleanCSS = require('gulp-clean-css');
const uglify = require('gulp-uglify');
const babel = require('gulp-babel');
const sass = require('gulp-sass');
sass.compiler = require('node-sass');

gulp.task('styles', () =>
  gulp.src('./assets/scss/main.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(cleanCSS({compatibility: 'ie8'}))
    .pipe(concat('styles.css'))
    .pipe(gulp.dest('web/build'))
);

gulp.task('scripts', () => gulp.src([
    'assets/js/**/*.js',
    'assets/js/*.js'
  ])
    .pipe(babel({presets: ["@babel/preset-env"]}))
    .pipe(uglify({keep_fnames: true}))
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest('web/build'))
);

gulp.task('scripts:watch', () => gulp.src([
    'assets/js/**/*.js',
    'assets/js/*.js'
  ])
    .pipe(babel({presets: ["@babel/preset-env"]}))
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest('web/build'))
);

gulp.task('watch', () => {
  gulp.watch('assets/scss/**/*.scss', gulp.series(['styles']));
  gulp.watch('assets/js/**/*.js', gulp.series(['scripts:watch']));
});

gulp.task('build', gulp.parallel(['scripts', 'styles']));