<?php

require "filelock-master\src\FileLock.php";

$fname = 'lock-test.txt';

//create file
$handle = fopen($fname, 'w') or die('ERROR[0]: Unable to open/create file "'.$fname.'"!');
$txt = date('d.m.Y H:i:s');
fwrite($handle, $txt);
fclose($handle); 
echo 'test file "'.$fname.'" created ['.date('d.m.Y H:i:s').']: '.$handle.'-'.filesize($fname).'<br/>'; 

// create lock object
$lock = new \Dorantor\FileLock($fname);

echo 'try to lock file<br/>';
if (!$lock->acquire()) { //fopen('a')
    // failed with lock
	echo 'ERROR[1]: file "'.$fname.'" not locked!';
    exit();
} else {
	echo 'OK[0]: file locked!<br/>';
}

//try to  read write
$handle = fopen($fname, 'r+');

if ($handle === false) {
	echo 'ERROR[2]: file "'.$fname.'" can not be opened!';
	exit();
} else {
	echo 'try to read<br/>';
    if (($buffer = fgets($handle, 4096)) !== false) {
		echo 'ERROR[3]: file "'.$fname.'"  not locked! File content: '.$buffer;
		exit();        
    } else {
		echo 'OK[1]: file locked! Reading fail.<br/>';
	}
	
    if (!feof($handle)) {
        echo 'ERROR[4]: fgets() fail<br/>';
		$lock->release(); //unlock file
		exit();
    }
	
	echo 'try to write<br/>';	
	$fwrite_res = fwrite($handle, 'test');
	if ($fwrite === false) {
		echo 'OK[2]: file locked! Writing fail.<br/>';
	} else {
		if ($fwrite_res != 0) { 
			echo 'ERROR[5]: writed '.$fwrite_res.' bytes.<br/>';
		} else {
			echo 'OK[3]: file locked! Writing fail.<br/>';		
		}
	}
    fclose($handle);
}

echo 'try to unlock file<br/>';
if (!$lock->release()) { //unlock file -> fclose()
    // failed with lock
	echo 'ERROR[6]: file "'.$fname.'" still locked!';
    exit();
} else {
	echo 'OK[4]: file unlocked!<br/>';
}


$handle = fopen($fname, 'r+');	

if ($handle === false) {
	echo 'ERROR[7]: file "'.$fname.'" can not be opened!';
	exit();
} else {
	
	echo 'try to read<br/>';
    if (($buffer = fgets($handle, 4096)) !== false) {
		echo 'OK[5]: file "'.$fname.'" not locked! File content: '.$buffer.'<br/>';
    } else {
		echo 'ERROR[8]: file locked! Reading fail.<br/>';
		exit();
	}
	
    if (!feof($handle)) {
        echo 'ERROR[9]: fgets() fail<br/>';
		exit();
    }
	
	echo 'try to write<br/>';	
	$fwrite_res = fwrite($handle, 'test');
	if ($fwrite === false) {
		echo 'ERROR[10]: file locked! Writing fail.<br/>';
	} else {
		if ($fwrite_res != 0) { 
			echo 'OK[6]: writed '.$fwrite_res.' bytes.<br/>';
		} else {
			echo 'ERROR[11]: file locked! Writing fail.<br/>';		
		}
	}
    
	fclose($handle);
	
	echo 'done';
}
?> 