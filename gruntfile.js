module.exports = function(grunt) {
	require('time-grunt')(grunt);
	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		config: {
		},

		sass: { // Task
			dev: { // Target
				options: {
					//sourcemap: 'none',
					update: true,
				},
				files: { // Dictionary of files
					'public/style/style.css': 'scss/style.scss', // 'destination': 'source'
				}
			},
			prod: { // Target
				options: {
					sourcemap: 'none',
					update: true,
				},
				files: { // Dictionary of files
					'public/style/style.css': 'scss/style.scss', // 'destination': 'source'
				}
			},
		},

		jshint: {
			options: {
				reporter: require('jshint-stylish'),
				reporterOutput: "",
				browser: true,
				jquery: true,
				//devel : true
			},
			js: {
				src: [
						'js-source/script.js'
				],
			}

		},

		concat: {
			options: {},
			js: {
				src: [
						'js-source/jquery-3.2.1.slim.min.js',
						'js-source/popper.min.js',
						'js-source/bootstrap.min.js',
						'js-source/script.js'
				],
				dest: 'public/js/script.js',
				nonull: true,
			},
		},
		uglify: {
			js: {
				files: {
					'public/js/script.js': ['public/js/script.js']
				}
			},
		},

		watch: {
			scss: {
				files: [
						'scss/**/*.scss',
						'scss/*.scss',
				],
				tasks: ['sass:dev'],
				options: {
					spawn: true,
					interrupt: false,
					event: 'changed',
					interval: 500,
					debounceDelay: 500,
					//livereload : true,
				}
			},
			js: {
				files: [
						'js-source/**/*.js',
						'js-source/*.js',
				],
				tasks: ['jshint','concat'],
				options: {
					spawn: true,
					interrupt: false,
					event: 'changed',
					interval: 500,
					debounceDelay: 500,
					//livereload : true,
				}
			}
		},

		cssmin: {

			minify: {
				options: {
					debug: true,
					level: {
						1: {
							all: true, // set all values to `false`
						},
						2: {
							all: true, // set all values to `false`
						}
					},

				},
				expand: true,
				cwd: 'public/style/',
				src: ['*.css'],
				dest: 'public/style/',
			},

		},

		clean: {
			options: {
				force: true,
			},
			maps: ['public/style/*.map'],
			css: ['public/style/*'],
			js: ['public/js/*'],
		},

	});
	grunt.registerTask('default', ['dev', 'watch']);
	grunt.registerTask('dev', ['clean','sass:dev', 'jshint','concat']);
	grunt.registerTask('prod', ['clean','sass:prod', 'jshint','concat','uglify','cssmin','clean:maps']);
};
