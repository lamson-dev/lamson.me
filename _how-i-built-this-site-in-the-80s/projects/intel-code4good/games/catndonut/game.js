<<<<<<< HEAD
/*
Alex Miller, CSE 190M Extra Session
5/28/2012

A simple game using the HTML5 canvas. Use the arrow keys to move the cat
around. The donut chases the cat.
*/

// cat image
var img; 

// canvas context, used to draw to the canvas (like Java's Graphics)
var paintbrush;

// x and y position of the cat
var x;
var y;

// x and y components of the cat's velocity
var vx;
var vy;

// x and y components of the cat's acceleration
var ax;
var ay;

// donut image
var donutImg;

// x and y position of the donut
var donutx;
var donuty;

// x and y components of the donut's velocity
var donutvx;
var donutvy;

// acceleration constant of the cat
var ACCELERATION = 0.5;

// note: we used a lot of global variables to make this game. this is a bad
// way to do it. if we wanted to improve this code, we could use javascript
// objects to decrease the number of global variables.

document.observe("dom:loaded", function() {
  // grab the context of the canvas, save it into variable
  var canvas = $("game_canvas");
  paintbrush = canvas.getContext("2d");
  
  // load images for the cat and the donut
  img = new Image();
  img.src = "cat.png";
  donutImg = new Image();
  donutImg.src = "donut.png";

  // initialize all the globals
  x = 10;
  y = 10;
  vx = 0;
  vy = 0;
  ax = 0;
  ay = 0;
  donutx = 300;
  donuty = 300;
  donutvx = 0;
  donutvy = 0;

  // set up the game loop. will run every 10 milliseconds.
  setInterval(function() {
    update();
    draw();
  }, 10);
});

// updates the state of the game. this entails updating each object's position
// and velocity.
function update() {
  // update the cat's position and veloctity based on its acceleration.
  // this doesn't take many lines of code, but it actually is grounded in
  // real math! (http://en.wikipedia.org/wiki/Euler_method)
  x += vx;
  y += vy;
  vx += ax;
  vy += ay;
  
  // apply friction to the cat by decreasing the velocity slightly every frame
  vx *= 0.9;
  vy *= 0.9;
  
  // update the position of the donut based on its velocity
  donutx += donutvx;
  donuty += donutvy;
  
  // make the donut chase the cat
  // first, compute the x and y components of a vector from the donut to the cat
  var dx = x - donutx;
  var dy = y - donuty;
  
  // compute the magnitude of the vector (distance from donut to cat)
  var dist = Math.sqrt(dx * dx + dy * dy);
  
  // compute the angle of the vector
  var angle = Math.atan2(dy, dx);
  
  // the force depends on the distance. a greater distance means a greater force
  // of attraction between the donut and the cat. this is really fun to play
  // around with! for example, what happens if force = 10 / dist?
  var force = dist / 1000;
  
  // now that we have the force, we accelerate the donut. this uses some trig.
  donutvx += Math.cos(angle) * force;
  donutvy += Math.sin(angle) * force;
  
  // apply friction to the donut
  donutvx *= 0.9;
  donutvy *= 0.9;
}

// renders the game to the canvas
function draw() {
  clear();
  paintbrush.drawImage(donutImg, donutx, donuty, 100, 100);  
  paintbrush.drawImage(img, x, y, 75, 44);  
}

// clears the canvas
function clear() {
  $("game_canvas").width = $("game_canvas").width;
}

// sets up the keydown event listener. used to move the cat.
document.observe("keydown", function(event) {
  if (event.keyCode == 38) {
    // move up
    ay = -ACCELERATION;
  } else if (event.keyCode == 40) {
    // move down
    ay = ACCELERATION;
  }
  if (event.keyCode == 39) {
    // move right
    ax = ACCELERATION;
  } else if (event.keyCode == 37) {
    // move left
    ax = -ACCELERATION;
  }
});

// sets up the keyup event listener. used to stop the cat's movement.
document.observe("keyup", function(event) {
  if (event.keyCode == 38 || event.keyCode == 40) {
    ay = 0;
  } else if (event.keyCode == 39 || event.keyCode == 37) {
    ax = 0;
  }
});
=======
/*
Alex Miller, CSE 190M Extra Session
5/28/2012

A simple game using the HTML5 canvas. Use the arrow keys to move the cat
around. The donut chases the cat.
*/

// cat image
var img; 

// canvas context, used to draw to the canvas (like Java's Graphics)
var paintbrush;

// x and y position of the cat
var x;
var y;

// x and y components of the cat's velocity
var vx;
var vy;

// x and y components of the cat's acceleration
var ax;
var ay;

// donut image
var donutImg;

// x and y position of the donut
var donutx;
var donuty;

// x and y components of the donut's velocity
var donutvx;
var donutvy;

// acceleration constant of the cat
var ACCELERATION = 0.5;

// note: we used a lot of global variables to make this game. this is a bad
// way to do it. if we wanted to improve this code, we could use javascript
// objects to decrease the number of global variables.

document.observe("dom:loaded", function() {
  // grab the context of the canvas, save it into variable
  var canvas = $("game_canvas");
  paintbrush = canvas.getContext("2d");
  
  // load images for the cat and the donut
  img = new Image();
  img.src = "cat.png";
  donutImg = new Image();
  donutImg.src = "donut.png";

  // initialize all the globals
  x = 10;
  y = 10;
  vx = 0;
  vy = 0;
  ax = 0;
  ay = 0;
  donutx = 300;
  donuty = 300;
  donutvx = 0;
  donutvy = 0;

  // set up the game loop. will run every 10 milliseconds.
  setInterval(function() {
    update();
    draw();
  }, 10);
});

// updates the state of the game. this entails updating each object's position
// and velocity.
function update() {
  // update the cat's position and veloctity based on its acceleration.
  // this doesn't take many lines of code, but it actually is grounded in
  // real math! (http://en.wikipedia.org/wiki/Euler_method)
  x += vx;
  y += vy;
  vx += ax;
  vy += ay;
  
  // apply friction to the cat by decreasing the velocity slightly every frame
  vx *= 0.9;
  vy *= 0.9;
  
  // update the position of the donut based on its velocity
  donutx += donutvx;
  donuty += donutvy;
  
  // make the donut chase the cat
  // first, compute the x and y components of a vector from the donut to the cat
  var dx = x - donutx;
  var dy = y - donuty;
  
  // compute the magnitude of the vector (distance from donut to cat)
  var dist = Math.sqrt(dx * dx + dy * dy);
  
  // compute the angle of the vector
  var angle = Math.atan2(dy, dx);
  
  // the force depends on the distance. a greater distance means a greater force
  // of attraction between the donut and the cat. this is really fun to play
  // around with! for example, what happens if force = 10 / dist?
  var force = dist / 1000;
  
  // now that we have the force, we accelerate the donut. this uses some trig.
  donutvx += Math.cos(angle) * force;
  donutvy += Math.sin(angle) * force;
  
  // apply friction to the donut
  donutvx *= 0.9;
  donutvy *= 0.9;
}

// renders the game to the canvas
function draw() {
  clear();
  paintbrush.drawImage(donutImg, donutx, donuty, 100, 100);  
  paintbrush.drawImage(img, x, y, 75, 44);  
}

// clears the canvas
function clear() {
  $("game_canvas").width = $("game_canvas").width;
}

// sets up the keydown event listener. used to move the cat.
document.observe("keydown", function(event) {
  if (event.keyCode == 38) {
    // move up
    ay = -ACCELERATION;
  } else if (event.keyCode == 40) {
    // move down
    ay = ACCELERATION;
  }
  if (event.keyCode == 39) {
    // move right
    ax = ACCELERATION;
  } else if (event.keyCode == 37) {
    // move left
    ax = -ACCELERATION;
  }
});

// sets up the keyup event listener. used to stop the cat's movement.
document.observe("keyup", function(event) {
  if (event.keyCode == 38 || event.keyCode == 40) {
    ay = 0;
  } else if (event.keyCode == 39 || event.keyCode == 37) {
    ax = 0;
  }
});
>>>>>>> ac9dd56d737d9347102bf97ea5da025b6ebcf909
