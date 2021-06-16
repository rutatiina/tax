

const AccountingTaxes = () => import('./Index')
const AccountingTaxesForm = () => import('./Form')


const routes = [

    {
        path: '/taxes',
        components: {
            default: AccountingTaxes
        },
        meta: {
            title: 'Accounting :: Taxes',
            metaTags: [
                {
                    name: 'description',
                    content: 'Taxes'
                },
                {
                    property: 'og:description',
                    content: 'Taxes'
                }
            ]
        }
    },
    {
        path: '/taxes/create',
        components: {
            default: AccountingTaxesForm
        },
        meta: {
            title: 'Accounting :: Taxes',
            metaTags: [
                {
                    name: 'description',
                    content: 'Create Tax'
                },
                {
                    property: 'og:description',
                    content: 'Create Tax'
                }
            ]
        }
    },
    {
        path: '/taxes/:id/edit',
        components: {
            default: AccountingTaxesForm
        },
        meta: {
            title: 'Accounting :: Edit Tax',
            metaTags: [
                {
                    name: 'description',
                    content: 'Edit Tax'
                },
                {
                    property: 'og:description',
                    content: 'Edit Tax'
                }
            ]
        }
    }

]

export default routes
