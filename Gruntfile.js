'use strict';

module.exports = function(grunt){

	const sass = require('node-sass');

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		watch: {
			'default': {
				files: ['Gruntfile.js', 'assets/sass/*.scss', 'assets/svg/**/*.svg'],
				tasks: ['sass:dev', 'autoprefixer']
			},
			'svg': {
				files: ['Gruntfile.js', 'public/img/svg-dev/*.svg', 'assets/svg/**/*.svg'],
				tasks: ['svgmin', 'svgstore']
			}
		},
		sass: {
			options:{
				implementation: sass,
				sourceMap: true,
				outFile: 'public/style.css',
			},
			dev: {
				files: {
					'public/style.css': 'assets/sass/styles.scss',
				},
				options:{
					style: 'expanded',
				},
			},
		},
		autoprefixer: {
			options: {
				browsers: ['last 3 versions', 'Android >= 2.1', 'iOS >= 7'],
				map: {
					annotation: 'style.css.map',
				}

			},
			prefix: {
				src: 'public/style.css',
				dest: 'public/style.css'
			},
		},
		svgmin: {
	        options: {
	            plugins: [
	                { removeViewBox: false },
	                { removeUselessStrokeAndFill: false }
	            ]
	        },
	        dist: {
	            files: [{
                    expand: true,
                    cwd: 'templates/assets',
                    src: '*.svg',
                    dest: 'templates/assets'
                }]
	        }
	    },
		svgstore: {
		    options: {
		      prefix : 'icon-',
		      svg: {
		        viewBox : '0 0 100 100',
		        xmlns: 'http://www.w3.org/2000/svg'
		      }
		    },
		    your_target: {
		      files:{
		      	'templates/assets/svgs.svg' : ['assets/svg/sprite/*.svg']
		      },
		    },
		},
		copy: {
			svg: {
				expand:true,
				cwd: 'assets/svg/',
				src: 'logo.svg',
				dest: 'templates/assets/',
			},
			png: {
				expand:true,
				cwd: 'assets/png/',
				src: '*.png',
				dest: 'public/img/',
			},
			js: {
				files: [{
					expand: true,
					cwd: 'node_modules/masonry-layout/dist/',
					src: 'masonry.pkgd.min.js',
					dest: 'public/js/',
				},{
					expand: true,
					cwd: 'node_modules/jquery.scrollto/',
					src: 'jquery.scrollTo.min.js',
					dest: 'public/js/',
				},{
					expand: true,
					cwd: 'node_modules/jquery/dist/',
					src: 'jquery.min.js',
					dest: 'public/js/',
				},{
					expand: true,
					cwd: 'assets/js',
					src: '**/*.js',
					dest: 'public/js/',
				}],
			},
		},
		downloadfile: {
			options: {
				serverUrl: '',
			},
			files: {
				'public/js/snowstorm-min.js': 'https://raw.githubusercontent.com/scottschiller/Snowstorm/snowstorm_20131208/snowstorm-min.js',
			}
		},
		clean: {
			svg: ['templates/assets/*.svg'],
			png: ['public/**/*.png'],
			css: ['public/**/*.css', 'public/**.css.map'],
			js: ['public/**/*.js'],
		}
	});

	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-downloadfile');
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-svgmin');
	grunt.loadNpmTasks('grunt-svgstore');

	grunt.registerTask('css', ['sass', 'autoprefixer']);
	grunt.registerTask('svg', ['svgstore', 'copy:svg', 'svgmin']);
	grunt.registerTask('png', ['copy:png']);
	grunt.registerTask('js', ['copy:js']);
	grunt.registerTask('default', ['css', 'svg', 'png', 'js']);
}
