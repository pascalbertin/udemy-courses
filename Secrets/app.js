require('dotenv').config();
const express = require('express');
const mongoose = require('mongoose');
const ejs = require('ejs');
const session = require('express-session');
const passport = require('passport');
const passportLocalMongoose = require('passport-local-mongoose');
const GoogleStrategy = require('passport-google-oauth20').Strategy;
const findOrCreate = require('mongoose-findorcreate');
const app = express();

app.set('view engine', 'ejs');
app.use(express.urlencoded({ extended: true }));
app.use(express.json());
app.use(express.static('public'));
app.use(session({
  secret: 'Some secret stuff!',
  resave: false,
  saveUninitialized: false
}));
app.use(passport.initialize());
app.use(passport.session());

mongoose.connect('mongodb://localhost:27017/userDB', {
  useUnifiedTopology: true,
  useNewUrlParser: true
});
mongoose.set('useCreateIndex', true);

const userSchema = new mongoose.Schema({
  email: String,
  password: String,
  googleId: String,
  secret: String
});

userSchema.plugin(passportLocalMongoose);
userSchema.plugin(findOrCreate);

const User = mongoose.model('User', userSchema);

passport.use(User.createStrategy());

passport.serializeUser(function (user, done) {
  done(null, user.id);
});

passport.deserializeUser(function (id, done) {
  User.findById(id, function (err, user) {
    done(err, user);
  });
});


passport.use(new GoogleStrategy({
  clientID: process.env.CLIENT_ID,
  clientSecret: process.env.CLIENT_SECRET,
  callbackURL: 'http://localhost:3000/auth/google/secrets',
  userProfileURL: 'https://www.googleapis.com/oauth2/v3/userinfo'
},
  function (accessToken, refreshToken, profile, cb) {
    User.findOrCreate({ googleId: profile.id }, function (err, user) {
      return cb(err, user);
    });
  }
));


app.get('/', (req, res) => {
  res.render('home');
});

app.get('/auth/google', passport.authenticate('google', {
  scope: ['profile']
}));

app.get('/auth/google/secrets',
  passport.authenticate('google', { failureRedirect: '/login' }),
  (req, res) => {
    // Successful authentication, redirect to secrets.
    res.redirect('/secrets');
  });

app.get('/login', (req, res) => {
  res.render('login');
});

app.get('/register', (req, res) => {
  res.render('register');
});

app.get('/secrets', (req, res) => {
  User.find({ 'secret': { $ne: null } }, (err, foundUsers) => {
    if (!err && foundUsers) {
      res.render('secrets', { userWithSecrets: foundUsers });
    }
  });
});

app.get('/submit', (req, res) => {
  if (req.isAuthenticated()) {
    res.render('submit');
  } else {
    res.redirect('/login');
  }
});

app.post('/submit', (req, res) => {
  const submittedSecret = req.body.secret;

  User.findById(req.user.id, (err, foundUser) => {
    if (!err && foundUser) {
      foundUser.secret = submittedSecret;
      foundUser.save(() => {
        res.redirect('/secrets');
      });
    }
  });
});

app.get('/logout', (req, res) => {
  req.logout();
  res.redirect('/');
});

app.post('/register', (req, res) => {
  const userName = req.body.username;
  const password = req.body.password;

  User.register({ username: userName }, password, (err, user) => {
    if (!err) {
      passport.authenticate('local')(req, res, () => {
        res.redirect('/secrets');
      });
    } else {
      res.redirect('/register');
    }
  });
});

app.post('/login', (req, res) => {
  const user = new User({
    username: req.body.username,
    password: req.body.password
  });

  req.login(user, err => {
    if (!err) {
      passport.authenticate('local')(req, res, () => {
        res.redirect('/secrets');
      });
    } else {
      console.log(err);
      res.redirect('/login');
    }
  });
});

app.listen(3000);