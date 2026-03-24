<?php
namespace T6\Helper;

use Joomla\CMS\Factory;

class Asset {


    public static function getWebAssetManager() {
        static $wam = null;
        if ($wam === null) {
            $doc = Factory::getApplication()->getDocument();
            if (!$doc) {
                $doc = Factory::getDocument();
            }

            $wam = $doc->getWebAssetManager();
        }
        return $wam;
    }

    public static function init() {
        $assetfile = '/etc/assets.json';

        self::addAssets(T6PATH_BASE . $assetfile);
        self::addAssets(T6PATH_TPL . $assetfile);
        self::addAssets(T6PATH_LOCAL . $assetfile);
        self::getBs5();
    }

    public static function addAssets($file) {

        //static $i = 1;
        //if ($i++ == 10) {debug_print_backtrace(-1);die;}
        if (!is_file($file)) return;
        $assets = json_decode(file_get_contents($file), true);

        $wam = self::getWebAssetManager();
        $war = $wam->getRegistry();

        foreach ($assets['assets'] as $name => $asset) {

            // update url
            if (!empty($asset['js'])) {
                $new_js_asset = array();
                $new_js_asset['attributes'] = array();
                $new_js_asset['name'] = $name;
                // $new_js_asset['required'] = !empty($asset['required']) ? $asset['required'] : "";
                $new_js_asset['type'] = 'script';
                for ($i = count($asset['js']) -1; $i >=0 ; $i--) {
                    $_uri = Path::findInTheme($asset['js'][$i], true, true);
                    if($_uri){
                        $new_js_asset['uri'] = $_uri;
                        if($i -1 >= 0){
                            $new_js_asset['name'] = 'js.'.basename($_uri,'.min.js');
                            $new_js_asset['dependencies'] = !empty($asset['dependencies']) ? $asset['dependencies'] : array('jquery');
                        }else{
                            $new_js_asset['dependencies'] = !empty($asset['js'][$i+1]) ? array($new_js_asset['name']) : (!empty($asset['dependencies']) ? $asset['dependencies'] : array('jquery'));
                            $new_js_asset['name'] = $name;
                        }
                        $js_asset = new \Joomla\CMS\WebAsset\WebAssetItem($new_js_asset['name'], $new_js_asset['uri'], $new_js_asset,$new_js_asset['attributes'],$new_js_asset['dependencies']);
                        $war->add($new_js_asset['type'],$js_asset);
                    }
                }
                
            }
            if (!empty($asset['css'])) {
                 $new_css_asset = array();
                $new_css_asset['attributes'] = array();
                $new_css_asset['name'] = $name;
                $new_css_asset['required'] = !empty($asset['required']) ? $asset['required'] : "";
                $new_css_asset['type'] = 'style';
                for ($i = count($asset['css']) -1; $i >=0 ; $i--) {
                    $_uri = Path::findInTheme($asset['css'][$i], true, true);
                    if($_uri){
                        $new_css_asset['uri'] = $_uri;
                        if($i -1 >= 0){
                            $new_css_asset['name'] = 'css.'.basename($_uri,'.min.css');
                            $new_css_asset['dependencies'] = !empty($asset['dependencies']) ? $asset['dependencies'] : array();
                        }else{
                            $new_css_asset['dependencies'] = !empty($asset['css'][$i+1]) ? array($new_css_asset['name']) : (!empty($asset['dependencies']) ? $asset['dependencies'] : array());
                            $new_css_asset['name'] = $name;
                        }
                        $css_asset = new \Joomla\CMS\WebAsset\WebAssetItem($new_css_asset['name'], $new_css_asset['uri'], $new_css_asset,$new_css_asset['attributes'],$new_css_asset['dependencies']);
                        $war->add($new_css_asset['type'],$css_asset);
                    }
                }
            }
           
        }
    }
    public static function getBs5()
    {
        // Check base theme
        $template = Factory::getApplication()->getTemplate();
        // parse xml
        $filePath = JPATH_THEMES . '/' . $template . '/templateDetails.xml';
        $bs5 = null;
        if (is_file ($filePath)) {
            $xml = simplexml_load_file($filePath);
            // check t6 load bs5
            if (isset($xml->t6) && isset($xml->t6->bootstrap)) {
                $bs5 = trim(strtolower($xml->t6->bootstrap));
            }
        }
        return $bs5;
    }
}
