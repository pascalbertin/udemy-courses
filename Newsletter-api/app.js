const express = require('express');
const bodyParser = require('body-parser');
const https = require('https');

// API KEY
const apiKey = '*****';

// List ID
const listId = '******';

const port = 3000;

const app = express();

app.use(bodyParser.urlencoded({extended: true}));
app.use(express.static('public'));

app.get("/", (req, res) => {
  res.sendFile(__dirname + "/singup.html");
});

app.post("/", (req, res) => {
  const firstName = req.body.firstName;
  const secondName = req.body.secondName;
  const email = req.body.email;

  const data = {
    members: [
      {
        email_address: email,
        status: "subscribed",
        merge_fields: {
          FNAME: firstName,
          LNAME: secondName
        }
      }
    ]
  };

  const jsonData = JSON.stringify(data);

  const url = "https://us1.api.mailchimp.com/3.0/lists/" + listId;
  const options = {
    method: "POST",
    auth: "severo:" + apiKey
  };

  const request = https.request(url, options, response => {
    if (response.statusCode === 200) {
      res.sendFile(__dirname + "/success.html");
    } else {
      res.sendFile(__dirname + "/failure.html");
    }
  });

  request.write(jsonData);
  request.end();
});

app.post("/failure", (req, res) => {
  res.redirect("/");
});

app.listen(process.env.PORT || port, () => {
  console.log("Server is running on port " + port);
});