/*
Copyright (c) 2008, Yahoo! Inc. All rights reserved.
Code licensed under the BSD License:
http://developer.yahoo.net/yui/license.txt
version: 2.5.1
*/
YAHOO.namespace("util");YAHOO.util.Cookie={_createCookieString:function(B,D,C,A){var F=YAHOO.lang;var E=encodeURIComponent(B)+"="+(C?encodeURIComponent(D):D);if(F.isObject(A)){if(A.expires instanceof Date){E+="; expires="+A.expires.toGMTString();}if(F.isString(A.path)&&A.path!=""){E+="; path="+A.path;}if(F.isString(A.domain)&&A.domain!=""){E+="; domain="+A.domain;}if(A.secure===true){E+="; secure";}}return E;},_createCookieHashString:function(B){var D=YAHOO.lang;if(!D.isObject(B)){throw new TypeError("Cookie._createCookieHashString(): Argument must be an object.");}var C=new Array();for(var A in B){if(D.hasOwnProperty(B,A)&&!D.isFunction(B[A])&&!D.isUndefined(B[A])){C.push(encodeURIComponent(A)+"="+encodeURIComponent(String(B[A])));}}return C.join("&");},_parseCookieHash:function(E){var D=E.split("&");var F=null;var C=new Object();for(var B=0,A=D.length;B<A;B++){F=D[B].split("=");C[decodeURIComponent(F[0])]=decodeURIComponent(F[1]);}return C;},_parseCookieString:function(I,A){var J=new Object();if(YAHOO.lang.isString(I)&&I.length>0){var B=(A===false?function(K){return K;}:decodeURIComponent);if(/[^=]+=[^=;]?(?:; [^=]+=[^=]?)?/.test(I)){var G=I.split(/;\s/g);var H=null;var C=null;var E=null;for(var D=0,F=G.length;D<F;D++){E=G[D].match(/([^=]+)=/i);if(E instanceof Array){H=decodeURIComponent(E[1]);C=B(G[D].substring(H.length+1));}else{H=decodeURIComponent(G[D]);C=H;}J[H]=C;}}}return J;},get:function(A,B){var D=YAHOO.lang;var C=this._parseCookieString(document.cookie);if(!D.isString(A)||A===""){throw new TypeError("Cookie.get(): Cookie name must be a non-empty string.");}if(D.isUndefined(C[A])){return null;}if(!D.isFunction(B)){return C[A];}else{return B(C[A]);}},getSub:function(A,C,B){var E=YAHOO.lang;var D=this.getSubs(A);if(D!==null){if(!E.isString(C)||C===""){throw new TypeError("Cookie.getSub(): Subcookie name must be a non-empty string.");}if(E.isUndefined(D[C])){return null;}if(!E.isFunction(B)){return D[C];}else{return B(D[C]);}}else{return null;}},getSubs:function(A){if(!YAHOO.lang.isString(A)||A===""){throw new TypeError("Cookie.getSubs(): Cookie name must be a non-empty string.");}var B=this._parseCookieString(document.cookie,false);if(YAHOO.lang.isString(B[A])){return this._parseCookieHash(B[A]);}return null;},remove:function(B,A){if(!YAHOO.lang.isString(B)||B===""){throw new TypeError("Cookie.remove(): Cookie name must be a non-empty string.");}A=A||{};A.expires=new Date(0);return this.set(B,"",A);},set:function(B,C,A){var E=YAHOO.lang;if(!E.isString(B)){throw new TypeError("Cookie.set(): Cookie name must be a string.");}if(E.isUndefined(C)){throw new TypeError("Cookie.set(): Value cannot be undefined.");}var D=this._createCookieString(B,C,true,A);document.cookie=D;return D;},setSub:function(B,D,C,A){var F=YAHOO.lang;if(!F.isString(B)||B===""){throw new TypeError("Cookie.setSub(): Cookie name must be a non-empty string.");}if(!F.isString(D)||D===""){throw new TypeError("Cookie.setSub(): Subcookie name must be a non-empty string.");}if(F.isUndefined(C)){throw new TypeError("Cookie.setSub(): Subcookie value cannot be undefined.");}var E=this.getSubs(B);if(!F.isObject(E)){E=new Object();}E[D]=C;return this.setSubs(B,E,A);},setSubs:function(B,C,A){var E=YAHOO.lang;if(!E.isString(B)){throw new TypeError("Cookie.setSubs(): Cookie name must be a string.");}if(!E.isObject(C)){throw new TypeError("Cookie.setSubs(): Cookie value must be an object.");}var D=this._createCookieString(B,this._createCookieHashString(C),false,A);document.cookie=D;return D;}};YAHOO.register("cookie",YAHOO.util.Cookie,{version:"2.5.1",build:"984"});
YAHOO.Tools=function(){keyStr="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";regExs={quotes:/\x22/g,startspace:/^\s+/g,endspace:/\s+$/g,striptags:/<\/?[^>]+>/gi,hasbr:/<br/i,hasp:/<p>/i,rbr:/<br>/gi,rbr2:/<br\/>/gi,rendp:/<\/p>/gi,rp:/<p>/gi,base64:/[^A-Za-z0-9\+\/\=]/g,syntaxCheck:/^("(\\.|[^"\\\n\r])*?"|[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t])+?$/}
jsonCodes={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'}
return{version:'1.0'}}();YAHOO.Tools.getHeight=function(elm){var elm=$(elm);var h=$D.getStyle(elm,'height');if(h=='auto'){elm.style.zoom=1;h=elm.clientHeight+'px';}
return h;}
YAHOO.Tools.getCenter=function(elm){var elm=$(elm);var cX=Math.round(($D.getViewportWidth()-parseInt($D.getStyle(elm,'width')))/2);var cY=Math.round(($D.getViewportHeight()-parseInt(this.getHeight(elm)))/2);return[cX,cY];}
YAHOO.Tools.makeTextObject=function(txt){return document.createTextNode(txt);}
YAHOO.Tools.makeChildren=function(arr,elm){var elm=$(elm);for(var i in arr){_val=arr[i];if(typeof _val=='string'){_val=this.makeTxtObject(_val);}
elm.appendChild(_val);}}
YAHOO.Tools.styleToCamel=function(str){var _tmp=str.split('-');var _new_style=_tmp[0];for(var i=1;i<_tmp.length;i++){_new_style+=_tmp[i].substring(0,1).toUpperCase()+_tmp[i].substring(1,_tmp[i].length);}
return _new_style;}
YAHOO.Tools.removeQuotes=function(str){var checkText=new String(str);return String(checkText.replace(regExs.quotes,''));}
YAHOO.Tools.trim=function(str){return str.replace(regExs.startspace,'').replace(regExs.endspace,'');}
YAHOO.Tools.stripTags=function(str){return str.replace(regExs.striptags,'');}
YAHOO.Tools.hasBRs=function(str){return str.match(regExs.hasbr)||str.match(regExs.hasp);}
YAHOO.Tools.convertBRs2NLs=function(str){return str.replace(regExs.rbr,"\n").replace(regExs.rbr2,"\n").replace(regExs.rendp,"\n").replace(regExs.rp,"");}
YAHOO.Tools.stringRepeat=function(str,repeat){return new Array(repeat+1).join(str);}
YAHOO.Tools.stringReverse=function(str){var new_str='';for(i=0;i<str.length;i++){new_str=new_str+str.charAt((str.length-1)-i);}
return new_str;}
YAHOO.Tools.printf=function(){var num=arguments.length;var oStr=arguments[0];for(var i=1;i<num;i++){var pattern="\\{"+(i-1)+"\\}";var re=new RegExp(pattern,"g");oStr=oStr.replace(re,arguments[i]);}
return oStr;}
YAHOO.Tools.setStyleString=function(el,str){var _tmp=str.split(';');for(x in _tmp){if(x){__tmp=YAHOO.Tools.trim(_tmp[x]);__tmp=_tmp[x].split(':');if(__tmp[0]&&__tmp[1]){var _attr=YAHOO.Tools.trim(__tmp[0]);var _val=YAHOO.Tools.trim(__tmp[1]);if(_attr&&_val){if(_attr.indexOf('-')!=-1){_attr=YAHOO.Tools.styleToCamel(_attr);}
$D.setStyle(el,_attr,_val);}}}}}
YAHOO.Tools.getSelection=function(_document,_window){if(!_document){_document=document;}
if(!_window){_window=window;}
if(_document.selection){return _document.selection;}
return _window.getSelection();}
YAHOO.Tools.removeElement=function(el){if(!(el instanceof Array)){el=new Array($(el));}
for(var i=0;i<el.length;i++){if(el[i].parentNode){el[i].parentNode.removeChild(el);}}}
YAHOO.Tools.setCookie=function(name,value,expires,path,domain,secure){var argv=arguments;var argc=arguments.length;var expires=(argc>2)?argv[2]:null;var path=(argc>3)?argv[3]:'/';var domain=(argc>4)?argv[4]:null;var secure=(argc>5)?argv[5]:false;document.cookie=name+"="+escape(value)+
((expires==null)?"":("; expires="+expires.toGMTString()))+
((path==null)?"":("; path="+path))+
((domain==null)?"":("; domain="+domain))+
((secure==true)?"; secure":"");}
YAHOO.Tools.getCookie=function(name){var dc=document.cookie;var prefix=name+'=';var begin=dc.indexOf('; '+prefix);if(begin==-1){begin=dc.indexOf(prefix);if(begin!=0)return null;}else{begin+=2;}
var end=document.cookie.indexOf(';',begin);if(end==-1){end=dc.length;}
return unescape(dc.substring(begin+prefix.length,end));}
YAHOO.Tools.deleteCookie=function(name,path,domain){if(getCookie(name)){document.cookie=name+'='+((path)?'; path='+path:'')+((domain)?'; domain='+domain:'')+'; expires=Thu, 01-Jan-70 00:00:01 GMT';}}
YAHOO.Tools.getBrowserEngine=function(){var opera=((window.opera&&window.opera.version)?true:false);var safari=((navigator.vendor&&navigator.vendor.indexOf('Apple')!=-1)?true:false);var gecko=((document.getElementById&&!document.all&&!opera&&!safari)?true:false);var msie=((window.ActiveXObject)?true:false);var version=false;if(msie){if(typeof document.body.style.maxHeight!="undefined"){version='7';}else{version='6';}}
if(opera){var tmp_version=window.opera.version().split('.');version=tmp_version[0]+'.'+tmp_version[1];}
if(gecko){if(navigator.registerContentHandler){version='2';}else{version='1.5';}
if((navigator.vendorSub)&&!version){version=navigator.vendorSub;}}
if(safari){try{if(console){if((window.onmousewheel!=='undefined')&&(window.onmousewheel===null)){version='2';}else{version='1.3';}}}catch(e){version='1.2';}}
var browsers={ua:navigator.userAgent,opera:opera,safari:safari,gecko:gecko,msie:msie,version:version}
return browsers;}
YAHOO.Tools.getBrowserAgent=function(){var ua=navigator.userAgent.toLowerCase();var opera=((ua.indexOf('opera')!=-1)?true:false);var safari=((ua.indexOf('safari')!=-1)?true:false);var firefox=((ua.indexOf('firefox')!=-1)?true:false);var msie=((ua.indexOf('msie')!=-1)?true:false);var mac=((ua.indexOf('mac')!=-1)?true:false);var unix=((ua.indexOf('x11')!=-1)?true:false);var win=((mac||unix)?false:true);var version=false;var mozilla=false;if(!firefox&&!safari&&(ua.indexOf('gecko')!=-1)){mozilla=true;var _tmp=ua.split('/');version=_tmp[_tmp.length-1].split(' ')[0];}
if(firefox){var _tmp=ua.split('/');version=_tmp[_tmp.length-1].split(' ')[0];}
if(msie){version=ua.substring((ua.indexOf('msie ')+5)).split(';')[0];}
if(safari){version=this.getBrowserEngine().version;}
if(opera){version=ua.substring((ua.indexOf('opera/')+6)).split(' ')[0];}
var browsers={ua:navigator.userAgent,opera:opera,safari:safari,firefox:firefox,mozilla:mozilla,msie:msie,mac:mac,win:win,unix:unix,version:version}
return browsers;}
YAHOO.Tools.checkFlash=function(){var br=this.getBrowserEngine();if(br.msie){try{var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");var versionStr=axo.GetVariable("$version");var tempArray=versionStr.split(" ");var tempString=tempArray[1];var versionArray=tempString.split(",");var flash=versionArray[0];}catch(e){}}else{var flashObj=null;var tokens,len,curr_tok;if(navigator.mimeTypes&&navigator.mimeTypes['application/x-shockwave-flash']){flashObj=navigator.mimeTypes['application/x-shockwave-flash'].enabledPlugin;}
if(flashObj==null){flash=false;}else{tokens=navigator.plugins['Shockwave Flash'].description.split(' ');len=tokens.length;while(len--){curr_tok=tokens[len];if(!isNaN(parseInt(curr_tok))){hasVersion=curr_tok;flash=hasVersion;break;}}}}
return flash;}
YAHOO.Tools.setAttr=function(attrsObj,elm){if(typeof elm=='string'){elm=$(elm);}
for(var i in attrsObj){switch(i.toLowerCase()){case'listener':if(attrsObj[i]instanceof Array){var ev=attrsObj[i][0];var func=attrsObj[i][1];var base=attrsObj[i][2];var scope=attrsObj[i][3];$E.addListener(elm,ev,func,base,scope);}
break;case'classname':case'class':elm.className=attrsObj[i];break;case'style':YAHOO.Tools.setStyleString(elm,attrsObj[i]);break;default:elm.setAttribute(i,attrsObj[i]);break;}}}
YAHOO.Tools.create=function(tagName){tagName=tagName.toLowerCase();elm=document.createElement(tagName);var txt=false;var attrsObj=false;if(!elm){return false;}
for(var i=1;i<arguments.length;i++){txt=arguments[i];if(typeof txt=='string'){_txt=YAHOO.Tools.makeTextObject(txt);elm.appendChild(_txt);}else if(txt instanceof Array){YAHOO.Tools.makeChildren(txt,elm);}else if(typeof txt=='object'){YAHOO.Tools.setAttr(txt,elm);}}
return elm;}
YAHOO.Tools.insertAfter=function(elm,curNode){if(curNode.nextSibling){curNode.parentNode.insertBefore(elm,curNode.nextSibling);}else{curNode.parentNode.appendChild(elm);}}
YAHOO.Tools.inArray=function(arr,val){if(arr instanceof Array){for(var i=(arr.length-1);i>=0;i--){if(arr[i]===val){return true;}}}
return false;}
YAHOO.Tools.checkBoolean=function(str){return((typeof str=='boolean')?true:false);}
YAHOO.Tools.checkNumber=function(str){return((isNaN(str))?false:true);}
YAHOO.Tools.PixelToEm=function(size){var data={};var sSize=(size/13);data.other=(Math.round(sSize*100)/100);data.msie=(Math.round((sSize*0.9759)*100)/100);return data;}
YAHOO.Tools.PixelToEmStyle=function(size,prop){var data='';var prop=((prop)?prop.toLowerCase():'width');var sSize=(size/13);data+=prop+':'+(Math.round(sSize*100)/100)+'em;';data+='*'+prop+':'+(Math.round((sSize*0.9759)*100)/100)+'em;';if((prop=='width')||(prop=='height')){data+='min-'+prop+':'+size+'px;';}
return data;}
YAHOO.Tools.base64Encode=function(str){var data="";var chr1,chr2,chr3,enc1,enc2,enc3,enc4;var i=0;do{chr1=str.charCodeAt(i++);chr2=str.charCodeAt(i++);chr3=str.charCodeAt(i++);enc1=chr1>>2;enc2=((chr1&3)<<4)|(chr2>>4);enc3=((chr2&15)<<2)|(chr3>>6);enc4=chr3&63;if(isNaN(chr2)){enc3=enc4=64;}else if(isNaN(chr3)){enc4=64;}
data=data+keyStr.charAt(enc1)+keyStr.charAt(enc2)+keyStr.charAt(enc3)+keyStr.charAt(enc4);}while(i<str.length);return data;}
YAHOO.Tools.base64Decode=function(str){var data="";var chr1,chr2,chr3,enc1,enc2,enc3,enc4;var i=0;str=str.replace(regExs.base64,"");do{enc1=keyStr.indexOf(str.charAt(i++));enc2=keyStr.indexOf(str.charAt(i++));enc3=keyStr.indexOf(str.charAt(i++));enc4=keyStr.indexOf(str.charAt(i++));chr1=(enc1<<2)|(enc2>>4);chr2=((enc2&15)<<4)|(enc3>>2);chr3=((enc3&3)<<6)|enc4;data=data+String.fromCharCode(chr1);if(enc3!=64){data=data+String.fromCharCode(chr2);}
if(enc4!=64){data=data+String.fromCharCode(chr3);}}while(i<str.length);return data;}
YAHOO.Tools.getQueryString=function(str){var qstr={};if(!str){var str=location.href.split('?');if(str.length!=2){str=['',location.href];}}else{var str=['',str];}
if(str[1].match('#')){var _tmp=str[1].split('#');qstr.hash=_tmp[1];str[1]=_tmp[0];}
if(str[1]){str=str[1].split('&');if(str.length){for(var i=0;i<str.length;i++){var part=str[i].split('=');if(part[0].indexOf('[')!=-1){if(part[0].indexOf('[]')!=-1){var arr=part[0].substring(0,part[0].length-2);if(!qstr[arr]){qstr[arr]=[];}
qstr[arr][qstr[arr].length]=part[1];}else{var arr=part[0].substring(0,part[0].indexOf('['));var data=part[0].substring((part[0].indexOf('[')+1),part[0].indexOf(']'));if(!qstr[arr]){qstr[arr]={};}
qstr[arr][data]=part[1];}}else{qstr[part[0]]=part[1];}}}}
return qstr;}
YAHOO.Tools.getQueryStringVar=function(str){var qs=this.getQueryString();if(qs[str]){return qs[str];}else{return false;}}
YAHOO.Tools.padDate=function(n){return n<10?'0'+n:n;}
YAHOO.Tools.encodeStr=function(str){if(/["\\\x00-\x1f]/.test(str)){return'"'+str.replace(/([\x00-\x1f\\"])/g,function(a,b){var c=jsonCodes[b];if(c){return c;}
c=b.charCodeAt();return'\\u00'+
Math.floor(c/16).toString(16)+
(c%16).toString(16);})+'"';}
return'"'+str+'"';}
YAHOO.Tools.encodeArr=function(arr){var a=['['],b,i,l=arr.length,v;for(i=0;i<l;i+=1){v=arr[i];switch(typeof v){case'undefined':case'function':case'unknown':break;default:if(b){a.push(',');}
a.push(v===null?"null":YAHOO.Tools.JSONEncode(v));b=true;}}
a.push(']');return a.join('');}
YAHOO.Tools.encodeDate=function(d){return'"'+d.getFullYear()+'-'+YAHOO.Tools.padDate(d.getMonth()+1)+'-'+YAHOO.Tools.padDate(d.getDate())+'T'+YAHOO.Tools.padDate(d.getHours())+':'+YAHOO.Tools.padDate(d.getMinutes())+':'+YAHOO.Tools.padDate(d.getSeconds())+'"';}
YAHOO.Tools.fixJSONDate=function(dateStr){var tmp=dateStr.split('T');var fixedDate=dateStr;if(tmp.length==2){var tmpDate=tmp[0].split('-');if(tmpDate.length==3){fixedDate=new Date(tmpDate[0],(tmpDate[1]-1),tmpDate[2]);var tmpTime=tmp[1].split(':');if(tmpTime.length==3){fixedDate.setHours(tmpTime[0],tmpTime[1],tmpTime[2]);}}}
return fixedDate;}
YAHOO.Tools.JSONEncode=function(o){if((typeof o=='undefined')||(o===null)){return'null';}else if(o instanceof Array){return YAHOO.Tools.encodeArr(o);}else if(o instanceof Date){return YAHOO.Tools.encodeDate(o);}else if(typeof o=='string'){return YAHOO.Tools.encodeStr(o);}else if(typeof o=='number'){return isFinite(o)?String(o):"null";}else if(typeof o=='boolean'){return String(o);}else{var a=['{'],b,i,v;for(var i in o){v=o[i];switch(typeof v){case'undefined':case'function':case'unknown':break;default:if(b){a.push(',');}
a.push(YAHOO.Tools.JSONEncode(i),':',((v===null)?"null":YAHOO.Tools.JSONEncode(v)));b=true;}}
a.push('}');return a.join('');}}
YAHOO.Tools.JSONParse=function(json,autoDate){var autoDate=((autoDate)?true:false);try{if(regExs.syntaxCheck.test(json)){var j=eval('('+json+')');if(autoDate){function walk(k,v){if(v&&typeof v==='object'){for(var i in v){if(v.hasOwnProperty(i)){v[i]=walk(i,v[i]);}}}
if(k.toLowerCase().indexOf('date')>=0){return YAHOO.Tools.fixJSONDate(v);}else{return v;}}
return walk('',j);}else{return j;}}}catch(e){console.log(e);}
throw new SyntaxError("parseJSON");}
YAHOO.tools=YAHOO.Tools;YAHOO.TOOLS=YAHOO.Tools;YAHOO.util.Dom.create=YAHOO.Tools.create;$A=YAHOO.util.Anim;$E=YAHOO.util.Event;$D=YAHOO.util.Dom;$T=YAHOO.Tools;$=YAHOO.util.Dom.get;$$=YAHOO.util.Dom.getElementsByClassName;
YAHOO.widget.Effects=function(){return{version:'0.8'}}();YAHOO.widget.Effects.Hide=function(inElm){this.element=YAHOO.util.Dom.get(inElm);YAHOO.util.Dom.setStyle(this.element,'display','none');YAHOO.util.Dom.setStyle(this.element,'visibility','hidden');}
YAHOO.widget.Effects.Hide.prototype.toString=function(){return'Effect Hide ['+this.element.id+']';}
YAHOO.widget.Effects.Show=function(inElm){this.element=YAHOO.util.Dom.get(inElm);YAHOO.util.Dom.setStyle(this.element,'display','block');YAHOO.util.Dom.setStyle(this.element,'visibility','visible');}
YAHOO.widget.Effects.Show.prototype.toString=function(){return'Effect Show ['+this.element.id+']';}
YAHOO.widget.Effects.Fade=function(inElm,opts){this.element=YAHOO.util.Dom.get(inElm);var attributes={opacity:{from:1,to:0}};this.onEffectComplete=new YAHOO.util.CustomEvent('oneffectcomplete',this);var ease=((opts&&opts.ease)?opts.ease:YAHOO.util.Easing.easeOut);var secs=((opts&&opts.seconds)?opts.seconds:1);var delay=((opts&&opts.delay)?opts.delay:false);this.effect=new YAHOO.util.Anim(this.element,attributes,secs,ease);this.effect.onComplete.subscribe(function(){YAHOO.widget.Effects.Hide(this.element);this.onEffectComplete.fire();},this,true);if(!delay){this.effect.animate();}}
YAHOO.widget.Effects.Fade.prototype.animate=function(){this.effect.animate();}
YAHOO.widget.Effects.Fade.prototype.toString=function(){return'Effect Fade ['+this.element.id+']';}
YAHOO.widget.Effects.Appear=function(inElm,opts){this.element=YAHOO.util.Dom.get(inElm);YAHOO.util.Dom.setStyle(this.element,'opacity','0');YAHOO.widget.Effects.Show(this.element);var attributes={opacity:{from:0,to:1}};this.onEffectComplete=new YAHOO.util.CustomEvent('oneffectcomplete',this);var ease=((opts&&opts.ease)?opts.ease:YAHOO.util.Easing.easeOut);var secs=((opts&&opts.seconds)?opts.seconds:3);var delay=((opts&&opts.delay)?opts.delay:false);this.effect=new YAHOO.util.Anim(this.element,attributes,secs,ease);this.effect.onComplete.subscribe(function(){this.onEffectComplete.fire();},this,true);if(!delay){this.effect.animate();}}
YAHOO.widget.Effects.Appear.prototype.animate=function(){this.effect.animate();}
YAHOO.widget.Effects.Appear.prototype.toString=function(){return'Effect Appear ['+this.element.id+']';}
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