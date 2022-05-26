const mix = require("laravel-mix");
require("dotenv").config();

let devProxy = process.env.BASE_URL;

mix
    .setPublicPath("public")
    .browserSync({
        browser: "Firefox Developer Edition",
        proxy: devProxy,
        files: [
            "src/templates/**/*.twig",
            "src/templates/**/*.html",
            "public/assets/css/theme.css",
            "public/assets/js/theme.js"
        ]
    })
    .js("src/mix/cp.js", "public/cpresources/")
    .postCss("src/mix/cp.css", "public/cpresources/", [    require("tailwindcss")("tailwind.config.js"),  ])

// versioning in Production only
if (mix.inProduction()) {
    mix.version();
}