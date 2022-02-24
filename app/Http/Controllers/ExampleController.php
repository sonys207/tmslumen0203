<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\User;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\HcException;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
     
        // $this->middleware('locale');
    }
    public function save()
    {

        $result=DB::table('users')->insert(
            ["id" => "2",
                'name' => 'sam',
                'email' => 'sam@mail.com',
                'password' => Hash::make("sam1"),
            ]
        );
        echo $result;
    }

    public function test(Request $Request)
    {
       
        //---Get access token Start---
        $postData = array (
            'client_id' => '238843d9-cecd-4a6e-82c7-84f93a7d96fe',
            'client_secret' => '3MC7Q~CLkWv4SyqYrXn6H6s4rkZ6JJwfqJZBn',
            'grant_type' => 'client_credentials',
            'Scope' => 'https://vault.azure.net/.default'
        );
        $ch = curl_init();
        //e13d0b7a-a128-47ce-81a8-9e7d3daf0e94为app registration中的 (tenant) ID
        curl_setopt($ch, CURLOPT_URL,"https://login.microsoftonline.com/e13d0b7a-a128-47ce-81a8-9e7d3daf0e94/oauth2/v2.0/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); 
        $json_response_data = curl_exec($ch);
        curl_close($ch);
        print_r(json_decode($json_response_data, true));
      //  print_r(json_decode($json_response_data, true)['access_token']);
        //dd(json_decode($json_response_data, true)['access_token']);
        $access_token=json_decode($json_response_data, true)['access_token'];
        print_r($access_token);
        //---Get access token End---


        //---Get data Encyption Start
        $cURL = curl_init();
        $str = 'I like to live in Markham';
       
        $strenconde = base64_encode($str);
        $header=[
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer '.$access_token,
            'Cache-Control'=>'no-cache'
        ];
        print_r($header);
        $postdata2 = [
            'alg'=>'RSA-OAEP-256',
            'value'=>$strenconde
        ];
        print_r($postdata2);
        curl_setopt($cURL, CURLOPT_URL, "https://keyvalut0222.vault.azure.net/keys/RSAKEY202222/8ab185785de54ca1bba657482d908ad8/encrypt?api-version=7.2");
        //curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, $header);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $postdata2); 
        curl_setopt($cURL, CURLOPT_POST, true);
        $json_response_data1 = curl_exec($cURL);
        curl_close($cURL);
        print_r($json_response_data1);
        //---Get data Encyption End
        dd(1234456);



        $str1 = 'SSBsb3ZlIHRvbnk';
        echo base64_decode($str1);

        
       // $user = $Request->user();
        $user = Auth::user();
      //  $mgt_uid = app()->make('CSAuth')->getCS($uid)->mgt_uid;
      //dd($this);
         $la_paras = $this->parse_parameters($Request, __FUNCTION__);
      
      
      
         dd($la_paras);
       $user1 = app()->make('tony0127')->auth0127();
       dd($user1);
       dd($user);
        return response()->json(['name' => $Request->input('tony'), 'state' => 'CA']);
    }

    

    public function userinfo(Request $Request)
    {
        
        dd(123);
        $encrypted =$this->str_encryptaesgcm("mysecretText", "myPassword", "hex");
        $decrypted =$this->str_decryptaesgcm($encrypted, "myPassword", "hex");
        dd($encrypted,$decrypted);
        $ch = curl_init('https://www.howsmyssl.com/a/check'); 
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt ($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_MAX_TLSv1_1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch); 
        curl_close($ch); 
        $json = json_decode($data); 
        echo "<h1>Your TLS version is: " . $json->tls_version . "</h1>\n";

	 // dd(DB::connection()->getPdo());	
     error_log('API Error:Some message here.');
     
     //方法1
    // $file_path1 = base_path().'\tmp\trace.log';
    //方法2
    $file_path1 = base_path('tmp/trace.log');
     error_log("order_info:10000017.\r\n",3,$file_path1);
      return User::all();
    }


    function str_encryptaesgcm($plaintext, $password, $encoding = null) {
        if ($plaintext != null && $password != null) {
            $keysalt = openssl_random_pseudo_bytes(16);
            //openssl_random_pseudo_bytes — 生成一个伪随机字节串
            
            $key = hash_pbkdf2("sha512", $password, $keysalt, 20000, 32, true);
            //生成所提供密码的 PBKDF2 密钥派生
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-gcm"));
            $tag = "";
            $encryptedstring = openssl_encrypt($plaintext, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag, "", 16);
            $result=$encoding == "hex" ? bin2hex($keysalt.$iv.$encryptedstring.$tag) : ($encoding == "base64" ? base64_encode($keysalt.$iv.$encryptedstring.$tag) : $keysalt.$iv.$encryptedstring.$tag);
            echo 123456;
            dd($keysalt,$key,$iv,$encryptedstring,$result);
            return $result;
        }
    }
    
    function str_decryptaesgcm($encryptedstring, $password, $encoding = null) {
        if ($encryptedstring != null && $password != null) {
            $encryptedstring = $encoding == "hex" ? hex2bin($encryptedstring) : ($encoding == "base64" ? base64_decode($encryptedstring) : $encryptedstring);
            $keysalt = substr($encryptedstring, 0, 16);
            $key = hash_pbkdf2("sha512", $password, $keysalt, 20000, 32, true);
            $ivlength = openssl_cipher_iv_length("aes-256-gcm");
            $iv = substr($encryptedstring, 16, $ivlength);
            $tag = substr($encryptedstring, -16);
            return openssl_decrypt(substr($encryptedstring, 16 + $ivlength, -16), "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag);
        }
    }

    public function create_order(Request $Request)

    {
        //print_r($Request->input());
     
            $this->validate($Request, [
                '*.user_id' => 'required',
                '*.order_number' => 'required|unique:orders,order_number|max:12|distinct'
            ]);
        
     
        
     // return $Request->input();
     print_r ($Request->input());
     dd(123);
     $order=new Order();
     $order->order_number=$Request->input('order_number');
     $order->user_id=$Request->input('user_id');
     $status = $order->save();
     echo $status;
    }
    //

  
}
