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
        sidebarDepth: 2,
        sidebar: {
            '/documentation': [
                {
                    title: 'Quick Start',
                    collapsable: false,
                    children: [
                        ['documentation/', 'How It Works'],
                        ['documentation/getting-started', 'Getting Started'],
                        // ['documentation/configuration', 'Configuration'],
                    ]
                },
                {
                    title: 'Digging Deeper',
                    collapsable: false,
                    children: [
                        ['documentation/digging-deeper/', 'Introduction'],
                        ['documentation/digging-deeper/win32-model', 'Win32Model'],
                        ['documentation/digging-deeper/the-config-instance', 'The Config Instance'],
                    ]
                },
            ],
        }
    }
};