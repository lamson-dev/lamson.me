/*
		Son Nguyen
		
		JavaScript code for Fifteen Puzzle page
		Implement a classic game consisting of a 4x4 grid of numbered
		squares with one square missing.  The object of the game is to arrange 
		the tiles into numerical order by repeatedly sliding a square that 
		neighbors the missing square into its empty space.
*/

"use strict";

// declares variables
var emptyRow = 3;
var emptyCol = 3;
var tileSize = 100;
var puzzleSize = 4;
var MOVE_TIMES = 200; // for random shuffle algorithm
var NUM_BACKGROUNDS = 4; // for random background
var initFlag = true;

// attach events handlers to objects, set default values for variables
window.onload = function() {
	initializePuzzle();
	$("shufflebutton").observe("click", shuffleClick);	
};

// initialize puzzle, place 15 tiles in their correct order
function initializePuzzle() {

	var	allTiles = getAllTiles();
	
	var index = 0;
	var xPos = 0;
	var yPos = 0;
	var randBackground = "url(\"backgrounds/img" + Math.floor(Math.random()*NUM_BACKGROUNDS) + ".jpg\")";	
	
	// position, attach events handlers for all tiles
	while (index < allTiles.length) {
		var tile = allTiles[index];
				
		tile.observe("click", tileClick);
		
		// positioning
		tile.setStyle({
			left: xPos + "px",
			top: yPos + "px",
			backgroundImage: randBackground,
			backgroundPosition: "-" + xPos + "px -" + yPos + "px"
		});
					
		// update tile and its position
		index++;
		xPos += tileSize;
		if (index%puzzleSize == 0) {
			xPos = 0;
			yPos += tileSize;
		}

	}
	
	assignMoveableClass(allTiles);
	
	shuffleClick();
	initFlag = false;
}

// Called when user click Shuffle button
// Randomly rearrange the the tiles of puzzle
function shuffleClick() {
	
	// randomly pick a moveable tile and move it to empty space
	for (var i = 0; i < MOVE_TIMES; i++) {
		var	moveableTiles = getMoveableTiles();
		var random = parseInt(Math.random()*moveableTiles.length);
		moveTile(moveableTiles[random]);
	}
	
}

// Called when user click on any tile
// Move a tile to empty space if it is moveable
function tileClick() {
	if (isMoveable(this)) {
		moveTile(this);
	}
}

// Check if a tile is moveable
function isMoveable(tile) {

	var tileCol = getCol(tile);
	var tileRow = getRow(tile);
	
	// the moveable tiles are the one which is either 
	// to the left/right or top/bottom of the empty square
	if ((Math.abs(tileCol-emptyCol)==1 && tileRow==emptyRow) || 
			(Math.abs(tileRow-emptyRow)==1 && tileCol==emptyCol)) {
		return true;
	} else {
		return false;
	}
}

// Move tile to empty space
function moveTile(tile) {
	
	// get the original tile's row/col
	var tileCol = getCol(tile);
	var tileRow = getRow(tile);
	
	// calculate x/y position of emtpy square
	var xPosEmpty = emptyCol*tileSize;
	var yPosEmpty = emptyRow*tileSize;
	
	// move the tile to the empty square
	tile.setStyle({
		left: xPosEmpty + "px",
		top: yPosEmpty + "px"
	});	
	
	// update the empty square location at the old tile position
	emptyCol = tileCol;
	emptyRow = tileRow;
	
	assignMoveableClass(getAllTiles());
	
	if (isWin(initFlag)) {
		alert("congrats!");
	}


}

// Assign class "moveable" for all moveable tiles
function assignMoveableClass(tiles) {

	for (var i = 0; i < tiles.length; i++) {
		if (isMoveable(tiles[i])) {
			tiles[i].addClassName("moveable");
		} else {
			tiles[i].removeClassName("moveable");
		}
	}
}

function isWin(flag) {

	if (flag) { return; }
	
	var	allTiles = getAllTiles();
	
	var index = 0;
	var xPos = 0;
	var yPos = 0;
	var isDone = true;
	
	// position, attach events handlers for all tiles
	while (index < allTiles.length) {
	
		var tile = allTiles[index];		
		var xPosTile = tile.getStyle("left");
		var yPosTile = tile.getStyle("top");
		
		var left = xPos + "px";
		var top = yPos + "px";
		
		if ((xPosTile != left) || (yPosTile != top)) {
			isDone = false;
			break;
		}
							
		// update tile and its position
		index++;
		xPos += tileSize;
		if (index%puzzleSize == 0) {
			xPos = 0;
			yPos += tileSize;
		}
	}
	
	return isDone;

}

// Return the column number of a tile
function getCol(tile) {	return parseInt(tile.style.left)/tileSize; }

// Return the row number of a tile
function getRow(tile) {	return parseInt(tile.style.top)/tileSize; }

// Return an array of DOM elements, which are the puzzle tiles
function getAllTiles() { return $$("#puzzlearea div"); }

// Return an array of DOM elements, which are the moveable tiles
function getMoveableTiles() { return $$("#puzzlearea .moveable"); }