
const Index = () => import('./components/l-limitless-bs4/Index')
const Form = () => import('./components/l-limitless-bs4/Form')

const routes = [
    {
        path: '/taxes',
        components: {
            default: Index
        },
        meta: {
            title: 'Taxes',
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
            default: Form
        },
        meta: {
            title: 'Taxes',
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
            default: Form
        },
        meta: {
            title: 'Edit Tax',
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
