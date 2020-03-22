<template>
    <div>
        <div class="page-titles p-3 border-bottom">
            <h3 class="text-themecolor">{{trans('general.home')}}
                <button class="btn btn-danger btn-sm pull-right" @click.prevent="logout"><i class="fas fa-power-off"></i> <span class="d-none d-sm-inline">{{trans('auth.logout')}}</span></button>
                <button class="btn btn-info btn-sm right-sidebar-toggle pull-right m-r-10" v-tooltip="trans('user.user_preference')"><i class="fas fa-cog"></i></button>
            </h3>
        </div>
        <div class="container-fluid p-0">
            <div class="row" v-if="hasRole('admin')">
                <div class="col-12 col-sm-3">
                    <div class="card">
                        <div class="card-body px-3 pt-3">
                            <h4 class="card-title">{{trans('dashboard.period_registered_user',{period: trans('dashboard.total')})}}</h4>
                            <div class="text-right">
                                <h2 class="font-light m-b-0"><i class="fas fa-users fa-lg pull-right m-r-40"></i> <span class="pull-left">{{users}}</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="card">
                        <div class="card-body px-3 pt-3">
                            <h4 class="card-title">{{trans('dashboard.period_registered_user',{period: trans('dashboard.today')})}}</h4>
                            <div class="text-right">
                                <h2 class="font-light m-b-0"><i class="fas fa-users fa-lg pull-right m-r-40"></i> <span class="pull-left">{{today_registered_users}}</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="card">
                        <div class="card-body px-3 pt-3">
                            <h4 class="card-title">{{trans('dashboard.period_registered_user',{period: trans('dashboard.week')})}}</h4>
                            <div class="text-right">
                                <h2 class="font-light m-b-0"><i class="fas fa-users fa-lg pull-right m-r-40"></i> <span class="pull-left">{{weekly_registered_users}}</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="card">
                        <div class="card-body px-3 pt-3">
                            <h4 class="card-title">{{trans('dashboard.period_registered_user',{period: trans('dashboard.month')})}}</h4>
                            <div class="text-right">
                                <h2 class="font-light m-b-0"><i class="fas fa-users fa-lg pull-right m-r-40"></i> <span class="pull-left">{{monthly_registered_users}}</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-0 border-top">
                <div class="col-12 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="px-3 pt-3">
                                <h4 class="card-title">{{trans('activity.activity_log')}}
                                    <span class="card-subtitle" v-if="!activity_logs.length">{{trans('general.no_result_found')}}</span>
                                </h4>
                            </div>
                            <div class="table-responsive" v-if="activity_logs.length">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th v-if="hasRole('admin')">{{trans('user.user')}}</th>
                                            <th>{{trans('activity.activity')}}</th>
                                            <th class="table-option">{{trans('activity.date_time')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="activity_log in activity_logs">
                                            <td v-if="hasRole('admin')" v-text="activity_log.user.profile.first_name+' '+activity_log.user.profile.last_name"></td>
                                            <td>{{trans('activity.'+activity_log.activity,{activity: trans(activity_log.module+'.'+activity_log.module)})}}</td>
                                            <td class="table-option">{{activity_log.created_at | momentDateTime }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-sidebar">
            <div class="slimscrollright">
                <div class="rpanel-title"> 
                    {{trans('user.user_preference')}} 
                    <button class="btn btn-danger btn-sm right-sidebar-toggle pull-right m-r-10"><i class="fas fa-times"></i></button>
                </div>
                <div class="r-panel-body">
                    <form @submit.prevent="updatePreference" @keydown="preferenceForm.errors.clear($event.target.name)">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{trans('configuration.color_theme')}}</label>
                                    <select v-model="preferenceForm.color_theme" class="custom-select col-12">
                                      <option v-for="option in color_themes" v-bind:value="option.value">
                                        {{ option.text }}
                                      </option>
                                    </select>
                                    <show-error :form-name="preferenceForm" prop-name="color_theme"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{trans('configuration.direction')}}</label>
                                    <select v-model="preferenceForm.direction" class="custom-select col-12">
                                      <option v-for="option in directions" v-bind:value="option.value">
                                        {{ option.text }}
                                      </option>
                                    </select>
                                    <show-error :form-name="preferenceForm" prop-name="direction"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{trans('configuration.sidebar')}}</label>
                                    <select v-model="preferenceForm.sidebar" class="custom-select col-12">
                                      <option v-for="option in sidebar" v-bind:value="option.value">
                                        {{ option.text }}
                                      </option>
                                    </select>
                                    <show-error :form-name="preferenceForm" prop-name="sidebar"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{trans('locale.locale')}}</label>
                                    <select v-model="preferenceForm.locale" class="custom-select col-12">
                                      <option v-for="option in locales" v-bind:value="option.value">
                                        {{ option.text }}
                                      </option>
                                    </select>
                                    <show-error :form-name="preferenceForm" prop-name="sidebar"></show-error>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info waves-effect waves-light pull-right m-t-10">{{trans('general.save')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {},
        mounted(){
            if(this.$route.query.reload)
                window.location = window.location.pathname;

            axios.get('/api/dashboard')
                .then(response => response.data)
                .then(response => {
                    this.users = response.users;
                    this.today_registered_users = response.today_registered_users;
                    this.weekly_registered_users = response.weekly_registered_users;
                    this.monthly_registered_users = response.monthly_registered_users;
                    this.activity_logs = response.activity_logs;
                    this.todos = response.todos;
                })
                .catch(error => {
                    helper.showDataErrorMsg(error);
                })

            axios.get('/api/user/preference/pre-requisite')
                .then(response => response.data)
                .then(response => {
                    this.color_themes = response.color_themes;
                    this.directions = response.directions;
                    this.sidebar = response.sidebar;
                    this.locales = response.locales;
                })
                .catch(error => {
                    helper.showDataErrorMsg(error);
                })
        },
        methods: {
            getStatus(todo){
                return todo.status ? ('<span class="label label-success">'+i18n.todo.complete+'</span>') : ('<span class="label label-danger">'+i18n.todo.incomplete+'</span>') ;
            },
            hasRole(role){
                return helper.hasRole(role);
            },
            logout(){
                helper.logout().then(() => {
                    this.$store.dispatch('resetAuthUserDetail');
                    this.$router.push('/login')
                })
            },
            updatePreference(){
                this.preferenceForm.post('/api/user/preference')
                    .then(response => {
                        toastr.success(response.message);

                        $('#theme').attr('href','/css/colors/'+this.preferenceForm.color_theme+'.css');

                        if(this.user_preference.direction != this.preferenceForm.direction || this.user_preference.sidebar != this.preferenceForm.sidebar || this.user_preference.locale != this.preferenceForm.locale)
                            location.reload();
                    })
                    .catch(error => {
                        helper.showErrorMsg(error);
                    })
            }
        },
        data() {
            return {
                users: 0,
                today_registered_users: 0,
                weekly_registered_users: 0,
                monthly_registered_users: 0,
                activity_logs: {},
                todos: {},
                color_themes: [],
                directions: [],
                sidebar: [],
                locales: [],
                preferenceForm: new Form({
                    color_theme: helper.getConfig('user_color_theme') || helper.getConfig('color_theme'),
                    direction: helper.getConfig('user_direction') || helper.getConfig('direction'),
                    locale: helper.getConfig('user_locale') || helper.getConfig('locale'),
                    sidebar: helper.getConfig('user_sidebar') || helper.getConfig('sidebar')
                },false),
                user_preference: {
                    color_theme: helper.getConfig('user_color_theme') || helper.getConfig('color_theme'),
                    direction: helper.getConfig('user_direction') || helper.getConfig('direction'),
                    locale: helper.getConfig('user_locale') || helper.getConfig('locale'),
                    sidebar: helper.getConfig('user_sidebar') || helper.getConfig('sidebar')
                }
            }
        },
        computed: {
        },
        filters: {
          momentDateTime(date) {
            return helper.formatDateTime(date);
          },
          moment(date) {
            return helper.formatDate(date);
          }
        },
    }
</script>
<style>
    .shw-rside{
        width: 500px;
    }
</style>