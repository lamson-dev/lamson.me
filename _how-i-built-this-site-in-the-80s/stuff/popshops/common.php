<!-- big chunk of functions -->
<?php 
	
	#get database		
	$database = new PDO("mysql:dbname=imdb;host=localhost", "koojin", "3w4BEBbt8VypR");
	$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	#receive the actor's first,last name, and the database as parameters
	#return the actor id number
	#when there are ties, pick actor has the most appearance and lower-numbered ID
	function get_actorid($first, $last, $db) {
	
		$first = $db->quote($first . "%");
		$last = $db->quote($last);
		
		$actors = $db->query("SELECT a.id
						FROM actors a
						WHERE a.first_name LIKE $first 
						AND a.last_name = $last
						ORDER BY a.film_count DESC, a.id
						LIMIT 1;");
	
		foreach ($actors as $actor) {
			return $actor["id"];
		}

	}
	
	#receive the actor's id number, database, getkevinmovies (true/false) as parameters
	#return all of the actor's movies information in rows
	function get_movies($id, $db, $getkevinmovies) {
		
		$id = $db->quote($id);
		
		if ($getkevinmovies) {
			$rows = $db->query("SELECT m.name, m.year
								FROM movies m
								JOIN roles r1 ON m.id = r1.movie_id
								JOIN actors a1 ON a1.id = r1.actor_id
								JOIN roles r2 ON m.id = r2.movie_id
								JOIN actors a2 ON a2.id = r2.actor_id
								WHERE a1.first_name = 'kevin' AND a1.last_name = 'bacon'
									AND a2.id = $id
								ORDER BY m.year DESC, m.name;");
		} else {
			$rows = $db->query("SELECT m.name, m.year
								FROM movies m
								JOIN roles r ON r.movie_id = m.id
								JOIN actors a ON a.id = r.actor_id			
								WHERE a.id = $id
								ORDER BY m.year DESC, m.name;");
		}
		
		return $rows;	
	}	
	
	#take the movies information in rows, actor's name, iswithkevin as parameters
	#create a table of the actor's movies 
	function create_table($movies, $actorname, $iswithkevin){ 
		
		if ($iswithkevin) {
			$caption = "Film with $actorname and Kevin Bacon";
		} else {
			$caption = "All Films";
		}?>
		
		<div id="moviestable">
			<table>
				<caption><?= $caption ?></caption>
				
				<tr>	<th>#</th>	<th>Title</th>	<th>Year</th>	</tr>
				
				<?php		
				
					$num = 0;
					foreach ($movies as $movie) { 
						$num ++;
						$moviename = $movie["name"];
						$movieyear = $movie["year"];
				?>			  
				
				<tr>	
					<td><?= $num ?></td>	
					<td><?= $moviename ?></td>	
					<td><?= $movieyear ?></td>
	
				<?php } ?>				
			
				</tr>
			</table>
		</div>

	<?php }
	
	#take object $movies as parameter, find out if this object has zero rows
	#return false if zero rows (no movies with Kevin)
	#		true otherwise (has movies with Kevin)
	function is_with_kevin($movies) {
		$numrows = $movies->rowCount();
		if ($numrows == 0) {
			return false;
		}		
		return true;
	}
	
	
?> <!-- end big chunk of functions -->