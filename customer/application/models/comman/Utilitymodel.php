<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Utilitymodel extends CI_Model{
/******************************** RC ******************************************/
/*----------------------- IMAGE RESIZE PLUG-IN ---------------------------------
	@CREATE DATE                 :  22-02-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
        @PASSING ATTRIBUTES ARE      :  IMAGE PATH
                                        NEW PATH
                                        PREFIX NAME
                                        HEIGHT,WIDTH
------------------------------------------------------------------------------*/ 	
    public function resizeimage($imageConfig){
        $config_manip   =   array(
                                'image_library'     =>  'gd2',
                                'source_image'      =>  $imageConfig['source'].'/'.$imageConfig['filename'],
                                'new_image'         =>  $imageConfig['thumb'],
                                'maintain_ratio'    =>  TRUE,
                                'create_thumb'      =>  TRUE,
                                'thumb_marker'      =>  $imageConfig['prefix'],
                                'width'             =>  $imageConfig['width'],
                                'height'            =>  $imageConfig['height']
                            );
        $this->load->library('image_lib', $config_manip);
        if ($this->image_lib->resize()) {
            return TRUE; 
        }
        else{
            return $this->image_lib->display_errors();
        }
    }
}


