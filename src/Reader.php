<?php

namespace ICC;

use ICC\Record\AbstractRecord;
use ICC\Record\ConditionHead;

/**
 * Class Reader
 * @package ICC
 */
class Reader extends AbstractReader
{
    const SETTING_FORMATTER = 'formatter';

    /**
     * @var File
     */
    protected $file;

    /**
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * Reader constructor.
     * @param File $file
     * @param array $settings
     */
    public function __construct(array $settings = [])
    {
        $this->setSettings($settings);
    }

    /**
     * @param array $settings
     */
    public function setSettings(array $settings)
    {
        foreach ($settings as $key => $setting) {
            switch ($key) {
                case self::SETTING_FORMATTER:
                    $this->setFormatter($setting);
                    break;
            }
        }
    }

    /**
     * @param FormatterInterface $formatter
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @param File $file
     * @param FormatterInterface|null $formatter
     * @return AbstractRecord[]
     * @throws Exception\FileException
     */
    public function read(File $file, FormatterInterface $formatter = null)
    {
        if ($formatter) {
            $this->setFormatter($formatter);
        }

        $fileContent = $file->getContents();

        if ($this->formatter) {
            $fileContent = $this->formatter->setFirstLineOnly(false)->format($file->getId(), $fileContent);
        }

        return $fileContent;
    }

    /**
     * @param File $file
     * @param FormatterInterface|null $formatter
     * @return mixed
     * @throws Exception\FileException
     */
    public function getHead(File $file, FormatterInterface $formatter = null)
    {
        if ($formatter) {
            $this->setFormatter($formatter);
        }

        $fileContent = $file->getContents();

        if ($this->formatter) {
            $head = $this->formatter->setFirstLineOnly(true)->toArray($fileContent, ConditionHead::class);
        }

        return $head;
    }

    /**
     * @param File $file
     * @param FormatterInterface|null $formatter
     * @return string
     * @throws Exception\FileException
     */
    public function getHeadAsString(File $file, FormatterInterface $formatter = null)
    {
        if ($formatter) {
            $this->setFormatter($formatter);
        }

        $fileContent = $file->getContents();

        if ($this->formatter) {
            $head = $this->formatter->setFirstLineOnly(true)->toString($fileContent, ConditionHead::class);
        }

        return $head;
    }
}
