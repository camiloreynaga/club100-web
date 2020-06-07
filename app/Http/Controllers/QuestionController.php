<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Question;
use App\AppUser;
use Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = AppUser::orderBy('id', 'DESC')->paginate(10);
        $categories = Category::withCount(['question'=>function($q) {
                        return $q->where('status', 1);
                    }])->orderBy('title', 'ASC')->get();
        return view('admin.question.index', compact('questions', 'categories'));
    }

    // Remove Unnecessary HTML or Space
    public function format_html_string($content){
        $remove_tags = trim(strip_tags($content, '<sub><sup>'));   
        $output = preg_replace('~
         (?>
            <(\w++)[^>]*+>(?>\s++|&nbsp;|<br\s*+/?>)*</\1>  # empty tags
           |                                                # OR
            (?>\s++|&nbsp;|<br\s*+/?>)+                     # white spaces, br, &nbsp;
         )+$
                        ~xi', '', $remove_tags);  

        return $output;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('status', 1)->pluck('title', 'id');
        return view('admin.question.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);             

        $data = $request->all();
        $data['token'] = "";
        $data['status'] = 1;

        $question = new AppUser($data);
        $question->save();

        return redirect('admin/question')->withType('success')->withMessage('User Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = AppUser::findorfail($id);
        return view('admin.question.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = AppUser::findorfail($id);
        $categories = Category::where('status', 1)->pluck('title', 'id');
        return view('admin.question.edit', compact('question', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        $question = AppUser::findorfail($id);
        $data = $request->all();
        
        $question->update($data);        
        return redirect('admin/question')->withType('success')->withMessage('User Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = AppUser::findorfail($id);
        $question->destroy($id);
        return redirect('admin/question')->withType('danger')->withMessage('User Deleted');
    }

    /**
     * Chnage status of the specified resource from storage.
     *
     * @param  int  $id
     * @param  int  $status
     * @return \Illuminate\Http\Response
     */
    public function status($id, $status){
        $question = AppUser::findorfail($id);
        if($status == 0){
            $data['status'] = 1;
            $message = 'Selected User is Active now';
        }
        else{
            $data['status'] = 0;
            $message = 'Selected User is Inactive now';
        }
        $question->update($data);
        return redirect('admin/question')->withType('success')->withMessage($message); 
    }

    /**
     * Chnage status of the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function filterCategory($id){
        if($id == 'all'){
            $questions = AppUser::orderBy('id', 'DESC')->paginate(10);
        }
        else{
            $questions = AppUser::orderBy('id', 'DESC')->where('category_id', $id)->paginate(10);
        }        
        $categories = Category::withCount(['question'=>function($q) {
                        return $q->where('status', 1);
                    }])->orderBy('title', 'ASC')->get();
        return view('admin.question.index', compact('questions', 'categories'));
    }

    /**
     * Search the specified resource from storage.
     *
     * @param  int  $title
     * @return \Illuminate\Http\Response
     */
    public function searchQuestion($title){
        $questions = AppUser::orderBy('id', 'DESC')->where('name', 'like', '%' . $title . '%')->paginate(10);
        $categories = Category::withCount(['question'=>function($q) {
                        return $q->where('status', 1);
                    }])->orderBy('title', 'ASC')->get();
        return view('admin.question.index', compact('questions', 'categories'));
    }
}
