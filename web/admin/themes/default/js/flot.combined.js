/* Javascript plotting library for jQuery, version 0.8.1.

Copyright (c) 2007-2013 IOLA and Ole Laursen.
Licensed under the MIT license.

*/ // first an inline dependency, jquery.colorhelpers.js, we inline it here
// for convenience
/* Plugin for jQuery for working with colors.
 *
 * Version 1.1.
 *
 * Inspiration from jQuery color animation plugin by John Resig.
 *
 * Released under the MIT license by Ole Laursen, October 2009.
 *
 * Examples:
 *
 *   $.color.parse("#fff").scale('rgb', 0.25).add('a', -0.5).toString()
 *   var c = $.color.extract($("#mydiv"), 'background-color');
 *   console.log(c.r, c.g, c.b, c.a);
 *   $.color.make(100, 50, 25, 0.4).toString() // returns "rgba(100,50,25,0.4)"
 *
 * Note that .scale() and .add() return the same modified object
 * instead of making a new one.
 *
 * V. 1.1: Fix error handling so e.g. parsing an empty string does
 * produce a color rather than just crashing.
 */
(function(t){t.color={},t.color.make=function(i,e,o,n){var r={};return r.r=i||0,r.g=e||0,r.b=o||0,r.a=null!=n?n:1,r.add=function(t,i){for(var e=0;e<t.length;++e)r[t.charAt(e)]+=i;return r.normalize()},r.scale=function(t,i){for(var e=0;e<t.length;++e)r[t.charAt(e)]*=i;return r.normalize()},r.toString=function(){return r.a>=1?"rgb("+[r.r,r.g,r.b].join(",")+")":"rgba("+[r.r,r.g,r.b,r.a].join(",")+")"},r.normalize=function(){function t(t,i,e){return i<t?t:i>e?e:i}return r.r=t(0,parseInt(r.r),255),r.g=t(0,parseInt(r.g),255),r.b=t(0,parseInt(r.b),255),r.a=t(0,r.a,1),r},r.clone=function(){return t.color.make(r.r,r.b,r.g,r.a)},r.normalize()},t.color.extract=function(i,e){var o;do{if(""!=(o=i.css(e).toLowerCase())&&"transparent"!=o)break;i=i.parent()}while(!t.nodeName(i.get(0),"body"));return"rgba(0, 0, 0, 0)"==o&&(o="transparent"),t.color.parse(o)},t.color.parse=function(e){var o,n=t.color.make;if(o=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(e))return n(parseInt(o[1],10),parseInt(o[2],10),parseInt(o[3],10));if(o=/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]+(?:\.[0-9]+)?)\s*\)/.exec(e))return n(parseInt(o[1],10),parseInt(o[2],10),parseInt(o[3],10),parseFloat(o[4]));if(o=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(e))return n(2.55*parseFloat(o[1]),2.55*parseFloat(o[2]),2.55*parseFloat(o[3]));if(o=/rgba\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\s*\)/.exec(e))return n(2.55*parseFloat(o[1]),2.55*parseFloat(o[2]),2.55*parseFloat(o[3]),parseFloat(o[4]));if(o=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(e))return n(parseInt(o[1],16),parseInt(o[2],16),parseInt(o[3],16));if(o=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(e))return n(parseInt(o[1]+o[1],16),parseInt(o[2]+o[2],16),parseInt(o[3]+o[3],16));var r=t.trim(e).toLowerCase();return"transparent"==r?n(255,255,255,0):n((o=i[r]||[0,0,0])[0],o[1],o[2])};var i={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0]}})(jQuery),function(t){function i(i,e){var o=e.children("."+i)[0];if(null==o&&((o=document.createElement("canvas")).className=i,t(o).css({direction:"ltr",position:"absolute",left:0,top:0}).appendTo(e),!o.getContext)){if(!window.G_vmlCanvasManager)throw new Error("Canvas is not available. If you're using IE with a fall-back such as Excanvas, then there's either a mistake in your conditional include, or the page has no DOCTYPE and is rendering in Quirks Mode.");o=window.G_vmlCanvasManager.initElement(o)}this.element=o;var n=this.context=o.getContext("2d"),r=window.devicePixelRatio||1,a=n.webkitBackingStorePixelRatio||n.mozBackingStorePixelRatio||n.msBackingStorePixelRatio||n.oBackingStorePixelRatio||n.backingStorePixelRatio||1;this.pixelRatio=r/a,this.resize(e.width(),e.height()),this.textContainer=null,this.text={},this._textCache={}}function e(e,o,n,r){function a(t,i){i=[K].concat(i);for(var e=0;e<t.length;++e)t[e].apply(this,i)}function l(t){j=s(t),d(),p()}function s(i){for(var e=[],o=0;o<i.length;++o){var n=t.extend(!0,{},B.series);null!=i[o].data?(n.data=i[o].data,delete i[o].data,t.extend(!0,n,i[o]),i[o].data=n.data):n.data=i[o],e.push(n)}return e}function c(t,i){var e=t[i+"axis"];return"object"==typeof e&&(e=e.n),"number"!=typeof e&&(e=1),e}function h(){return t.grep(Y.concat(q),function(t){return t})}function f(t){var i,e,o={};for(i=0;i<Y.length;++i)(e=Y[i])&&e.used&&(o["x"+e.n]=e.c2p(t.left));for(i=0;i<q.length;++i)(e=q[i])&&e.used&&(o["y"+e.n]=e.c2p(t.top));return void 0!==o.x1&&(o.x=o.x1),void 0!==o.y1&&(o.y=o.y1),o}function u(i,e){return i[e-1]||(i[e-1]={n:e,direction:i==Y?"x":"y",options:t.extend(!0,{},i==Y?B.xaxis:B.yaxis)}),i[e-1]}function d(){var i,e=j.length,o=-1;for(i=0;i<j.length;++i){var n=j[i].color;null!=n&&(e--,"number"==typeof n&&n>o&&(o=n))}e<=o&&(e=o+1);var r,a=[],l=B.colors,s=l.length,h=0;for(i=0;i<e;i++)r=t.color.parse(l[i%s]||"#666"),i%s==0&&i&&(h=h>=0?h<.5?-h-.2:0:-h),a[i]=r.scale("rgb",1+h);var f,d=0;for(i=0;i<j.length;++i){if(null==(f=j[i]).color?(f.color=a[d].toString(),++d):"number"==typeof f.color&&(f.color=a[f.color].toString()),null==f.lines.show){var p,m=!0;for(p in f)if(f[p]&&f[p].show){m=!1;break}m&&(f.lines.show=!0)}null==f.lines.zero&&(f.lines.zero=!!f.lines.fill),f.xaxis=u(Y,c(f,"x")),f.yaxis=u(q,c(f,"y"))}}function p(){function i(t,i,e){i<t.datamin&&i!=-b&&(t.datamin=i),e>t.datamax&&e!=b&&(t.datamax=e)}var e,o,n,r,l,s,c,f,u,d,p,m,x=Number.POSITIVE_INFINITY,g=Number.NEGATIVE_INFINITY,b=Number.MAX_VALUE;for(t.each(h(),function(t,i){i.datamin=x,i.datamax=g,i.used=!1}),e=0;e<j.length;++e)(l=j[e]).datapoints={points:[]},a($.processRawData,[l,l.data,l.datapoints]);for(e=0;e<j.length;++e){if(p=(l=j[e]).data,!(m=l.datapoints.format)){if((m=[]).push({x:!0,number:!0,required:!0}),m.push({y:!0,number:!0,required:!0}),l.bars.show||l.lines.show&&l.lines.fill){var v=!!(l.bars.show&&l.bars.zero||l.lines.show&&l.lines.zero);m.push({y:!0,number:!0,required:!1,defaultValue:0,autoscale:v}),l.bars.horizontal&&(delete m[m.length-1].y,m[m.length-1].x=!0)}l.datapoints.format=m}if(null==l.datapoints.pointsize){l.datapoints.pointsize=m.length,c=l.datapoints.pointsize,s=l.datapoints.points;var k=l.lines.show&&l.lines.steps;for(l.xaxis.used=l.yaxis.used=!0,o=n=0;o<p.length;++o,n+=c){var y=null==(d=p[o]);if(!y)for(r=0;r<c;++r)f=d[r],(u=m[r])&&(u.number&&null!=f&&(f=+f,isNaN(f)?f=null:f==1/0?f=b:f==-1/0&&(f=-b)),null==f&&(u.required&&(y=!0),null!=u.defaultValue&&(f=u.defaultValue))),s[n+r]=f;if(y)for(r=0;r<c;++r)null!=(f=s[n+r])&&((u=m[r]).autoscale&&(u.x&&i(l.xaxis,f,f),u.y&&i(l.yaxis,f,f))),s[n+r]=null;else if(k&&n>0&&null!=s[n-c]&&s[n-c]!=s[n]&&s[n-c+1]!=s[n+1]){for(r=0;r<c;++r)s[n+c+r]=s[n+r];s[n+1]=s[n-c+1],n+=c}}}}for(e=0;e<j.length;++e)l=j[e],a($.processDatapoints,[l,l.datapoints]);for(e=0;e<j.length;++e){s=(l=j[e]).datapoints.points,c=l.datapoints.pointsize,m=l.datapoints.format;var w=x,T=x,M=g,C=g;for(o=0;o<s.length;o+=c)if(null!=s[o])for(r=0;r<c;++r)f=s[o+r],(u=m[r])&&!1!==u.autoscale&&f!=b&&f!=-b&&(u.x&&(f<w&&(w=f),f>M&&(M=f)),u.y&&(f<T&&(T=f),f>C&&(C=f)));if(l.bars.show){var S;switch(l.bars.align){case"left":S=0;break;case"right":S=-l.bars.barWidth;break;case"center":S=-l.bars.barWidth/2;break;default:throw new Error("Invalid bar alignment: "+l.bars.align)}l.bars.horizontal?(T+=S,C+=S+l.bars.barWidth):(w+=S,M+=S+l.bars.barWidth)}i(l.xaxis,w,M),i(l.yaxis,T,C)}t.each(h(),function(t,i){i.datamin==x&&(i.datamin=null),i.datamax==g&&(i.datamax=null)})}function m(i){var e,o=i.labelWidth,n=i.labelHeight,r=i.options.position,a=i.options.tickLength,l=B.grid.axisMargin,s=B.grid.labelMargin,c="x"==i.direction?Y:q,h=t.grep(c,function(t){return t&&t.options.position==r&&t.reserveSpace});if(t.inArray(i,h)==h.length-1&&(l=0),null==a){var f=t.grep(c,function(t){return t&&t.reserveSpace});a=(e=0==t.inArray(i,f))?"full":5}isNaN(+a)||(s+=+a),"x"==i.direction?(n+=s,"bottom"==r?(Q.bottom+=n+l,i.box={top:H.height-Q.bottom,height:n}):(i.box={top:Q.top+l,height:n},Q.top+=n+l)):(o+=s,"left"==r?(i.box={left:Q.left+l,width:o},Q.left+=o+l):(Q.right+=o+l,i.box={left:H.width-Q.right,width:o})),i.position=r,i.tickLength=a,i.box.padding=s,i.innermost=e}function x(){var i,e=h(),o=B.grid.show;for(var n in Q){var r=B.grid.margin||0;Q[n]="number"==typeof r?r:r[n]||0}for(var n in a($.processOffset,[Q]),Q)"object"==typeof B.grid.borderWidth?Q[n]+=o?B.grid.borderWidth[n]:0:Q[n]+=o?B.grid.borderWidth:0;if(t.each(e,function(t,i){i.show=i.options.show,null==i.show&&(i.show=i.used),i.reserveSpace=i.show||i.options.reserveSpace,function(t){var i=t.options,e=+(null!=i.min?i.min:t.datamin),o=+(null!=i.max?i.max:t.datamax),n=o-e;if(0==n){var r=0==o?1:.01;null==i.min&&(e-=r),null!=i.max&&null==i.min||(o+=r)}else{var a=i.autoscaleMargin;null!=a&&(null==i.min&&((e-=n*a)<0&&null!=t.datamin&&t.datamin>=0&&(e=0)),null==i.max&&((o+=n*a)>0&&null!=t.datamax&&t.datamax<=0&&(o=0)))}t.min=e,t.max=o}(i)}),o){var l=t.grep(e,function(t){return t.reserveSpace});for(t.each(l,function(t,i){g(i),b(i),function(t,i){t.options.autoscaleMargin&&i.length>0&&(null==t.options.min&&(t.min=Math.min(t.min,i[0].v)),null==t.options.max&&i.length>1&&(t.max=Math.max(t.max,i[i.length-1].v)))}(i,i.ticks),function(t){var i=t.options,e=t.ticks||[],o=i.labelWidth||0,n=i.labelHeight||0,r=o||"x"==t.direction?Math.floor(H.width/(e.length||1)):null;legacyStyles=t.direction+"Axis "+t.direction+t.n+"Axis",layer="flot-"+t.direction+"-axis flot-"+t.direction+t.n+"-axis "+legacyStyles,font=i.font||"flot-tick-label tickLabel";for(var a=0;a<e.length;++a){var l=e[a];if(l.label){var s=H.getTextInfo(layer,l.label,font,null,r);o=Math.max(o,s.width),n=Math.max(n,s.height)}}t.labelWidth=i.labelWidth||o,t.labelHeight=i.labelHeight||n}(i)}),i=l.length-1;i>=0;--i)m(l[i]);(function(){var i,e=B.grid.minBorderMargin,o={x:0,y:0};if(null==e)for(e=0,i=0;i<j.length;++i)e=Math.max(e,2*(j[i].points.radius+j[i].points.lineWidth/2));o.x=o.y=Math.ceil(e),t.each(h(),function(t,i){var e=i.direction;i.reserveSpace&&(o[e]=Math.ceil(Math.max(o[e],("x"==e?i.labelWidth:i.labelHeight)/2)))}),Q.left=Math.max(o.x,Q.left),Q.right=Math.max(o.x,Q.right),Q.top=Math.max(o.y,Q.top),Q.bottom=Math.max(o.y,Q.bottom)})(),t.each(l,function(t,i){!function(t){"x"==t.direction?(t.box.left=Q.left-t.labelWidth/2,t.box.width=H.width-Q.left-Q.right+t.labelWidth):(t.box.top=Q.top-t.labelHeight/2,t.box.height=H.height-Q.bottom-Q.top+t.labelHeight)}(i)})}U=H.width-Q.left-Q.right,J=H.height-Q.bottom-Q.top,t.each(e,function(t,i){!function(t){function i(t){return t}var e,o,n=t.options.transform||i,r=t.options.inverseTransform;"x"==t.direction?(e=t.scale=U/Math.abs(n(t.max)-n(t.min)),o=Math.min(n(t.max),n(t.min))):(e=-(e=t.scale=J/Math.abs(n(t.max)-n(t.min))),o=Math.max(n(t.max),n(t.min))),t.p2c=n==i?function(t){return(t-o)*e}:function(t){return(n(t)-o)*e},t.c2p=r?function(t){return r(o+t/e)}:function(t){return o+t/e}}(i)}),o&&t.each(h(),function(t,i){if(i.show&&0!=i.ticks.length){var e,o,n,r,a,l=i.box,s=i.direction+"Axis "+i.direction+i.n+"Axis",c="flot-"+i.direction+"-axis flot-"+i.direction+i.n+"-axis "+s,h=i.options.font||"flot-tick-label tickLabel";H.removeText(c);for(var f=0;f<i.ticks.length;++f)!(e=i.ticks[f]).label||e.v<i.min||e.v>i.max||("x"==i.direction?(r="center",o=Q.left+i.p2c(e.v),"bottom"==i.position?n=l.top+l.padding:(n=l.top+l.height-l.padding,a="bottom")):(a="middle",n=Q.top+i.p2c(e.v),"left"==i.position?(o=l.left+l.width-l.padding,r="right"):o=l.left+l.padding),H.addText(c,o,n,e.label,h,null,null,r,a))}}),C()}function g(i){var e,o=i.options;e="number"==typeof o.ticks&&o.ticks>0?o.ticks:.3*Math.sqrt("x"==i.direction?H.width:H.height);var n=(i.max-i.min)/e,r=-Math.floor(Math.log(n)/Math.LN10),a=o.tickDecimals;null!=a&&r>a&&(r=a);var l,s=Math.pow(10,-r),c=n/s;if(c<1.5?l=1:c<3?(l=2,c>2.25&&(null==a||r+1<=a)&&(l=2.5,++r)):l=c<7.5?5:10,l*=s,null!=o.minTickSize&&l<o.minTickSize&&(l=o.minTickSize),i.delta=n,i.tickDecimals=Math.max(0,null!=a?a:r),i.tickSize=o.tickSize||l,"time"==o.mode&&!i.tickGenerator)throw new Error("Time mode requires the flot.time plugin.");if(i.tickGenerator||(i.tickGenerator=function(t){var i,e=[],o=function(t,i){return i*Math.floor(t/i)}(t.min,t.tickSize),n=0,r=Number.NaN;do{i=r,r=o+n*t.tickSize,e.push(r),++n}while(r<t.max&&r!=i);return e},i.tickFormatter=function(t,i){var e=i.tickDecimals?Math.pow(10,i.tickDecimals):1,o=""+Math.round(t*e)/e;if(null!=i.tickDecimals){var n=o.indexOf("."),r=-1==n?0:o.length-n-1;if(r<i.tickDecimals)return(r?o:o+".")+(""+e).substr(1,i.tickDecimals-r)}return o}),t.isFunction(o.tickFormatter)&&(i.tickFormatter=function(t,i){return""+o.tickFormatter(t,i)}),null!=o.alignTicksWithAxis){var h=("x"==i.direction?Y:q)[o.alignTicksWithAxis-1];if(h&&h.used&&h!=i){var f=i.tickGenerator(i);if(f.length>0&&(null==o.min&&(i.min=Math.min(i.min,f[0])),null==o.max&&f.length>1&&(i.max=Math.max(i.max,f[f.length-1]))),i.tickGenerator=function(t){var i,e,o=[];for(e=0;e<h.ticks.length;++e)i=(h.ticks[e].v-h.min)/(h.max-h.min),i=t.min+i*(t.max-t.min),o.push(i);return o},!i.mode&&null==o.tickDecimals){var u=Math.max(0,1-Math.floor(Math.log(i.delta)/Math.LN10)),d=i.tickGenerator(i);d.length>1&&/\..*0$/.test((d[1]-d[0]).toFixed(u))||(i.tickDecimals=u)}}}}function b(i){var e,o,n=i.options.ticks,r=[];for(null==n||"number"==typeof n&&n>0?r=i.tickGenerator(i):n&&(r=t.isFunction(n)?n(i):n),i.ticks=[],e=0;e<r.length;++e){var a=null,l=r[e];"object"==typeof l?(o=+l[0],l.length>1&&(a=l[1])):o=+l,null==a&&(a=i.tickFormatter(o,i)),isNaN(o)||i.ticks.push({v:o,label:a})}}function v(){H.clear(),a($.drawBackground,[V]);var t=B.grid;t.show&&t.backgroundColor&&(V.save(),V.translate(Q.left,Q.top),V.fillStyle=R(B.grid.backgroundColor,J,0,"rgba(255, 255, 255, 0)"),V.fillRect(0,0,U,J),V.restore()),t.show&&!t.aboveData&&y();for(var i=0;i<j.length;++i)a($.drawSeries,[V,j[i]]),w(j[i]);a($.draw,[V]),t.show&&t.aboveData&&y(),H.render(),P()}function k(t,i){for(var e,o,n,r,a=h(),l=0;l<a.length;++l)if((e=a[l]).direction==i&&(!t[r=i+e.n+"axis"]&&1==e.n&&(r=i+"axis"),t[r])){o=t[r].from,n=t[r].to;break}if(t[r]||(e="x"==i?Y[0]:q[0],o=t[i+"1"],n=t[i+"2"]),null!=o&&null!=n&&o>n){var s=o;o=n,n=s}return{from:o,to:n,axis:e}}function y(){var i,e,o,n;V.save(),V.translate(Q.left,Q.top);var r=B.grid.markings;if(r)for(t.isFunction(r)&&((e=K.getAxes()).xmin=e.xaxis.min,e.xmax=e.xaxis.max,e.ymin=e.yaxis.min,e.ymax=e.yaxis.max,r=r(e)),i=0;i<r.length;++i){var a=r[i],l=k(a,"x"),s=k(a,"y");null==l.from&&(l.from=l.axis.min),null==l.to&&(l.to=l.axis.max),null==s.from&&(s.from=s.axis.min),null==s.to&&(s.to=s.axis.max),l.to<l.axis.min||l.from>l.axis.max||s.to<s.axis.min||s.from>s.axis.max||(l.from=Math.max(l.from,l.axis.min),l.to=Math.min(l.to,l.axis.max),s.from=Math.max(s.from,s.axis.min),s.to=Math.min(s.to,s.axis.max),l.from==l.to&&s.from==s.to||(l.from=l.axis.p2c(l.from),l.to=l.axis.p2c(l.to),s.from=s.axis.p2c(s.from),s.to=s.axis.p2c(s.to),l.from==l.to||s.from==s.to?(V.beginPath(),V.strokeStyle=a.color||B.grid.markingsColor,V.lineWidth=a.lineWidth||B.grid.markingsLineWidth,V.moveTo(l.from,s.from),V.lineTo(l.to,s.to),V.stroke()):(V.fillStyle=a.color||B.grid.markingsColor,V.fillRect(l.from,s.to,l.to-l.from,s.from-s.to))))}e=h(),o=B.grid.borderWidth;for(var c=0;c<e.length;++c){var f,u,d,p,m=e[c],x=m.box,g=m.tickLength;if(m.show&&0!=m.ticks.length){for(V.lineWidth=1,"x"==m.direction?(f=0,u="full"==g?"top"==m.position?0:J:x.top-Q.top+("top"==m.position?x.height:0)):(u=0,f="full"==g?"left"==m.position?0:U:x.left-Q.left+("left"==m.position?x.width:0)),m.innermost||(V.strokeStyle=m.options.color,V.beginPath(),d=p=0,"x"==m.direction?d=U+1:p=J+1,1==V.lineWidth&&("x"==m.direction?u=Math.floor(u)+.5:f=Math.floor(f)+.5),V.moveTo(f,u),V.lineTo(f+d,u+p),V.stroke()),V.strokeStyle=m.options.tickColor,V.beginPath(),i=0;i<m.ticks.length;++i){var b=m.ticks[i].v;d=p=0,isNaN(b)||b<m.min||b>m.max||"full"==g&&("object"==typeof o&&o[m.position]>0||o>0)&&(b==m.min||b==m.max)||("x"==m.direction?(f=m.p2c(b),p="full"==g?-J:g,"top"==m.position&&(p=-p)):(u=m.p2c(b),d="full"==g?-U:g,"left"==m.position&&(d=-d)),1==V.lineWidth&&("x"==m.direction?f=Math.floor(f)+.5:u=Math.floor(u)+.5),V.moveTo(f,u),V.lineTo(f+d,u+p))}V.stroke()}}o&&(n=B.grid.borderColor,"object"==typeof o||"object"==typeof n?("object"!=typeof o&&(o={top:o,right:o,bottom:o,left:o}),"object"!=typeof n&&(n={top:n,right:n,bottom:n,left:n}),o.top>0&&(V.strokeStyle=n.top,V.lineWidth=o.top,V.beginPath(),V.moveTo(0-o.left,0-o.top/2),V.lineTo(U,0-o.top/2),V.stroke()),o.right>0&&(V.strokeStyle=n.right,V.lineWidth=o.right,V.beginPath(),V.moveTo(U+o.right/2,0-o.top),V.lineTo(U+o.right/2,J),V.stroke()),o.bottom>0&&(V.strokeStyle=n.bottom,V.lineWidth=o.bottom,V.beginPath(),V.moveTo(U+o.right,J+o.bottom/2),V.lineTo(0,J+o.bottom/2),V.stroke()),o.left>0&&(V.strokeStyle=n.left,V.lineWidth=o.left,V.beginPath(),V.moveTo(0-o.left/2,J+o.bottom),V.lineTo(0-o.left/2,0),V.stroke())):(V.lineWidth=o,V.strokeStyle=B.grid.borderColor,V.strokeRect(-o/2,-o/2,U+o,J+o))),V.restore()}function w(t){t.lines.show&&function(t){function i(t,i,e,o,n){var r=t.points,a=t.pointsize,l=null,s=null;V.beginPath();for(var c=a;c<r.length;c+=a){var h=r[c-a],f=r[c-a+1],u=r[c],d=r[c+1];if(null!=h&&null!=u){if(f<=d&&f<n.min){if(d<n.min)continue;h=(n.min-f)/(d-f)*(u-h)+h,f=n.min}else if(d<=f&&d<n.min){if(f<n.min)continue;u=(n.min-f)/(d-f)*(u-h)+h,d=n.min}if(f>=d&&f>n.max){if(d>n.max)continue;h=(n.max-f)/(d-f)*(u-h)+h,f=n.max}else if(d>=f&&d>n.max){if(f>n.max)continue;u=(n.max-f)/(d-f)*(u-h)+h,d=n.max}if(h<=u&&h<o.min){if(u<o.min)continue;f=(o.min-h)/(u-h)*(d-f)+f,h=o.min}else if(u<=h&&u<o.min){if(h<o.min)continue;d=(o.min-h)/(u-h)*(d-f)+f,u=o.min}if(h>=u&&h>o.max){if(u>o.max)continue;f=(o.max-h)/(u-h)*(d-f)+f,h=o.max}else if(u>=h&&u>o.max){if(h>o.max)continue;d=(o.max-h)/(u-h)*(d-f)+f,u=o.max}(h!=l||f!=s)&&V.moveTo(o.p2c(h)+i,n.p2c(f)+e),l=u,s=d,V.lineTo(o.p2c(u)+i,n.p2c(d)+e)}}V.stroke()}V.save(),V.translate(Q.left,Q.top),V.lineJoin="round";var e=t.lines.lineWidth,o=t.shadowSize;if(e>0&&o>0){V.lineWidth=o,V.strokeStyle="rgba(0,0,0,0.1)";var n=Math.PI/18;i(t.datapoints,Math.sin(n)*(e/2+o/2),Math.cos(n)*(e/2+o/2),t.xaxis,t.yaxis),V.lineWidth=o/2,i(t.datapoints,Math.sin(n)*(e/2+o/4),Math.cos(n)*(e/2+o/4),t.xaxis,t.yaxis)}V.lineWidth=e,V.strokeStyle=t.color;var r=M(t.lines,t.color,0,J);r&&(V.fillStyle=r,function(t,i,e){for(var o=t.points,n=t.pointsize,r=Math.min(Math.max(0,e.min),e.max),a=0,l=!1,s=1,c=0,h=0;!(n>0&&a>o.length+n);){var f=o[(a+=n)-n],u=o[a-n+s],d=o[a],p=o[a+s];if(l){if(n>0&&null!=f&&null==d){h=a,n=-n,s=2;continue}if(n<0&&a==c+n){V.fill(),l=!1,s=1,a=c=h+(n=-n);continue}}if(null!=f&&null!=d){if(f<=d&&f<i.min){if(d<i.min)continue;u=(i.min-f)/(d-f)*(p-u)+u,f=i.min}else if(d<=f&&d<i.min){if(f<i.min)continue;p=(i.min-f)/(d-f)*(p-u)+u,d=i.min}if(f>=d&&f>i.max){if(d>i.max)continue;u=(i.max-f)/(d-f)*(p-u)+u,f=i.max}else if(d>=f&&d>i.max){if(f>i.max)continue;p=(i.max-f)/(d-f)*(p-u)+u,d=i.max}if(l||(V.beginPath(),V.moveTo(i.p2c(f),e.p2c(r)),l=!0),u>=e.max&&p>=e.max)V.lineTo(i.p2c(f),e.p2c(e.max)),V.lineTo(i.p2c(d),e.p2c(e.max));else if(u<=e.min&&p<=e.min)V.lineTo(i.p2c(f),e.p2c(e.min)),V.lineTo(i.p2c(d),e.p2c(e.min));else{var m=f,x=d;u<=p&&u<e.min&&p>=e.min?(f=(e.min-u)/(p-u)*(d-f)+f,u=e.min):p<=u&&p<e.min&&u>=e.min&&(d=(e.min-u)/(p-u)*(d-f)+f,p=e.min),u>=p&&u>e.max&&p<=e.max?(f=(e.max-u)/(p-u)*(d-f)+f,u=e.max):p>=u&&p>e.max&&u<=e.max&&(d=(e.max-u)/(p-u)*(d-f)+f,p=e.max),f!=m&&V.lineTo(i.p2c(m),e.p2c(u)),V.lineTo(i.p2c(f),e.p2c(u)),V.lineTo(i.p2c(d),e.p2c(p)),d!=x&&(V.lineTo(i.p2c(d),e.p2c(p)),V.lineTo(i.p2c(x),e.p2c(p)))}}}}(t.datapoints,t.xaxis,t.yaxis)),e>0&&i(t.datapoints,0,0,t.xaxis,t.yaxis),V.restore()}(t),t.bars.show&&function(t){var i;switch(V.save(),V.translate(Q.left,Q.top),V.lineWidth=t.bars.lineWidth,V.strokeStyle=t.color,t.bars.align){case"left":i=0;break;case"right":i=-t.bars.barWidth;break;case"center":i=-t.bars.barWidth/2;break;default:throw new Error("Invalid bar alignment: "+t.bars.align)}var e=t.bars.fill?function(i,e){return M(t.bars,t.color,i,e)}:null;(function(i,e,o,n,r,a,l){for(var s=i.points,c=i.pointsize,h=0;h<s.length;h+=c)null!=s[h]&&T(s[h],s[h+1],s[h+2],e,o,n,r,a,l,V,t.bars.horizontal,t.bars.lineWidth)})(t.datapoints,i,i+t.bars.barWidth,0,e,t.xaxis,t.yaxis),V.restore()}(t),t.points.show&&function(t){function i(t,i,e,o,n,r,a,l){for(var s=t.points,c=t.pointsize,h=0;h<s.length;h+=c){var f=s[h],u=s[h+1];null==f||f<r.min||f>r.max||u<a.min||u>a.max||(V.beginPath(),f=r.p2c(f),u=a.p2c(u)+o,"circle"==l?V.arc(f,u,i,0,n?Math.PI:2*Math.PI,!1):l(V,f,u,i,n),V.closePath(),e&&(V.fillStyle=e,V.fill()),V.stroke())}}V.save(),V.translate(Q.left,Q.top);var e=t.points.lineWidth,o=t.shadowSize,n=t.points.radius,r=t.points.symbol;if(0==e&&(e=1e-4),e>0&&o>0){var a=o/2;V.lineWidth=a,V.strokeStyle="rgba(0,0,0,0.1)",i(t.datapoints,n,null,a+a/2,!0,t.xaxis,t.yaxis,r),V.strokeStyle="rgba(0,0,0,0.2)",i(t.datapoints,n,null,a/2,!0,t.xaxis,t.yaxis,r)}V.lineWidth=e,V.strokeStyle=t.color,i(t.datapoints,n,M(t.points,t.color),0,!1,t.xaxis,t.yaxis,r),V.restore()}(t)}function T(t,i,e,o,n,r,a,l,s,c,h,f){var u,d,p,m,x,g,b,v,k;h?(v=g=b=!0,x=!1,m=i+o,p=i+n,(d=t)<(u=e)&&(k=d,d=u,u=k,x=!0,g=!1)):(x=g=b=!0,v=!1,u=t+o,d=t+n,(m=i)<(p=e)&&(k=m,m=p,p=k,v=!0,b=!1)),d<l.min||u>l.max||m<s.min||p>s.max||(u<l.min&&(u=l.min,x=!1),d>l.max&&(d=l.max,g=!1),p<s.min&&(p=s.min,v=!1),m>s.max&&(m=s.max,b=!1),u=l.p2c(u),p=s.p2c(p),d=l.p2c(d),m=s.p2c(m),a&&(c.beginPath(),c.moveTo(u,p),c.lineTo(u,m),c.lineTo(d,m),c.lineTo(d,p),c.fillStyle=a(p,m),c.fill()),f>0&&(x||g||b||v)&&(c.beginPath(),c.moveTo(u,p+r),x?c.lineTo(u,m+r):c.moveTo(u,m+r),b?c.lineTo(d,m+r):c.moveTo(d,m+r),g?c.lineTo(d,p+r):c.moveTo(d,p+r),v?c.lineTo(u,p+r):c.moveTo(u,p+r),c.stroke()))}function M(i,e,o,n){var r=i.fill;if(!r)return null;if(i.fillColor)return R(i.fillColor,o,n,e);var a=t.color.parse(e);return a.a="number"==typeof r?r:.4,a.normalize(),a.toString()}function C(){if(e.find(".legend").remove(),B.legend.show){for(var i,o,n=[],r=[],a=!1,l=B.legend.labelFormatter,s=0;s<j.length;++s)(i=j[s]).label&&((o=l?l(i.label,i):i.label)&&r.push({label:o,color:i.color}));if(B.legend.sorted)if(t.isFunction(B.legend.sorted))r.sort(B.legend.sorted);else if("reverse"==B.legend.sorted)r.reverse();else{var c="descending"!=B.legend.sorted;r.sort(function(t,i){return t.label==i.label?0:t.label<i.label!=c?1:-1})}for(s=0;s<r.length;++s){var h=r[s];s%B.legend.noColumns==0&&(a&&n.push("</tr>"),n.push("<tr>"),a=!0),n.push('<td class="legendColorBox"><div style="border:1px solid '+B.legend.labelBoxBorderColor+';padding:1px"><div style="width:4px;height:0;border:5px solid '+h.color+';overflow:hidden"></div></div></td><td class="legendLabel">'+h.label+"</td>")}if(a&&n.push("</tr>"),0!=n.length){var f='<table style="font-size:smaller;color:'+B.grid.color+'">'+n.join("")+"</table>";if(null!=B.legend.container)t(B.legend.container).html(f);else{var u="",d=B.legend.position,p=B.legend.margin;null==p[0]&&(p=[p,p]),"n"==d.charAt(0)?u+="top:"+(p[1]+Q.top)+"px;":"s"==d.charAt(0)&&(u+="bottom:"+(p[1]+Q.bottom)+"px;"),"e"==d.charAt(1)?u+="right:"+(p[0]+Q.right)+"px;":"w"==d.charAt(1)&&(u+="left:"+(p[0]+Q.left)+"px;");var m=t('<div class="legend">'+f.replace('style="','style="position:absolute;'+u+";")+"</div>").appendTo(e);if(0!=B.legend.backgroundOpacity){var x=B.legend.backgroundColor;null==x&&((x=(x=B.grid.backgroundColor)&&"string"==typeof x?t.color.parse(x):t.color.extract(m,"background-color")).a=1,x=x.toString());var g=m.children();t('<div style="position:absolute;width:'+g.width()+"px;height:"+g.height()+"px;"+u+"background-color:"+x+';"> </div>').prependTo(m).css("opacity",B.legend.backgroundOpacity)}}}}}function S(t,i,e){var o,n,r,a=B.grid.mouseActiveRadius,l=a*a+1,s=null;for(o=j.length-1;o>=0;--o)if(e(j[o])){var c=j[o],h=c.xaxis,f=c.yaxis,u=c.datapoints.points,d=h.c2p(t),p=f.c2p(i),m=a/h.scale,x=a/f.scale;if(r=c.datapoints.pointsize,h.options.inverseTransform&&(m=Number.MAX_VALUE),f.options.inverseTransform&&(x=Number.MAX_VALUE),c.lines.show||c.points.show)for(n=0;n<u.length;n+=r){var g=u[n],b=u[n+1];if(null!=g&&!(g-d>m||g-d<-m||b-p>x||b-p<-x)){var v=Math.abs(h.p2c(g)-t),k=Math.abs(f.p2c(b)-i),y=v*v+k*k;y<l&&(l=y,s=[o,n/r])}}if(c.bars.show&&!s){var w="left"==c.bars.align?0:-c.bars.barWidth/2,T=w+c.bars.barWidth;for(n=0;n<u.length;n+=r){g=u[n],b=u[n+1];var M=u[n+2];null!=g&&((j[o].bars.horizontal?d<=Math.max(M,g)&&d>=Math.min(M,g)&&p>=b+w&&p<=b+T:d>=g+w&&d<=g+T&&p>=Math.min(M,b)&&p<=Math.max(M,b))&&(s=[o,n/r]))}}}return s?(o=s[0],n=s[1],r=j[o].datapoints.pointsize,{datapoint:j[o].datapoints.points.slice(n*r,(n+1)*r),dataIndex:n,series:j[o],seriesIndex:o}):null}function W(t){B.grid.hoverable&&A("plothover",t,function(t){return 0!=t.hoverable})}function z(t){B.grid.hoverable&&A("plothover",t,function(t){return!1})}function I(t){A("plotclick",t,function(t){return 0!=t.clickable})}function A(t,i,o){var n=_.offset(),r=i.pageX-n.left-Q.left,a=i.pageY-n.top-Q.top,l=f({left:r,top:a});l.pageX=i.pageX,l.pageY=i.pageY;var s=S(r,a,o);if(s&&(s.pageX=parseInt(s.series.xaxis.p2c(s.datapoint[0])+n.left+Q.left,10),s.pageY=parseInt(s.series.yaxis.p2c(s.datapoint[1])+n.top+Q.top,10)),B.grid.autoHighlight){for(var c=0;c<Z.length;++c){var h=Z[c];h.auto==t&&(!s||h.series!=s.series||h.point[0]!=s.datapoint[0]||h.point[1]!=s.datapoint[1])&&D(h.series,h.point)}s&&N(s.series,s.datapoint,t)}e.trigger(t,[l,s])}function P(){var t=B.interaction.redrawOverlayInterval;-1!=t?tt||(tt=setTimeout(F,t)):F()}function F(){var t,i;for(tt=null,X.save(),G.clear(),X.translate(Q.left,Q.top),t=0;t<Z.length;++t)(i=Z[t]).series.bars.show?E(i.series,i.point):O(i.series,i.point);X.restore(),a($.drawOverlay,[X])}function N(t,i,e){if("number"==typeof t&&(t=j[t]),"number"==typeof i){var o=t.datapoints.pointsize;i=t.datapoints.points.slice(o*i,o*(i+1))}var n=L(t,i);-1==n?(Z.push({series:t,point:i,auto:e}),P()):e||(Z[n].auto=!1)}function D(t,i){if(null==t&&null==i)return Z=[],void P();if("number"==typeof t&&(t=j[t]),"number"==typeof i){var e=t.datapoints.pointsize;i=t.datapoints.points.slice(e*i,e*(i+1))}var o=L(t,i);-1!=o&&(Z.splice(o,1),P())}function L(t,i){for(var e=0;e<Z.length;++e){var o=Z[e];if(o.series==t&&o.point[0]==i[0]&&o.point[1]==i[1])return e}return-1}function O(i,e){var o=e[0],n=e[1],r=i.xaxis,a=i.yaxis,l="string"==typeof i.highlightColor?i.highlightColor:t.color.parse(i.color).scale("a",.5).toString();if(!(o<r.min||o>r.max||n<a.min||n>a.max)){var s=i.points.radius+i.points.lineWidth/2;X.lineWidth=s,X.strokeStyle=l;var c=1.5*s;o=r.p2c(o),n=a.p2c(n),X.beginPath(),"circle"==i.points.symbol?X.arc(o,n,c,0,2*Math.PI,!1):i.points.symbol(X,o,n,c,!1),X.closePath(),X.stroke()}}function E(i,e){var o="string"==typeof i.highlightColor?i.highlightColor:t.color.parse(i.color).scale("a",.5).toString(),n=o,r="left"==i.bars.align?0:-i.bars.barWidth/2;X.lineWidth=i.bars.lineWidth,X.strokeStyle=o,T(e[0],e[1],e[2]||0,r,r+i.bars.barWidth,0,function(){return n},i.xaxis,i.yaxis,X,i.bars.horizontal,i.bars.lineWidth)}function R(i,e,o,n){if("string"==typeof i)return i;for(var r=V.createLinearGradient(0,o,0,e),a=0,l=i.colors.length;a<l;++a){var s=i.colors[a];if("string"!=typeof s){var c=t.color.parse(n);null!=s.brightness&&(c=c.scale("rgb",s.brightness)),null!=s.opacity&&(c.a*=s.opacity),s=c.toString()}r.addColorStop(a/(l-1),s)}return r}var j=[],B={colors:["#edc240","#afd8f8","#cb4b4b","#4da74d","#9440ed"],legend:{show:!0,noColumns:1,labelFormatter:null,labelBoxBorderColor:"#ccc",container:null,position:"ne",margin:5,backgroundColor:null,backgroundOpacity:.85,sorted:null},xaxis:{show:null,position:"bottom",mode:null,font:null,color:null,tickColor:null,transform:null,inverseTransform:null,min:null,max:null,autoscaleMargin:null,ticks:null,tickFormatter:null,labelWidth:null,labelHeight:null,reserveSpace:null,tickLength:null,alignTicksWithAxis:null,tickDecimals:null,tickSize:null,minTickSize:null},yaxis:{autoscaleMargin:.02,position:"left"},xaxes:[],yaxes:[],series:{points:{show:!1,radius:3,lineWidth:2,fill:!0,fillColor:"#ffffff",symbol:"circle"},lines:{lineWidth:2,fill:!1,fillColor:null,steps:!1},bars:{show:!1,lineWidth:2,barWidth:1,fill:!0,fillColor:null,align:"left",horizontal:!1,zero:!0},shadowSize:3,highlightColor:null},grid:{show:!0,aboveData:!1,color:"#545454",backgroundColor:null,borderColor:null,tickColor:null,margin:0,labelMargin:5,axisMargin:8,borderWidth:2,minBorderMargin:null,markings:null,markingsColor:"#f4f4f4",markingsLineWidth:2,clickable:!1,hoverable:!1,autoHighlight:!0,mouseActiveRadius:10},interaction:{redrawOverlayInterval:1e3/60},hooks:{}},H=null,G=null,_=null,V=null,X=null,Y=[],q=[],Q={left:0,right:0,top:0,bottom:0},U=0,J=0,$={processOptions:[],processRawData:[],processDatapoints:[],processOffset:[],drawBackground:[],drawSeries:[],draw:[],bindEvents:[],drawOverlay:[],shutdown:[]},K=this;K.setData=l,K.setupGrid=x,K.draw=v,K.getPlaceholder=function(){return e},K.getCanvas=function(){return H.element},K.getPlotOffset=function(){return Q},K.width=function(){return U},K.height=function(){return J},K.offset=function(){var t=_.offset();return t.left+=Q.left,t.top+=Q.top,t},K.getData=function(){return j},K.getAxes=function(){var i={};return t.each(Y.concat(q),function(t,e){e&&(i[e.direction+(1!=e.n?e.n:"")+"axis"]=e)}),i},K.getXAxes=function(){return Y},K.getYAxes=function(){return q},K.c2p=f,K.p2c=function(t){var i,e,o,n={};for(i=0;i<Y.length;++i)if((e=Y[i])&&e.used&&(null==t[o="x"+e.n]&&1==e.n&&(o="x"),null!=t[o])){n.left=e.p2c(t[o]);break}for(i=0;i<q.length;++i)if((e=q[i])&&e.used&&(null==t[o="y"+e.n]&&1==e.n&&(o="y"),null!=t[o])){n.top=e.p2c(t[o]);break}return n},K.getOptions=function(){return B},K.highlight=N,K.unhighlight=D,K.triggerRedrawOverlay=P,K.pointOffset=function(t){return{left:parseInt(Y[c(t,"x")-1].p2c(+t.x)+Q.left,10),top:parseInt(q[c(t,"y")-1].p2c(+t.y)+Q.top,10)}},K.shutdown=function(){tt&&clearTimeout(tt),_.unbind("mousemove",W),_.unbind("mouseleave",z),_.unbind("click",I),a($.shutdown,[_])},K.resize=function(){var t=e.width(),i=e.height();H.resize(t,i),G.resize(t,i)},K.hooks=$,function(){for(var e={Canvas:i},o=0;o<r.length;++o){var n=r[o];n.init(K,e),n.options&&t.extend(!0,B,n.options)}}(),function(i){var o;console.log(),t.extend(!0,B,i),i&&i.colors&&(B.colors=i.colors),null==B.xaxis.color&&(B.xaxis.color=t.color.parse(B.grid.color).scale("a",.22).toString()),null==B.yaxis.color&&(B.yaxis.color=t.color.parse(B.grid.color).scale("a",.22).toString()),null==B.xaxis.tickColor&&(B.xaxis.tickColor=B.grid.tickColor||B.xaxis.color),null==B.yaxis.tickColor&&(B.yaxis.tickColor=B.grid.tickColor||B.yaxis.color),null==B.grid.borderColor&&(B.grid.borderColor=B.grid.color),null==B.grid.tickColor&&(B.grid.tickColor=t.color.parse(B.grid.color).scale("a",.22).toString()),o=void 0===e.css("font-size")||null===e.css("font-size")?13:e.css("font-size").replace("px","");var n,r,l,s={style:e.css("font-style"),size:Math.round(.8*o),variant:e.css("font-variant"),weight:e.css("font-weight"),family:e.css("font-family")};for(s.lineHeight=1.15*s.size,l=B.xaxes.length||1,n=0;n<l;++n)(r=B.xaxes[n])&&!r.tickColor&&(r.tickColor=r.color),r=t.extend(!0,{},B.xaxis,r),B.xaxes[n]=r,r.font&&(r.font=t.extend({},s,r.font),r.font.color||(r.font.color=r.color));for(l=B.yaxes.length||1,n=0;n<l;++n)(r=B.yaxes[n])&&!r.tickColor&&(r.tickColor=r.color),r=t.extend(!0,{},B.yaxis,r),B.yaxes[n]=r,r.font&&(r.font=t.extend({},s,r.font),r.font.color||(r.font.color=r.color));for(B.xaxis.noTicks&&null==B.xaxis.ticks&&(B.xaxis.ticks=B.xaxis.noTicks),B.yaxis.noTicks&&null==B.yaxis.ticks&&(B.yaxis.ticks=B.yaxis.noTicks),B.x2axis&&(B.xaxes[1]=t.extend(!0,{},B.xaxis,B.x2axis),B.xaxes[1].position="top"),B.y2axis&&(B.yaxes[1]=t.extend(!0,{},B.yaxis,B.y2axis),B.yaxes[1].position="right"),B.grid.coloredAreas&&(B.grid.markings=B.grid.coloredAreas),B.grid.coloredAreasColor&&(B.grid.markingsColor=B.grid.coloredAreasColor),B.lines&&t.extend(!0,B.series.lines,B.lines),B.points&&t.extend(!0,B.series.points,B.points),B.bars&&t.extend(!0,B.series.bars,B.bars),null!=B.shadowSize&&(B.series.shadowSize=B.shadowSize),null!=B.highlightColor&&(B.series.highlightColor=B.highlightColor),n=0;n<B.xaxes.length;++n)u(Y,n+1).options=B.xaxes[n];for(n=0;n<B.yaxes.length;++n)u(q,n+1).options=B.yaxes[n];for(var c in $)B.hooks[c]&&B.hooks[c].length&&($[c]=$[c].concat(B.hooks[c]));a($.processOptions,[B])}(n),function(){e.css("padding",0).children(":not(.flot-base,.flot-overlay)").remove(),"static"==e.css("position")&&e.css("position","relative"),H=new i("flot-base",e),G=new i("flot-overlay",e),V=H.context,X=G.context,_=t(G.element).unbind();var o=e.data("plot");o&&(o.shutdown(),G.clear()),e.data("plot",K)}(),l(o),x(),v(),B.grid.hoverable&&(_.mousemove(W),_.bind("mouseleave",z)),B.grid.clickable&&_.click(I),a($.bindEvents,[_]);var Z=[],tt=null}var o=Object.prototype.hasOwnProperty;i.prototype.resize=function(t,i){if(t<=0||i<=0)throw new Error("Invalid dimensions for plot, width = "+t+", height = "+i);var e=this.element,o=this.context,n=this.pixelRatio;this.width!=t&&(e.width=t*n,e.style.width=t+"px",this.width=t),this.height!=i&&(e.height=i*n,e.style.height=i+"px",this.height=i),o.restore(),o.save(),o.scale(n,n)},i.prototype.clear=function(){this.context.clearRect(0,0,this.width,this.height)},i.prototype.render=function(){var t=this._textCache;for(var i in t)if(o.call(t,i)){var e=this.getTextLayer(i),n=t[i];for(var r in e.hide(),n)if(o.call(n,r)){var a=n[r];for(var l in a)if(o.call(a,l)){for(var s,c=a[l].positions,h=0;s=c[h];h++)s.active?s.rendered||(e.append(s.element),s.rendered=!0):(c.splice(h--,1),s.rendered&&s.element.detach());0==c.length&&delete a[l]}}e.show()}},i.prototype.getTextLayer=function(i){var e=this.text[i];return null==e&&(null==this.textContainer&&(this.textContainer=t("<div class='flot-text'></div>").css({position:"absolute",top:0,left:0,bottom:0,right:0,"font-size":"smaller",color:"#545454"}).insertAfter(this.element)),e=this.text[i]=t("<div></div>").addClass(i).css({position:"absolute",top:0,left:0,bottom:0,right:0}).appendTo(this.textContainer)),e},i.prototype.getTextInfo=function(i,e,o,n,r){var a,l,s,c;if(e=""+e,a="object"==typeof o?o.style+" "+o.variant+" "+o.weight+" "+o.size+"px/"+o.lineHeight+"px "+o.family:o,null==(l=this._textCache[i])&&(l=this._textCache[i]={}),null==(s=l[a])&&(s=l[a]={}),null==(c=s[e])){var h=t("<div></div>").html(e).css({position:"absolute","max-width":r,top:-9999}).appendTo(this.getTextLayer(i));"object"==typeof o?h.css({font:a,color:o.color}):"string"==typeof o&&h.addClass(o),c=s[e]={width:h.outerWidth(!0),height:h.outerHeight(!0),element:h,positions:[]},h.detach()}return c},i.prototype.addText=function(t,i,e,o,n,r,a,l,s){var c=this.getTextInfo(t,o,n,r,a),h=c.positions;"center"==l?i-=c.width/2:"right"==l&&(i-=c.width),"middle"==s?e-=c.height/2:"bottom"==s&&(e-=c.height);for(var f,u=0;f=h[u];u++)if(f.x==i&&f.y==e)return void(f.active=!0);f={active:!0,rendered:!1,element:h.length?c.element.clone():c.element,x:i,y:e},h.push(f),f.element.css({top:Math.round(e),left:Math.round(i),"text-align":l})},i.prototype.removeText=function(t,i,e,n,r,a){if(null==n){var l=this._textCache[t];if(null!=l)for(var s in l)if(o.call(l,s)){var c=l[s];for(var h in c)if(o.call(c,h))for(var f=c[h].positions,u=0;d=f[u];u++)d.active=!1}}else{var d;for(f=this.getTextInfo(t,n,r,a).positions,u=0;d=f[u];u++)d.x==i&&d.y==e&&(d.active=!1)}},t.plot=function(i,o,n){return new e(t(i),o,n,t.plot.plugins)},t.plot.version="0.8.1",t.plot.plugins=[],t.fn.plot=function(i,e){return this.each(function(){t.plot(this,i,e)})}}(jQuery);

/* Flot plugin for rendering pie charts.

Copyright (c) 2007-2013 IOLA and Ole Laursen.
Licensed under the MIT license.

The plugin assumes that each series has a single data value, and that each
value is a positive integer or zero.  Negative numbers don't make sense for a
pie chart, and have unpredictable results.  The values do NOT need to be
passed in as percentages; the plugin will calculate the total and per-slice
percentages internally.

* Created by Brian Medendorp

* Updated with contributions from btburnett3, Anthony Aragues and Xavi Ivars

The plugin supports these options:

	series: {
		pie: {
			show: true/false
			radius: 0-1 for percentage of fullsize, or a specified pixel length, or 'auto'
			innerRadius: 0-1 for percentage of fullsize or a specified pixel length, for creating a donut effect
			startAngle: 0-2 factor of PI used for starting angle (in radians) i.e 3/2 starts at the top, 0 and 2 have the same result
			tilt: 0-1 for percentage to tilt the pie, where 1 is no tilt, and 0 is completely flat (nothing will show)
			offset: {
				top: integer value to move the pie up or down
				left: integer value to move the pie left or right, or 'auto'
			},
			stroke: {
				color: any hexidecimal color value (other formats may or may not work, so best to stick with something like '#FFF')
				width: integer pixel width of the stroke
			},
			label: {
				show: true/false, or 'auto'
				formatter:  a user-defined function that modifies the text/style of the label text
				radius: 0-1 for percentage of fullsize, or a specified pixel length
				background: {
					color: any hexidecimal color value (other formats may or may not work, so best to stick with something like '#000')
					opacity: 0-1
				},
				threshold: 0-1 for the percentage value at which to hide labels (if they're too small)
			},
			combine: {
				threshold: 0-1 for the percentage value at which to combine slices (if they're too small)
				color: any hexidecimal color value (other formats may or may not work, so best to stick with something like '#CCC'), if null, the plugin will automatically use the color of the first slice to be combined
				label: any text value of what the combined slice should be labeled
			}
			highlight: {
				opacity: 0-1
			}
		}
	}

More detail and specific examples can be found in the included HTML file.

*/(function(e){function r(r){function p(t,n,r){l||(l=!0,s=t.getCanvas(),o=e(s).parent(),i=t.getOptions(),t.setData(d(t.getData())))}function d(t){var n=0,r=0,s=0,o=i.series.pie.combine.color,u=[];for(var a=0;a<t.length;++a){var f=t[a].data;e.isArray(f)&&f.length==1&&(f=f[0]),e.isArray(f)?!isNaN(parseFloat(f[1]))&&isFinite(f[1])?f[1]=+f[1]:f[1]=0:!isNaN(parseFloat(f))&&isFinite(f)?f=[1,+f]:f=[1,0],t[a].data=[f]}for(var a=0;a<t.length;++a)n+=t[a].data[0][1];for(var a=0;a<t.length;++a){var f=t[a].data[0][1];f/n<=i.series.pie.combine.threshold&&(r+=f,s++,o||(o=t[a].color))}for(var a=0;a<t.length;++a){var f=t[a].data[0][1];(s<2||f/n>i.series.pie.combine.threshold)&&u.push({data:[[1,f]],color:t[a].color,label:t[a].label,angle:f*Math.PI*2/n,percent:f/(n/100)})}return s>1&&u.push({data:[[1,r]],color:o,label:i.series.pie.combine.label,angle:r*Math.PI*2/n,percent:r/(n/100)}),u}function v(r,s){function y(){c.clearRect(0,0,h,p),o.children().filter(".pieLabel, .pieLabelBackground").remove()}function b(){var e=i.series.pie.shadow.left,t=i.series.pie.shadow.top,n=10,r=i.series.pie.shadow.alpha,s=i.series.pie.radius>1?i.series.pie.radius:u*i.series.pie.radius;if(s>=h/2-e||s*i.series.pie.tilt>=p/2-t||s<=n)return;c.save(),c.translate(e,t),c.globalAlpha=r,c.fillStyle="#000",c.translate(a,f),c.scale(1,i.series.pie.tilt);for(var o=1;o<=n;o++)c.beginPath(),c.arc(0,0,s,0,Math.PI*2,!1),c.fill(),s-=o;c.restore()}function w(){function l(e,t,i){if(e<=0||isNaN(e))return;i?c.fillStyle=t:(c.strokeStyle=t,c.lineJoin="round"),c.beginPath(),Math.abs(e-Math.PI*2)>1e-9&&c.moveTo(0,0),c.arc(0,0,n,r,r+e/2,!1),c.arc(0,0,n,r+e/2,r+e,!1),c.closePath(),r+=e,i?c.fill():c.stroke()}function d(){function l(t,n,s){if(t.data[0][1]==0)return!0;var u=i.legend.labelFormatter,l,c=i.series.pie.label.formatter;u?l=u(t.label,t):l=t.label,c&&(l=c(l,t));var d=(n+t.angle+n)/2,v=a+Math.round(Math.cos(d)*r),m=f+Math.round(Math.sin(d)*r)*i.series.pie.tilt,g="<span class='pieLabel' id='pieLabel"+s+"' style='position:absolute;top:"+m+"px;left:"+v+"px;'>"+l+"</span>";o.append(g);var y=o.children("#pieLabel"+s),b=m-y.height()/2,w=v-y.width()/2;y.css("top",b),y.css("left",w);if(0-b>0||0-w>0||p-(b+y.height())<0||h-(w+y.width())<0)return!1;if(i.series.pie.label.background.opacity!=0){var E=i.series.pie.label.background.color;E==null&&(E=t.color);var S="top:"+b+"px;left:"+w+"px;";e("<div class='pieLabelBackground' style='position:absolute;width:"+y.width()+"px;height:"+y.height()+"px;"+S+"background-color:"+E+";'></div>").css("opacity",i.series.pie.label.background.opacity).insertBefore(y)}return!0}var n=t,r=i.series.pie.label.radius>1?i.series.pie.label.radius:u*i.series.pie.label.radius;for(var s=0;s<v.length;++s){if(v[s].percent>=i.series.pie.label.threshold*100&&!l(v[s],n,s))return!1;n+=v[s].angle}return!0}var t=Math.PI*i.series.pie.startAngle,n=i.series.pie.radius>1?i.series.pie.radius:u*i.series.pie.radius;c.save(),c.translate(a,f),c.scale(1,i.series.pie.tilt),c.save();var r=t;for(var s=0;s<v.length;++s)v[s].startAngle=r,l(v[s].angle,v[s].color,!0);c.restore();if(i.series.pie.stroke.width>0){c.save(),c.lineWidth=i.series.pie.stroke.width,r=t;for(var s=0;s<v.length;++s)l(v[s].angle,i.series.pie.stroke.color,!1);c.restore()}return m(c),c.restore(),i.series.pie.label.show?d():!0}if(!o)return;var h=r.getPlaceholder().width(),p=r.getPlaceholder().height(),d=o.children().filter(".legend").children().width()||0;c=s,l=!1,u=Math.min(h,p/i.series.pie.tilt)/2,f=p/2+i.series.pie.offset.top,a=h/2,i.series.pie.offset.left=="auto"?i.legend.position.match("w")?a+=d/2:a-=d/2:a+=i.series.pie.offset.left,a<u?a=u:a>h-u&&(a=h-u);var v=r.getData(),g=0;do g>0&&(u*=n),g+=1,y(),i.series.pie.tilt<=.8&&b();while(!w()&&g<t);g>=t&&(y(),o.prepend("<div class='error'>Could not draw pie with labels contained inside canvas</div>")),r.setSeries&&r.insertLegend&&(r.setSeries(v),r.insertLegend())}function m(e){if(i.series.pie.innerRadius>0){e.save();var t=i.series.pie.innerRadius>1?i.series.pie.innerRadius:u*i.series.pie.innerRadius;e.globalCompositeOperation="destination-out",e.beginPath(),e.fillStyle=i.series.pie.stroke.color,e.arc(0,0,t,0,Math.PI*2,!1),e.fill(),e.closePath(),e.restore(),e.save(),e.beginPath(),e.strokeStyle=i.series.pie.stroke.color,e.arc(0,0,t,0,Math.PI*2,!1),e.stroke(),e.closePath(),e.restore()}}function g(e,t){for(var n=!1,r=-1,i=e.length,s=i-1;++r<i;s=r)(e[r][1]<=t[1]&&t[1]<e[s][1]||e[s][1]<=t[1]&&t[1]<e[r][1])&&t[0]<(e[s][0]-e[r][0])*(t[1]-e[r][1])/(e[s][1]-e[r][1])+e[r][0]&&(n=!n);return n}function y(e,t){var n=r.getData(),i=r.getOptions(),s=i.series.pie.radius>1?i.series.pie.radius:u*i.series.pie.radius,o,l;for(var h=0;h<n.length;++h){var p=n[h];if(p.pie.show){c.save(),c.beginPath(),c.moveTo(0,0),c.arc(0,0,s,p.startAngle,p.startAngle+p.angle/2,!1),c.arc(0,0,s,p.startAngle+p.angle/2,p.startAngle+p.angle,!1),c.closePath(),o=e-a,l=t-f;if(c.isPointInPath){if(c.isPointInPath(e-a,t-f))return c.restore(),{datapoint:[p.percent,p.data],dataIndex:0,series:p,seriesIndex:h}}else{var d=s*Math.cos(p.startAngle),v=s*Math.sin(p.startAngle),m=s*Math.cos(p.startAngle+p.angle/4),y=s*Math.sin(p.startAngle+p.angle/4),b=s*Math.cos(p.startAngle+p.angle/2),w=s*Math.sin(p.startAngle+p.angle/2),E=s*Math.cos(p.startAngle+p.angle/1.5),S=s*Math.sin(p.startAngle+p.angle/1.5),x=s*Math.cos(p.startAngle+p.angle),T=s*Math.sin(p.startAngle+p.angle),N=[[0,0],[d,v],[m,y],[b,w],[E,S],[x,T]],C=[o,l];if(g(N,C))return c.restore(),{datapoint:[p.percent,p.data],dataIndex:0,series:p,seriesIndex:h}}c.restore()}}return null}function b(e){E("plothover",e)}function w(e){E("plotclick",e)}function E(e,t){var n=r.offset(),s=parseInt(t.pageX-n.left),u=parseInt(t.pageY-n.top),a=y(s,u);if(i.grid.autoHighlight)for(var f=0;f<h.length;++f){var l=h[f];l.auto==e&&(!a||l.series!=a.series)&&x(l.series)}a&&S(a.series,e);var c={pageX:t.pageX,pageY:t.pageY};o.trigger(e,[c,a])}function S(e,t){var n=T(e);n==-1?(h.push({series:e,auto:t}),r.triggerRedrawOverlay()):t||(h[n].auto=!1)}function x(e){e==null&&(h=[],r.triggerRedrawOverlay());var t=T(e);t!=-1&&(h.splice(t,1),r.triggerRedrawOverlay())}function T(e){for(var t=0;t<h.length;++t){var n=h[t];if(n.series==e)return t}return-1}function N(e,t){function s(e){if(e.angle<=0||isNaN(e.angle))return;t.fillStyle="rgba(255, 255, 255, "+n.series.pie.highlight.opacity+")",t.beginPath(),Math.abs(e.angle-Math.PI*2)>1e-9&&t.moveTo(0,0),t.arc(0,0,r,e.startAngle,e.startAngle+e.angle/2,!1),t.arc(0,0,r,e.startAngle+e.angle/2,e.startAngle+e.angle,!1),t.closePath(),t.fill()}var n=e.getOptions(),r=n.series.pie.radius>1?n.series.pie.radius:u*n.series.pie.radius;t.save(),t.translate(a,f),t.scale(1,n.series.pie.tilt);for(var i=0;i<h.length;++i)s(h[i].series);m(t),t.restore()}var s=null,o=null,u=null,a=null,f=null,l=!1,c=null,h=[];r.hooks.processOptions.push(function(e,t){t.series.pie.show&&(t.grid.show=!1,t.series.pie.label.show=="auto"&&(t.legend.show?t.series.pie.label.show=!1:t.series.pie.label.show=!0),t.series.pie.radius=="auto"&&(t.series.pie.label.show?t.series.pie.radius=.75:t.series.pie.radius=1),t.series.pie.tilt>1?t.series.pie.tilt=1:t.series.pie.tilt<0&&(t.series.pie.tilt=0))}),r.hooks.bindEvents.push(function(e,t){var n=e.getOptions();n.series.pie.show&&(n.grid.hoverable&&t.unbind("mousemove").mousemove(b),n.grid.clickable&&t.unbind("click").click(w))}),r.hooks.processDatapoints.push(function(e,t,n,r){var i=e.getOptions();i.series.pie.show&&p(e,t,n,r)}),r.hooks.drawOverlay.push(function(e,t){var n=e.getOptions();n.series.pie.show&&N(e,t)}),r.hooks.draw.push(function(e,t){var n=e.getOptions();n.series.pie.show&&v(e,t)})}var t=10,n=.95,i={series:{pie:{show:!1,radius:"auto",innerRadius:0,startAngle:1.5,tilt:1,shadow:{left:5,top:15,alpha:.02},offset:{top:0,left:"auto"},stroke:{color:"#fff",width:1},label:{show:"auto",formatter:function(e,t){return"<div style='font-size:x-small;text-align:center;padding:2px;color:"+t.color+";'>"+e+"<br/>"+Math.round(t.percent)+"%</div>"},radius:1,background:{color:null,opacity:0},threshold:0},combine:{threshold:-1,color:null,label:"Other"},highlight:{opacity:.5}}}};e.plot.plugins.push({init:r,options:i,name:"pie",version:"1.1"})})(jQuery);

(function($,t,n){function p(){for(var n=r.length-1;n>=0;n--){var o=$(r[n]);if(o[0]==t||o.is(":visible")){var h=o.width(),d=o.height(),v=o.data(a);!v||h===v.w&&d===v.h?i[f]=i[l]:(i[f]=i[c],o.trigger(u,[v.w=h,v.h=d]))}else v=o.data(a),v.w=0,v.h=0}s!==null&&(s=t.requestAnimationFrame(p))}var r=[],i=$.resize=$.extend($.resize,{}),s,o="setTimeout",u="resize",a=u+"-special-event",f="delay",l="pendingDelay",c="activeDelay",h="throttleWindow";i[l]=250,i[c]=20,i[f]=i[l],i[h]=!0,$.event.special[u]={setup:function(){if(!i[h]&&this[o])return!1;var t=$(this);r.push(this),t.data(a,{w:t.width(),h:t.height()}),r.length===1&&(s=n,p())},teardown:function(){if(!i[h]&&this[o])return!1;var t=$(this);for(var n=r.length-1;n>=0;n--)if(r[n]==this){r.splice(n,1);break}t.removeData(a),r.length||(cancelAnimationFrame(s),s=null)},add:function(t){function s(t,i,s){var o=$(this),u=o.data(a);u.w=i!==n?i:o.width(),u.h=s!==n?s:o.height(),r.apply(this,arguments)}if(!i[h]&&this[o])return!1;var r;if($.isFunction(t))return r=t,s;r=t.handler,t.handler=s}},t.requestAnimationFrame||(t.requestAnimationFrame=function(){return t.webkitRequestAnimationFrame||t.mozRequestAnimationFrame||t.oRequestAnimationFrame||t.msRequestAnimationFrame||function(e,n){return t.setTimeout(e,i[f])}}()),t.cancelAnimationFrame||(t.cancelAnimationFrame=function(){return t.webkitCancelRequestAnimationFrame||t.mozCancelRequestAnimationFrame||t.oCancelRequestAnimationFrame||t.msCancelRequestAnimationFrame||clearTimeout}())})(jQuery,this);(function($){var options={};function init(plot){function onResize(){var placeholder=plot.getPlaceholder();if(placeholder.width()==0||placeholder.height()==0)return;plot.resize();plot.setupGrid();plot.draw()}function bindEvents(plot,eventHolder){plot.getPlaceholder().resize(onResize)}function shutdown(plot,eventHolder){plot.getPlaceholder().unbind("resize",onResize)}plot.hooks.bindEvents.push(bindEvents);plot.hooks.shutdown.push(shutdown)}$.plot.plugins.push({init:init,options:options,name:"resize",version:"1.0"})})(jQuery);

/* Javascript plotting library for jQuery, version 0.8.3.

Copyright (c) 2007-2014 IOLA and Ole Laursen.
Licensed under the MIT license.

*/
(function($){var options={xaxis:{timezone:null,timeformat:null,twelveHourClock:false,monthNames:null}};function floorInBase(n,base){return base*Math.floor(n/base)}function formatDate(d,fmt,monthNames,dayNames){if(typeof d.strftime=="function"){return d.strftime(fmt)}var leftPad=function(n,pad){n=""+n;pad=""+(pad==null?"0":pad);return n.length==1?pad+n:n};var r=[];var escape=false;var hours=d.getHours();var isAM=hours<12;if(monthNames==null){monthNames=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]}if(dayNames==null){dayNames=["Sun","Mon","Tue","Wed","Thu","Fri","Sat"]}var hours12;if(hours>12){hours12=hours-12}else if(hours==0){hours12=12}else{hours12=hours}for(var i=0;i<fmt.length;++i){var c=fmt.charAt(i);if(escape){switch(c){case"a":c=""+dayNames[d.getDay()];break;case"b":c=""+monthNames[d.getMonth()];break;case"d":c=leftPad(d.getDate());break;case"e":c=leftPad(d.getDate()," ");break;case"h":case"H":c=leftPad(hours);break;case"I":c=leftPad(hours12);break;case"l":c=leftPad(hours12," ");break;case"m":c=leftPad(d.getMonth()+1);break;case"M":c=leftPad(d.getMinutes());break;case"q":c=""+(Math.floor(d.getMonth()/3)+1);break;case"S":c=leftPad(d.getSeconds());break;case"y":c=leftPad(d.getFullYear()%100);break;case"Y":c=""+d.getFullYear();break;case"p":c=isAM?""+"am":""+"pm";break;case"P":c=isAM?""+"AM":""+"PM";break;case"w":c=""+d.getDay();break}r.push(c);escape=false}else{if(c=="%"){escape=true}else{r.push(c)}}}return r.join("")}function makeUtcWrapper(d){function addProxyMethod(sourceObj,sourceMethod,targetObj,targetMethod){sourceObj[sourceMethod]=function(){return targetObj[targetMethod].apply(targetObj,arguments)}}var utc={date:d};if(d.strftime!=undefined){addProxyMethod(utc,"strftime",d,"strftime")}addProxyMethod(utc,"getTime",d,"getTime");addProxyMethod(utc,"setTime",d,"setTime");var props=["Date","Day","FullYear","Hours","Milliseconds","Minutes","Month","Seconds"];for(var p=0;p<props.length;p++){addProxyMethod(utc,"get"+props[p],d,"getUTC"+props[p]);addProxyMethod(utc,"set"+props[p],d,"setUTC"+props[p])}return utc}function dateGenerator(ts,opts){if(opts.timezone=="browser"){return new Date(ts)}else if(!opts.timezone||opts.timezone=="utc"){return makeUtcWrapper(new Date(ts))}else if(typeof timezoneJS!="undefined"&&typeof timezoneJS.Date!="undefined"){var d=new timezoneJS.Date;d.setTimezone(opts.timezone);d.setTime(ts);return d}else{return makeUtcWrapper(new Date(ts))}}var timeUnitSize={second:1e3,minute:60*1e3,hour:60*60*1e3,day:24*60*60*1e3,month:30*24*60*60*1e3,quarter:3*30*24*60*60*1e3,year:365.2425*24*60*60*1e3};var baseSpec=[[1,"second"],[2,"second"],[5,"second"],[10,"second"],[30,"second"],[1,"minute"],[2,"minute"],[5,"minute"],[10,"minute"],[30,"minute"],[1,"hour"],[2,"hour"],[4,"hour"],[8,"hour"],[12,"hour"],[1,"day"],[2,"day"],[3,"day"],[.25,"month"],[.5,"month"],[1,"month"],[2,"month"]];var specMonths=baseSpec.concat([[3,"month"],[6,"month"],[1,"year"]]);var specQuarters=baseSpec.concat([[1,"quarter"],[2,"quarter"],[1,"year"]]);function init(plot){plot.hooks.processOptions.push(function(plot,options){$.each(plot.getAxes(),function(axisName,axis){var opts=axis.options;if(opts.mode=="time"){axis.tickGenerator=function(axis){var ticks=[];var d=dateGenerator(axis.min,opts);var minSize=0;var spec=opts.tickSize&&opts.tickSize[1]==="quarter"||opts.minTickSize&&opts.minTickSize[1]==="quarter"?specQuarters:specMonths;if(opts.minTickSize!=null){if(typeof opts.tickSize=="number"){minSize=opts.tickSize}else{minSize=opts.minTickSize[0]*timeUnitSize[opts.minTickSize[1]]}}for(var i=0;i<spec.length-1;++i){if(axis.delta<(spec[i][0]*timeUnitSize[spec[i][1]]+spec[i+1][0]*timeUnitSize[spec[i+1][1]])/2&&spec[i][0]*timeUnitSize[spec[i][1]]>=minSize){break}}var size=spec[i][0];var unit=spec[i][1];if(unit=="year"){if(opts.minTickSize!=null&&opts.minTickSize[1]=="year"){size=Math.floor(opts.minTickSize[0])}else{var magn=Math.pow(10,Math.floor(Math.log(axis.delta/timeUnitSize.year)/Math.LN10));var norm=axis.delta/timeUnitSize.year/magn;if(norm<1.5){size=1}else if(norm<3){size=2}else if(norm<7.5){size=5}else{size=10}size*=magn}if(size<1){size=1}}axis.tickSize=opts.tickSize||[size,unit];var tickSize=axis.tickSize[0];unit=axis.tickSize[1];var step=tickSize*timeUnitSize[unit];if(unit=="second"){d.setSeconds(floorInBase(d.getSeconds(),tickSize))}else if(unit=="minute"){d.setMinutes(floorInBase(d.getMinutes(),tickSize))}else if(unit=="hour"){d.setHours(floorInBase(d.getHours(),tickSize))}else if(unit=="month"){d.setMonth(floorInBase(d.getMonth(),tickSize))}else if(unit=="quarter"){d.setMonth(3*floorInBase(d.getMonth()/3,tickSize))}else if(unit=="year"){d.setFullYear(floorInBase(d.getFullYear(),tickSize))}d.setMilliseconds(0);if(step>=timeUnitSize.minute){d.setSeconds(0)}if(step>=timeUnitSize.hour){d.setMinutes(0)}if(step>=timeUnitSize.day){d.setHours(0)}if(step>=timeUnitSize.day*4){d.setDate(1)}if(step>=timeUnitSize.month*2){d.setMonth(floorInBase(d.getMonth(),3))}if(step>=timeUnitSize.quarter*2){d.setMonth(floorInBase(d.getMonth(),6))}if(step>=timeUnitSize.year){d.setMonth(0)}var carry=0;var v=Number.NaN;var prev;do{prev=v;v=d.getTime();ticks.push(v);if(unit=="month"||unit=="quarter"){if(tickSize<1){d.setDate(1);var start=d.getTime();d.setMonth(d.getMonth()+(unit=="quarter"?3:1));var end=d.getTime();d.setTime(v+carry*timeUnitSize.hour+(end-start)*tickSize);carry=d.getHours();d.setHours(0)}else{d.setMonth(d.getMonth()+tickSize*(unit=="quarter"?3:1))}}else if(unit=="year"){d.setFullYear(d.getFullYear()+tickSize)}else{d.setTime(v+step)}}while(v<axis.max&&v!=prev);return ticks};axis.tickFormatter=function(v,axis){var d=dateGenerator(v,axis.options);if(opts.timeformat!=null){return formatDate(d,opts.timeformat,opts.monthNames,opts.dayNames)}var useQuarters=axis.options.tickSize&&axis.options.tickSize[1]=="quarter"||axis.options.minTickSize&&axis.options.minTickSize[1]=="quarter";var t=axis.tickSize[0]*timeUnitSize[axis.tickSize[1]];var span=axis.max-axis.min;var suffix=opts.twelveHourClock?" %p":"";var hourCode=opts.twelveHourClock?"%I":"%H";var fmt;if(t<timeUnitSize.minute){fmt=hourCode+":%M:%S"+suffix}else if(t<timeUnitSize.day){if(span<2*timeUnitSize.day){fmt=hourCode+":%M"+suffix}else{fmt="%b %d "+hourCode+":%M"+suffix}}else if(t<timeUnitSize.month){fmt="%b %d"}else if(useQuarters&&t<timeUnitSize.quarter||!useQuarters&&t<timeUnitSize.year){if(span<timeUnitSize.year){fmt="%b"}else{fmt="%b %Y"}}else if(useQuarters&&t<timeUnitSize.year){if(span<timeUnitSize.year){fmt="Q%q"}else{fmt="Q%q %Y"}}else{fmt="%Y"}var rt=formatDate(d,fmt,opts.monthNames,opts.dayNames);return rt}}})})}$.plot.plugins.push({init:init,options:options,name:"time",version:"1.0"});$.plot.formatDate=formatDate;$.plot.dateGenerator=dateGenerator})(jQuery);

/*
 * jquery.flot.tooltip
 * 
 * description: easy-to-use tooltips for Flot charts
 * version: 0.8.5
 * authors: Krzysztof Urbas @krzysu [myviews.pl],Evan Steinkerchner @Roundaround
 * website: https://github.com/krzysu/flot.tooltip
 * 
 * build on 2015-06-12
 * released under MIT License, 2012
*/ 
!function(a){var b={tooltip:{show:!1,cssClass:"flotTip",content:"%s | X: %x | Y: %y",xDateFormat:null,yDateFormat:null,monthNames:null,dayNames:null,shifts:{x:10,y:20},defaultTheme:!0,snap:!0,lines:!1,onHover:function(a,b){},$compat:!1}};b.tooltipOpts=b.tooltip;var c=function(a){this.tipPosition={x:0,y:0},this.init(a)};c.prototype.init=function(b){function c(a){var c={};c.x=a.pageX,c.y=a.pageY,b.setTooltipPosition(c)}function d(c,d,f){var g=function(a,b,c,d){return Math.sqrt((c-a)*(c-a)+(d-b)*(d-b))},h=function(a,b,c,d,e,f,h){if(!h||(h=function(a,b,c,d,e,f){if("undefined"!=typeof c)return{x:c,y:b};if("undefined"!=typeof d)return{x:a,y:d};var g,h=-1/((f-d)/(e-c));return{x:g=(e*(a*h-b+d)+c*(a*-h+b-f))/(h*(e-c)+d-f),y:h*g-h*a+b}}(a,b,c,d,e,f),h.x>=Math.min(c,e)&&h.x<=Math.max(c,e)&&h.y>=Math.min(d,f)&&h.y<=Math.max(d,f))){var i=d-f,j=e-c,k=c*f-d*e;return Math.abs(i*a+j*b+k)/Math.sqrt(i*i+j*j)}var l=g(a,b,c,d),m=g(a,b,e,f);return l>m?m:l};if(f)b.showTooltip(f,e.tooltipOptions.snap?f:d);else if(e.plotOptions.series.lines.show&&e.tooltipOptions.lines===!0){var i=e.plotOptions.grid.mouseActiveRadius,j={distance:i+1},k=d;a.each(b.getData(),function(a,c){for(var f=0,i=-1,l=1;l<c.data.length;l++)c.data[l-1][0]<=d.x&&c.data[l][0]>=d.x&&(f=l-1,i=l);if(-1===i)return void b.hideTooltip();var m={x:c.data[f][0],y:c.data[f][1]},n={x:c.data[i][0],y:c.data[i][1]},o=h(c.xaxis.p2c(d.x),c.yaxis.p2c(d.y),c.xaxis.p2c(m.x),c.yaxis.p2c(m.y),c.xaxis.p2c(n.x),c.yaxis.p2c(n.y),!1);if(o<j.distance){var p=g(m.x,m.y,d.x,d.y)<g(d.x,d.y,n.x,n.y)?f:i,q=(c.datapoints.pointsize,[d.x,m.y+(n.y-m.y)*((d.x-m.x)/(n.x-m.x))]),r={datapoint:q,dataIndex:p,series:c,seriesIndex:a};j={distance:o,item:r},e.tooltipOptions.snap&&(k={pageX:c.xaxis.p2c(q[0]),pageY:c.yaxis.p2c(q[1])})}}),j.distance<i+1?b.showTooltip(j.item,k):b.hideTooltip()}else b.hideTooltip()}var e=this,f=a.plot.plugins.length;if(this.plotPlugins=[],f)for(var g=0;f>g;g++)this.plotPlugins.push(a.plot.plugins[g].name);b.hooks.bindEvents.push(function(b,f){if(e.plotOptions=b.getOptions(),"boolean"==typeof e.plotOptions.tooltip&&(e.plotOptions.tooltipOpts.show=e.plotOptions.tooltip,e.plotOptions.tooltip=e.plotOptions.tooltipOpts,delete e.plotOptions.tooltipOpts),e.plotOptions.tooltip.show!==!1&&"undefined"!=typeof e.plotOptions.tooltip.show){e.tooltipOptions=e.plotOptions.tooltip,e.tooltipOptions.$compat?(e.wfunc="width",e.hfunc="height"):(e.wfunc="innerWidth",e.hfunc="innerHeight");e.getDomElement();a(b.getPlaceholder()).bind("plothover",d),a(f).bind("mousemove",c)}}),b.hooks.shutdown.push(function(b,e){a(b.getPlaceholder()).unbind("plothover",d),a(e).unbind("mousemove",c)}),b.setTooltipPosition=function(b){var c=e.getDomElement(),d=c.outerWidth()+e.tooltipOptions.shifts.x,f=c.outerHeight()+e.tooltipOptions.shifts.y;b.x-a(window).scrollLeft()>a(window)[e.wfunc]()-d&&(b.x-=d),b.y-a(window).scrollTop()>a(window)[e.hfunc]()-f&&(b.y-=f),isNaN(b.x)?e.tipPosition.x=e.tipPosition.xPrev:(e.tipPosition.x=b.x,e.tipPosition.xPrev=b.x),isNaN(b.y)?e.tipPosition.y=e.tipPosition.yPrev:(e.tipPosition.y=b.y,e.tipPosition.yPrev=b.y)},b.showTooltip=function(a,c,d){var f=e.getDomElement(),g=e.stringFormat(e.tooltipOptions.content,a);""!==g&&(f.html(g),b.setTooltipPosition({x:c.pageX,y:c.pageY}),f.css({left:e.tipPosition.x+e.tooltipOptions.shifts.x,top:e.tipPosition.y+e.tooltipOptions.shifts.y}).show(),"function"==typeof e.tooltipOptions.onHover&&e.tooltipOptions.onHover(a,f))},b.hideTooltip=function(){e.getDomElement().hide().html("")}},c.prototype.getDomElement=function(){var b=a("."+this.tooltipOptions.cssClass);return 0===b.length&&(b=a("<div />").addClass(this.tooltipOptions.cssClass),b.appendTo("body").hide().css({position:"absolute"}),this.tooltipOptions.defaultTheme&&b.css({background:"#fff","z-index":"1040",padding:"0.4em 0.6em","border-radius":"0.5em","font-size":"0.8em",border:"1px solid #111",display:"none","white-space":"nowrap"})),b},c.prototype.stringFormat=function(a,b){var c,d,e,f,g,h=/%p\.{0,1}(\d{0,})/,i=/%s/,j=/%c/,k=/%lx/,l=/%ly/,m=/%x\.{0,1}(\d{0,})/,n=/%y\.{0,1}(\d{0,})/,o="%x",p="%y",q="%ct",r="%n";if("undefined"!=typeof b.series.threshold?(c=b.datapoint[0],d=b.datapoint[1],e=b.datapoint[2]):"undefined"!=typeof b.series.curvedLines?(c=b.datapoint[0],d=b.datapoint[1]):"undefined"!=typeof b.series.lines&&b.series.lines.steps?(c=b.series.datapoints.points[2*b.dataIndex],d=b.series.datapoints.points[2*b.dataIndex+1],e=""):(c=b.series.data[b.dataIndex][0],d=b.series.data[b.dataIndex][1],e=b.series.data[b.dataIndex][2]),null===b.series.label&&b.series.originSeries&&(b.series.label=b.series.originSeries.label),"function"==typeof a&&(a=a(b.series.label,c,d,b)),"boolean"==typeof a&&!a)return"";if(e&&(a=a.replace(q,e)),"undefined"!=typeof b.series.percent?f=b.series.percent:"undefined"!=typeof b.series.percents&&(f=b.series.percents[b.dataIndex]),"number"==typeof f&&(a=this.adjustValPrecision(h,a,f)),b.series.hasOwnProperty("pie")&&(g=b.series.data[0][1]),"number"==typeof g&&(a=a.replace(r,g)),a="undefined"!=typeof b.series.label?a.replace(i,b.series.label):a.replace(i,""),a="undefined"!=typeof b.series.color?a.replace(j,b.series.color):a.replace(j,""),a=this.hasAxisLabel("xaxis",b)?a.replace(k,b.series.xaxis.options.axisLabel):a.replace(k,""),a=this.hasAxisLabel("yaxis",b)?a.replace(l,b.series.yaxis.options.axisLabel):a.replace(l,""),this.isTimeMode("xaxis",b)&&this.isXDateFormat(b)&&(a=a.replace(m,this.timestampToDate(c,this.tooltipOptions.xDateFormat,b.series.xaxis.options))),this.isTimeMode("yaxis",b)&&this.isYDateFormat(b)&&(a=a.replace(n,this.timestampToDate(d,this.tooltipOptions.yDateFormat,b.series.yaxis.options))),"number"==typeof c&&(a=this.adjustValPrecision(m,a,c)),"number"==typeof d&&(a=this.adjustValPrecision(n,a,d)),"undefined"!=typeof b.series.xaxis.ticks){var s;s=this.hasRotatedXAxisTicks(b)?"rotatedTicks":"ticks";var t=b.dataIndex+b.seriesIndex;for(var u in b.series.xaxis[s])if(b.series.xaxis[s].hasOwnProperty(t)&&!this.isTimeMode("xaxis",b)){var v=this.isCategoriesMode("xaxis",b)?b.series.xaxis[s][t].label:b.series.xaxis[s][t].v;v===c&&(a=a.replace(m,b.series.xaxis[s][t].label))}}if("undefined"!=typeof b.series.yaxis.ticks)for(var w in b.series.yaxis.ticks)if(b.series.yaxis.ticks.hasOwnProperty(w)){var x=this.isCategoriesMode("yaxis",b)?b.series.yaxis.ticks[w].label:b.series.yaxis.ticks[w].v;x===d&&(a=a.replace(n,b.series.yaxis.ticks[w].label))}return"undefined"!=typeof b.series.xaxis.tickFormatter&&(a=a.replace(o,b.series.xaxis.tickFormatter(c,b.series.xaxis).replace(/\$/g,"$$"))),"undefined"!=typeof b.series.yaxis.tickFormatter&&(a=a.replace(p,b.series.yaxis.tickFormatter(d,b.series.yaxis).replace(/\$/g,"$$"))),a},c.prototype.isTimeMode=function(a,b){return"undefined"!=typeof b.series[a].options.mode&&"time"===b.series[a].options.mode},c.prototype.isXDateFormat=function(a){return"undefined"!=typeof this.tooltipOptions.xDateFormat&&null!==this.tooltipOptions.xDateFormat},c.prototype.isYDateFormat=function(a){return"undefined"!=typeof this.tooltipOptions.yDateFormat&&null!==this.tooltipOptions.yDateFormat},c.prototype.isCategoriesMode=function(a,b){return"undefined"!=typeof b.series[a].options.mode&&"categories"===b.series[a].options.mode},c.prototype.timestampToDate=function(b,c,d){var e=a.plot.dateGenerator(b,d);return a.plot.formatDate(e,c,this.tooltipOptions.monthNames,this.tooltipOptions.dayNames)},c.prototype.adjustValPrecision=function(a,b,c){var d,e=b.match(a);return null!==e&&""!==RegExp.$1&&(d=RegExp.$1,c=c.toFixed(d),b=b.replace(a,c)),b},c.prototype.hasAxisLabel=function(b,c){return-1!==a.inArray(this.plotPlugins,"axisLabels")&&"undefined"!=typeof c.series[b].options.axisLabel&&c.series[b].options.axisLabel.length>0},c.prototype.hasRotatedXAxisTicks=function(b){return-1!==a.inArray(this.plotPlugins,"tickRotor")&&"undefined"!=typeof b.series.xaxis.rotatedTicks};var d=function(a){new c(a)};a.plot.plugins.push({init:d,options:b,name:"tooltip",version:"0.8.5"})}(jQuery);

/* Flot plugin for stacking data sets rather than overlyaing them.

Copyright (c) 2007-2013 IOLA and Ole Laursen.
Licensed under the MIT license.

The plugin assumes the data is sorted on x (or y if stacking horizontally).
For line charts, it is assumed that if a line has an undefined gap (from a
null point), then the line above it should have the same gap - insert zeros
instead of "null" if you want another behaviour. This also holds for the start
and end of the chart. Note that stacking a mix of positive and negative values
in most instances doesn't make sense (so it looks weird).

Two or more series are stacked when their "stack" attribute is set to the same
key (which can be any number or string or just "true"). To specify the default
stack, you can set the stack option like this:

	series: {
		stack: null/false, true, or a key (number/string)
	}

You can also specify it for a single series, like this:

	$.plot( $("#placeholder"), [{
		data: [ ... ],
		stack: true
	}])

The stacking order is determined by the order of the data series in the array
(later series end up on top of the previous).

Internally, the plugin modifies the datapoints in each series, adding an
offset to the y value. For line series, extra data points are inserted through
interpolation. If there's a second y value, it's also adjusted (e.g for bar
charts or filled areas).

*/(function(e){function n(e){function t(e,t){var n=null;for(var r=0;r<t.length;++r){if(e==t[r])break;t[r].stack==e.stack&&(n=t[r])}return n}function n(e,n,r){if(n.stack==null||n.stack===!1)return;var i=t(n,e.getData());if(!i)return;var s=r.pointsize,o=r.points,u=i.datapoints.pointsize,a=i.datapoints.points,f=[],l,c,h,p,d,v,m=n.lines.show,g=n.bars.horizontal,y=s>2&&(g?r.format[2].x:r.format[2].y),b=m&&n.lines.steps,w=!0,E=g?1:0,S=g?0:1,x=0,T=0,N,C;for(;;){if(x>=o.length)break;N=f.length;if(o[x]==null){for(C=0;C<s;++C)f.push(o[x+C]);x+=s}else if(T>=a.length){if(!m)for(C=0;C<s;++C)f.push(o[x+C]);x+=s}else if(a[T]==null){for(C=0;C<s;++C)f.push(null);w=!0,T+=u}else{l=o[x+E],c=o[x+S],p=a[T+E],d=a[T+S],v=0;if(l==p){for(C=0;C<s;++C)f.push(o[x+C]);f[N+S]+=d,v=d,x+=s,T+=u}else if(l>p){if(m&&x>0&&o[x-s]!=null){h=c+(o[x-s+S]-c)*(p-l)/(o[x-s+E]-l),f.push(p),f.push(h+d);for(C=2;C<s;++C)f.push(o[x+C]);v=d}T+=u}else{if(w&&m){x+=s;continue}for(C=0;C<s;++C)f.push(o[x+C]);m&&T>0&&a[T-u]!=null&&(v=d+(a[T-u+S]-d)*(l-p)/(a[T-u+E]-p)),f[N+S]+=v,x+=s}w=!1,N!=f.length&&y&&(f[N+2]+=v)}if(b&&N!=f.length&&N>0&&f[N]!=null&&f[N]!=f[N-s]&&f[N+1]!=f[N-s+1]){for(C=0;C<s;++C)f[N+s+C]=f[N+C];f[N+1]=f[N-s+1]}}r.points=f}e.hooks.processDatapoints.push(n)}var t={series:{stack:null}};e.plot.plugins.push({init:n,options:t,name:"stack",version:"1.2"})})(jQuery);

/* Javascript plotting library for jQuery, version 0.8.3.

Copyright (c) 2007-2014 IOLA and Ole Laursen.
Licensed under the MIT license.

*/
(function($){function processRawData(plot,series,datapoints){var handlers={square:function(ctx,x,y,radius,shadow){var size=radius*Math.sqrt(Math.PI)/2;ctx.rect(x-size,y-size,size+size,size+size)},diamond:function(ctx,x,y,radius,shadow){var size=radius*Math.sqrt(Math.PI/2);ctx.moveTo(x-size,y);ctx.lineTo(x,y-size);ctx.lineTo(x+size,y);ctx.lineTo(x,y+size);ctx.lineTo(x-size,y)},triangle:function(ctx,x,y,radius,shadow){var size=radius*Math.sqrt(2*Math.PI/Math.sin(Math.PI/3));var height=size*Math.sin(Math.PI/3);ctx.moveTo(x-size/2,y+height/2);ctx.lineTo(x+size/2,y+height/2);if(!shadow){ctx.lineTo(x,y-height/2);ctx.lineTo(x-size/2,y+height/2)}},cross:function(ctx,x,y,radius,shadow){var size=radius*Math.sqrt(Math.PI)/2;ctx.moveTo(x-size,y-size);ctx.lineTo(x+size,y+size);ctx.moveTo(x-size,y+size);ctx.lineTo(x+size,y-size)}};var s=series.points.symbol;if(handlers[s])series.points.symbol=handlers[s]}function init(plot){plot.hooks.processDatapoints.push(processRawData)}$.plot.plugins.push({init:init,name:"symbols",version:"1.0"})})(jQuery);

/*
Axis Labels Plugin for flot.
http://github.com/markrcote/flot-axislabels

Original code is Copyright (c) 2010 Xuan Luo.
Original code was released under the GPLv3 license by Xuan Luo, September 2010.
Original code was rereleased under the MIT license by Xuan Luo, April 2012.

Improvements by Mark Cote.

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/
(function ($) {
    var options = {};

    function canvasSupported() {
        return !!document.createElement('canvas').getContext;
    }

    function canvasTextSupported() {
        if (!canvasSupported()) {
            return false;
        }
        var dummy_canvas = document.createElement('canvas');
        var context = dummy_canvas.getContext('2d');
        return typeof context.fillText == 'function';
    }

    function css3TransitionSupported() {
        var div = document.createElement('div');
        return typeof div.style.MozTransition != 'undefined'    // Gecko
            || typeof div.style.OTransition != 'undefined'      // Opera
            || typeof div.style.webkitTransition != 'undefined' // WebKit
            || typeof div.style.transition != 'undefined';
    }


    function AxisLabel(axisName, position, padding, plot, opts) {
        this.axisName = axisName;
        this.position = position;
        this.padding = padding;
        this.plot = plot;
        this.opts = opts;
        this.width = 0;
        this.height = 0;
    }


    CanvasAxisLabel.prototype = new AxisLabel();
    CanvasAxisLabel.prototype.constructor = CanvasAxisLabel;
    function CanvasAxisLabel(axisName, position, padding, plot, opts) {
        AxisLabel.prototype.constructor.call(this, axisName, position, padding,
                                             plot, opts);
    }

    CanvasAxisLabel.prototype.calculateSize = function () {
        if (!this.opts.axisLabelFontSizePixels)
            this.opts.axisLabelFontSizePixels = 14;
        if (!this.opts.axisLabelFontFamily)
            this.opts.axisLabelFontFamily = 'sans-serif';

        var textWidth = this.opts.axisLabelFontSizePixels + this.padding;
        var textHeight = this.opts.axisLabelFontSizePixels + this.padding;
        if (this.position == 'left' || this.position == 'right') {
            this.width = this.opts.axisLabelFontSizePixels + this.padding;
            this.height = 0;
        } else {
            this.width = 0;
            this.height = this.opts.axisLabelFontSizePixels + this.padding;
        }
    };

    CanvasAxisLabel.prototype.draw = function (box) {
        var ctx = this.plot.getCanvas().getContext('2d');
        ctx.save();
        ctx.font = this.opts.axisLabelFontSizePixels + 'px ' +
            this.opts.axisLabelFontFamily;
        var width = ctx.measureText(this.opts.axisLabel).width;
        var height = this.opts.axisLabelFontSizePixels;
        var x, y, angle = 0;
        if (this.position == 'top') {
            x = box.left + box.width / 2 - width / 2;
            y = box.top + height * 0.72;
        } else if (this.position == 'bottom') {
            x = box.left + box.width / 2 - width / 2;
            y = box.top + box.height - height * 0.72;
        } else if (this.position == 'left') {
            x = box.left + height * 0.72;
            y = box.height / 2 + box.top + width / 2;
            angle = -Math.PI / 2;
        } else if (this.position == 'right') {
            x = box.left + box.width - height * 0.72;
            y = box.height / 2 + box.top - width / 2;
            angle = Math.PI / 2;
        }
        ctx.translate(x, y);
        ctx.rotate(angle);
        ctx.fillText(this.opts.axisLabel, 0, 0);
        ctx.restore();
    };


    HtmlAxisLabel.prototype = new AxisLabel();
    HtmlAxisLabel.prototype.constructor = HtmlAxisLabel;
    function HtmlAxisLabel(axisName, position, padding, plot, opts) {
        AxisLabel.prototype.constructor.call(this, axisName, position,
                                             padding, plot, opts);
    }

    HtmlAxisLabel.prototype.calculateSize = function () {
        var elem = $('<div class="axisLabels" style="position:absolute;">' +
                     this.opts.axisLabel + '</div>');
        this.plot.getPlaceholder().append(elem);
        // store height and width of label itself, for use in draw()
        this.labelWidth = elem.outerWidth(true);
        this.labelHeight = elem.outerHeight(true);
        elem.remove();

        this.width = this.height = 0;
        if (this.position == 'left' || this.position == 'right') {
            this.width = this.labelWidth + this.padding;
        } else {
            this.height = this.labelHeight + this.padding;
        }
    };

    HtmlAxisLabel.prototype.draw = function (box) {
        this.plot.getPlaceholder().find('#' + this.axisName + 'Label').remove();
        var elem = $('<div id="' + this.axisName +
                     'Label" " class="axisLabels" style="position:absolute;">'
                     + this.opts.axisLabel + '</div>');
        this.plot.getPlaceholder().append(elem);
        if (this.position == 'top') {
            elem.css('left', box.left + box.width / 2 - this.labelWidth / 2 + 'px');
            elem.css('top', box.top + 'px');
        } else if (this.position == 'bottom') {
            elem.css('left', box.left + box.width / 2 - this.labelWidth / 2 + 'px');
            elem.css('top', box.top + box.height - this.labelHeight + 'px');
        } else if (this.position == 'left') {
            elem.css('top', box.top + box.height / 2 - this.labelHeight / 2 + 'px');
            elem.css('left', box.left + 'px');
        } else if (this.position == 'right') {
            elem.css('top', box.top + box.height / 2 - this.labelHeight / 2 + 'px');
            elem.css('left', box.left + box.width - this.labelWidth + 'px');
        }
    };


    CssTransformAxisLabel.prototype = new HtmlAxisLabel();
    CssTransformAxisLabel.prototype.constructor = CssTransformAxisLabel;
    function CssTransformAxisLabel(axisName, position, padding, plot, opts) {
        HtmlAxisLabel.prototype.constructor.call(this, axisName, position,
                                                 padding, plot, opts);
    }

    CssTransformAxisLabel.prototype.calculateSize = function () {
        HtmlAxisLabel.prototype.calculateSize.call(this);
        this.width = this.height = 0;
        if (this.position == 'left' || this.position == 'right') {
            this.width = this.labelHeight + this.padding;
        } else {
            this.height = this.labelHeight + this.padding;
        }
    };

    CssTransformAxisLabel.prototype.transforms = function (degrees, x, y) {
        var stransforms = {
            '-moz-transform': '',
            '-webkit-transform': '',
            '-o-transform': '',
            '-ms-transform': ''
        };
        if (x != 0 || y != 0) {
            var stdTranslate = ' translate(' + x + 'px, ' + y + 'px)';
            stransforms['-moz-transform'] += stdTranslate;
            stransforms['-webkit-transform'] += stdTranslate;
            stransforms['-o-transform'] += stdTranslate;
            stransforms['-ms-transform'] += stdTranslate;
        }
        if (degrees != 0) {
            var rotation = degrees / 90;
            var stdRotate = ' rotate(' + degrees + 'deg)';
            stransforms['-moz-transform'] += stdRotate;
            stransforms['-webkit-transform'] += stdRotate;
            stransforms['-o-transform'] += stdRotate;
            stransforms['-ms-transform'] += stdRotate;
        }
        var s = 'top: 0; left: 0; ';
        for (var prop in stransforms) {
            if (stransforms[prop]) {
                s += prop + ':' + stransforms[prop] + ';';
            }
        }
        s += ';';
        return s;
    };

    CssTransformAxisLabel.prototype.calculateOffsets = function (box) {
        var offsets = { x: 0, y: 0, degrees: 0 };
        if (this.position == 'bottom') {
            offsets.x = box.left + box.width / 2 - this.labelWidth / 2;
            offsets.y = box.top + box.height - this.labelHeight;
        } else if (this.position == 'top') {
            offsets.x = box.left + box.width / 2 - this.labelWidth / 2;
            offsets.y = box.top;
        } else if (this.position == 'left') {
            offsets.degrees = -90;
            offsets.x = box.left - this.labelWidth / 2 + this.labelHeight / 2;
            offsets.y = box.height / 2 + box.top;
        } else if (this.position == 'right') {
            offsets.degrees = 90;
            offsets.x = box.left + box.width - this.labelWidth / 2
                        - this.labelHeight / 2;
            offsets.y = box.height / 2 + box.top;
        }
        return offsets;
    };

    CssTransformAxisLabel.prototype.draw = function (box) {
        this.plot.getPlaceholder().find("." + this.axisName + "Label").remove();
        var offsets = this.calculateOffsets(box);
        var elem = $('<div class="axisLabels ' + this.axisName +
                     'Label" style="position:absolute; ' +
                     this.transforms(offsets.degrees, offsets.x, offsets.y) +
                     '">' + this.opts.axisLabel + '</div>');
        this.plot.getPlaceholder().append(elem);
    };


    IeTransformAxisLabel.prototype = new CssTransformAxisLabel();
    IeTransformAxisLabel.prototype.constructor = IeTransformAxisLabel;
    function IeTransformAxisLabel(axisName, position, padding, plot, opts) {
        CssTransformAxisLabel.prototype.constructor.call(this, axisName,
                                                         position, padding,
                                                         plot, opts);
        this.requiresResize = false;
    }

    IeTransformAxisLabel.prototype.transforms = function (degrees, x, y) {
        // I didn't feel like learning the crazy Matrix stuff, so this uses
        // a combination of the rotation transform and CSS positioning.
        var s = '';
        if (degrees != 0) {
            var rotation = degrees / 90;
            while (rotation < 0) {
                rotation += 4;
            }
            s += ' filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=' + rotation + '); ';
            // see below
            this.requiresResize = (this.position == 'right');
        }
        if (x != 0) {
            s += 'left: ' + x + 'px; ';
        }
        if (y != 0) {
            s += 'top: ' + y + 'px; ';
        }
        return s;
    };

    IeTransformAxisLabel.prototype.calculateOffsets = function (box) {
        var offsets = CssTransformAxisLabel.prototype.calculateOffsets.call(
                          this, box);
        // adjust some values to take into account differences between
        // CSS and IE rotations.
        if (this.position == 'top') {
            // FIXME: not sure why, but placing this exactly at the top causes 
            // the top axis label to flip to the bottom...
            offsets.y = box.top + 1;
        } else if (this.position == 'left') {
            offsets.x = box.left;
            offsets.y = box.height / 2 + box.top - this.labelWidth / 2;
        } else if (this.position == 'right') {
            offsets.x = box.left + box.width - this.labelHeight;
            offsets.y = box.height / 2 + box.top - this.labelWidth / 2;
        }
        return offsets;
    };

    IeTransformAxisLabel.prototype.draw = function (box) {
        CssTransformAxisLabel.prototype.draw.call(this, box);
        if (this.requiresResize) {
            var elem = this.plot.getPlaceholder().find("." + this.axisName + "Label");
            // Since we used CSS positioning instead of transforms for
            // translating the element, and since the positioning is done
            // before any rotations, we have to reset the width and height
            // in case the browser wrapped the text (specifically for the
            // y2axis).
            elem.css('width', this.labelWidth);
            elem.css('height', this.labelHeight);
        }
    };


    function init(plot) {
        // This is kind of a hack. There are no hooks in Flot between
        // the creation and measuring of the ticks (setTicks, measureTickLabels
        // in setupGrid() ) and the drawing of the ticks and plot box
        // (insertAxisLabels in setupGrid() ).
        //
        // Therefore, we use a trick where we run the draw routine twice:
        // the first time to get the tick measurements, so that we can change
        // them, and then have it draw it again.
        var secondPass = false;

        var axisLabels = {};
        var axisOffsetCounts = { left: 0, right: 0, top: 0, bottom: 0 };

        var defaultPadding = 2;  // padding between axis and tick labels
        plot.hooks.draw.push(function (plot, ctx) {
            if (!secondPass) {
                // MEASURE AND SET OPTIONS
                $.each(plot.getAxes(), function (axisName, axis) {
                    var opts = axis.options // Flot 0.7
                        || plot.getOptions()[axisName]; // Flot 0.6
                    if (!opts || !opts.axisLabel)
                        return;

                    var renderer = null;

                    if (!opts.axisLabelUseHtml &&
                        navigator.appName == 'Microsoft Internet Explorer') {
                        var ua = navigator.userAgent;
                        var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
                        if (re.exec(ua) != null) {
                            rv = parseFloat(RegExp.$1);
                        }
                        if (rv >= 9 && !opts.axisLabelUseCanvas && !opts.axisLabelUseHtml) {
                            renderer = CssTransformAxisLabel;
                        } else if (!opts.axisLabelUseCanvas && !opts.axisLabelUseHtml) {
                            renderer = IeTransformAxisLabel;
                        } else if (opts.axisLabelUseCanvas) {
                            renderer = CanvasAxisLabel;
                        } else {
                            renderer = HtmlAxisLabel;
                        }
                    } else {
                        if (opts.axisLabelUseHtml || (!css3TransitionSupported() && !canvasTextSupported()) && !opts.axisLabelUseCanvas) {
                            renderer = HtmlAxisLabel;
                        } else if (opts.axisLabelUseCanvas || !css3TransitionSupported()) {
                            renderer = CanvasAxisLabel;
                        } else {
                            renderer = CssTransformAxisLabel;
                        }
                    }

                    var padding = opts.axisLabelPadding === undefined ?
                                  defaultPadding : opts.axisLabelPadding;

                    axisLabels[axisName] = new renderer(axisName,
                                                        axis.position, padding,
                                                        plot, opts);

                    // flot interprets axis.labelHeight and .labelWidth as
                    // the height and width of the tick labels. We increase
                    // these values to make room for the axis label and
                    // padding.

                    axisLabels[axisName].calculateSize();

                    // AxisLabel.height and .width are the size of the
                    // axis label and padding.
                    axis.labelHeight += axisLabels[axisName].height;
                    axis.labelWidth += axisLabels[axisName].width;
                    opts.labelHeight = axis.labelHeight;
                    opts.labelWidth = axis.labelWidth;
                });
                // re-draw with new label widths and heights
                secondPass = true;
                plot.setupGrid();
                plot.draw();
            } else {
                // DRAW
                $.each(plot.getAxes(), function (axisName, axis) {
                    var opts = axis.options // Flot 0.7
                        || plot.getOptions()[axisName]; // Flot 0.6
                    if (!opts || !opts.axisLabel)
                        return;

                    axisLabels[axisName].draw(axis.box);
                });
            }
        });
    }


    $.plot.plugins.push({
        init: init,
        options: options,
        name: 'axisLabels',
        version: '2.0b0'
    });
})(jQuery);

