require('dotenv').config();

const express = require('express');
const http = require('http');
const socketIO = require('socket.io');
const cors = require('cors');

const app = express();
app.use(cors());

const server = http.createServer(app);
const io = socketIO(server, {
    cors: { origin: "*" }
});

io.on('connection', (socket) => {
    console.log('Cliente conectado');
});

app.post('/notify-update', express.json(), (req, res) => {
    io.emit('order_updated', req.body);
    res.send({ status: 'ok' });
});

const PORT = process.env.SOCKET_PORT || 3000;

server.listen(PORT, () => console.log(`Socket rodando na porta ${PORT}`));
