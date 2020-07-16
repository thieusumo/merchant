<?php
namespace App\Helpers;

use App\Providers\GoogleProvider;
use GuzzleHttp\Client;
use App\Traits\License;
/**
 * ImagesHelper class
 */
class ImagesHelper{
    use License;
    /**
     * send post request upload Image to a different server
     * @return string image name
     */
    private static function sendRequestToApi($tmpUpload,$name,$path){
      try {
        $client = new Client;
        $response = $client->request('POST', config('app.url_file_write'), 
          [                
                'multipart' => [
                      [
                          'name'     => 'name',
                          'contents' => $name,
                      ],                    
                      [
                          'name'     => 'fileUpload',
                          'contents' => fopen($tmpUpload, 'r'),
                      ],
                      [
                          'name'     => 'pathImage',
                          'contents' => $path,
                      ]
                  ],
                  'headers' => [
                      'Authorization' => 'Bearer '.env('UPLOAD_IMAGE_API_KEY'),
                  ],
          ]); 
        $body = (string)$response->getBody();
        \Log::info($body);

      } catch (\Exception $e) {
        \Log::info($e);
        return "error";
      }
    }

    public static function uploadImage($file, $folder_upload, $place_ip_license) { 
        $place_ip_license = self::getLicense();

        $pathFile = config('app.url_file_write');
        $name = $file->getClientOriginalName();
        $name = str_replace(" ", "-", $name);
        $pathImage = '/images/' . $place_ip_license . '/website/' . $folder_upload . '/';
        $filename = strtotime('now') . strtolower($name);
        //dd(config('app.url_file_write'));
        // if (!file_exists($pathFile . $pathImage)) {
        //     mkdir($pathFile . $pathImage, 0777, true);
        // }
        $file->move("tmp-upload", $filename);
        $tmpUpload = "tmp-upload/".$filename;

        self::sendRequestToApi($tmpUpload,$filename,$pathImage);
        unlink("tmp-upload/".$filename);

        // die();
        return $pathImage . $filename;
    }

    public static function uploadImageService($file , $folder_upload , $place_ip_license)
    {     
          $place_ip_license = self::getLicense();

          $pathFile   = config('app.url_file_write');
          $name = $file->getClientOriginalName();
          $pathImage = '/images/'.$place_ip_license.'/website/'.$folder_upload.'/';
          // if (!file_exists($pathFile.$pathImage)) {
          //     mkdir($pathFile.$pathImage,0777, true);
          // }
          // $file->move($pathFile.$pathImage,$name);
          $file->move("tmp-upload", $name);
          $tmpUpload = "tmp-upload/".$name;

          self::sendRequestToApi($tmpUpload,$name,$pathImage);
          unlink("tmp-upload/".$filename);

          return $pathImage.$name;
    }

    public static function uploadImageDropZone($file , $folder_upload , $place_ip_license)
    {     
          return self::uploadImageDropZone_get_path($file , $folder_upload , $place_ip_license);
          
          // $place_ip_license = self::getLicense();

          // $pathFile   = config('app.url_file_write');
          // $name = preg_replace("/[^A-Za-z0-9\-]/",'_',$file->getClientOriginalName());
          // $pathImage = '/images/'.$place_ip_license.'/website/'.$folder_upload.'/';
          // // if (!file_exists($pathFile.$pathImage)) {
          // //     mkdir($pathFile.$pathImage,0777, true);
          // // }
          // // $file->move($pathFile.$pathImage,$name);
          // $file->move("tmp-upload", $name);
          // $tmpUpload = "tmp-upload/".$name;

          // self::sendRequestToApi($tmpUpload,$name,$pathImage);
          // unlink("tmp-upload/".$name);
          
          // return $pathImage.$name;
    }

    public static function uploadImageDropZone_get_path($file , $folder_upload , $place_ip_license)
    {     
          $place_ip_license = self::getLicense();
          
          $pathFile   = config('app.url_file_write');
          $name = preg_replace("/[^A-Za-z0-9\-]\./",'_',$file->getClientOriginalName());
          $pathImage = '/images/'.$place_ip_license.'/website/'.$folder_upload.'/';
          // if (!file_exists($pathFile.$pathImage)) {
          //     mkdir($pathFile.$pathImage,0775, true);
          // }
          // $file->move($pathFile.$pathImage,$name);
          $file->move("tmp-upload", $name);
          $tmpUpload = "tmp-upload/".$name;

          self::sendRequestToApi($tmpUpload,$name,$pathImage);
          unlink("tmp-upload/".$name);
          
          return $pathImage.$name;
    }
    public static function uploadImageCanvas($path_file, $folder_upload,$filename){
          $place_ip_license = self::getLicense();

          $pathImage = '/images/'.$place_ip_license.'/website/'.$folder_upload.'/';

          try {
            self::sendRequestToApi($path_file,$filename,$pathImage);
            unlink($path_file);
          } catch (\Exception $e) {
            \Log::info($e);
          }

          return $pathImage.$filename;
    }
    //abc
    
    /**
     * generate shortened URL through famous API like Google
     * @param type $imageUrl
     * @return type
     */
    public static function getShortUrl($imageUrl){
        return (new GoogleProvider())->shorten(config('app.url_file_view')."/".$imageUrl);
    }
}
?>