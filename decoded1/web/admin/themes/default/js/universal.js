//////////////////////////////////////////////////////////////
// universal.js - Simple JS functions that make JS easy
// Inspired by Alons
// ----------------------------------------------------------
// Please Read the Terms of Use at https://webuzo.com/terms
// ----------------------------------------------------------
// (c) Softaculous Ltd.
//////////////////////////////////////////////////////////////

ua = navigator.userAgent.toLowerCase();
isIE = ((ua.indexOf("msie") != -1) && (ua.indexOf("opera") == -1) && (ua.indexOf("webtv") == -1));
isFF = (ua.indexOf("firefox") != -1);
isGecko = (ua.indexOf("gecko") != -1);
isSafari = (ua.indexOf("safari") != -1);
isKonqueror = (ua.indexOf("konqueror") != -1);

//Element referencer - We use $ because we love PHP
function $_(id){
	//DOM
	if(document.getElementById){
		return document.getElementById(id);
	//IE
	}else if(document.all){
		return document.all[id];
	//NS4
	}else if(document.layers){
		return document.layers[id];
	}
};

//Trims a string
function trim(str){
	return str.replace(/^[\s]+|[\s]+$/, "");
};

function in_array(val, array){
	if (typeof array == "undefined") {
		return false;
	}
	
	for (i=0; i <= array.length; i++){
		if (array[i] == val) {
			return true;
			// {alert(i +" -- "+ids[i]+" -- "+val);return i;}
		}
	}
	return false;
}

//Cookie setter
function setcookie(name, value, duration){
	value = escape(value);
	if(duration){
		var date = new Date();
		date.setTime(date.getTime() + (duration * 86400000));
		value += "; expires=" + date.toGMTString();
	}
	document.cookie = name + "=" + value;
};

//Gets the cookie value
function getcookie(name){
	value = document.cookie.match('(?:^|;)\\s*'+name+'=([^;]*)');
	return value ? unescape(value[1]) : false;
};

//Removes the cookies
function removecookie(name){
	setcookie(name, '', -1);
};

// PHP equivalent empty()
function empty(mixed_var) {

  var undef, key, i, len;
  var emptyValues = [undef, null, false, 0, '', '0'];

  for (i = 0, len = emptyValues.length; i < len; i++) {
    if (mixed_var === emptyValues[i]) {
      return true;
    }
  }

  if (typeof mixed_var === 'object') {
    for (key in mixed_var) {
      // TODO: should we check for own properties only?
      //if (mixed_var.hasOwnProperty(key)) {
      return false;
      //}
    }
    return true;
  }

  return false;
}

// Our special ajax function which also shows the loading text
function AJAX(url, success, failure){
	
	var obj = {};
	
	if(typeof url === 'object'){
		obj = url;
	}else{
		obj.url = url;
	}
	
	$(".loading").show();
	
	// On success
	obj.success = function(data, textStatus, jqXHR) {

		$(".loading").hide();
	
		// Is there a success function ?
		if(typeof success === 'function'){
			success(data, textStatus, jqXHR);
		}
		
		// For legacy functions
		if(typeof success === 'string' && data.length > 1){
			var re = data;
			eval(success);
		}
	}
	
	var myAjax = $.ajax(obj).fail(function (data, textStatus, jqXHR){
		
		$(".loading").hide();
		
		// Is there a failure function ?
		if(typeof failure === 'function'){
			failure(data, textStatus, jqXHR);
		}
		
	});
	
	return myAjax;
	
};
	
function randstr(length, strength) {
    var chars = "abcdefghijklmnopqrstuvwxyz!@#$%&ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    var pass = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }
	
	if(strength){
		var pass_strength = check_pass_score(pass);
		
		if(strength > pass_strength){
			while (pass_strength < strength) {
				$randnum = Math.floor(Math.random()*61);
				pass = pass + String.fromCharCode($randnum+55);
				pass_strength = check_pass_score(pass);
			}
		}
	}
  
    return pass;
};

function change_image(span_obj, elementid){
	var ip_obj = document.getElementById(elementid);
	var span_child = span_obj.getElementsByTagName('i')[0];
	if(ip_obj.type == "password") {
		ip_obj.type = "text";
		span_child.setAttribute('class','fas fa-eye-slash');
	}else{
		ip_obj.type = "password";
		span_child.setAttribute('class','fas fa-eye');
	}
};

/******************************************************/
/******** PUNYCODE CONVERSION CODE  *******************/
/******** FOR IDN (Internationalized Domain Name) *****/
/******************************************************/
/*! http://mths.be/punycode v1.2.0 by @mathias */
;(function(u){var I,e=typeof define=='function'&&typeof define.amd=='object'&&define.amd&&define,J=typeof exports=='object'&&exports,q=typeof module=='object'&&module,h=typeof require=='function'&&require,o=2147483647,p=36,i=1,H=26,B=38,b=700,m=72,G=128,C='-',E=/^xn--/,t=/[^ -~]/,l=/\x2E|\u3002|\uFF0E|\uFF61/g,s={overflow:'Overflow: input needs wider integers to process','not-basic':'Illegal input >= 0x80 (not a basic code point)','invalid-input':'Invalid input'},v=p-i,g=Math.floor,j=String.fromCharCode,n;function y(K){throw RangeError(s[K])}function z(M,K){var L=M.length;while(L--){M[L]=K(M[L])}return M}function f(K,L){return z(K.split(l),L).join('.')}function D(N){var M=[],L=0,O=N.length,P,K;while(L<O){P=N.charCodeAt(L++);if((P&63488)==55296&&L<O){K=N.charCodeAt(L++);if((K&64512)==56320){M.push(((P&1023)<<10)+(K&1023)+65536)}else{M.push(P,K)}}else{M.push(P)}}return M}function F(K){return z(K,function(M){var L='';if(M>65535){M-=65536;L+=j(M>>>10&1023|55296);M=56320|M&1023}L+=j(M);return L}).join('')}function c(K){return K-48<10?K-22:K-65<26?K-65:K-97<26?K-97:p}function A(L,K){return L+22+75*(L<26)-((K!=0)<<5)}function w(N,L,M){var K=0;N=M?g(N/b):N>>1;N+=g(N/L);for(;N>v*H>>1;K+=p){N=g(N/v)}return g(K+(v+1)*N/(N+B))}function k(L,K){L-=(L-97<26)<<5;return L+(!K&&L-65<26)<<5}function a(X){var N=[],Q=X.length,S,T=0,M=G,U=m,P,R,V,L,Y,O,W,aa,K,Z;P=X.lastIndexOf(C);if(P<0){P=0}for(R=0;R<P;++R){if(X.charCodeAt(R)>=128){y('not-basic')}N.push(X.charCodeAt(R))}for(V=P>0?P+1:0;V<Q;){for(L=T,Y=1,O=p;;O+=p){if(V>=Q){y('invalid-input')}W=c(X.charCodeAt(V++));if(W>=p||W>g((o-T)/Y)){y('overflow')}T+=W*Y;aa=O<=U?i:(O>=U+H?H:O-U);if(W<aa){break}Z=p-aa;if(Y>g(o/Z)){y('overflow')}Y*=Z}S=N.length+1;U=w(T-L,S,L==0);if(g(T/S)>o-M){y('overflow')}M+=g(T/S);T%=S;N.splice(T++,0,M)}return F(N)}function d(W){var N,Y,T,L,U,S,O,K,R,aa,X,M=[],Q,P,Z,V;W=D(W);Q=W.length;N=G;Y=0;U=m;for(S=0;S<Q;++S){X=W[S];if(X<128){M.push(j(X))}}T=L=M.length;if(L){M.push(C)}while(T<Q){for(O=o,S=0;S<Q;++S){X=W[S];if(X>=N&&X<O){O=X}}P=T+1;if(O-N>g((o-Y)/P)){y('overflow')}Y+=(O-N)*P;N=O;for(S=0;S<Q;++S){X=W[S];if(X<N&&++Y>o){y('overflow')}if(X==N){for(K=Y,R=p;;R+=p){aa=R<=U?i:(R>=U+H?H:R-U);if(K<aa){break}V=K-aa;Z=p-aa;M.push(j(A(aa+V%Z,0)));K=g(V/Z)}M.push(j(A(K,0)));U=w(Y,P,T==L);Y=0;++T}}++Y;++N}return M.join('')}function r(K){return f(K,function(L){return E.test(L)?a(L.slice(4).toLowerCase()):L})}function x(K){return f(K,function(L){return t.test(L)?'xn--'+d(L):L})}I={version:'1.2.0',ucs2:{decode:D,encode:F},decode:a,encode:d,toASCII:x,toUnicode:r};if(J){if(q&&q.exports==J){q.exports=I}else{for(n in I){I.hasOwnProperty(n)&&(J[n]=I[n])}}}else{if(e){define('punycode',I)}else{u.punycode=I}}}(this));

function check_punycode(text){
	
	// Is it an email ??
	if(text.indexOf("@") > 0){
		var tmp_array = text.split("@");
		var out = [];
		for (var i=0; i < tmp_array.length; ++i) {
			var s = tmp_array[i];
            out.push(punycode.toASCII(s));
		}
		
        var encoded_email = out.join("@");
		
		if(!(/^([a-zA-Z0-9+])+([a-zA-Z0-9+\._-])*@([a-zA-Z0-9+_-])+([.])+([a-zA-Z0-9\._-]+)+$/.test(encoded_email))){
			return false;
		}
	
		return true;
	}
	
	return text;
};

function check_pass_strength(ev, pb){
	
	var progressClass = 'progress-bar bg-';
	var ariaMsg = '0';
	var $progressBarElement = pb;
	var score = 0;
	var pass = ev.val();
	
	if (!pass){
		return score;
	}

	// Award every unique letter until 5 repetitions
	var letters = new Object();
	for (var i=0; i<pass.length; i++) {
		letters[pass[i]] = (letters[pass[i]] || 0) + 1;
		score += 5.0 / letters[pass[i]];
	}

	// Bonus points for mixing it up
	var variations = {
		digits: /\d/.test(pass),
		lower: /[a-z]/.test(pass),
		upper: /[A-Z]/.test(pass),
		nonWords: /\W/.test(pass),
	}

	var variationCount = 0;
	for (var check in variations) {
		variationCount += (variations[check] == true) ? 1 : 0;
	}

	score += (variationCount - 1) * 10;
	score = parseInt(score);

	if(score >=100){
		score=100;
	}

	if(score >= 65){
		progressClass += 'success';
		ariaMsg = score;
	}else if(score <= 45){
		progressClass += 'danger';
		ariaMsg = score;
	}else if(score > 45 ){
		progressClass += 'warning';
		ariaMsg = score;
	}	

	$progressBarElement.removeClass().addClass(progressClass);
	$progressBarElement.attr('aria-valuenow', score+"%");
	$progressBarElement.css('width', score+"%");
	$progressBarElement.find('span').text(ariaMsg+"%");
	
}

function check_pass_score(pass){
	
	var score = 0;
	
	if (!pass){
		return score;
	}
	
	// Award every unique letter until 5 repetitions
	var letters = new Object();
	for (var i=0; i<pass.length; i++) {
		letters[pass[i]] = (letters[pass[i]] || 0) + 1;
		score += 5.0 / letters[pass[i]];
	}

	// Bonus points for mixing it up
	var variations = {
		digits: /\d/.test(pass),
		lower: /[a-z]/.test(pass),
		upper: /[A-Z]/.test(pass),
		nonWords: /\W/.test(pass),
	}

	var variationCount = 0;
	for (var check in variations) {
		variationCount += (variations[check] == true) ? 1 : 0;
	}

	score += (variationCount - 1) * 10;
	score = parseInt(score);

	if(score >=100){
		score=100;
	}
	
	return score;
	
}

function goto_id(id){
	// Scroll
	$('html,body').animate({
		scrollTop: $("#"+id).offset().top},
		'slow');
}

function dotweet(ele){
	window.open($("#"+ele.id).attr("action")+"?"+$("#"+ele.id).serialize(), "_blank", "scrollbars=no, menubar=no, height=400, width=500, resizable=yes, toolbar=no, status=no");
	return false;
}

function new_theme_funcs_init(){
	
	$(".sai_exp").each(function(){
		$(this).hide();
		var txt = $(this).html();
		var tmp = $('<i class="fas fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="top" style="font-size:1.15em; vertical-align:middle;"></i>');
		tmp.attr('title', txt);
		$(this).parent().append(tmp);		
	});
	
};

function toggle_pass(toggle_button, pass_field) {
	
	if($("#"+pass_field).attr('type') == "text"){
		$("#"+toggle_button).text(l.show);
		$("#"+pass_field).prop("type", "password");
	}
	else{
		$("#"+toggle_button).text(l.hide);
		$("#"+pass_field).prop("type", "text");
	}
}

function slugify(string) {
	
	const a = 'àáâäæãåāăąçćčđďèéêëēėęěğǵḧîïíīįìłḿñńǹňôöòóœøōõőṕŕřßśšşșťțûüùúūǘůűųẃẍÿýžźż·/_,:;'
	const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiilmnnnnoooooooooprrsssssttuuuuuuuuuwxyyzzz------'
	const p = new RegExp(a.split('').join('|'), 'g')

	return string.toString().toLowerCase()
		.replace(/\s+/g, '-') // Replace spaces with -
		.replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
		.replace(/&/g, '-and-') // Replace & with 'and'
		.replace(/[^\w\-]+/g, '') // Remove all non-word characters
		.replace(/\-\-+/g, '-') // Replace multiple - with single -
		.replace(/^-+/, '') // Trim - from start of text
		.replace(/-+$/, '') // Trim - from end of text
}

function go_to(max, pg, urlto){
	try{
		var urlto = (urlto || window.location).toString();
		var pg = pg || 0;
		var final = urlto.replace(/(&?)page\=(\d{1,4})|(&?)reslen\=(\d{1,500}|all)/gi,"")+"&page="+pg+"&reslen="+max;
		window.location = final;
	}catch(e){ }
};

// Joins an object
function obj_join(c, obj){
	var c = c || "";
	var r = [];
	for(var x in obj){
		r.push(obj[x]);
	}
	return r.join(c);
}


//Returns true if it is a DOM node
// https://stackoverflow.com/questions/384286/how-do-you-check-if-a-javascript-object-is-a-dom-object
function isNode(o){
	return (typeof Node === "object" ? o instanceof Node : o && typeof o === "object" && typeof o.nodeType === "number" && typeof o.nodeName==="string");
}

//Returns true if it is a DOM element   
// https://stackoverflow.com/questions/384286/how-do-you-check-if-a-javascript-object-is-a-dom-object 
function isElement(o){
	return (typeof HTMLElement === "object" ? o instanceof HTMLElement : o && typeof o === "object" && o !== null && o.nodeType === 1 && typeof o.nodeName==="string");
}

function merge_obj(obj1,obj2){
	var obj3 = {};
	for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
	for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
	return obj3;
}



// Submit a form and show response
// ele - can be a object of key => value
// 		OR it can be a string key=value&key1=value1
//		OR it can be a an element within the form or the form itself
function submitit(ele, p){
	
	p = p || {};
	
	// Hide any modals if visible
	$(".modal:visible").each(function(){
		var m = $(this);
		
		if(m.attr("id") == "show_message"){
			return;
		}
		
		var bm = bootstrap.Modal.getOrCreateInstance(m[0]);
		
		p.sm_repopup = function(){
			bm.show();
		}
		
		// Hide it
		bm.hide();
		
	});
	
	var loc = window.location.toString();	
	if(loc.indexOf("#") > 0){
		loc = loc.substring(0, loc.indexOf("#"));
	}
	
	if(!(loc.indexOf("?") > 0)){
		loc = loc+'?';
	}
	
	// If its an element
	if(isElement(ele) || isNode(ele)){
		
		// Can be the form or the submit button
		var jEle = $(ele);
		var form = $(ele).closest("form");
		
		var button = jEle;
		
		// Is it the submit button ?
		if(jEle.prop("type") != "submit"){
			var button = form.find(':submit').first();
		}
		
		// Get the element data
		var d = form.serialize()+(typeof(button.attr("name")) !== 'undefined' && button.attr("name").length > 0 ? "&"+button.attr("name")+"="+button.val() : 0);
		
		var opt = jEle.data();
		//console.log(opt);
		
		// If we have to reload on done !
		if("donereload" in opt){
			p.done_reload = loc;
		}
		
		// If we have to redirect on done !
		if("doneredirect" in opt){
			p.done_reload = opt["doneredirect"];
		}
		
		// Pass the element
		p.ele = ele;
		
	// If its an object
	}else{
		var d = ele;
	}
	
	//console.log(d);
	//console.log(p);
	
	// Show the loading
	$(".loading").show();
	
	// Make an ajax call
	$.ajax({
		type: "POST",
		url: loc+"&api=json",
		data: d,
		dataType: "json",
		success: function(data){
			$(".loading").hide();
			
			if(p.success){
				p.success(data, p);
			}
			
			if(p.handle){
				p.handle(data, p);
			}else{
				handleResponseData(data, p);
			}
			
			if(p.after_handle){
				p.after_handle(data, p);
			}
		},
		error: function() {
			
			$(".loading").hide();
			
			var a = show_message_r(l.error, l.r_connect_error);
			a.alert = "alert-danger";
			
			// Show any previous popups
			if(p.sm_repopup){
				a.onclose.push(p.sm_repopup);
			}
			
			show_message(a);
		}
	});
	
	// Necessary to prevent submission
	return false;
}

// Handler for submitted data - redirects to be handled by logic !
function handleResponseData(data, p){
	
	p = p || {};
	
	// Are we to show a success message ?
	if(typeof(data["done"]) != "undefined"){
		
		var d = show_message_r(l.done, data.done.msg);
		d.alert = "alert-success";
		
		if(p.done_reload){
			d.onclose.push(function(){
				window.location = p.done_reload;
			});
		}
		
		if(p.sm_done_onclose){
			d.onclose.push(p.sm_done_onclose);
		}
		
		// Onclose trigger
		if(p.ele){
			d.onclose.push(function(){
				$(p.ele).trigger("done_onclose");
			});
			
			$(p.ele).trigger("done");
		}
		
		//console.log(d);
		
		// Show the message
		show_message(d);
		
		// If there is anything to do after showing the message ?
		if(p.done){
			p.done(data);
		}
		
	}

	// Are there any errors ?
	if(typeof(data["error"]) != "undefined"){
		
		var errors = l.following_errors_occured+"<br><ul><li>";
		errors += obj_join("</li><li>", data.error);
		errors += "</li></ul>";
			
		var a = show_message_r(l.error, errors);
		a.alert = "alert-danger";
		
		// Show any previous popups
		if(p.sm_repopup){
			a.onclose.push(p.sm_repopup);
		}
		
		show_message(a);
		
		// If there is anything to do after showing the message ?
		if(p.error){
			p.error();
		}
	}

	// Are we to get redirected ?
	if(typeof(data["redirect"]) != "undefined"){
		window.location = data["redirect"];
	}
	
};

// Universal function to delete a record
function delete_record(ele, p){
	
	var jEle = $(ele);
	
	p = p || {};
	p.ele = ele;
	
	var a = show_message_r(l.warning, l.delete_conf);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
			
		// Get the element data
		var d = jEle.data();
		//console.log(d);

		// Submit the data
		submitit(d, {
			sm_done_onclose: function(){
				
				// If there is any row of this, delete it
				if(jEle.attr("id").length > 0 && jEle.attr("id").match(/^did/)){
					var did = jEle.attr("id").substring(3);
					var escaped_did = did.replace(/[-\/()_*+?&.\#\s]/g, "\\\$&");
					if($("#tr"+escaped_did).length > 0){
						
						var tObj = $("#tr"+escaped_did).parent();
						$("#tr"+escaped_did).remove();
						
						// If it is final row!!!
						if(tObj.children().length < 1){
							
							var tmpthml = '<tr><td class="text-center" colspan="100">'+l.no_tbl_rec+'</td></tr>';
							tObj.append(tmpthml);
							
						}
						
						return;
					}
				}
				
				location.reload();
				
			}
		});
		
	});
	
	show_message(a);
	
}

function delete_manyrecord(ele, p){
	var jEle = $(ele);
	
	p = p || {};
	p.ele = ele;
	
	var a = show_message_r(l.warning, l.delete_conf);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
			
		// Get the element data
		var d = jEle.data();
		// console.log("d", d);
		var tbjEle = $("#tbody_"+d.deletmanykey);
		var deljEles = tbjEle.find("."+d.delete_select_cls);
		var deleteData = [];
		var deleteIds = [];
		
		// console.log(deljEles);
		
		$.each(deljEles, function(){
			
			var currentEle = $(this);
			var isChecked = currentEle.prop("checked");
			
			if(!isChecked){
				return;
			}
			
			var tmp = currentEle.data();
			
			console.log(tmp);
			
			deleteData.push(tmp[d.deletmanykey]);
			deleteIds.push(tmp.id);
			
		});
		
		d[d.deletmanykey] = deleteData;
		// delete d.deletmanykey;
		// delete d.delete_select_cls;
		
		if("donereload" in d ){
			p.done_reload = window.location.toString();
		}
		// console.log(deleteData, deleteIds, d);

		submitit(d, {
			...p,
			
			sm_done_onclose: function(){
				
				// If there is any row of this, delete it
				if(deleteIds.length > 0){
					
					$.each(deleteIds, function(key, val){
						// console.log(key, val);
						if($("#tr"+val).length > 0){
							var tObj = $("#tr"+val).parent();
							$("#tr"+val).remove();
							
							// If it is final row!!!
							if(tObj.children().length < 1){
								
								var tmpthml = '<tr><td class="text-center" colspan="100">'+l.no_tbl_rec+'</td></tr>';
								tObj.append(tmpthml);
								
							}
						}
						
						
					});
					
					$(p.ele).trigger("done:delete_many");
						return;
				}
				
				location.reload();
				
			}
			
		});
	});
	
	show_message(a);
}

function delete_selected(ele){
	var jEle = $(ele);
	var d = jEle.data();	
	var dEle = $("."+d.class);
	d[d.deletekey] = [];
	
	var p = {};
	p.ele = ele;
	
	var a = show_message_r(l.warning, l.delete_conf);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){	
	
		$.each(dEle, function(){
			var cEle = $(this);
			var isChecked = cEle.prop("checked");
			
			if(!isChecked){
				return;
			}
			
			d[d.deletekey].push(cEle.val());
		});
		
		if("donereload" in d ){
			p.done_reload = window.location.toString();
		}
		//console.log(d, p);

		submitit(d, p);
	});
	
	//console.log(d);
	
	show_message(a);
}

function show_message_r(_title, _body){
	
	var d = {title: _title, body: _body};
	
	d.onclose = [];
	d.confirm = [];
	d.ok = [];
	d.no = [];
	
	return d;
	
}

function exec_funcs_in_r(r){
	
	return function(){
		for(var x in r){
			r[x]();
		}
	}
}

// The modal to show all messages
function show_message(p){
	
	// Get the modal
	var m = $("#show_message");	
	var bm = bootstrap.Modal.getOrCreateInstance(m[0]);
	
	// Unbind any previous modal hidden events
	m.unbind("hidden.bs.modal");
	
	// Bootstrap prevents firing up the modal once its shown
	// Hence we will show it after its hidden
	if(m.is(":visible")){
		m.on("hidden.bs.modal", function(){
			show_message(p);
		});
		return;
	}
	
	// On close do we need to execute ?
	if(p.onclose && p.onclose.length> 0){
		m.on("hidden.bs.modal", exec_funcs_in_r(p.onclose));
	}
	
	// Title
	m.find(".modal-title").html(p.title || "");
	
	// Type of header
	m.find(".modal-header").removeClass(function (index, className) {
		return (className.match (/(^|\s)alert-\S+/g) || []).join("");
	});
	
	if("alert" in p){
		m.find(".modal-header").addClass("alert "+p.alert);
	}
	
	// The body
	m.find(".modal-body").html(p.body);
	
	// Clear previous click events
	m.find(".yes, .no, .ok").unbind("click");
	
	if(p.confirm && p.confirm.length > 0){
		m.find(".yes, .no").show();
		m.find(".ok").hide();
		if(p.confirm && p.confirm.length > 0){
			m.find(".yes").click(exec_funcs_in_r(p.confirm));
		}
		if(p.no && p.no.length > 0){
			m.find(".no").click(exec_funcs_in_r(p.no));
		}
	}else{
		m.find(".yes, .no").hide();
		m.find(".ok").show();
		if(p.ok && p.ok.length > 0){
			m.find(".ok").click(exec_funcs_in_r(p.ok));
		}
	}
	
	// Show the modal
	bm.show();
	
}
	
function human_readable_bytes(v){
	return (v >= 1073741824 ? Math.round(v / 1073741824)+" GB" : (v > 1048576 ? Math.round(v / 1048576)+" MB" : (v > 1024 ? Math.round(v / 1024)+" KB" : Math.round(v)+" Bytes")));
}

function isNumber(n) {
	if(isNaN(n.value)){
		alert(n.value+' is not a valid Number');
		return false;
	}
}

var task_logs_refresh_i;

// Function to show LOGS if any error occurs
function loadlogs(actid, is_refresh){
	
	is_refresh = is_refresh || false;
	var logs_body = $("#logs_modal_body");
	var old_actid = logs_body.data("actid") || "";
	var last_length = parseInt($("#logs_modal_body").data("last-length")) || 0;
	
	actid = actid || 0;
	
	// Handle the closure
	var m = $('#logs_modal');
	m.unbind('hidden.bs.modal');
	m.on('hidden.bs.modal', function(){
		logs_body.data("actid", "");
	});
	
	var url_path = window.location.origin+window.location.pathname+"?act=";
	$.ajax({
			
		url: url_path+"tasks&api=json",
		data: "actid="+actid,
		dataType : "json",
		method : "post",
		success:function(data){
			
			logs_tab_head = "";
			logs_tab_data = "";
			var j = 0;
			active = "";
			active_div = "";
			
			// Lets set the current taskID
			logs_body.data("actid", actid);
			logs_body.data("last-length", (empty(data["task"]["logs"]) ? 0 : data["task"]["logs"].length));
			
			$("#alert-modal").modal("hide");
			$("#logs_modal").modal("show");
			logs_body.css('padding', '3px');
			
			// In case of refresh append
			if(is_refresh && last_length > 0){
				logs_body.find('.log_div').append(data["task"]["logs"].substr(last_length));
				
				//console.log(data["task"]["logs"].length+' '+last_length);
			
			// Otherwise replace all HTML
			}else{				
				logs_body.html('<div class="log_div">'+data["task"]["logs"]+'</div>');
			}
			
			///////////////////////////////////////////////////////////////
			
			//header click event
			task_header();
			
			//button click event
			load_task();
			
			//output button click event
			command_output();
			
			// Clear any refresh interval
			clearTimeout(task_logs_refresh_i);
			
			// If the task is still running refresh
			if(data["task"]["status"] > 0 && data["task"]["progress"] < 100){
				//console.log("Refreshing");
				task_logs_refresh_i = setTimeout(task_logs_refresh, 4000);
			}
			
			var exportLink = document.getElementById('exportLink');
			exportLink.href = url_path + 'tasks&export=html&actid=' + actid;
			
			document.getElementById('task_id').textContent = ' [ID:'+actid+']';

    
			/////////////////////////////////////////////////////////////
			
		}
	});

}

// Refresh the task logs
function task_logs_refresh(){
	
	var actid = parseInt($("#logs_modal_body").data("actid")) || 0;
	
	if(!$("#logs_modal_body").is(":visible") || actid < 1){
		setTimeout(task_logs_refresh_i);
		return;
	}
	
	loadlogs(actid, 1);
	
}

function command_output(){
	$('.command_output').each(function(){
		$(this).unbind().click(function(){
			var id = $(this).attr('data-target');
			$(id).slideToggle();
			$(this).text(function(i,old){
				return (old == l.show ? l.hide : l.show);
			});
		});
	});
}

function task_header(){
		
	//default header
	$(".vexec_default_header").each(function(){
		//if next element is group task then we add vexec_task_header class
		if($(this).next('.grouped_task').length == 1){
			$(this).removeClass('vexec_default_header');
			$(this).addClass('vexec_task_header');
		}
		if(empty($(this).text())){
			$(this).remove();
		}
	});
	
	//toggle header 
	$(".vexec_task_header").each(function() {
		//if next element is group task then we add span style to the div
		if($(this).next('.grouped_task').length == 1 && $(this).find('.sign').length != 1 && $(this).find('.shw_txt').length != 1){
			$(this).css('cursor','pointer');
			$(this).prepend('<span class="bg-primary sign" style="">+</span>');
			$(this).append('<span style="margin-left:20px;">(<span style="display: inline-block;" class="shw_txt">'+show_more_l+'</span>)</span>');
			$(this).next('.grouped_task').css('display','none');
		}
		
		if(empty($(this).text())){
			$(this).remove();
		}
	});
	
	$('.vexec_task_header').unbind().click(function() {
		//Get data from the button 
		var actid = $(this).next('.grouped_task').find('.load-task').data('actid');
		var vpsid = $(this).next('.grouped_task').find('.load-task').data('vpsid');
		var serid = $(this).next('.grouped_task').find('.load-task').data('serid');
		var slaveactid = $(this).next('.grouped_task').find('.load-task').data('slaveactid');
		var next_action = $(this).next('.grouped_task').find('.load-task').data('op');
		
		actid = actid || '';
		vpsid = vpsid || '';
		serid = serid || '';
		slaveactid = slaveactid || '';
		next_action = next_action || '';

		if(empty(actid)){
			actid = slaveactid;
		}
		
		$(this).next('.grouped_task').slideToggle();
		
		//Perform ajax only if we get data-vpsid from the button inside grouped_task
		if(!empty($(this).next('.grouped_task').find('.load-task').data('vpsid'))){
			$(this).next('.grouped_task').find('.load-task').css('display','none');
			
			//only add the div if it is not present
			if($('#'+next_action+actid).length != 1){
				$(this).next('.grouped_task').find('button').after('<div id="'+next_action+actid+'"></div>');
			}
			
			var url_path = window.location.origin+window.location.pathname+"?act=";
			
			//perform ajax on when div when user want to see the div and when he clicks again to hide dont perform ajax.
			if($(this).find('.shw_txt').text() == show_more_l){
				$.ajax({
					url: url_path+'tasks&jsnohf=1',
					data: 'find_actid='+actid+'&find_action='+next_action+'&find_vps='+vpsid+'&serid='+serid+'&slaveactid='+slaveactid,
					method : 'post',
					beforeSend:function(){
						//By default show loading.
						$('#'+next_action+actid).html('<center><img src=\'../themes/default/images/admin/vpsloading.gif\' width="20px"/><center>');
					},
					success:function(data){
						$('#'+next_action+actid).html(data);
						$('#'+next_action+actid+' button').unbind().click(function(){
							$(this).text(function(i,old){
								return (old == l.show ? l.hide : l.show);
							});
						});
						load_task();
						task_header();
						command_output();
					}
				});
			}
		}
		
		$(this).find('.sign').text(function(i,old){
			return (old == '+' ? '-' : '+');
		});
		$(this).find('.shw_txt').text(function(i,old){
			return (old == show_more_l ? l.showess_l : show_more_l);
		});
	});
}

function load_task(){
	$('.load-task').unbind().click(function(){
		//Get data from the button
		var actid = $(this).data('actid');
		var vpsid = $(this).data('vpsid');
		var next_action = $(this).data('op');
		var serid = $(this).data('serid');
		serid = serid || '';
		
		//Add onclick attr so that div can be toggled
		$(this).attr('onclick','_slidetoggle(this,\''+next_action+actid+'\',\''+l.show+'\',\''+l.hide+'\');');
		
		//only add the div if it is not present
		if($('#'+next_action+actid).length != 1){
			//On first click we change text to hide
			$(this).text(l.hide);
			$(this).after('<div id="'+next_action+actid+'" style="display: block;border: 1px solid#868686;padding: 3px;background-color: #f7ffee;"></div>');
		}
		// l.show and l.hide is defined in hf_theme, its language so we will need to define in variable
		var url_path = window.location.origin+window.location.pathname+"?act=";
		$.ajax({
			url: url_path+'tasks&jsnohf=1',
			data: 'find_actid='+actid+'&find_action='+next_action+'&find_vps='+vpsid+'&serid='+serid,
			method : 'post',
			success:function(data){
				$('#'+next_action+actid).html(data);
				
				//if the button inside div have attr data-vpsid do not add onclick event.
				$('#'+next_action+actid+' button').each(function(){
					if($(this).data('vpsid') == ''){
						$(this).unbind().click(function(){
							$(this).text(function(i,old){
								return (old == l.show ? l.hide : l.show);
							});
						});
					}
				});
				// recursive call to again register the click events
				load_task();
				task_header();
				command_output();
			}
		});
	});
}

function load_average(){
	var url_path = window.location.origin+window.location.pathname+"?act=load_average&api=json";
	$.ajax({
		url: url_path,
		type: "post",
		dataType: "JSON",
		success: function(response){			
			var spl = response["load_average"];
			$("#lavg_one").html(spl[0]);
			$("#lavg_five").html(spl[1]);
			$("#lavg_fifteen").html(spl[2]);
		}
	});
}

function dismiss_notice(ele){
	var jEle = $(ele);
	var d = jEle.data();
	var url_path = window.location.origin+window.location.pathname+"?act=notices&dismiss_notice=1&api=json";
	
	$.ajax({
		url: url_path,
		type: "post",
		dataType: "JSON",
		data: d,
		success: function(response){				
			// console.log("dismiss_notice", response);
			
			var pEle = $("#notices");
			
			if(d.nid == "all"){
				pEle.children().remove();
				$("#notices_count").text(0);
			}else{
				if( $("#ndiv_"+d.nid).length > 0){
					$("#ndiv_"+d.nid).remove();
				}
				$("#notices_count").text($("#notices").children().length);
			}
			
			// if its last notice simply display no notice !
			
			if(pEle.children().length < 1){
				pEle.html("<p>"+l.ser_no_notices+"</p>");
			}
			
		}
	});
}

function submititwithdata(ele, p){
	
	p = p || {};
	
	// console.log("ele",ele, p);return false;
	// Hide any modals if visible
	$(".modal:visible").each(function(){
		var m = $(this);
		
		if(m.attr("id") == "show_message"){
			return;
		}
		
		var bm = bootstrap.Modal.getInstance(document.getElementById($(this).attr("id")));
		// console.log(bm);return;
		// var bm = new bootstrap.Modal(m, {});
		
		p.sm_repopup = function(){
			bm.show();
		}
		
		// Hide it
		bm.hide();
		
	});
	
	var loc = window.location.toString();
	if(loc.indexOf("#") > 0){
		loc = loc.substring(0, loc.indexOf("#"));
	}
	
	if(!isElement(ele)){
		return false;
	}

	// If its an element
	if(isElement(ele) || isNode(ele)){
		
		// Can be the form or the submit button
		var jEle = $(ele);
		var form = $(ele).closest("form");

		if(form.attr('action')){
			loc = form.attr('action');
		}
		
		var button = jEle;
		
		// Is it the submit button ?
		if(jEle.prop("type") != "submit"){
			var button = form.find(':submit').first();
		}
		
		// Get the element data
		var d = form.serialize()+(button.attr("name").length > 0 ? "&"+button.attr("name")+"="+button.val() : 0);
		
		var opt = jEle.data();
		//console.log(opt);
		
		// If we have to reload on done !
		if("donereload" in opt){
			p.done_reload = loc;
		}
		
		// If we have to redirect on done !
		if("doneredirect" in opt){
			p.done_reload = opt["doneredirect"];
		}
		
		// Pass the element
		p.ele = ele;
		
	// If its an object
	}

	// Make an ajax call
	$.ajax({
		type: "POST",
		url: loc+"&api=json",
		data: new FormData(ele),
		dataType: "json",
		contentType: false,
		cache: false,
		processData:false,
		success: function(data){
			$(".loading").hide();
			
			if(p.success){
				p.success(data, p);
			}
			
			if(p.handle){
				p.handle(data, p);
			}else{
				handleResponseData(data, p);
			}
			
			if(p.after_handle){
				p.after_handle(data, p);
			}
		},
		error: function() {
			
			$(".loading").hide();
			
			var a = show_message_r(l.error, l.r_connect_error);
			a.alert = "alert-danger";
			
			// Show any previous popups
			if(p.sm_repopup){
				a.onclose.push(p.sm_repopup);
			}
			
			show_message(a);
		}
	});
	

	// Necessary to prevent submission
	return false;
}

// Make a select2 box
function make_select2(ele, props){
	
	var jEle = $(ele);
	props = props || {};
	
	props.placeholder = jEle.attr('s2-placeholder') || '';
	props.allowClear = jEle.attr('s2-allow-clear') || true;
	props.dropdownParent = $(jEle.attr('s2-dropdownParent') || document.body);
	props.val = jEle.val();
	
	// Is there any Ajax data to load ?
	props.ajaxurl = jEle.attr('s2-ajaxurl') || '';	
	if(!empty(props.ajaxurl)){
		
		props.ajax = {
			url: props.ajaxurl,
			dataType: jEle.attr('s2-ajax-datatype') || "json",
			type: jEle.attr('s2-ajaxtype') || "post",
			data: function(params) {
				var query = {};
				query[jEle.attr('s2-query')] = params.term;
				return query;
			},
			processResults: function(data){
				var r = data[jEle.attr('s2-data-key')];
				
				var obj = [];
				if(jEle.attr('s2-data-subkey')){
					
					var func = function(item){
						return {
							text: item[jEle.attr('s2-data-subkey')],
							id: item[jEle.attr('s2-data-subkey')],
						}
					}
					
					if(jEle.attr('s2-data-display-handler')){
						func = window[jEle.attr('s2-data-display-handler')];
					}
					
					obj = $.map(r, func);
				}else{
					$.each(r, function(k, v){
						obj.push({text: k, id: k});
					});
				}
				
				var ret = {results: obj};
				
				if(jEle.attr('s2-result-add')){
					var add = JSON.parse(jEle.attr('s2-result-add'));
					for(var i in add){
						ret.results.push(add[i]);
					}
				}
				//console.log(ret);
				return ret;
			},
			cache: false,
		}
		
	}
	
	props.escapeMarkup = function(m){ return m; }
	
	// Make the Select2
	jEle.select2(props);
	
}
