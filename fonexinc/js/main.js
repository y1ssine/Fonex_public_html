"use strict";
var xtensions = document.querySelector('#extension');
var radios1 = document.getElementsByName('choice');
var selectedtxt = document.getElementsByName('#selectedtxt');
var package2 = document.querySelector('#package');
var singlefree = document.querySelector('#singlefree').innerHTML;
var singlecontract = document.querySelector('#singlecontract').innerHTML;
var ext2_20free = document.querySelector('#ext2-20free').innerHTML;
var ext2_20contract = document.querySelector('#ext2-20contract').innerHTML;
var sr,xt,xt2;
$(document).on('keyup mouseup', '#extension', function() { 
  xt = xtensions.value;
  for (var i = 0, length = radios1.length; i < length; i++) {
    if (radios1[i].checked) {
      // do whatever you want with the checked radio
      sr = radios1[i].value;
      
      // only one radio can be logically checked, don't check the rest
      break;
    }
  }     
  change_plans(sr, xt);                                                                                                             
});
function change_plans(type, ex){
   if(type ==="free" && ex==1){
     package2.innerHTML = singlefree;
   }
   if(type ==="contract" && ex==1){
     package2.innerHTML = singlecontract;
   }
   if(type ==="free" && ex !== 0 && ex > 1 && ex <= 20){
     package2.innerHTML = ext2_20free;
   }
   if(type ==="contract" && ex>1  && ex <= 20){
     package2.innerHTML = ext2_20contract;
   }
}
/* Radio switch input - start***/

//radio_switch("toggle1","flap1","choice3","choice4","content1");
//radio_switch("flap1","choice3","choice4");
function radio_switch(toggle,flap, choice1, choice2, content){
    const st = {};

    st.flap = document.querySelector('#'+flap);
    st.toggle = document.querySelector('.'+toggle);
    st.content = document.querySelector('.'+content);

    st.choice1 = document.querySelector('#'+choice1);
    st.choice2 = document.querySelector('#'+choice2);

    st.flap.addEventListener('transitionend', () => {

        if (st.choice1.checked) {
            st.toggle.style.transform = 'rotateY(-15deg)';

            setTimeout(() => st.toggle.style.transform = '', 400);
        } else {
            st.toggle.style.transform = 'rotateY(15deg)';
            setTimeout(() => st.toggle.style.transform = '', 400);
        }

    })

    st.clickHandler = (e) => {
        
        if (e.target.tagName === 'LABEL' && e.target.className==="cho") {
            selectedtxt.innerHTML =  e.target.textContent
            setTimeout(() => {
                st.flap.children[0].textContent = e.target.textContent;
            }, 250);
        }
        if (e.target.tagName === 'INPUT' && e.target.className==="choi") {
            xt2 = xtensions.value;

            change_plans(e.target.value, xt2);
        }

    }

    document.addEventListener('DOMContentLoaded', () => {
        st.flap.children[0].textContent = st.choice2.nextElementSibling.textContent;
    });

    document.addEventListener('click', (e) => st.clickHandler(e));
}
/* Radio switch input - end ***/
var step1_form = $("#step1_form");
var path = window.location.origin;
var tz = $("#i_time_zone").val();
$.post( path+'/fonexinc/helper.php', { 
    gettimezone: tz})
    .done(function( data ) {
    $("#i_time_zone").val(data.trim());
});
$.post( path+'/fonexinc/helper.php', { 
    code: 'US'})
    .done(function( data ) {
    $("#states").html(data.trim());
});
var numberContainer = $(".number-result-item-container");
var extension = $("#extension");
var package3 = $("#package");
var i_customer = $("#i_customer");
var diderror = $("#diderror");
var didload = $("#didload");
var number = $("#didnumber");
$(document).ready(function () {
    radio_switch("toggle","flap","choice1","choice2","content");
    end_session();
    var cellphone = $("#cellphone");
    var selectext = $("#selectext");
    var ivrlang = $("#ivrlang");
    var ivroptions = $("#ivroptions");
    var selectextall= $(".allexts");
   

    
    var firststepload = $("#firststepload");
    var secondstepload = $("#secondstepload");
    var fourthstepload = $("#fourthstepload");
    
    var callflow = $("#callflow");
    var cellphone = $("#cellphone");
    var flowvoicemail = $("#flowvoicemail");
    var thirdstepload = $("#thirdstepload");
    var callflowerror = $("#callflowerror");
    var bycity1 = $("#bycity");
    var  flow ="";
    var l ="";
    var stat,data7,t;
    var btn_local_search_by_city = $("#btn_local_search_by_city");
    stat = $("#stat");
    var stat2 = $("#stat2");
    var cit = $("#city1");
    var city2 = $("#city2");
    btn_local_search_by_city.on('click', function(){
        didload.show();
        end_session();
        bycity1.hide();
        stat2.hide();
        city2.hide();
        numberContainer.html('');
        data7 = 'action=get_states';
        $.ajax({
          type: 'POST',
          url : path+'/fonexinc/functions.php',              
          data: data7,
          success: function(data){
             didload.hide();
             bycity1.show();
             stat.html(data);
             stat2.show();
          }

        });
    });
    
    stat.on('change', function(){
        city2.hide();
        didload.show();

        cit.html("");
        t = $(this).val();
        numberContainer.html('');
        data7 = 'action=get_ratecenter&state='+t;
        end_session();
        $.ajax({
          type: 'POST',
          url : path+'/fonexinc/functions.php',              
          data: data7,
          success: function(data){
             didload.hide();
             cit.html(data);
             city2.show();
          }

        });
    });
    var st;
    cit.on('change', function(){
        t = $(this).val();
        st = stat.val();
        didload.show();
        numberContainer.html('');
        data7 = 'action=get_numbers&state='+st+'&city='+t;
        end_session();
        $.ajax({
          type: 'POST',
          url : path+'/fonexinc/functions.php',              
          data: data7,
          success: function(data){
           didload.hide();
           numberContainer.html(data);
          }

        });
    });
    function add_customer($step){
    var form = $("#step1_form");
    var error = $("#step1error");
    var stext = $("#package option:selected").text();
    var splittext = stext.split(" ");
    var texttosend = (splittext.length > 0) ? splittext[0]+" "+splittext[splittext.length-1] : "";
    var data = form.serialize();
    //clear session 
    document.cookie = "PHPSESSID=;Path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
    data = 'action=add_customer&'+data+'&plan_name='+texttosend;
    end_session();
    $.ajax({
        type: 'POST',
        url : path+'/fonexinc/functions.php',              
        data: data,
        success: function(data){
           firststepload.hide();
         }
      });
      }
    $("#lang").on('change', function(){
      l = $(this).val();
      if(l==='Disabled'){
        $(".langhere").hide();
        $(".dis").show();
      }else{
        $(".langhere").html(l);
        $(".dis").hide();
        $(".langhere").show();

      }
      ivroptions.show();
    });
    callflow.on('change', function(){
        flow = $(this).val();
        if(flow=='recepIVR'){
          ivrlang.show();
          ivroptions.show();
        }
        else if(flow=='cellphone'){
           cellphone.show();
           ivrlang.hide();
           ivroptions.hide();
        }else if(flow =='IVR'){
           ivrlang.show();
           ivroptions.show();
           cellphone.hide();
        }
        else{
           ivrlang.hide();
           cellphone.hide();
           ivroptions.hide();
        }
    }); 
    var extforvm1 = $("#extforvm1");
    var extforvm = $("#extforvm");
    var emailforvm = $("#emailforvm");
    var allext = $("#allext");
    flowvoicemail.on('change', function(){
        flow = $(this).val();
        if(flow=='extemail'){
          var theExt = "";
          for (var i =  0; i < extension.val(); i++) {
            theExt += '<option value="10'+i+'">Extension 10'+i+'</option>'; 
          }
          extforvm1.html(theExt);
          allext.html(theExt);
          extforvm.show();
          emailforvm.show();
        }
        else if(flow=='specemail'){
           extforvm.hide();
           emailforvm.show();
        }else{
           extforvm.hide();
           emailforvm.hide();
        }
    }); 
    var html = '<tr><td>From<select  class="form-control" name="999fromday" class="fromday"><option value="mo">Monday</option><option value="tu">Tuesday</option><option value="we">Wednesday</option><option value="th">Thursday</option><option value="fr">Friday</option><option value="sa">Saturday</option><option value="su">Sunday</option></select></td><td>To<select  class="form-control" class="today" name="today"><option value="mo">Monday</option><option value="tu">Tuesday</option><option value="we">Wednesday</option><option value="th">Thursday</option><option value="fr">Friday</option><option value="sa">Saturday</option><option value="su">Sunday</option></select></td><td>From<select  class="form-control" class="fromhour" name="fromhour"><option value="0">00:00</option><option value="0.30">00:30</option><option value="1">01:00</option><option value="1.30">01:30</option><option value="2">02:00</option><option value="2.30">02:30</option><option value="3">03:00</option><option value="3.30">03:30</option><option value="4">04:00</option><option value="4.30">04:30</option><option value="5">05:00</option><option value="5.30">05:30</option><option value="6">06:00</option><option value="6.30">06:30</option><option value="7">07:00</option><option value="7.30">07:30</option><option value="8.00">08:00</option><option value="8.30">08:30</option><option value="9">09:00</option><option value="9.30">09:30</option><option value="10">10:00</option><option value="10.30">10:30</option><option value="11">11:00</option><option value="11.30">11:30</option><option value="12">12:00</option></select></td><td>AM/PM<select  class="form-control" class="fromhourm" name="fromhourm"><option value="AM">AM</option><option value="PM">PM</option></select></td><td>To<select  class="form-control" class="tohour" name="tohour"><option value="0">00:00</option><option value="0.30">00:30</option><option value="1">01:00</option><option value="1.30">01:30</option><option value="2">02:00</option><option value="2.30">02:30</option><option value="3">03:00</option><option value="3.30">03:30</option><option value="4">04:00</option><option value="4.30">04:30</option><option value="5">05:00</option><option value="5.30">05:30</option><option value="6">06:00</option><option value="6.30">06:30</option><option value="7">07:00</option><option value="7.30">07:30</option><option value="8.00">08:00</option><option value="8.30">08:30</option><option value="9">09:00</option><option value="9.30">09:30</option><option value="10">10:00</option><option value="10.30">10:30</option><option value="11">11:00</option><option value="11.30">11:30</option><option value="12">12:00</option></select></td><td>AM/PM<select  class="form-control" class="tohourm" name="ampm"><option value="AM">AM</option><option value="PM">PM</option></select></td><td><button class="btn" type="button" style="margin-top: 30%" id="remove_period">Remove</button></td></tr>';
    $("#add_period").on("click", function(even){
       even.preventDefault();
       var period_body = $("#period_body");
       period_body.append(html);
    });
    $(document).on("click","#remove_period",function() {
        $(this).closest("tr").remove();
    });
    var state = $("#statess");
    var stat = $("#stat");
    var btnlg = $(".btn-lg1");
    var byarea = $("#byarea");
    var bycity = $("#bycity");
    var btnlg2 = $(".btn-lg2");
    var btn_local_pick = $("#btn_local_pick");
    var btn_local_keep = $("#btn_local_keep");
    var searchby = $(".SearchDID");
    var search = $(".Search");
    var keepnumb = $("#keepnumb");
    getstates("US");
    function  getstates(country_code){
        /*var data = "code="+country_code; 
        end_session();
        $.ajax({
          type: 'POST',
          url : path+'/fonexinc/helper.php',              
          data: data,
          success: function(data){
             state.html(data);
             stat.html(data);
          }
      });*/
    }
    btnlg2.on('click', function(e){
       e.preventDefault();
       var aclick1 = $(this);
       btnlg2.removeClass("btn-uv-blue");
       aclick1.addClass("btn-uv-blue");
       if(aclick1.attr("id") === "btn_local_pick"){
         keepnumb.hide();
         search.show();
       }else{
         searchby.hide();
         keepnumb.show();
       }

    });
    btnlg.on('click', function(e){
       e.preventDefault();
       var aclick = $(this);
       btnlg.removeClass("btn-uv-blue");
       aclick.addClass("btn-uv-blue");
       if(aclick.attr("id") === "btn_local_search_by_areacode"){
         bycity.hide();
         byarea.show();
       }else{
         byarea.hide();
         bycity.show();
       }

    });
    var did_buttons = $('.local');
    var did_i = $('.radio-container').find('i');
    did_buttons.on('click', function(e){
       e.preventDefault();
       var label_i = $(this).find('i');
       did_i.removeClass('fas fa-check-circle');
       did_i.addClass('far fa-circle');
       label_i.removeClass('far fa-circle');
       label_i.addClass('fas fa-check-circle');
    });

    function verificationForm(){
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        $(".next").on('click', function () {
            animating = true;
            var $input = $( this );
            if($input.attr("id") == "firstnext"){
                    firststepload.show();
                    validate_form("step1_form");
                    add_customer($input);    //return;
                    firststepload.hide();
                    
            }
            if($input.attr("id") == "secondnext"){
                   secondstepload.show();
                   var keep = $("#btn_local_keep").hasClass("btn-uv-blue");
                   var inputkeep = "";
                   if(keep){
                     inputkeep = $("#inputkeep").val().trim();
                   }
                   var periodf = $("#openhours_form").serialize();
                   var length = periodf.length;
                   var timeframe="";
                   var timeframe_desc='';
                   if(length > 0){
                     var res = periodf.substr(3,length);
                     var periods =  res.split("&999");
                     var PM = [24,13,14,15,16,17,18,19,20,21,22,23,12];
                     var h="";
                     var hh="";
                     var timee="";
                     var thour ="";
                     var fhour ="";
                     var days = "";
                     for (var i = 0; i < periods.length; i++) {
                          //split the string by the & so you get key value pairs, and reduce them into a single object
                          var params = periods[i].split('&').reduce(function(results, keyValue){
                          //split the key value pair by the =
                          var [key, value] = keyValue.split('=');
                          h = "";
                          hh = "";
                          //if the key already exists, we need to convert the value to an array, and then push to the array
                          if (results[key]) {
                            if (typeof results[key] === 'string') {
                              results[key] = [ results[key] ];
                            }
                            
                            results[key].push(value);
                          } else {
                            //key did not exist, just set it as a string
                            results[key] = value;
                          }
                          
                          //return the results for the next iteration
                          return results;
                        }, {});

                        days = (params.fromday===params.today) ? params.fromday : params.fromday+"-"+params.today;
                        //hr{2}min{30-59} wd{mo-fr},hr{3-17} wd{mo-fr},hr{18}min{0-29} wd{mo-fr}
                        thour = (params.ampm === 'PM') ? PM[float2int(params.tohour)] : float2int(params.tohour);
                        fhour = (params.fromhourm === 'PM') ? PM[float2int(params.fromhour)] : float2int(params.fromhour);
                        if(isFloat(params.fromhour)){
                           h = "hr{"+fhour+"}min{30-59} wd{"+days+"},";
                           fhour = fhour+1;
                        }
                        if(isFloat(params.tohour)){
                           hh = "hr{"+thour+"}min{0-29} wd{"+days+"},";
                           thour = thour-1;
                        }

                        timee ="hr{"+fhour+"-"+thour+"} wd{"+days+"},";
                        timeframe+= h+""+timee+""+hh;
                        timeframe_desc+= "From "+params.fromhour+" "+params.fromhourm+" Until "+params.tohour+" "+params.ampm+" "+days+" OR ";
                     }
                    }
                   set_extensions(); 
                   picked_number_add(timeframe,timeframe_desc, inputkeep);
                   secondstepload.hide();
            }
            var finalerror = $("#finalerror");
            if($input.attr("id") == "fourthnext"){
              fourthstepload.show();
              if(callflow.val() =="recepIVR" || callflow.val() =="IVR"){
                 var ivr_form = $("#ivr_form");
                 var ivr_form_data = ivr_form.serialize();
                 var language = $("#lang").val();
                 var num = number.val();
                 end_session();
                 var data1 = 'action=set_opt&number='+num+'&lang='+language+'&i_customer='+i_customer.val()+'&callflow='+callflow.val()+"&"+ivr_form_data;
                 $.ajax({
                      type: 'POST',
                      url : path+'/fonexinc/functions.php',              
                      data: data1,
                      success: function(data){
                        if (data!='') {
                          finalerror.html(data);
                          finalerror.show();
                          fourthstepload.hide();
                          //Disable the button and test the user to go back #TODO
                          throw new Error();
                        }else{
                          finalerror.html(data);
                          finalerror.show();
                          fourthstepload.hide();
                        }
                      }
                  });
              }
              if(callflow.val() =="ringall"){
                var num = number.val();
                end_session();
                var data2 = 'action=set_opt&number='+num+'&i_customer='+i_customer.val()+'&callflow='+callflow.val();
                $.ajax({
                      type: 'POST',
                      url : path+'/fonexinc/functions.php',              
                      data: data2,
                      success: function(data){
                        if (data!='') {
                          finalerror.html(data);
                          finalerror.show();
                          fourthstepload.hide();
                          //Disable the button and test the user to go back #TODO
                          throw new Error();
                        }else{
                          finalerror.html(data);
                          finalerror.show();
                          fourthstepload.hide();
                        }
                      }
                  });
              } 
              if(callflow.val() == "cellphone") {
                var num = number.val();
                var cell = $("#inputcell").val().trim();
                end_session();
                var data3 = 'action=set_opt&number='+num+'&i_customer='+i_customer.val()+'&callflow='+callflow.val()+'&cellphone='+cell;
                $.ajax({
                      type: 'POST',
                      url : path+'/fonexinc/functions.php',              
                      data: data3,
                      success: function(data){
                        if (data!='') {
                          finalerror.html(data);
                          finalerror.show();
                          fourthstepload.hide();
                          //Disable the button and test the user to go back #TODO
                          throw new Error();
                        }else{
                          finalerror.html(data);
                          finalerror.show();
                          fourthstepload.hide();
                        }
                      }
                  });
              } 
              return false;
            }
            current_fs = $input.parent();
            next_fs = $input.parent().next();
            var animating = true;
            //activate next step on progressbar using the index of next_fs
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = (now * 50) + "%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;

                    next_fs.css({
                        'left': left,
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },

            });

        });

        $(".previous").click(function () {

            animating = true;

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();
            //de-activate current step on progressbar
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.hide();
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = ((1 - now) * 50) + "%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'left': left
                    });
                    previous_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                
            });
        });

    } 
    function  validate_form(form){
    var $inputs = $('#'+form+' :input');
    var f = false;
    $inputs.each(function() {
        if($(this).val() !==""){
            $(this).css({
              'border': '1px solid #d8e1e7'
            });
        }else{
            f = true;
            $(this).css({
              'border': '1px solid red'
            });
            $(this).focus();
        }
    });
    if (f) {
        firststepload.hide();
        throw new Error();
    }else{
        return true;
    }

}


function set_extensions(){
  var ua_choose = $("#ua_choose");
  var e = extension.val();
  var tr="";
  var allex="";
  for (var i = e - 1; i >= 0; i--) {
     allex+= '<option value="10'+i+'">Extension 10'+i+'</option>'; 
  }
  for (var i = e - 1; i >= 0; i--) {
    tr += '<tr><td><select  class="form-control" id="ua"><option value="none">None</option><option value="app">Mobile App</option><option value="y21p">Yealink 21P $20</option><option value="y22p">Yealink 22P $22</option><option value="gxpwave">Grandstream wave $34</option><option value="cisco">Cisco SPA201 $50</option><option value="pol">Polycom P21 $43</option></select></td><td></td><td><select  class="form-control allext">'+allex+'</select></td><td></td></tr>';
  }
  ua_choose.html(tr);

}

function recepivr(){
  var exts = extension.val();
  end_session();
  var data = 'action=add_number&number='+number.val()+'&i_customer='+i_customer.val()+'&exts='+exts+'&period='+tim+'&period_desc='+desc;
  $.ajax({
        type: 'POST',
        url : path+'/fonexinc/functions.php',              
        data: data,
        success: function(data){

                 diderror.hide();
        }
  });
}

       /*Function Calls*/  
    verificationForm ();
    
}); 


function get_numbers_by_areacode(){
  var area = $("#inputAreacode").val();
  didload.show();
  end_session();
  if(area !=="" && !isNaN(area)){
    var data = 'action=get_number_list&area='+area.trim();
    $.ajax({
        type: 'POST',
        url : path+'/fonexinc/functions.php',              
        data: data,
        success: function(data){
           didload.hide();
           numberContainer.html(data);
        }
    });
  }
}
function picked_number_add(timefram, timedesc, nu){
  var phone = (nu !== "") ? nu : number.val();
  var exts = extension.val();
  end_session();
  var tim = (timefram !== "") ? timefram.trim().substring(0, timefram.length - 1) : 'Always' ;
  var desc = (timefram !== "") ? timedesc.trim().substring(0, timedesc.length - 3) : 'Always' ;
  var data = 'action=add_number&number='+phone+'&i_customer='+i_customer.val()+'&exts='+exts+'&period='+tim+'&period_desc='+desc;
  $.ajax({
        type: 'POST',
        url : path+'/fonexinc/functions.php',              
        data: data,
        success: function(data){
           data = data.trim();
           diderror.html(data);
           if(data !==''){
                // diderror.show();
                 throw new Error();

           }else{
                 diderror.hide();
           }
        }
  });
}

$('input[name="country_code"]').change(function() {
    var hiddenValue = $(this).val();
    changecountrycode(hiddenValue);
});

function nice_Select(){
  if ( $('.product_select').length ){ 
      $('select').niceSelect();
  };
}; 
function isFloat(n) {
if( n.match(/^-?\d*(\.\d+)?$/) && !isNaN(parseFloat(n)) && (n%1!=0) ){
    return true;
  }else{
    return false;
  }
}
function float2int(value) {
  return value | 0;
}

function end_session(){
 // document.cookie = "PHPSESSID=;Path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
}