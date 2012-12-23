$(document).ready(function() {
		
	var main = $('#mainbox');
	var div = $('<div>');
	for (var i = 1; i <= 15; i++) {
		for (var j = 1; j <= 10; j++) {
			main.append(div.clone().addClass("grid off" + " row" + i + " col" + j));			
		}
	}
	
	// why doesn't work with function?
	
	// createTetrisBox();
	
	$('#start').click(startClick);
	$('#stop').click(stopClick);
	$(document).keydown(function(event) {
	
		var key = event.which;

		if(key == 37 || key == 38 || key == 39 || key == 40){
			event.preventDefault();
		}
		
		switch(key) {
			case 37:
				shiftLeft();
				break;
			case 39:
				shiftRight();
		  		break;
		  	case 40:
		  		shiftDown();
		  		break;
		  	case 38:
		  		rotate();
		  		break;
			default:
		  		break;
		}
		
	});
});

var pieceRows = [[1,1,2,2], [1,1,1,1], [1,1,2,1], [1,1,2,2], [1,1,2,2], [1,1,2,3], [1,1,2,3]];
var pieceCols = [[4,5,4,5], [4,5,6,7], [4,5,5,6], [4,5,5,6], [5,6,5,4], [4,5,5,5], [4,5,4,4]];
var pieceColors = ["yellow", "blue", "purple", "red", "green", "navy", "orange"];

//active squares are paired by index (two parallel arrays)
var arows = [0,0,0,0];
var acols = [0,0,0,0];
var acolor = "navy";

var SPEED = 310;
var POINT = 5;
var score = 0;

var timer = null;
var initial = null;

function createTerisBox() {

	var main = $('#mainbox');
	var div = $('<div>');
	for (var i = 1; i <= 15; i++) {
		for (var j = 1; j <= 10; j++) {
			main.append(div.clone().addClass("grid off" + " row" + i + " col" + j));			
		}
	}

}

function startClick() {
	
	
	//initialize game
	SPEED = 310;
	resetScore();
	//clear all squares
	var squares = $(".grid");
	for (var i = 0; i < squares.length; i++) {
		var square = $(squares[i]);
		//var sq2 = getSq(3,3);
		//if(sq2.hasClass("grid")) alert("has grid");
		removeColor(square);
		if (square.hasClass("on")) { square.removeClass("on"); }
		if (!square.hasClass("off")) { square.addClass("off"); }
		
	}
	
	createNewPiece();
	
	if (!timer) {
		timer = setInterval(move, SPEED);
	}
}

function stopClick() {
	clearInterval(timer);
	timer = null;
}

function move() {
	//var square = $(".row" + arow + ".col" + acol);
	
	//check if piece can move down a square
	if (canMoveDown()) {
		shiftDown();	
	} else {
	
		//create new square at the top, update active
		createNewPiece();
	}
}

//creates new piece
function createNewPiece(){

	//alert(checkFullRows());
	removeFullRows(checkFullRows());
	
	//square, bar, tee, s, s-in, el, el-in
	var random = parseInt(Math.random() * 7);	
	
/*
	var pieceRows = [[1,1,2,2]];
	var pieceCols = [[4,5,4,5]];
	var random = parseInt(Math.random() * 1);	
*/
	
	arows = pieceRows[random].slice();
	acols = pieceCols[random].slice();
	acolor = pieceColors[random].slice();
	
	//check if the new squares are already active - if so, we lose
	var losing = false;
	
	for(var i = 0; i < 4; i++){
		var square = getSq(arows[i], acols[i]);
		//	alert("hi!");
		if(square.hasClass("on")){
			losing = true;
		}
	}
	
	if (losing) {
		loseGame();
	} else {
		turnOnActive();
	}
}

function loseGame() {
	alert("You have lost the game!");
	clearInterval(timer);
	timer = null;
}

function canMoveDown(){

	for (var i = 0; i < 4; i++) {
		if (!canMoveSqDown(arows[i] , acols[i])) { return false; }		
	}
	return true;
}

function canMoveRight(){
	for (var i = 0; i < 4; i++) {
		if (!canMoveSqRight(arows[i] , acols[i])) { return false; }
	}
	return true;
}

function canMoveLeft(){
	for (var i = 0; i < 4; i++) {
		if (!canMoveSqLeft(arows[i] , acols[i])) { return false; }
	}
	return true;
}


function canMoveSqDown(row, col) {

	//if at bottom of the screen, then we can't move
	if(row == 15){ return false}
	//if square below us is active, then we can move
	if(isActive(row+1, col)){ return true}
	//get the square below the current square
	var nrow = row+1;
	var sqNext = getSq(nrow, col);
	return(sqNext.hasClass("off"));
}

function canMoveSqLeft(row, col) {
	if(col == 1){ return false; }
	//if square left of us is active, then we can move there
	if(isActive(row, col-1)){ return true}
	var lfSq = getSq(row, col-1);
	return (lfSq.hasClass("off"));
}

function canMoveSqRight(row, col) {
	if(col == 10){ return false; }
	//if square right of us is active, then we can move there
	if(isActive(row, col+1)){return true}
	var rtSq = getSq(row, col+1);
	return (rtSq.hasClass("off"));
}
//used to test if an active square could move to another location
function canMoveSqTo(row, col) {
	if(col < 1 || col > 10 || row < 1 || row > 15){ return false;}
	if(isActive(row, col) ){return true;}
	var loc = getSq(row, col);
	return (loc.hasClass("off"));
}


function shiftLeft() {
	if(canMoveLeft()){
		//turn off all squares
		turnOffActive();
		//move shape left
		for(var i =0; i < 4; i++){
			acols[i] -= 1;
		}
		//turn on the squares
		turnOnActive();
	}
}

function shiftRight() {
	if(canMoveRight()){
		//turn off all squares
		turnOffActive();
		//move shape right
		for(var i =0; i < 4; i++){
			acols[i] += 1;
		}
		//turn on the squares
		turnOnActive();
	}
}

function shiftDown() {
	if(canMoveDown()){
		//turn off all squares
		turnOffActive();
		//move shape down
		for(var i = 0; i < 4; i++){
			arows[i] += 1;
		}
		//turn on the squares
		turnOnActive();
	}
}

function rotate() {
	//choose square to rotate around
	var rcent = arows[2];
	var ccent = acols[2];
	//get offsets from center point (left, and right)
	var offsetsL = [0,0,0,0];
	var offsetsU = [0,0,0,0];
	for(var i = 0; i < 4; i++){
		offsetsL[i] = ccent - acols[i];
		offsetsU[i] = rcent - arows[i];
	}
	var nrows = [0,0,0,0];
	var ncols = [0,0,0,0];
	//calculate new position
	for(var i = 0; i < 4; i++) {
		nrows[i] = rcent - offsetsL[i];
		ncols[i] = ccent + offsetsU[i]; 
	}
	//check if new position is valid
	var canRotate = true;
	for(var i = 0; i < 4; i++){

		if(!canMoveSqTo(nrows[i], ncols[i])){
			canRotate = false;
		}
	}
	//rotate if valid
	if(canRotate){
		turnOffActive();
		arows = nrows;
		acols = ncols;
		turnOnActive();
	}
}

function isActive(row, col) {
	for(var i = 0; i < 4; i++){
		if(arows[i] == row && acols[i] == col){ return true}
	}
	return false;
}

function turnOffActive(){
		for(var i =0; i < 4; i++){
			var square = getSq(arows[i], acols[i]);
			square.removeClass("on").addClass("off");			
			square.removeClass(acolor);
		}
}

function turnOnActive(){
		for(var i =0; i < 4; i++){
			var square = getSq(arows[i], acols[i]);
			square.removeClass("off").addClass("on");					
			square.addClass(acolor);
		}
}

//shifts down all the rows above a certain row,
//specified as input parameter
function shiftUpperRowsDown(rowNum) {
	//start at the row above, and work your way up
	for (var i = rowNum - 1; i >= 1; i--) {
		shiftDownRow(i);
	}
}

//shifts a single row down onto the row below it.
function shiftDownRow(rowNum) {
	for (var i = 1; i <= 10; i++) {
	
		var sq = getSq(rowNum, i);
		var color = colorOf(sq);
		var sqBelow = getSq(rowNum + 1, i);
		removeColor(sqBelow);
		if (sq.hasClass("on")) {
			sqBelow.addClass("on " + color).removeClass("off"); 
		} else {
			sqBelow.addClass("off " + color).removeClass("on");
		}
	}
}

function removeFullRows(rowNumbers) {
	if (rowNumbers.length == 0) {
		return;
	} else {
	
		// remove all full rows
		for (var i = 0; i < rowNumbers.length; i++) {
			
			//maybe light up row, then remove?
			
			shiftUpperRowsDown(rowNumbers[i]);
		}
		
	}
}

function checkFullRows() {
	//array of full rows
	var numFullRows = 0;
	var fullRows = [];
	
	for (var i = 1; i <= 15; i++) {
		var row = getRow(i);				
		var isFull = true;	
		for (var j = 0; j < 10; j++) {		
			if ($(row[j]).hasClass("off")) {
				isFull = false;
				break;
			}		
		}
		
		if (isFull) {
			fullRows[numFullRows] = i;	
			numFullRows++;
		}
	}
	
	if (numFullRows > 0) {
		updateScore(numFullRows);
	}
	
	return fullRows;
}

function getRow(row){
	return $(".row" + row);
}

function getSq(row, col){
	return $(".row" + row + ".col" + col);
}

function colorOf(square) {
	for(var i = 0; i < pieceColors.length; i++){
		if(square.hasClass(pieceColors[i])){
			return pieceColors[i];
		}
	}
	return "";
}

function removeColor(square) {
	for(var i = 0; i < pieceColors.length; i++){
		//should be ok to remove class even if it's not there
		square.removeClass(pieceColors[i]);
	}
}

function updateScore(power) {
	score += Math.pow(POINT,power);
	$("#score").text(score + "");
	speedUp();
}

function resetScore() {
	score = 0;
}
function speedUp() {
	SPEED -= 5;
	clearInterval(timer);
	timer = setInterval(move, SPEED);
}


