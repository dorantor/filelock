<?php

/*
 * This file is part of the dorantor/filelock package.
 */

namespace Dorantor;

/**
 * Simple class providing OOP-interface to file locks.
 *
 * @package Dorantor
 */
class FileLock
{
    /**
     * Whether or not we currently have a lock.
     *
     * @var boolean
     */
    protected $locked;

    /**
     * The file being wrapped by this lock.
     *
     * @var resource
     */
    protected $resource;

    /**
     * The path to the resource to lock.
     *
     * @var string
     */
    protected $filePath;

    /**
     * Constructor.
     *
     * @param  string $filePath path to lockfile
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->locked   = false;
        $this->resource = null;
    }

    /**
     * Destructor.
     *
     * Release lock at the last opportunity.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->release();
    }

    /**
     * Acquire a lock on the resource.
     *
     * @return boolean
     */
    public function acquire()
    {
        if (!$this->locked) {
            $this->resource = @fopen($this->filePath, 'a');

            if (!$this->resource || !flock($this->resource, LOCK_EX | LOCK_NB)) {
                return false;
            } else {
                $this->locked = true;
            }
        }

        return true;
    }

    /**
     * Release the lock on the resource, if we have one.
     *
     * @return bool
     */
    public function release()
    {
        if ($this->locked) {
            flock($this->resource, LOCK_UN);
            fclose($this->resource);

            $this->resource = null;
            $this->locked = false;

            return true;
        }

        return false;
    }
}
