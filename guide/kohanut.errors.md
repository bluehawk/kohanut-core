# Error Pages In Kohanut

In order for error pages to show up properly, there must be a page with the url `error`, preferably with the Show in Navigation and Show in Sitemap check marks turned off.  Somewhere on this page, either in the layout, or in a piece of content, the following line must appear:

    {{ Kohanut.status(404) }}

You can style and theme the error page however you want, and change any text on it, as long as it has this line.

[!!] If `Kohanut.status(404)` is in a content or snippet element the Enable Twig check box will need to be checked.