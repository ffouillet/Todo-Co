<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskEditType;
use App\Form\TaskType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    const MAX_TASKS_PER_PAGE = 5;

    /**
     * @Route("/tasks", name="task_list")
     */
    public function list(Request $request)
    {
        $pageNumber = $request->query->getInt('page',1);
        $paginatedTasks =
            $this->getDoctrine()
                ->getRepository(Task::class
                )->findByIsDonePaginated($pageNumber, self::MAX_TASKS_PER_PAGE, $completedTasks = false);

        return $this->render('task/list.html.twig',
            ['tasks' => $paginatedTasks]);
    }

    /**
     * @Route("/tasks/completed", name="task_list_completed")
     */
    public function listCompleted(Request $request)
    {
        $pageNumber = $request->query->getInt('page',1);
        $paginatedTasks =
            $this->getDoctrine()
                ->getRepository(Task::class)
                ->findByIsDonePaginated($pageNumber, self::MAX_TASKS_PER_PAGE, $completedTasks = true);

        return $this->render('task/list_completed.html.twig',
            ['tasks' => $paginatedTasks]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function create(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Affect currently authenticated user as the task owner
            $task->setAuthor($this->getUser());

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function edit(Task $task, Request $request)
    {

        $form = $this->createForm(TaskEditType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Only task author can edit his task. Users with ROLE_ADMIN can edit all tasks.
            $this->denyAccessUnlessGranted('edit', $task, "Désolé, seul les auteurs de leur tâches peuvent les modifier.");

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTask(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTask(Task $task)
    {
        // Only task author can delete his task. Tasks without an author can be deleted only by an author with ROLE_ADMIN
        $this->denyAccessUnlessGranted('delete', $task, "Désolé, seul les auteurs de leurs tâches peuvent les supprimer.");

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
