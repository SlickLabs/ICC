<?php

namespace ICC;

interface ReaderInterface
{
    /**
     * @param File $file
     * @param FormatterInterface|null $formatter
     * @return mixed
     */
    public function read(File $file, FormatterInterface $formatter = null);

    /**
     * @param File $file
     * @param FormatterInterface|null $formatter
     * @return mixed
     */
    public function getHead(File $file, FormatterInterface $formatter = null);

    /**
     * @param File $file
     * @param FormatterInterface|null $formatter
     * @return string
     */
    public function getHeadAsString(File $file, FormatterInterface $formatter = null);
}
