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
    public function DisplayAllAsTable(array $queryParameters) : void
	{
		try
        {
			if (UserHelper::IsLogin())
			{
				$postBLL = new PostBLL();
				$posts = $postBLL->Load(new PostFilter());

				$path = PathHelper::GetPath([ "Post", "DisplayAllAsTable" ]);
				$view = new View($path);
				
				$view->Render([ "Posts" => $posts ]);
			}
			else
				RoutesHelper::Redirect("DisplayHome");

		}
		catch (\Exception $e)
		{
			ErrorManager::Manage($e);
		}
	}

	public function DisplayOne(array $queryParameters) : void
	{
		try
        {
			$slug = $queryParameters["slug"]->GetValue();
			$postFilter = new PostFilter();
			$postFilter->SetSlugs([ $slug ]);

			$postBLL = new PostBLL();
			$posts = $postBLL->Load($postFilter);
			$post = reset($posts);

			$path = PathHelper::GetPath([ "Post", "DisplayOne" ]);
			$view = new View($path);
			
			$view->Render([ "Post" => $post ]);
		}
		catch (\Exception $e)
		{
			ErrorManager::Manage($e);
		}
	}

	public function Add(array $queryParameters) : void
	{
		try
        {
			if (UserHelper::IsLogin())
			{
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
					$user = UserHelper::GetUser();
					$creationHistory->SetUser($user);
					$post->SetCreationHistory($creationHistory);

					$postBLL = new PostBLL();
					$postBLL->Add([ $post ]);

					RoutesHelper::Redirect("DisplayAllPostAsTable");
				}
				else
					RoutesHelper::Redirect("DisplayHome");


				$path = PathHelper::GetPath([ "Post", "Edit" ]);
				$view = new View($path);
				
				$view->Render([ "Post" => $post, "Violations" => $violations, "Action" => "ADD" ]);
			}
			else
				RoutesHelper::Redirect("DisplayHome");

		}
		catch (\Exception $e)
		{
			ErrorManager::Manage($e);
		}
	}

	public function Update(array $queryParameters) : void
	{
		try
        {
			if (UserHelper::IsLogin())
			{
				$post = null;
				$violations = new ViolationManager();
				$postBLL = new PostBLL();

				if ($_SERVER["REQUEST_METHOD"] == "GET")
				{
					$postId = $queryParameters["id"]->GetValue();

					$postFilter = new PostFilter();
					$postFilter->SetIds([ $postId ]);
					$posts = $postBLL->Load($postFilter);

					$post = reset($posts);
				}
				else if ($_SERVER["REQUEST_METHOD"] == "POST")
				{
					$post = new Post();

					$post->SetId($queryParameters["id"]->GetValue());
					$post->SetTitle($queryParameters["title"]->GetValue());
					$slug = SlugHelper::Slugify($post->GetTitle());
					$post->SetSlug($slug);
					$post->SetDescription($queryParameters["description"]->GetValue());
					$post->SetContent($queryParameters["content"]->GetValue());
					$post->SetIsPublished(array_key_exists("is_published", $queryParameters) ? $queryParameters["is_published"]->GetValue() : false);

					$history = new History();
					$history->SetDateTime(new \DateTime());
					$user = UserHelper::GetUser();
					$history->SetUser($user);
					$post->AddUpdateHistory($history);

					$postBLL->Update([ $post ]);

					RoutesHelper::Redirect("DisplayAllPostAsTable");
				}
				else
					RoutesHelper::Redirect("DisplayHome");


				$path = PathHelper::GetPath([ "Post", "Edit" ]);
				$view = new View($path);
				
				$view->Render([ "Post" => $post, "Violations" => $violations, "Action" => "UPDATE" ]);
			}
			else
				RoutesHelper::Redirect("DisplayHome");

		}
		catch (\Exception $e)
		{
			ErrorManager::Manage($e);
		}
	}

	public function Delete(array $queryParameters) : void
	{
		try
        {
			if (UserHelper::IsLogin())
			{
				if ($_SERVER["REQUEST_METHOD"] == "GET")
				{
					$postId = $queryParameters["id"]->GetValue();

					$postBLL = new PostBLL();
					$postBLL->Delete([ $postId ]);

					RoutesHelper::Redirect("DisplayAllPostAsTable");
				}
				else
				{
					RoutesHelper::Redirect("DisplayHome");
				}
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