<template>
    <div id="main-wrapper">
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-collapse">
                    <ul class="navbar-nav ml-auto  my-lg-0">
                        <li class="nav-item">
                            <router-link v-if="this.isAuth()" class="nav-link" to="/home">Acessar o painel</router-link>
                            <router-link v-else class="nav-link" to="/login">Login</router-link>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <router-view></router-view>
    </div>
</template>


<script>
    import AppHeader from './header.vue'

    export default {
        components: {
            AppHeader
        },
        methods: {
            isAuth() {
                return helper.isAuth()
            }
        }
    }
</script>
