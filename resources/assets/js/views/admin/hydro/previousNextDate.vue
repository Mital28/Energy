<template>

	<section class="content">
        <div class="">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button class="btn btn-info" @click="getPrevoiusDate()">Previous Day</button> <button class="btn btn-info"  @click="getNextDate()">Next Day</button>
                </div>
            </div>
        </div>
        <br/>
		  
	</section>
</template>
	
<script >
    import User from '../../../api/users.js';
    import moment from 'moment';

export default {
    name: "",
    data() {
        return {
            'selectedDate' : moment().format('DD-MM-YYYY'),
            'disabledDate':  moment().add(1,'days').format('DD-MM-YYYY'),
        }
    },
    mounted: function() {
        let vm=this;
        vm.initData();
        vm.$root.$emit('changeDashbordDate',vm.selectedDate);
        vm.$root.$emit('setDate',vm.selectedDate);
            vm.$store.dispatch('setSelectedDate',vm.selectedDate);
        
    },
    components: {
           
    },
    methods: {
        initData()
        {
          let vm=this;
          let nomination_page=vm.$store.state.Nomination.nominationPage;
         
          //if(nomination_page=='LIST')
          //{
              let nDate=vm.$store.state.Nomination.nominationDate;
              if(nDate!="" && nDate!=null)
              {
                  vm.selectedDate=nDate;
              }
          //}
        },
        getPrevoiusDate(){
            let vm=this;
            let prevoiusDay = moment(vm.selectedDate,'DD-MM-YYYY').add(-1,'days').format('DD-MM-YYYY');
            vm.selectedDate =  prevoiusDay;
            toastr.success('Your selected date is : '+vm.selectedDate+'.', 'Dashboard', {timeOut: 1500});
            vm.$root.$emit('changeDashbordDate',vm.selectedDate);
            vm.$root.$emit('setDate',vm.selectedDate);
            vm.$store.dispatch('setSelectedDate',vm.selectedDate);
            

        },
        getNextDate(){
            let vm=this;
            let nextDay = moment(vm.selectedDate,'DD-MM-YYYY').add(1,'days').format('DD-MM-YYYY');
            vm.selectedDate =  nextDay;
             toastr.success('Your selected date is : '+vm.selectedDate+'.', 'Dashboard', {timeOut: 1500});
            vm.$root.$emit('changeDashbordDate',vm.selectedDate);
            vm.$root.$emit('setDate',vm.selectedDate);
            vm.$store.dispatch('setSelectedDate',vm.selectedDate);
        },
    },
    
}

</script>