// View your website at your own local server
// for example http://vite-php-setup.test

// http://localhost:3000 is serving Vite on development
// but accessing it directly will be empty

// IMPORTANT image urls in CSS works fine
// BUT you need to create a symlink on dev server to map this folder during dev:
// ln -s {path_to_vite}/src/assets {path_to_public_html}/assets
// on production everything will work just fine

//import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload'
const { resolve } = require('path')
const fs = require('fs')


// https://vitejs.dev/config
export default {

  plugins: [
    //vue(),
    liveReload([__dirname+'/**/*.php',__dirname+'/**/*.twig'])
  ],

  // config
  root: '',
  base: process.env.NODE_ENV === 'development'
    ? '/'
    : '/dist/',

  build: {
    // output dir for production build
    outDir: resolve(__dirname, './dist'),
    emptyOutDir: true,

    // emit manifest so PHP can find the hashed files
    manifest: true,

    // esbuild target
    target: 'es2018',

    // our entry
    rollupOptions: {
      input: {
        homepage: resolve( __dirname + '/js/homepage.js'),
        models: resolve( __dirname + '/js/models.js'),
        singleModel: resolve( __dirname + '/js/singleModel.js'),
        request: resolve( __dirname + '/js/request.js'),
        blog: resolve( __dirname + '/js/blog.js'),
        singleBlog: resolve( __dirname + '/js/singleBlog.js'),
        woocommerce: resolve( __dirname + '/js/woocommerce.js'),
        singleProduct: resolve( __dirname + '/js/singleProduct.js'),
        builds: resolve( __dirname + '/js/builds.js'),
        singleBuild: resolve( __dirname + '/js/singleBuild.js'),
        contact: resolve( __dirname + '/js/contact.js'),
        trademark: resolve( __dirname + '/js/trademark.js'),
        register: resolve( __dirname + '/js/register.js'),
        standard: resolve( __dirname + '/js/standard.js'),
      },
      output: {
        entryFileNames: '[name].js',
      }
    },

    // minifying switch
    minify: true,
    write: true
  },

  server: {

    // required to load scripts from custom host
    cors: true,

    // we need a strict port to match on PHP side
    // change freely, but update in your functions.php to match the same port
    strictPort: true,
    port: 3000,

    // serve over http
    https: false,

    // serve over httpS
    // to generate localhost certificate follow the link:
    // https://github.com/FiloSottile/mkcert - Windows, MacOS and Linux supported - Browsers Chrome, Chromium and Firefox (FF MacOS and Linux only)
    // installation example on Windows 10:
    // > choco install mkcert (this will install mkcert)
    // > mkcert -install (global one time install)
    // > mkcert localhost (in project folder files localhost-key.pem & localhost.pem will be created)
    // uncomment below to enable https
    //https: {
    //  key: fs.readFileSync('localhost-key.pem'),
    //  cert: fs.readFileSync('localhost.pem'),
    //},

    hmr: {
      host: 'localhost'
    },
    
  },

  // required for in-browser template compilation
  // https://v3.vuejs.org/guide/installation.html#with-a-bundler
  resolve: {
    alias: {
      //vue: 'vue/dist/vue.esm-bundler.js'
    }
  }
}

