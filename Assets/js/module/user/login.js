$(document).ready(function()
{
    // Sur le clic du bouton d'inscription.
    $("#register-button").click(function(event)
    {
        event.preventDefault(); // Empêche l'action par défaut.

        window.location.assign(window.location.protocol + "//" + window.location.hostname + "/User/Register"); // Renvoi vers l'adresse correspondant à l'enregistrement.
    });

    // Sur le clic du bouton de connexion avec Facebook.
    $("#facebook-login-button").click(function(event)
    {
        event.preventDefault(); // Empêche l'action par défaut.

        FB.login(function(response) 
        {
            if (response.status === "connected") 
            {
                window.location.assign(window.location.protocol + "//" + window.location.hostname + "/User/Login/Facebook");
            } 
            else 
            {
              // The person is not logged into your webpage or we are unable to tell. 
            }
          }
          , { scope: "public_profile,email,user_birthday" });
    });

    // Sur le changement du login.
    $("#input-login").on("input", function()
    {
        $(this).removeClass("error");

        $("#input-login-errors").remove();
    });

    // Sur le changement du password.
    $("#input-password").on("input", function()
    {
        $(this).removeClass("error");

        $("#input-password-errors").remove();
    });

    window.fbAsyncInit = function() 
    {
        FB.init({
          appId: "2446860912259803"
          , autoLogAppEvents: true
          , xfbml: true
          , cookie: true // Active les cookies pour permettre au serveur d'accéder à la session.
          , version: "v4.0"
        });
    };

    (function(d, s, id) 
    {   
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/fr_FR/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, "script", "facebook-jssdk"));
});