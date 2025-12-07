<?php
final class rate_exchange
{

    private $_rate = array();
    private $_currency = ["euro","yuan","lira","rublo","dolar"];

    public function __construct()
    {
          $this->loadRate();  
    }
    private function file_get_contents_ssl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3000); // 3 sec.
        curl_setopt($ch, CURLOPT_TIMEOUT, 10000); // 10 sec.
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
    private function loadRate()
    {
        $site = $this->file_get_contents_ssl("https://www.bcv.org.ve/");
  
        $dom = new DOMDocument();

        @$dom->loadHTML($site);

        $divs = $dom->getElementsByTagName('div');
        $values = [];
        foreach( $divs as $div ){
            
            if( $div->getAttribute( 'class' ) === 'col-sm-6 col-xs-6 centrado' ){
                $values[] = $div->nodeValue;
            }
        
        }
        $i=0;
        foreach($this->_currency as $val)
        {
            $this->_rate[$val] = $values[$i]; 
            $i++;
        }

    }
    public function getRate($value)
    {
        $rate=0;
        switch($value)
        {
            case 'euro':
                $rate = $this->_rate['euro'];
                break;
            case 'yuan':
                $rate = $this->_rate['yuan'];
                break;
            case 'lira':
                $rate = $this->_rate['lira'];
                break;
            case 'rublo':
                $rate = $this->_rate['rublo'];
                break; 
            case 'dolar':
                $rate = $this->_rate['dolar'];
                break;           
        }

        return $rate;
    }





}



