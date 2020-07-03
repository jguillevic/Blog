$(document).ready(function()
{
    function IsItemHidden(item)
    {
        return !$(item).hasClass("visible");
    }

    function HideItem(item, afterHide = null)
    {
        $(item).addClass("hidden");
        $(item).removeClass("visible");

        if (afterHide != null)
            afterHide(item);
    }

    function DisplayItem(item, afterDisplay = null)
    {
        $(item).addClass("visible");
        $(item).removeClass("hidden");

        if (afterDisplay != null)
            afterDisplay(item);
    }

    function ToggleVisibility(item, afterHide = null, afterDisplay = null)
    {
        if (item != null)
        {
            if (IsItemHidden(item))
                DisplayItem(item, afterDisplay);
            else
                HideItem(item, afterHide);
        }
    }

    function ToggleMenuItemWrapperVisibility(wrapper)
    {
        ToggleVisibility(
            wrapper
            , function(item)
            {
                var menuItem = $(item).closest(".nav__menu__item");
                menuItem.addClass("close");
                menuItem.removeClass("open");
            }
            , function(item)
            {
                var menuItem = $(item).closest(".nav__menu__item");
                menuItem.addClass("open");
                menuItem.removeClass("close");
            });
    }

    $(".nav__menu__item").click(function()
    {
        var wrapper = $(this).children(".nav__menu__sub-item__wrapper");

        if (wrapper != null)
            ToggleMenuItemWrapperVisibility(wrapper);
    });

    $("#avatar").click(function()
    {
        var tooltip = $("#avatar-tooltip");

        if (tooltip != null)
            ToggleVisibility(tooltip);
    });

    $(window).click(function(event)
    {
        var avatarTooltip = $("#avatar-tooltip");

        if (event.target.id != "avatar" && avatarTooltip != null && !IsItemHidden(avatarTooltip))
            ToggleVisibility(avatarTooltip);

        var wrappers = $(".nav__menu__sub-item__wrapper");
        if (wrappers != null)
        {
            $(wrappers).each(function()
            {
                if (!$(event.target).hasClass("nav__menu__item") && !IsItemHidden(this))
                    ToggleMenuItemWrapperVisibility(this);
            });
        }
    });

    $("#logout-link").click(function(event)
    {
        event.preventDefault(); // Empêche l'action par défaut.

        $.get(window.location.protocol + "//" + window.location.hostname + "/User/Login/Kind", function(data)
        {
            console.log(data);
            if (data === "WEBSITE")
                window.location.assign(window.location.protocol + "//" + window.location.hostname + "/User/Logout"); // Renvoi vers l'adresse correspondant à l'enregistrement.
            else if (data === "FACEBOOK")
            {
                FB.getLoginStatus(function(response)
                {
                    FB.logout(function(response)
                    {
                        window.location.assign(window.location.protocol + "//" + window.location.hostname + "/User/Logout"); // Renvoi vers l'adresse correspondant à l'enregistrement.
                    });
                });
            }
        });
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