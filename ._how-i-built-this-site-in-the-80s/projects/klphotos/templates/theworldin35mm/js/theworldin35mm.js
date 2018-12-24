function clearBox(box) {
	if (box.value == box.defaultValue) {
		box.value = "";
	}
	else {
		box.select();
	}
}

function trim(str) {
	return String(str).replace(/^\s+|\s+$/g, "");
}

function empty(str) {
	if (str === undefined) {
		return true;
	}
	else if (str == null) {
		return true;
	}
	else {
		return String(str).search(/^\s*$/) != -1;
	}
}

function isEmailAddress(s) {
	if (empty(s)) {
		return false;
	}
	else {
		return String(s).search(/^\s*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+(\.[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+)*\@[\w\-]+\.[\w\-]+(\.[\w\-]+)*\s*$/i) != -1;
	}
}

function isURL(s) {
	if (empty(s)) {
		return false;
	}
	else {
		return String(s).search(/^\s*(https?|ftp):\/\/([\-\w\.]+)+(:\d+)?(\/([\S\-\/_\.]*(\?\S+)?)?)?\s*$/i) != -1;
	}
}

function isFormTag(tagName) {
	tagName = tagName.toUpperCase();
	
	if (tagName == "INPUT" ||
		tagName == "TEXTAREA" ||
		tagName == "SELECT" ||
		tagName == "OPTION" ||
		tagName == "BUTTON") {
		return true;
	}
	
	return false;
}

function checkCommentForm(form) {
	var ret = true;
	var ne = $('name-error');
	var ee = $('email-error');
	var ue = $('url-error');
	var me = $('message-error');
	
	ne.setStyle('display', 'none');
	ee.setStyle('display', 'none');
	ue.setStyle('display', 'none');
	me.setStyle('display', 'none');
	
	if (form.message.value == form.message.defaultValue || empty(form.message.value)) {
		me.setStyle('display', 'inline');
		form.message.focus();
		ret = false;
	}
	
	if (!empty(form.url.value)) {
		// search email text for regular exp matches
		if (!isURL(form.url.value)) {
			ue.setStyle('display', 'inline');
			form.url.focus();
			form.url.select();
			ret = false;
		}
	}
	
	if (!empty(form.email.value)) {
		// search email text for regular exp matches
		if (!isEmailAddress(form.email.value)) {
			ee.setStyle('display', 'inline');
			form.email.focus();
			form.email.select();
			ret = false;
		}
	}
	
	if (empty(form.name.value)) {
		ne.setStyle('display', 'inline');
		form.name.focus();
		form.name.select();
		ret = false;
	}
	
	// set cookie of info
	if (ret == true && form.vcookie2.checked) {
		Cookie.write(
			'visitorinfo',
			form.name.value + '%' + form.url.value + '%' + form.email.value,
			{path:cookie_path, duration:9999});
	}
	
	return ret;
}
