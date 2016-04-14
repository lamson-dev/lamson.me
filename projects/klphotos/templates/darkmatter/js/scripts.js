/* Dark Matter Behavioral layer

Requires: YUI DOM, Event, Animation and
Dav Glass' YUI Tools and YUI Effects (stripped version used)

Check out Dav's awesome stuff: http://blog.davglass.com/files/yui/effects/

This file Copyright 2008 by Marco van Hylckama Vlieg
http://www.i-marco.nl/weblog/
marco@i-marco.nl

Word of advice: Don't mess with this unless you know what you're doing!

*/

YAHOO.photoblog.behaviours = function() {

		/* global variable definitions */

		var YUE = YAHOO.util.Event, YUA = YAHOO.util.Anim, YUD = YAHOO.util.Dom, YUC = YAHOO.util.Connect;	
		YAHOO.photoblog.bAnimating = false;

		/* animate tabs */

		return function() {

			var docStr = String(document.location);
			var nTagsHeight = null;
			var that = this;
			var oConfig = YAHOO.photoblog.oConfig;			

			var imagenode = YUD.get('dm_photo_id');
			var imageid = null;
				if(imagenode) {
					imageid = imagenode.value;
				}
			
			var cachedRequests = {};
			var currentRequest = '';
			
			if(docStr.match('longdescription')) {
				slideListener('info', nInfoHeight, function() {
					(YUD.get('info').offsetHeight > 0) ? YUD.setStyle(['photo-prev', 'photo-next'], 'top', '-1000px') : YUD.setStyle(['photo-prev', 'photo-next'], 'top', '115px');
				});
				YUD.setStyle('image-info-toggle', 'visibility', 'visible');
			}

			if(YUD.get('tag_cloud')) {
				nTagsHeight = YUD.get('tag_cloud').offsetHeight;
				YUD.setStyle('tag_cloud', 'height', 0);
				YUD.setStyle('tags-tab', 'visibility', 'visible');				
				createTab('tags-tab', 'tags-tab-toggle', oConfig.STR_TAGS, false);
				YUE.on('tags-tab-toggle', 'click', function(e){ 
					slideListener('tag_cloud', nTagsHeight);
					YUE.preventDefault(e);
				});
			}
		
			/* utility function to create a dynamic tab */

			function createTab(sParent, sTabId, sText, sBeforeElement) {

				var elTab = document.createElement('div');
				var elLink = document.createElement('a');

				if(!YUD.get(sParent)) {
					return;
				}
			
				elTab.id = sTabId;
				elLink.href = '#';
				elLink.id = sTabId + '-link';
				elLink.appendChild(document.createTextNode(sText));
				elTab.appendChild(elLink);	
				(sBeforeElement) ? YUD.get(sParent).insertBefore(elTab, YUD.get(sBeforeElement)) : YUD.get(sParent).appendChild(elTab);
			}

			/* slide tabs */
			function slideListener(sElem, nHeight, fnComplete, fnTween) {
				// part of ugly ie hack
				if((sElem == 'cform') && (nHeight > 0)) {
					YUD.setStyle(YUD.get('commentinfo'), 'height', '400px');
				}
				var slide = function (sElement, sHeight) {
					if(YAHOO.photoblog.bAnimating) {
						return false;
					}
					YAHOO.photoblog.bAnimating = true;
					var sMyHeight = parseInt(sHeight, 10);
					var animatorObj = new YUA(sElement, {height: {to: sMyHeight}}, 0.7, YAHOO.util.Easing.easeOutStrong); 
					if(fnComplete) {
						animatorObj.onComplete.subscribe(fnComplete);
					}
					animatorObj.onComplete.subscribe(function(){YAHOO.photoblog.bAnimating = false;});
						animatorObj.animate();
						return true;
					};
					(parseInt(YUD.getStyle(sElem, 'height'), 10) > 0) ? slide(sElem, 0) : slide(sElem, nHeight);					
				}

			/* utilities / hacks */

			function toggleIEStatic() {
				YUD.setStyle('view-window', 'position', 'static');
				YUD.setStyle('view-window', 'padding-left', '10px');
				YUD.setStyle('slider', 'position', 'static');
				var tranches = YUD.getElementsByClassName('tranche');
				if(tranches) {
					YUD.setStyle(tranches, 'position', 'static');
				}
			}
			
			function toggleThumbsCookie() {
				(YAHOO.util.Cookie.get('thumbslider') == 'expanded') ? YAHOO.util.Cookie.set('thumbslider', 'closed') : YAHOO.util.Cookie.set('thumbslider', 'expanded'); 		
			}

			function linksList(sHTML) {
				sListHTML = sHTML.replace(/<a/g, '<li><a');
				sListHTML = sListHTML.replace(/<\/a>/g, '</li></a>');
				return ('<ul>' + sListHTML + '</ul>');
			}

			/* right ajax carousel arrow */

			function replaceHTMLLeft(o) {
				
				if(!cachedRequests[currentRequest]) {
					cachedRequests[currentRequest] = o.responseText;
					currentRequest = null;
				}
				
				var dummies = null, nDummies = 0, nNegOffSet = 0, animatorObj = null, eSlider = null, aRawImages = null, nOffsetcount = 0, aImages = null, eNewDiv = null, imagesLength = 0;
				
				YAHOO.photoblog.bAnimating = true;
				if(o.responseText.replace(/^\s+|\s+$/g, '') !== '') {
					dummies = o.responseText.match(/dummy-element/g);
					if(dummies) {
						nDummies = dummies.length;
					}

					nNegOffSet = (600 - (nDummies * 120));
					YUD.get('slider').innerHTML = document.getElementById('slider').innerHTML + '<div class="tranche" id="ieleft">' + o.responseText + '</div>';						

					YUD.setStyle(YUD.getElementsByClassName('tranche')[1], 'right', 600 + 'px');
					animatorObj = new YUA('slider', {left: {to: nNegOffSet}}, 1, YAHOO.util.Easing.easeOutStrong);						
					animatorObj.onComplete.subscribe(function() {
						if(nDummies === 0) {
							YUD.get('slider').innerHTML = '<div class="tranche">' + linksList(o.responseText) + '</div>';
							YUD.setStyle('slider', 'position', 'relative');
							YUD.setStyle('slider', 'left', '0');
							YAHOO.photoblog.bAnimating = false;
							YUD.removeClass('nav_left', 'blinking');
							return false;
						}
					eSlider = YUD.get('slider'); 
					aRawImages = eSlider.getElementsByTagName('img');

					nDummies = (aDummies = YUD.getElementsByClassName('dummy-element')) ? aDummies.length : 0;

					nOffsetcount = 5 - nDummies;

					aImages = new Array();
					for(i=5;i<10;i++) {
						if((YUD.hasClass(aRawImages[i], 'thumbnails')) || (YUD.hasClass(aRawImages[i], 'current-thumbnail'))) {
							aImages.push(aRawImages[i].parentNode);
							}
						}
						for(i=0;i<(5 - nOffsetcount);i++) {
							if((YUD.hasClass(aRawImages[i], 'thumbnails')) || (YUD.hasClass(aRawImages[i], 'current-thumbnail'))) {
								aImages.push(aRawImages[i].parentNode);
							}
						}

						eNewDiv = document.createElement('div');
						eNewDiv.className = 'tranche';
						imagesLength = aImages.length;
						for(i=0;i<imagesLength;i++) {
							eNewDiv.appendChild(aImages[i]);
						}
						eSlider.innerHTML = '<div class="tranche">' + linksList(eNewDiv.innerHTML) + '</div>';
						YUD.setStyle('slider', 'position', 'relative');
						YUD.setStyle('slider', 'left', '0');
						YAHOO.photoblog.bAnimating = false;
						YUD.removeClass('nav_left','blinking');
						return true;
					});
				animatorObj.animate();	
				}
				else {
					YUD.removeClass('nav_left','blinking');
					YUD.addClass(YUD.get('nav_left'),'end');
					YAHOO.photoblog.bAnimating = false;
				}
			}				

			/* left AJAX carousel arrow */
		
			function replaceHTMLRight(o) {
				
				if(!cachedRequests[currentRequest]) {
					cachedRequests[currentRequest] = o.responseText;
					currentRequest = null;
				}
				
				var dummies = null, nDummies = 0, nNegOffSet = 0, animatorObj = null, eSlider = null, aRawImages = null, nOffsetcount = 0, aImages = null, eNewDiv = null, imagesLength = 0;
				
				YAHOO.photoblog.bAnimating = true;
				if(o.responseText.replace(/^\s+|\s+$/g, '') !== '') {
					
					nDummies = (dummies = o.responseText.match(/dummy-element/g)) ? dummies.length : 0;
					nNegOffSet = 0 - (600 - (nDummies * 120));
					YUD.get('slider').innerHTML = document.getElementById('slider').innerHTML + '<div class="tranche">' + o.responseText + '</div>';						
					YUD.setStyle(YUD.getElementsByClassName('tranche')[1], 'left', 600 + 'px');
					animatorObj = new YUA('slider', {left: {to: nNegOffSet}}, 1, YAHOO.util.Easing.easeOutStrong);						
					animatorObj.onComplete.subscribe(function() {
						if(nDummies === 0) {
							YUD.get('slider').innerHTML = '<div class="tranche">' + linksList(o.responseText) + '</div>';
							YUD.setStyle('slider', 'position', 'relative');
							YUD.setStyle('slider', 'left', '0');
							YUD.removeClass('nav_right', 'blinking');
							YAHOO.photoblog.bAnimating = false;
							return false;
						}
						eSlider = YUD.get('slider'); 
						aRawImages = eSlider.getElementsByTagName('img');
						nDummies = (aDummies = YUD.getElementsByClassName('dummy-element')) ? aDummies.length : 0;
		
						nOffsetcount = 5 - nDummies;
						aImages = new Array();
						for(i=0;i<aRawImages.length;i++) {
							if((YUD.hasClass(aRawImages[i], 'thumbnails')) || (YUD.hasClass(aRawImages[i], 'current-thumbnail'))) {
								if(nOffsetcount > 0) {
									nOffsetcount--;
								}
								else {
									aImages.push(aRawImages[i].parentNode);
								}
							}
						}

						eNewDiv = document.createElement('div');
						eNewDiv.className = 'tranche';
						imagesLength = aImages.length;
						for(i=0;i<imagesLength;i++) {
							eNewDiv.appendChild(aImages[i]);
						}							
		
						eSlider.innerHTML = '<div class="tranche">' + linksList(eNewDiv.innerHTML) + '</div>';
						YUD.setStyle('slider', 'position', 'relative');
						YUD.setStyle('slider', 'left', '0');
						YUD.removeClass('nav_right', 'blinking');
						YAHOO.photoblog.bAnimating = false;
						return true;
					});
				animatorObj.animate();	
				}
				else {
					YUD.removeClass('nav_right','blinking');
					YUD.addClass(YUD.get('nav_right'),'end');
					YAHOO.photoblog.bAnimating = false;
				}
			}				

			/* silent fail function for when the AJAX carousel fails for whatever reason */

			function handleFailure(o) {
				return false;
			}

			if(YUD.get('viewedphoto')) {	
				var nThumbsHeight = oConfig.thumbsheight;
				var nCommentFormHeight = 400;
				var nCurrentRequest = '';
				var nInfoHeight = YUD.get('info').offsetHeight;
				
				if(YUD.get('cform')) {				
					YUD.setStyle('cform', 'height', 0);
					// part of ugly IE hack
					YUD.setStyle('commentinfo', 'height', 0);
					YUD.setStyle('cform', 'display', 'block');
					createTab('commentform', 'commentform-toggle', oConfig.STR_COMMENT_FORM, 'cform');
					YUE.on(['commentform-toggle-link', 'name', 'email', 'url', 'comment', 'csubmit'], 'focus', function(e) {
						if(YUD.get('cform').offsetHeight === 0) {
							slideListener('cform', nCommentFormHeight, function() {
								window.scrollTo(0,10000);
							}, function() {window.scrollTo(0,10000);});
						}
					});
					YUD.setStyle('cform', 'visibility', 'visible');					
				}
				
				YUD.setStyle('info', 'height', 0);
				
				YUD.setStyle('image-info-toggle', 'visibility', 'hidden');
				var sCookie = YAHOO.util.Cookie.get('thumbslider');
			
				if(sCookie !== 'expanded') {
					YUD.setStyle('thumbs', 'height', 0);	
					toggleIEStatic();				
				}

				YUD.setStyle('thumbs', 'display', 'block');

				if(oConfig.fade == 'yes') {
					var sPhotoSRC = YUD.get('viewedphoto').src;
					YUD.get('viewedphoto').src = 'templates/darkmatter/img/blank.gif';
					var myImage = new Image();
			
					myImage.onload = function() {
						YUD.get('viewedphoto').src = sPhotoSRC;
						YUD.setStyle('photo', 'background-image', 'none');			
				
						var opts = {
							seconds:oConfig.fadespeed, 
							ease:YAHOO.util.Easing.easeOut, 
							delay: false
						};
					
						YUD.get('viewedphoto').onload = new YAHOO.widget.Effects.Appear('viewedphoto', opts);
					};
					myImage.src = sPhotoSRC;			
				}
				else {
					YUD.setStyle('viewedphoto', 'visibility', 'visible');
				}
				
				
				
				createTab('image-info', 'image-info-toggle', oConfig.STR_IMAGE_INFO, false);
				
				var eInfoLink = document.createElement('a');
				eInfoLink.id = 'i-link';
				eInfoLink.href = '#';
				eInfoLink.appendChild(document.createTextNode(oConfig.STR_IMAGE_INFO));
				YUD.get('titlebar').appendChild(eInfoLink);
				
				createTab('thumbnail-navigator', 'thumbnail-navigator-toggle', oConfig.STR_PHOTO_STREAM, false);

				
				if(YUD.get('thumbnail-navigator')) {
					YUD.setStyle(['image-info', 'thumbnail-navigator'], 'visibility', 'visible');
				}
				else {
					YUD.setStyle(['image-info'], 'visibility', 'visible');					
				}

				YUE.on(['photo', 'n-next', 'n-prev', 'photo-next', 'photo-prev'], 'mouseover', function() {
					YUD.setStyle('image-info-toggle', 'visibility', 'visible');
				}
			);

			YUE.on('photo-next', 'mouseover', function() {
				YUD.addClass(YUD.get('n-next'), 'hover');
				YUD.removeClass(YUD.get('n-next'), 'hidden');
			});
			YUE.on('photo-prev', 'mouseover', function() {
				YUD.addClass(YUD.get('n-prev'), 'hover');
				YUD.removeClass(YUD.get('n-prev'), 'hidden');
			});
			YUE.on('photo-next', 'mouseout', function() {
				YUD.removeClass(YUD.get('n-next'), 'hover');
				YUD.addClass(YUD.get('n-next'), 'hidden');				
			});
			YUE.on('photo-prev', 'mouseout', function() {
				YUD.removeClass(YUD.get('n-prev'), 'hover');
				YUD.addClass(YUD.get('n-prev'), 'hidden');
			});
			YUE.on('photo-next', 'click', function() {
				YUD.setStyle(YUD.get('photo-next'), 'display', 'none');		
		
			});
			YUE.on('photo-prev', 'click', function() {
				YUD.setStyle(YUD.get('photo-prev'), 'display', 'none');		
			});		
			
			YUE.on(['photo', 'n-prev', 'n-next', 'photo-next', 'photo-prev'], 'mouseout', function() {
				if(YUD.get('info').offsetHeight === 0) {
					YUD.setStyle('image-info-toggle', 'visibility', 'hidden');
				}
			});
			
			YUE.on('i-link', 'focus', function(e) {
				YUD.setStyle('image-info-toggle', 'visibility', 'visible');					
			});

			YUE.on('i-link', 'blur', function(e) {
				YUD.setStyle('image-info-toggle', 'visibility', 'hidden');					
			});			

			YUE.on(['i-link'], 'click', function(e) {
				YUE.preventDefault(e);				
				slideListener('info', nInfoHeight, function() {
					(YUD.get('info').offsetHeight > 0) ? YUD.setStyle(['photo-prev', 'photo-next'], 'top', '-1000px') : YUD.setStyle(['photo-prev', 'photo-next'], 'top', '115px');
					YUD.setStyle('image-info-toggle', 'visibility', 'visible');		
				});
			});

			YUE.on(['image-info-toggle', 'i-link'], 'mouseover', function() {
				YUD.setStyle('image-info-toggle', 'visibility', 'visible');
				YUD.setStyle('photo-next', 'display', 'none');
			});

			YUE.on(['image-info-toggle', 'i-link'], 'mouseout', function() {
				YUD.setStyle('image-info-toggle', 'visibility', 'visible');
				YUD.setStyle('photo-next', 'display', 'block');
			});
	
			if(YUD.get('thumbnail-navigator-toggle')) {
				YUE.on('thumbnail-navigator-toggle', 'click', function(e) {
					if(YUD.get('thumbs').offsetHeight > 0) {
						toggleIEStatic();
						slideListener('thumbs', nThumbsHeight);
					}
					else {
						slideListener('thumbs', nThumbsHeight, function() {
							YUD.setStyle(['view-window', 'slider'], 'position', 'relative');
							YUD.setStyle('view-window', 'padding-left', '0');
							YUD.setStyle(YUD.getElementsByClassName('tranche'), 'position', 'absolute');				
						});			
					}
					toggleThumbsCookie();
					YUE.preventDefault(e);
				});
			}
	
			YUE.on('nav_left', 'focus', function(e) {
				if(YUD.get('thumbs').offsetHeight === 0) {
					slideListener('thumbs', nThumbsHeight, function() {
						YUD.setStyle(['view-window', 'slider'], 'position', 'relative');
						YUD.setStyle('view-window', 'padding-left', '0');
						YUD.setStyle(YUD.getElementsByClassName('tranche'), 'position', 'absolute');				
					});			
				}
			});

			YUE.on('image-info-toggle', 'click', function(e) {
				slideListener('info', nInfoHeight, function() {
					(YUD.get('info').offsetHeight > 0) ? YUD.setStyle(['photo-prev', 'photo-next'], 'top', '-1000px') : YUD.setStyle(['photo-prev', 'photo-next'], 'top', '115px');
				});
				YUE.preventDefault(e);
			});

			YUE.on('commentform-toggle', 'click', function(e) {
				slideListener('cform', nCommentFormHeight, function() {
					window.scrollTo(0,10000);
					// ugly hack for ie7 ahead
					if(YUD.get('cform').offsetHeight === 0) {
						YUD.setStyle(YUD.get('commentinfo'), 'height', '0');
					}
					else {
						YUD.setStyle(YUD.get('commentinfo'), 'height', '400px');
					}
				});
				YUE.preventDefault(e);
			});

			YUE.on('nav_left', 'click', function(e)	{
				var request = '';		
				if(YAHOO.photoblog.bAnimating) {
					YUE.preventDefault(e);
					return false;
				}
				YUD.removeClass('nav_right', 'end');
				YUD.addClass(YUD.get('nav_left'),'blinking');
				var aLinks = YUD.getElementsByClassName('tranche')[0].getElementsByTagName('a');
				var sIDString = '';
				for(i=0;i<aLinks.length;i++) {
					var nID = aLinks[i].href.split("=")[1];
					if (i===0) {
						var nStartID = nID;
					}
					if(parseInt(nID, 10)) {
						sIDString = sIDString + nID;
						if(aLinks.length-1 > i) {
							sIDString = sIDString + ',';
						}
					}
				}
				request = 'index.php?x=caroussel&direction=backwards&not=' + sIDString + '&startid=' + nStartID + '&current=' + imageid;
				currentRequest = request;
				if(cachedRequests[currentRequest]) {					
					replaceHTMLLeft({responseText: cachedRequests[request]});
					YUE.preventDefault(e);
					return true;
				}

				var callback = {
					success: replaceHTMLLeft,
					failure: handleFailure
				};					
				
				objFetch = YUC.asyncRequest('GET', request, callback);
				YUE.preventDefault(e);
				return true;
			});

			YUE.on('nav_right', 'click', function(e)	{
				var request = '';
				if(YAHOO.photoblog.bAnimating) {
					YUE.preventDefault(e);
					return false;
				}
				YUD.removeClass('nav_left', 'end');
				YUD.addClass(YUD.get('nav_right'),'blinking');

				var aLinks = YUD.getElementsByClassName('tranche')[0].getElementsByTagName('a');
				var sIDString = '';
				for(i=0;i<aLinks.length;i++) {
					var nID = aLinks[i].href.split("=")[1];
					if (i==aLinks.length-1) {
						var nStartID = nID;
					}
					if(parseInt(nID, 10)) {
						sIDString = sIDString + nID;
						if(aLinks.length-1 > i) {
							sIDString = sIDString + ',';
						}
					}
				}
				
				request = 'index.php?x=caroussel&direction=forward&not=' + sIDString + '&startid=' + nStartID + '&current=' + imageid;
				
				currentRequest = request;
				if(cachedRequests[currentRequest]) {					
					replaceHTMLRight({responseText: cachedRequests[request]});
					YUE.preventDefault(e);
					return true;
				}
				
				var callback = {
					success: replaceHTMLRight,
					failure: handleFailure
				};					
				objFetch = YUC.asyncRequest('GET', request, callback);
				YUE.preventDefault(e);
				return true;
			});
			
			// precache arrow images
			
			var prevArrow = new Image();
			prevArrow.src = 'templates/darkmatter/img/arr_left.png';
			var nextArrow = new Image();
			nextArrow.src = 'templates/darkmatter/img/arr_right.png';
			// precache previous and next
			if(oConfig.previous_image !== "") {
				var prevImage = new Image();
				prevImage.src = 'images/' + oConfig.previous_image;
			}
			if(oConfig.next_image !== "") {
				var nextImage = new Image();
				nextImage.src = 'images/' + oConfig.next_image;
			}
			
			// precache thumbs
						
			var tlength = oConfig.thumbs.length;
			var myThumbs = [];
			for(i=0;i<tlength;i++) {
				myThumbs[i] = new Image();
				myThumbs[i].src = 'thumbnails/thumb_' + oConfig.thumbs[i];
			}		
		}
	};
}();

YAHOO.util.Event.onDOMReady(YAHOO.photoblog.behaviours);