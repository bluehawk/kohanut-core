# Installation

To install Kohanut, follow these instructions:

## Installing using the zip

1.  Download the [latest zip file](http://kohanut.com/download), and extract it onto your server.

2.  We need to make `application/logs` and `application/cache`, and `application/cache/twig` folders writable, Using an ftp client you can usually right-click on each folder, click on permisions, and mark as group and world writable.  If you have command-line access you can run the following commands:

   ~~~
   chmod 777 application/logs
   chmod 777 application/cache
   chmod 777 application/cache/twig
   ~~~

3.  You will need to create a database, and a database user with access to that database, and then edit `application/config/database.php` with those settings.  Here is the relevant pieces of that file:

   ~~~
   'hostname'   => 'localhost',
   'username'   => 'PUT USERNAME HERE',
   'password'   => 'PUT PASSWORD HERE',
   'persistent' => FALSE,
   'database'   => 'PUT DATABASE HERE',
   ~~~
   
4.  Now point your browser to `/admin/install` and enter a password for the user `admin`, and click install. If you get no errors, then Kohanut is installed!

5.  For security, you should rename `modules/kohanut/controllers/admin/install` to something like `modules/kohanut/controllers/admin/installed`

## Installing from github:

**Note: This is not recommended as the github code may be unstable**

1. Download the code by cloning the repository, and fetching the submodules

   ~~~
   git clone git://github.com/bluehawk/kohanut.git
   cd kohanut/
   git submodule init
   git submodule update
   # now fetch the vendor stuff, like twig
   cd modules/kohanut
   git submodule init
   git submodule update
   ~~~

   
2. Continue from step 2 above

## Putting Kohanut in a subfolder

If Kohanut is not in the root of the server we need to change some files.  Lets say we are putting kohanut in a subfolder called "subfolder"

In **.htaccess**

    RewriteBase /
    -- change to --
    RewriteBase /subfolder
  
In **application/bootstrap.php**
   
    'base_url'   => '/',
    -- change to --
    'base_url'   => '/subfolder',

If you put Kohanut in a subfolder, the links on all your pages will probably be broken, especially if you move a site that is already made. You could probably fix it by adding a [<base\>](http://w3schools.com/tags/tag_base.asp) tag.