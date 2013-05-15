<?php

	if (! $message = unserialize($message))
		$message['trace'] = $e->getMessage(); ?>
<html>
	<head>
		<title><?php print $message['title']; ?></title>
		
		<style type="text/css">
			#core_error {
				background: #666;
				padding:5px;
				font-size: 1em;
				font-family:sans-serif;
				text-align:center;
			}
			
			#core_error h1 {
				margin:0;
				padding:16px;
				font-size:16px;
				font-weight:bold;
				background:#CCC;
				color:#333;
			}
		
			#core_error h1 a {
				color:#333;
				font-weight:bold;
			}
		
			#core_error h1 a:hover {
				color:#911;
			}
		</style>
	</head>
	<body>
		<div id="core_error">
			<h1><span class="type"><?php echo $type ?> [ <?php echo ($code)?$code.' at ':'',($line)?"line - ".$line.': ':'',$file ?> ]:</span> <span class="message"><?php echo $message['trace'] ?></span></h1>
		</div>
	</body>
</html>