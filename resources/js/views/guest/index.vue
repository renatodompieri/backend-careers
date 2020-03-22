<template>
    <div>
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="card border-bottom">
                        <div class="card-body p-4">
                            <h4 class="card-title">{{trans('vacancies.search')}}
                                <sort-by class="pull-right" :order-by-options="orderByOptions"
                                         :sort-by="filterVacancyForm.sort_by"
                                         :order="filterVacancyForm.order"
                                         @updateSortBy="value => {filterVacancyForm.sort_by = value}"
                                         @updateOrder="value => {filterVacancyForm.order = value}"></sort-by>
                            </h4>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">{{trans('vacancies.title_or_description')}}</label>
                                        <input class="form-control" name="keyword"
                                               v-model="filterVacancyForm.keyword">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive" v-if="vacancies.total">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{trans('vacancies.title')}}</th>
                                        <th>{{trans('vacancies.salary')}}</th>
                                        <th>{{trans('vacancies.city')}}</th>
                                        <th>{{trans('vacancies.state')}}</th>
                                        <th class="table-option">{{trans('general.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="vacancy in vacancies.data">
                                        <td v-text="vacancy.title"></td>
                                        <td v-text="vacancy.salary"></td>
                                        <td v-text="vacancy.city"></td>
                                        <td v-text="vacancy.state"></td>

                                        <td class="table-option">
                                            <div class="btn-group">
                                                <button class="btn btn-info btn-sm"
                                                        v-tooltip="trans('vacancies.apply')"
                                                        @click="$router.push('/vacancies/' + vacancy.id)"><i
                                                        class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <module-info v-if="!vacancies.total" module="vacancies" title="module_info_title"
                                         description="module_info_description" icon="check-circle">
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
    import switches from 'vue-switches'
    import sortBy from '../../components/sort-by'

    export default {
        components: {switches, sortBy},
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
            if (!helper.featureAvailable('vacancy')) {
                helper.featureNotAvailableMsg();
                this.$router.push('/home');
            }

            this.getVacancies();
        },
        methods: {
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
            applyTo(vacancy) {
                return true;
            },
            getStatus(vacancy) {
                return (vacancy.status == 'open') ? ('<span class="label label-success">' + i18n.vacancies.open + '</span>') : ('<span class="label label-danger">' + i18n.vacancies.closed + '</span>');
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
