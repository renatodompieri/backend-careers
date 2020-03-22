<template>
    <div>
        <div class="page-titles p-3 border-bottom">
            <h3 class="text-themecolor">{{trans('vacancy.edit_vacancy')}}
                <button class="btn btn-info btn-sm pull-right" @click="$router.push('/vacancy')"><i class="fas fa-check-circle"></i> <span class="d-none d-sm-inline">{{trans('vacancy.vacancy')}}</span></button>
            </h3>
        </div>
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h4 class="card-title">{{trans('vacancy.edit_vacancy')}}</h4>
                            <vacancy-form :id="id"></vacancy-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vacancyForm from './form';

    export default {
        components : { vacancyForm },
        data() {
            return {
                id:this.$route.params.id
            }
        },
        mounted(){
            if(!helper.hasPermission('access-vacancy')){
                helper.notAccessibleMsg();
                this.$router.push('/home');
            }

            if(!helper.featureAvailable('vacancy')){
                helper.featureNotAvailableMsg();
                this.$router.push('/home');
            }
        }
    }
</script>
