/***************************/
//@Author: Adrian "yEnS" Mato Gondelle
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;

//loading popup with jQuery magic!
function loadPopup(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#popupContact").fadeIn("slow");
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#popupContact").fadeOut("slow");
		popupStatus = 0;
	}
}

function centerElement(element){
	var elementWidth, elementHeight, windowWidth, windowHeight, X2, Y2, position='fixed';
	win=$(window);
	elementWidth = element.outerWidth();
	elementHeight = element.outerHeight();
	windowWidth = win.width();
	windowHeight = win.height();	
	X2 = (windowWidth/2 - elementWidth/2) + "px";
	if (windowHeight <= elementHeight) {Y2 = $(window).scrollTop() + "px"; position = 'absolute';} else {Y2 = (windowHeight/2 - elementHeight/2) + "px";}
	jQuery(element).css({
		'left':X2,
		'top':Y2,
		'position':position
	});
}

//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	
	//LOADING POPUP
	//Click the button event!
	$("#button").click(function(){
		//centering with css
		centerElement($("#popupContact"));
		$("#backgroundPopup").css({
			"height": document.documentElement.clientHeight
		});
		//load popup
		loadPopup();
	});
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupContactClose").click(function(){
		disablePopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		disablePopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup();
		}
	});

});