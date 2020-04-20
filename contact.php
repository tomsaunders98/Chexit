<?php
include("header1.php");
include("header.php");
?>
<div class="text-center searchfield">
	<form method="post" action="search.php">
		<input class="searchbox"  name="searchtext" placeholder="Enter an MPs name or constituency ...">
	<a clas="searchlink" href="#">
		<input type="image" class="searchglass img-fluid" src="img/search.png" alt="Submit Form" />
	</a>
	</form>
</div>
<div class="whitebody">
	<div class="brextables text-center">
		<h1> Contact me </h1>
	<p> If you see a bug in this site or an error in the vote tallying or record for any of the MPs please don't hesitate to let me know by email at <script type="text/javascript" language="javascript">
			<!--
			// Email obfuscator script 2.1 by Tim Williams, University of Arizona
			// Random encryption key feature coded by Andrew Moulden
			// This code is freeware provided these four comment lines remain intact
			// A wizard to generate this code is at http://www.jottings.com/obfuscator/
			{ coded = "jUShbrjHGbGKQ@TSh8Y.kUS"
			key = "fLqZt7eNsMHV63SF48IkubUEdlajwGOQpygXv2xW1DR0BzPrYh9K5mCAiTcJno"
			shift=coded.length
			link=""
			for (i=0; i<coded.length; i++) {
			if (key.indexOf(coded.charAt(i))==-1) {
			  ltr = coded.charAt(i)
			  link += (ltr)
			}
			else {     
			  ltr = (key.indexOf(coded.charAt(i))-shift+key.length) % key.length
			  link += (key.charAt(ltr))
			}
			}
			document.write("<a href='mailto:"+link+"'>"+link+"</a>")
			}
			//-->
		</script>.</p>
		<p> If this site interests you then please check out my <a href="http://medium.com/@tomandthenews"> blog</a> where you can read my articles or follow me on twitter at <a href="https://twitter.com/tomandthenews">@tomandthenews</a>. </p>
		</div>
	</div>
</body>
</html>