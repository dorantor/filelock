<?php

use PHPUnit\Framework\TestCase;

class FileLockTest extends TestCase {

	private $fname;
	
    protected function setUp() 
	{
        parent::setUp();
        $this->fname = 'tests/lock-test.txt';
    }

    public function testInvalidLockId() 
	{
        //try to catch exception from Class if the file name is incorrect
		$f = new \Dorantor\FileLock('');
		self::assertFalse($f->acquire());
    }
	
	public function testLock()
    {
        $lock = new \Dorantor\FileLock($this->fname);

        //try to lock file
		self::assertTrue($lock->acquire(),'ERROR[0]: Result of function acquire() not correct!'. PHP_EOL);
        return $lock;
		
    }

    /**
     * @depends     testLock
     * @param       Dorantor\FileLock       $lock
     */
    public function testLockLockedFile()
    {
		//try to lock locked file - wait false result
		$f = new \Dorantor\FileLock($this->fname);
		self::assertFalse($f->acquire());
    }	

    /**
     * @depends     testLock
     * @param       Dorantor\FileLock       $lock
     */
    public function testUnlock(Dorantor\FileLock $lock)
    {
		//try to unlock file
		self::assertTrue($lock->release(),'ERROR[2]: Result of function release() not correct!'. PHP_EOL);
    }
	
	public function testFileUnLocked()
    {
		//try to read/write file after UnLocking
		$handle = fopen($this->fname, 'a+');
		self::assertTrue(($handle !== false),'ERROR[3]: file "'.$this->fname.'" can not be opened!'. PHP_EOL);		

		//try to write
		$fwrite_res = fwrite($handle, ''.date('d.m.Y H:i:s'). PHP_EOL);
		self::assertNotEquals(0, $fwrite_res,'ERROR[4]: can not write to file '.$this->fname.''. PHP_EOL);
		
		fclose($handle);		
		
		$read_res = file_get_contents($this->fname, FALSE, NULL, 0, 1024);
		self::assertNotEquals(0, strlen($read_res),'ERROR[5]: file still locked'. PHP_EOL);
	}
}