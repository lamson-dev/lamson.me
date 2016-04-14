/*
		Son Nguyen
		CSE 190 M
		Homework #8

		TA: Rishi Goutam		
		
		JavaScript code for Baby Names page
		This code fetches and processes text, HTML, XML, and JSON data from the following URL:
		https://webster.cs.washington.edu/cse190m/babynames.php		
*/

"use strict";

// constant
var URL = "proxy.php?url=https://webster.cs.washington.edu/cse190m/babynames.php";

// attach event handler to button
// fecth names into dropdown list
window.onload = function() {
	$("search").observe("click",searchClick);
	requestBabyNames();
};

// called when Search button is clicked
// request and show a baby name data onto page
function searchClick() {

	clearOldData();
	
	// do nothing if no name
	if ($("allnames").value=="") { 
		$("resultsarea").hide();	// need this?
		return; 
	}

	var name = $("allnames").value.toLowerCase();
	var gender = "";
	
	if ($("genderm").checked) {
		gender = $("genderm").value;
	} else {
		gender = $("genderf").value;
	}	
	requestMeaning(name);
	requestRanking(name, gender);
	requestCelebs(name, gender);	
	
	$("resultsarea").show();
}

// request baby names data from php file to fetch into dropdown list
function requestBabyNames() {
	new Ajax.Request(URL, {
			method: "get",
			parameters: {type: "list"},
			onSuccess: fetchNames,
		    onFailure: ajaxFailure,
		    onException: ajaxFailure
	  	});
}

// request a baby name's meaning data from php file to fetch onto page
function requestMeaning(babyName) {
	new Ajax.Request(URL, {
			method: "get",
			parameters: {type: "meaning", name: babyName},
			onSuccess: fetchMeaningData,
		    onFailure: ajaxFailure,
		    onException: ajaxFailure
	  	});

}

// request a baby name's ranking data from php file to fetch onto page
function requestRanking(babyName, babyGender) {	
	new Ajax.Request(URL, {
			method: "get",
			parameters: {type: "rank", name: babyName, gender: babyGender},
			onSuccess: fetchRankingData,
		    onFailure: ajaxFailure,
		    onException: ajaxFailure
	  	});

}

// request celebrities data with a baby name from php file to fetch onto page
function requestCelebs(babyName, babyGender) {
	new Ajax.Request(URL, {
			method: "get",
			parameters: {type: "celebs", name: babyName, gender: babyGender},
			onSuccess: fetchCelebsData
	  	});

}

// fetch list of names into dropdown list
function fetchNames(ajax) {

	var names = ajax.responseText.split("\n");
	for (var i = 0; i < names.length; i++) { 
		var optionTag = document.createElement("option");
		optionTag.innerHTML = names[i];
		//optionTag.setAttribute("value", names[i].toLowerCase());
		$("allnames").appendChild(optionTag);
	}
	
	$("allnames").disabled = false;	
	$("loadingnames").hide();
}

// fetch meaning of a name onto page
function fetchMeaningData(ajax) {
	var textHTML = ajax.responseText;
	$("meaning").innerHTML = textHTML;
	$("loadingmeaning").hide();	
}

// fetch ranking of a name onto page
function fetchRankingData(ajax) {

	var baby = ajax.responseXML.getElementsByTagName("baby")[0];
	var ranks = baby.getElementsByTagName("rank");
	
	var trTag1 = document.createElement("tr");
	var trTag2 = document.createElement("tr");
	
	for (var i = 0; i < ranks.length; i++) {
		
		var year = ranks[i].getAttribute("year");
		var popularity = ranks[i].firstChild.nodeValue;
		
		var thTag = document.createElement("th");
		thTag.innerHTML = year;
		trTag1.appendChild(thTag);
		
		var tdTag = document.createElement("td");
		var divTag = document.createElement("div");
		
		var barHeight = 0; 
		if (popularity > 0) {
			barHeight = parseInt((1000-popularity)/4);
		}
		
		if (popularity >= 1 && popularity <= 10) {
			divTag.addClassName("highrank");
		}
		
		divTag.style.height = barHeight + "px";
		divTag.innerHTML = popularity;
		
		tdTag.appendChild(divTag);
		trTag2.appendChild(tdTag);
	}
	$("graph").appendChild(trTag1);
	$("graph").appendChild(trTag2);
	$("loadinggraph").hide();
}

// fetch celebs data onto page
function fetchCelebsData(ajax) {
	var data = JSON.parse(ajax.responseText);
	for (var i = 0; i < data.actors.length; i++) { 
	
		var firstName = data.actors[i].firstName;
		var lastName = data.actors[i].lastName;
		var filmCount = data.actors[i].filmCount;
		
		var liTag = document.createElement("li");
		liTag.innerHTML = firstName + " " + lastName + " (" + filmCount + " films)";
		$("celebs").appendChild(liTag);
	}	
	$("loadingcelebs").hide();
}

// display no ranking data on page
function noRankingData() {
	$("norankdata").show();
	$("norankdata").innerHTML = "There is no ranking data for that name/gender combination.";
	$("loadinggraph").hide();
}

// clear old data from previous search for new search
function clearOldData() {
	$("meaning").innerHTML = "";
	$("graph").innerHTML = "";
	$("norankdata").innerHTML = "";
	$("norankdata").hide();
	$("celebs").innerHTML = "";	
	$("errors").innerHTML = "";	
	$("loadingmeaning").show();
	$("loadinggraph").show();
	$("loadingcelebs").show();
}

// display errors on page if any ajax request failed
function ajaxFailure(ajax, exception) {

	// show no ranking data for error 410
	if (ajax.status == 410) {
		noRankingData();
		return;
	}
	
	$("resultsarea").show();
	$("loadingnames").hide();
	$("loadingmeaning").hide();
	$("loadinggraph").hide();
	$("loadingcelebs").hide();
		
	$("errors").innerHTML = "Error making Ajax request:" + 
	    "\n\nServer status:\n" + ajax.status + " " + ajax.statusText + 
	    "\n\nServer response text:\n" + ajax.responseText;
	if (exception) {
		throw exception;
	}

}