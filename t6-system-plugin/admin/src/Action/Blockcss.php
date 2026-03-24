<?php
namespace T6Admin\Action;

use Joomla\CMS\Factory;
use T6\Helper\Path;
use T6Admin\Action;
class Blockcss {

	// custom Font actions
    public static function doGetcss() {
        $input = Factory::getApplication()->input->post;
        $blockName = $input->getVar('name');
        $tplStyle = \T6Admin\Admin::getTemplate(true)->id;
        $fileName = 'scss/layouts/'.$tplStyle . '-' . $blockName . '.scss';


        $file = \T6\Helper\Path::findInTheme($fileName);
        $scss = $file ? file_get_contents($file) : '';
        echo $scss;
        exit();
    }
    public static function doSave() {
        $input = Factory::getApplication()->input->post;
        $path = T6PATH_LOCAL . '/scss/layouts/';
        $blockName = $input->getVar('name');
        $blockscss =  $input->getRaw('blockcss', '');
        $tplStyle = \T6Admin\Admin::getTemplate(true)->id;
        $fileName = $tplStyle . '-' . $blockName . '.scss';
        if ($blockscss) {
            Path::saveLocalContent('scss/layouts/'.$fileName, $blockscss);

        }else{
            $aa = Path::removeLocalContent('scss/layouts/'.$fileName);
        }
        return self::renderCss();
    }
    public static function renderCss()
    {
        $path = T6PATH_LOCAL . '/scss/layouts/';
        $tplStyle = \T6Admin\Admin::getTemplate(true)->id;
        $allFileScss = array();
        $allFileScss = Action::scanDirectories($path,$allFileScss);
        // save template.scss if not exist
        $layouts = '';
        if(count($allFileScss)){
            $layouts .= "// load custom block css\n";
            foreach ($allFileScss as $filescss) {
                $name = basename($filescss,'.scss');
                if(strpos($name, $tplStyle.'-') !== false){
                    $layouts .= "// layouts styles.\n#".str_replace($tplStyle,'t6',$name)."{\n\n @import \"layouts/".$name."\";\n}\n\n";
                }
            }
            $layouts .= "// end load custom block css\n";
        }
        if($layouts){
            // now compile local layouts css
            require_once T6PATH . '/vendor/autoload.php';
            $scss = new \ScssPhp\ScssPhp\Compiler();
            chdir(T6PATH_LOCAL . '/scss');
            $css = $scss->compile($layouts);
            Path::saveLocalContent('css/'.$tplStyle . '-layouts.css', $css);

            return ['ok' => 1];
        }
        return ['error'=> "T6_BLOCK_CUSTOM_CSS_SAVE_ERROR"];
    }
}