/*
		Son Nguyen
		CSE 190 M
		Homework #9

		TA: Rishi Goutam		
		
		JavaScript code for Remember the Cow page
		
		Uses AJAX to post/get user To-Do List to/from webservice
		Adds effects to page using Scriptaculous Library
		Pops up flash messages on top of page
		
*/

"use strict";

// Get flash message on index.php AND todolist.php
// Attach event handler to buttons
document.observe("dom:loaded", function() { // same as window.onload = function() { };
	getFlashMessage();
	if ($("add")) { // only attach event handler to objects on todolist.php
		$("add").observe("click", addClick);
		$("delete").observe("click", deleteClick);
		getTodoList();
	}
});

// Called when Add To Bottom button is clicked
// Add user input text to the bottom of the list
function addClick() {

	if ($("itemtext").value == "") { // do nothing when there is no input
		$("itemtext").focus();	
		return;
	}
	
	var li = document.createElement("li");	
	li.innerHTML = $("itemtext").value.escapeHTML();
	$("todolist").appendChild(li);
	
	appearEffects(li);
	
	Sortable.create($("todolist"), { // make list Sortable so that its
		onChange: listItemUpdate	// elements can be dragged up and down
	});
	
	$("itemtext").value = ""; 	// reset textbox
	postTodoList();
}

// Called when Delete Top Item button is clicked
// Removes the top item of the list
function deleteClick() {
	var todolist = $$("#todolist li");
	if (todolist.length==0) {
		$("itemtext").focus();	
		return;
	}
	disappearEffects(todolist[0]);
}

// Adds effects to the appearance of list item
function appearEffects(obj) {
	obj.hide();
	obj.grow({ 
		afterFinish: function() {
			obj.shake();
		}
	});
}

// Adds effects to the disappearance of list item
function disappearEffects(obj) {
	obj.fade({ afterFinish: removeItem });		
}

// Called when item completely disappeared
// Removes list item, updates to web service
function removeItem(effect) {
	effect.element.remove();
	postTodoList();
}

// Called when the list items are dragged
function listItemUpdate(list) {
	list.pulsate();		
	postTodoList();
}

// Get user todolist JSON data from webservice to display on page
function getTodoList() {
	new Ajax.Request("webservice.php", {
		method: "get",
		onSuccess: displayTodoList,
		onFailure: ajaxFailure,
		onException: ajaxFailure
	});
}

// Updates todolist to webservice if user makes any change to the list
function postTodoList() {

	var jsonText = createJSON($$("#todolist li"));
	new Ajax.Request("webservice.php", {
		method: "post",
		parameters: {todolist: jsonText},
		//onSuccess: function() {alert("success")},
		onFailure: ajaxFailure,
		onException: ajaxFailure
	});
	
	$("itemtext").focus();
}


// Called when the Ajax request returns with the JSON data.
// Processes this data and displays To-Do List on page
function displayTodoList(ajax) {
	var data = JSON.parse(ajax.responseText);
	$("listblock").hide();
	for (var i = 0; i < data.items.length; i++) { 
		var li = document.createElement("li");
		li.innerHTML = data.items[i];
		$("todolist").appendChild(li);
	}
	
	$("listblock").slideDown();
	
	Sortable.create($("todolist"), {
		onChange: listItemUpdate
	});
	
}

// Creates JSON text based on user todolist
function createJSON(list) {
	var items = [];
	for (var i = 0; i < list.length; i++) {
		items[i] = list[i].innerHTML;
	}
	var todolist = {};
	todolist.items = items;
	return JSON.stringify(todolist);
}

// Pops up a message on page (if any) when page is loaded
// Fade message box in 4 seconds
function getFlashMessage() {
	if ($("flash")===null) {
		showBlankSpace();
	} else {
		$("reserved4flash").hide();
		$("flash").fade( {duration: 4, afterFinish: showBlankSpace }); 
	}
}

// Show the blank space that is reserved for message box
// This helps the content not to move up
function showBlankSpace() {
	$("reserved4flash").show();
}

// Display errors on page if any ajax request failed
function ajaxFailure(ajax, exception) {
  $("errors").innerHTML = "Error making Ajax request:" + 
        "\n\nServer status:\n" + ajax.status + " " + ajax.statusText + 
        "\n\nServer response text:\n" + ajax.responseText;
  if (exception) {
    throw exception;
  }
}