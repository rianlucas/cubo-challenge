import axios from "axios";

const isServer = typeof window === 'undefined';

const baseURL = isServer
    ? 'http://laravel_api:8000/api'   // dentro do container do Next.js
    : 'http://localhost:8000/api';   // no navegador do host

const api = axios.create({
  baseURL,
  headers: {
    "Content-Type": "application/json",
  },
  withCredentials: true,
});

export default api;