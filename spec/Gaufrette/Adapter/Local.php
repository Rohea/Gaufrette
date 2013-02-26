<?php

namespace spec\Gaufrette\Adapter;

//hack - mock php built-in functions
require_once 'functions.php';

use PHPSpec2\ObjectBehavior;

class Local extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(sys_get_temp_dir().'/somedir');
    }

    function letgo()
    {
        global $iteratorToArray;
        $iteratorToArray = array();
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Gaufrette\Adapter\Local');
    }

    function it_should_read_file()
    {
        $this->read('filename')->shouldReturn(sys_get_temp_dir().'/somedir/filename content');
    }

    function it_should_write_file()
    {
        $this->write('filename', 'some content')->shouldReturn(12);
    }

    function it_should_rename_file()
    {
        $this->rename('filename', 'aaa/filename2')->shouldReturn(sys_get_temp_dir().'/somedir/filename to /home/somedir/aaa/filename2');
    }

    function it_should_check_if_file_exists()
    {
        $this->exists('filename')->shouldReturn(true);
        $this->exists('filename1')->shouldReturn(false);
    }

    function it_should_get_keys()
    {
        global $iteratorToArray;
        $iteratorToArray = array(sys_get_temp_dir().'/somedir/filename', sys_get_temp_dir().'/somedir/filename1', sys_get_temp_dir().'/somedir/aaa/filename');

        $this->keys()->shouldReturn(array('aaa', 'aaa/filename', 'filename', 'filename1'));
    }

    function it_should_get_mtime()
    {
        $this->mtime('filename')->shouldReturn(12345);
    }

    function it_should_delete_file()
    {
        $this->delete('filename')->shouldReturn(true);
        $this->delete('filename1')->shouldReturn(false);
    }

    function it_should_check_if_key_is_dir()
    {
        $this->isDirectory('dir')->shouldReturn(true);
        $this->isDirectory('filename')->shouldReturn(false);
    }

    function it_should_create_local_stream()
    {
        $this->createStream('filename')->shouldReturnAnInstanceOf('Gaufrette\Stream\Local');
    }

    function it_should_accepts_symbolic_links()
    {
        $this->beConstructedWith('symbolicLink');

        $this->read('filename')->shouldReturn(sys_get_temp_dir().'/somedir/filename content');
    }

    function it_should_not_allow_to_read_path_above_main_file_directory()
    {
        $this
            ->shouldThrow(new \OutOfBoundsException('The path "'.sys_get_temp_dir().'/filename" is out of the filesystem.'))
            ->duringRead('../filename');
        $this
            ->shouldThrow(new \OutOfBoundsException('The path "'.sys_get_temp_dir().'/filename" is out of the filesystem.'))
            ->duringExists('../filename');
    }

    function it_should_fail_when_directory_does_not_exists()
    {
        $this->beConstructedWith(sys_get_temp_dir().'/other');

        $this
            ->shouldThrow(new \RuntimeException('The directory "'.sys_get_temp_dir().'/other" does not exist.'))
            ->duringRead('filename');
        $this
            ->shouldThrow(new \RuntimeException('The directory "'.sys_get_temp_dir().'/other" does not exist.'))
            ->duringWrite('filename', 'some content');
        $this
            ->shouldThrow(new \RuntimeException('The directory "'.sys_get_temp_dir().'/other" does not exist.'))
            ->duringRename('filename', 'otherFilename');
        $this
            ->shouldThrow(new \RuntimeException('The directory "'.sys_get_temp_dir().'/other" does not exist.'))
            ->duringExists('filename');
        $this
            ->shouldThrow(new \RuntimeException('The directory "'.sys_get_temp_dir().'/other" does not exist.'))
            ->duringKeys();
        $this
            ->shouldThrow(new \RuntimeException('The directory "'.sys_get_temp_dir().'/other" does not exist.'))
            ->duringMtime('filename');
        $this
            ->shouldThrow(new \RuntimeException('The directory "'.sys_get_temp_dir().'/other" does not exist.'))
            ->duringDelete('filename');
        $this
            ->shouldThrow(new \RuntimeException('The directory "'.sys_get_temp_dir().'/other" does not exist.'))
            ->duringIsDirectory('filename');
        $this
            ->shouldThrow(new \RuntimeException('The directory "'.sys_get_temp_dir().'/other" does not exist.'))
            ->duringCreateStream('filename');
        $this
            ->shouldThrow(new \RuntimeException('The directory "'.sys_get_temp_dir().'/other" does not exist.'))
            ->duringChecksum('filename');
    }

    function it_should_create_directory_when_does_not_exists()
    {
        $this->beConstructedWith(sys_get_temp_dir().'/other', true);

        $this->read('filename')->shouldReturn(sys_get_temp_dir().'/other/filename content');
    }

    function it_should_be_able_to_calculate_checksum()
    {
        $this->shouldHaveType('Gaufrette\ChecksumCalculator');
    }
}
