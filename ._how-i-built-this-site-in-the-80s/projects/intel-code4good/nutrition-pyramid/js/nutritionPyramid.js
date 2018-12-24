// JavaScript Document
var numCorrect = 0;
var MAX_CORRECT = 4;

function allowDrop(m_event)
{
	m_event.preventDefault();
}

function drag(m_event)
{
	var file_id = m_event.target.id;
	var stringSplit = file_id.split('/');
	var fileNameCompo = stringSplit[stringSplit.length - 1].split('.');
	var dragId = fileNameCompo[0];
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
	
	if(!isCorrectAns(targetId, dragId))
	{
		alert("Your matching is not correct. Please redo it");
	}
	else
	{
		
		
		$('#' + dragId + '_question').remove();
		
		$('#' + dragId + '_part').css(
			{
				'background-image': "url('img/" + dragId + "_ans.png')",
				'background-position': "center center"
			}
		);
		
		numCorrect += 1;		
		if(numCorrect == MAX_CORRECT)
		{

/* 			var rtnAddress = <?php $rtnAddress; ?> */
			alert("woohooo! You unlocked 1 GAME!!!");
			window.location.href = "http://lamson.me/projects/intel/games/wackamole/wackamole.html";

		}
	}
}

function isCorrectAns(answer_key, answer)
{
	
	
	var component = answer_key.split('_');
	var str1 = component[0];

	component = answer.split('_');
	var str2 = component[0];
	
	if(str1 == str2)
		return true;
	return false;
}