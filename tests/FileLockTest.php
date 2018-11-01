<?php

use PHPUnit\Framework\TestCase;

$fname = 'tests/lock-test.txt';

//create file
$handle = fopen($fname, 'w') or die('ERROR[0]: Unable to open/create file "'.$fname.'"!');
$txt = date('d.m.Y H:i:s');
fwrite($handle, $txt);
fclose($handle); 
echo PHP_EOL .'Test file "'.$fname.'" created ['.date('d.m.Y H:i:s').']. FileSize: '.filesize($fname).' bytes.'. PHP_EOL . PHP_EOL; 


class FileLockTest extends TestCase {

	public function testCanBeLocked() {

		global $fname;
		//create lock object
		$lock = new \Dorantor\FileLock($fname);
		
		//try to lock file
        $this->assertEquals(true, $lock->acquire(),'ERROR[1]: Result of function acquire() not correct!'. PHP_EOL);
/*		
		//try to  read write
		$handle = fopen($fname, 'r+');
		$this->assertTrue(($handle != false),'ERROR[2]: file "'.$fname.'" can not be opened!'. PHP_EOL);
		
		//try to read
		//$buffer = fgets($handle, 4096);
		//$this->assertFalse($buffer,'ERROR[3]: file "'.$fname.'"  not locked! File content: '.$buffer. PHP_EOL);
		
		//try to write
		$fwrite_res = fwrite($handle, 'test');
		$this->assertEquals(0, $fwrite_res,'ERROR[5]: writed '.$fwrite_res.' bytes.'. PHP_EOL);
		
		fclose($handle);
*/
		//try to unlock file
		$this->assertEquals(true, $lock->release(),'ERROR[6]: Result of function release() not correct!'. PHP_EOL);
/*		
		$handle = fopen($fname, 'r+');	

		//try to write
		$fwrite_res = (fwrite($handle, 'test') != 0);
		$this->assertTrue($fwrite_res,'ERROR[7]: File "'.$fname.'" locked! Writing fail.'. PHP_EOL);
		   
		fclose($handle);
*/
	}
}