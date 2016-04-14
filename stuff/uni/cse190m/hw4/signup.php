<?php include("top.html"); ?>

<!--  	Son Nguyen
		CSE 190 M
		Homework #4
		
		TA: Rishi Goutam		
		
		Description: php file of "NerdLuv" online dating site
		
		the code in this file create a sign up page for new user
		require new user to input their information
		and submit user information (as POST) to signup-submit.php
-->

<form action="signup-submit.php" method="post">

	<fieldset>
		<legend>New User Signup:</legend>
		
			<div>
			
<!-- need to assign class="left" for label ?????? -->

				<label for="name"><strong>Name:</strong></label>
				<input type="text" name="name" id="name" size="16" autofocus required />
			</div>
			
			<div>
				<label><strong>Gender:</strong></label>
				<label><input type="radio" name="gender" value="M"/> Male</label>
				<label><input type="radio" name="gender" value="F" checked="checked" /> Female</label>
			</div>
			
			<div>
				<label for="age"><strong>Age:</strong></label>
				<input type="text" name="age" id="age" size="6" maxlength="2" required />
			</div>
			
			<div>
				<label for="type"><strong>Personality type:</strong></label>
				<input type="text" name="type" id="type" size="6" maxlength="4" required />
				(<a href="http://www.humanmetrics.com/cgi-win/JTypes2.asp">Don't know your type?</a>)
			</div>
			
			<div>
				<label for="os"><strong>Favorite OS:</strong></label>
				<select name="os" id="os">
			
					<option selected="selected">Windows</option>
					<option>Mac OS X</option>
					<option>Linux</option>
				
				</select>
			</div>		
			
			<div>
				<label for="seekingage"><strong>Seeking age:</strong></label>
				<input type="text" name="seekfrom" id="seekingage" size="6" maxlength="2" placeholder="min" required /> to 
				<input type="text" name="seekto" size="6" maxlength="2" placeholder="max" required />
			</div>
			
			<div>
				<input type="submit" value="Sign Up" />
			</div>
			
	</fieldset>
	
</form>
	

<?php include("bottom.html"); ?>
