<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppNotification;
use App\Category;
use Auth;

class AppNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = AppNotification::orderBy('id', 'DESC')->paginate(10);
        return view('admin.tutorial.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {       
        $categories = Category::pluck('title', 'id');
        return view('admin.category.create', compact('categories'));
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
            'title' => 'required',
            'description' => 'required',
            'thumbnail' => 'mimes:jpeg,jpg,png,bmp,gif'
        ]);

        $data = $request->all();

        if($request->file('thumbnail')){
            $file = $request->file('thumbnail');
            $mimes = $file->getClientMimeType();
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(base_path() . '/uploads/category/', $name); 
            $data['thumbnail'] = $name;            
        }        

        $category = new Category($data);        
        $category->save();
        return redirect('admin/category')->withType('success')->withMessage('Plan Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = AppNotification::findorfail($id);
        $categories = Category::where('status', 1)->pluck('title', 'id');
        return view('admin.tutorial.edit', compact('category', 'categories'));
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
            'title' => 'required',
            'description' => 'required',
            'thumbnail' => 'mimes:jpeg,jpg,png,bmp,gif'
        ]);

        $category = Category::findorfail($id);
        $data = $request->all();

        if($request->file('thumbnail')){
            $file = $request->file('thumbnail');
            $mimes = $file->getClientMimeType();
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(base_path() . '/uploads/category/', $name); 
            $data['thumbnail'] = $name;   
        }
        
        $category->update($data);        
        return redirect('admin/category')->withType('success')->withMessage('Plan Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = AppNotification::findorfail($id);
        $category->destroy($id);
        return redirect('admin/tutorial')->withType('danger')->withMessage('Notification Deleted');
    }    

    /**
     * Chnage status of the specified resource from storage.
     *
     * @param  int  $id
     * @param  int  $status
     * @return \Illuminate\Http\Response
     */
    public function status($id, $status){
        $category = AppNotification::findorfail($id);
        if($status == 1){
            $data['status'] = 1;
            $message = 'Selected Notification is WIN now';
        }
        else{
            $data['status'] = 2;
            $message = 'Selected Notification is LOSE now';
        }
        $flights = AppNotification::where('id', $id)
            ->update(['status' => $status]);

        //$category->update($data);
        return redirect('admin/tutorial')->withType('success')->withMessage($message);
    }

    public function order(){
        $categories = Category::withCount(['question'=>function($q) {
                        return $q->where('status', 1);
                    }])->orderBy('position', 'ASC')->get();
        return view('admin.category.order', compact('categories'));
    }

    public function chnageOrder(Request $request){
        foreach($request['cat_id'] as $position => $cat){
            $category = Category::findorfail($cat);
            $data['position'] = $position + 1;             
            $category->update($data);   
        }

        return redirect('admin/category')->withType('success')->withMessage('Category Order Updated');
    }
}
