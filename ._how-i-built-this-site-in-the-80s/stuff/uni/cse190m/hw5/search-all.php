<?php include("top.html") ?>

<!--  	Son Nguyen
		CSE 190 M
		Homework #5

		TA: Rishi Goutam		
		
		Description: php file of the One Degree of Kevin Bacon page
		 
		this page get user input (name of actor) from submitting query
		then base from user input, look into the imdb database
		and show search results for all films by this actor
		
		common.php file contains all functions of the page
-->

<?php include("common.php") ?>

<!-- main part starts here -->

		
	<?php
	 	
	 	#assign user's input into variables
	 	$firstname = $_GET["firstname"];
		$lastname = $_GET["lastname"];
		$actorname = $firstname . " " . $lastname;
	 	
	 	#get actor id from database
		$actorid = get_actorid($firstname, $lastname, $database);
		
		#check if the actor information exists in database
		if (!isset($actorid)) { ?>
		
			<p>Actor <?= $actorname ?> not found.</p>			
			
			<?php	
		} else { ?>
		
			<h1>Results for <?= $actorname ?></h1>	
			
			<?php			
				#the 3rd parameter take "true" if want to get movies with Kevin Bacon
				#take "false" if don't want to
				$actormovies = get_movies($actorid, $database, false);
				create_table($actormovies, $actorname, false);							
		} ?>			
	
		
<!-- end main part -->

   
<?php include("bottom.html") ?>