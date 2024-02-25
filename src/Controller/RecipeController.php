<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recipes', name: 'app_recipe_')]
class RecipeController extends AbstractController
{

    private readonly RecipeRepository $recipeRepository;

    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipies' => $this->recipeRepository->findAll(),
        ]);
    }

    #[Route('/{slug}-{id}', name: 'show', requirements: ['slug' => '[a-z0-9-]+', 'id' => '\d+'])]
    public function show($slug, $id): Response
    {
        $recipe = $this->recipeRepository->find($id);

        if($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('app_recipe_show', [
                'id' => $recipe->getId(),
                'slug' => $recipe->getSlug(),
            ], 301);
        }
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{duration<\d+>}', name: 'duration')]
    public function duration(int $duration): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipies' => $this->recipeRepository->findWithDurationLowerThan($duration),
        ]);
    }
}
