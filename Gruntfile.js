module.exports = function(grunt) {
 
    
  grunt.initConfig({
      config: {
        root: 'web',
        requirejs: '<%= config.root %>/js/requirejs',
        modules_js: '<%= config.root %>/js/modules',
        css: '<%= config.root %>/css',
        fonts: '<%= config.root %>/fonts',
      },
      
      copy: {
          requirejs: {
            files :[{
              expand: true,
              cwd: 'node_modules/requirejs',
              src: ['require.js'],
              dest: '<%= config.requirejs %>'
            }]
          },
          modules_js: {
              files: [{
                  expand: true,
                  cwd: 'node_modules/jquery/dist',
                  src: ['jquery.js'],
                  dest: '<%= config.modules_js %>'
              }/*,{
                  expand: true,
                  cwd: 'resources/public/js/',
                  src: ['main_menu.js'],
                  dest: '<%= config.modules_js %>'
              }*/]
          },
          css: {
        	  files :[{
        		  expand: true,
                  cwd: 'vendor/tastejs/todomvc-app-template/node_modules/todomvc-common/',
                  src: ['base.css'],
                  dest: '<%= config.css %>'
        	  },{
        		  expand: true,
                  cwd: 'vendor/tastejs/todomvc-app-template/node_modules/todomvc-app-css/',
                  src: ['index.css'],
                  dest: '<%= config.css %>'
        	  }]
          }	
      },
      exec: {
    	  'todomvc-app-template': 'cd vendor/tastejs/todomvc-app-template && npm install'
      }
  });
  
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-exec');
  
  grunt.registerTask('dev_js', ['copy:requirejs', 'copy:modules_js']);
  grunt.registerTask('dev_css', ['exec:todomvc-app-template', 'copy:css']);
  
  grunt.registerTask('dev', ['dev_js', 'dev_css']);
  grunt.registerTask('default', ['dev']);
};    