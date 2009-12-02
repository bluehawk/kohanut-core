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
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT
			AUTO_INCREMENT=3
			AVG_ROW_LENGTH=8192")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `pages` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`url` VARCHAR(256) NOT NULL,
				`name` VARCHAR(128) NOT NULL,
				`layout_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
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
				INDEX `Page-Layout` (`layout_id`),
				CONSTRAINT `Page-Layout` FOREIGN KEY (`layout_id`) REFERENCES `layouts` (`id`) ON UPDATE NO ACTION
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT
			AUTO_INCREMENT=13
			AVG_ROW_LENGTH=2340")->execute();
			
		DB::query(NULL,"
			CREATE TABLE `redirects` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`url` VARCHAR(255) NOT NULL,
				`newurl` VARCHAR(255) NOT NULL,
				`type` ENUM('301','302') NOT NULL DEFAULT '302',
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT
			AUTO_INCREMENT=2
			AVG_ROW_LENGTH=16384")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `users` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`username` VARCHAR(30) NOT NULL,
				`password` VARCHAR(40) NULL DEFAULT NULL,
				`last_login` DATETIME NULL DEFAULT NULL,
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT
			AUTO_INCREMENT=2
			AVG_ROW_LENGTH=16384")->execute();
		
		DB::query(NULL,"
			CREATE TABLE `pagecontents` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`page_id` INT(11) NOT NULL,
				`area_id` INT(11) NOT NULL,
				`order` INT(11) NOT NULL,
				`elementtype_id` INT(11) NOT NULL,
				`element_id` INT(11) NOT NULL,
				PRIMARY KEY (`id`)
			)
			COLLATE=latin1_swedish_ci
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT
			AUTO_INCREMENT=15
			AVG_ROW_LENGTH=5461")->execute();
		
		// Create sample layouts
		DB::query(NULL,"
			INSERT INTO `layouts` (`id`, `name`, `desc`, `code`) VALUES ('1', 'Home', 'Home page', '<?php Kohanut::stylesheet(\'default.css\'); ?>\n\n<div id=\'wrapper\'>\n<div id=\'logo\'>\n    <h1><a href=\'/\'>Site Name</a></h1>\n    <h2>Tag line goes here</h2>\n</div>\n<div id=\'header\'>\n    <div id=\'menu\'>\n        <?php Kohanut::main_nav(2); ?>\n    </div>\n    <?php Kohanut::bread_crumbs(); ?>\n</div>\n\n<div id=\'page\'>\n    <div id=\'content\'>\n        <?php Kohanut::content_area(1,\'Main Column\') ?>\n    </div>\n    <div id=\'sidebar\'>\n        <?php Kohanut::content_area(2,\'Side Column\') ?>\n    </div>\n    <div style=\'clear:both;\'></div>\n</div>\n\n<?php Kohanut::element(\'snippet\',\'footer\'); ?>\n\n</div>');
		")->execute();
		DB::query(NULL,"
			INSERT INTO `layouts` (`id`, `name`, `desc`, `code`) VALUES ('2', 'Two Column', 'Standard two column template', '<?php Kohanut::stylesheet(\'default.css\'); ?>\n<?php Kohanut::meta(\'<meta name=\'something\' value=\'this is cool\' />\') ?>\n\n<div id=\'wrapper\'>\n<div id=\'logo\'>\n    <h1>Site Name</h1>\n    <h2>Tag line goes here</h2>\n</div>\n<div id=\'header\'>\n    <div id=\'menu\'>\n        <?php Kohanut::main_nav(2); ?>\n    </div>\n    <?php Kohanut::bread_crumbs(); ?>\n</div>\n\n<div id=\'page\'>\n    <div id=\'content\'>\n        <?php Kohanut::content_area(1,\'Main Column\') ?>\n    </div>\n    <div id=\'sidebar\'>\n        <?php Kohanut::secondary_nav() ?>\n        <?php Kohanut::content_area(2,\'Side Column\') ?>\n    </div>\n    <div style=\'clear:both;\'></div>\n</div>\n<?php Kohanut::element(\'snippet\',\'footer\'); ?>\n\n</div>');
		")->execute();

		// Create the sample pages
		DB::query(NULL,"INSERT INTO `pages` (`id`, `url`, `name`, `layout_id`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('1', '/', 'Home', '1', '0', '1', '1', 'Home Page', 'Meta description!', 'meta, keywords, go, here', '1', '12', '0', '1');")->execute();
		DB::query(NULL,"INSERT INTO `pages` (`id`, `url`, `name`, `layout_id`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('7', '/products', 'Products', '2', '0', '1', '1', 'Products', 'Meta Desc', 'keywords', '4', '11', '1', '1');")->execute();
		DB::query(NULL,"INSERT INTO `pages` (`id`, `url`, `name`, `layout_id`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('8', '/products/something', 'Something', '2', '0', '1', '1', '', '', '', '5', '6', '2', '1');")->execute();
		DB::query(NULL,"INSERT INTO `pages` (`id`, `url`, `name`, `layout_id`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('10', 'http://google.com', 'External Link', '2', '1', '1', '1', '', '', '', '7', '8', '2', '1');")->execute();
		DB::query(NULL,"INSERT INTO `pages` (`id`, `url`, `name`, `layout_id`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('11', '/about', 'About', '2', '0', '1', '1', '', '', '', '9', '10', '1', '1');")->execute();
		DB::query(NULL,"INSERT INTO `pages` (`id`, `url`, `name`, `layout_id`, `islink`, `showmap`, `shownav`, `title`, `metadesc`, `metakw`, `lft`, `rgt`, `lvl`, `scp`) VALUES ('12', '/', 'Home', '2', '1', '1', '1', '', '', '', '2', '3', '1', '1');")->execute();
		
		// Create the admin user
		$admin = Sprig::factory('user',array(
			'username'=>'admin',
			'password'=>$_POST['password'],
			'password_confirm'=>$_POST['password']
		));
		$admin->create();
		
		$this->request->response = new View('kohanut/install-success');
		return;
	
	}
}