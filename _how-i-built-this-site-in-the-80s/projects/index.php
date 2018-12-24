<!DOCTYPE html>
<html lang="en">
<head>
	<title>Son Nguyen's Projects</title>
	
	<?php include "../includes/header.php" ?>

</head>

<body>
	<div id="royal-wrapper" style="width: 960px;">
		<div id="wrapper" style="width: 930px;">
		
			<div id="banner"></div>
			
			<div id="header">
				<div id="site-title">Projects</div>
				
				<?php include "../includes/nav.php" ?>
	
				<div class="clear"></div>
			</div>
			
			
			<div id="main">
			
				<div id="content">
				
					<?php
						
						$info = file_get_contents("info.txt");
						$projects = explode("\n\n", $info);
						
						foreach ($projects as $project) {
							list($path, $target, $name, $description) = explode("\n", $project); ?>
							
						<h2><a href="<?= $path ?>" target="<?= $target ?>"><?= $name ?></a></h2>
						<p><?= $description ?> </p>	
							
							
					<?php } # close loop ?> 
	
				</div> <!-- end content -->
			
			</div> <!-- end main div -->
			
			<div id="footer">
				<?php include "../includes/footer.php" ?> 
			</div>
		</div> <!-- id=wrapper -->
	</div> <!-- id=royal-wrapper -->
</body>
</html>
