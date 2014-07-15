'use strict';

/**
 * Grunt Module
 */
module.exports = function(grunt) {

    /**
     * Configuration
     */
    grunt.initConfig({

        /**
         * Get package meta data
         */
        pkg: grunt.file.readJSON('package.json'),

        /**
         * Set project object
         */
        project: {
            app: 'app',
            assets: '<%= project.app %>/assets',
            css: [
                '<%= project.assets %>/scss/style.scss'
            ],
            js: [
                'public/js/*.js'
            ]
        },

        /**
         * Project banner
         */
        tag: {
            banner: '/*!\n' +
                ' * <%= pkg.name %>\n' +
                ' * <%= pkg.title %>\n' +
                ' * <%= pkg.url %>\n' +
                ' * @author <%= pkg.author %>\n' +
                ' * @version <%= pkg.version %>\n' +
                ' * Copyright <%= pkg.copyright %>. <%= pkg.license %> licensed.\n' +
                ' */\n'
        },

        /**
         * uglify
         */
        uglify: {
            my_target: {
                files: {
                    'public/assets/js/bootstrap.min.js': ['public/assets/js/bootstrap.js'],
                    'public/assets/js/jquery.cookie.min.js': ['public/assets/js/jquery.cookie.js'],
                    'public/assets/js/jquery.validate.min.js': ['public/assets/js/jquery.validate.js']
                }
            }
        },

        /**
         * jshint
         */
        jshint: {
            all: [
                'public/assets/js/loottracker.js',
                'public/assets/js/jquery.cookie.js',
                'public/assets/js/jquery.validate.js'
            ]
        }
    });

    /**
     * Load Grunt plugins
     */
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');

    /**
     * Default task
     * Run `grunt` on the command line
     */
    grunt.registerTask('default', [
        'uglify',
        'jshint'
    ]);

    /**
     * Build task
     * Run `grunt build` on the command line
     * Then compress all JS/CSS files
     */
    grunt.registerTask('build', [
    ]);

};