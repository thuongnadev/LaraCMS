    /** @type {import('tailwindcss').Config} */
    module.exports = {
      content: [
        "./Resources/**/*.blade.php",
        "./Resources/**/*.js",
        "./Resources/**/*.vue",
        "./Resources/**/*.scss",
        './node_modules/flowbite/**/*.js',
      ],
      theme: {
        extend: {},
      },
      plugins: [
          require('flowbite/plugin')
      ],
    }
