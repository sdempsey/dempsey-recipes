var timer = require("grunt-timer");

module.exports = function(grunt) {
	timer.init(grunt, {deferLogs: true, friendlyTime: true});
  	"use strict";

  	var globalConfig = {
  		path: 'wp-content/themes/skeleton' //update your theme name,
  	};

	grunt.initConfig({
		globalConfig: globalConfig,
		pkg: grunt.file.readJSON("package.json"),
		autoprefixer: {
			devMain: {
				options: {
					map: true,
				},
				expand: true,
				flatten: true,
				src: '<%= globalConfig.path %>/css/src/style.css',
				dest: '<%= globalConfig.path %>/'
			},
			devAll: {
				options: {
					map: true
				},
				expand: true,
				flatten: true,
				src: ['<%= globalConfig.path %>/css/src/*.css', '!<%= globalConfig.path %>/css/src/style.css'],
				dest: '<%= globalConfig.path %>/css/'
			},
			production: {
				expand: true,
				flatten: true,
				src: ['<%= globalConfig.path %>/css/src/*.css'],
				dest: '<%= globalConfig.path %>/css/src'
			}
		},
		clean: { //removes src files after tasks are completed
			development: {
				src: ["<%= globalConfig.path %>/css/src"]
			},
			production: {
				src: ["<%= globalConfig.path %>/css/src", "<%= globalConfig.path %>/style.css.map", "<%= globalConfig.path %>/css/*.css.map"]
			}
		},
	    cmq: {
      		debug: {
	        	files: { "<%= globalConfig.path %>/css/src": ["<%= globalConfig.path %>/css/src/*.css"] }
	      	}
	    },
		concat: { //concatenates .js files into one.
			debug: {
				src: '<%= globalConfig.path %>/scripts/src/*.js',
				dest: '<%= globalConfig.path %>/scripts/site/global.js'
			}
		},
		cssmin: {
			css: {
				options: {
					relativeTo: '<%= globalConfig.path %>/',
					keepSpecialComments: 0,
					roundingPrecision: -1,
					advanced: false //try toggling this.  Leaving it set to true produced weird results in my tests.
				},
				expand: true,
				cwd: '<%= globalConfig.path %>/css/src',
				src: ['*.css', '!style.css'],
				dest: 'css',
				ext: '.css'
			},
			root: {
				options: {
					relativeTo: '<%= globalConfig.path %>/',
					keepSpecialComments: 0,
					roundingPrecision: -1,
					advanced: false //try toggling this.  Leaving it set to true produced weird results in my tests.
				},
				expand: true,
				cwd: '<%= globalConfig.path %>/css/src',
				src: ['style.css'],
				dest: '.',
				ext: '.css'
			}
		},
		imagemin: { //optimizes images
			debug: {
				options: {
					optimizationLevel: 7
				},
				files: [{
					expand: true,
					cwd: '<%= globalConfig.path %>/images/src/',
					src: '**/*.{jpg,png,gif,svg}',
					dest: 'images/'
				}]
			}
		},
		jshint: { // stops compiling when you write bad js.
			// options: {
			// 	jshintrc: true
			// },
			all: ['<%= globalConfig.path %>/scripts/src/*.js']
		},
		modernizr: {
		    dist: {
		        // [REQUIRED] Path to the build you're using for development.
		        "devFile" : "<%= globalConfig.path %>/scripts/libraries/modernizr.js",

		        // Path to save out the built file.
		        "outputFile" : "<%= globalConfig.path %>/scripts/libraries/modernizr-custom.min.js",

		        // Based on default settings on http://modernizr.com/download/
		        "extra" : {
		            "shiv" : true,
		            "printshiv" : false,
		            "load" : true,
		            "mq" : true,
		            "cssclasses" : true
		        },

		        // Based on default settings on http://modernizr.com/download/
		        "extensibility" : {
		            "addtest" : true,
		            "prefixed" : false,
		            "teststyles" : false,
		            "testprops" : false,
		            "testallprops" : false,
		            "hasevents" : false,
		            "prefixes" : false,
		            "domprefixes" : false,
		            "cssclassprefix": ""
		        },

		        // By default, source is uglified before saving
		        "uglify" : true,

		        // Define any tests you want to implicitly include.
		        "tests" : ['rgba'],

		        // By default, this task will crawl your project for references to Modernizr tests.
		        // Set to false to disable.
		        "parseFiles" : true,

		        // When parseFiles = true, this task will crawl all *.js, *.css, *.scss and *.sass files,
		        // except files that are in node_modules/.
		        // You can override this by defining a "files" array below.
		        // "files" : {
		            // "src": []
		        // },

		        // This handler will be passed an array of all the test names passed to the Modernizr API, and will run after the API call has returned
		        // "handler": function (tests) {},

		        // When parseFiles = true, matchCommunityTests = true will attempt to
		        // match user-contributed tests.
		        "matchCommunityTests" : false,

		        // Have custom Modernizr tests? Add paths to their location here.
		        "customTests" : []
		    }

		},
		watch: { //checks for specified changes, refreshes browser if plugin is installed
			options: { livereload: true},
			icon: {
				files: '<%= globalConfig.path %>/fonts/fontcustom/src/*.svg',
				tasks: ['webfont', 'css']
			},
			scripts: {
				files: '<%= globalConfig.path %>/scripts/src/*.js',
				tasks: ['js']
			},
			css: {
				files: ['<%= globalConfig.path %>/scss/**/*.scss'],
				tasks: ['css']
			},
			img: {
				files: '<%= globalConfig.path %>/images/src/**/*.{jpg,gif,png,svg}',
				tasks: ['img']
			},
			php: {
				files: '*.php',
				tasks: []
			}
		},
		sass: { //compiles your sass!
			development: {
				options: {
					sourceMap: true,
					precision: 3
				},
				files: {
					'<%= globalConfig.path %>/css/src/style.css': '<%= globalConfig.path %>/scss/style.scss',
					'<%= globalConfig.path %>/css/src/editor-style.css': '<%= globalConfig.path %>/scss/editor-style.scss',
					'<%= globalConfig.path %>/css/src/fonts.css': '<%= globalConfig.path %>/scss/fonts.scss'
				}
			},
			production: {
				options: {
					precision: 3
				},
				files: {
					'<%= globalConfig.path %>/css/src/style.css': '<%= globalConfig.path %>/scss/style.scss',
					'<%= globalConfig.path %>/css/src/editor-style.css': '<%= globalConfig.path %>/scss/editor-style.scss',
					'<%= globalConfig.path %>/css/src/fonts.css': '<%= globalConfig.path %>/scss/fonts.scss'
				}
			}
		},
    	uglify: { //still testing this.
      		options: {
        		mangle: {
        	  		except: ["jQuery"]
        		},
        		screwIE8: true,
    			preserveComments: "none"
  			},
      		"<%= globalConfig.path %>/scripts/site/global.js": ["<%= globalConfig.path %>/scripts/site/global.js"],
      		"<%= globalConfig.path %>/scripts/site/polyfills.js": ["<%= globalConfig.path %>/scripts/site/polyfills.js"]
    	},
		webfont: { // I use this, you don't have to.  It generates icon fonts using fontforge.
			icons: {
				src: '<%= globalConfig.path %>/fonts/fontcustom/src/*.svg',
				dest: '<%= globalConfig.path %>/fonts/fontcustom',
				destCss: '<%= globalConfig.path %>/scss/global/icon-font',
				options: {
					engine: 'node', //if you're on a mac I suggest installing fontforge and setting this to fontforge.
					font: 'fontcustom',
					hashes: false,
					stylesheet: 'scss',
					relativeFontPath: '<%= globalConfig.path %>/fonts/fontcustom/',
					htmlDemo: false,
					template: '<%= globalConfig.path %>/fonts/fontcustom/template/template.css',
					templateOptions: {
						classPrefix: 'icon-',
						mixinPrefix: 'icon-'
					}
				}
			}
		}
	});
	require("load-grunt-tasks")(grunt);
	grunt.registerTask('js', ['jshint', 'concat']);
	grunt.registerTask('css', ['sass:development', 'autoprefixer:devMain', 'autoprefixer:devAll', 'clean:development']);
	grunt.registerTask('img', ['newer:imagemin']);
	grunt.registerTask('deploy', ['img', 'jshint', 'concat', 'modernizr', 'sass:production', 'cmq', 'autoprefixer:production', 'cssmin', 'clean:production']);
	grunt.registerTask('default', ['js', 'css', 'img']);
}