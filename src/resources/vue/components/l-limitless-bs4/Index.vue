<template>

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Page header -->
        <div class="page-header page-header-light">
            <div class="page-header-content header-elements-md-inline">
                <div class="page-title d-flex">
                    <h4>
                        <i class="icon-price-tags mr-2"></i>
                        <span class="font-weight-semibold">Taxes</span>
                        <span class="pl-3 small">Manage, Taxes</span>
                    </h4>
                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>

            </div>

            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                <div class="d-flex">
                    <div class="breadcrumb">
                        <a href="/" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                        <span class="breadcrumb-item active">Taxes</span>
                    </div>

                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>

                <div class="header-elements">
                    <div class="breadcrumb justify-content-center">
                        <router-link to="/taxes/create" class=" btn btn-danger btn-sm rounded-round font-weight-bold">
                            <i class="icon-price-tags2 mr-1"></i>
                            Create Tax
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page header -->


        <!-- Content area -->
        <div class="content border-0 p-0">

            <loading-animation></loading-animation>


            <!-- Basic table -->
            <div class="card shadow-none rounded-0 border-0" v-if="!this.$root.loading">

                <div class="card-body" v-if="!this.$root.loading && tableData.payload.data.length===0">
                    <h6>
                        <i class="icon-files-empty mr-2"></i>
                        No records found
                    </h6>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-active">
                                <th scope="col" style="width: 20px;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               v-model="rgTableSelectAll"
                                               class="custom-control-input"
                                               id="row-checkbox-all">
                                        <label class="custom-control-label" for="row-checkbox-all"> </label>
                                    </div>
                                </th>
                                <th scope="col" class="font-weight-bold">Name</th>
                                <th scope="col" class="font-weight-bold" nowrap="">Display Name</th>
                                <th scope="col" class="font-weight-bold" nowrap="">Country</th>
                                <th scope="col" class="font-weight-bold" nowrap="">Value</th>
                                <th scope="col" class="font-weight-bold" nowrap="">Based On</th>
                                <th scope="col" class="font-weight-bold" nowrap="">Inclusive</th>
                                <th scope="col" class="font-weight-bold" nowrap="">On Sale Effect</th>
                                <th scope="col" class="font-weight-bold" nowrap="">On Sale Account</th>
                                <th scope="col" class="font-weight-bold" nowrap="">On Bill Effect</th>
                                <th scope="col" class="font-weight-bold" nowrap="">On Bill Account</th>
                            </tr>
                        </thead>

                        <rg-tables-state></rg-tables-state>

                        <tbody>
                            <tr v-for="row in tableData.payload.data"
                                @click="onRowClick(row)">
                                <td v-on:click.stop="" class="pr-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               v-model="tableData.selected"
                                               :value="row.id"
                                               :disabled="row.tenant_id === 0"
                                               number
                                               class="custom-control-input"
                                               :id="'row-checkbox-'+row.id">
                                        <label class="custom-control-label" :for="'row-checkbox-'+row.id"> </label>
                                    </div>
                                </td>
                                <td class="cursor-pointer" nowrap>{{row.name}}</td>
                                <td class="cursor-pointer font-weight-semibold">{{row.display_name}}</td>
                                <td class="cursor-pointer">{{row.country}}</td>
                                <td class="cursor-pointer">{{row.value}}</td>
                                <td class="cursor-pointer">{{row.based_on}}</td>
                                <td class="cursor-pointer">{{row.inclusive}}</td>
                                <td class="cursor-pointer">{{row.on_sale_effect}}</td>
                                <td class="cursor-pointer">{{row.on_sale_account ? row.on_sale_account.name : ''}}</td>
                                <td class="cursor-pointer">{{row.on_bill_effect}}</td>
                                <td class="cursor-pointer">{{row.on_bill_account ? row.on_bill_account.name : ''}}</td>

                            </tr>
                        </tbody>
                    </table>

                    <rg-tables-pagination v-bind:table-data-prop="tableData"></rg-tables-pagination>

                </div>

            </div>
            <!-- /basic table -->

        </div>
        <!-- /content area -->


        <!-- Footer -->

        <!-- /footer -->

    </div>
    <!-- /main content -->

</template>

<script>

    export default {
        components: {},
        data() {
            return {}
        },
        watch: {
            '$route.query.page': function (page) {
                this.tableData.url = this.$router.currentRoute.path + '?page='+page;
            }
        },
        mounted() {
            this.$root.appMenu('accounting')

            this.tableData.initiate = true

            //page height - 230(page header and breadcrump) - 80 (lower space) / 45 (height of each row)
            this.tableRecordsPerPage(230, 80, 45)

            let currentObj = this;

            if (currentObj.$route.query.page === undefined) {
                currentObj.tableData.url = this.$router.currentRoute.path; //'/crbt/transactions';
            } else {
                currentObj.tableData.url = this.$router.currentRoute.path + '?page='+currentObj.$route.query.page;
            }

        },
        methods: {
            onRowClick(row) {
                this.$router.push({ path: '/taxes/'+row.id + '/edit' })
            }
        },
        ready:function(){},
        beforeUpdate: function () {},
        updated: function () {}
    }
</script>
