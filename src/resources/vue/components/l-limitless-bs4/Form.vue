<template>

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Page header -->
        <div class="page-header page-header-light">
            <div class="page-header-content header-elements-md-inline">
                <div class="page-title d-flex">
                    <h4>
                        <i class="icon-file-plus"></i>
                        {{pageTitle}}
                    </h4>
                </div>
            </div>

            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                <div class="d-flex">
                    <div class="breadcrumb">
                        <a href="/" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Taxes</a>
                        <span class="breadcrumb-item active">{{pageAction}}</span>
                    </div>

                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>

                <div class="header-elements">
                    <div class="breadcrumb justify-content-center">
                        <router-link to="/taxes" class=" btn btn-danger btn-sm rounded-round font-weight-bold">
                            <i class="icon-price-tags mr-1"></i>
                            Taxes
                        </router-link>
                    </div>
                </div>

            </div>

        </div>
        <!-- /page header -->

        <!-- Content area -->
        <div class="content border-0 padding-0">

            <!-- Form horizontal -->
            <div class="card shadow-none rounded-0 border-0">

                <div class="card-body p-0">

                    <loading-animation></loading-animation>

                    <form v-if="!this.$root.loading"
                          @submit="formSubmit"
                          method="post"
                          class="max-width-1040"
                          autocomplete="off">

                        <fieldset>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label"> </label>
                                <div class="col-lg-10">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">Name</span>
                                        </span>
                                        <input type="text" v-model="attributes.name" class="form-control input-roundless" placeholder="Tax name">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label"> </label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">Display name</span>
                                        </span>
                                        <input type="text" class="form-control" v-model="attributes.display_name" placeholder="Display name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row mr-0">
                                        <label class="col-auto col-form-label text-right bg-light border rounded-left border-right-0" style="white-space: nowrap;">
                                            Country
                                        </label>
                                        <div class="col pl-0 pr-0">
                                            <model-list-select :list="$root.globalsCountries"
                                                               v-model="attributes.country"
                                                               option-value="value"
                                                               option-text="text"
                                                               class="rounded-left-0"
                                                               placeholder="Select Country">
                                            </model-list-select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">  </label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">Value</span>
                                            </span>
                                        <input type="text" class="form-control" v-model="attributes.value" placeholder="Value e.g 500 or 10%">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row mr-0">
                                        <label class="col-auto col-form-label text-right bg-light border rounded-left border-right-0"
                                               style="white-space: nowrap;">
                                            Based on
                                        </label>
                                        <div class="col pl-0 pr-0">
                                            <model-list-select :list="taxBasedOn"
                                                               v-model="attributes.based_on"
                                                               option-value="value"
                                                               option-text="text"
                                                               class="rounded-left-0 rounded-right-0"
                                                               placeholder="select account">
                                            </model-list-select>
                                        </div>
                                        <span class="input-group-append">
                                            <div class="input-group-text">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" v-model="attributes.inclusive" value="1" class="custom-control-input" id="tax-inclusive" >
                                                    <label class="custom-control-label font-weight-semibold" for="tax-inclusive">Tax is Inclusive</label>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div id="item_rates" class="col-md-10 offset-md-2 p-0">

                            <div class="row m-0">

                                <!-- OPEN: Sales information -->
                                <div class="col-md-6">

                                    <div class="form-group row mr-0">
                                        <span class="badge bg-purple badge-pill font-weight-bold">ON SALES</span>
                                    </div>

                                    <div class="form-group row">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" v-model="attributes.on_sale" value="customer" class="custom-control-input" id="tax-on-sale" checked>
                                            <label class="custom-control-label" for="tax-on-sale">Tax is applied on Sales - effect of tax when making sales.</label>
                                        </div>
                                    </div>

                                    <div class="form-group row mr-0">
                                        <label class="col-auto col-form-label text-right bg-light border rounded-left border-right-0"
                                               style="white-space: nowrap;">
                                            Effect
                                        </label>
                                        <div class="col pl-0 pr-0">
                                            <model-list-select :list="taxEffect"
                                                               v-model="attributes.on_sale_effect"
                                                               option-value="value"
                                                               option-text="text"
                                                               class="rounded-left-0"
                                                               placeholder="select account">
                                            </model-list-select>
                                        </div>
                                    </div>

                                    <div class="form-group row mr-0">
                                        <label class="col-auto col-form-label text-right bg-light border rounded-left border-right-0"
                                               style="white-space: nowrap;">
                                            Account
                                        </label>
                                        <div class="col pl-0 pr-0">
                                            <model-list-select :list="accounts"
                                                               v-model="attributes.on_sale_financial_account_code"
                                                               option-value="code"
                                                               option-text="name"
                                                               class="rounded-left-0"
                                                               placeholder="select account">
                                            </model-list-select>
                                        </div>
                                    </div>

                                </div>
                                <!-- CLOSE: Sales information -->

                                <!-- OPEN: Cost / Purchase information -->
                                <div class="col-md-6">

                                    <div class="form-group row ml-0">
                                        <span class="badge bg-purple badge-pill font-weight-bold">ON BILLING</span>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" v-model="attributes.on_bill" value="customer" class="custom-control-input" id="tax-on-bill" checked>
                                            <label class="custom-control-label" for="tax-on-bill">Tax is applied to Bills - effect of tax when bills are received.</label>
                                        </div>
                                    </div>

                                    <div class="form-group row pl-2">
                                        <label class="col-auto col-form-label text-right bg-light border rounded-left border-right-0"
                                               style="white-space: nowrap;">
                                            Effect
                                        </label>
                                        <div class="col pl-0 pr-0">
                                            <model-list-select :list="taxEffect"
                                                               v-model="attributes.on_bill_effect"
                                                               option-value="value"
                                                               option-text="text"
                                                               class="rounded-left-0"
                                                               placeholder="select account">
                                            </model-list-select>
                                        </div>
                                    </div>

                                    <div class="form-group row pl-2">
                                        <label class="col-auto col-form-label text-right bg-light border rounded-left border-right-0"
                                               style="white-space: nowrap;">
                                            Account
                                        </label>
                                        <div class="col pl-0 pr-0">
                                            <model-list-select :list="accounts"
                                                               v-model="attributes.on_bill_financial_account_code"
                                                               option-value="code"
                                                               option-text="name"
                                                               class="rounded-left-0"
                                                               placeholder="select account">
                                            </model-list-select>
                                        </div>
                                    </div>


                                </div>
                                <!-- CLOSE: Cost / Purchase information -->

                            </div>

                        </div>

                        <div class="col-md-10 offset-md-2 p-0">

                            <div class="row col-12 mb-3">
                                <span class="badge bg-purple badge-pill font-weight-bold">Important dates</span>
                            </div>

                            <div class="row m-0">

                                <!-- OPEN: Sales information -->
                                <div class="col-md-6">

                                    <!--<div class="row">-->
                                    <!--    <p>* Tax goes into enforce on:</p>-->
                                    <!--</div>-->

                                    <div class="form-group row mr-0">
                                        <label class="col-auto col-form-label text-right bg-light border rounded-left border-right-0"
                                               style="white-space: nowrap;">
                                            Date Tax goes in force:
                                        </label>
                                        <div class="col pl-0 pr-0">
                                            <date-picker v-model="attributes.enforceable_from"
                                                         valueType="format"
                                                         :lang="vue2DatePicker.lang"
                                                         class="font-weight-bold w-100 h-100 rounded-0"
                                                         placeholder="Choose date">
                                            </date-picker>
                                        </div>
                                    </div>

                                </div>
                                <!-- CLOSE: Sales information -->

                                <!-- OPEN: Cost / Purchase information -->
                                <div class="col-md-6">

                                    <!--<div class="row ml-0">-->
                                    <!--    <p>*Tax is abolished on:</p>-->
                                    <!--</div>-->

                                    <div class="form-group row ml-0 mr-0">
                                        <label class="col-auto col-form-label text-right bg-light border rounded-left border-right-0"
                                               style="white-space: nowrap;">
                                            Date tax is abolished:
                                        </label>
                                        <div class="col pl-0 pr-0">
                                            <date-picker v-model="attributes.abolished_on"
                                                         valueType="format"
                                                         :lang="vue2DatePicker.lang"
                                                         class="font-weight-bold w-100 h-100 rounded-0"
                                                         placeholder="Choose date">
                                            </date-picker>
                                        </div>
                                    </div>

                                </div>
                                <!-- CLOSE: Cost / Purchase information -->

                            </div>

                        </div>

                        <div class="text-left col-md-10 offset-md-2 p-0">
                            <button type="submit" class="btn btn-danger font-weight-bold">
                                <i class="icon-price-tags2 mr-1"></i> {{pageTitle}}
                            </button>
                        </div>


                    </form>

                </div>
            </div>
            <!-- /form horizontal -->


        </div>
        <!-- /content area -->

    </div>
    <!-- /main content -->

</template>
<style>
.mx-input {
     border-radius: 0px !important; /* remove the border radius on the date selection */
}
</style>

<script>

    export default {
        components: {},
        data() {
            return {
                pageTitle: 'Taxes',
                urlPost: '/taxes',
                pageAction: 'Create',
                attributes: [],
                taxBasedOn: [
                    { value: 'item', text: 'Item' },
                    { value: 'total', text: 'Total' }
                ],
                taxMethods: [
                    { value: 'inclusive', text: 'Inclusive' },
                    { value: 'exclusive', text: 'Exclusive' }
                ],
                taxEffect: [
                    { value: 'debit', text: 'Debit' },
                    { value: 'credit', text: 'Credit' }
                ],
                accounts: [],
            }
        },
        mounted() {
            this.$root.appMenu('accounting')
            this.$root.appFetchGlobalsCountries()

            // console.log('TaxesForm: Component mounted');

            this.fetchAttributes()

        },
        watch: {
            $route: function () {
                this.fetchAttributes()
            }
        },
        methods: {
            async fetchAttributes() {
                //console.log('fetchAttributes')

                try {

                    return await axios.get(this.$route.fullPath)
                        .then(response => {

                            this.pageTitle = response.data.pageTitle
                            this.attributes = response.data.attributes
                            this.urlPost = response.data.urlPost
                            this.accounts = response.data.accounts

                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error); //test
                        })
                        .finally(function (response) {
                            // always executed this is supposed
                        })

                } catch (e) {
                    console.log(e); //test
                }
            },
            formSubmit(e) {

                e.preventDefault();

                let currentObj = this;

                PNotify.removeAll();

                let PNotifySettings = {
                    title: false, //'Processing',
                    text: 'Please wait as we do our thing',
                    addclass: 'bg-warning-400 border-warning-400',
                    hide: false,
                    buttons: {
                        closer: false,
                        sticker: false
                    }
                };

                let notice = new PNotify(PNotifySettings);

                //console.log(this.attributes);

                axios.post(currentObj.urlPost, this.attributes)
                    .then(function (response) {

                        //PNotify.removeAll();

                        PNotifySettings.text = response.data.messages.join("\n");

                        if(response.data.status === true)
                        {
                            PNotifySettings.title = 'Success';
                            PNotifySettings.type = 'success';
                            PNotifySettings.addclass = 'bg-success-400 border-success-400';
                        } else
                        {
                            PNotifySettings.title = '! Error';
                            PNotifySettings.type = 'error';
                            PNotifySettings.addclass = 'bg-warning-400 border-warning-400';
                        }

                        //let notice = new PNotify(PNotifySettings);
                        notice.update(PNotifySettings);

                        notice.get().click(function() {
                            notice.remove();
                        });

                        currentObj.$router.push({ path: response.data.callback });

                        // console.log('redirect to /taxes');
                    })
                    .catch(function (error) {
                        currentObj.response = error;
                    });
            },
        },
        ready:function(){},
        beforeUpdate: function () {},
        updated: function () {},
        destroyed: function () {}
    }
</script>
