<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Installer
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Controller_Kohanut_Install_Install extends Controller {

	public function action_index()
	{
		
		$view = new View('kohanut/install');
		
		$post = Validate::factory($_POST)
			->rule('password','not_empty')
			->rule('repeat','not_empty')
			->rule('password','min_length',array(6))
			->rule('password','matches',array('repeat'));
			
		// No post, just show the install form
		if (! $_POST)
		{
			$this->request->response = $view;
			return;
		}
		// If posted, but wasn't valid, show errors
		else if ( ! $post->check())
		{
			$view->errors = $post->errors('kohanut/error');
			$this->request->response = $view;
			return;
		}
		
		// Everything looks good, lets do it:
		
		// Create the tables
		DB::query(NULL,"
			CREATE TABLE `kohanut_layouts` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(50) CHARACTER SET latin1 NOT NULL,
				`desc` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
				`code` text CHARACTER SET latin1,
				PRIMARY KEY (`id`),
				UNIQUE KEY `name` (`name`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `kohanut_pages` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`url` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
				`name` varchar(128) CHARACTER SET latin1 NOT NULL,
				`layout` int(10) unsigned DEFAULT '0',
				`islink` tinyint(1) unsigned DEFAULT '0',
				`showmap` tinyint(3) unsigned DEFAULT '1',
				`shownav` tinyint(3) unsigned DEFAULT '1',
				`title` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
				`metadesc` text CHARACTER SET latin1,
				`metakw` text CHARACTER SET latin1,
				`lft` int(10) unsigned DEFAULT NULL,
				`rgt` int(10) unsigned DEFAULT NULL,
				`lvl` int(10) unsigned DEFAULT NULL,
				`scp` int(10) unsigned DEFAULT NULL,
				PRIMARY KEY (`id`),
				KEY `Page-Layout` (`layout`),
				CONSTRAINT `Page-Layout` FOREIGN KEY (`layout`) REFERENCES `kohanut_layouts` (`id`) ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->execute();
			
		DB::query(NULL,"
			CREATE TABLE `kohanut_redirects` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`url` varchar(255) CHARACTER SET latin1 NOT NULL,
				`newurl` varchar(255) CHARACTER SET latin1 NOT NULL,
				`type` enum('301','302') CHARACTER SET latin1 NOT NULL DEFAULT '302',
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `kohanut_users` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`username` varchar(30) CHARACTER SET latin1 NOT NULL,
				`password` varchar(40) CHARACTER SET latin1 DEFAULT NULL,
				`last_login` datetime DEFAULT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `kohanut_blocks` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`page` int(11) NOT NULL,
				`area` int(11) NOT NULL,
				`order` int(11) NOT NULL,
				`elementtype` int(11) NOT NULL,
				`element` int(11) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `kohanut_element_content` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`code` text CHARACTER SET latin1 NOT NULL,
				`markdown` int(1) unsigned NOT NULL DEFAULT '1',
				`twig` int(1) unsigned NOT NULL DEFAULT '1',
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->execute();
				
		DB::query(NULL,"
			CREATE TABLE `kohanut_element_snippet` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`code` text CHARACTER SET latin1 NOT NULL,
				`name` varchar(127) CHARACTER SET latin1 NOT NULL,
				`markdown` tinyint(1) unsigned NOT NULL DEFAULT '1',
				`twig` tinyint(1) unsigned NOT NULL DEFAULT '1',
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->execute();
				
		DB::query(NULL,"
			CREATE TABLE `kohanut_element_request` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`url` text CHARACTER SET latin1 NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `kohanut_elementtypes` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(127) CHARACTER SET latin1 NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->execute();
		
		// Create the admin user
		$admin = Sprig::factory('kohanut_user',array(
			'username'=>'admin',
			'password'=>$_POST['password'],
			'password_confirm'=>$_POST['password']
		));
		$admin->create();
		
		// Create sample layouts, pages, and content
		
		$queries = explode(";\n",trim("
			
			INSERT INTO `kohanut_blocks` (`id`, `page`, `area`, `order`, `elementtype`, `element`) VALUES
				(1, 1, 1, 3, 1, 1),
				(7, 11, 1, 1, 1, 5),
				(8, 13, 1, 1, 1, 7),
				(15, 15, 1, 1, 1, 11),
				(16, 18, 1, 1, 1, 13),
				(17, 21, 1, 1, 1, 14),
				(19, 17, 1, 1, 2, 1),
				(24, 24, 1, 3, 3, 2),
				(25, 24, 1, 1, 1, 20),
				(26, 24, 1, 4, 3, 2),
				(28, 20, 1, 1, 1, 22),
				(29, 16, 1, 1, 1, 23);
			
			INSERT INTO `kohanut_elementtypes` (`id`, `name`) VALUES
				(1, 'content'), (2, 'request'), (3, 'snippet');
			
			INSERT INTO `kohanut_element_content` (`id`, `code`, `markdown`, `twig`) VALUES (1, '# Thanks for Using Kohanut!\n\nIf you are seeing this message that Kohanut has been successfully installed and is ready to go!  You can browse around the default site using the navigation at the top of the screen.  To log in to the admin and start making changes simply browse to [/admin](/admin) and log in using the password you entered during the install.  Enjoy!', 1, 0);
			INSERT INTO `kohanut_element_content` (`id`, `code`, `markdown`, `twig`) VALUES (5, '# Page not found\n\nThe page could not be found.  You could try going [Home](/) or see our [Products](/products).\n\n{{ Kohanut.status(404) }}', 1, 1);
			INSERT INTO `kohanut_element_content` (`id`, `code`, `markdown`, `twig`) VALUES (7, '# Our story\n\nLorem ipsum [dolor sit amet](/about), consectetur adipiscing elit. Pellentesque vitae arcu eros. Nam in arcu id nulla commodo molestie. Donec imperdiet leo et est **iaculis at facilisis** eros imperdiet. Vivamus ultrices commodo dolor, ut egestas tortor lacinia non. Aliquam condimentum gravida velit, id faucibus purus feugiat sit amet. \n\nEtiam eu lacus ultrices enim tincidunt ullamcorper. Aliquam feugiat, nunc ut sodales dictum, velit magna ultrices leo, a laoreet sapien arcu non lorem. \n\n*   Vestibulum _sed rhoncus metus_. Morbi pellentesque molestie elit in ultrices. Praesent mauris ante, vestibulum ac condimentum quis, vestibulum non urna. Duis quis tortor viverra nunc consectetur aliquet sit amet non massa. \n*   Etiam nec dolor eu est porta suscipit vitae in orci. Nam molestie pretium consectetur. \n    1.  Integer velit nunc, lobortis ac viverra vitae, ornare ut elit. \n    2.  Fusce at vestibulum lectus. \n    3.  Vivamus interdum bibendum auctor.', 1, 0);
			INSERT INTO `kohanut_element_content` (`id`, `code`, `markdown`, `twig`) VALUES (11, '# Frequently Asked Questions\n\n#### Lorem Ipsum?\n\nLorem ipsum dolor sit amet consectetor\n\n#### Dolor sit?\n\nBlah blah blah blah blah\n', 1, 0);
			INSERT INTO `kohanut_element_content` (`id`, `code`, `markdown`, `twig`) VALUES (13, '{{ Kohanut.site_map() }}', 0, 1);
			INSERT INTO `kohanut_element_content` (`id`, `code`, `markdown`, `twig`) VALUES (14, '# Header 1\nIf you login to the admin and edit this page you will see that it shows a variety of different elements and how you achieve them in [markdown syntax](http://kohanut.com/docs/using.markdown).  So without further ado, lots of different headers, tables, lists and quotes filled with various Latin phrases.  Enjoy!\n\n## Header 2\nLorem ipsum dolor sit amet, consectetur adipiscing elit.\n\n### Header 3\nLorem ipsum dolor sit amet, consectetur adipiscing elit.\n\n#### Header 4\nLorem ipsum dolor sit amet, consectetur adipiscing elit.\n\n##### Header 5\nLorem ipsum dolor sit amet, consectetur adipiscing elit.\n\n##### Header 6\nLorem ipsum dolor sit amet, consectetur adipiscing elit.\n\n----------\n\n## Blockquote\n\n> Praesent orci nisi, interdum quis, tristique vitae, consectetur sed, arcu. Ut at sapien non dolor semper sollicitudin. Etiam semper erat quis odio. Quisque commodo suscipit velit. Nulla facilisi.\n>\n> \\- *Duis justo quam*\n\n-----------\n\n## Code Segment\n\n    public function()\n    {\n       echo \"You create code by indenting several lines \" .\n            \"by 4 spaces, then all white space after that \" .\n            \"will be preserved.\"; \n    }\n\n-----------\n\n## Lists\n\n### Unsorted List\n*  Blandit in, interdum a\n*  Ultrices non lectus\n*  Nunc id odio\n*  Fusce ultricies\n\n### Ordered list\n1.  Blandit in, interdum a\n2.  Ultrices non lectus\n3.  Nunc id odio\n4.  Fusce ultricies\n   *  Sub list\n      1.  Etcetera\n\n### Definition List\nTitle\n:   Definition of something\n\nTitle 1\nTitle 2\n:   1- Lorem ipsum dolor sit amet.\n:   2- It can also mean this\n\n------------\n\n## Tables\n\n  Property 1   | Property 2     | Property 3    | Property 4\n---------------|----------------|---------------|--------------\n  Lorem ipsum  |Dolor sit amet  |Consectetuer   |Adipiscing elit\nDolor sit amet |Consectetuer    |Adipiscing elit|  Lorem ipsum\nConsectetuer   |Adipiscing elit |  Lorem ipsum  | Dolor sit amet', 1, 0);
			INSERT INTO `kohanut_element_content` (`id`, `code`, `markdown`, `twig`) VALUES (20, '<h1>Basic Samples</h1>\n\n</p>These is a standard content element. It doesn\'t even use markdown or anything, if you look at it in the admin, you will see it uses regular old html</p>', 1, 0);
			INSERT INTO `kohanut_element_content` (`id`, `code`, `markdown`, `twig`) VALUES (22, '## Samples\n\n*  To see some basic content and snippet examples, click on \"Basic\" from the \"Samples\" category.\n*  The \"About\" link in the navigation doesn\'t actually represent a page, it just links to \"/about/story\".  Navigation items don\'t have to actually be a page.\n*  To see a sample of an External Link, click on \"External Link\" in the \"Samples\" category.  It\'s usually a bad idea to do this from the main nav, but it can be useful.\n*  To see an example of Integrating Kohana, you can see the [contact form](/contact), which uses a sub request, or the [override example](/samples/override).\n\n## Navigation Samples\n\nAs an example, the Home template has a secondary nav (on the right side of the page) with the header turned off, and the depth set to 2.  The code for this nav is like this:\n\n    {{ Kohanut.nav(\'header=0, depth=2\') }}\n\nWhere as on all the other pages in this demo the nav has a header and a depth of 1, like this:\n\n    {{ Kohanut.nav(\'header=1, header_class=nav-header, depth=1\') }}\n\nSee the complete list of the [navigation options].', 1, 0);
			INSERT INTO `kohanut_element_content` (`id`, `code`, `markdown`, `twig`) VALUES (23, '# Privacy Policy\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec justo a est rhoncus rutrum. Mauris vel vehicula diam. Phasellus tristique, augue nec convallis luctus, ante nisi commodo est, a feugiat lacus ipsum sit amet sem. Integer pretium condimentum ante, eu sagittis elit pretium pharetra. In congue semper viverra. Cras tincidunt ligula nec justo placerat vehicula. Vestibulum urna nisl, fermentum in blandit a, accumsan id massa. \n\n*   Praesent adipiscing scelerisque mi. Vivamus aliquam, neque sit amet ultrices viverra, ante lorem condimentum purus, sit amet dapibus purus est ut erat. Mauris odio orci, posuere nec malesuada at, sollicitudin eu mi. Suspendisse eget massa id ante porta sodales. \n*   Aliquam hendrerit dui vitae risus dignissim nec scelerisque dolor dapibus. \n*   Maecenas libero diam, euismod non porttitor a, sollicitudin non tortor. Proin massa leo, consectetur at dapibus non, bibendum ac velit. Sed posuere euismod neque, at ultrices mauris semper sed. Donec purus justo, malesuada sed interdum et, tempus vel sem.', 1, 0);
			
			
			INSERT INTO `kohanut_element_request` (`id`, `url`) VALUES
				(1, 'actions/contact');
			
			INSERT INTO `kohanut_element_snippet` (`id`, `code`, `name`, `markdown`, `twig`) VALUES (1, '<div class=\"left\">\n&copy; 2010 Company Name - A slogan is a powerful tool!\n</div>\n\n<div class=\"right\" markdown=\"1\">\nSite by Michael Peters | \nPowered by [Kohanut](http://kohanut.com \"{{ Kohanut.render_stats() }}\") |\nTemplate by [Arcsin](http://arcsin.se/)\n</div>\n\n\n', 'Footer', 1, 1),
				(2, '## This is a sample snippet.  \n\nFeatures of snippets:\n\n*   They are handy for things that need to be *reused*. Anything that might appear in **several places**.  For example, the header and footer are both snippets because all the layouts use them, so if you want to change the header or footer, you edit the snippet, rather than editing every layout.\n*   They can use markdown or twig, if you desire.\n', 'Sample Snippet', 1, 0),
				(3, '<div id=\"toplinks\">\n   <div id=\"toplinks_inner\" markdown=\"1\">\n[Sitemap](/sitemap) | \n[Privacy Policy](/about/privacy) | \n[FAQ](/about/faq)</div>\n</div>\n<div class=\"clearer\">&nbsp;</div>\n<div id=\"site_title\">\n   <h1><a href=\"/\">Kohanut <span>Sample Site</span></a></h1>\n   <p>Showcasing the power and flexibility of Kohanut CMS</p>\n</div>', 'Header', 1, 0),
				(4, '<div class=\"col3 left\"><div class=\"col3_content\" markdown=\"1\">\n\n#### Tincidunt\n\n*  [Consequat molestie](#)\n*  [Sem justo](#)\n*  [Semper eros](#)\n*  [Magna sed purus](#)\n*  [Tincidunt morbi](#)\n\n</div></div>\n\n<div class=\"col3mid left\"><div class=\"col3_content\" markdown=\"1\">\n\n#### Fermentum\n\n*  [Semper fermentum](#)\n*  [Sem justo](#)\n*  [Magna sed purus](#)\n*  [Tincidunt nisl](#)               \n*  [Consequat molestie](#)\n\n</div></div>\n\n<div class=\"col3 right\"><div class=\"col3_content\" markdown=\"1\">\n\n#### Praesent\n\n*  [Semper lobortis](#)\n*  [Consequat molestie](#)          \n*  [Magna sed purus](#)\n*  [Sem morbi](#)\n*  [Tincidunt sed](#)\n\n</div></div>\n\n<div class=\"clear\"></div>', 'Dashboard', 1, 0);
			
			INSERT INTO `kohanut_layouts` (`id`, `name`, `desc`, `code`) VALUES
				(1, 'Home', 'Home page', '{{ Kohanut.style(\'theme/style.css\') }}\n\n<div id=\"header\">\n   {{ Kohanut.element(\'snippet\',\'header\') }}\n</div>\n<div id=\"navigation\">\n   {{ Kohanut.main_nav() }}\n   <div class=\"clear\"></div>\n</div>\n<div class=\"clear\"></div>\n\n<div id=\"main_wrapper_outer\"><div id=\"main_wrapper_inner\">	\n   <div class=\"center_wrapper\">	\n      <div id=\"main\"><div id=\"main_content\">\n         {{ Kohanut.element_area(1,\'Main Column\') }}\n      </div></div>\n      <div id=\"sidebar\"><div id=\"sidebar_content\">\n         <div class=\"box\">\n         <div class=\"left-nav\">{{ Kohanut.nav(\'header=0, header_class=nav-header, header_elem=h3, depth=2\') }}</div>\n         </div>\n         {{ Kohanut.element_area(2,\'Side Column\') }}\n      </div></div>\n      <div class=\"clear\"></div>\n   </div>\n</div></div>\n\n<div id=\"dashboard\"><div id=\"dashboard_content\">\n   {{ Kohanut.element(\'snippet\',\'dashboard\') }}\n</div></div>\n\n<div class=\"clear\"></div>\n\n<div id=\"footer\"><div class=\"center_wrapper\">\n   {{ Kohanut.element(\'snippet\',\'footer\') }}\n</div></div>'),
				(2, 'Two Column', 'Standard two column template', '{{ Kohanut.style(\'theme/style.css\') }}\n\n<div id=\"header\">\n   {{ Kohanut.element(\'snippet\',\'header\') }}\n</div>\n<div id=\"navigation\">\n   {{ Kohanut.main_nav() }}\n   <div class=\"clear\"></div>\n</div>\n<div class=\"clear\"></div>\n\n<div id=\"main_wrapper_outer\"><div id=\"main_wrapper_inner\">	\n   <div class=\"center_wrapper\">	\n      <div id=\"main\"><div id=\"main_content\">\n         <div id=\"breadcrumbs\" class=\"box_title\">\n            {{ Kohanut.bread_crumbs() }}\n         </div>\n         {{ Kohanut.element_area(1,\'Main Column\') }}\n      </div></div>\n      <div id=\"sidebar\"><div id=\"sidebar_content\">\n         <div class=\"box\">\n         <div class=\"left-nav\">{{ Kohanut.nav(\'header=1, header_class=nav-header, header_elem=h3, depth=1\') }}</div>\n         </div>\n         {{ Kohanut.element_area(2,\'Side Column\') }}\n      </div></div>\n      <div class=\"clear\"></div>\n   </div>\n</div></div>\n\n<div id=\"dashboard\"><div id=\"dashboard_content\">\n   {{ Kohanut.element(\'snippet\',\'dashboard\') }}\n</div></div>\n\n<div class=\"clear\"></div>\n\n<div id=\"footer\"><div class=\"center_wrapper\">\n   {{ Kohanut.element(\'snippet\',\'footer\') }}\n</div></div>'),
				(3, 'Error', 'error layout', '{{ Kohanut.style(\'theme/style.css\') }}\n\n<div id=\"header\">\n   {{ Kohanut.element(\'snippet\',\'header\') }}\n</div>\n<div id=\"navigation\">\n   {{ Kohanut.main_nav() }}\n   <div class=\"clear\"></div>\n</div>\n<div class=\"clear\"></div>\n\n<div id=\"main_wrapper_outer\"><div id=\"main_wrapper_inner\">	\n   <div class=\"center_wrapper\">	\n      \n         {{ Kohanut.element_area(1,\'Main Column\') }}\n      \n   </div>\n</div></div>\n\n<div id=\"dashboard\"><div id=\"dashboard_content\">\n   {{ Kohanut.element(\'snippet\',\'dashboard\') }}\n</div></div>\n\n<div class=\"clear\"></div>\n\n<div id=\"footer\"><div class=\"center_wrapper\">\n   {{ Kohanut.element(\'snippet\',\'footer\') }}\n</div></div>');
			
			INSERT INTO `kohanut_pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES
				(1, '', 'Home', 1, 0, 1, 1, 'Sample Site', 'Meta description!', 'key, words, go, here', 1, 28, 0, 1),
				(4, 'http://kohanut.com', 'External Link', 3, 1, 1, 1, '', '', '', 11, 12, 2, 1),
				(5, 'about/story', 'About', 2, 1, 1, 1, '', '', '', 14, 21, 1, 1),
				(6, '', 'Home', 2, 1, 1, 1, '', '', '', 2, 3, 1, 1),
				(11, 'error', 'Error', 3, 0, 0, 0, 'Page not found', '', '', 24, 25, 1, 1),
				(13, 'about/story', 'Story', 2, 0, 1, 1, 'Sample - Story', '', '', 15, 16, 2, 1),
				(15, 'about/faq', 'FAQ', 2, 0, 1, 1, 'Sample - FAQ', '', '', 17, 18, 2, 1),
				(16, 'about/privacy', 'Privacy Policy', 2, 0, 1, 1, 'Sample - Privacy', '', '', 19, 20, 2, 1),
				(17, 'contact', 'Contact', 2, 0, 1, 1, 'Sample - Contact', '', '', 22, 23, 1, 1),
				(18, 'sitemap', 'Site Map', 2, 0, 0, 0, 'Sample - Site Map', '', '', 26, 27, 1, 1),
				(20, 'samples', 'Samples', 2, 0, 1, 1, 'Sample - Samples', '', '', 4, 13, 1, 1),
				(21, 'samples/markdown', 'Markdown', 2, 0, 1, 1, 'Sample - Markdown', '', '', 7, 8, 2, 1),
				(23, 'samples/override', 'Override', 2, 1, 1, 1, '', '', '', 9, 10, 2, 1),
				(24, 'samples/basic', 'Basic', 2, 0, 1, 1, 'Sample - Basic', '', '', 5, 6, 2, 1);
			
			INSERT INTO `kohanut_redirects` (`id`, `url`, `newurl`, `type`) VALUES
				(1, 'test', 'about', '301');
			
		"));
		
		foreach ($queries as $query)
		{
			DB::query(NULL,$query)->execute();
		}
		
		$this->request->response = new View('kohanut/install-success');
		return;
	
	}
}