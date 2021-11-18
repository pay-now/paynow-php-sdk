<?php

namespace Paynow\Response\DataProcessing;

use Paynow\Model\DataProcessing\Notice;

class Notices
{
    /**
     * @var Notice[]
     */
    private $list;

    public function __construct($body)
    {
        if (! empty($body)) {
            foreach ($body as $item) {
                $this->list[] = new Notice(
                    $item->title,
                    $item->content,
                    $item->locale
                );
            }
        }
    }

    /**
     * @return array|Notice[]
     */
    public function getAll()
    {
        return $this->list;
    }
}
