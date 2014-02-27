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

	createUser: function(username){
		this.set("user", HB.User.create(username));
	},

	pageSize: 0,
	user: null,


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

	userName: "",
	userLoad: function(){
		return this.get("controllers.application.user.load");
	}.property("controllers.application.user.load"),
	userIsOn: function(){
		return this.get("controllers.application.user.isOn");
	}.property("controllers.application.user.isOn"),
	userIsOk: function(){
		return this.get("controllers.application.user.isOk");
	}.property("controllers.application.user.isOk"),

	actions: {
		defineUser: function(){
			if(this.userName == "") return false;
			if(this.get("controllers.application.user") == null){
				this.set("controllers.application.user", HB.User.create({
					"username": this.userName
				}));
			} else {
				this.get("controllers.application.user").request(this.userName);
			}
		}
	}
});


HB.StatisticsController = Ember.Controller.extend({
	needs: ["application"]
});





/**********************************************************
 * Hummingboard Classes
 */
HB.User = Ember.Object.extend({
	needs: ["index"],

	isOk: false,
	isOn: false,
	load: false,
	name: "",
	imga: "",
	imgc: "",
	time: "",
	link: function(){
		return "http://hummingbird.me/users/"+this.name;
	}.property("userName"),


	init: function(){
		this.request(this.get("username"));
		this._super();
	},

	request: function(username){
		self = this;
		self.set("name", username);
		self.set("load", true);

		config.api.request("users/"+this.name);

		$.cookie('_hboard-user', this.name);
		$.getJSON(config.api.reqlink, function(json){
			self.set("load", false);
			if(json.success === false){
				self.setProperties({
					"isOn": true,
					"isOk": false
				});
			} else {
				self.generateTimeString(json.life_spent_on_anime);
				self.setProperties({
					"name": json.name,
					"imga": json.avatar,
					"imgc": json.cover_image,
					"isOn": true,
					"isOk": true
				});
			}
		});
	},

	generateTimeString: function(wt){
		yr = Math.floor(wt / 525948.766);
		lf = wt % 525948.766;
		mh = Math.floor(lf / 43829.766);
		lf = lf % 43829.0639;
		dy = Math.floor(lf / 1440);
		lf = lf % 1440;
		hr = Math.floor(lf / 60);
		mn = Math.floor(lf % 60);

		this.set("time", yr+" Years, "+mn+" Months, "+dy+" Days, "+hr+" Hours, "+mn+" Minutes");
	}
});


HB.Library = Ember.Object.extend({

});