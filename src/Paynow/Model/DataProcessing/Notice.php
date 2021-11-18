<?php

namespace Paynow\Model\DataProcessing;

/**
 * Class Notice
 *
 * @package Paynow\Model\DataProcessing
 */
class Notice
{
    /**
     * @var string
     */
    private $title;

    private $content;

    private $locale;

    public function __construct($title, $content, $locale)
    {
        $this->title = $title;
        $this->content = $content;
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
