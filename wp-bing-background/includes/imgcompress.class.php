<?php

/**
 * imgcompress v1.0.0
 * 
 * Image compiler
 */

/**
 * Image compiler, Compression by scaling.
 * If you want to maintain the scale of the source image, keep the parameter $percent to 1.
 * Even if the original ratio is compressed, it can be greatly reduced. Digital camera m picture. 
 * Can also be reduced to KB or so. If the scale is reduced, the volume will be smaller.
 * Results: It can be saved and displayed directly.
 */
class imgcompress {
    private $src;
    private $image;
    private $imageinfo;
    private $percent = 0.5;
    
    /**
     * Compress the image.
     * @param string $src The source image url.
     * @param number $percent The image compression scale.
     */
    public function __construct( $src, $percent = 1 ) {
        $this->src = $src;
        $this->percent = $percent;
    }
    
    /**
     * High definition compressed picture
     * @param string $saveName Provide the picture name (without extension, with the extension of the source picture) 
     * for saving. Or directly display without providing a file name.
     */
    public function compressImg( $saveName = '' ) {
        $this->_openImage();
        if( !empty( $saveName ) ) $this->_saveImage( $saveName ); //保存
        else $this->_showImage();
    }
    
    /**
     * Open image.
     * @access private
     */
    private function _openImage() {
        list( $width, $height, $type, $attr ) = getimagesize( $this->src );
        $this->imageinfo = array(
            'width' => $width,
            'height' => $height,
            'type' => image_type_to_extension( $type, false ),
            'attr' => $attr
        );
        $fun = "imagecreatefrom" . $this->imageinfo['type'];
        $this->image = $fun( $this->src );
        $this->_thumpImage();
    }
    
    private function _thumpImage() {
        $new_width = $this->imageinfo['width'] * $this->percent;
        $new_height = $this->imageinfo['height'] * $this->percent;
        $image_thump = imagecreatetruecolor( $new_width, $new_height );
        //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度
        imagecopyresampled(
            $image_thump, 
            $this->image, 
            0, 
            0, 
            0, 
            0, 
            $new_width, 
            $new_height, 
            $this->imageinfo['width'], 
            $this->imageinfo['height']
        );
        imagedestroy( $this->image );
        $this->image = $image_thump;
    }
    
    /**
     * Show image.
     * @access private
     */
    private function _showImage() {
        header( 'Content-Type: image/' . $this->imageinfo['type'] );
        $funcs = "image" . $this->imageinfo['type'];
        $funcs( $this->image );
    }
    
    /**
     * Save image.
     * @param string $dstImgName You can specify a name with no suffix in the string and use the source graph extension. 
     * Directly specify the target picture name with extension.
     * @return boolean
     */
    private function _saveImage( $dstImgName ) {
        if( empty( $dstImgName ) ) return false;
        $allowImgs = ['.jpg', '.jpeg', '.png', '.bmp', '.wbmp','.gif']; //如果目标图片名有后缀就用目标图片扩展名 后缀，如果没有，则用源图的扩展名
        $dstExt = strrchr( $dstImgName, "." );
        $sourseExt = strrchr( $this->src, "." );
        if( !empty( $dstExt ) ) $dstExt = strtolower( $dstExt );
        if( !empty( $sourseExt ) ) $sourseExt = strtolower( $sourseExt );
        //有指定目标名扩展名
        if( !empty( $dstExt ) && in_array( $dstExt, $allowImgs ) ) {
            $dstName = $dstImgName;
        }elseif( !empty( $sourseExt ) && in_array( $sourseExt,$allowImgs ) ){
            $dstName = $dstImgName . $sourseExt;
        }else{
            $dstName = $dstImgName . $this->imageinfo['type'];
        }
        $funcs = "image" . $this->imageinfo['type'];
        $funcs( $this->image, $dstName );
    }
    
    /**
     * Destroy the image.
     */
    public function __destruct() {
        imagedestroy( $this->image );
    }
}