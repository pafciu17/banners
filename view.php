<?php
	require_once('init.php');
	$banner = new Banner();
	$bannerContent = (!empty($_GET['slotId']) ? $banner->getContent($_GET['slotId']) : 'no such slot');
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
	 	echo $bannerContent;
	 ?>
</body>
</html>