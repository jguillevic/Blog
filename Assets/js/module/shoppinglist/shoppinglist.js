$(document).ready(function()
{
    $('[id^="shoppinglistitem"]').click(function() 
    {
        var id = $(this).attr('id');
        var split = id.split("_");
        var shoppingListItemId = split[2];
        var value = $(this).attr('checked') == "checked";

        $.ajax({ 
            url: "/ShoppingListItem/UpdateIsHandled"
            , method: "GET"
            , timeout: 1000
            , data: { Id: shoppingListItemId, Value: value }
            , success: function(data) {  }
            , error: function(err) { alert(err.alert(err.responseText)); }
        });
    });
});