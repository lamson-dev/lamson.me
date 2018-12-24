function fadeClass(fadeObj, duration, steps, callback) {
	this.fadeObj = fadeObj;
	this.duration = duration;
	this.steps = steps;
	this.callback = callback;
	this.delta = 1.0 / steps;
	this.timeout = duration / steps;
	
	this.setOpacity = function(opacity) {
			if (this.fadeObj.style.opacity != null) {
				this.fadeObj.style.opacity = opacity;
			}
			else if (this.fadeObj.style.KHTMLOpacity != null) {
				if (opacity == 1.0) {
					opacity = 0.99;
				}
				this.fadeObj.style.KHTMLOpacity = opacity;
			}
			else if (this.fadeObj.style.MozOpacity != null) {
				if (opacity == 1.0) {
					opacity = 0.99;
				}
				this.fadeObj.style.MozOpacity = opacity;
			}
			else if (this.fadeObj.style.filter != null) {
				if (opacity == 1.0) {
					opacity = 0.99;
				}
				this.fadeObj.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(enabled=1,opacity=' + (opacity * 100) + ')';
			}
	}
	
	this.setVisibility = function(visibility) {
		this.fadeObj.style.visibility = visibility;
	}
	
	this.fadeIn = function(step, opacity) {
		if (step < this.steps) {
			this.setOpacity(opacity);
			var _this = this;
			var _args = [step + 1, opacity + this.delta];
			// crazy setTimeout to work in IE7/8, since IE does not support setTimeout extension
			// https://developer.mozilla.org/En/DOM/Window.setTimeout
			window.setTimeout(function(){_this['fadeIn'].apply(_this, _args)}, this.timeout);
		}
		else {
			this.setOpacity(1.0);
			this.callback();
		}
	}
	
	this.fade = function() {
		this.setVisibility('visible');
		this.fadeIn(0, 0.0);
	}
	
	this.setOpacity(0.0);
	this.setVisibility('hidden');
}

function fade(fadeObj, duration, steps, callback) {
	var fader = new fadeClass(fadeObj, duration * 1000, steps, callback);
	fader.fade();
}
