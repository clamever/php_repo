// Require.js allows us to configure shortcut alias
require.config({
	// The shim config allows us to configure dependencies for
	// scripts that do not call define() to register a module
  baseUrl: 'js/',
  urlArgs: (new Date()).getTime(),
	shim: {
    jquery: {
      exports: '$'
    },
    serializeObject:{
      deps:['jquery']
    },
    json2:{
      deps:['jquery']
    },
		underscore: {
			exports: '_'
		},
		backbone: {
			deps: [
				'underscore',
				'jquery'
			],
			exports: 'Backbone'
		},
		backboneLocalstorage: {
			deps: ['backbone'],
			exports: 'Store'
		},
    bootstrap: {
      deps: ['jquery']
    }
	},
	paths: {
		jquery: 'lib/jquery',
    bootstrap: 'lib/bootstrap/js/bootstrap.min',
		underscore: 'lib/underscore',
		backbone: 'lib/backbone/backbone',
		backboneLocalstorage: 'lib/backbone/backbone.localStorage',
		text: 'lib/text',
    serializeObject: 'lib/jquery.serialize-object',
    json2: 'lib/json2',
    html5shiv: 'lib/html5shiv'
	}
});

var app = {};

require([
  'jquery',
  'underscore',
  'backbone',
  'route/app',
  'bootstrap',
  'html5shiv'
], function($,_,Backbone,AppRoute) {
  app = new AppRoute();
	Backbone.history.start();
});
