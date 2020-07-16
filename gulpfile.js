var gulp = require('gulp');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');
var php = require('gulp-connect-php');
var browserSync = require('browser-sync');
var flatten = require('gulp-flatten');
var reload = browserSync.reload;


// Compile Our Sass
gulp.task('styles', function() {
    
    return gulp.src('resources/sass/**/*.scss')
        .pipe(sass().on('error', sass.logError))       
        .pipe(cleanCSS())
        .pipe(gulp.dest('public/css/'));
});

// Compile Our script
gulp.task('scripts', function() {     
    return gulp.src('./resources/js/**/*.js')
      .pipe(uglify())
      .pipe(gulp.dest('public/js'));
});

//  copy necessary files
gulp.task('copy', function(resolve) {
    gulp.src('./node_modules/font-awesome/fonts/*').pipe(gulp.dest('public/fonts'));
    gulp.src('./node_modules/font-awesome/css/*').pipe(gulp.dest('public/css'));
    gulp.src('./node_modules/popper.js/dist/umd/po*.js').pipe(gulp.dest('public/js/plugins'));
    gulp.src('./node_modules/jquery/dist/jq*').pipe(gulp.dest('public/js/plugins'));
    gulp.src('./node_modules/bootstrap/dist/css/boo*.min.css').pipe(gulp.dest('public/css'));
    gulp.src('./node_modules/bootstrap/dist/js/boo*.js').pipe(gulp.dest('public/js/plugins'));
    gulp.src('./resources/images/**/*').pipe(gulp.dest('public/images'));
    gulp.src('./resources/css/*.min.css').pipe(gulp.dest('public/css'));
    gulp.src('./resources/plugins/**/*').pipe(gulp.dest('public/plugins'));
    gulp.src('./resources/fonts/**/*').pipe(gulp.dest('public/fonts'));  
gulp.src('./resources/file_tax/**/*').pipe(gulp.dest('public/file_tax'));   
    gulp.src('./node_modules/datatables.net-*/css/*.min.css').pipe(flatten()).pipe(gulp.dest('public/plugins/datatables.net-bs4/css'));    
    gulp.src('./node_modules/datatables.net-*/js/*.min.js').pipe(flatten()).pipe(gulp.dest('public/plugins/datatables.net-bs4/js'));    
    gulp.src('./node_modules/datatables.net/js/*.min.js').pipe(flatten()).pipe(gulp.dest('public/plugins/datatables.net-bs4/js'));    
    gulp.src('./node_modules/pdfmake/build/*.js').pipe(flatten()).pipe(gulp.dest('public/plugins/pdfmake'));    
    gulp.src('./node_modules/bootstrap-confirm-delete/**/*').pipe(gulp.dest('public/plugins/bootstrap-confirm-delete'));
    //
    gulp.src('./resources/js/OneSignalSDKUpdaterWorker.js').pipe(gulp.dest('public'));
    gulp.src('./resources/js/OneSignalSDKWorker.js').pipe(gulp.dest('public'));
    resolve();
});

gulp.task('browser-sync', function(){
  // note the .init
  browserSync.init({
     notify:false, 
     server: {
            baseDir: '.',            
            injectChanges: true 
     }     
  });
});

// Default Task
gulp.task('default', gulp.series('copy', 'styles', 'scripts'));


gulp.task('serve', gulp.series('copy', 'styles', 'scripts', function(){
     php.server({ base: './public', port: 8000, keepalive: true});
     browserSync({
        notify: false,
        proxy: 'http://localhost:8000'
    });
    gulp.watch(['./resources/views/**/*.php'], reload);
    gulp.watch(['./resources/js/*.js'],gulp.series('scripts'));
    gulp.watch(['./resources/sass/**/*.scss'], gulp.series('styles'));    
}));