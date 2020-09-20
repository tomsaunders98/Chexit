# Code for Chexit.co.uk
![Chexit logo](https://github.com/tomsaunders98/Chexit/raw/master/logo.png "Logo Title Text 1")
This is the underlying front-end and back-end code for the website [Chexit.co.uk](https://chexit.co.uk/). 

### Requirements
* Jquery
* Popper.js
* Chart.js
* Bootstrap
* Mysql/PHP

### Guide to the files
#### Headers and Footers
* headers
	* header1.php (back-end header for mysql etc.)
	* header.php (front-end header)
* footer.php (front-end footer for most pages)

#### Simple Pages
* About.php (self-explanatory)
* contact.php (self-explanatory)
* index.php (homepage)

#### Recal.php
This turns the CSV files which were taken from Commons digivotes (not hansard as their vote tallies are rubbish) and process them before entering them into the MySQL database.
There was a fair amount of data-wrangling needed to process these properly as vote Tellers were regestered as TelY or TelN so that their votes wouldn't be counted in total votes for/against a motion but would still appear on the Teller's profile. More information on Tellers [here](https://www.parliament.uk/site-information/glossary/tellers/).

Also votes for Speaker + Deputy Speakers + Sinn Fein had to be ignored. Data is filtered by Constitutency as names like Therese Coffey seem to be spelt a million different ways, not to mention the various MPs with titles.

Boilerplate for this code was taken from [here](http://code.google.com/p/php-excel-reader/).

#### Search.php
Visualises all of the data from Mastervotes databse. You may be wondering about this little piece of magic on lines 316-321:
```php
$url = 'search.php?id=' . $id;
			if ($namelow === strtolower($MPtext)){// Matching name then redirect
				$isres = 1;
				header("Location:" . $url);
				exit();
			}
```
I can explain. The first version of this used a simple POST value sent from wherever to display results. I then realised that this meant that noone could share the page of an individual MP so I put this hack together which redirects the POST variable into a GET then sends a header redirect. Yeah i'm not proud of it.

#### Chart Viz
* main.js
	* sends Ajax request for chart on click to chart.php with GET and then adds description for chart from details.html
* chart.php
	* develops chart.js code from Mastervotes data
* details.html
	* contains decsription for every vote

## Share Your Thoughts
I'm on twitter at [@tomjs](https://twitter.com/tomjs). If you have any questions/suggestions please let me know! 


<a rel="license" href="http://creativecommons.org/licenses/by/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by/4.0/88x31.png" /></a><br />
