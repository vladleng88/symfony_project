<?php

namespace App\Controller;

use App\Form\PostType;
use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;

class PostsController extends AbstractController
{
    private $repository;
    private $slugify;


    public function __construct(PostRepository $postRepository, Slugify $slugify)
    {
        $this->repository = $postRepository;
        $this->slugify = $slugify;
    }

    /**
     * @Route("/posts", name="blog_posts")
     */
    public function posts()
    {
        $posts = $this->repository->findAll();


        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
        ]);
    }
    /**
     * @Route("/search", name="blog_search")
     */
    public function search (Request $request) {
        $query = $request->query->get('q');
        $posts = $this->repository->searchByQuery($query);
        return $this->render('posts/query.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/posts/{slug}/update", name="post_update")
     */
    public function edit(Request $request, Post $post)
    {
        //$slugify = Slugify::create();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($this->slugify->slugify($post->getTitle()));
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('blog_show',[
                'slug'=>$post->getSlug()
            ]);
        }
        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/posts/{slug}/delete", name="post_delete")
     */
    public function delete(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute('blog_posts');
    }

    /**
     * @Route("/addpost", name="addpost")
     */
    public function addPost(Request $request)
    {
        $post = new Post();
        //$slugify = Slugify::create();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($this->slugify->slugify($post->getTitle()));
            $post->setCreatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

        return $this->redirectToRoute('blog_posts');
    }
        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/posts/{slug}", name="blog_show")
     */
    public function show(Post $post)
    {
        //$post = $this->repository->findOneBy(['slug'=>$slug]);
        return $this->render('posts/show.html.twig', [

            'post'=>$post,
        ]);
    }
}
