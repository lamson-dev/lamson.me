/*

A simple game using the HTML5 canvas. Use the arrow keys to move the cat
around. The donut chases the cat.
*/

// cat image
var img; 

var canvas;
var level = 1;
var score = 0;

// canvas context, used to draw to the canvas (like Java's Graphics)
var paintbrush;

// x and y position of the cat
var x;
var y;

// donut image
var donutImg;
var touchImg;

// x and y position of the donut
var donutx;
var donuty;

var OFFSET = 180;


var NUM_ROWS = 5;
var NUM_COLS = 5;

// note: we used a lot of global variables to make this game. this is a bad
// way to do it. if we wanted to improve this code, we could use javascript
// objects to decrease the number of global variables.

$(document).ready(function() {
/* 	alert("haha"); */
  // grab the context of the canvas, save it into variable
  canvas = $('#game_canvas')[0];
  paintbrush = canvas.getContext("2d");
  
  // add event listeners
 
/*   canvas.addEventListener("onclick", handleStart, false); */
    canvas.addEventListener("touchstart", handleStart, false);
/*   canvas.addEventListener("touchend", handleEnd, false); */
/*   canvas.addEventListener("touchcancel", handleCancel, false); */
/*   canvas.addEventListener("touchleave", handleLeave, false); */
/*   canvas.addEventListener("touchmove", handleMove, false); */
  
  
  // load images for the cat and the donut
  img = new Image();
  img.src = "cat.png";
  donutImg = new Image();
  donutImg.src = "donut.png";
  touchImg = new Image();
  touchImg.src = "touch.png";

  // initialize all the globals
  x = 10;
  y = 10;
  donutx = 300;
  donuty = 300;

  // set up the game loop. will run every 10 milliseconds.
  setInterval(function() {
  		draw();
  	}, 400);
  
}
);

// updates the state of the game. this entails updating each object's position
// and velocity.
function randPosition() {
	var randNum = Math.floor(Math.random()*(NUM_ROWS*NUM_COLS));
	donutx = (canvas.width/NUM_COLS) * (randNum % NUM_COLS);
	donuty = (canvas.height/NUM_ROWS) * Math.floor(randNum / NUM_COLS);
}

var xPosBad = [];
var yPosBad = [];
var xPosGood = [];
var yPosGood = [];

function resetPosArray() {
	xPosBad = [];
	yPosBad = [];
	xPosGood = [];
	yPosGood = [];
}

// renders the game to the canvas
function draw() {
	var blankProb = 5;
	var goodProb = 5;
	var badProb = 5;
	var bothProb = 5;
	var stayProb = 25 - level;
	var randNum = Math.floor(Math.random()*(goodProb+badProb+bothProb+blankProb+stayProb));
	var randNumGood = Math.floor(Math.random()*4);
	var randNumBad = Math.floor(Math.random()*4);

	var count = 0;	
 	if (randNum < blankProb) {
 		clear();
 	} else if (randNum < (blankProb+goodProb)) {
	 	clear();
	 	resetPosArray();
		while (count<randNumGood) {
  			randPosition();
	  		paintbrush.drawImage(img, donutx, donuty, 100, 100);
	  		xPosGood[count] = donutx;
	  		yPosGood[count] = donuty;
	  		count++;
		} 
 	
 	} else if (randNum < (blankProb+goodProb+badProb)) {
		clear();
	 	resetPosArray();		
		while (count<randNumBad) {
  			randPosition();
	  		paintbrush.drawImage(donutImg, donutx, donuty, 100, 100);
	  		xPosBad[count] = donutx;
	  		yPosBad[count] = donuty;
	  		count++;
		} 
	} else if (randNum< (blankProb+goodProb+badProb+bothProb)) {
		clear();
	 	resetPosArray();				
		while (count<randNumGood) {

  			randPosition();
	  		paintbrush.drawImage(img, donutx, donuty, 100, 100);
	  		xPosGood[count] = donutx;
	  		yPosGood[count] = donuty;
	  		count++;
		} 
		count=0;
		while (count<randNumBad) {
  			randPosition();
	  		paintbrush.drawImage(donutImg, donutx, donuty, 100, 100);
	  		xPosBad[count] = donutx;
	  		yPosBad[count] = donuty;
	  		count++;
		} 
	}
	
/*
	alert(xPosBad + " " + yPosBad);
	alert(xPosGood + " " + yPosGood);
*/
}

// clears the canvas
function clear() {
	canvas.width = canvas.width;
}

function updateScore(good) {
	
	if (good) {
		score += 20;
	} else {
		score -=10;
	}
	
/* 	alert(score); */
	
 	$("#score").text(score + "");
	levelUp();
}

function levelUp() {
	if (score > (level*50)) { level++; }
}

function handleStart(evt) {
  	evt.preventDefault();
  	var touches = evt.changedTouches;

  	for (var i=0; i<touches.length; i++) {
  		xTouchPos = touches[i].pageX - OFFSET;
  		yTouchPos = touches[i].pageY;
  		
  		row = Math.floor(yTouchPos/(canvas.width/NUM_COLS));
  		col = Math.floor(xTouchPos/(canvas.height/NUM_ROWS));
  		
/*
  		alert(xTouchPos + " " + yTouchPos);
  		alert(row + " " + col);

  		alert(col*canvas.width/NUM_COLS + " " + row*canvas.width/NUM_ROWS);
*/

  		if (isBadTouch(col,row)) {
			updateScore(false);
  		}
  		
  		if (isGoodTouch(col,row)) {
			updateScore(true);
  		}
		
	  	paintbrush.drawImage(touchImg, col*canvas.width/NUM_COLS, row*canvas.width/NUM_ROWS, 100, 100);  	}

}

function isBadTouch(col,row) {
	for (var i=0; i<xPosBad.length; i++) {
		xPos = col * canvas.width/NUM_COLS;
		yPos = row * canvas.width/NUM_ROWS;
		if (xPos==xPosBad[i] && yPos==yPosBad[i]) {
			return true;
		}
	}
	return false;
}

function isGoodTouch(x,y) {
	for (var i=0; i<xPosGood.length; i++) {
		xPos = col * canvas.width/NUM_COLS;
		yPos = row * canvas.width/NUM_ROWS;
		if (xPos==xPosGood[i] && yPos==yPosGood[i]) {
			return true;
		}
	}
	return false;
}

function handleEnd(evt) {
  evt.preventDefault();
  var touches = evt.changedTouches;
   
/*
  ctx.lineWidth = 4;
         
  for (var i=0; i<touches.length; i++) {
    var color = colorForTouch(touches[i]);
    var idx = ongoingTouchIndexById(touches[i].identifier);
     
    ctx.fillStyle = color;
    ctx.beginPath();
    ctx.moveTo(ongoingTouches[i].pageX, ongoingTouches[i].pageY);
    ctx.lineTo(touches[i].pageX, touches[i].pageY);
    ongoingTouches.splice(i, 1);  // remove it; we're done
*/
}