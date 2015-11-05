/**
 * Created by paulb on 10/27/15.
 */

$(document).ready(function() {

    $(".page_button").on('click',function (  ) {


            var page_id = $( this ).attr( "show_page" );

            $(".screen-page").hide();

            $( "#" + page_id ).show();
        }
    );

});