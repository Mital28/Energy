<template>

<section>
    <section class="content-header mb-3">
            <div class="row">
                <div class="col-md-4 col-sm-5">
                    <h1><i class="fas fa-chart-bar"></i>Dashboard</h1> 
                </div>
                <div class="col-md-8 col-sm-7 text-right">
                     <ol class="breadcrumb">
                        
                    </ol>
                </div>
            </div>
    </section>
    <section>
        <previousNextDate></previousNextDate>
    </section>

	<section class="content">
        <!-- <div class="row">
            <div class="col-md-12 text-right">
                <a href="/nomination_list"> <button class="btn btn-warning">Nomination Request</button></a>
            </div>
        </div>
        <br/> -->
        
	<div class="row">
         <div class="col-sm-6 col-md-6 col-xl-3">
                <div class="flip">
                     <a  @click="nomination_lng()" title="Add Nomination LNG">
                        <div class="widget-bg-color-icon card-box front">
                            <div class="bg-icon float-left">
                               <i class="fas fa-charging-station"></i>
                            
                            </div>
                            <div class="text-right">
                            <h3><b>LNG Nomination</b></h3>
                            <h3 class="text-dark"><b>{{ buyerDashboardData.LngTotal }} Kg</b></h3>
                            <p>For Date:{{buyerDashboardData.requestDate}}</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                     </a>
                </div>
            </div>
            <!-- <div class="col-sm-6 col-md-6 col-xl-3">
             	<div class="flip">
                     <a href="#" @click="nomination_page()" title="Add Nomination">
                        <div class="widget-bg-color-icon card-box front">
                            <div class="bg-icon float-left">
                               <i class="fas fa-charging-station"></i>
                            
                            </div>
                            <div class="text-right">
                            <h3><b>Nomination</b></h3>
                            <h3 class="text-dark"><b>{{total_request}}</b></h3>
                            <p>For Date:{{selectedDashbordDate}}</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                     </a>
                </div>
            </div> -->
            <div class="col-sm-6 col-md-6 col-xl-3">
                <div class="flip">
                    <a >
                    <div class="widget-bg-color-icon card-box front">
                        <div class="bg-icon float-left">
                           <i class="fa fa-calendar-plus-o"></i> 
                        </div>
                        <div class="text-right">
                            <h3><b>Scheduled Quantity</b></h3>
                            <h3 class="text-dark"><b id="widget_count3">{{ buyerDashboardData.ApprovedLngTotal }} Kg</b></h3>
                            <p>For Date:{{buyerDashboardData.approvedDate}}</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    </a>  

            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-xl-3">
                <div class="flip">
                    <a >
                    <div class="widget-bg-color-icon card-box front">
                        <div class="bg-icon float-left">
                          <i class="fas fa-truck"></i>
                        </div>
                        <div class="text-right">
                            <h3><b>Truck Loading</b></h3>
                            <h3 class="text-dark"><b id="widget_count3">
                                {{buyerDashboardData.SuppliedQuantity}} Kg</b></h3>
                            <p>For Date:{{ buyerDashboardData.supplyDate }}</p>

                        </div>
                        <div class="clearfix"></div>
                    </div>
                    </a>
                    </div>
                </div>
        <div class="col-sm-6 col-md-6 col-xl-3">
            <div class="flip">
                <a >
                <div class="widget-bg-color-icon card-box front">
                    <div class="bg-icon float-left">
                        <!-- <i class="fa fa-credit-card text-blue"></i> -->
                        <i class="fas fa-money-check"></i>
                    </div>
                    <div class="text-right">

                        <h3><b><a href="/generate_buyer_invoice_lng">Invoice</a></b></h3>
                        <p>View Invoice</p>

                    </div>
                    <div class="clearfix"></div>
                </div>
                </a>
                </div>
            </div>
         
            </div>
            <div class="row">
                <div class="col-xl-6 col-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header panel-tabs buyer_chart1">
                                     <h3 class="card-title">Nomination</h3>
                                </div>
                                
                                 <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane  active" id="allocation">
                                            <div style="width: 50%;  margin: 20px auto 0 auto;">
                                               <!--  <canvas id="myChart" width="100px" height="100px"></canvas> -->
                                                <canvas id="myplotArea" width="100px" height="100px"></canvas>
                                               <!--  <button class="btn btn-success" @click="getPrevoiusDay()"><i class="fa fa-arrow-left fa-lg"></i></button>
                                               <button   class="btn btn-success" @click="getNextDay()"><i class="fa fa-arrow-right fa-lg"></i></button> -->

                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="profile">
                                            <div class="chart-container">
                                                <span class="">
                                                    <i class="ti-reload redraw-cart float-right set-animate"></i>
                                                </span>
                                                <canvas id="dashboard-chart1" width="800" height="300"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <timelineLng :userData=userData> </timelineLng>
            </div>

            <!-- /#right -->
            <div class="background-overlay"></div>
</section>
    </section>
</template>
	
<script >
    import User from '../../../api/users.js';
    import moment from 'moment';
    import Chart from 'chart.js';
    import timelineLng from './timelineLng.vue';
    import previousNextDate from './previousNextDate.vue';

export default {
    name: "dashboardBuyer",
    data() {
        return {
            formstate: {},

            'selectedDate' : moment().format('DD-MM-YYYY'),
            'userData' : {
                    'userType' : this.$store.state.Users.userDetails.user_type,
                    'userId' : this.$store.state.Users.userDetails.id,
            },
            'total_request':'',
            'total_approved':'',
            'total_supplied':'',
            'nominationData' : [], 
            'selected_date' :'',
            'selectedDashbordDate' : moment().format('DD-MM-YYYY'),
            'buyerDashboardData': ''
        }
    },
    created: function(){
        this.$root.$on('changeDashbordDate',this.changeDashbordDate);
    },
    components: {
            timelineLng,
            previousNextDate
    },
    mounted: function() {
        let vm=this;
        vm.selectedDashbordDate = this.$store.state.selected_date;
       if(vm.$store.state.Users.userDetails.user_type != '2'){
              vm.$root.$emit('logout','You are not authorize to access this page'); 
          }
        vm.chartData();

        User.getBuyerNominationLngTotals(vm.userData.userId,vm.selectedDashbordDate).then(
            (response) => {
                this.buyerDashboardData = response.data.data;
            },
            (error) => {

            }
        );
    },
    methods: {
        nomination_lng()
        {
            let vm=this;
            /*vm.$store.dispatch('SetNominationDate',''); 
            vm.$store.dispatch('SetNominationPage','');
            vm.$store.dispatch('SetNominationId','');*/
            vm.$router.push({'name':'nomination_lng_list'});
        },
        nomination_page()
        {
            let vm=this;
            vm.$store.dispatch('SetNominationDate',''); 
            vm.$store.dispatch('SetNominationPage','');
            vm.$store.dispatch('SetNominationId','');
            vm.$router.push({'name':'nomination_list'});
        },
        changeDashbordDate(selectDate)
        {
            let vm=this;
            vm.selectedDashbordDate=selectDate;
            vm.getTotalSuppliedQuantityByBuyer(vm.selectedDashbordDate,vm.userData.userId);
            vm.getTotalRequestedQuantity(vm.selectedDashbordDate,vm.userData.userId);
            vm.getTotalApprovedQuantityByBuyer(vm.selectedDashbordDate,vm.userData.userId);
            vm.getBuyerDetailsById(vm.selectedDashbordDate,vm.userData.userId);
            
            User.getBuyerNominationLngTotals(vm.userData.userId,vm.selectedDashbordDate).then(
                (response) => {
                    this.buyerDashboardData = response.data.data;
                },
                (error) => {

                }
            );  
        },
        getPrevoiusDay(){
            let vm=this;
            let prevoiusDay = moment(vm.selectedDate,'DD-MM-YYYY').add(-1,'days').format('DD-MM-YYYY');
             vm.selectedDate =  prevoiusDay;
            vm.getBuyerDetailsById(vm.selectedDate,vm.userData.userId);
        },
        getNextDay(){
            let vm=this;
              let nextDay = moment(vm.selectedDate,'DD-MM-YYYY').add(1,'days').format('DD-MM-YYYY');
             vm.selectedDate =  nextDay;
          
            vm.getBuyerDetailsById(vm.selectedDate,vm.userData.userId);

        },
        getTotalSuppliedQuantityByBuyer(selected_date,buyer_id)
       {
            let vm=this;
             User.getTotalSuppliedQuantityByBuyer(selected_date,buyer_id).then(
                 (response) => {
                    let supply=response.data.data;
                    if(supply!=null && supply!='' && supply!=0)
                    {
                        vm.total_supplied=supply;
                    }
                    else
                    {
                         vm.total_supplied=0;
                    }
                    
                },
                (error) => {
                },
            );
       },
        getTotalRequestedQuantity(selected_date,buyer_id)
       {
            let vm=this;
             User.getTotalRequestedQuantity(selected_date,buyer_id).then(
                 (response) => {
                    let request=response.data.data;
                    if(request!=null && request!='' && request!=0)
                    {
                        vm.total_request=request;
                    }
                    else
                    {
                         vm.total_request=0;
                    }
                    
                },
                (error) => {
                },
            );
       },
        getBuyerDetailsById(date,buyerId){
            let curDate = date;
            let nData = {'date':curDate};
            let vm =this;
            User.getNominationDetailsByDateAndId(curDate,buyerId).then(
                 (response) => {
                    // return false;
                    let nominationData  = [];
                   // $.each(response.data.data, function(key, value) {
                    if(response.data.code == 200){
                        let data =  {
                            'buyer_id':response.data.data.buyer_id,
                            'buyer_name':response.data.data.first_name,
                            'quantity_required':response.data.data.quantity_required,
                            'approved_quantity':response.data.data.approved_quantity,
                            'supplied_quantity':response.data.data.supplied_quantity,
                            'date':response.data.data.date
                        }
                        nominationData.push(data);
                        vm.nominationData = nominationData;
                     }
                      if(response.data.code == 300){
                        vm.nominationData={};
                        //toastr.error('No data available.', 'Live Feed', {timeOut: 5000});
                      }
                      vm.chartData();
                },
                (error) => {
                },
            );
        },
       getTotalApprovedQuantityByBuyer(selected_date,buyer_id)
       {
            let vm=this;
             User.getTotalApprovedQuantityByBuyer(selected_date,buyer_id).then(
                 (response) => {
                    let approve=response.data.data;
                    if(approve!=null && approve!='' && approve!=0)
                    {
                        vm.total_approved=approve;
                    }
                    else
                    {
                         vm.total_approved=0;
                    }
                },
                (error) => {
                },
            );
       },
       chartData()
       {
            let vm=this;
            var randomScalingFactor = function() {
                return Math.round(Math.random() * 100);
            };
             var ctx1 = document.getElementById("myplotArea").getContext("2d");
           
               // window.myPie1 = new Chart(ctx1, config1);
            jQuery('.js-loader').removeClass('d-none')

            setTimeout(function(){
                var config1 = {
                type: 'bar',
                data: {
                    datasets: [
                    {
                        // data: [
                        //     100,200,300
                        // ],
                        data: [
                            vm.buyerDashboardData.LngTotal,
                            vm.buyerDashboardData.ApprovedLngTotal,
                            vm.buyerDashboardData.SuppliedQuantity
                        ],
                        // data: [
                        //     100,200,300
                        // ],
                        backgroundColor: [
                            '#82be00',
                            '#004696',
                            '#173f6d',
                            
                        ],
                        label: 'LNG Allocation'
                    }
                    ],
                    labels: [
                        'Requested',
                        'Scheduled',
                        'Supplied',
                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                            position: 'top',
                        },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            };
                window.myPie1 = new Chart(ctx1, config1);
            jQuery('.js-loader').addClass('d-none')

            },3000);       }

    }, 
}

</script>