<?php

namespace Controller\Post;

use BLL\Post\PostBLL;
use Model\Post\Post;
use Model\Post\PostFilter;
use Model\History\History;
use Model\Admin\User\User;
use Framework\View\View;
use Framework\Tools\Helper\PathHelper;
use Framework\Tools\Helper\SlugHelper;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Controller\Violation\ViolationManager;
use Tools\Helper\UserHelper;

class PostController
{
    public function DisplayAllAsTable(array $queryParameters)
	{
		try
        {
			// if (UserHelper::IsLogin())
			// {
			//	$postBLL = new PostBLL();
			//	$posts = $postBLL->Load(new PostFilter());

				$posts = [];
				$post1 = new Post();
				$post1->SetId(1);
				$post1->SetSlug("salut-les-mecs");
				$post1->SetTitle("1er article !");
				$post1->SetDescription("Description la plus courte du monde !!!");
				$post1->SetContent("Le contenu n'est pas en reste au niveau de la longueur !");
				$creationHistory1 = new History();
				$creationHistory1->SetId(1);
				$creationHistory1->SetDateTime(new \DateTime("2020-07-06 14:00:00"));
				$user1 = new User();
				$user1->SetId(1);
				$user1->SetLogin("Khany");
				$creationHistory1->SetUser($user1);
				$post1->SetCreationHistory($creationHistory1);

				$posts[] = $post1;

				$path = PathHelper::GetPath([ "Post", "DisplayAllAsTable" ]);
				$view = new View($path);
				
				return $view->Render([ "Posts" => $posts ]);
			// }
			// else
			// 	RoutesHelper::Redirect("DisplayHome");

		}
		catch (\Exception $e)
		{
			ErrorManager::Manage($e);
		}
	}

	public function Add(array $queryParameters)
	{
		try
        {
			// if (UserHelper::IsLogin())
			// {
				$post = new Post();
				$violations = new ViolationManager();

				if ($_SERVER["REQUEST_METHOD"] == "GET")
				{
					
				}
				else if ($_SERVER["REQUEST_METHOD"] == "POST")
				{
					$post->SetTitle($queryParameters["title"]->GetValue());
					$slug = SlugHelper::Slugify($post->GetTitle());
					$post->SetSlug($slug);
					$post->SetDescription($queryParameters["description"]->GetValue());
					$post->SetContent($queryParameters["content"]->GetValue());
					$post->SetIsPublished(array_key_exists("is_published", $queryParameters) ? $queryParameters["is_published"]->GetValue() : false);

					$creationHistory = new History();
					$creationHistory->SetDateTime(new \DateTime());
					//$user = UserHelper::GetUser();
					$user = new User();
					$user->SetId(1);
					$user->SetLogin("Khany");
					$creationHistory->SetUser($user);
					$post->SetCreationHistory($creationHistory);

					// $postBLL = new PostBLL();
					// $postBLL->Add([ $post ]);

					//RoutesHelper::Redirect("DisplayAllPostAsTable");
				}
				else
					RoutesHelper::Redirect("DisplayHome");


				$path = PathHelper::GetPath([ "Post", "Add" ]);
				$view = new View($path);
				
				return $view->Render([ "Post" => $post, "Violations" => $violations ]);
			// }
			// else
			// 	RoutesHelper::Redirect("DisplayHome");

		}
		catch (\Exception $e)
		{
			ErrorManager::Manage($e);
		}
	}
}