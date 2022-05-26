// tailwind.config.js

module.exports = {
    content: [
        "./src/templates/**/*.html",
        "./src/templates/**/*.twig",
        "./src/js/theme.js",
        "./templates/**/*.html",
        "./templates/**/*.twig",
    ],
    theme: {
        screens: {
            sm: "640px",
            md: "1024px",
            lg: "1200px",
            xl: "1440px",
        },
        extend: {
            spacing: {
                "3px": "3px",
                "32px": "32px",
                "48px": "48px",
                "64px": "64px",
                "84px": "84px",
                "128px" : "128px",
                "256px" : "256px",
                "300px" : "300px",
                "400px" : "400px",
                "500px" : "500px",
                "600px" : "600px",
                "800px" : "800px",
                "900px" : "900px",
                5.5: "1.375rem",
                18: "4.5rem",
                48: "12rem",
                112: "28rem",
                128: "32rem",
                164: "48rem",
                200: "56rem",
                256: "64rem",
                '1/4': '25%',
                '1/3': '33.333333%',
                '1/2': '50%',
                '2/3': '66.666666%',
                '3/4': '75%',
                '1/5': '20%',
                '2/5': '40%',
                '3/5': '60%',
                '4/5': '80%',
                '1/10': '10%',
                '2/10': '20%',
                '3/10': '30%',
                '4/10': '40%',
                '5/10': '50%',
                '6/10': '60%',
                '7/10': '70%',
                '8/10': '80%',
                '9/10': '90%',
            },
            colors: {
                "brand-gray": {
                    DEFAULT: "#727B80",
                    "50": "#EBECED",
                    "100": "#DDE0E1",
                    "200": "#C2C6C9",
                    "300": "#A7ADB1",
                    "400": "#8C9499",
                    "500": "#727B80",
                    "600": "#5A6165",
                    "700": "#42474A",
                    "800": "#2A2D2F",
                    "900": "#121314",
                },
                "brand-lime": {
                    DEFAULT: "#8CC63E",
                    "50": "#EBF5DD",
                    "100": "#E0F0CC",
                    "200": "#CBE5A8",
                    "300": "#B6DB85",
                    "400": "#A1D061",
                    "500": "#8CC63E",
                    "600": "#74A531",
                    "700": "#5B8226",
                    "800": "#425E1C",
                    "900": "#293B11",
                },
                "brand-navy": {
                    DEFAULT: "#004C7F",
                    "50": "rgb(222, 242, 255)",
                    "100": "#B9DFF8",
                    "200": "#78BEED",
                    "300": "#2B9FED",
                    "400": "#007ACC",
                    "500": "#004C7F",
                    "600": "#00406B",
                    "700": "#003456",
                    "800": "#002742",
                    "900": "#001B2D",
                },
                "brand-orange": {
                    DEFAULT: "#f59202",
                    '100': "#fcab35",
                },
                "brand-teal": {
                    DEFAULT: "#4186a5",
                },
                "insight" : {
                    DEFAULT: "#3982A1",
                },
                "trophy" : {
                    DEFAULT : "#F7CD46",
                },
                social: {
                    twitter: "#00aced",
                    facebook: "#3b5998",
                    pinterest: "#cb2027",
                    linkedin: "#007bb6",
                    youtube: "#bb0000",
                    instagram: "#517fa4",
                    vimeo: "#aad450",
                },
            },
            fontFamily: {
                'sans' : ['Work Sans', 'sans-serif']
            },
            minHeight: (theme) => ({
                ...theme("spacing"),
            }),
            minWidth: (theme) => ({
                //...theme('spacing'),
            }),
            zIndex: {
                //'99': 99,
            },
        },
    },
    variants: {
        extend: {
            //fontWeight: ['hover', 'focus']
        },
    },
    plugins: [],
};