**Important Note:** At first, Hummingboard was just a very simple statistics tool. However, I've added a few other things over time and it got really messy so I just decided to redo the whole thing and build it upon a new core. The new Hummingboard will be a easy-to-use collection of all the old Hummingboard Tools wrapped up in an ember application.  
You can already download the indev version from the *master-v2* branch if you want to check it out but nothing of that is defined to stay by now.
Speaking of branches, I'll also try to start using real branching in this project. The first version was just all-in-one because it wasn't really big enough to be worth the work but it eventually grow pretty big to manage it like that.


# Hummingboard

Hummingboard is a collection of small goodies such as additional statistics or signature images for hummingbird.me users.
The Hummingboard (hummingboard.me) contains all these smaller projects gathered on one domain.

## \#01 - Hummingboard stats
Hummingboard stats is a clean statistics page that will grab all your anime data via the Hummingbird API and generating bar graphs out of it. You might ask why you'd ever need this? Well, you don't but I like to visualize things and this is a very good way to do that in my opinion.
##### Project Facts
- The site will chache your data for 3 days to prevent an API overload.
- Though the site still has some bugs, it is able to progress up to ~1600 anime entries.

##### Intresting Links
- http://hummingboard.eu/ - Landing page
- http://hummingboard.eu/cybrox/ - Example profile
- http://forums.hummingbird.me/t/hummingboard-hummingbird-stats/1622 - Original thread


## \#02 - Hummingboard signatures
Hummingboard signatures generates a simple signature image you can embed in any forum signature, profile or whatever. The image is a 500px x 100px png image that contains your avatar, cover image, name and anime time.
##### Usage
- Your image link only contais your username:   
`http://sig.hummingboard.me/cybrox`  
- You might want to add ".png" at the end for forums software to recognize it as an image   `http://sig.hummingboard.me/cybrox.png`  
- In some forums you might need to use image BB-Codes  
`[img]http://sig.hummingboard.me/cybrox.png[/img]`  
- In some forums you might be able to link the image using link BB-Codes `[url='link-to-wherever-you-want'][img]http://sig.hummingboard.me/cybrox.png[/img][/url]`.  

##### Project Facts
- Your image will be cached for 24 hours to decrease loading time and API usage.  

##### Intresting Links
- http://sig.hummingboard.me/cybrox.png An example image
- http://forums.hummingbird.me/t/hummingbird-signature-images/2640 - Original thread
- http://forums.hummingbird.me/t/hummingbird-forum-signature-on-other-forums-not-on-hummingbird/2593/79 - Idea thread
