

var express = require('express');
var app = express();
 
// Global app configuration section
app.use(express.bodyParser());  // Populate req.body
 
app.post('/notify_message', 
         express.basicAuth('YOUR_USERNAME', 'YOUR_PASSWORD'),
         function(req, res) {
  // Use Parse JavaScript SDK to create a new message and save it.
  var Message = Parse.Object.extend("Message");
  var message = new Message();
  message.save({ text: req.body.text }).then(function(message) {
    res.send('Success');
  }, function(error) {
    res.status(500);
    res.send('Error');
  });
});
 
app.listen();

