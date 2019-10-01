/**
 * Gulp general setup.
 * Project related variables are defined in tools/gulp-project.js to keep gulpfile.js clean.
 */

// --- Gulp ---

const { src, dest, watch, series, parallel } = require('gulp');


// --- Gulp plugins ---

// CSS
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const purgecss = require('gulp-purgecss');
const cleancss = require('gulp-clean-css');

// JS
const uglify = require('gulp-uglify');

// Images
const imagemin = require('gulp-imagemin');

// Fonts
const ttf2woff = require('gulp-ttf2woff');
const ttf2woff2 = require('gulp-ttf2woff2');

// Icons
const faMinify = require('gulp-fa-minify');

// Utility
const rename = require('gulp-rename');
const sourcemaps = require('gulp-sourcemaps');
const zip = require('gulp-zip');
const del = require('del');
const gulpif = require('gulp-if');

// Browser
const browserSync  = require('browser-sync').create();
const cache = require('gulp-cache');


// --- Variables ---

// Project variables file
const projectVars = './tools/gulp-project';

// CSS
const cssSrc = './src/sass/**/*.scss';
const cssDist = './dist/css/';
const cssWatch = [ cssSrc ];

const purgeContent = ['**/*.php', 'src/**/*.js'];
const { purgeWLP } = require(projectVars);

// JS
const jsSrc = './src/js/**/*.js';
const jsDist = './dist/js/';
const jsWatch = [ jsSrc ];

// PHP
const phpWatch = [
    './**/*.php',
    '!node_modules/**',
    '!lib/**',
    '!vendor/**',
];

// Images
const imgSrc = './src/img/**/*.{png,jpg,gif,svg}';
const imgDist = './dist/img/';
const imgWatch = [ imgSrc ];

// Fonts
const fontsSrc = './src/fonts/**/*.ttf';
const fontsDist = './dist/fonts/';
const fontsWatch = [ fontsSrc ];

// Icons
const iconsSrc = './node_modules/@fortawesome/fontawesome-free/js/all.js';
const { iconsUsed } = require(projectVars);

// Zip package
const { pkgSrc } = require(projectVars);
const pkgDist = 'packages/';

// Browser Sync
const { siteUrl } = require(projectVars);

// Environment

function setDEV(done) {
    process.env.NODE_ENV = 'development';
    done();
}

function setPROD(done) {
    process.env.NODE_ENV = 'production';
    done();
}


// --- CSS functions ---

function css() {
    return src(cssSrc)
        .pipe(gulpif(process.env.NODE_ENV === 'development', sourcemaps.init()))
        .pipe(sass({
            errorLogToConsole: true,
        }))
        .on('error', console.error.bind(console))
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulpif(process.env.NODE_ENV === 'production', purgecss({
            content: purgeContent,
            whitelistPatterns: purgeWLP,
        })))
        .pipe(gulpif(process.env.NODE_ENV === 'production', cleancss()))
        .pipe(gulpif(process.env.NODE_ENV === 'development', sourcemaps.write('./')))
        .pipe(dest(cssDist))
        .pipe(browserSync.stream());
};


// --- JS functions ---

function js() {
    return src(jsSrc)
        .pipe(gulpif(process.env.NODE_ENV === 'development', sourcemaps.init()))
        .pipe(gulpif(process.env.NODE_ENV === 'production', uglify()))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulpif(process.env.NODE_ENV === 'development', sourcemaps.write('./')))
        .pipe(dest(jsDist))
        .pipe(browserSync.stream());
};


// --- Images functions ---

function img() {
    return src(imgSrc)
        .pipe(imagemin({
            verbose: true
        }))
        .pipe(dest(imgDist))
        .pipe(browserSync.stream());
};


// --- Fonts functions ---

function fonts(done) {
    src(fontsSrc)
        .pipe(ttf2woff())
        .pipe(dest(fontsDist))
        .pipe(browserSync.stream())
    src(fontsSrc)
        .pipe(ttf2woff2())
        .pipe(dest(fontsDist))
        .pipe(browserSync.stream())
    done();
};


// --- Icons functions ---

function icons() {
    return src(iconsSrc)
        .pipe(rename('fa5.min.js')) 
        .pipe(gulpif(process.env.NODE_ENV === 'production', faMinify(iconsUsed)))
        .pipe(gulpif(process.env.NODE_ENV === 'production', uglify()))
        .pipe(dest(jsDist));
};


// --- Utility functions ---

function clean() {
    return del('dist/**', {force:true});
};


// --- Packages functions ---

function pkg() {
    return src(pkgSrc, {base: '..'})
        .pipe(zip('archive.zip'))
        .pipe(dest(pkgDist));
};


// --- Browser functions ---

function browser_sync(done) {
	browserSync.init({
        open: false,
        injectChanges: true,
        // server: { baseDir: './dist/' },
        proxy: siteUrl,
        // tunnel: "sbbase",
    });
    done();
}

function reload(done) {
    browserSync.reload();
    done();
}

function clearCache(done) {
    cache.clearAll();
    done();
}


// --- Watch functions ---

function watch_files(done) {
    watch(cssWatch, series(css, clearCache, reload));
    watch(jsWatch, series(js, clearCache, reload));
    watch(phpWatch, series(clearCache, reload));
    watch(imgWatch, series(img, clearCache, reload));
    watch(fontsWatch, series(fonts, clearCache, reload));
    done();
}


// --- Tasks ---

exports.css = css;
exports.js = js;
exports.img = img;
exports.fonts = fonts;
exports.icons = icons;

exports.pkg = pkg;

exports.default = series(setDEV, clean, parallel(css, js, img, fonts, icons));
exports.prod = series(setPROD, clean, parallel(css, js, img, fonts, icons));
exports.watch = parallel(browser_sync, watch_files);
