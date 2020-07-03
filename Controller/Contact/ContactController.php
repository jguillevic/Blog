<?php

namespace Controller\Contact;

use Framework\View\View;
use Framework\Tools\Helper\PathHelper;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Error\ErrorManager;
use BLL\Contact\ContactBLL;
use Model\Contact\Contact;

class ContactController
{
    public function Display($queryParameters)
    {
        try
        {
            $path = PathHelper::GetPath([ "Contact", "Display" ]);
            $view = new View($path);

            return $view->Render();
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function Send($queryParameters)
    {
        try
        {
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $contact = new Contact();
                $contact->SetFirstName($queryParameters["firstname"]->GetValue());
                $contact->SetLastName($queryParameters["lastname"]->GetValue());
                $contact->SetEmail($queryParameters["email"]->GetValue());
                $contact->SetContent($queryParameters["content"]->GetValue());

                $contactBLL = new ContactBLL();
                $contactBLL->Add([ $contact ]);

                $path = PathHelper::GetPath([ "Contact", "Send" ]);
                $view = new View($path);

                return $view->Render();
            }
            else
                RoutesHelper::Redirect("DisplayHome");
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }
}