module.exports = {
    base: `/wmi-scripting/`,
    title: 'WMI Scripting',
    description: 'WMI Scripting and Querying made easy.',

    head: [
        ['link', { rel: 'icon', href: `/wmi-squares.png` }],
    ],

    markdown: {
        lineNumbers: true
    },
    themeConfig: {
        bottom: "MIT Licensed | Copyright © 2019-present Joe Springe | This library is not in any way associated with nor endorsed by Microsoft™.",
        logo: '/wmi-squares.svg',
        nav: [
            { text: 'Home', link: '/' },
            { text: 'Documentation', link: '/documentation/' },
            { text: 'GitHub', link: 'https://github.com/phpwintools/wmi-scripting' },
        ],
        sidebar: {
            '/documentation': [
                {
                    title: 'Documentation',
                    collapsable: false,
                    children: [
                        ['documentation/', 'How It Works'],
                        ['documentation/getting-started', 'Getting Started']
                    ]
                }
            ]
        }
    }
};