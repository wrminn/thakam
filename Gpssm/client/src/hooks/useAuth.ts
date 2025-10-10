import { useEffect, useState } from "react";
import { api } from "../lib/api";

export type User = { id: number; email: string; fullName?: string };

export function useAuth() {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api<{ user: User }>("/auth/me")
      .then((d) => setUser(d.user))
      .catch(() => setUser(null))
      .finally(() => setLoading(false));
  }, []);

  async function login(email: string, password: string) {
    const d = await api<{ user: User }>("/auth/login", { method: "POST", body: { email, password } });
    setUser(d.user);
  }

  async function register(email: string, password: string, fullName?: string) {
    const d =  await api("auth/register", {
   method: "POST",
   body: { email,password,fullName: fullName?.trim() ? fullName.trim() : undefined,}, });
    setUser(d.user);
  }

  async function logout() {
    await api("/auth/logout", { method: "POST" });
    setUser(null);
  }

  return { user, loading, login, register, logout };
}
export type Session = {
  user: User | null;
  loading: boolean;
  isAuthenticated: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (email: string, password: string, fullName?: string) => Promise<void>;
  logout: () => Promise<void>;
};


export function useSession(): Session {
  const { user, loading, login, register, logout } = useAuth();
  return {
    user,
    loading,
    isAuthenticated: !!user,
    login,
    register,
    logout,
  };
}