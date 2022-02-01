1) "Links to both your own page and external pages"

Internal links:
- Home --> How to Help 
- Navigation bar

External links:
- Home --> social media
- How to Help --> social media
- How to Help --> donation platform

2) "Navigation bar"

Animated nav bar enclosed in <nav> tag in the <header> of every page

3) "At least one table used in an appropriate manner"

Table found on How to Help page.

4) "At least one list (ordered or unordered)"

- Navbar
- Home & How to Help --> Social media link list
- Our Story --> recruitment list
- How to Help --> list of upcoming events

5) "At least one local or embedded video"

Video page contains embedded video

6) "At least five CSS3 and four HTML5 specific elements"

CSS3:
- border-radius (LINE 297 & 180)
- opacity (LINE 126)
- media queries (LINE 300)
- flexbox properties (seen numerous times throughout, e.g. LINE 161)
- last-child pseudoelement (LINE265)

HTML5:
- <header>
- <footer>
- <section>
- <nav>
- <time> (How to Help LINE 95-101)

7) "Make use of the CSS positional properties (e.g. position, float)"

Float: 
- seen in the header and footer as the logo floats to the left and the navbar floats to the right. The <li> elements of the navbar float left of one another to maintain the correct order.
- seen in the careers section as the list floats left and the message floats right

Position:
- header logo is fixed
- header/footer are relatively positioned, with 10px added to bottom position
- navbar position absolute within header

8) "Make use of both inline and block elements"

- inline elements used include <span>, <a>, <img>, etc

- block elements used include <div>, <h1>, <p>, <ul>, <li>, etc



*** Additional features of HTML and CSS ***

There are two key elements that I have included in my website which allow for a responsivene design while maintaining user friendliness. 

--> media queries
--> flex display

Media queries - the @media element of CSS allows for the styling concerned to be applied under particular circumstances. The Gallery page includes a media query that changes the photo layout to be 1 column wide rather than 3 once the screen is smaller than 750px wide. This allows for a greater user experience when the browser window is resized, and negates the need for scrolling horizontally.

Flex display - the display: flex property, and subsequent flex-direction & flex-wrap elements aid with responsive design. This was useful for items like social media list, where I was able to achieve a layout that maintains readability when the screen is resized. The list items wrap neatly under one another in a flexible design.