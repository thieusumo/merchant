function formatPhone(obj) {
        var numbers = obj.value.replace(/\D/g, ''),
            char = {0:'(',3:') ',6:' - '};
        obj.value = '';
        for (var i = 0; i < numbers.length; i++) {
            obj.value += (char[i]||'') + numbers[i];
        }
    }
//formatDate 5/17/2019 4:55 pm
function formatDate(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'PM' : 'AM';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear() + " " + strTime;
}
function intVal( i ) {
    return typeof i === 'string' ?
        i.replace(/[\$,]/g, '')*1 :
        typeof i === 'number' ?
            i : 0;
};
(function($,sr){
    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/

    var debounce = function (func, threshold, execAsap) {
      var timeout;

        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
                if (!execAsap)
                    func.apply(obj, args); 
                timeout = null; 
            }

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100); 
        };
    };

    // smartresize 
    jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');
/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var randNum = function() {
    return (Math.floor(Math.random() * (1 + 40 - 20))) + 20;
};
var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.top_nav'),
    $FOOTER = $('footer');

    
    
// Sidebar
function init_sidebar() {
// TODO: This is some kind of easy fix, maybe we can improve this
var setContentHeight = function () {
    // reset height
    $RIGHT_COL.css('min-height', $(window).height()-84);

    var bodyHeight = $BODY.outerHeight(),
        footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
        leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
        contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

    // normalize content
    contentHeight -= $NAV_MENU.height() + (footerHeight?footerHeight:0);               
    $RIGHT_COL.css('min-height', contentHeight);
        
        if ($("#payservice")[0]) {
            $("#payservice").css('height', $(window).height()-($NAV_MENU.height()));
        }
        if ($(".fixLHeight")[0]) {
            $(".fixLHeight").css('height', $(window).height()-($NAV_MENU.height()));
        }
};

  $SIDEBAR_MENU.find('a').on('click', function(ev) {
        var $li = $(this).parent();

        if ($li.is('.active')) {
            $li.removeClass('active active-sm');
            $('ul:first', $li).slideUp(function() {
                setContentHeight();
            });
        } else {
            // prevent closing menu if we are on child menu
            if (!$li.parent().is('.child_menu')) {
                $SIDEBAR_MENU.find('li').removeClass('active active-sm');
                $SIDEBAR_MENU.find('li ul').slideUp();
            }else
            {
                if ( $BODY.is( ".nav-sm" ) )
                {
                    $li.parent().find( "li" ).removeClass( "active active-sm" );
                    $li.parent().find( "li ul" ).slideUp();
                }
            }
            $li.addClass('active');

            $('ul:first', $li).slideDown(function() {
                setContentHeight();
            });
        }
    });

// toggle small or large menu 
$MENU_TOGGLE.on('click', function() {
        console.log('clicked - menu toggle');
        console.log($BODY);
        if ($BODY.hasClass('nav-md')) {
            $SIDEBAR_MENU.find('li.active ul').hide();
            $SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
        } else {
            $SIDEBAR_MENU.find('li.active-sm ul').show();
            $SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
        }

    $BODY.toggleClass('nav-md nav-sm');

    setContentHeight();

    $('.dataTable').each ( function () { $(this).dataTable().fnDraw(); });
});

    // check active menu
    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('current-page');

    $SIDEBAR_MENU.find('a').filter(function () {
        return this.href == CURRENT_URL;
    }).parent('li').addClass('current-page').parents('ul').slideDown(function() {
        setContentHeight();
    }).parent().addClass('active');

    // recompute content when resizing
    $(window).smartresize(function(){  
        setContentHeight();
    });

    setContentHeight();

    // fixed sidebar
    if ($.fn.mCustomScrollbar) {
        $('.menu_fixed').mCustomScrollbar({
            autoHideScrollbar: true,
            theme: 'minimal',
            mouseWheel:{ preventDefault: true }
        });
    }
};
// /Sidebar

// Tooltip
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
});
// /Tooltip

// Progressbar
if ($(".progress .progress-bar")[0]) {
    $('.progress .progress-bar').progressbar();
}
// /Progressbar

// Accordion
$(document).ready(function() {
    $(".expand").on("click", function () {
        $(this).next().slideToggle(200);
        $expand = $(this).find(">:first-child");

        if ($expand.text() == "+") {
            $expand.text("-");
        } else {
            $expand.text("+");
        }
    });
});


    //hover and retain popover when on popover content
  var originalLeave = $.fn.popover.Constructor.prototype.leave;
  $.fn.popover.Constructor.prototype.leave = function(obj) {
    var self = obj instanceof this.constructor ?
      obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type);
    var container, timeout;

    originalLeave.call(this, obj);

    if (obj.currentTarget) {
      container = $(obj.currentTarget).siblings('.popover');
      timeout = self.timeout;
      container.one('mouseenter', function() {
        //We entered the actual popover – call off the dogs
        clearTimeout(timeout);
        //Let's monitor popover content instead
        container.one('mouseleave', function() {
          $.fn.popover.Constructor.prototype.leave.call(self, self);
        });
      });
    }
  };

  $('body').popover({
    selector: '[data-popover]',
    trigger: 'click hover',
    delay: {
      show: 50,
      hide: 400
    }
  });
function isNumberKey(evt)
{
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
        return false;
        return true;
}  
function isNumericKey(evt)
{
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
        return true;
        return false;
} 
$(document).ready(function() {   
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
         beforeSend: function() {
            NProgress.start();
            if(typeof(this) == 'object' && $(this).prop('tagName') == "INPUT"){
                $(this).prop("disabled",true);
            }                
        },
        complete: function(){
            NProgress.done();
            if(typeof(this) == 'object' && $(this).prop('tagName') == "INPUT"){
                $(this).prop("disabled",false);
            }
        }
     });
     init_sidebar();  

});                

if (typeof NProgress != 'undefined') {   
   NProgress.start();
}
$(window).on('load', function(){ 
    if (typeof NProgress != 'undefined') {   
        NProgress.done();
    }
});

// CHANGE PLACE ID IN HEADER MERCHANT
function change_place(place_id) {   
    $.ajax({  
        url:"/change-placeid",  
        method:"POST",  
        data:{place_id:place_id  },                              
           success: function( data ) {
            location.reload();
        }
   });
}

// ---- SCRIPT [SETUP COUPON] BEGIN ---------------------

var canvasReview = null;
var initCanvasTextEditor = function(canvas){
    $("#font-family").on( "change", function(event){
        $.each(canvas.getActiveObjects(), function( index, activeObject ) {
            activeObject.set("fontFamily", $("#font-family").val());
            canvas.renderAll();   
        });
    });
    $("#text-color").on( "change", function(event){
        $.each(canvas.getActiveObjects(), function( index, activeObject ) {
            activeObject.set("fill", $("#text-color").val());
            canvas.renderAll();   
        });
    });
    $("#btnAddText").on( "click", function(event){
         //add text to canvas
         canvas.add(new fabric.IText(' Your Text Here ', {left: 150, top: 150, fontSize: 30, deleteable : true }));
    });
    $("#btnDeleteText").on( "click", function(event){
        // delete text on canvas, only delete text (add from button addtext)
        $error = '';
        $.each(canvas.getActiveObjects(), function( index, activeObject ) {
            if(typeof(activeObject.deleteable) != 'undefined' && activeObject.deleteable == true){
                canvas.remove(activeObject);
              }else{
                $error = "Cannot delete the required text ";
              }
        });        
        if($error != ''){
            alert($error);
        }
    });
    $("#text-controls-additional a.btn").on( "click", function(event){   
        var dataEdit = $(this).attr("data-edit");
        $.each(canvas.getActiveObjects(), function( index, activeObject ) {
            if(typeof(activeObject ) != 'undefined'){
                switch(dataEdit){            
                    case "bold": activeObject.set("fontWeight",(activeObject["fontWeight"]=="bold"?"normal":"bold")); break;
                    case "italic": activeObject.set("fontStyle",(activeObject["fontStyle"]=="italic"?"normal":"italic")); break;
                    case "strikethrough": activeObject.set("linethrough",(activeObject["linethrough"]==true?false:true)); break;    
                    case "underline": activeObject.set("underline",(activeObject["underline"]==true?false:true)); break;        
                }         
                canvas.renderAll();   
            }
        });
    });
}
var initCanvasCoupon = function(){     
    canvasReview = new fabric.Canvas('canvasReview', {
        hoverCursor: 'pointer',
        preserveObjectStacking: true, 
        selection: true
    });
    canvasReview.add(new fabric.IText($("#coupon_code").val(), {id: "coupon_code", left: 40, top: 300, fontSize: 20, editable: false}));
    canvasReview.add(new fabric.IText($("#coupon_name").val(), {id: "coupon_name",  left: 40, top: 20, fontSize: 0}));
    canvasReview.add(new fabric.IText($("#coupon_title").val(), {id: "coupon_title",  left: 40, top: 30, fontSize: 40}));
    canvasReview.add(new fabric.IText($("#coupon_date_start").val(), {id: "coupon_date_start",  left: 40, top: 200, fontSize: 20, editable: false }));
    canvasReview.add(new fabric.IText(' \uf178 ', {id:"fontawesome",left: 140, top: 202, fontSize: 16, fontFamily: 'FontAwesome', editable: false}));
    canvasReview.add(new fabric.IText($("#coupon_date_end").val(), {id: "coupon_date_end",  left: 170, top: 200, fontSize: 20, editable: false }));    
    canvasReview.add(new fabric.IText('DISCOUNT', {id:"discount",left: 40, top: 140, fontSize: 30, fill: 'red'}));
    canvasReview.add(new fabric.IText($("#coupon_discount").val(), {id: "coupon_discount",  left: 210, top: 140, fontSize: 30, fill: 'red'}));    
    var coupon_discount_type  = $('input[name=coupon_discount_type]:checked').val();
    canvasReview.add(new fabric.IText(coupon_discount_type, {id: "coupon_discount_type",  left: 240, top: 140, fontSize: 30, fill: 'red', editable: false}));

    canvasReview.renderAll();

    canvasReview.on('text:changed', function(e) {
        if(typeof(e.target.id) != "undefined" && $("#" + e.target.id).length){
            $("#" + e.target.id).val(e.target.text);
        }
    });
    
    initCanvasTextEditor(canvasReview);
    
    $.each(['coupon_code','coupon_name','coupon_title','coupon_date_start', 'coupon_date_end', 'coupon_discount'], 
        function(i, objName){
        if($("#"+objName).length){    
            $("#"+objName).on('change keyup input', function(e) {  
                 var $elementInput = e.target;                 
                 canvasReview.getObjects().forEach(function(obj) {                     
                    if(typeof(obj.id) != 'undefined' && obj.id == objName) {                        
                        canvasReview.setActiveObject(obj);
                        obj.setText($($elementInput).val());
                        canvasReview.renderAll();                        
                        return false;  
                    }
                 });
            }); 
        }
    });
    $('input[name=coupon_discount_type]').on( "change", function(e){
         var $elementInput = e.target;                 
         canvasReview.getObjects().forEach(function(obj) {                     
           if(typeof(obj.id) != 'undefined' && obj.id == 'coupon_discount_type') {                        
               canvasReview.setActiveObject(obj);
               obj.setText($($elementInput).val());
               canvasReview.renderAll();                        
               return false;  
           }
        });
    });
    var dem=0;
    // $(document).on('change','#coupon_for',function(){
    // // alert($("#coupon_for option:selected").text());
    // demdelete=dem;
    //         dem=dem+1;
    //         canvasReview.add(new fabric.IText($("#coupon_for option:selected").text(), {id: "coupon_service"+dem, left: 40, top: 260, fontSize: 20, editable: false}));
    //         if(dem>=2)
    //         {
    //             canvasReview.getObjects().forEach(function(obj) {   
    //             if(typeof(obj.id) != 'undefined' && obj.id == "coupon_service"+demdelete) {                        
    //                         canvasReview.setActiveObject(obj);
    //                         obj.setText("");
    //                         canvasReview.renderAll();                        
    //                         return false;  
    //                     }
    //             });
    //             canvasReview.add(new fabric.IText($("#coupon_for option:selected").text(), {id: "coupon_service"+dem, left: 40, top: 260, fontSize: 20, editable: false}));
    //         }
    // });
    $("#coupon_date_start, #coupon_date_end").focusout(function() {
        var coupon_date_start=$("#coupon_date_start").val();
        var coupon_date_end=$("#coupon_date_end").val();
        if(coupon_date_start==coupon_date_end)
        {
            canvasReview.getObjects().forEach(function(obj) {                     
                        if(obj.id == "coupon_date_end"){                        
                            canvasReview.setActiveObject(obj);
                            obj.set({
                                top:3000,
                                text:$("#coupon_date_end").val(),
                            });
                            canvasReview.renderAll();                        
                            return false;  
                        }

                        if(obj.id=="fontawesome"){                        
                            canvasReview.setActiveObject(obj);
                            obj.set({
                                top:3000,
                            });
                            canvasReview.renderAll();                        
                            return false;  
                        }  
            });
        }
        else
        {
            canvasReview.getObjects().forEach(function(obj) {                     
                        if(obj.id == "coupon_date_end"){                        
                            canvasReview.setActiveObject(obj);
                            obj.set({
                                top:200,
                                text:$("#coupon_date_end").val(),
                            });
                            canvasReview.renderAll();                        
                            return false;  
                        }
                        if(obj.id=="fontawesome"){                        
                            canvasReview.setActiveObject(obj);
                            obj.set({
                                top:202,
                            });
                            canvasReview.renderAll();                        
                            return false;  
                        }  
                        
            });
        }
      });

        //change check all coupon
    // $(document).on("change","#check_all",function(){
    //     var check=$('#check_all').prop("checked");
    //     // alert(check);
    //     if(check==true)
    //     {
    //         canvasReview.add(new fabric.IText("All services", {id: "check_all", left: 40, top: 260, fontSize: 20, editable: false}));
    //         canvasReview.getObjects().forEach(function(obj) {   
    //             if(typeof(obj.id) != 'undefined' && obj.id == "") {                        
    //                         canvasReview.setActiveObject(obj);
    //                         obj.setText("");
    //                         canvasReview.renderAll();                        
    //                         return false;  
    //                     }
    //             });
    //     }
    //     else
    //     {
    //         canvasReview.getObjects().forEach(function(obj) {   
    //             if(typeof(obj.id) != 'undefined' && obj.id == "check_all") {                        
    //                         canvasReview.setActiveObject(obj);
    //                         obj.setText("");
    //                         canvasReview.renderAll();                        
    //                         return false;  
    //                     }
    //             });
    //     }
    // });
    //     //end

    // var str="";
    // var arr=[""];
    // var top=40;
    // var topDefault=40;
    // var dem=0;
    // $('input[name^="coupon_list_service"]').on( "change", function(e){
    //     var check=$(this).prop('checked');
    //     var id=$(this).attr("id");
    //     if(check==true)
    //     {
    //         console.log(1);
    //         // dem=dem+1;
    //         top=top+25;
    //         canvasReview.add(new fabric.IText($(this).attr("data"), {id: top, left: 300, top: top, fontSize: 20, editable: false}));
    //     }
    //     if(check==false)
    //     {
    //         //delete checkall
    //         canvasReview.getObjects().forEach(function(obj) {   
    //             if(typeof(obj.id) != 'undefined' && obj.id == "check_all") {                        
    //                         canvasReview.setActiveObject(obj);
    //                         obj.setText("");
    //                         canvasReview.renderAll();                        
    //                         return false;  
    //                     }
    //             });
    //         //end
    //         canvasReview.getObjects().forEach(function(obj) {   
    //             if(typeof(obj.id) != 'undefined' && obj.id == top) {                        
    //                 canvasReview.setActiveObject(obj);
    //                 obj.setText("");
    //                 canvasReview.renderAll();                        
    //                 return false;  
    //             }
    //         });
    //         console.log(top);
    //         if(top>=200)
    //         {
    //             top=topDefault-20;
    //         }
    //     }
    // });

    /////////////////////////////////////////////////////////////////////
    var str="";
    var arr=[""];
    var top=200;
    var topDefault=200;
    $('input[name^="coupon_list_service"]').on( "change", function(e){
        var e=e;
        var id=$(this).attr('id');
        var check=$(this).prop('checked');
        var discount_type  = $(this).attr('data');
        var element= $(this).attr('name');
        var oneData="";
        canVasListService(element,check,discount_type,oneData,e,id);
        if(check==false)
        {
            $('input[type=checkbox]').not(this).prop('checked','');
        }
    });

    $('input[name^="cateservice_check_all"]').on( "change", function(e){
        var e=e;
        var check=$(this).prop('checked');
        var element= $(this).attr('name');
        var data="";
        var oneData="All Services";
        var id='checkall';
        canVasListService(element,check,data,oneData,e,id);
    });

    $('input[name^="cateservice_check"]').on( "change", function(e){
        var e=e;
        var check=$(this).prop('checked');
        var element= $(this).attr('name');
        var data="";
        var oneData=$(this).attr('cate');
        var id=$(this).attr('id');
        canVasListService(element,check,data,oneData,e,id);
        if(check==false)
        {
            $('input[type=checkbox]').not(this).prop('checked','');
        }
    });
    


    function canVasListService(element,checkprop, data, oneData,e,idelm)
    {
        var check=checkprop;
        var discount_type = data;
        console.log(element);
        if(discount_type=="")
        {
            discount_type= oneData;
        }

        if(check==true)
        {
            top+=20;
            if(element=="cateservice_check_all")
            {
                top=220;
            }
            canvasReview.add(new fabric.IText(discount_type, {id: idelm,  left: 40, top: top, fontSize: 20, editable: false}));
            canvasReview.renderAll();
            if(element=="cateservice_check_all")
                {
                    top=220;
                    canvasReview.getObjects().forEach(function(obj) {                     
                        if(typeof(obj.id) != 'undefined' && obj.id != "checkall" && obj.id != "coupon_code" && obj.id != "coupon_name" && obj.id != "coupon_title" && obj.id != "coupon_date_start" && obj.id != "fontawesome" && obj.id != "coupon_date_end" && obj.id != "discount" && obj.id != "coupon_discount" && obj.id != "coupon_discount_type") {                        
                            canvasReview.setActiveObject(obj);
                            obj.setText("");
                            canvasReview.renderAll();                        
                            return false;  
                        }
                    });
                }
                if(element=="cateservice_check")
                {
                    // top=topDefault;
                    // canvasReview.getObjects().forEach(function(obj) {                     
                    //     if(typeof(obj.id) != 'undefined' && obj.id != idelm && obj.id != "promotion_name" && obj.id != "promotion_date_start" && obj.id != "promotion_date_end" && obj.id != "promotion_time_start" && obj.id != "promotion_time_end" && obj.id != "promotion_discount" && obj.id != "promotion_discount_type") {                        
                    //         canvasReview.setActiveObject(obj);
                    //         obj.setText("");
                    //         canvasReview.renderAll();                        
                    //         return false;  
                    //     }
                    // });
                }
        }
        else{
            top=topDefault;
            // var $elementInput = e.target;                 
                canvasReview.getObjects().forEach(function(obj) {                     
                    if(typeof(obj.id) != 'undefined' && obj.id == idelm) {                        
                       canvasReview.setActiveObject(obj);
                       obj.setText("");
                       canvasReview.renderAll();                    
                    return false;  
                }
                canvasReview.getObjects().forEach(function(obj) {                     
                    if(typeof(obj.id) != 'undefined' && obj.id == "checkall") 
                    {                        
                       canvasReview.setActiveObject(obj);
                       obj.setText("");
                       canvasReview.renderAll();                        
                       return false;
                    }
                });
                // var array=['promotion_name', 'promotion_date_start', 'promotion_date_end',  'promotion_time_start', 'promotion_time_end', 'promotion_discount'];
                if(element=="coupon_list_service[]")
                {
                    canvasReview.getObjects().forEach(function(obj) {                     
                        if(typeof(obj.id) != 'undefined' && obj.id != "checkall" && obj.id != "coupon_code" && obj.id != "coupon_name" && obj.id != "coupon_title" && obj.id != "coupon_date_start" && obj.id != "fontawesome" && obj.id != "coupon_date_end" && obj.id != "discount" && obj.id != "coupon_discount" && obj.id != "coupon_discount_type") {                        
                            canvasReview.setActiveObject(obj);
                            obj.setText("");
                            canvasReview.renderAll();                        
                            return false;  
                        }
                    });
                }
                if(element=="cateservice_check")
                {
                    canvasReview.getObjects().forEach(function(obj) {                     
                        if(typeof(obj.id) != 'undefined' && obj.id != "checkall" && obj.id != "coupon_code" && obj.id != "coupon_name" && obj.id != "coupon_title" && obj.id != "coupon_date_start" && obj.id != "fontawesome" && obj.id != "coupon_date_end" && obj.id != "discount" && obj.id != "coupon_discount" && obj.id != "coupon_discount_type") {                        
                            canvasReview.setActiveObject(obj);
                            obj.setText("");
                            canvasReview.renderAll();                        
                            return false;  
                        }
                    });
                }
                
            });
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////////

    return canvasReview;

}

// ---- SCRIPT [SETUP PROMOTION] BEGIN ---------------------
var initCanvasPromotion = function(){     
    canvasReview = new fabric.Canvas('canvasReview', {
        hoverCursor: 'pointer',
        preserveObjectStacking: true, 
        selection: true
    });

    canvasReview.add(new fabric.IText($("#promotion_name").val(), {id: "promotion_name",  left: 40, top: 20, fontSize: 40}));
    canvasReview.add(new fabric.IText($("#promotion_date_start").val(), {id: "promotion_date_start",  left: 40, top: 100, fontSize: 20, editable: false }));
    canvasReview.add(new fabric.IText(' \uf178 ', {id:'fontawesome1',left: 140, top: 102, fontSize: 16, fontFamily: 'FontAwesome', editable: false}));
    canvasReview.add(new fabric.IText($("#promotion_date_end").val(), {id: "promotion_date_end",  left: 170, top: 100, fontSize: 20, editable: false }));    
    
    canvasReview.add(new fabric.IText($("#promotion_time_start").val(), {id: "promotion_time_start",  left: 40, top: 200, fontSize: 20, editable: false }));
    canvasReview.add(new fabric.IText(' \uf178 ', {id:'fontawesome',left: 140, top: 202, fontSize: 16, fontFamily: 'FontAwesome', editable: false}));
    canvasReview.add(new fabric.IText($("#promotion_time_end").val(), {id: "promotion_time_end",  left: 170, top: 200, fontSize: 20, editable: false }));    

    canvasReview.add(new fabric.IText('DISCOUNT', {left: 40, top: 140, fontSize: 30, fill: 'red'}));
    canvasReview.add(new fabric.IText($("#promotion_discount").val(), {id: "promotion_discount",  left: 210, top: 140, fontSize: 30, fill: 'red'}));    
    var discount_type  = $('input[name=promotion_discount_type]:checked').val();
    canvasReview.add(new fabric.IText(discount_type, {id: "promotion_discount_type",  left: 240, top: 140, fontSize: 30, fill: 'red', editable: false}));

    

    canvasReview.renderAll(); 

    canvasReview.on('text:changed', function(e) {
        if(typeof(e.target.id) != "undefined" && $("#" + e.target.id).length){
            $("#" + e.target.id).val(e.target.text);
        }
    });

    initCanvasTextEditor(canvasReview);
    
    $.each(['promotion_name', 'promotion_date_start', 'promotion_date_end',  'promotion_time_start', 'promotion_time_end', 'promotion_discount'], 
        function(i, objName){
        if($("#"+objName).length){    
            $("#"+objName).on('change keyup input', function(e) {  
                 var $elementInput = e.target;                 
                 canvasReview.getObjects().forEach(function(obj) {                     
                    if(typeof(obj.id) != 'undefined' && obj.id == objName) {                        
                        canvasReview.setActiveObject(obj);
                        obj.setText($($elementInput).val());
                        canvasReview.renderAll();                        
                        return false;  
                    }
                 });
            }); 
        }
    });
    $('input[name=promotion_discount_type]').on( "change", function(e){
         var $elementInput = e.target;                 
         canvasReview.getObjects().forEach(function(obj) {                     
           if(typeof(obj.id) != 'undefined' && obj.id == 'promotion_discount_type') {                        
               canvasReview.setActiveObject(obj);
               obj.setText($($elementInput).val());
               canvasReview.renderAll();                        
               return false;
           }
        });
    });


    $(document).on('click','#allday',function(){
         canvasReview.getObjects().forEach(function(obj) {                     
                        if(obj.id == "promotion_time_start" || obj.id == "promotion_time_end" || obj.id=="fontawesome"){                        
                            // canvasReview.setActiveObject(obj);
                            obj.set({
                                top:3000,

                            });
                            canvasReview.renderAll();                        
                            return false;  
                        }
                        
            });
    });

    $(document).on('click','#previous',function(){
         canvasReview.getObjects().forEach(function(obj) {                     
                        if(obj.id == "promotion_time_start" || obj.id == "promotion_time_end" || obj.id=="fontawesome"){                        
                            obj.set({
                                top:200,
                            });
                            canvasReview.renderAll();                        
                            return false; 
                        }
                       
            });
    });


    $("#promotion_date_start,#promotion_date_end").focusout(function() {
        var promotion_date_start=$("#promotion_date_start").val();
        var promotion_date_end=$("#promotion_date_end").val();
        if(promotion_date_start==promotion_date_end)
        {
            canvasReview.getObjects().forEach(function(obj) {                     
                        if(obj.id == "promotion_date_end"){                        
                            // canvasReview.setActiveObject(obj);
                            obj.set({
                                top:3000,
                                text:$("#promotion_date_end").val(),
                            });
                            canvasReview.renderAll();                        
                            return false;  
                        }
                        if(obj.id=="fontawesome1"){                        
                            // canvasReview.setActiveObject(obj);
                            obj.set({
                                top:3000,
                            });
                            canvasReview.renderAll();                        
                            return false;  
                        }
                        
            });
        }
        else
        {
            canvasReview.getObjects().forEach(function(obj) {                     
                        if(obj.id == "promotion_date_end"){                        
                            // canvasReview.setActiveObject(obj);
                            obj.set({
                                top:100,
                                text:$("#promotion_date_end").val(),
                            });
                            canvasReview.renderAll();                        
                            return false;
                        }
                        if(obj.id=="fontawesome1"){                        
                            // canvasReview.setActiveObject(obj);
                            obj.set({
                                top:100,
                            });
                            canvasReview.renderAll();                        
                            return false;  
                        }
                        
            });
        }
      })


    var str="";
    var arr=[""];
    var top=200;
    var topDefault=200;
    $('input[name^="promotion_list_service"]').on( "change", function(e){
        var e=e;
        var id=$(this).attr('id');
        var check=$(this).prop('checked');
        var discount_type  = $(this).attr('data');
        var element= $(this).attr('name');
        var oneData="";
        canVasListService(element,check,discount_type,oneData,e,id);
        if(check==false)
        {
            $('input[type=checkbox]').not(this).prop('checked','');
        }
    });

    $('input[name^="cateservice_check_all"]').on( "change", function(e){
        var e=e;
        var check=$(this).prop('checked');
        var element= $(this).attr('name');
        var data="";
        var oneData="All Services";
        var id='checkall';
        canVasListService(element,check,data,oneData,e,id);
    });

    $('input[name^="cateservice_check"]').on( "change", function(e){
        var e=e;
        var check=$(this).prop('checked');
        var element= $(this).attr('name');
        var data="";
        var oneData=$(this).attr('cate');
        var id=$(this).attr('id');
        canVasListService(element,check,data,oneData,e,id);
        if(check==false)
        {
            $('input[type=checkbox]').not(this).prop('checked','');
        }
    });
    


    function canVasListService(element,checkprop, data, oneData,e,idelm)
    {
        var check=checkprop;
        var discount_type = data;
        console.log(element);
        if(discount_type=="")
        {
            discount_type= oneData;
        }

        if(check==true)
        {
            top+=20;
            if(element=="cateservice_check_all")
            {
                top=220;
            }
            canvasReview.add(new fabric.IText(discount_type, {id: idelm,  left: 40, top: top, fontSize: 20, editable: false}));
            canvasReview.renderAll();
            if(element=="cateservice_check_all")
                {
                    top=220;
                    canvasReview.getObjects().forEach(function(obj) {                     
                        if(typeof(obj.id) != 'undefined' && obj.id != "checkall" && obj.id != "promotion_name" && obj.id != "promotion_date_start" && obj.id != "promotion_date_end" && obj.id != "promotion_time_start" && obj.id != "promotion_time_end" && obj.id != "promotion_discount" && obj.id != "promotion_discount_type") {                        
                            canvasReview.setActiveObject(obj);
                            obj.setText("");
                            canvasReview.renderAll();                        
                            return false;  
                        }
                    });
                }
                if(element=="cateservice_check")
                {
                    // top=topDefault;
                    // canvasReview.getObjects().forEach(function(obj) {                     
                    //     if(typeof(obj.id) != 'undefined' && obj.id != idelm && obj.id != "promotion_name" && obj.id != "promotion_date_start" && obj.id != "promotion_date_end" && obj.id != "promotion_time_start" && obj.id != "promotion_time_end" && obj.id != "promotion_discount" && obj.id != "promotion_discount_type") {                        
                    //         canvasReview.setActiveObject(obj);
                    //         obj.setText("");
                    //         canvasReview.renderAll();                        
                    //         return false;  
                    //     }
                    // });
                }
        }
        else{
            top=topDefault;
            // var $elementInput = e.target;                 
                canvasReview.getObjects().forEach(function(obj) {                     
                    if(typeof(obj.id) != 'undefined' && obj.id == idelm) {                        
                       canvasReview.setActiveObject(obj);
                       obj.setText("");
                       canvasReview.renderAll();                    
                    return false;  
                }
                canvasReview.getObjects().forEach(function(obj) {                     
                    if(typeof(obj.id) != 'undefined' && obj.id == "checkall") 
                    {                        
                       canvasReview.setActiveObject(obj);
                       obj.setText("");
                       canvasReview.renderAll();                        
                       return false;
                    }
                });
                // var array=['promotion_name', 'promotion_date_start', 'promotion_date_end',  'promotion_time_start', 'promotion_time_end', 'promotion_discount'];
                if(element=="promotion_list_service[]")
                {
                    canvasReview.getObjects().forEach(function(obj) {                     
                        if(typeof(obj.id) != 'undefined' && obj.id != "promotion_name" && obj.id != "promotion_date_start" && obj.id != "promotion_date_end" && obj.id != "promotion_time_start" && obj.id != "promotion_time_end" && obj.id != "promotion_discount" && obj.id != "promotion_discount_type") {                        
                            canvasReview.setActiveObject(obj);
                            obj.setText("");
                            canvasReview.renderAll();                        
                            return false;  
                        }
                    });
                }
                if(element=="cateservice_check")
                {
                    canvasReview.getObjects().forEach(function(obj) {                     
                        if(typeof(obj.id) != 'undefined' && obj.id != "promotion_name" && obj.id != "promotion_date_start" && obj.id != "promotion_date_end" && obj.id != "promotion_time_start" && obj.id != "promotion_time_end" && obj.id != "promotion_discount" && obj.id != "promotion_discount_type") {                        
                            canvasReview.setActiveObject(obj);
                            obj.setText("");
                            canvasReview.renderAll();                        
                            return false;  
                        }
                    });
                }
                
            });
        }
    }

    return canvasReview;
}

// ---- SCRIPT [SETUP COUPON] END ---------------------