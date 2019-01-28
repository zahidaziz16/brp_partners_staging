<?php

	# execute shell command
	$git_status = shell_exec("git fetch origin && git reset --hard origin/master 2>&1");

	# intializing datetime 
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$current_time = date('Y-m-d H:i:s');
	$log_status = $current_time . " -- $git_status \n";

	# make pull log
	$current_dir = ''.dirname(__DIR__);
	$pull_status = file_put_contents($current_dir.'/cron/git_pull.log', $log_status, FILE_APPEND);

?>