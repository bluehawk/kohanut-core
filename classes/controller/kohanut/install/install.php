<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Kohanut_Install_Install extends Controller {

	public function action_index()
	{
		
		$post = Validate::factory($_POST)
			->rule('password','not_empty')
			->rule('repeat','not_empty')
			->rule('password','min_length',array(6))
			->rule('password','matches',array('repeat'));
			
		if ( ! $post->check()) {
		
			$view = new View('kohanut/install');
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
				`showmap` TINYINT(3) UNSIGNED NULL DEFAULT '1',
				`shownav` TINYINT(3) UNSIGNED NULL DEFAULT '1',
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
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB")->execute();
				
		DB::query(NULL,"
			CREATE TABLE `element_snippet` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`code` TEXT NOT NULL,
				`name` VARCHAR(127) NOT NULL,
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB")->execute();
				
		DB::query(NULL,"
			CREATE TABLE `element_code` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`code` TEXT NOT NULL,
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
			INSERT INTO `layouts` (`id`, `name`, `desc`, `code`) VALUES ('1', 'Home', 'Home page', '<?php Kohanut::stylesheet(\'default.css\'); ?>\n\n<div id=\'wrapper\'>\n<div id=\'logo\'>\n    <h1><a href=\'/\'>Site Name</a></h1>\n    <h2>Tag line goes here</h2>\n</div>\n<div id=\'header\'>\n    <div id=\'menu\'>\n        <?php echo Kohanut::main_nav(2); ?>\n    </div>\n    <?php echo Kohanut::bread_crumbs(); ?>\n</div>\n\n<div id=\'page\'>\n    <div id=\'content\'>\n        <?php echo Kohanut::content_area(1,\'Main Column\') ?>\n    </div>\n    <div id=\'sidebar\'>\n        <?php echo Kohanut::content_area(2,\'Side Column\') ?>\n    </div>\n    <div style=\'clear:both;\'></div>\n</div>\n\n<?php echo Kohanut::element(\'snippet\',\'footer\'); ?>\n\n</div>');
			INSERT INTO `layouts` (`id`, `name`, `desc`, `code`) VALUES ('2', 'Two Column', 'Standard two column template', '<?php Kohanut::stylesheet(\'default.css\'); ?>\n<?php Kohanut::meta(\"<meta name=\'something\' value=\'this is cool\' />\") ?>\n\n<div id=\'wrapper\'>\n<div id=\'logo\'>\n    <h1>Site Name</h1>\n    <h2>Tag line goes here</h2>\n</div>\n<div id=\'header\'>\n    <div id=\'menu\'>\n        <?php echo Kohanut::main_nav(2); ?>\n    </div>\n    <?php echo Kohanut::bread_crumbs(); ?>\n</div>\n\n<div id=\'page\'>\n    <div id=\'content\'>\n        <?php echo Kohanut::content_area(1,\'Main Column\') ?>\n    </div>\n    <div id=\'sidebar\'>\n        <?php echo Kohanut::secondary_nav() ?>\n        <?php echo Kohanut::content_area(2,\'Side Column\') ?>\n    </div>\n    <div style=\'clear:both;\'></div>\n</div>\n<?php echo Kohanut::element(\'snippet\',\'footer\'); ?>\n\n</div>');
			
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('1', '/', 'Home', '1', '0', '1', '1', 'Home Page', 'Meta description!', 'meta, keywords, go, here', '1', '12', '0', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('2', '/products', 'Products', '2', '0', '1', '1', 'Products', 'Meta Desc', 'keywords', '4', '11', '1', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('3', '/products/something', 'Something', '2', '0', '1', '1', '', '', '', '5', '6', '2', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('4', 'http://google.com', 'External Link', '2', '1', '1', '1', '', '', '', '7', '8', '2', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('5', '/about', 'About', '2', '0', '1', '1', '', '', '', '9', '10', '1', '1');
			INSERT INTO `pages` (`id`, `url`, `name`, `layout`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('6', '/', 'Home', '2', '1', '1', '1', '', '', '', '2', '3', '1', '1');

			INSERT INTO `elementtypes` (`id`,`name`) VALUES ('1','content');
			INSERT INTO `elementtypes` (`id`,`name`) VALUES ('2','code');
			INSERT INTO `elementtypes` (`id`,`name`) VALUES ('3','snippet');
			
			INSERT INTO `blocks` (`id`, `page`, `area`, `order`, `elementtype`, `element`) VALUES ('1', '1', '1', '1', '1', '1');
			INSERT INTO `blocks` (`id`, `page`, `area`, `order`, `elementtype`, `element`) VALUES ('2', '1', '2', '1', '1', '2');
			
			INSERT INTO `element_content` (`id`, `code`) VALUES ('1',  '<h1>Thanks for Using Kohanut!</h1>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam orci nisl, interdum nec molestie eget, tempus vel mi. Donec tristique quam sed orci interdum quis ultricies quam malesuada. Quisque auctor tincidunt tellus at aliquet. Nullam nec posuere tortor. Donec scelerisque cursus mollis. Pellentesque quam nisi, rhoncus vitae feugiat ac, vestibulum sed ligula. Vivamus risus lacus, viverra et dictum in, feugiat sed lorem. Fusce eu arcu sit amet felis elementum aliquet eget et nunc. Aliquam dictum ligula imperdiet urna tempus quis suscipit erat ultricies. Sed imperdiet pretium vehicula.</p>');
			INSERT INTO `element_content` (`id`, `code`) VALUES ('2',  '<p>Content</p>');
		"));
		
		foreach ($queries as $query)
		{
			DB::query(NULL,$query)->execute();
		}
		
		$this->request->response = new View('kohanut/install-success');
		return;
	
	}
}