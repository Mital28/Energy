<?php

namespace Energy\Api\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Energy\Models\User;
use Energy\Models\NominationLng;
use Energy\Api\Repositories\NominationLngRepository;
use DB;
use Carbon\Carbon;

class NominationLngController extends Controller
{
    public function __construct(){
        $this->nomLngObj = new NominationLngRepository();
    }
    

    /**
     * [getNominationLngList description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getNominationLngList(Request $request)
    { 
        
        $userType = $request->userType;
        $userId = $request->userId ;
        $noOfPage = $request->noofRecord;
        $selected_date=$request->selectedDate;
        $date =Carbon::createFromFormat('d-m-Y',$selected_date)->format('Y-m-d');

        $nomination_lng_list=$this->nomLngObj->getNominationLngList($userType,$noOfPage,$userId,$date);
        if($nomination_lng_list)
        {
            return ['code' => 200 ,'data'=>$nomination_lng_list,'message'=>'Getting list successfully.'];
        }
        else
        {
            return ['code'=> 300 ,'data'=>'','message'=>'Something went wrong'];
        }
       
    }

    /**
     * [createNominationLng description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function createNominationLng(Request $request)
    {
        $add_lng=$this->nomLngObj->createNominationLng($request);
        if($add_lng)
        {
            return $add_lng;
        }
        else
        {
            return ['code'=> 300 ,'data'=>'','message'=>'Something went wrong'];
        }
    }

    /**
     * [editNominationLng description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function editNominationLng(Request $request)
    {
        $edit_lng=$this->nomLngObj->editNominationLng($request);
        if($edit_lng)
        {
            return $edit_lng;
        }
        else
        {
            return ['code'=> 300 ,'data'=>'','message'=>'Something went wrong'];
        }
    }

    /**
     * [getNominationLngDetailsById description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getNominationLngDetailsById(Request $request)
    {
        $id=$request->id;
        $nomination_lng_details=$this->nomLngObj->getNominationLngDetailsById($id);
        if($nomination_lng_details)
        {
            return ['code' => 200 ,'data'=>$nomination_lng_details,'message'=>'Getting details successfully.'];
        }
        else
        {
            return ['code'=> 300 ,'data'=>'','message'=>'Something went wrong'];
        }
    }

    /**
     * [deleteNominationLngById description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function deleteNominationLngById(Request $request)
    {
        $id=$request->id;
        $delete=$this->nomLngObj->deleteNominationLngById($id);
        if($delete)
        {
            return ['code' => 200 ,'data'=>$delete,'message'=>'Nomination LNG deleted successfully.'];
        }
        else
        {
            return ['code'=> 300 ,'data'=>'','message'=>'Something went wrong'];
        }
    }

    /**
    *  Approve Qty For truck Load
    *  Description : approve valide qty for truck load
    *
    *  Auth : Mital Sharma
    **/

    public function approveNominationLngById(Request $request){

        $data = $request->data['data'];

        $approve=$this->nomLngObj->approveNominationLngById($data);
        if($approve == 1)
        {
            return ['code' => 200 ,'data'=>$approve,'message'=>'Nomination LNG approved successfully.'];
        }
        else
        {
            return ['code'=> 300 ,'data'=>'','message'=>'Something went wrong'];
        }

    }

     /**
    *  Reject Qty For truck Load
    *  Description : reject valide qty for truck load
    *
    *  Auth : Mital Sharma
    **/
    public function rejectNominationLngById(){

        $data = $request->data['data'];

        $approve=$this->nomLngObj->rejectNominationLngById($data);
        if($approve == 1)
        {
            return ['code' => 200 ,'data'=>$approve,'message'=>'Nomination LNG reject successfully.'];
        }
        else
        {
            return ['code'=> 300 ,'data'=>'','message'=>'Something went wrong'];
        }
    }

   


   
}
