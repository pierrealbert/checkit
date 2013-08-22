////////////////////////////////////////
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
    if(responseDataJson.redirectUrl){
        window.location.href = responseDataJson.redirectUrl;
    }
    return responseDataJson;
}
/**
 * this is function calls if we click button share 
 * @param {type} url - string
 * @param {type} data  - string
 * @param {type} additionParameter - object
 * @returns {undefined|share.responseData}
 */
function shareClicked(url, data, additionParameter)
{
    var responseDataJson;
    var responseDataObj;
    responseDataJson = getAjaxData(url, data, additionParameter);
    responseDataObj = $.parseJSON(responseDataJson);
    return responseDataObj;
}
/**
 * this is function calls if we click button apply 
 * @param {type} url - string
 * @param {type} data  - string
 * @param {type} additionParameter - object
 * @returns {undefined|share.responseData}
 */
function applyClicked(url, data, additionParameter)
{
    var responseDataJson;
    var responseDataObj;
    responseDataJson = getAjaxData(url, data, additionParameter);
    responseDataObj = $.parseJSON(responseDataJson);
    return responseDataObj;
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
    // console.log(status);
     //console.log($.parseJSON( error.responseText ));
}
//description is the same like for addToBookmarkError
function shareError( x, status, error)
{
     console.log('ajax error when we click share ');
    // console.log(status);
    // console.log(error);
}
//description is the same like for addToBookmarkError
function applyError( x, status, error)
{
     console.log('ajax error when we click share ');
    // console.log(status);
    // console.log(error);
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