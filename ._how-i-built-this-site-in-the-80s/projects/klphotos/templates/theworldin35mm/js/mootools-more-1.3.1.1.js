// MooTools: the javascript framework.
// Load this file's selection again by visiting: http://mootools.net/more/b81f04849e146d7cfb2000ce84e96c2a 
// Or build this file again with packager using: packager build More/Chain.Wait More/Element.Position More/Fx.Scroll More/Fx.Slide
/*
---
copyrights:
  - [MooTools](http://mootools.net)

licenses:
  - [MIT License](http://mootools.net/license.txt)
...
*/
MooTools.More={version:"1.3.1.1",build:"0292a3af1eea242b817fecf9daa127417d10d4ce"};(function(){var a={wait:function(b){return this.chain(function(){this.callChain.delay(b==null?500:b,this);
}.bind(this));}};Chain.implement(a);if(this.Fx){Fx.implement(a);}if(this.Element&&Element.implement&&this.Fx){Element.implement({chains:function(b){Array.from(b||["tween","morph","reveal"]).each(function(c){c=this.get(c);
if(!c){return;}c.setOptions({link:"chain"});},this);return this;},pauseFx:function(c,b){this.chains(b).get(b||"tween").wait(c);return this;}});}}).call(this);
(function(){var b=function(e,d){var f=[];Object.each(d,function(g){Object.each(g,function(h){e.each(function(i){f.push(i+"-"+h+(i=="border"?"-width":""));
});});});return f;};var c=function(f,e){var d=0;Object.each(e,function(h,g){if(g.test(f)){d=d+h.toInt();}});return d;};var a=function(d){return !!(!d||d.offsetHeight||d.offsetWidth);
};Element.implement({measure:function(h){if(a(this)){return h.call(this);}var g=this.getParent(),e=[];while(!a(g)&&g!=document.body){e.push(g.expose());
g=g.getParent();}var f=this.expose(),d=h.call(this);f();e.each(function(i){i();});return d;},expose:function(){if(this.getStyle("display")!="none"){return function(){};
}var d=this.style.cssText;this.setStyles({display:"block",position:"absolute",visibility:"hidden"});return function(){this.style.cssText=d;}.bind(this);
},getDimensions:function(d){d=Object.merge({computeSize:false},d);var i={x:0,y:0};var h=function(j,e){return(e.computeSize)?j.getComputedSize(e):j.getSize();
};var f=this.getParent("body");if(f&&this.getStyle("display")=="none"){i=this.measure(function(){return h(this,d);});}else{if(f){try{i=h(this,d);}catch(g){}}}return Object.append(i,(i.x||i.x===0)?{width:i.x,height:i.y}:{x:i.width,y:i.height});
},getComputedSize:function(d){d=Object.merge({styles:["padding","border"],planes:{height:["top","bottom"],width:["left","right"]},mode:"both"},d);var g={},e={width:0,height:0},f;
if(d.mode=="vertical"){delete e.width;delete d.planes.width;}else{if(d.mode=="horizontal"){delete e.height;delete d.planes.height;}}b(d.styles,d.planes).each(function(h){g[h]=this.getStyle(h).toInt();
},this);Object.each(d.planes,function(i,h){var k=h.capitalize(),j=this.getStyle(h);if(j=="auto"&&!f){f=this.getDimensions();}j=g[h]=(j=="auto")?f[h]:j.toInt();
e["total"+k]=j;i.each(function(m){var l=c(m,g);e["computed"+m.capitalize()]=l;e["total"+k]+=l;});},this);return Object.append(e,g);}});}).call(this);(function(){var a=Element.prototype.position;
Element.implement({position:function(g){if(g&&(g.x!=null||g.y!=null)){return a?a.apply(this,arguments):this;}Object.each(g||{},function(u,t){if(u==null){delete g[t];
}});g=Object.merge({relativeTo:document.body,position:{x:"center",y:"center"},offset:{x:0,y:0}},g);var r={x:0,y:0},e=false;var c=this.measure(function(){return document.id(this.getOffsetParent());
});if(c&&c!=this.getDocument().body){r=c.measure(function(){return this.getPosition();});e=c!=document.id(g.relativeTo);g.offset.x=g.offset.x-r.x;g.offset.y=g.offset.y-r.y;
}var s=function(t){if(typeOf(t)!="string"){return t;}t=t.toLowerCase();var u={};if(t.test("left")){u.x="left";}else{if(t.test("right")){u.x="right";}else{u.x="center";
}}if(t.test("upper")||t.test("top")){u.y="top";}else{if(t.test("bottom")){u.y="bottom";}else{u.y="center";}}return u;};g.edge=s(g.edge);g.position=s(g.position);
if(!g.edge){if(g.position.x=="center"&&g.position.y=="center"){g.edge={x:"center",y:"center"};}else{g.edge={x:"left",y:"top"};}}this.setStyle("position","absolute");
var f=document.id(g.relativeTo)||document.body,d=f==document.body?window.getScroll():f.getPosition(),l=d.y,h=d.x;var n=this.getDimensions({computeSize:true,styles:["padding","border","margin"]});
var j={},o=g.offset.y,q=g.offset.x,k=window.getSize();switch(g.position.x){case"left":j.x=h+q;break;case"right":j.x=h+q+f.offsetWidth;break;default:j.x=h+((f==document.body?k.x:f.offsetWidth)/2)+q;
break;}switch(g.position.y){case"top":j.y=l+o;break;case"bottom":j.y=l+o+f.offsetHeight;break;default:j.y=l+((f==document.body?k.y:f.offsetHeight)/2)+o;
break;}if(g.edge){var b={};switch(g.edge.x){case"left":b.x=0;break;case"right":b.x=-n.x-n.computedRight-n.computedLeft;break;default:b.x=-(n.totalWidth/2);
break;}switch(g.edge.y){case"top":b.y=0;break;case"bottom":b.y=-n.y-n.computedTop-n.computedBottom;break;default:b.y=-(n.totalHeight/2);break;}j.x+=b.x;
j.y+=b.y;}j={left:((j.x>=0||e||g.allowNegative)?j.x:0).toInt(),top:((j.y>=0||e||g.allowNegative)?j.y:0).toInt()};var i={left:"x",top:"y"};["minimum","maximum"].each(function(t){["left","top"].each(function(u){var v=g[t]?g[t][i[u]]:null;
if(v!=null&&((t=="minimum")?j[u]<v:j[u]>v)){j[u]=v;}});});if(f.getStyle("position")=="fixed"||g.relFixedPosition){var m=window.getScroll();j.top+=m.y;j.left+=m.x;
}if(g.ignoreScroll){var p=f.getScroll();j.top-=p.y;j.left-=p.x;}if(g.ignoreMargins){j.left+=(g.edge.x=="right"?n["margin-right"]:g.edge.x=="center"?-n["margin-left"]+((n["margin-right"]+n["margin-left"])/2):-n["margin-left"]);
j.top+=(g.edge.y=="bottom"?n["margin-bottom"]:g.edge.y=="center"?-n["margin-top"]+((n["margin-bottom"]+n["margin-top"])/2):-n["margin-top"]);}j.left=Math.ceil(j.left);
j.top=Math.ceil(j.top);if(g.returnPos){return j;}else{this.setStyles(j);}return this;}});}).call(this);(function(){Fx.Scroll=new Class({Extends:Fx,options:{offset:{x:0,y:0},wheelStops:true},initialize:function(c,b){this.element=this.subject=document.id(c);
this.parent(b);if(typeOf(this.element)!="element"){this.element=document.id(this.element.getDocument().body);}if(this.options.wheelStops){var d=this.element,e=this.cancel.pass(false,this);
this.addEvent("start",function(){d.addEvent("mousewheel",e);},true);this.addEvent("complete",function(){d.removeEvent("mousewheel",e);},true);}},set:function(){var b=Array.flatten(arguments);
if(Browser.firefox){b=[Math.round(b[0]),Math.round(b[1])];}this.element.scrollTo(b[0],b[1]);},compute:function(d,c,b){return[0,1].map(function(e){return Fx.compute(d[e],c[e],b);
});},start:function(c,d){if(!this.check(c,d)){return this;}var b=this.element.getScroll();return this.parent([b.x,b.y],[c,d]);},calculateScroll:function(g,f){var d=this.element,b=d.getScrollSize(),h=d.getScroll(),j=d.getSize(),c=this.options.offset,i={x:g,y:f};
for(var e in i){if(!i[e]&&i[e]!==0){i[e]=h[e];}if(typeOf(i[e])!="number"){i[e]=b[e]-j[e];}i[e]+=c[e];}return[i.x,i.y];},toTop:function(){return this.start.apply(this,this.calculateScroll(false,0));
},toLeft:function(){return this.start.apply(this,this.calculateScroll(0,false));},toRight:function(){return this.start.apply(this,this.calculateScroll("right",false));
},toBottom:function(){return this.start.apply(this,this.calculateScroll(false,"bottom"));},toElement:function(d,e){e=e?Array.from(e):["x","y"];var c=a(this.element)?{x:0,y:0}:this.element.getScroll();
var b=Object.map(document.id(d).getPosition(this.element),function(g,f){return e.contains(f)?g+c[f]:false;});return this.start.apply(this,this.calculateScroll(b.x,b.y));
},toElementEdge:function(d,g,e){g=g?Array.from(g):["x","y"];d=document.id(d);var i={},f=d.getPosition(this.element),j=d.getSize(),h=this.element.getScroll(),b=this.element.getSize(),c={x:f.x+j.x,y:f.y+j.y};
["x","y"].each(function(k){if(g.contains(k)){if(c[k]>h[k]+b[k]){i[k]=c[k]-b[k];}if(f[k]<h[k]){i[k]=f[k];}}if(i[k]==null){i[k]=h[k];}if(e&&e[k]){i[k]=i[k]+e[k];
}},this);if(i.x!=h.x||i.y!=h.y){this.start(i.x,i.y);}return this;},toElementCenter:function(e,f,h){f=f?Array.from(f):["x","y"];e=document.id(e);var i={},c=e.getPosition(this.element),d=e.getSize(),b=this.element.getScroll(),g=this.element.getSize();
["x","y"].each(function(j){if(f.contains(j)){i[j]=c[j]-(g[j]-d[j])/2;}if(i[j]==null){i[j]=b[j];}if(h&&h[j]){i[j]=i[j]+h[j];}},this);if(i.x!=b.x||i.y!=b.y){this.start(i.x,i.y);
}return this;}});function a(b){return(/^(?:body|html)$/i).test(b.tagName);}}).call(this);Fx.Slide=new Class({Extends:Fx,options:{mode:"vertical",wrapper:false,hideOverflow:true,resetHeight:false},initialize:function(b,a){b=this.element=this.subject=document.id(b);
this.parent(a);a=this.options;var d=b.retrieve("wrapper"),c=b.getStyles("margin","position","overflow");if(a.hideOverflow){c=Object.append(c,{overflow:"hidden"});
}if(a.wrapper){d=document.id(a.wrapper).setStyles(c);}if(!d){d=new Element("div",{styles:c}).wraps(b);}b.store("wrapper",d).setStyle("margin",0);if(b.getStyle("overflow")=="visible"){b.setStyle("overflow","hidden");
}this.now=[];this.open=true;this.wrapper=d;this.addEvent("complete",function(){this.open=(d["offset"+this.layout.capitalize()]!=0);if(this.open&&a.resetHeight){d.setStyle("height","");
}},true);},vertical:function(){this.margin="margin-top";this.layout="height";this.offset=this.element.offsetHeight;},horizontal:function(){this.margin="margin-left";
this.layout="width";this.offset=this.element.offsetWidth;},set:function(a){this.element.setStyle(this.margin,a[0]);this.wrapper.setStyle(this.layout,a[1]);
return this;},compute:function(c,b,a){return[0,1].map(function(d){return Fx.compute(c[d],b[d],a);});},start:function(b,e){if(!this.check(b,e)){return this;
}this[e||this.options.mode]();var d=this.element.getStyle(this.margin).toInt(),c=this.wrapper.getStyle(this.layout).toInt(),a=[[d,c],[0,this.offset]],g=[[d,c],[-this.offset,0]],f;
switch(b){case"in":f=a;break;case"out":f=g;break;case"toggle":f=(c==0)?a:g;}return this.parent(f[0],f[1]);},slideIn:function(a){return this.start("in",a);
},slideOut:function(a){return this.start("out",a);},hide:function(a){this[a||this.options.mode]();this.open=false;return this.set([-this.offset,0]);},show:function(a){this[a||this.options.mode]();
this.open=true;return this.set([0,this.offset]);},toggle:function(a){return this.start("toggle",a);}});Element.Properties.slide={set:function(a){this.get("slide").cancel().setOptions(a);
return this;},get:function(){var a=this.retrieve("slide");if(!a){a=new Fx.Slide(this,{link:"cancel"});this.store("slide",a);}return a;}};Element.implement({slide:function(d,e){d=d||"toggle";
var b=this.get("slide"),a;switch(d){case"hide":b.hide(e);break;case"show":b.show(e);break;case"toggle":var c=this.retrieve("slide:flag",b.open);b[c?"slideOut":"slideIn"](e);
this.store("slide:flag",!c);a=true;break;default:b.start(d,e);}if(!a){this.eliminate("slide:flag");}return this;}});