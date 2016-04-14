// JavaScript Document
$(document).ready(function () {
	createGameBoard();
});

var OFF = 0;
var ON = 1;
var SIZE_GAME_BOARD = 5;
//var cellLightState = [[ON, ON, OFF, ON, ON], [OFF, OFF, OFF, OFF, OFF], [ON, ON, OFF, ON, ON], [OFF, OFF, OFF, OFF, ON], [ON, ON, OFF, OFF, OFF]]; 
var cellLightState = [[ON, OFF, OFF, OFF, ON], [ON, ON, OFF, ON, ON], [OFF, OFF, ON, OFF, OFF], [ON, OFF, ON, OFF, OFF], [ON, OFF, ON, ON, OFF]]; 
//var cellLightState = [[ON, ON, ON, ON, ON], [ON, ON, ON, ON, ON], [ON, ON, ON, ON, ON], [ON, ON, ON, ON, ON], [ON, ON, ON, ON, ON]];
var lightStateImg = ["img/lightOff.png", "img/lightOn.png"]; 
var flagWin = false;

function createGameBoard()
{
	var tableObj = $('#gameBoard')[0];
	
	// Create the game board
	for(var rowIndex = 0; rowIndex < SIZE_GAME_BOARD; rowIndex++)
	{
		var insertRow = tableObj.insertRow(-1);
		
		for(var colIndex = 0; colIndex < SIZE_GAME_BOARD; colIndex++)
		{
			var insertCell = insertRow.insertCell(-1);
			
			insertCell.style.backgroundImage = "url('" + lightStateImg[cellLightState[rowIndex][colIndex]] + "')";
			
			insertCell.id = rowIndex.toString() + '_' + colIndex.toString();
		}
	}
	
	// Assign the click listener for td elements
	$('#gameBoard td').click(function(event) {
		if(!flagWin)
		{
			toggleLightStateNeighbor(event);
			flagWin = isWin();
			if(flagWin)
			{
				alert("Congratulations! You win");
			}
		}
	});
}

function toggleLightStateNeighbor(event)
{
	var clickedCell = event.target;
	var componentId = clickedCell.id.split('_');
	
	var rowIndex = parseInt(componentId[0], 10);
	var colIndex = parseInt(componentId[1], 10);
	
	for(var rowOffset = -1; rowOffset < 2; rowOffset++)
		for(var colOffset = -1; colOffset < 2; colOffset++)
			if(rowOffset == 0 || colOffset == 0)
			{
				var newRow = rowIndex + rowOffset;
				var newCol = colIndex + colOffset;
			
				if(isValidCoord(newRow, newCol))
				{
					cellLightState[newRow][newCol] = (cellLightState[newRow][newCol] + 1) % 2;
				
					$('#' + newRow.toString() + '_' + newCol.toString()).css(
						{
							'background-image' : "url('" + lightStateImg[cellLightState[newRow][newCol]] + "')"
						}
					);
				}
			}
}

function isValidCoord(newRow, newCol)
{
	if(newRow < 0 || newCol < 0 || newRow >= SIZE_GAME_BOARD || newCol >= SIZE_GAME_BOARD)
		return false;
	return true;
}

function isWin()
{
	for(var rowIndex = 0; rowIndex < SIZE_GAME_BOARD; rowIndex++)
		for(var colIndex = 0; colIndex < SIZE_GAME_BOARD; colIndex++)
			if(cellLightState[rowIndex][colIndex] == ON)
				return false;
				
	return true;
}