<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Question;
use Illuminate\Notifications\Notification;
use Route;
use App\User;
use App\AppUser;
use App\AppNotification;
use Auth;
use App\Tutorial;
use Maatwebsite\Excel\Facades\Excel;

class PagesController extends Controller
{
    public function dashboard(){
        $questions = AppUser::orderBy('id', 'DESC')->limit(10)->get();
        $categories = Category::withCount('question')->orderBy('id', 'DESC')->limit(10)->get();
        $total_question = count(AppUser::all());
        $total_category = count(Category::all());

        return view('admin.dashboard', compact('questions', 'categories', 'total_question', 'total_category'));
    }

    public function settings(){
        return view('admin.settings');
    }

    public function profile(){
        return view('admin.profile');
    }

    public function tutorial(){
        $tutorials = AppNotification::orderBy('id', 'DESC')->get();
        return view('admin.tutorial.index', compact('tutorials'));
    }

    public function addTutorial(Request $request){
        $this->validate($request, [
            'content' => 'required',
        ]);
        $data = $request->all();

        $tutorial = Tutorial::orderBy('id', 'DESC')->limit(1)->get();

        if(count($tutorial) > 0){
            $id = $tutorial[0]->id;
            $tutorial = Tutorial::findorfail($id);
            $tutorial->update($data);
            return redirect('admin/tutorial')->withType('success')->withMessage('Tutorial Updated');
        }
        else{
            $tutorial = new Tutorial($data);
            $tutorial->save();
            return redirect('admin/tutorial')->withType('success')->withMessage('Tutorial Added');
        }
    }

    public function notification(){
        $categories = Category::where('status', 1)->pluck('title', 'id');
        return view('admin.notification', compact('categories'));
    }

    public function upload(){
        $categories = Category::where('status', 1)->pluck('title', 'id');
        return view('admin.upload', compact('categories'));
    }

    public function uploadData(Request $request){
        $this->validate($request, [
            'category_id' => 'required',
            'upload_file' => 'required'
        ]);

        $data = $request->all();
        \Session::put('category_id', $data['category_id']);

        if($request->file('upload_file')){
            $file = $request->file('upload_file');
            $mimes = $file->getClientMimeType();
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(base_path() . '/uploads/bulkupload/', $name);
            $data['upload_file'] = base_path() . '/uploads/bulkupload/' . $name;
        }

        try {
            Excel::load($data['upload_file'], function ($reader) {
                $cat_id = \Session::get('category_id');

                foreach ($reader->toArray() as $key => $question) {
                    $number_of_answer = 0;
                    if(!empty($question['choice_a'])){
                        $number_of_answer++;
                    }
                    if(!empty($question['choice_b'])){
                        $number_of_answer++;
                    }
                    if(!empty($question['choice_c'])){
                        $number_of_answer++;
                    }
                    if(!empty($question['choice_d'])){
                        $number_of_answer++;
                    }
                    if(!empty($question['choice_e'])){
                        $number_of_answer++;
                    }
                    $question['number_of_answer'] = $number_of_answer;
                    $question['category_id'] = $cat_id;

                    $question = new Question($question);
                    $question->save();
                }
            });

            unlink($data['upload_file']);
            return redirect('admin/upload')->withType('success')->withMessage('Questions Data Added');
        }
        catch(\Exception $e){
            return redirect('admin/upload')->withType('danger')->withMessage($e->getMessage());
        }
    }

    private function UnirNotificacion($notification_data)
    {
        //liga + hora + tipo apuesta 
        $notification_data['title']= $notification_data['liga'].' '.$notification_data['title'].' '.$notification_data['apuesta'];
        $notification_data['message'] = $notification_data['message'] .' '.$notification_data['porcentaje'];
        return $notification_data;

    }

    public function sendNotification(Request $request){

        $notification_data = $request->all();
        $notification_data= $this->UnirNotificacion($notification_data);
        
        $notification = new AppNotification($notification_data);
        $notification->save();
        
        //obteniendo a todos los usuarios activos
        if( isset($notification_data['grupo']) && $notification_data['grupo']=='on')
            $flights = AppUser::where('status', 1)->get();
        else
            $flights = AppUser::where(
                ['category_id'=> $notification_data['category_id'],
                'status'=>1])
                ->get();

        // dd($flights);
        
        $array_token = array();
        foreach($flights as $item){
            array_push($array_token, $item['token']);
        }
        $key = env("FIREBASE_API_SERVER_KEY", "");

        if(!empty($key)){
            $this->validate($request, [
                'title' => 'required',
                'message' => 'required',
            ]);

            $data = array("to" => "/topics/" . env("FIREBASE_TOPIC", ""), "notification" => array( "title" => $notification_data['title'], "body" => $request['message'], "image" => $request['image']));
            //$data_string = json_encode($data);
            //return "The Json Data : ".$data_string;

            $url = 'https://fcm.googleapis.com/fcm/send';

            $headers = array(
                'Authorization: key=' . $key,
                'Content-Type: application/json'
            );
            $fields = array
            (
                'registration_ids'     => $array_token,
                'data'            => array( "title" => $notification_data['title'], "body" => $request['message'], "image" => $request['image'])
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if($result === FALSE){
                die('Sending Push Notification Failed: ' . curl_error($ch));
                return false;
            }

            curl_close($ch);

            return redirect('admin/notification')->withType('success')->withMessage('Push Notification Sent!');
        }
        else{
            return 'Enter Your Firebase Server API Key on the .env File First!';
        }
    }

    public function createUser(Request $request){
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        return redirect('admin/profile')->withType('success')->withMessage('User Added');
    }

    public function updatePassword(Request $request){
        $this->validate($request, [
            'uname' => 'required|string|max:255',
            'uemail' => 'required|string|email|max:255',
            'upassword' => 'required|string|min:6|confirmed',
        ]);

        $id = \Auth::user()->id;
        $user = User::findorfail($id);

        $data = array();
        $data['name'] = $request['uname'];
        $data['email'] = $request['uemail'];
        $data['password'] = bcrypt($request['upassword']);

        $user->update($data);
        return redirect('admin/profile')->withType('success')->withMessage('Profile Updated');
    }

    public function addUser(Request $request){
        $data = $request->all();
        $flights = AppUser::where('email', $data['email'])->get();
        if(count($flights) == 0){
            $category = new AppUser($data);
            $category->save();
            $ret = array(
                "result" => "success"
            );
        }else{
            $ret = array(
                "result" => "exist"
            );
        }

        return $ret;
    }
    public function login(Request $request){

        $data = $request->all();
        $matchThese = ['email' => $data['email'], 'password' => $data['password'], 'status' => 1];

        $flights = AppUser::where($matchThese)->get();

        if(count($flights) == 0){
            $ret = array(
                "result" => "failed"
            );
        }else{
            $device_token = "";
            foreach($flights as $item){
                $name = $item['name'];
                $category_id = $item['category_id'];
                $device_token = $item['token'];
            }

            if($device_token == "") {
                $flights = AppUser::where($matchThese)
                    ->update(['token' => $data['token']]);
                $ret = array(
                    "result" => "success",
                    "name" => $name,
                    "category_id" => "" . $category_id
                );
            }else{
                if($device_token == $data['token']){
                    $ret = array(
                        "result" => "success",
                        "name" => $name,
                        "category_id" => "" . $category_id
                    );
                }else{
                    $ret = array(
                        "result" => "token is wrong"
                    );
                }
            }
        }

        return $ret;
    }
    public function apiSignupUser($name, $email, $password, $token){
        $flights = AppUser::where('email', $email)->get();
        if(count($flights) == 0){
            $data = array(
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'token' => $token
            );
            $category = new AppUser($data);
            $category->save();
            $ret = array(
                "result" => "success"
            );
        }else{
            $ret = array(
                "result" => "exist"
            );
        }

        return $ret;
    }
    public function forgotPassword(Request $request){
        $data = $request->all();
        $flights = AppUser::where('email', $data['email'])->get();
        if(count($flights) == 0){
            $ret = array(
                "result" => "failed"
            );
        }else{
            $new_password = rand(1000, 9999);
            $flights = AppUser::where('email', $data['email'])
                ->update(['password' => $new_password]);
            $ret = array(
                "result" => "success"
            );

            $body = <<<EOT
        <h3>Hello </h3>
        <br>
        <p>Your password was recreated by admin.</p>
        <br>
        <p>New Password: $new_password </p>
        <br>
        <p>Thank You</p>
        <p>Club100</p>
EOT;
            $to = $data['email'];
            $subject = "[ club100 ] This is your new password. ";
            $message = $body;
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: Club100 <clubcien100@gmail.com>' . "\r\n";
            mail($to, $subject, $message, $headers);
        }

        return $ret;
    }
    public function getNotification(Request $request){
        $data = $request->all();
        $categories = AppNotification::where('category_id', $data['category_id'])->orderBy('id', 'DESC')->get();;
        return $categories;
    }
    public function apiShowCategories(){
        $categories = Category::withCount(['question'=>function($q) {
            return $q->where('status', 1);
        }])->orderBy('position', 'ASC')->withCount('children')->orderBy('title', 'ASC')->where('status', 1)->where('parent_id', null)->get();
        return $categories;
    }

    public function apiShowCategoriesPremium(){
        $categories = Category::withCount(['question'=>function($q) {
            return $q->where('status', 1);
        }])->orderBy('position', 'ASC')->withCount('children')->orderBy('title', 'ASC')->where('paid', 1)->where('status', 1)->where('parent_id', null)->get();
        return $categories;
    }

    public function apiShowCategoriesFree(){
        $categories = Category::withCount(['question'=>function($q) {
            return $q->where('status', 1);
        }])->orderBy('position', 'ASC')->withCount('children')->orderBy('title', 'ASC')->where('paid', 0)->where('status', 1)->where('parent_id', null)->get();
        return $categories;
    }

    public function apiShowChildCategories($id){
        $categories = Category::withCount(['question'=>function($q) {
            return $q->where('status', 1);
        }])->withCount('children')->orderBy('position', 'ASC')->where('status', 1)->where('parent_id', $id)->get();
        return $categories;
    }

    public function apiShowChildCategoriesPremium($id){
        $categories = Category::withCount(['question'=>function($q) {
            return $q->where('status', 1);
        }])->withCount('children')->orderBy('position', 'ASC')->where('paid', 1)->where('status', 1)->where('parent_id', $id)->get();
        return $categories;
    }

    public function apiShowChildCategoriesFree($id){
        $categories = Category::withCount(['question'=>function($q) {
            return $q->where('status', 1);
        }])->withCount('children')->orderBy('position', 'ASC')->where('paid', 0)->where('status', 1)->where('parent_id', $id)->get();
        return $categories;
    }

    public function apiShowSingleCategory($id){
        $category = Category::findorfail($id);
        return $category;
    }

    public function apiShowSingleCategoryQuestion($id){
        $questions = Category::findorfail($id)->question()->where('status', 1)->get();
        return $questions;
    }

    public function apiShowQuestions(){
        $questions = Question::orderBy('id', 'ASC')->where('status', 1)->get();
        return $questions;
    }

    public function apiShowSingleQuestion($id){
        $question = Question::findorfail($id)->get();
        return $question;
    }

    public function apiShowTutorial(){
        $tutorial = Tutorial::orderBy('id', 'DESC')->first();
        return $tutorial;
    }
}
