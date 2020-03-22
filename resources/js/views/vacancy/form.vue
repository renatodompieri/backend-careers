<template>
    <form @submit.prevent="proceed" @keydown="vacancyForm.errors.clear($event.target.name)">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="">{{trans('vacancies.title')}}</label>
                    <input class="form-control" type="text" value="" v-model="vacancyForm.title" name="title"
                           :placeholder="trans('vacancies.title')">
                    <show-error :form-name="vacancyForm" prop-name="title"></show-error>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="">{{trans('vacancies.description')}}</label>
                    <autosize-textarea v-model="vacancyForm.description" rows="1" name="description"
                                       :placeholder="trans('vacancies.description')"></autosize-textarea>
                    <show-error :form-name="vacancyForm" prop-name="description"></show-error>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="">{{trans('vacancies.salary')}}</label>
                    <input class="form-control" type="text" value="" v-model.lazy="vacancyForm.salary" v-money="money"
                           name="salary" :placeholder="trans('vacancies.salary')">
                    <show-error :form-name="vacancyForm" prop-name="salary"></show-error>
                </div>
            </div>
            <div class="col-12 col-md-10">
                <div class="form-group">
                    <label for="">{{trans('vacancies.address')}}</label>
                    <input class="form-control" type="text" value="" v-model="vacancyForm.address"
                           name="address" :placeholder="trans('vacancies.address')">
                    <show-error :form-name="vacancyForm" prop-name="address"></show-error>
                </div>
            </div>
            <div class="col-12 col-md-2 col-sm-6">
                <div class="form-group">
                    <label for="">{{trans('vacancies.number')}}</label>
                    <input class="form-control" v-mask="'#####'" type="text" value="" v-model="vacancyForm.number"
                           name="number"
                           :placeholder="trans('vacancies.number')">
                    <show-error :form-name="vacancyForm" prop-name="state"></show-error>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="">{{trans('vacancies.complement')}}</label>
                    <input class="form-control" type="text" value="" v-model="vacancyForm.complement" name="complement"
                           :placeholder="trans('vacancies.complement')">
                    <show-error :form-name="vacancyForm" prop-name="state"></show-error>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="">{{trans('vacancies.city')}}</label>
                    <input class="form-control" type="text" value="" v-model="vacancyForm.city" name="city"
                           :placeholder="trans('vacancies.city')">
                    <show-error :form-name="vacancyForm" prop-name="city"></show-error>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="">{{trans('vacancies.state')}}</label>
                    <input class="form-control" type="text" value="" v-mask="'AA'" v-model="vacancyForm.state"
                           name="state"
                           :placeholder="trans('vacancies.state')">
                    <show-error :form-name="vacancyForm" prop-name="state"></show-error>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="">{{trans('vacancies.zipcode')}}</label>
                    <input class="form-control" type="text" value="" v-mask="'##.###-###'" v-model="vacancyForm.zipcode"
                           name="zipcode"
                           :placeholder="trans('vacancies.zipcode')">
                    <show-error :form-name="vacancyForm" prop-name="zipcode"></show-error>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-info waves-effect waves-light pull-right">
            <span v-if="id">{{trans('general.update')}}</span>
            <span v-else>{{trans('general.save')}}</span>
        </button>
        <router-link to="/vacancies" class="btn btn-danger waves-effect waves-light pull-right m-r-10" v-show="id">
            {{trans('general.cancel')}}
        </router-link>
        <button v-if="!id" type="button" class="btn btn-danger waves-effect waves-light pull-right m-r-10"
                @click="$emit('cancel')">{{trans('general.cancel')}}
        </button>
    </form>
</template>


<script>
    import autosizeTextarea from '../../components/autosize-textarea'

    export default {
        components: {autosizeTextarea},
        data() {
            return {
                vacancyForm: new Form({
                    title: '',
                    description: '',
                    address: '',
                    complement: '',
                    number: '',
                    city: '',
                    state: '',
                    salary: '',
                    zipcode: ''
                }),
                money: {
                    decimal: ',',
                    thousands: '.',
                    prefix: '$ ',
                    suffix: ' / ano',
                    precision: 2
                }

            };
        },
        props: ['id'],
        mounted() {
            if (this.id)
                this.getVacancy();
        },
        methods: {
            proceed() {
                if (this.id)
                    this.updateVacancy();
                else
                    this.storeVacancy();
            },
            storeVacancy() {
                this.vacancyForm.post('/api/vacancies')
                    .then(response => {
                        toastr.success(response.message);
                        this.$emit('completed')
                    })
                    .catch(error => {
                        helper.showErrorMsg(error);
                    });
            },
            getVacancy() {
                axios.get('/api/vacancies/' + this.id)
                    .then(response => response.data)
                    .then(response => {
                        this.vacancyForm.title = response.title;
                        this.vacancyForm.description = response.description;
                        this.vacancyForm.address = response.address;
                        this.vacancyForm.complement = response.complement;
                        this.vacancyForm.number = response.number;
                        this.vacancyForm.city = response.city;
                        this.vacancyForm.salary = response.salary;
                        this.vacancyForm.state = response.state;
                        this.vacancyForm.zipcode = response.zipcode;
                    })
                    .catch(error => {
                        helper.showDataErrorMsg(error);
                        this.$router.push('/vacancies');
                    });
            },
            updateVacancy() {
                this.vacancyForm.patch('/api/vacancies/' + this.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.$router.push('/vacancies');
                    })
                    .catch(error => {
                        helper.showErrorMsg(error);
                    });
            }
        }
    }
</script>
