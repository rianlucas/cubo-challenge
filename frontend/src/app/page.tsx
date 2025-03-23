import { Button } from "@/components/ui/button";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { TodoForm } from "@/components/form";
import { taskService } from "@/services/todo";
import { TaskList } from "@/components/task-list";

export default async function Home() {
  const tasks = await taskService.getTasks();

  return (
    <div className="m-auto px-10 max-w-3xl">
      <div className="flex justify-center items-center pt-4">
        <h1 className="text-3xl font-bold">Todo list</h1>
      </div>

      <div className="pt-6">
        <div className="flex justify-between items-center">
          <h2 className="text-xl font-bold">Suas tarefas</h2>

          <Dialog>
            <DialogTrigger asChild>
              <Button>Adicionar tarefa</Button>
            </DialogTrigger>
            <DialogContent>
              <DialogHeader>
                <DialogTitle>Adicionar tarefa</DialogTitle>
              </DialogHeader>
              <TodoForm />
            </DialogContent>
          </Dialog>
        </div>

        <TaskList initialTasks={tasks} />
      </div>
    </div>
  );
}
