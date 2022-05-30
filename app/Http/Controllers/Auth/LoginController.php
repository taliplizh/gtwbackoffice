<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Storage;
use File;
use Artisan;
use App\Models\Org;
class LoginController extends Controller
{
    public function login()
    {
        // แก้ไข fonts ??? ตรวจสอบ dir fonts cache ถ้ายังไม่มีให้สร้าง
        $fonts = storage_path('fonts') ;
            if(!File::isDirectory($fonts)){
                File::makeDirectory($fonts, 0777, true, true);
            }
            $assetpdf = storage_path('app/public/assetpdf') ;
            if(!File::isDirectory($assetpdf)){
                File::makeDirectory($assetpdf, 0777, true, true);
            }
            
            $bookpdf = storage_path('app/public/bookpdf') ;
            if(!File::isDirectory($bookpdf)){
                File::makeDirectory($bookpdf, 0777, true, true);
            }

            $riskpdf = storage_path('app/public/riskpdf') ;
            if(!File::isDirectory($riskpdf)){
                File::makeDirectory($riskpdf, 0777, true, true);
            }

            $happy_image_question = storage_path('app/public/happy_image_question') ;
            if(!File::isDirectory($happy_image_question)){
                File::makeDirectory($happy_image_question, 0777, true, true);
            }

            $happy_image_reward = storage_path('app/public/happy_image_reward') ;
            if(!File::isDirectory($happy_image_reward)){
                File::makeDirectory($happy_image_reward, 0777, true, true);
            }

            $bookpdf = storage_path('app/public/leavepdf') ;
            if(!File::isDirectory($bookpdf)){
                File::makeDirectory($bookpdf, 0777, true, true);
            }


            $lesson_doc = storage_path('app/public/lesson_doc') ;
            if(!File::isDirectory($lesson_doc)){
                File::makeDirectory($lesson_doc, 0777, true, true);
            }

            $lesson_video = storage_path('app/public/lesson_video') ;
            if(!File::isDirectory($lesson_video)){
                File::makeDirectory($lesson_video, 0777, true, true);
            }

            $filepreson = storage_path('app/public/filepreson') ;
            if(!File::isDirectory($filepreson)){
                File::makeDirectory($filepreson, 0777, true, true);
            }


            $storage = public_path('storage') ;
            if(!File::isDirectory($storage)){
                Artisan::call('storage:link');
            }
            
          



        // if (Auth::check()) {
        //     return redirect()->route('select.dashboard');
        // }else{
            return view('auth.login');
        // }
    }

    public function authenticate(Request $request)
    {
        $org = Org::find(1);
        // $request->validate([
        //     'username' => 'required|string',
        //     'password' => 'required|string',
        // ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {

            $url = env("APP_DATACENTER")."logs";
            $type = 'login';
            $this->logs($url, $type);
           
                return redirect()->intended('home');
        
        }

        return redirect('login')->with('error', 'Oppes! You have entered invalid credentials');

    }

    // เก็บ log การเข้าใช้งานไปยัง datacenter
    private  function logs($url,$type){
        $org = Org::find(1);

        try {
            // เก็บ log การเข้าใช้งานไปยัง datacenter
            $endpoint = $url;
            $client = new \GuzzleHttp\Client();
            $response = $client->request('post', $endpoint, ['query' => [
                'type' => $type,
                'hos_code' => $org->ORG_PCODE,
                'hos_name' => $org->ORG_NAME,
                'user_id' => Auth::id(),
                'username' => Auth::user()->name,
                'ip_gateway' => request()->ip(), 
                'ip_client' => request()->ip(),
            ]]);
            $statusCode = $response->getStatusCode();
           $content = $response->getBody();

            // จบ=
            } catch (\Throwable $th) {
                            //throw $th;
            }
    }

    public function logout() {
      Auth::logout();

      return redirect('login');
    }

    public function home()
    {

      return view('home');
    }
}
