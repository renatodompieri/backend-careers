import Vue from 'vue'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import VueRouter from 'vue-router'
import store from './store'

Vue.use(Loading);

let pageLoader;

let routes = [
    {
        path: '/',                      // all the routes which can be access without authentication
        component: require('./layouts/home-page').default,
        meta: {validate: ['guest']},
        children: [
            {
                path: '',
                component: require('./views/guest/index').default
            },
            {
                path: '/',
                component: require('./views/guest/index').default
            },
            {
                path: '/vacancies/:id',
                component: require('./views/vacancy/show').default
            },
        ]
    },
    {
        path: '/',                      // all the routes which can be access without authentication
        component: require('./layouts/guest-page').default,
        meta: {validate: ['guest']},
        children: [
            {
                path: '/login',
                component: require('./views/auth/login').default
            },
            {
                path: '/password',
                component: require('./views/auth/password').default
            },
            {
                path: '/register',
                component: require('./views/auth/register').default
            },
            {
                path: '/auth/:token/activate',
                component: require('./views/auth/activate').default
            },
            {
                path: '/password/reset/:token',
                component: require('./views/auth/reset').default
            },
            {
                path: '/auth/social',
                component: require('./views/auth/social-auth').default
            }
        ]
    },
    {
        path: '/', // all the routes which needs authentication + two factor authentication + lock screen
        component: require('./layouts/default-page').default,
        meta: {validate: ['auth', 'two_factor', 'lock_screen']},
        children: [
            {
                path: '/',
                component: require('./views/pages/home').default
            },
            {
                path: '/home',
                component: require('./views/pages/home').default
            },
            {
                path: '/profile',
                component: require('./views/pages/profile').default
            },
            {
                path: '/change-password',
                component: require('./views/pages/change-password').default
            },
            {
                path: '/blank',
                component: require('./views/pages/blank').default
            },
            {
                path: '/configuration',
                component: require('./views/configuration/basic/index').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/logo',
                component: require('./views/configuration/logo/index').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/mail',
                component: require('./views/configuration/mail/index').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/backup',
                component: require('./views/backup/index').default
            },
            {
                path: '/configuration/basic',
                component: require('./views/configuration/basic/index').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/system',
                component: require('./views/configuration/system/index').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/role',
                component: require('./views/configuration/role/index').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/menu',
                component: require('./views/configuration/menu/index').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/authentication',
                component: require('./views/configuration/authentication/index').default,
                meta: {menu: 'configuration'}
            },
            // {
            //     path: '/configuration/sms',
            //     component: require('./views/configuration/sms/index').default,
            //     meta: { menu: 'configuration'}
            // },
            {
                path: '/configuration/scheduled-job',
                component: require('./views/configuration/scheduled-job/index').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/ip-filter',
                component: require('./views/configuration/ip-filter/index').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/ip-filter/:id/edit',
                component: require('./views/configuration/ip-filter/edit').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/permission',
                component: require('./views/configuration/permission/index').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/permission/assign',
                component: require('./views/configuration/permission/assign').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/locale',
                component: require('./views/configuration/locale/index').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/locale/:id/edit',
                component: require('./views/configuration/locale/edit').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/locale/:locale',
                component: require('./views/configuration/locale/view').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/configuration/locale/:locale/:module',
                component: require('./views/configuration/locale/view').default,
                meta: {menu: 'configuration'}
            },
            {
                path: '/email-template',
                component: require('./views/email-template/index').default
            },
            {
                path: '/email-template/:id/edit',
                component: require('./views/email-template/edit').default
            },
            {
                path: '/email-log',
                component: require('./views/email-log/index').default
            },
            {
                path: '/activity-log',
                component: require('./views/activity-log/index').default
            },
            {
                path: '/vacancies',
                component: require('./views/vacancy/index').default
            },
            {
                path: '/vacancies/:id/edit',
                component: require('./views/vacancy/edit').default
            },
            {
                path: '/user',
                component: require('./views/user/index').default
            },
            {
                path: '/user/:id',
                component: require('./views/user/basic').default
            },
            {
                path: '/user/:id/basic',
                component: require('./views/user/basic').default
            },
            {
                path: '/user/:id/contact',
                component: require('./views/user/contact').default
            },
            {
                path: '/user/:id/avatar',
                component: require('./views/user/avatar').default
            },
            {
                path: '/user/:id/social',
                component: require('./views/user/social').default
            },
            {
                path: '/user/:id/password',
                component: require('./views/user/password').default
            },
            {
                path: '/user/:id/email',
                component: require('./views/user/email').default
            },
        ]
    },
    {
        path: '/',
        component: require('./layouts/guest-page').default,
        meta: {validate: ['auth']},
        children: [
            {
                path: '/auth/security',
                component: require('./views/auth/security').default,
            },
            {
                path: '/auth/lock',
                component: require('./views/auth/lock').default,
            },
        ]
    },
    {
        path: '/',
        component: require('./layouts/guest-page').default,
        children: [
            {
                path: '/terms-and-conditions',
                component: require('./views/pages/terms-and-conditions').default
            }
        ]
    },
    {
        path: '/',
        component: require('./layouts/error-page').default,
        children: [
            {
                path: '/terms-and-conditions',
                component: require('./views/pages/terms-and-conditions').default
            },
            {
                path: '/ip-restricted',
                component: require('./views/errors/ip-restricted').default
            },
            {
                path: '/maintenance',
                component: require('./views/errors/maintenance').default
            }
        ]
    },
    {
        path: '*',
        component: require('./layouts/error-page').default,
        children: [
            {
                path: '*',
                component: require('./views/errors/page-not-found').default
            }
        ]
    }
];

const router = new VueRouter({
    routes,
    // linkActiveClass: 'active',
    mode: 'history',
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return {x: 0, y: 0}
        }
    }
});

router.beforeEach((to, from, next) => {
    pageLoader = Vue.$loading.show();
    // For force logout
    // store.dispatch('resetAuthUserDetail');

    helper.check()
        .then(response => {

            // Initialize toastr notification
            helper.notification();

            // Check for IP Restriction; If restricted IP found, redirect to "/ip-restricted" route
            if (helper.getConfig('ip_filter') && localStorage.getItem('ip_restricted') && to.fullPath != '/ip-restricted') {
                pageLoader.hide();
                return next({path: '/ip-restricted'})
            }

            if (to.fullPath == '/login' && helper.isAuth()) {
                pageLoader.hide();
            }

            // Check for Maintenance mode; If maintenance mode is on, redirect to "/maintenance" route
            if (helper.isAuth() && !helper.hasRole('admin') && helper.getConfig('maintenance_mode') && to.fullPath != '/maintenance') {
                pageLoader.hide();
                return next({path: '/maintenance'})
            }

            if (to.matched.some(m => m.meta.validate)) {
                const m = to.matched.find(m => m.meta.validate);

                // Check for authentication; If authenticated, redirect to "/home" route
                if (m.meta.validate.indexOf('guest') > -1 && !helper.isAuth()) {
                    return next();
                }

                // Check for authentication; If no, redirect to "/login" route
                if (m.meta.validate.indexOf('auth') > -1) {
                    if (!helper.isAuth()) {
                        toastr.error(i18n.auth.auth_required);
                        pageLoader.hide();
                        return next({path: '/login'})
                    }
                }

                // Check for two factor security; If enabled, redirect to "/auth/security" route after login
                if (m.meta.validate.indexOf('two_factor') > -1) {
                    if (helper.getConfig('two_factor_security') && helper.getTwoFactorCode()) {
                        pageLoader.hide();
                        return next({path: '/auth/security'})
                    }
                }

                // Check for screen lock; If enabled, redirect to "/auth/lock" route after screen lock timeout
                if (m.meta.validate.indexOf('lock_screen') > -1) {
                    if (helper.getConfig('lock_screen') && helper.isScreenLocked()) {
                        pageLoader.hide();
                        return next({path: '/auth/lock'})
                    }
                }

            }

            return next()
        })
        .catch(error => {
            // Authentication check fail, redirected back to "/login" route
            store.dispatch('resetAuthUserDetail');
            pageLoader.hide();
            return next({path: '/login'})
        });
});

router.afterEach((to, from) => {
    pageLoader.hide();
});

export default router;
