<?php

namespace Regitra\Scrapper;

class Scrapper
{
    /**
     * Initialisation url
     *
     * @var string
     */
    protected $_initUrl = '';

    /**
     * How often init url must be fetched
     *
     * @var int
     */
    protected $_initTimeout = 1;

    /**
     * Cookies
     *
     * @var string
     */
    protected $_cookies = '';

    /**
     * Set init Url
     *
     * @param string $url
     */
    public function setInitUrl($url)
    {
        $this->_initUrl = $url;
    }

    /**
     * Set cookies path
     *
     * @param string $path
     */
    public function setCookiesPath($path)
    {
        if (!\file_exists($path))
        {
            throw new \Regitra\Exception('Cookie path does not exist');
        }

        $this->_cookies = $path;
    }

    /**
     * Get data
     *
     * @param string $url
     * @param array $data
     * @return DataObject
     */
    public function getData($url, $data = array())
    {
        static $last;

        // only init once in a while
        if ($last + $this->_initTimeout * 60 < time())
        {
            try
            {
                $this->getHtml($this->_initUrl);
            }
            catch (\Regitra\Exception $e)
            {
                throw new \Regitra\Exception('Initialisation page can not be loaded');
            }
            
            $last = time();
        }

        try
        {
            $html = $this->getHtml($url, $data);
        }
        catch (\Regitra\Exception $e)
        {
            throw new \Regitra\Exception('Document can not be loaded');
        }

        return new DataObject($html);
    }

    /**
     * Get html from url
     *
     * @param string $url
     * @return string
     */
    protected function getHtml($url, $post = false)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');

        $header = array();
        $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Pragma: "; // browsers keep this blank.

        $cookies = $this->_cookies;

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSLVERSION,3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_REFERER, $url);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies);

        if (is_array($post))
        {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getPostFields($post));
        }

        $html = curl_exec($curl); // execute the curl command
        $error = curl_errno($curl);

        if ($error)
        {
            throw new \Regitra\Exception('URL ' . $url . ' can not be loeaded');
        }

        curl_close($curl); // close the connection

        return $html;
    }

    /**
     *
     * @param array $data
     * @return string
     */
    private function getPostFields ($data) {

        $return = array();

        foreach ($data as $key => $field) {
            $return[] = $key . '=' . urlencode($field);
        }

        return implode('&', $return);
    }
}