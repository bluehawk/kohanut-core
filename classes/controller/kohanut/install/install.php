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
		
		$post = Validate::factory($_POST)
			->rule('password','not_empty')
			->rule('repeat','not_empty')
			->rule('password','min_length',array(6))
			->rule('password','matches',array('repeat'));
			
		if ( ! $post->check()) {
		
			$view = new View('kohanut/admin/install');
			$view->errors = $post->errors('kohanut/admin/error');
			$this->request->response = $view;
			return;
		}
		
		
		// Create the tables
		DB::query(NULL,"
			CREATE TABLE `layouts` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(50) NOT NULL,
				`desc` VARCHAR(256) NULL DEFAULT NULL,
				`code` TEXT NULL,
				PRIMARY KEY (`id`),
				UNIQUE INDEX `name` (`name`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `pages` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`url` VARCHAR(256) NOT NULL,
				`name` VARCHAR(128) NOT NULL,
				`layout` INT(10) UNSIGNED NOT NULL DEFAULT '0',
				`islink` TINYINT(1) UNSIGNED NULL DEFAULT '0',
				`showmap` TINYINT(1) UNSIGNED NULL DEFAULT '1',
				`shownav` TINYINT(1) UNSIGNED NULL DEFAULT '1',
				`title` VARCHAR(256) NULL DEFAULT NULL,
				`metadesc` TEXT NULL,
				`metakw` TEXT NULL,
				`lft` INT(10) UNSIGNED NULL DEFAULT NULL,
				`rgt` INT(10) UNSIGNED NULL DEFAULT NULL,
				`lvl` INT(10) UNSIGNED NULL DEFAULT NULL,
				`scp` INT(10) UNSIGNED NULL DEFAULT NULL,
				PRIMARY KEY (`id`),
				INDEX `Page-Layout` (`layout`),
				CONSTRAINT `Page-Layout` FOREIGN KEY (`layout`) REFERENCES `layouts` (`id`) ON UPDATE NO ACTION
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB")->execute();
			
		DB::query(NULL,"
			CREATE TABLE `redirects` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`url` VARCHAR(255) NOT NULL,
				`newurl` VARCHAR(255) NOT NULL,
				`type` ENUM('301','302') NOT NULL DEFAULT '302',
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `users` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`username` VARCHAR(30) NOT NULL,
				`password` VARCHAR(40) NULL DEFAULT NULL,
				`last_login` DATETIME NULL DEFAULT NULL,
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `blocks` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`page` INT(11) NOT NULL,
				`area` INT(11) NOT NULL,
				`order` INT(11) NOT NULL,
				`elementtype` INT(11) NOT NULL,
				`element` INT(11) NOT NULL,
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `element_content` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`code` TEXT NOT NULL,
				`markdown` TINYINT(1) UNSIGNED NULL DEFAULT '1',
				`twig` TINYINT(1) UNSIGNED NULL DEFAULT '1',
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB")->execute();
				
		DB::query(NULL,"
			CREATE TABLE `element_snippet` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`code` TEXT NOT NULL,
				`name` VARCHAR(127) NOT NULL,
				`markdown` TINYINT(1) UNSIGNED NULL DEFAULT '1',
				`twig` TINYINT(1) UNSIGNED NULL DEFAULT '1',
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB")->execute();
				
		DB::query(NULL,"
			CREATE TABLE `element_request` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`url` TEXT NOT NULL,
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `elementtypes` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(127) NOT NULL,
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB")->execute();
		
		// Create the admin user
		$admin = Sprig::factory('user',array(
			'username'=>'admin',
			'password'=>$_POST['password'],
			'password_confirm'=>$_POST['password']
		));
		$admin->create();
		
		// Create sample layouts, pages, and content
		
		$queries = explode(";\n",trim("
			
			INSERT INTO `layouts` (`id`, `name`, `desc`, `code`) VALUES ('1', 'Home', 'Home page', '{{ Kohanut.stylesheet(\'default.css\') }}\n\n<div id=\'wrapper\'>\n<div id=\'logo\'>\n    <h1><a href=\'/\'>Site Name</a></h1>\n    <h2>Tag line goes here</h2>\n</div>\n<div id=\'header\'>\n    <div id=\'menu\'>\n        {{ Kohanut.main_nav(2) }}\n    </div>\n    {{ Kohanut.bread_crumbs() }}\n</div>\n\n<div id=\'page\'>\n    <div id=\'content\'>\n         {{ Kohanut.content_area(1,\'Main Column\') }}\n    </div>\n    <div id=\'sidebar\'>\n         {{ Kohanut.content_area(2,\'Side Column\') }}\n    </div>\n    <div style=\'clear:both;\'></div>\n</div>\n{{ Kohanut.element(\'snippet\',\'footer\') }}\n</div>');
			INSERT INTO `layouts` (`id`, `name`, `desc`, `code`) VALUES ('2', 'Two Column', 'Standard two column template', '{{ Kohanut.stylesheet(\'default.css\') }}\n{{ Kohanut.meta(\"<meta name=\'something\' value=\'this is cool\' />\") }}\n\n<div id=\'wrapper\'>\n<div id=\'logo\'>\n    <h1><a href=\'/\'>Site Name</a></h1>\n    <h2>Tag line goes here</h2>\n</div>\n<div id=\'header\'>\n    <div id=\'menu\'>\n        {{ Kohanut.main_nav(2) }}\n    </div>\n    {{ Kohanut.bread_crumbs() }}\n</div>\n\n<div id=\'page\'>\n    <div id=\'content\'>\n         {{ Kohanut.content_area(1,\'Main Column\') }}\n    </div>\n    <div id=\'sidebar\'>\n         {{ Kohanut.secondary_nav() }}\n         {{ Kohanut.content_area(2,\'Side Column\') }}\n    </div>\n    <div style=\'clear:both;\'></div>\n</div>\n{{ Kohanut.element(\'snippet\',\'footer\') }}\n</div>');
			INSERT INTO `layouts` (`id`, `name`, `desc`, `code`) VALUES ('3', 'Error', 'Error Layout', '{{ Kohanut.stylesheet(\'default.css\') }}\n\n<div id=\'wrapper\'>\n<div id=\'logo\'>\n    <h1><a href=\'/\'>Site Name</a></h1>\n    <h2>Tag line goes here</h2>\n</div>\n<div id=\'header\'>\n    <div id=\'menu\'>\n        {{ Kohanut.main_nav(2) }}\n    </div>\n    {{ Kohanut.bread_crumbs() }}\n</div>\n\n<div id=\'page\'>\n    <div id=\'content\'>\n         {{ Kohanut.content_area(1,\'Main Column\') }}\n    </div>\n    <div id=\'sidebar\'>\n         {{ Kohanut.content_area(2,\'Side Column\') }}\n    </div>\n    <div style=\'clear:both;\'></div>\n</div>\n{{ Kohanut.element(\'snippet\',\'footer\') }}\n</div>');

			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('1', '', 'Home', '1', '0', '1', '1', 'Home Page', 'Meta description!', 'meta, keywords, go, here', '1', '16', '0', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('2', 'products', 'Products', '2', '0', '1', '1', 'Products', 'Meta Desc', 'keywords', '4', '9', '1', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('3', 'products/something', 'Something', '2', '0', '1', '1', '', '', '', '5', '6', '2', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('4', 'http://google.com', 'External Link', '2', '1', '1', '1', '', '', '', '7', '8', '2', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('5', 'about', 'About', '2', '0', '1', '1', '', '', '', '10', '13', '1', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('6', '', 'Home', '2', '1', '1', '1', '', '', '', '2', '3', '1', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('7', 'about/story', 'Story', '2', '0', '1', '1', '', '', '', '11', '12', '2', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('8', 'error', 'Error', '3', '0', '0', '0', 'Page not found', '', '', '14', '15', '1', '1');

			INSERT INTO `elementtypes` (`id`,`name`) VALUES ('1','content');
			INSERT INTO `elementtypes` (`id`,`name`) VALUES ('2','request');
			INSERT INTO `elementtypes` (`id`,`name`) VALUES ('3','snippet');
			
			INSERT INTO `blocks` (`id`, `page`, `area`, `order`, `elementtype`, `element`) VALUES ('1', '1', '1', '1', '1', '1');
			INSERT INTO `blocks` (`id`, `page`, `area`, `order`, `elementtype`, `element`) VALUES ('2', '1', '2', '1', '1', '2');
			INSERT INTO `blocks` (`id`, `page`, `area`, `order`, `elementtype`, `element`) VALUES ('3', '8', '1', '1', '1', '3');
			
			INSERT INTO `element_content` (`id`, `code`, `markdown`, `twig`) VALUES ('1', '<h1>Thanks for Using Kohanut!</h1>\n\n<p>If you are seeing this message that Kohanut has been successfully installed and is ready to go!  You can browse around the default site using the navigation at the top of the screen.  To log in to the admin and start making changes simply browse to <a href=\"/admin\">/admin</a> and log in using the password you entered during the install.  Enjoy!</p>', '0', '0');
			INSERT INTO `element_content` (`id`, `code`, `markdown`, `twig`) VALUES ('2', '<p>This is the side column. I would put calls to action or something over here</p>', '0', '0');
			INSERT INTO `element_content` (`id`, `code`, `markdown`, `twig`) VALUES ('3', '# Page not found\n\nThe page could not be found.  You could try going [Home](/) or see our [Products](/products).\n\n{{ Kohanut.status(404) }}', '1', '1');


			INSERT INTO `element_snippet` (`id`, `code`, `name`, `markdown`, `twig`) VALUES ('1', '<div id=\"footer\">\n    <p>This is the Footer. {{ Kohanut.render_stats() }}</p>\n</div>\n\n', 'Footer', '0', '1');
			INSERT INTO `element_snippet` (`id`, `code`, `name`, `markdown`, `twig`) VALUES ('2', 'This is some kind of random snippet.', 'Sample Snippet', '1', '1');

		"));
		
		foreach ($queries as $query)
		{
			DB::query(NULL,$query)->execute();
		}
		
		$this->request->response = new View('kohanut/admin/install-success');
		return;
	
	}
}