// client/src/lib/api.ts

function extractErrorMessage(x: any): string {
  if (!x) return "Unknown error";
  if (typeof x === "string") return x;
  if (x.message && typeof x.message === "string") return x.message;
  if ((x as any).error) return extractErrorMessage((x as any).error);
  if ((x as any).fieldErrors) {
    const first = Object.values((x as any).fieldErrors as Record<string, string[]>)
      .flat().find(Boolean);
    if (first) return String(first);
  }
  if (Array.isArray((x as any).errors) && (x as any).errors[0]?.message) {
    return String((x as any).errors[0].message);
  }
  try { return JSON.stringify(x); } catch { return "Unknown error"; }
}

function buildApiUrl(path: string): string {
  if (/^https?:\/\//i.test(path)) return path;    // absolute URL
  let p = path.startsWith("/api/") ? path.slice(4) : path;
  if (!p.startsWith("/")) p = "/" + p;            // กัน "/apiauth/login"
  return `/api${p}`;
}

type ApiRequestInit = Omit<RequestInit, "body"> & { body?: any };

export async function api<T = any>(path: string, init: ApiRequestInit = {}) {
  const url = buildApiUrl(path);
  const opts: RequestInit = { credentials: "include", ...init };

  const isFormData = typeof window !== "undefined" && opts.body instanceof FormData;
  const headers = new Headers(opts.headers || {});
  headers.set("Accept", "application/json, text/plain, */*");

  if (isFormData) {
    headers.delete("Content-Type"); // ❗ปล่อย browser ใส่ boundary ให้เอง
  } else {
    if (opts.body && typeof opts.body !== "string") {
      headers.set("Content-Type", "application/json");
      opts.body = JSON.stringify(opts.body);
    } else if (!opts.body) {
      headers.delete("Content-Type"); // GET/HEAD ไม่ต้องมี
    }
  }
  opts.headers = headers;

  // เพิ่ม debug ให้เห็นใน Console ฝั่งเว็บ
  console.debug("[api] →", opts.method || "GET", url, { isFormData });

  const res = await fetch(url, opts);

  const ct = res.headers.get("content-type") || "";
  if ((opts.method && opts.method !== "GET") && ct.includes("text/html")) {
    const text = await res.text();
    throw new Error("Routed to HTML (path ผิดหรือโดน SPA fallback): " + text.slice(0, 120));
  }

  if (res.status === 204 || res.status === 205) return undefined as T;

  const data = ct.includes("application/json") ? await res.json() : await res.text();
  if (!res.ok) throw new Error(extractErrorMessage(data) || `${res.status} ${res.statusText}`);

  console.debug("[api] ←", res.status, url);
  return data as T;
}
