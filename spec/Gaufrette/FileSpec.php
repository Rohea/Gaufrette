<?php

namespace spec\Gaufrette;

use PhpSpec\ObjectBehavior;

class FileSpec extends ObjectBehavior
{
    /**
     * @param \Gaufrette\Filesystem $filesystem
     */
    function let($filesystem)
    {
        $this->beConstructedWith('filename', $filesystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Gaufrette\File');
    }

    function it_gives_access_to_key()
    {
        $this->getKey()->shouldReturn('filename');
    }

    function it_gets_content()
    {
        $this->setContent("Some content");
        $this->getContent()->shouldReturn('Some content');
    }

    /**
     * @param \Gaufrette\Filesystem $filesystem
     */
    function it_gets_mtime($filesystem)
    {
        $this->setMTime(1358797854);

        $this->getMtime()->shouldReturn(1358797854);
    }

    /**
     * @param \Gaufrette\Filesystem $filesystem
     * @param \spec\Gaufrette\MetadataAdapter $adapter
     */
    function it_pass_metadata_when_write_content($filesystem, $adapter)
    {
        $metadata = array('id' => '123');
        $filesystem->write('filename', 'some content', true, $metadata)->willReturn(12);
        $filesystem->getAdapter()->willReturn($adapter);

        $this->setContent('some content', $metadata);
    }

    function it_sets_content_of_file()
    {
        $this->setContent('some content');

        $this->getContent()->shouldReturn('some content');
    }

    function it_sets_key_as_name_by_default()
    {
        $this->getName()->shouldReturn('filename');
    }

    function it_sets_name()
    {
        $this->setName('name');
        $this->getName()->shouldReturn('name');
    }

    function it_sets_size_for_new_file()
    {
        $this->setContent('some content');
        $this->getSize()->shouldReturn(12);
    }

    function it_calculates_size_from_content()
    {
        $this->setContent('some content');

        $this->getSize()->shouldReturn(12);
    }

    /**
     * @param \Gaufrette\Filesystem $filesystem
     */
    function it_allows_to_set_size($filesystem)
    {
        $filesystem->read('filename')->shouldNotBeCalled();

        $this->setSize(21);
        $this->getSize()->shouldReturn(21);
    }

    /**
     * @param \Gaufrette\Filesystem $filesystem
     */
    function it_gets_zero_size_when_file_not_found($filesystem)
    {
        $filesystem->read('filename')->willThrow(new \Gaufrette\Exception\FileNotFound('filename'));

        $this->getSize()->shouldReturn(0);
    }

}

interface MetadataAdapter extends \Gaufrette\Adapter,
                                  \Gaufrette\MetadataSupporter
{}
