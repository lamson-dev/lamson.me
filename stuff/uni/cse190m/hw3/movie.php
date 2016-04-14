<!DOCTYPE html>

<!--  	Son Nguyen
		CSE 190 M
		Homework #3

		TA: Rishi Goutam		
		
		Description: 
		
		php file of "Rancid Tomatoes" movies review page
		the code in this file will generate reviews for variety of movies
		by getting information from files in the respective movies folders
		
		when user sends in parameter (movie title), it will show the 
		movie review page of this movie title
		
		default page with no parameter is Ratatouille (mymovie) movie.
		
		***Extra: "Ratatouille" is added for Creative Aspect with parameter "ratatouille"
-->

<html>
	<head>
		<title>Rancid Tomatoes</title>

		<meta charset="utf-8" />
		<link href="movie.css" type="text/css" rel="stylesheet" />
		
		<link href="http://www.cs.washington.edu/education/courses/cse190m/12sp/homework/3/fresh.gif" 
			type="image/gif" rel="shortcut icon" />
	</head>
	
	
	<?php
		
		#get parameter from link
		#if there is no parameter, default page is TMNT	
		if (isset($_GET["film"])) {
			$movie = $_GET["film"];
		} else {
			$movie = "ratatouille";
		}		
			
		#get movie infomation
		$info = file("$movie/info.txt", FILE_IGNORE_NEW_LINES);
		$title = $info[0];
		$year = $info[1];
		$ratingOverall = $info[2];
		
		#OR:
		#$info = implode(" ", $info);
		#list($title, $year, $ratingOverall) = explode(" ",$info);
	
		#choosing the ratingbig image (fresh/rotten)										
		if ($ratingOverall >= 60) {
			$ratingBig = "fresh";	
		} else {
			$ratingBig = "rotten";
		} 
				
	?>

	<body>
	
		
		
		<div id="banner">
			<img src="http://www.cs.washington.edu/education/courses/cse190m/12sp/homework/3/banner.png" alt="Rancid Tomatoes" />
		</div>

		<h1 id="mainheading"><?= $title ?> (<?= $year ?>)</h1>
		
		<div id="maincontent">
		
			<div id="overview"> 
			
				<div>
					<img src="<?= $movie ?>/overview.png" 
						alt="general overview" />
				</div>
		
				<dl>
					<?php
						
						#get movie overview terms and definitions
						$overview = file("$movie/overview.txt");
						
						#loop through every line in overview.text file												
						foreach ($overview as $item) {
							list($term,$def) = explode(":", $item);	?>
					
					<dt><?= $term ?></dt>
					<dd><?= $def ?></dd>
					
					
					<?php } ?>
							
				</dl>
			
			</div> <!-- end overview -->
		
			
			<div id="leftsection">
			
				<div id="ratingbar">
					
					<img src="http://www.cs.washington.edu/education/courses/cse190m/12sp/homework/3/<?= $ratingBig ?>big.png" 
					alt="<?= $ratingBig ?>" />
					<span><?= $ratingOverall ?>%</span>
					
				</div> <!-- close rating bar -->

				<?php					
				

					$reviewFiles = glob("$movie/review*.txt"); 	#get all the review*.txt file directory
					
					$numReviews = count($reviewFiles); 			#total number or reviews
					
					#loop through every review/review file
					for ($i = 0; $i < $numReviews; $i++) {
					
						$fileDirectory = $reviewFiles[$i];
						$reviewInfo = file("$fileDirectory", FILE_IGNORE_NEW_LINES);
						
						list($quote, $rating, $critic, $publication) = $reviewInfo;  #break text in review file

						$rating = strtolower($rating); 		#lowercase string (ex: FRESH -> fresh…)
						
						# open <div class="column"> at appropriate position
						# so that the left column will take an extra reviews
						# if numberReviews is odd
						if ($i == 0 || $i == (int) (($numReviews+1)/2)) {  ?>
															
				<div class="column">			
				
						<?php } #close if statement ?> 
						
						<p class="review">
							<img src="http://www.cs.washington.edu/education/courses/cse190m/12sp/homework/3/<?= $rating ?>.gif" 												alt="<?= $rating ?>" />
							<q><?= $quote ?></q>
	
						</p>
						
						<p class="critic">
							<img src="http://www.cs.washington.edu/education/courses/cse190m/12sp/homework/3/critic.gif" alt="Critic" />
							<?= $critic ?> <br />
							<span class="publication"><?= $publication ?></span>
						</p>
				
						<?php 
						
						# close </div> of class column at appropriate position									
						if ($i == (int) (($numReviews-1)/2) || $i == ($numReviews-1)) { ?>		
				</div> 
						<?php  } # close if statement

					}	# close loop
				?>			
				
			</div> <!-- end of left section -->
			
			
			<p id="page">(1-<?= $numReviews ?>) of <?= $numReviews ?></p>
			
		</div> <!-- end maincontent -->

		<div id="validation">
			<a href="https://webster.cs.washington.edu/validate-html.php">
				<img src="http://webster.cs.washington.edu/w3c-html.png" alt="Valid HTML5" /></a> <br />
			<a href="https://webster.cs.washington.edu/validate-css.php">
				<img src="http://webster.cs.washington.edu/w3c-css.png" alt="Valid CSS" /></a>
		</div> <!-- end validation -->
		
	</body>
</html>
