<template>
	<ul id="sidebarnav">
	    <li><router-link to="/home" exact><i class="fas fa-home fa-fw"></i> <span class="hide-menu">{{trans('general.home')}}</span></router-link></li>
		<template v-if="$route.meta.menu == 'configuration'">
	        <li><router-link to="/configuration/basic" exact><i class="fas fa-cog fa-fw"></i> <span class="hide-menu">{{trans('configuration.basic_configuration')}}</span></router-link></li>
	        <li><router-link to="/configuration/system" exact><i class="fas fa-cogs fa-fw"></i> <span class="hide-menu">{{trans('configuration.system_configuration')}}</span></router-link></li>
	        <li><router-link to="/configuration/mail" exact><i class="fas fa-envelope fa-fw"></i> <span class="hide-menu">{{trans('mail.mail_configuration')}}</span></router-link></li>
	        <li v-if="getConfig('multilingual')"><router-link to="/configuration/locale" exact><i class="fas fa-globe fa-fw"></i> <span class="hide-menu">{{trans('locale.locale')}}</span></router-link></li>
	        <li><router-link to="/configuration/role" exact><i class="fas fa-users fa-fw"></i> <span class="hide-menu">{{trans('role.role')}}</span></router-link></li>
	        <li><router-link to="/configuration/permission" exact><i class="fas fa-key fa-fw"></i> <span class="hide-menu">{{trans('permission.permission')}}</span></router-link></li>
	        <li><router-link to="/configuration/menu" exact><i class="fas fa-list fa-fw"></i> <span class="hide-menu">{{trans('general.menu')}}</span></router-link></li>
	        <!-- <li><router-link to="/configuration/sms" exact><i class="fas fa-comment fa-fw"></i> <span class="hide-menu">{{trans('general.sms')}}</span></router-link></li> -->
	        <li><router-link to="/configuration/authentication" exact><i class="fas fa-sign-in-alt fa-fw"></i> <span class="hide-menu">{{trans('auth.authentication')}}</span></router-link></li>
	        <li v-if="getConfig('ip_filter')"><router-link to="/configuration/ip-filter" exact><i class="fas fa-ellipsis-v fa-fw"></i> <span class="hide-menu">{{trans('ip_filter.ip_filter')}}</span></router-link></li>
	        <li><router-link to="/configuration/scheduled-job" exact><i class="fas fa-clock fa-fw"></i> <span class="hide-menu">{{trans('general.scheduled_job')}}</span></router-link></li>
		</template>
		<template v-else>
	        <li v-if="hasPermission('list-user') && getConfig('show_user_menu')"><router-link to="/user" exact><i class="fas fa-users fa-fw"></i> <span class="hide-menu">{{trans('user.user')}}</span></router-link></li>
	        <li v-if="hasPermission('access-vacancy') && getConfig('show_vacancy_menu')"><router-link to="/vacancies" exact><i class="far fa-check-circle fa-fw"></i> <span class="hide-menu">{{trans('vacancies.vacancies')}}</span></router-link></li>
	    </template>
    </ul>
</template>

<script>
	export default {
		methods: {
			hasPermission(permission){
				return helper.hasPermission(permission);
			},
			hasRole(role){
				return helper.hasRole(role);
			},
			getConfig(config){
				return helper.getConfig(config);
			}
		}
	}
</script>
