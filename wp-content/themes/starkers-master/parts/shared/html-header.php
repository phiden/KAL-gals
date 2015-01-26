<!DOCTYPE HTML>
<!--[if IEMobile 7 ]><html class="no-js iem7" manifest="default.appcache?v=1"><![endif]--> 
<!--[if lt IE 7 ]><html class="no-js ie6" lang="en"><![endif]--> 
<!--[if IE 7 ]><html class="no-js ie7" lang="en"><![endif]--> 
<!--[if IE 8 ]><html class="no-js ie8" lang="en"><![endif]--> 
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html class="no-js" lang="en"><!--<![endif]-->
	<head>
		<title><?php bloginfo( 'name' ); ?><?php wp_title( '|' ); ?></title>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
	  	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- Remove if you're not building a responsive site. (But then why would you do such a thing?) -->
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon.ico"/>
	
		<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
		
		<script src="http://www.thekalgals.com/wp-content/themes/starkers-master/js/jquery.mmenu.min.js" type="text/javascript"></script>
		<link href="http://www.thekalgals.com/wp-content/themes/starkers-master/css/jquery.mmenu.css" type="text/css" rel="stylesheet" />
		
		
		
   		<script src='http://www.thekalgals.com/wp-content/themes/starkers-master/js/bootstrap.min.js'></script>
		
		<link href='http://fonts.googleapis.com/css?family=Over+the+Rainbow' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Quattrocento+Sans:400,700,400italic' rel='stylesheet' type='text/css'>
		
		 <style type="text/css" media="screen">

    
</style>



<script type="text/javascript" charset="utf-8">

	$( document ).ready(function() {
		
		/* it looks like wordpress is automatically wrapping images in <p> tags. Correct for this. */
		$('p:has(img)').addClass('clearP');
		
	})
	

	RavelryThing = function() {
    	var progressData = null;
    
	    // Dollar and Dollar E convenience
	    var $ = function(id) { return document.getElementById(id); };

    var $E = function(data) {
        var el;
        if ('string' == typeof data) {
          el = document.createTextNode(data);
        } else {
          el = document.createElement(data.tag);
          delete(data.tag);
          if ('undefined' != typeof data.children) {
            for (var i=0, child=null; 'undefined' != typeof (child=data.children[i]); i++) { if (child) { el.appendChild($E(child)); } }
            delete(data.children);
          }
          for (attr in data) { 
            if (attr == 'style') {
              for (s in data[attr]) {
                el.style[s] =  data[attr][s];
              } 
            } else if (data[attr]) {
              el[attr]=data[attr]; 
            }
          }
        }
        return el;
    };
    
    return {
      progressReceived: function(data) {
        progressData = data;
      },

      /*
        Allowed options are: color, width, height, photos (true/false), 
        projects (list of permalinks for specific projects). For example:
        drawProgressBars({color: 'red', width: 200, height: 20});
      */
      drawProgressBars: function(options) {
        if (!progressData) return;
        
        if (!options) options = {};
        if ('number' == typeof options.height) options.height += 'px';
        if (!options.height) options.height = '1.3em';
        if (!options.width) options.width = 100;
        if (!options.color) options.color = 'lightgreen';
        if (!options.container) options.container = 'rav_progress_bars';
        
        var container = $(options.container);
        if (!container) {
          // using unicode so that Blogger doesn't get cranky 
          document.write("\u003cdiv id='" + options.container + "'\u003e\u003c/div\u003e");
          container = $(options.container);
        }
        
        var selectedProjects = progressData.projects;
        if (options.projects) {
          // user has selected individual projects
          var projectsById = new Object();
          for (var i=0; i < selectedProjects.length; i++) {
            projectsById[selectedProjects[i].permalink] = selectedProjects[i];
          }
          selectedProjects = new Array();
          for (var i=0; i < options.projects.length; i++) {
            var project = projectsById[options.projects[i]];
            if (project) {
              selectedProjects.push(project);
            }
          }
        }
        
        for (var i=0; i < selectedProjects.length; i++) {
          var project = selectedProjects[i];
          var filledStyle = { width: Math.round((project.progress/100) * options.width) + 'px', height: options.height, backgroundColor: options.color};
          var barStyle = { width: (options.width) + 'px', height: options.height};
          var className = 'rav_project'
          
          var photo = null;
          if (options.photos && project.thumbnail) {
            className += ' rav_project_with_photos';
            photo = { tag: 'a', className: 'rav_photo_link', href: project.thumbnail.flickrUrl, children: [
                {tag: 'img', src: project.thumbnail.src }
              ]
            };
          }
          
          var title = null;
          if (options.title != false) {
            title = { tag: 'a', className: 'rav_title', href: project.url, children: [project.name] };
          }
          
          container.appendChild($E({
            tag: 'div',
            className: className,
            children: [ title, photo,
              { tag: 'div', className: 'rav_progress_bar_wrapper', style: barStyle, children: [
                { tag: 'div', className: 'rav_progress_bar', style: barStyle, children: [
                  {tag: 'div', className: 'rav_progress_filled', style: filledStyle},
                  {tag: 'div', className: 'rav_progress_text', style: barStyle, 
                    children: [ project.progress + '%' ]}
                ]}
              ]}
            ]
          }));
        }
      }
    }
  }();
	RavelryThing2 = function() {
    var progressData = null;
    
    // Dollar and Dollar E convenience
    var $ = function(id) { return document.getElementById(id); };

    var $E = function(data) {
        var el;
        if ('string' == typeof data) {
          el = document.createTextNode(data);
        } else {
          el = document.createElement(data.tag);
          delete(data.tag);
          if ('undefined' != typeof data.children) {
            for (var i=0, child=null; 'undefined' != typeof (child=data.children[i]); i++) { if (child) { el.appendChild($E(child)); } }
            delete(data.children);
          }
          for (attr in data) { 
            if (attr == 'style') {
              for (s in data[attr]) {
                el.style[s] =  data[attr][s];
              } 
            } else if (data[attr]) {
              el[attr]=data[attr]; 
            }
          }
        }
        return el;
    };
    
    return {
      progressReceived: function(data) {
        progressData = data;
      },

      /*
        Allowed options are: color, width, height, photos (true/false), 
        projects (list of permalinks for specific projects). For example:
        drawProgressBars({color: 'red', width: 200, height: 20});
      */
      drawProgressBars: function(options) {
        if (!progressData) return;
        
        if (!options) options = {};
        if ('number' == typeof options.height) options.height += 'px';
        if (!options.height) options.height = '1.3em';
        if (!options.width) options.width = 100;
        if (!options.color) options.color = 'lightgreen';
        if (!options.container) options.container = 'rav_progress_bars1';
        
        var container = $(options.container);
        if (!container) {
          // using unicode so that Blogger doesn't get cranky 
          document.write("\u003cdiv id='" + options.container + "'\u003e\u003c/div\u003e");
          container = $(options.container);
        }
        
        var selectedProjects = progressData.projects;
        if (options.projects) {
          // user has selected individual projects
          var projectsById = new Object();
          for (var i=0; i < selectedProjects.length; i++) {
            projectsById[selectedProjects[i].permalink] = selectedProjects[i];
          }
          selectedProjects = new Array();
          for (var i=0; i < options.projects.length; i++) {
            var project = projectsById[options.projects[i]];
            if (project) {
              selectedProjects.push(project);
            }
          }
        }
        
        for (var i=0; i < selectedProjects.length; i++) {
          var project = selectedProjects[i];
          var filledStyle = { width: Math.round((project.progress/100) * options.width) + 'px', height: options.height, backgroundColor: options.color};
          var barStyle = { width: (options.width) + 'px', height: options.height};
          var className = 'rav_project'
          
          var photo = null;
          if (options.photos && project.thumbnail) {
            className += ' rav_project_with_photos';
            photo = { tag: 'a', className: 'rav_photo_link', href: project.thumbnail.flickrUrl, children: [
                {tag: 'img', src: project.thumbnail.src }
              ]
            };
          }
          
          var title = null;
          if (options.title != false) {
            title = { tag: 'a', className: 'rav_title', href: project.url, children: [project.name] };
          }
          
          container.appendChild($E({
            tag: 'div',
            className: className,
            children: [ title, photo,
              { tag: 'div', className: 'rav_progress_bar_wrapper', style: barStyle, children: [
                { tag: 'div', className: 'rav_progress_bar', style: barStyle, children: [
                  {tag: 'div', className: 'rav_progress_filled', style: filledStyle},
                  {tag: 'div', className: 'rav_progress_text', style: barStyle, 
                    children: [ project.progress + '%' ]}
                ]}
              ]}
            ]
          }));
        }
      }
    }
  }();
</script>
<script src="http://api.ravelry.com/projects/phiden/progress.json?callback=RavelryThing.progressReceived&key=fa9143840615448ae665b929aff15fa1c0e406b9&version=0"></script>

<script src="http://api.ravelry.com/projects/JosieLynne/progress.json?callback=RavelryThing2.progressReceived&key=85ff4dcc6ff8df2ba16db3e7c348e4c4e35c0c26&version=0"></script>


		<?php wp_head(); ?>
		
		<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-3384895-15', 'thekalgals.com');
  ga('send', 'pageview');

</script>


	</head>
	<body <?php body_class(); ?>>
	
		
			
			<!-- header gets called in -->
			
			