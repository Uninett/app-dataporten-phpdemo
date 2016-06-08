<?php
	
	$output = shell_exec("cd docker_composers && docker ps -a 2>&1");

	echo "$output ".PHP_EOL;