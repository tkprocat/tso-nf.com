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
            production: {
                files: {
                    'public/assets/js/loottracker.min.js': ['public/assets/js/loottracker.js'],
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
        },

        /**
         * less
         */
        less: {
            default: {
                options: {
                    paths: ["public/assets/css"]
                },
                files: {
                    'public/assets/css/bootstrap-amelia.css': ['public/assets/css/amelia/bootstrap.less'],
                    'public/assets/css/bootstrap-cosmo.css': ['public/assets/css/cosmo/bootstrap.less'],
                    'public/assets/css/bootstrap-readable.css': ['public/assets/css/readable/bootstrap.less'],
                    'public/assets/css/bootstrap.css': ['public/assets/css/slate/bootstrap.less']
                }
            }
        },

        copy: {
            bootstrapjs:{
                files: [
                    {
                        cwd: 'public/assets/bower/bootstrap/dist/js/',
                        src: 'bootstrap.js',
                        dest: 'public/assets/js/',
                        expand: true
                    }
                ]
            },
            datepickerjs:{
                files: [
                    {
                        cwd: 'public/assets/bower/bootstrap-3-datepicker/js/',
                        src: 'bootstrap-datepicker.js',
                        dest: 'public/assets/js/',
                        expand: true
                    }
                ]
            },
            sortablejs:{
                files: [
                    {
                        cwd: 'public/assets/bower/bootstrap-sortable/Scripts/',
                        src: 'bootstrap-sortable.js',
                        dest: 'public/assets/js/',
                        expand: true
                    }
                ]
            }
        }
    });

    /**
     * Load Grunt plugins
     */
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-less');

    /**
     * Default task
     * Run `grunt` on the command line
     */
    grunt.registerTask('default', [
        'copy:bootstrapjs',
        'copy:datepickerjs',
        'copy:sortablejs',
        'uglify',
        'less',
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