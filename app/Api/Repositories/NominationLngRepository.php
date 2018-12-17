<?php
namespace Energy\Api\Repositories;
use Carbon\Carbon;
use DB;
use Energy\Models\NominationLng;
use Energy\Models\TruckDetails;
use Energy\Models\Setting;
use Energy\Api\Repositories\NotificationRepository;
use Excel;
use File;
use Energy\Api\Repositories\UserRepository;
use Energy\Api\Repositories\SettingRepository;
use Auth;





 class NominationLngRepository 
 {
    public function __construct(){
        $this->userObj = new UserRepository(); 
        $this->confObj = new SettingRepository(); 
    }
   
 	/**
 	 * [getNominationLngList description]
 	 * @param  [type] $userType [description]
 	 * @param  [type] $noOfPage [description]
 	 * @param  [type] $userId   [description]
 	 * @return [type]           [description]
 	 */
    public function getNominationLngList($userType,$noOfPage,$userId,$date)
        {
         
        if($userType==2)
        {
             $list= NominationLng::join('truck_details', function ($join) {
                $join->on('truck_details.id', '=', 'nomination_lng.truck_details_id');
            })
             ->whereDate('nomination_lng.lngDate',$date)
             ->where('nomination_lng.buyer_id',$userId)
             ->select('nomination_lng.*','nomination_lng.id as nId','truck_details.truck_no','truck_details.truck_company')
             ->orderBy('nomination_lng.created_at','desc')
             ->paginate($noOfPage);

        }
        else if($userType==3)
        {
             $list= NominationLng::join('truck_details', function ($join) {
                $join->on('truck_details.id', '=', 'nomination_lng.truck_details_id');
            })->join('users',function($join){
                $join->on('users.id','=','nomination_lng.buyer_id');
            })
             ->whereDate('nomination_lng.lngDate',$date)
             ->whereIn('nomination_lng.lng_status',['pending','approved'])
             ->select('nomination_lng.*','users.first_name','users.last_name','nomination_lng.id as nId','truck_details.truck_no','truck_details.truck_company')

             ->orderBy('nomination_lng.buyer_id','ASC')
             ->paginate($noOfPage);
        }
       
        return $list;
    }


        public function getSuppliedLngList($userType,$noOfPage,$userId,$date)
        {
            // dd($userType,$noOfPage,$userId,$date);
        if($userType==2)
        {
             $list= NominationLng::with('truckDetail')
             ->whereDate('nomination_lng.lngDate',$date)
             ->where('nomination_lng.buyer_id',$userId)
             // ->select('nomination_lng.*','nomination_lng.id as nId','truck_details.truck_no','truck_details.truck_company')
             ->orderBy('nomination_lng.created_at','desc')
             ->paginate($noOfPage);

        }
        else if($userType==3)
        {
             $list= NominationLng::with('truckDetail','buyerDetail')
             ->whereDate('nomination_lng.lngDate',$date)
             ->orderBy('nomination_lng.lngTime','desc')
             ->paginate($noOfPage);
             // ->get();
        }
        
        return $list;
    }

    public function saveTruckLoading($data)
    {
        // dd($data);
        $tare_weight = $data['tare_weight'] ? $data['tare_weight'] : '';
        $gross_weight = $data['gross_weight'] ? $data['gross_weight'] : '';

        // return Nomination::where('id',$id)->first();
        return $truckLoading =NominationLng::where('id', $data['nominationLngId'])->update([
            'tare_weight' => $tare_weight,
            'gross_weight' => $gross_weight
        ]);
       
    }

    public function createNominationLng($request)
    {
            $formData=$request->nominationLngData;
            // dd($request->all());
            // $lngDate=$request->all()['nominationLngData']['lngDate'];
            $lngDate=Carbon::createFromFormat('d-m-Y', $request->all()['nominationLngData']['lngDate'])->format('Y-m-d');
            $checkNominationLng=$this->checkNominationLngWithTime($lngDate,$formData['buyer_id'],$formData['lngTime']);
            if($checkNominationLng>0)
            {
                return ['code'=> 300 ,'data'=>'','message'=>'Truck is already added for this day.'];
            }
            $checkTruckSafeQuantity=$this->checkTruckSafeQuantity($formData['truck_details_id']);
            if($checkTruckSafeQuantity<$formData['quantity'])
            {
                return ['code'=> 300 ,'data'=>'','message'=>'Safe quantity lng is exceed.The Safe quantity of lng for this truck is '.$checkTruckSafeQuantity.'.'];
            }
            $nominationLng=new NominationLng;
            $nominationLng->buyer_id=$formData['buyer_id'];
            $nominationLng->truck_details_id=$formData['truck_details_id'];
            $nominationLng->lngTime=$formData['lngTime'];
            $nominationLng->lngDate=$lngDate;

            $nominationLng->quantity=$formData['quantity'];
            $nominationLng->tare_weight='0.00';
            $nominationLng->gross_weight='0.00';
            $nominationLng->save();
            if($nominationLng->id!=0)
            {
                return ['code'=> 200 ,'data'=>$nominationLng->id,'message'=>'Record successfully added.'];
            }
            else
            {
                return ['code'=> 300 ,'data'=>'','message'=>'Record not added.'];
            }
    }

    public function checkNominationLngWithTime($lDate,$buyer_id,$time)
    {
        // dd($lDate,$buyer_id,$time);
        $list=NominationLng::whereDate('lngDate',$lDate)
        ->where('buyer_id',$buyer_id)
        ->where('lngTime',$time)
        ->get();
        return count($list);
    }
    /**
     * [checkNominationLng description]
     * @param  [type] $truck_details_id [description]
     * @param  [type] $lDate            [description]
     * @param  [type] $buyer_id         [description]
     * @return [type]                   [description]
     */
    public function checkNominationLng($truck_details_id,$lDate,$buyer_id)
    {
        $list=NominationLng::where('truck_details_id',$truck_details_id)
        ->whereDate('lngDate',$lDate)
        ->where('buyer_id',$buyer_id)
        ->get();
        return count($list);
    }

    /**
     * [checkTruckSafeQuantity description]
     * @param  [type] $t_id [description]
     * @return [type]       [description]
     */
    public function checkTruckSafeQuantity($t_id)
    {
        $list=TruckDetails::where('id',$t_id)->first();
        return $list->safe_quantity_lng;
    }

    /**
     * [editNominationLng description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function editNominationLng($request)
    {
        $formData=$request->nominationLngData;
            $checkTruckSafeQuantity=$this->checkTruckSafeQuantity($formData['truck_details_id']);
            if($checkTruckSafeQuantity<$formData['quantity'])
            {
                return ['code'=> 300 ,'data'=>'','message'=>'Safe quantity lng is exceed.The Safe quantity of lng for this truck is '.$checkTruckSafeQuantity.'.'];
            }
            $id=$formData['nominationLngId'];
            $nominationLng=NominationLng::findOrFail($id);
            $nominationLng->truck_details_id=$formData['truck_details_id'];
            $nominationLng->lngTime=$formData['lngTime'];
            //$nominationLng->lngDate=$formData['lngDate'];
            $nominationLng->quantity=$formData['quantity'];
            $nominationLng->save();
            if($nominationLng->id!=0)
            {
                return ['code'=> 200 ,'data'=>$nominationLng->id,'message'=>'Record successfully updated.'];
            }
            else
            {
                return ['code'=> 300 ,'data'=>'','message'=>'Record not updated.'];
            }
    }

    /**
     * [getNominationLngDetailsById description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getNominationLngDetailsById($id)
    {
        $details=NominationLng::with('truckDetail')->where('id',$id)->first();
        return $details;
    }

    public function deleteNominationLngById($id)
    {
        $presp_id = NominationLng::find( $id );
        $presp_id ->delete();
        return $id;
    }

    /**
    * update approve qty and status in related tabel
    *
    *  Auth : Mital Sharma
    **/

    public function approveNominationLngById($data){
        $result = 0;
        $totalApproveQty = 0;
        $availabelLimit  = '';
        $availabelLimit = $this->confObj->getFieldValue('lng_supply_daily_limit','numeric');

        if(!empty($data)){

            foreach($data as $key){
                 $totalApproveQty = (int)$totalApproveQty + (int)$key['quantity'] ;  
                NominationLng::where('id',$key['id'])->update(array('lng_status' => 'approved','approve_quantity' => $key['quantity']));

            }
            if( $totalApproveQty <= $availabelLimit){
                foreach($data as $key){
               
                NominationLng::where('id',$key['id'])->update(array('lng_status' => 'approved','approve_quantity' => $key['quantity']));

                $result = 1;
                }
            }else{
                 $result = 0;
            }
        }

        return $result;

    }


    

    /**
    * reject qty and status in related tabel
    *
    *  Auth : Mital Sharma
    **/

    public function rejectNominationLngById($data,$rid){

        $result = 0;
        if(!empty($rid)){

           // foreach($data as $key){
                NominationLng::where('id',$rid)->update(array('lng_status' => 'rejected'));
            //        $addedBy  = Auth::user()->id;
            // $dataId = NominationLng::id;
            // $qty    = $form_data['quantity'];
            // $type   = 'add_notification';
            // $d_format=Carbon::createFromFormat('d-m-Y', $nom->date)->format('jS M Y');
            // $dataText = ucwords($userName).'  has added nomination to '.number_format($qty,2).' MMBTU for '.$d_format;
            // $title  = 'Nomination request added';
            // $dataTable = 'nomination_request';
            // $new_date=Carbon::createFromFormat('d-m-Y', $nom->date)->format('Y-m-d');
            // $nomination_date=$new_date;
            // //echo $nomination_date;exit;
            // $this->notificationObj = new NotificationRepository();
            // $this->notificationObj->insert($dataId,$type,$dataUserId,$dataText,$title,$dataTable,$addedBy,$nomination_date);
                $result = 1;
            //}
        }

        return $result;

    }

    /**
    *
    *
    *
    **/

    public function getLngBuyerRequestList($buyerId,$requestType,$typeInclude,$multipleType='no'){

        if($typeInclude == 'no'){
                if($multipleType == 'no'){
                    $list = NominationLng::select('nomination_lng.id as nId','nomination_lng.*','users.*')->where('nomination_lng.lng_status','!=',$requestType)->where('buyer_id',$buyerId)->join('users', function ($join) {
                    $join->on('users.id', '=', 'nomination_lng.buyer_id');
                            })->get();

                }
                if($multipleType == 'yes'){
                   $list = NominationLng::select('nomination_lng.id as nId','nomination_lng.*','users.*')->whereNotIn('nomination_lng.lng_status',$requestType)->where('buyer_id',$buyerId)->join('users', function ($join) {
                    $join->on('users.id', '=', 'nomination_lng.buyer_id');
                            })->get();  
                }
           
        }else if($typeInclude == 'yes'){

            $list = NominationLng::select('nomination_lng.id as nId','nomination_lng.*','users.*')->where('nomination_lng.lng_status','=',$requestType)->where('buyer_id',$buyerId)->join('users', function ($join) {
                $join->on('users.id', '=', 'nomination_lng.buyer_id');
            })->get();
        }else{

            $list = NominationLng::select('nomination_lng.id as nId','nomination_lng.*','users.*')->where('nomination_lng.buyer_id',$buyerId)->join('users', function ($join) {
                $join->on('users.id', '=', 'nomination_lng.buyer_id');
            })->get();
        }

        return $list;

    }

    public function updateRequeststatus($requestType,$nid){
        return NominationLng::where('id',$nid)->update(array('nomination_lng.lng_status' => $requestType));
    }

    
    
 }
?>
