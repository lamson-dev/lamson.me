// JavaScript Document
var numCorrect = 0;
var MAX_CORRECT = 3;

function allowDrop(m_event)
{
	m_event.preventDefault();
}

function drag(m_event)
{
	m_event.dataTransfer.setData("Text", m_event.target.id);
}

function drop(m_event)
{
	m_event.preventDefault();
	var fileURL = m_event.dataTransfer.getData("Text");
	var targetId = m_event.target.id;
	
	var stringSplit = fileURL.split('/');
	var fileNameCompo = stringSplit[stringSplit.length - 1].split('.');
	var dragId = fileNameCompo[0];
	
	if(!isCorrectAns(dragId))
	{
		alert("Your choice is not correct. Please redo it");
	}
	else
	{
	/*	alert(targetId);
		$('#' + targetId).append($('#' + dragId)); */
		var component = dragId.split('_');
		m_event.target.appendChild(document.getElementById(component[1]));
		$('#' + dragId).remove();
		
		numCorrect += 1;
		if(numCorrect == MAX_CORRECT)
		{
			alert("You WON ANOTHER GAMMMMMEEEE! YAHOOOOOOO!!!!");
			window.location.href = "http://projects.lamson.me/intel/games/LightOutGame/index.html";
			// Win. Enter what you want to do after winning
		}
	}
}

function isCorrectAns(answer)
{
	var component = answer.split('_');
	var str1 = component[0];

	if(str1 == 'yes')
		return true;
	return false;
}