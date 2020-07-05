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
});