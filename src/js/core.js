/**
 * Hummingboard - Hummingbird tools
 *
 * Written and copyrighted 2014+
 * by Sven Marc 'cybrox' Gehring
 *
 * Licensed under CC BY-SA
 */

var config = {
	api: {
		proxyurl: "./src/api/",
		reqlink:  "",
		request: function(apiurl){
			config.api.reqlink = config.api.proxyurl+apiurl;
		}
	}
}






/**********************************************************
 * Ember Init & Router
 */
HB = Ember.Application.create();

HB.Router.map(function(){
	this.resource("achievements");
	this.resource("statistics");
	this.resource("calendar");
	this.resource("sigimg");
});





/**********************************************************
 * Hummingboard Controllers
 */
HB.ApplicationController = Ember.Controller.extend({
	pageName: function(){
		var thisPath = this.get("currentPath");
		var thisName = thisPath.replace("index", "");
		var pageName = thisName.charAt(0).toUpperCase() + thisName.slice(1);

		return (pageName == "") ? "" : " - " + pageName;
	}.property("currentPath"),

	userAvat: "",
	userCovr: "",
	userName: "",
	userIsOn: false,
	userIsOk: false,
	pageSize: 0,

	/**
	 * Overwrite Controller init method
	 * Add methods to read the current user and the current page height
	 */
	init: function(){
		this.readUser();
		this.readPage();
		this._super();
	},

	/**
	 * Request a user from Hummingbird
	 * This will either load the name given by the PHP part (url parameter)
	 * or a cookie on the user's computer to directly display content
	 * for a specific user. If no username is given, the user will be
	 * redirected to the index (/) page.
	 */
	readUser: function(){
		var bodyName = $("body").attr("name");
		var userName = "";
		var userIsOn = true;

		if(bodyName === "__undefined"){
			if($.cookie('_hboard-user') === undefined){
				userName = "";
				userIsOn = false;

				// this.transitionToRoute('index');
			} else {
				userName = $.cookie('_hboard-user');
			}
		} else {
			userName = bodyName;
		}

		this.set("userName", this.userName);
		this.set("userIsOn", this.userIsOn);
	},

	readPage: function(){
		this.pageSize = window.innerHeight - 61 - 40;
		window.onresize = function(){
			this.pageSize = window.innerHeight - 61 - 40;
		}
	}
});


HB.IndexController = Ember.Controller.extend({
	needs: ["application", "index"],

	defUsr: false,
	userName: "",
	userIsOn: function(){
		return this.get("controllers.application.userIsOn");
	}.property("controllers.application.userIsOn"),
	userIsOk: function(){
		return this.get("controllers.application.userIsOk");
	}.property("controllers.application.userIsOk"),

	actions: {
		defineUser: function(){
			var self = this;
			if(this.userName == "") return false;

			config.api.request("users/"+this.userName);
			self.set("defUsr", true);

			$.cookie('_hboard-user', this.userName);
			$.getJSON(config.api.reqlink, function(json){
				self.set("defUsr", false);
				if(json.success === false){
					self.setProperties({
						"controllers.application.userIsOn": true,
						"controllers.application.userIsOk": false
					});
				} else {
					self.setProperties({
						"controllers.application.userName": this.userName,
						"controllers.application.userAvat": json.avatar,
						"controllers.application.userCovr": json.cover_image,
						"controllers.application.userIsOn": true,
						"controllers.application.userIsOk": true
					});
				}
			});
		}
	}
});


HB.StatisticsController = Ember.Controller.extend({
	needs: ["application"]
});





/**********************************************************
 * Hummingboard Classes
 */
HB.Library = Ember.Object.extend({

});