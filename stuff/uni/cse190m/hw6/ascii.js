/*
		Son Nguyen
		CSE 190 M
		Homework #6

		TA: Rishi Goutam		
		
		JavaScript code for ASCII Animation Viewer
		The code listens to events from user to let user create/view
		ASCIImations 
*/

"use strict";

// declares variables
var playing;
var animation;
var frames;
var currentFrame;
var size;
var speed;
var DEFAULT_SPEED = 250;
var timer = null;

// attach events handlers to objects, set default values for variables
window.onload = function() {
	$("startButton").observe("click", start);
	$("stopButton").observe("click", stop);
	$("stopButton").disabled = true;
	$("animation").observe("change", changeAnimation);
	$("size").observe("change", changeSize);
	$("turbo").observe("change", changeSpeed);
		
	size = $("size").value;
	speed = DEFAULT_SPEED;
	playing = false;
	currentFrame = 0;
	
};

// Called when user click Start button
// Breaks text currently in textbox apart to produce frames of animation
function start() {
	
	//store frames of animation from broken text
	frames = $("textbox").value.split("=====\n");
	
/*
	//this resume the current animation
	if ($("stopButton").disabled) {
		frames = $("textbox").value.split("=====\n");
	}
*/
	
	playing = true;
	
	//disable some of controls
	$("stopButton").disabled = false;
	$("startButton").disabled = true;
	$("animation").disabled = true;
	
	//run annimation in certain speed
	runAnimation(speed);
}

// Called when user click Stop button
// Halts any animation in progress
// Returns previous frame of animation to the box
function stop() {
	playing = false;
	$("startButton").disabled = false;
	$("animation").disabled = false;
		
	clearInterval(timer);
	timer = null;
	
	showLastFrame()
}

// Called when user change type of animation
// Displays all text of chosen animation
function changeAnimation() {
	
	//get type of animation, reset currentFrame
	animation = $("animation").value;
	currentFrame = 0;	
	
	//display all text of chosen animation when animation is not playing
	if (!playing) { //not really need this (it's for resuming animation)
		$("textbox").value = ANIMATIONS[animation];
		$("stopButton").disabled = true;

	}
}

// Called when user change size of text
// Immediately sets font size in the main text area
function changeSize() {
	size = $("size").value;
	$("textbox").style.fontSize = size;
}

// Called when user change speed of animation
// Takes effect immediately, only change the delay
function changeSpeed() {
	if ($("turbo").checked) {
		speed = parseInt($("turbo").value);
	} else {
		speed = DEFAULT_SPEED;
	}
	
	if ($("startButton").disabled) {
		clearInterval(timer);
		timer = null;
		runAnimation(speed);
	}
}

// Use timer event to run frame by frame animation
function runAnimation(speed) {
	if (!timer) {
		timer = setInterval(changeFrame, speed);
	}
}

// Called when animation is running, change frame of animation
function changeFrame() {
	$("textbox").value = frames[currentFrame];
	currentFrame++;
	if (currentFrame==frames.length) {
		currentFrame = 0;
	}
	
}

// Called when user click Stop button
// Display the previous frame of animation in textbox
function showLastFrame() {
	if (currentFrame==0) {
		currentFrame = frames.length-1;
	} else {
		currentFrame--;
	}
	$("textbox").value = frames[currentFrame];
}