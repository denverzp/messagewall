module.exports = function (grunt) {
        require('time-grunt')(grunt);
        // 1 - Описываем все выполняемые задачи
        grunt.initConfig({
            pkg: grunt.file.readJSON('package.json'),
            autoprefixer: {
                options: {
                    browsers: ['last 2 versions', 'ie 8', 'ie 9'],
                    diff: 'public/css/style.css.patch',
                    remove: true
                },
                your_target: {
                    src: 'public/css/style.css'
                }
            }
        });

    // 2 - Загружаем нужные плагины
    grunt.loadNpmTasks('grunt-autoprefixer');

    // 3 - Говорим grunt, что мы хотим сделать, когда напечатаем grunt в терминале.
    //grunt.registerTask('default', ['newer:concat', 'newer:autoprefixer', 'newer:cssmin', 'newer:uglify']);

    grunt.registerTask('default', ['autoprefixer']);
};
