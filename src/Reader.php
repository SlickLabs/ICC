<?php

namespace ICC;

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
     * @return bool|File|null|string
     */
    public function read(File $file, FormatterInterface $formatter = null)
    {
        if ($formatter) {
            $this->setFormatter($formatter);
        }

        $fileContent = $file->getContents();

        if ($this->formatter) {
            $fileContent = $this->formatter->format($fileContent);
        }

        return $fileContent;
    }

    /**
     * @param File $file
     * @param FormatterInterface|null $formatter
     * @return mixed
     */
    public function getHead(File $file, FormatterInterface $formatter = null)
    {
        if ($formatter) {
            $this->setFormatter($formatter);
        }

        $fileContent = $file->getContents();

        if ($this->formatter) {
            $fileContent = $this->formatter->toArray($fileContent);
        }

        return $fileContent[0];
    }
}
