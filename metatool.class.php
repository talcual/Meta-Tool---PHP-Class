<?php

class metatool{
    public $source;

    public function metatool(){

    }

    // Get HTTP Protocol Load Solution
    public function setSource($uri){
        if(substr($uri, 0,5) == 'https'){
          $this->source = self::getSslPage($uri);
        }else{
          $this->source = file_get_contents($uri);
        }
       return $this;
    }
    
    // Get SSL Page, HTTPS Protocol Load Solution
    public function getSslPage($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }    

    // Get Normal Metatags
    public function getMeta(){
        $regex = '/<[\s]*meta[\s]*(name)="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si';
        $tags = self::getTag('meta',$regex);
        foreach ($tags[1] as $key => $value) {
            $metatags[$value][$tags[2][$key]] = $tags[3][$key];
        }

        return $metatags;
    }

    // Get OpenGraph Metatags
    public function getOG(){
        $regex = '/<[\s]*meta[\s]*(property)="?' . '(og[^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si';
        $tags = self::getTag('meta',$regex);
        foreach ($tags[1] as $key => $value) {
            $metatags[$value][$tags[2][$key]] = $tags[3][$key];
        }

        return $metatags;
    }

    // Get APPLinks Metatags
    public function getAL(){
        $regex = '/<[\s]*meta[\s]*(property)="?' . '(al[^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si';
        $tags = self::getTag('meta',$regex);

        if(count($tags) > 0){
            foreach ($tags[1] as $key => $value) {
                $metatags[$value][$tags[2][$key]] = $tags[3][$key];
            }
            return $metatags;
        }else{
            return 'APPLinks Not found';
        }

       
    }

    // Get Title Tag
    public function getTitle(){
        $tag = 'title';
        $regex = '/<'.$tag.'>(.*)<\/'.$tag.'>/';
        return self::getTag($tag,$regex);
    }
 
    // Metoth for execute a Regex for Metatags
    public function getTag($tag, $regex){
            if(preg_match_all($regex,$this->source,$matches))
                return $matches;
            else
                return "Not Found";  
    }    


}

/*

$meta = new metatool();

echo '<pre>';
print_r($meta->setSource($_GET['url'])->getTitle());
print_r($meta->setSource($_GET['url'])->getMeta());
print_r($meta->setSource($_GET['url'])->getOG());
print_r($meta->setSource($_GET['url'])->getAL());
echo '</pre>';

*/

