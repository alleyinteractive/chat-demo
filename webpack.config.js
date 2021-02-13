const path = require('path');
const fs = require('fs');
const homeDir = process.env.HOME;

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        },
    },
    devServer: {
        host: 'chat-demo.home',
        port: 8080,
        https: {
            key: fs.readFileSync(path.resolve(homeDir, `.config/valet/Certificates/chat-demo.home.key`)),
            cert: fs.readFileSync(path.resolve(homeDir, `.config/valet/Certificates/chat-demo.home.crt`)),
        },
    },
};
