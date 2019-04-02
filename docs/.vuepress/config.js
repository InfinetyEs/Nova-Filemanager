module.exports = {
  title: 'Nova Filemanager',
  description: 'Documentation for Filemanager Tool and Field for Laravel Nova',
  base: '/Nova-Filemanager/',
  serviceWorker: true,
  toc: { includeLevel: [1, 2] },
  themeConfig: {
  	nav: [
      { text: 'Home', link: '/' },
      { text: 'Infinety', link: 'https://infinety.es' },
      { text: 'Eric Lagarda', link: 'https://ericlagarda.com' },
    ],
    sidebar: {
      '/2.1/': require('./2.1'),
    },
    sidebarDepth: 2,
    lastUpdated: 'Last Updated',
    serviceWorker: {
      updatePopup: true
    },
    repo: 'InfinetyEs/Nova-Filemanager',
    repoLabel: 'Contribute!',
    docsDir: 'docs',
    editLinks: true,
  },
  markdown: {
      lineNumbers: false
  }
}