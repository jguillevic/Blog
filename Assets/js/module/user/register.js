$(document).ready(function()
{
    // Sur le changement du login.
    $("#input-login").on("input", function()
    {
        $(this).removeClass("error");

        $("#input-login-errors").remove();
    });

    // Sur le changement de l'email.
    $("#input-email").on("input", function()
    {
        $(this).removeClass("error");

        $("#input-email-errors").remove();
    });

    // Sur le changement du password.
    $("#input-password").on("input", function()
    {
        $(this).removeClass("error");

        $("#input-password-errors").remove();
    });
});