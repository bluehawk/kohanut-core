# Using Markdown

Markdown is used to parse text and turn it in the HTML, without having to put the usual HTML tags around it.  Markdown is used by **Content** and **Snippets** only if "Use Markdown" is checked.

[!!] These are the most common markdown syntax. Please see the [full syntax](http://daringfireball.net/projects/markdown/syntax) and the [PHP Markdown Extra](http://michelf.com/projects/php-markdown/extra/) pages if you have any questions.


### Contents

1.  [Headers](#headers)
1.  [Paragraphs](#paragraphs)
1.  [Links](#links)
1.  [Lists](#lists)
1.  [Italics and Bold](#italics-and-bold)
1.  [Images](#images)
1.  [Horizontal Rules](#horizontal-rules)
1.  [Tables](#tables)
1.  [Using HTML](#using-html)


<div style="clear:both;"></div>
## Headers        {#headers}

~~~
# This is Header 1
## This is a Header 2
### This is a Header 3 (etc, up to level 6)

(Alternate syntax:)

Header 1
========

Header 2
--------
~~~

<div class="sample" markdown="1">
# This is Header 1
## This is a Header 2
### This is a Header 3 (etc, up to level 6)

Header 1
========

Header 2
--------
</div>

You can also create header ids, and link to them.

~~~
Header 1            {#header1}
========

## Header 2 ##      {#header2}

[Link back to header 1](#header1)
~~~

<div class="sample" markdown="1">
Header 1            {#header1}
========

## Header 2 ##      {#header2}

[Link back to header 1](#header1)
</div>

## Paragraphs        {#paragraphs}

~~~
Lines of text are turned into paragraphs. And chars like > and & are escaped for you.

Note that you need to have a blank line between paragraphs.
For example, this is not a new paragraph.
~~~

<div class="sample" markdown="1">
Lines of text are turned into paragraphs. And chars like > and & are escaped for you.

Note that you need to have a blank line between paragraphs.
For example, this is not a new paragraph.
</div>

## Links      {#links}

~~~
This is [an example](http://example.com "Title") with a title.

[This link](http://example.com) has no title.

If you are refering to a page on this site, you should use a [relative link](using.markdown).
~~~
<div class="sample" markdown="1">
This is [an example](http://example.com "Title") with a title.

[This link](http://example.com) has no title.

If you are refering to a page on this site, you should use a [relative link](using.markdown).
</div>


## Lists       {#lists}

~~~
*   To make a unordered list, put an asterisk and three spaces
*   On each line that you want a bullet on
-   You can also use - or +
+   And you can mix them, but it will make no difference in the final page
~~~

<div class="sample" markdown="1">
*   To make a unordered list, put an asterisk and three spaces
*   On each line that you want a bullet on
-   You can also use - or +
+   And you can mix them, but it will make no difference in the final page
</div>

~~~
1.  For ordered lists, put a number and a period
2.  On each line that you want numbered.
9.  It doesn't actually have to be the correct number order
5.  Just as long as each line has a number
~~~

<div class="sample" markdown="1">
1.  For ordered lists, put a number and a period
2.  On each line that you want numbered.
9.  It doesn't actually have to be the correct number order
5.  Just as long as each line has a number
</div>

~~~
*   To nest lists you just add four spaces before the * or number
    1.  Like this
        *   It's pretty basic, this line has eight spaces, so its nested twice
~~~

<div class="sample" markdown="1">
*   To nest lists you just add four spaces before the * or number
    1.  Like this
        *   It's pretty basic, this line has eight spaces, so its nested twice
</div>

~~~
1.  If you put a blank line before and after each list item

1.  Then they will be wrapped in paragraphs, which will make them take up more space

1.  This can help make them more readable
~~~

<div class="sample" markdown="1">
1.  If you put a blank line between each list item

1.  Then they will be wrapped in paragraphs, which will make them take up more space

1.  This can help make them more readable
</div>

## Italics and Bold         {#italics-and-bold}

~~~
*Asterisks and underscores both add emphasis*

_Another word for emphasis is italics_

**Double asterisks or underscores make text strong**

__Or in other words, bold__
~~~

<div class="sample" markdown="1">
*Asterisks and underscores both add emphasis*

_Another work for emphasis is italics_

**Double asterisks or underscores make text strong**

__Or in other words, bold__
</div>

## Images         {#images}

~~~
![Alt text](/path/to/img.jpg)

![Alt text](/path/to/img.jpg "Optional title")
~~~

<div class="sample" markdown="1">
![Alt text](/path/to/img.jpg)

![Alt text](/path/to/img.jpg)
</div>

## Horizontal Rules    {#horizontal-rules}

Horizontal rules are made by placing 3 or more hyphens, asterisks or underscores on a line by themselves.

~~~
---
* * *
___________________________
~~~

<div class="sample" markdown="1">
---
* * *
___________________________
</div>

## Tables         {#tables}

~~~
First Header  | Second Header
------------- | -------------
Content Cell  | Content Cell
Content Cell  | Content Cell
~~~

<div class="sample" markdown="1">
First Header  | Second Header
------------- | -------------
Content Cell  | Content Cell
Content Cell  | Content Cell
</div>

Note that the pipes on the left and right side are optional, and you can change the text-alignment by adding a colon on the right, or on both sides for center.

~~~
| Item      | Value | Savings |
| --------- | -----:|:-------:|
| Computer  | $1600 |   40%   |
| Phone     |   $12 |   30%   |
| Pipe      |    $1 |    0%   |
~~~

<div class="sample" markdown="1">
| Item      | Value | Savings |
| --------- | -----:|:-------:|
| Computer  | $1600 |   40%   |
| Phone     |   $12 |   30%   |
| Pipe      |    $1 |    0%   |
</div>

## Using HTML    {#using-html}

You can still use html on your pages, even with Markdown turned on, but markdown won't be parsed inside of html elements unless you tell it to. For example:

~~~
<div class="something">
This [link](http://kohanut.com) **won't** get parsed!
</div>
~~~
<div class="sample" markdown="1">
<div class="something">
This [link](http://kohanut.com) **won't** get parsed!
</div>
</div>

Unless you add `markdown="1"` to the div.  The markdown tag will be removed and the markdown will be processed.

~~~
<div class="something" markdown="1">
This [link](http://kohanut.com) **will** get parsed!
</div>
~~~
<div class="sample" markdown="1">
<div class="something" markdown="1">
This [link](http://kohanut.com) **will** get parsed!
</div>
</div>

### If you have any other questions, please see the [full syntax](http://daringfireball.net/projects/markdown/syntax) and the [PHP Markdown Extra](http://michelf.com/projects/php-markdown/extra/) pages.