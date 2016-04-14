<?php include("top.html") ?>

<!--  	Son Nguyen
		CSE 190 M
		Homework #5

		TA: Rishi Goutam		
		
		Description: php file of the One Degree of Kevin Bacon page
		 
		this page get user input (name of actor) from submitting query
		then base from user input, look into the imdb database
		and show search results for all films by this actor AND Kevin Bacon
		
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
		} else { 
			#get actor movies that has Kevin in there (may not have any)
			$actormovies = get_movies($actorid, $database, true);
			
			#check if the actor has movies with Kevin
			$iswithkevin = is_with_kevin($actormovies);
					
			if ($iswithkevin) { ?>
			
				<h1>Results for <?= $actorname ?></h1>	
				
				<?php
				create_table($actormovies, $actorname, $iswithkevin);
		
			} else { ?>
			
				<p><?= $actorname ?> wasn't in any films with Kevin Bacon.</p>
				
				<?php
			}
		} ?>			
	
		
<!-- end main part -->

   
<?php include("bottom.html") ?>