<?php

function addproblem($title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $test_input, $test_output, $hint, $source, $spj,$OJ_DATA) {
	$title=mysql_real_escape_string($title);
	$time_limit=mysql_real_escape_string($time_limit);
	$memory_limit=mysql_real_escape_string($memory_limit);
	$description=mysql_real_escape_string($description);
	$input=mysql_real_escape_string($input);
	$output=mysql_real_escape_string($output);
	$sample_input=($sample_input);
	$sample_output=($sample_output);
	$test_input=($test_input);
	$test_output=($test_output);
	$hint=mysql_real_escape_string($hint);
	$source=mysql_real_escape_string($source);
	$spj=($spj);
	
	$sql = "INSERT into `problem` (`title`,`time_limit`,`memory_limit`,
	`description`,`input`,`output`,`sample_input`,`sample_output`,`hint`,`source`,`spj`,`in_date`,`defunct`)
	VALUES('$title','$time_limit','$memory_limit','$description','$input','$output',
			'$sample_input','$sample_output','$hint','$source','$spj',NOW(),'Y')";
	//echo $sql;
	@mysql_query ( $sql ) or die ( mysql_error () );
	$pid = mysql_insert_id ();
	echo "<br>Add $pid  ";
	if (intval ( $_POST ['contest_id'] ) > 999) {
		$sql = "select count(*) FROM `contest_problem` WHERE `contest_id`=" . strval ( intval ( $_POST ['contest_id'] ) );
		$result = @mysql_query ( $sql ) or die ( mysql_error () );
		$row = mysql_fetch_row ( $result );
		$cid = $_POST ['contest_id'];
		$num = $row [0];
		echo "Num=" . $num . ":";
		$sql = "INSERT INTO `contest_problem` (`problem_id`,`contest_id`,`num`) VALUES('$pid','$cid','$num')";
		mysql_free_result ( $result );
		mysql_query ( $sql );
	}
	$basedir = "$OJ_DATA/$pid";
	echo "Please add more data file in $basedir";
	
	mkdir ( $basedir );
	$fp = fopen ( $basedir . "/sample.in", "w" );
	fputs ( $fp, ereg_replace ( "\r\n", "\n", $sample_input ) );
	fclose ( $fp );
	
	$fp = fopen ( $basedir . "/sample.out", "w" );
	fputs ( $fp, ereg_replace ( "\r\n", "\n", $sample_output ) );
	fclose ( $fp );
	
	if ( $test_output ) {
		$fp = fopen ( $basedir . "/test.in", "w" );
		fputs ( $fp, ereg_replace ( "\r\n", "\n", $test_input ) );
		fclose ( $fp );
		
		$fp = fopen ( $basedir . "/test.out", "w" );
		fputs ( $fp, ereg_replace ( "\r\n", "\n", $test_output ) );
		fclose ( $fp );
	
	}
	return $pid;
}
?>
