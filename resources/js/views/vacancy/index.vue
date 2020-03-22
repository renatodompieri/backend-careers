<template>
    <div>
        <div class="page-titles p-3 border-bottom">
            <h3 class="text-themecolor">{{trans('vacancies.vacancies_list')}}
                <span class="card-subtitle" v-if="vacancies">{{trans('general.total_result_found',{'count' : vacancies.total})}}</span>
                <span class="card-subtitle" v-else>{{trans('general.no_result_found')}}</span>

                <sort-by class="pull-right" :order-by-options="orderByOptions" :sort-by="filterVacancyForm.sort_by"
                         :order="filterVacancyForm.order" @updateSortBy="value => {filterVacancyForm.sort_by = value}"
                         @updateOrder="value => {filterVacancyForm.order = value}"></sort-by>
                <button class="btn btn-info btn-sm pull-right m-r-10" v-if="!showFilterPanel"
                        @click="showFilterPanel = !showFilterPanel"><i class="fas fa-filter"></i> <span
                        class="d-none d-sm-inline">{{trans('general.filter')}}</span></button>
                <button class="btn btn-info btn-sm pull-right m-r-10" v-if="vacancies.total && !showCreatePanel"
                        @click="showCreatePanel = !showCreatePanel"><i class="fas fa-plus"></i> <span
                        class="d-none d-sm-inline">{{trans('vacancies.add_new_vacancy')}}</span></button>
            </h3>
        </div>
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <transition name="fade">
                        <div class="card border-bottom" v-if="showCreatePanel">
                            <div class="card-body p-4">
                                <h4 class="card-title">{{trans('vacancies.add_new_vacancy')}}</h4>
                                <vacancy-form @completed="getVacancies"
                                              @cancel="showCreatePanel = !showCreatePanel"></vacancy-form>
                            </div>
                        </div>
                    </transition>

                    <transition name="fade">
                        <div class="card border-bottom" v-if="showFilterPanel">
                            <div class="card-body p-4">
                                <h4 class="card-title">{{trans('general.filter')}}</h4>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">{{trans('vacancies.keyword')}}</label>
                                            <input class="form-control" name="keyword"
                                                   v-model="filterVacancyForm.keyword">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <switches v-model="filterVacancyForm.status" theme="bootstrap"
                                                      color="success"></switches>
                                            {{trans('vacancies.show_closed')}}
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-danger btn-sm pull-right" v-if="showFilterPanel"
                                        @click="showFilterPanel = !showFilterPanel">{{trans('general.cancel')}}
                                </button>
                            </div>
                        </div>
                    </transition>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive" v-if="vacancies.total">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{trans('vacancies.title')}}</th>
                                        <th>{{trans('vacancies.status')}}</th>
                                        <th>{{trans('vacancies.salary')}}</th>
                                        <th>{{trans('vacancies.city')}}</th>
                                        <th>{{trans('vacancies.state')}}</th>
                                        <th class="table-option">{{trans('general.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="vacancy in vacancies.data">
                                        <td v-text="vacancy.title"></td>
                                        <td v-html="getStatus(vacancy)"></td>
                                        <td v-text="vacancy.salary"></td>
                                        <td v-text="vacancy.city"></td>
                                        <td v-text="vacancy.state"></td>

                                        <td class="table-option">
                                            <div class="btn-group">
                                                <button class="btn btn-info btn-sm"
                                                        v-tooltip="trans('vacancies.edit_vacancy')"
                                                        @click.prevent="editVacancy(vacancy)"><i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" :key="vacancy.id"
                                                        v-confirm="{ok: confirmDelete(vacancy)}"
                                                        v-tooltip="trans('vacancies.delete_vacancy')"><i
                                                        class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <module-info v-if="!vacancies.total" module="vacancies" title="module_info_title"
                                         description="module_info_description" icon="check-circle">
                                <div slot="btn">
                                    <button class="btn btn-info btn-md" v-if="!showCreatePanel"
                                            @click="showCreatePanel = !showCreatePanel"><i class="fas fa-plus"></i>
                                        {{trans('vacancies.add_new_vacancy')}}
                                    </button>
                                </div>
                            </module-info>
                            <pagination-record :page-length.sync="filterVacancyForm.page_length" :records="vacancies"
                                               @updateRecords="getVacancies"></pagination-record>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vacancyForm from './form'
    import switches from 'vue-switches'
    import sortBy from '../../components/sort-by'

    export default {
        components: {vacancyForm, switches, sortBy},
        data() {
            return {
                vacancies: {
                    total: 0,
                    data: []
                },
                filterVacancyForm: {
                    keyword: '',
                    status: false,
                    sort_by: 'created_at',
                    order: 'desc',
                    page_length: helper.getConfig('page_length')
                },
                orderByOptions: [
                    {
                        value: 'title',
                        translation: i18n.vacancies.title
                    },
                    {
                        value: 'description',
                        translation: i18n.vacancies.description
                    },
                    {
                        value: 'created_at',
                        translation: i18n.vacancies.date
                    },
                    {
                        value: 'status',
                        translation: i18n.vacancies.status
                    }
                ],
                showCreatePanel: false,
                showFilterPanel: false
            };
        },
        mounted() {
            if (!helper.hasPermission('access-vacancy')) {
                helper.notAccessibleMsg();
                this.$router.push('/home');
            }

            if (!helper.featureAvailable('vacancy')) {
                helper.featureNotAvailableMsg();
                this.$router.push('/home');
            }

            this.getVacancies();
        },
        methods: {
            hasPermission(permission) {
                return helper.hasPermission(permission);
            },
            getVacancies(page) {
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filterVacancyForm);
                axios.get('/api/vacancies?page=' + page + url)
                    .then(response => response.data)
                    .then(response => this.vacancies = response)
                    .catch(error => {
                        helper.showDataErrorMsg(error);
                    });
            },
            editVacancy(vacancy) {
                this.$router.push('/vacancies/' + vacancy.id + '/edit');
            },
            confirmDelete(vacancy) {
                return dialog => this.deleteVacancy(vacancy);
            },
            deleteVacancy(vacancy) {
                axios.delete('/api/vacancies/' + vacancy.id)
                    .then(response => response.data)
                    .then(response => {
                        toastr.success(response.message);
                        this.getVacancies();
                    }).catch(error => {
                    helper.showDataErrorMsg(error);
                });
            },
            getStatus(vacancy) {
                return (vacancy.status == 'open') ? ('<span class="label label-success">' + i18n.vacancies.open + '</span>') : ('<span class="label label-danger">' + i18n.vacancies.closed + '</span>');
            },
            toggleStatus(vacancy) {
                axios.post('/api/vacancies/' + vacancy.id + '/status')
                    .then(response => response.data)
                    .then(response => {
                        this.getVacancies();
                        toastr.success(response.message);
                    })
                    .catch(error => {
                        helper.showDataErrorMsg();
                    });
            }
        },
        watch: {
            filterVacancyForm: {
                handler(val) {
                    setTimeout(() => {
                        this.getVacancies();
                    }, 500)
                },
                deep: true
            }
        }
    }
</script>
