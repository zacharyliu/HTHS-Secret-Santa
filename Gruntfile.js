module.exports = function(grunt) {

    grunt.initConfig({
        coffee: {
            target: {
                files: [{
                    expand: true,
                    cwd: 'js/',
                    src: ['*.coffee'],
                    dest: 'js/',
                    ext: '.js'
                }]
            }
        },
        less: {
            target: {
                files: [{
                    expand: true,
                    cwd: 'css/',
                    src: ['*.less'],
                    dest: 'css/',
                    ext: '.css'
                }]
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-coffee');
    grunt.loadNpmTasks('grunt-contrib-less');

    grunt.registerTask('default', ['coffee', 'less']);

};
