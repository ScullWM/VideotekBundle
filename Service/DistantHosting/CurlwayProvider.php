<?php

namespace Swm\VideotekBundle\Service\DistantHosting;

class CurlwayProvider
{
    public function getLocal($url, $filename, $path)
    {
        $ch = curl_init($url);
        $fp = fopen($path.'/'.$filename, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        return new splFileInfo($path.'/'.$filename);
    }
}