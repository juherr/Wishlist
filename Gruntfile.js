module.exports = function(grunt){

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		watch: {
			'default': {
				files: ['Gruntfile.js', 'public/sass/*.scss', 'public/img/svg-dev/*.svg'],
				tasks: ['sass:dev', 'autoprefixer']
			},
			'svg': {
				files: ['Gruntfile.js', 'public/img/svg-dev/*.svg', 'public/img/svg-dev/sprite/*.svg'],
				tasks: ['svgmin', 'svgstore']
			}
		},
		sass: {
			options:{
				sourceMap: true,
				outFile: "style.css",
			},
			dev: {
				files: {
					'style.css': 'public/sass/styles.scss',
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
				src: 'style.css',
				dest: 'style.css'
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
                    cwd: 'public/img/svg-dev',
                    src: '*.svg',
                    dest: 'public/img/svg-prod'
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
		      	'public/img/svg-prod/sprite/svgs.svg' : ['public/img/svg-dev/sprite/*.svg'],
		      },
		    },
		},
	});

	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-svgmin');
	grunt.loadNpmTasks('grunt-svgstore');
}
