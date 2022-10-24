<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserLimit;
use Illuminate\Http\Request;

class UserLimitController extends Controller
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
    public function create(string $request, string $idUser)
    {
        $userLimit = new UserLimit();
        $userLimit->user_id = $idUser;
        $userLimit->request = $request;
        $userLimit->save();
        return $userLimit;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function verifyLimit(string $request, string $idUser)
    {
        $listUserLimit = UserLimit::
                                    where('request', $request)
                                    ->where('user_id', $idUser)
                                    ->orderByDesc('created_at')
                                    ->limit(3)->get()->toArray();
        if(count($listUserLimit)<3){
            return true;
        }
        $result = strtotime($listUserLimit[0]['created_at'])-strtotime($listUserLimit[2]['created_at']);
        if($result <=60){
            return false;
        }
        return true;

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserLimit  $userLimit
     * @return \Illuminate\Http\Response
     */
    public function show(UserLimit $userLimit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserLimit  $userLimit
     * @return \Illuminate\Http\Response
     */
    public function edit(UserLimit $userLimit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserLimit  $userLimit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserLimit $userLimit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserLimit  $userLimit
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserLimit $userLimit)
    {
        //
    }
}
