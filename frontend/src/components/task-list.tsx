"use client"

import { useState, useEffect } from "react"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Card, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { taskService } from "@/services/todo"
import type { paginatedTasks } from "@/services/todo"

export function TaskList({ initialTasks }: { initialTasks: paginatedTasks }) {
  const [tasks, setTasks] = useState<paginatedTasks>(initialTasks)
  const [statusFilter, setStatusFilter] = useState<string>("all")
  const [filteredTasks, setFilteredTasks] = useState(initialTasks.data)

  useEffect(() => {
    if (statusFilter === "all") {
      setFilteredTasks(tasks.data)
    } else {
      taskService.getTasks({ status: statusFilter }).then(response => {
        setFilteredTasks(response.data)
      })
    }
  }, [statusFilter, tasks.data])

  // Função para obter todos os status únicos das tarefas
  const getUniqueStatuses = () => {
    const statuses = new Set<string>()
    tasks.data.forEach((task) => statuses.add(task.status))
    return Array.from(statuses)
  }

  async function handleDelete(taskId: string) {
    await taskService.deleteTask(taskId)
    setTasks((prev) => ({
      ...prev,
      data: prev.data.filter((task) => task.id !== taskId),
    }))
  }

  // Função para obter a cor do badge com base no status
  const getStatusColor = (status: string) => {
    switch (status.toLowerCase()) {
      case "pendente":
        return "bg-yellow-100 text-yellow-800 hover:bg-yellow-200"
      case "em andamento":
      case "in progress":
        return "bg-blue-100 text-blue-800 hover:bg-blue-200"
      case "completada":
      case "completed":
        return "bg-green-100 text-green-800 hover:bg-green-200"
      default:
        return ""
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <div className="w-[200px]">
          <Select value={statusFilter} onValueChange={setStatusFilter}>
            <SelectTrigger>
              <SelectValue placeholder="Filtrar por status" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="all">Todos os status</SelectItem>
              {getUniqueStatuses().map((status) => (
                <SelectItem key={status} value={status}>
                  {status.charAt(0).toUpperCase() + status.slice(1)}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
      </div>

      <div className="pt-2">
        {filteredTasks.length === 0 ? (
          <div className="text-center py-10 text-muted-foreground border rounded-lg">
            {statusFilter === "all"
              ? "Nenhuma tarefa encontrada."
              : `Nenhuma tarefa com status "${statusFilter}" encontrada.`}
          </div>
        ) : (
          filteredTasks.map((task) => (
            <Card key={task.id} className="mt-6">
              <CardHeader>
                <div className="flex justify-between">
                  <CardTitle>{task.name}</CardTitle>
                  <Badge variant="outline" className={getStatusColor(task.status)}>
                    {task.status}
                  </Badge>
                </div>
              </CardHeader>
              <div className="flex justify-between px-6 pb-6">
                <CardDescription>{task.description}</CardDescription>
                <Button className="cursor-pointer" variant="destructive" onClick={() => handleDelete(task.id)}>
                  Remover
                </Button>
              </div>
            </Card>
          ))
        )}
      </div>

      {tasks.data.length > 0 && (
        <div className="flex justify-between items-center pt-4 text-sm text-muted-foreground">
          <div>
            Mostrando {filteredTasks.length} de {tasks.data.length} tarefas
          </div>
          {statusFilter !== "all" && (
            <Button variant="ghost" size="sm" onClick={() => setStatusFilter("all")}>
              Limpar filtro
            </Button>
          )}
        </div>
      )}
    </div>
  )
}

