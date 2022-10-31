<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{

    private $todos;

    public function __construct()
    {
        $this->todos = file_get_contents('database/todos.json');
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        $todos = $this->todos;

        $todo_decode = json_decode($todos, true);

        return $this->render('article/show.html.twig', [
            'title' => "Agregá un nuevo ToDo",
            'todos' => $todo_decode
        ]);
    }

    /**
     * @Route("/show", name="show_todo")
     */
    public function show()
    {
        $todos = $this->todos;

        $this->homepage();
        return $this->json(json_decode(json_encode($todos)));
    }

    /**
     * @Route("/todo/{newTodo}", name="add_todo", methods={"POST"})
     */
    public function add($newTodo)
    {
        $todos = $this->todos;

        $todo_decode = json_decode($todos, true);

        $arrayLength = count($todo_decode) - 1;

        $newTodoObj = ["name" => $newTodo, "completed" => false, "id" => $arrayLength >= 0 ? $todo_decode[$arrayLength]['id'] + 1 : 0];


        array_push($todo_decode, $newTodoObj);

        $todo_encode = json_encode($todo_decode);

        file_put_contents('database/todos.json', $todo_encode);

        return $this->json(json_decode(json_encode($todo_decode)));
    }

    /**
     * @Route("/edit/{id}", name="edit_todo", methods={"PUT"})
     */
    public function edit($id)
    {
        $todos = $this->todos;

        $todo_decode = json_decode($todos, true);
        $todo_decode[$id]["completed"] = $todo_decode[$id]["completed"] == false ? true : false;

        $todo_encode = json_encode($todo_decode);

        file_put_contents('database/todos.json', $todo_encode);

        return $this->json(json_decode(json_encode($todo_decode)));
    }

    /**
     * @Route("/delete/{id}", name="delete_todo", methods={"DELETE"})
     */
    public function delete($id)
    {
        $todos = $this->todos;

        $todo_decode = json_decode($todos, true);

        $todo_filtered = [];

        foreach ($todo_decode as $todo) {
            if ($todo['id'] != $id) {
                array_push($todo_filtered, $todo);
            }
        }

        $todo_encode = json_encode($todo_filtered);

        file_put_contents('database/todos.json', $todo_encode);

        return new Response('Borrado');
    }
}