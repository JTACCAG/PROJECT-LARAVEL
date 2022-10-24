<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\UserLimitController;
use SebastianBergmann\CodeCoverage\Report\Xml\Tests;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $UserLimit = new UserLimitController();
        if(!$UserLimit->verifyLimit('createCourse', auth()->user()->id)){
            return response()->json([
                "message" => "query limit exceeded"
            ], 400);
        }
        $UserLimit->create('createCourse', auth()->user()->id);
        $course = new Course();
        if($request->hasFile('file')) {
            $file = $request->file('file');
            $allowedFileTypes = config('app.allowedFileTypes');
            $maxFileSize = config('app.maxFileSize');
            $rules = [
                'file' => 'required|mimes:'.$allowedFileTypes.'|max:'.$maxFileSize
            ];
            $validator= Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $request->file('file')->storeAs('public/file', $fileNameToStore);
        } else {
            return response()->json([
                "message" => "file is null or empty"
            ], 400);
        }
        $course->filename = $filename;
        $course->extension = $extension;
        $course->fileNameToStore = $fileNameToStore;
        $course->path = $path;
        $course->save();
        return response()->json([
            "status" => 1,
            "message" => "Course enrolled successfully"
        ]);
    }

    public function createMassive(Request $request){
        $UserLimit = new UserLimitController();
        if(!$UserLimit->verifyLimit('createCourses', auth()->user()->id)){
            return response()->json([
                "message" => "query limit exceeded"
            ], 400);
        }
        $UserLimit->create('createCourses', auth()->user()->id);
        if(!$request->hasFile('files')) {
            return response()->json(['upload_file_not_found'], 400);
        }
     
        $allowedFileTypes = config('app.allowedFileTypes');
        $maxFileSize = config('app.maxFileSize');
        $rules = [
            // 'files' => 'required|mimes:'.$allowedFileTypes.'|max:'.$maxFileSize
        ];
        $count = count($request->file('files')) - 1;
        foreach(range(0, $count) as $index) {
            $rules['files.' . $index] = 'required|mimes:'.$allowedFileTypes.'|max:'.$maxFileSize;
        }
        $validator= Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ) , 400);
        }
        $files = $request->file('files'); 
        $errors = [];
        foreach ($files as $file) {
            $course = new Course();
            $filenameWithExt = $file->getClientOriginalName();
            $course->filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $course->extension = $file->getClientOriginalExtension();
            $course->fileNameToStore = $course->filename.'_'.time().'.'.$course->extension;
            $course->path = $file->storeAs('public/file', $course->fileNameToStore);
            $course->save();
        }
        return response()->json([
            "status" => 1,
            "message" => "Courses uploaded successfully"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $UserLimit = new UserLimitController();
        if(!$UserLimit->verifyLimit('showCourses', auth()->user()->id)){
            return response()->json([
                "message" => "query limit exceeded"
            ], 400);
        }
        $UserLimit->create('showCourses', auth()->user()->id);
        $courses = Course::all();
        return response()->json($courses);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroyLogic(Request $request)
    {
        $UserLimit = new UserLimitController();
        if(!$UserLimit->verifyLimit('deleteLogicCourse', auth()->user()->id)){
            return response()->json([
                "message" => "query limit exceeded"
            ], 400);
        }
        $UserLimit->create('deleteLogicCourse', auth()->user()->id);
        $id = $request->id;
        $course = Course::find($id);
        $course->delete();
        return response()->json($course);
    }

    public function destroyPhysical(Request $request)
    {
        $UserLimit = new UserLimitController();
        if(!$UserLimit->verifyLimit('deletePhysicalCourse', auth()->user()->id)){
            return response()->json([
                "message" => "query limit exceeded"
            ], 400);
        }
        $UserLimit->create('deletePhysicalCourse', auth()->user()->id);
        $id = $request->id;
        $course = Course::find($id);
        $course->delete();
        if (File::delete(storage_path().'//app//'.$course->path)) {
            // file was successfully deleted
            return response()->json($course);
        } else {
            // there was a problem deleting the file
            return response()->json($course,400);
        }
    }
}
