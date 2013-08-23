
var propertyPage = {

    //when you click to some message theme for sending to admin in future
    showPopWindow : function (elem){
        var self = this;
        //inset to input with type = hidden value - nameof issue
        var titleIssue = $(elem).text();
        //show form 
        var divWithForm =   $('#issue_pop_window');
        divWithForm.show();
        //set value to that input with type = gidden
        $('input[name=issueName]').val(titleIssue);
        //add ajax to this form 
        var form = $('#issue_pop_window_form');
        form.submit(function() {
          
          var response =  propertyPage.submitPopWindow(form);
          if(response.error.length) {
              //if we have an error
               self.alertError(response.error); 
          } else {
              // if everything is okay
              //show message and  hide foem 
              self.alertSuccessMsg(response.result)
              divWithForm.hide();
          }
          return false;
        });
        
    },
    //when you click send message to admin    
    submitPopWindow : function (form){
        var self = this;
        //create ajax 
        var url = form.attr('action');       
        var additionParameter = new Object();
        var data = 'issueText='+$('textarea[name=issueText]').val();;
        var additionParameter = getAjaxData(url, data, additionParameter);      
        return additionParameter;
    },
    //show error message to screen
    alertError : function (msg)
    {
        if($.type( msg ) === "string") {
              alert(msg);   
        }
    },
    //show successful message to screen
    alertSuccessMsg : function (msg)
    {
        if($.type( msg ) === "string") {
              alert(msg);   
        }
       
    },
        
    
}


//////////////////////////////////////////
/////  THE MAIN AJAX REQUEST   //////
/////////////////////////////////////

/**
 * this is function calls if  we click button add_to_bookmark and ajax retuned  valid data
 * @param {type} url - string
 * @param {type} data  - string
 * @param {type} additionParameter - object
 * @returns {undefined|addToBookmark.responseData}
 */
function addToBookmarkClicked(url, data, additionParameter)
{  
    var responseDataJson;
    responseDataJson = getAjaxData(url, data, additionParameter);
    if(responseDataJson.error.length) {
          //exist error message
          propertyPage.alertError(responseDataJson.error);
    }
    //show message if everything is okay
    propertyPage.alertSuccessMsg(responseDataJson.result);    
    return responseDataJson;
}

//////////////////////////////////////
/////  ERROR AJAX HANDLERING    //////
/////////////////////////////////////

/**
 * this is our own error handler for error when we click to button add_to_bookmark
 * @param {type} x
 * @param {type} status
 * @param {type} error
 * @returns 
 */
function addToBookmarkError( x, status, error)
{
     console.log('ajax error when we click buttons add to bookmark');
}



////////////////////////////////////////////////
/////  FUNCTIONS FOR ALL BELOW FUNCTIONS  //////
////////////////////////////////////////////////

/**
 * 
 * @param {type} url - string
 * @param {type} data - string
 * @param {type} additionParameter - my own addition parameter for ajax 
 * @returns object from server
 */
function getAjaxData(url, data, additionParameter)
{
    var myResponse ;
    $.ajax({
        url: url,
        data: data,
        dataType: additionParameter.dataType || 'json',
        type: additionParameter.type || "POST",
        async: additionParameter.async || false,
        success: function( response ) {
            //redirect if we tried to call some function whitch we donot have access
            if(response.redirectUrl){
                window.location.href = response.redirectUrl;
            }
            myResponse = response;
        },
        error: function(x, status, error)
        {          
            //if we have own error handler we will call it
           if(additionParameter.errorHandler ) {
                var myerror = error;
                var mystatus = status;
                var myx = x;
                additionParameter.errorHandler( myx, mystatus, myerror) ;
           } else {
                console.log(error);
           }
  
        }
       
      });
      return myResponse;
}