var http    = require('http'),
socketio    = require('socket.io');

var PORT_SOCKETIO_SERVER    = 1234;
var server                  = http.createServer();
var io                      = socketio.listen(server);
var keys                    = {};

io.sockets.on('connection', function(socket) {
    socket.on('video.init', function(d) {
        console.log('video.init : '+JSON.stringify(d));
        if(keys[d.key] === undefined){
            createKeys(d.key);
        }
        keys[d.key].videos.push(socket);
    });
    
    socket.on('pad.init', function(d) {
        console.log('pad.init : '+JSON.stringify(d));
        if(keys[d.key] === undefined){
            createKeys(d.key);
        }
        keys[d.key].pads.push(socket);
        keys[d.key].videos.forEach(function(v, i){
           console.log('video.new.pad : '+JSON.stringify(d));
           v.emit('video.new.pad', {pad: d});
        });
    });
    
    socket.on('pad.goto.video', function(d){
        console.log('pad.goto.video : '+JSON.stringify(d));

        Object.keys(keys).forEach(function(k){
            if(k === d.key){
                keys[k].videos.forEach(function(v, i){
                    console.log('video.goto : '+JSON.stringify(d));
                    v.emit('video.goto', {to: d.to});
                });
            }
        });
    });
    
    socket.on('disconnect', function(d) {
        Object.keys(keys).forEach(function(k){
            keys[k].videos.forEach(function(v, i){
               if(v === socket){
                   delete keys[k].videos[i];
                   console.log('video.disconnect : '+JSON.stringify(d));
               } 
            });
            keys[k].pads.forEach(function(v, i){
               if(v === socket){
                   delete keys[k].pads[i];
                   console.log('pad.disconnect : '+JSON.stringify(d));
               } 
            });
        });
    });
});

function createKeys(key){
    keys[key] = {
        'videos': [],
        'pads':[]
    };
}
server.listen(PORT_SOCKETIO_SERVER);