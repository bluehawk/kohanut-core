# How it works

Each page in Kohanut has one or more **Content Areas** and these areas hold **Blocks** with represent different **Elements**.

Execution starts with `Controller_Kohanut->action_view()`.  Lets say we request the page "about".  Kohanut looks in the database for a page with that url.

### kohanut_pages table
| id | url | layout | ...(more columns not shown)... |
|:---:|:------:|:---------:|:-----:|
| 8 | about | 2 | ...|

It looks like the page "about" has an id of 8 and layout number 2.  `Kohanut_Page->render()` is called, which finds the layout:

### kohanut_layouts table
| id | name | code |
|:--:|:----:|----|
| 2  |Two Column| ...|

`Kohanut_Layout->render()` is called, which parses the code through Twig. The navs, breadcrumbs, snippets, and content areas are drawn.  For example, if the layout has the line `{{ Kohanut.content_area(1,'Main Column') }}`, we will look in the blocks table for elements on this page and this area:

### kohanut_blocks table
| page | area | elementtype | element |
|:--:|:--:|:--:|:--:|
| 8 | 1 | 1 | 65 |
| 8 | 1 | 2 | 4  |
| 8 | 1 | 1 | 67 |

We would then find which elementtype id 1 and 2 are.

### kohanut_elementtypes table
| id | name |
|:-:|:-:|
| 1  | content |
| 2 | request |

We then try to find the classes `Kohanut_Element_Content` and `Kohanut_Element_Request`, and pass them the ids from the kohanut_blocks table, and each elements `render()` function is called.