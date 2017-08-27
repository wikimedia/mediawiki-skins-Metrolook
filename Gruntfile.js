/* eslint-env node */
module.exports = function ( grunt ) {
	var conf = grunt.file.readJSON( 'skin.json' );
	grunt.loadNpmTasks( 'grunt-eslint' );
	grunt.loadNpmTasks( 'grunt-jsonlint' );
	grunt.loadNpmTasks( 'grunt-banana-checker' );

	grunt.initConfig( {
		eslint: {
			all: [
				'*.js',
				'**/*.js',
				'!node_modules/**',
				'!vendor/**',
				'!js/overthrow.js'
			]
		},
		banana: conf.MessagesDirs,
		jsonlint: {
			options: {
				reporter: 'jshint'
			},
			all: [
				'*.json',
				'**/*.json',
				'.stylelintrc',
				'!node_modules/**',
				'!vendor/**'
			]
		}
	} );

	grunt.registerTask( 'test', [ 'eslint', 'jsonlint', 'banana' ] );
	grunt.registerTask( 'default', 'test' );
};
