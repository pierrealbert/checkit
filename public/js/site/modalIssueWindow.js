$(function() {
     var issueText = $( "#issueText" );
     var subject_id;
     var user_id = $( "#user_id" );
     var property_id = $( "#property_id" );
     var subject = $( "#subject" );
     var allFields = $( [] ).add( issueText ).add( user_id ).add( property_id ).add( subject );
     var tips = $( ".validateTips" );
 
    function updateTips( t ) {    
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    }
 
    function checkLength( o, n, min, max ) {
      if ( o.val().length > max || o.val().length < min ) {
        o.addClass( "ui-state-error" );
        updateTips( "Length of " + n + " must be between " +
          min + " and " + max + "." );
        return false;
      } else {
        return true;
      }
    }
 
    function checkRegexp( o, regexp, n ) {
      if ( !( regexp.test( o.val() ) ) ) {
        o.addClass( "ui-state-error" );
        updateTips( n );
        return false;
      } else {
        return true;
      }
    }
 
    $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "Send": function() {
          var bValid = true;
          allFields.removeClass( "ui-state-error" );
          var newText = $.trim(issueText.val());
          bValid = bValid && newText.length;
 
 
          if ( bValid ) {
             
            //ajax
            var form = $(this).find('form#issue_pop_window_form');
            
            var response =  propertyPage.submitPopWindow(form);
  
            if(response.error.length) {
                //if we have an error
                 updateTips( response.error );             
            } else {
                // if everything is okay
                $( this ).dialog( "close" );
                //show message from server
                $('#formessage').text(response.result);
            }
    
          }
          else {
                updateTips( window.errorMsg1);
          }
              
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      close: function() {
        allFields.val( "" ).removeClass( "ui-state-error" );
      }
    });
      
    $(".create_issue").on("click", "a.menu_element", function(event){
        subject_id = $(this).data('number');
       $( "#dialog-form" ).dialog( "open" );
       var currentPropertyinput = $('#property_id');
       if( ! currentPropertyinput.val()){
           currentPropertyinput.val(window.currentpropertyId);
       }
       //set subject to inout
       $('#subject_id').val(subject_id);
    });
    
});
