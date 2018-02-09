<h1>PHP and CSS changes for the Commonwealth Autism web conversion project.</h1>
csvfix_filter.txt config file for csvfix<br>
csvfix_adult.txt csv validate category 'adult'<br>
csvfix_education.txt validate category 'education'<br>
csvfix_funding.txt validate category 'funding'<br>
csvfix_general.txt validate category 'general'<br>
csvfix_legal.txt validate category 'legal'<br>
csvfix_medical.txt validate category 'medical'<br>
csvfix_recreation.txt validate category 'recreation'<br>
csvfix_resource.txt validate category 'resource'<br>
filter-adult.csv file filtered for category 'adult'<br>
filter-education.csv file filtered for category 'education'<br>
filter-funding.csv file filtered for category 'funding'<br>
filter-general.csv file filtered for category 'general'<br>
filter-legal.csv file filtered for category 'legal'<br>
filter-recreation.csv file filtered for category 'recreation'<br>
filter-resource.csv file filtered for category 'resource'<br>
functions.php contains the shortcode for the resource database search<br>
resource-cat.php is the template for cpt resource_db<br>
resource-result-cat.php is obsolete<br>
resource-search-cat.php os obsolete<br>
resource-search-cat2 is obsolete<br>
resource-search is obsolete<br>
resource-json.txt is the json code for use with csvlint<br>
resource-show-results.php is the template for showing the results of a search on the resource database<br>
single-resource_db.php is the template for showing a single post of the resource database<br>
style.css is the css in the child theme, it4-causes-dev-site<br>

<h2>To extract the resource database from the ca site</h2>
http://www.autismva.org/export-resources/export-resources-data<br>

A screen will come up with a view of the database. The two input fields are for beginning and ending nid. The first one in the db is 77, and the last one is 1236. I couldn't extract all of them at once, so I used 77-600 for the first half. If it times out, just keep trying, and it should eventually work. Scroll down to the bottom and click on the csv button. It will download to your download folder, with the name export-resource-data. Rename it before extracting the second half, using nid 601-1236.<br>
<h2>Customization</h2>
A custom post type, resource_db was created for handling the resource database. Three taxonomies were created--resource-cat, resource-region, and resource_age, corresponding to the form fields on the search page. They were built with plugin Custom UI.<br>
The search page name is resource-finder, and it uses the default template. The content of this page uses a shortcode<br>
[resource_search]<br>
to execute the function resource_search_form in functions.php.<br>
The search form then passes the form variables to admin_post.php, the standard wordpress form handler. The function prefix_resource_search_cat processes the form, executes the search, and passes control to page resource_result_cat. This page uses template resource-show-results.php to display the result.
If an item on the results page is clicked, template page single-resource_db.php is used.<br>
<h2>Region category tweak</h2>
To prevent the region "No location" from showing up on the search page, it's necessary to modify this code in functions.php:
	wp_dropdown_categories( 'show_count=0&exclude=98&show_option_none=Select by region&name=$region_id&taxonomy=resource_region&echo=0' ) 
Change 'exclude=xx', where xx is the region category id for 'No location.'