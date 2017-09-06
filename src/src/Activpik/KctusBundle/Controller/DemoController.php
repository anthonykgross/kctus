<?php
namespace Activpik\KctusBundle\Controller;

include dirname(__FILE__).DIRECTORY_SEPARATOR.'../Vendor/phpqrcode/qrlib.php';

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DemoController extends Controller
{
    /**
     * @Route("/f/{file}")
     * @Template()
     */
    public function indexAction($file = "france-bresil-1998")
    {
        $json_info      = json_decode($this->getFile($file));
        $key            = md5(time());
        $path_qrcode    = $this->container->getParameter('ABSOLUTE_PATH_UPLOAD_QRCODE').$key.'.png';
        \QRcode::png($this->generateUrl("padKey", array('file' => $file ,'key' => $key), true), $path_qrcode, 'L', 3, 2);
        
        //create background for video + qrcoder inner.
        $overlay    = new \Imagick($path_qrcode);
        $image      = new \Imagick();
        $image->readImageBlob(base64_decode(str_replace('data:image/png;base64,', '', $json_info->thumb)));

        $image->setImageColorspace($overlay->getImageColorspace() ); 
        $image->compositeImage($overlay, \Imagick::COMPOSITE_DEFAULT, 10, 10);
        $image->writeImage($path_qrcode); //replace original background

        return $this->redirect($this->generateUrl('videoKey', array('file' => $file, 'key' => $key)));
    }

    /**
     * @Route("/f/{file}/video/key/{key}", name="videoKey")
     * @Template()
     */
    public function videoKeyAction($file, $key)
    {
        
        return array(
            'json'              => $this->getFile($file),
            'key'               => $key,
            'serverio'          => $this->container->getParameter('URL_SERVER_IO')
        );
    }
    
    /**
     * @Route("/f/{file}/pad/key/{key}", name="padKey")
     * @Template()
     */
    public function padKeyAction($file, $key)
    {
        return array(
            'json'      => $this->getFile($file),
            'file'      => $file,
            'base_path' =>  realpath(dirname(__FILE__)."/../../../../web/data/"),
            'key'       => $key, 
            'serverio'  => $this->container->getParameter('URL_SERVER_IO')
        );
    }
    
    private function getFile($file){
        $absolute_path = realpath(dirname(__FILE__)."/../../../../web/data/".$file."/data.json");
        
        if(!$absolute_path){
            throw new Exception("Invalide filename");
        }
        $content = file_get_contents($absolute_path);
        return json_encode(json_decode($content));
    }
}
