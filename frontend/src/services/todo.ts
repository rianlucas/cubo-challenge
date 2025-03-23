import api from "@/api";

export interface Task {
  sucess: string;
  data: {
    id: string;
    name: string;
    description: string;
    status: "pending" | "in progress" | "completed";
  }
}

export interface paginatedTasks {
  data: Array<{
    id: string;
    name: string;
    description: string;
    status: "pending" | "in progress" | "completed";
  }>;
  current_page: number;
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: {
    url: string | null;
    label: string;
    active: boolean;
  }[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}

export const taskService = {
  async getTasks(params?: {
    per_page?: number,
    page?: number,
    status?: string
  }) {
    const response = await api.get<paginatedTasks>("/tasks", { params });
    return response.data;
  },

  async getTask(id: string) {
    const response = await api.get<Task>(`/tasks/${id}`);
    return response.data;
  },

  async createTask(Task: Omit<Task["data"], "id">) {
    const response = await api.post<Task>("/tasks", Task);
    return response.data;
  },

  async updateTask(id: string, Task: Partial<Task>) {
    const response = await api.put<Task>(`/tasks/${id}`, Task);
    return response.data;
  },

  async deleteTask(id: string) {
    await api.delete(`/tasks/${id}`);
  },
};
