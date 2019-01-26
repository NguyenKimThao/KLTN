<?php


if(!class_exists('Camunda')) {
    class Camunda {
            function __construct() 
            {
                   if(!function_exists('add_shortcode')) {
                            return;
                    } 
                    
                    add_shortcode( 'hello' , array(&$this, 'hello_func') );
              
          }      
        function hello_func($atts = array(), $content = null) {
                    extract(shortcode_atts(array('name' => 'World'), $atts));
                    return '<div>Heelo'.$name.'</div>';
            }
    }
}
function camundaLoad() {
global $mfpd;
$mfpd = new Camunda();
}
add_action( 'plugins_loaded', 'camundaLoad' );
?>

?>