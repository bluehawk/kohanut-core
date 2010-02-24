# What is Kohanut?

Kohanut is an open source, extensible [Content Management System](http://en.wikipedia.org/wiki/Content_management_system) that is built on the [Kohana Framework](http://kohanaphp.com).

One of the goals of Kohanut is to try to be as out of the way as possible, I designed it so you can call other Kohana controllers from inside of the CMS, or call the CMS from your controllers. It does not (and probably never will have) themes, as instead it allows you to construct your html and include whatever css and js files you want. The default content elements themselves support [Twig](http://twig-project.org) and [Markdown](http://daringfireball.net/projects/markdown/), so writing content is easy, even for people who don't know html. The idea is that content is easy to write, so you can spend your time working on the fun parts of websites, and when it's done, the client could potentially log in and make minor changes.

The major features still missing are ACL, caching, better configuration (setting a default template, or setting an error page other than "/error"), and better documentation.

## Who has contributed to Kohanut?

Kohanut was developed by Michael Peters, but it uses many libraries and modules developed by others.  I would like to thank the following for their contributions:

 * [Kohana 3](http://kohanaphp.com) by the Kohana Team
 * [Sprig](http://github.com/shadowhand/sprig) by shadowhand
 * [Sprig-MPTT](http://github.com/banks/sprig-mptt) by banks
 * [The Twig Project](http://www.twig-project.org/) by Fabien Potencier
 * [Markdown](http://daringfireball.net/projects/markdown/) by Daring Fireball
 * [Mathew Davies](http://mathew-davies.co.uk) for his xhtml and css design work
 * Michael Ebert for his [database design](http://kohanut.com/images/cms-database-design-03.png)