<?php
include("header1.php");
include("header.php");
?>
<header class="header">  
	<div class="container">
		<div class="description text-center">   
			<h1 class="display-1">C</h1><h2>hexit.</h2>
			<p>What do they really think ?</p>
			<form method="post" action="search.php">
					<input class="searchbox"  name="searchtext" placeholder="Enter an MPs name or constituency ...">
					<a clas="searchlink" href='#'>
						<input type="image" class="searchglass img-fluid" src="img/search.png" alt="Submit Form" />
					</a>
 			</form>
		</div>
	</div>
</header>
<?php include("footer.php"); ?>	