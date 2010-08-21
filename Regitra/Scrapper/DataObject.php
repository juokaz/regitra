<?php

namespace Regitra\Scrapper;

class DataObject
{
    /**
     * Data value
     *
     * @var string
     */
    private $_data = '';

    /**
     * DataObject object
     *
     * @param string $string
     */
    public function __construct($string)
    {
        if (!\is_string($string))
        {
            throw new \Regitra\Exception('Data can only be constructed from string');
        }

        $this->_data = $string;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Get XMLDocument
     *
     * @staticvar \DOMDocument $dom
     * @return \DOMDocument
     */
    public function getDomDocument()
    {
        $oldSetting = \libxml_use_internal_errors(true);
        \libxml_clear_errors();

        $dom = new \DOMDocument();
        $dom->loadHTML($this->_data);

        \libxml_clear_errors();
        \libxml_use_internal_errors($oldSetting);
        
        return $dom;
    }

    /**
     * Cast to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getData();
    }
}