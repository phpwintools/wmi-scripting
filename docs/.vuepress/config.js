module.exports = {
    base: `/wmi-scripting/`,
    title: 'WMI Scripting',
    description: 'WMI Scripting in PHP made easy.',

    head: [
        ['link', { rel: 'icon', href: `/wmi-squares.png` }],
    ],

    themeConfig: {
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
                        ['documentation/getting-started/', 'Getting Started']
                    ]
                }
            ]
        }
    }
};