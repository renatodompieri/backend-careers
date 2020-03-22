<template>
    <div class="row align-items-center">
        <div class="col-12 col-sm-8 offset-sm-2">
            <div class="card">

                <div class="card-body p-4">
                    <h4 class="card-title m-t-10">{{vacancy.title}}
                    <button @click="$router.push('/')" class="btn btn-info btn-sm pull-right m-r-10"><i class="fas fa-home"></i> <span
                        class="d-none d-sm-inline">{{trans('general.back')}}</span></button>
                    </h4>
                        <button type="button" class="btn btn-block btn-success">
                            <i class="fas fa-check"></i> {{trans('vacancies.apply')}}
                        </button>
                </div>

                <div class="card-body border-top p-4">
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">{{trans('vacancies.description')}}</small>
                            <h6>{{vacancy.description}}</h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">{{trans('vacancies.salary')}}</small>
                            <h6>{{vacancy.salary}}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">{{trans('vacancies.status')}}</small>
                            <h6>{{ getStatus() }}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">{{trans('vacancies.address')}}</small>
                            <h6>{{vacancy.address}}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">{{trans('vacancies.complement')}}</small>
                            <h6>{{vacancy.complement}}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">{{trans('vacancies.number')}}</small>
                            <h6>{{vacancy.number}}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">{{trans('vacancies.city')}}</small>
                            <h6>{{vacancy.city}}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">{{trans('vacancies.state')}}</small>
                            <h6>{{vacancy.state}}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">{{trans('vacancies.zipcode')}}</small>
                            <h6>{{vacancy.zipcode}}</h6>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                id: this.$route.params.id,
                vacancy: {
                    title: '',
                    description: '',
                    address: '',
                    complement: '',
                    number: '',
                    city: '',
                    state: '',
                    salary: '',
                    zipcode: ''
                }
            }
        },
        methods: {
            getVacancy() {
                axios.get('/api/vacancies/' + this.id)
                    .then(response => response.data)
                    .then(response => {
                        this.vacancy.title = response.title;
                        this.vacancy.description = response.description;
                        this.vacancy.address = response.address;
                        this.vacancy.complement = response.complement;
                        this.vacancy.number = response.number;
                        this.vacancy.city = response.city;
                        this.vacancy.salary = response.salary;
                        this.vacancy.state = response.state;
                        this.vacancy.zipcode = response.zipcode;
                    })
                    .catch(error => {
                        helper.showDataErrorMsg(error);
                        this.$router.push('/');
                    });
            },
            getStatus() {
                return (this.vacancy.status == 'open') ? i18n.vacancies.open : i18n.vacancies.closed;
            }
        },
        mounted() {
            if (this.id)
                this.getVacancy();
        }
    }
</script>
