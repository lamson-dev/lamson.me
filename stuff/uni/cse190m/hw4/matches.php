<?php include("top.html"); ?>

<!--  	Son Nguyen
		CSE 190 M
		Homework #4

		TA: Rishi Goutam		
		
		Description: php file of "NerdLuv" online dating site
		
		the code in this file create a matches page for returning user
		require user to input his/her name
		and submit user information (as GET) to matches-submit.php
		
-->

<form action="matches-submit.php">

	<fieldset>
		<legend>Returning User:</legend>
		
			<div>

				<label for="name"><strong>Name:</strong></label>
				<input type="text" name="name" id="name" size="16" autofocus required />
				
			</div>
			
			<div>
				<input type="submit" value="View My Matches" />
			</div>
			
	</fieldset>
	
</form>


<?php include("bottom.html"); ?>