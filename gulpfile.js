/* NAČTENÍ POTŘEBNÝCH DOPLŇKŮ */
const gulp = require("gulp");

const gIf = require("gulp-if"),
    gutil = require("gulp-util");

const less = require("gulp-less"),
    browserSync = require("browser-sync").create(),
    cssnano = require("gulp-cssnano"),
    del = require("del"),
    imagemin = require("gulp-imagemin"),
    cache = require("gulp-cache"),
    rename = require("gulp-rename"),
    LessAutoPrefix = require("less-plugin-autoprefix"),
    sourcemaps = require("gulp-sourcemaps"),
    ftp = require("vinyl-ftp"),
    clean = require("gulp-clean");

/* KONSTANTY */
const devRoot = "src-front";
const publicRoot = "www";

// Cesty
// src -> zdrojová adresa (= odkud se to vezme)
// dest -> koncová adresa (= kam se to dá)
const paths = {
    less : {
        src : devRoot + "/less/**/*.less",
        mainFile : devRoot + "/less/core.less",
        maps: "./maps",
        dest : devRoot + "/css",
    },
    html : {
        src : devRoot + "/templates/**/*.html",
    },
    js : {
        src : publicRoot + "/js/**/*.js",
    },
    img : {
        src : publicRoot + "/img/**/*.+(png|jpg|jpeg|gif|svg)",
        dist : devRoot + "/img",
    },
    font : {
        src : publicRoot + "/fonts/**/*",
    },
    css : {
        src : devRoot + "/css/styles.css",
        dest : publicRoot + "/css",
        dev: devRoot + "/css/**/*" // Zkompilovaný LESS připravený na odeslání na veřejný server
    }
};

/* ÚKONY - VÝVOJ (LESS, HTML) */
// Odstranění CSS souborů ve vývojovém adresáři vzniklých kompilací
async function cleanCss() {
    await del(paths.less.dest);
}

// Kompilace LESSu
const compileLess = () => {
    return gulp.src(paths.less.mainFile)
    // Sourcemaps pro zobrazení konkrétních LESS souborů v průzkumníku prohlížeče
        .pipe(sourcemaps.init())
        // Kompilace LESS na CSS
        .pipe(less({
            plugins : [
                // Autoprefixer pro zachování kompatibility pro starší prohlížeče
                new LessAutoPrefix({browsers : ["last 2 versions"]})
            ]
        }))
        .pipe(rename("styles.css"))
        .pipe(sourcemaps.write(paths.less.maps))
        .pipe(gulp.dest(paths.less.dest))
        // Aktualizace prohlížeče
        .pipe(browserSync.stream())
};

/* ÚKONY - ODESLÁNÍ NA VEŘEJNÝ SERVER (TESTOVÁNÍ GRAFIKY) */
async function deploy() {
    const config = require("./src-front/config/ftp-credentials.json");

    const connection = ftp.create({
        host : config.ftp.host,
        user : config.ftp.user,
        password : config.ftp.password,
        parallel : 10,
        log : gutil.log
    });

    // Cesty k datům z veřejného adresáře
    // Mají stejný postup pro nahrátí, takže se dají seskupit
    const publicSources = [
        paths.js.src,
        paths.font.src,
        paths.img.src
    ];

    // CSS
    await gulp.src(paths.css.dev, {base : ".", buffer : false})
        .pipe(rename((path) => {
            // Opravení koncového adresáře (původně se bere cesta od base)
            path.dirname = path.dirname.replace("src-front/", "");
        }))
        .pipe(connection.newer("/"))
        .pipe(connection.dest("/"));

    // HTML
    await gulp.src(paths.html.src, {base : ".", buffer : false})
        .pipe(rename((path) => {
            // Opravení koncového adresáře (původně se bere cesta od base)
            path.dirname = path.dirname.replace("src-front/templates/", "");
        }))
        .pipe(connection.newer("/"))
        .pipe(connection.dest("/"));

    // Data z veřejného adresáře
    await gulp.src(publicSources, {base : ".", buffer : false})
        .pipe(rename((path) => {
            // Opravení koncového adresáře (původně se bere cesta od base)
            path.dirname = path.dirname.replace("www/", "");
        }))
        .pipe(connection.newer("/"))
        .pipe(connection.dest("/"));
}

/* ÚKONY - PUBLIKACE DO WWW (CSS) */
// HTML se nedá automaticky publikovat, slouží jako šablona pro tvorbu .latte souborů
// Minifikace a přesun CSS do veřejného adresáře
const publishCss = () => {
    return gulp.src(paths.css.src)
        .pipe(gIf("*.css", cssnano())) // CSS minify
        .pipe(gulp.dest(paths.css.dest))
};

/* ÚKONY - SPECIÁLNÍ */
// Promazání cache
const clearCache = (callback) => {
    return cache.clearAll(callback);
};

// Minifikace obrázků
const minifyImages = () => {
    return gulp.src(paths.img.src)
        .pipe(cache(imagemin({
            interlaced : true
        })))
        .pipe(gulp.dest(paths.img.dist))
};

/* ŘÍDÍCÍ FUNKCE */

// Sleduje změny front-endových souborů a zajišťuje běh Browser sync pro změny v prohlížeči v reálném čase
const startBrowserSync = () => {
    // Prvotní kompilace LESSu ještě před spuštěním (když by proběhly změny mimo puštění této události)
    compileLess();

    // Konfigurace Browser sync
    browserSync.init({
        server : {
            baseDir : devRoot,
            index : "templates/index.html"
        }
    });

    // Sledování změn LESS souborů kvůli kompilaci (při každé změně proběhne automatická kompilaceú
    gulp.watch(paths.less.src, compileLess);

    // Sledování změn v ostatních souborech, při změně dojde k automatickému přenačtení stránky v prohlížeči
    gulp.watch([
        paths.html.src,
        paths.js.src,
        paths.img.src
    ]).on("change", browserSync.reload);
};

/* EXPORTY */
// Vývoj
exports.cleanCss = cleanCss;
exports.default = startBrowserSync;

// Testování grafiky (odeslání na veřejný server)
exports.deploy = deploy;

// Publikace do www
exports.publish = publishCss;

// Speciální příkazy
exports.clearCache = clearCache;
exports.imageMin = minifyImages;