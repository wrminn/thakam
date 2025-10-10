import { clsx, type ClassValue } from "clsx"
import { twMerge } from "tailwind-merge"

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

export function extractErrorMessage(
  error: unknown,
  fallback = "เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุ",
) {
  if (error instanceof Error) {
    const message = error.message ?? ""
    const separatorIndex = message.indexOf(": ")
    const rawDetails =
      separatorIndex >= 0 ? message.slice(separatorIndex + 2) : message
    const trimmed = rawDetails.trim()

    if (!trimmed) {
      return fallback
    }

    if (trimmed.startsWith("{") && trimmed.endsWith("}")) {
      try {
        const parsed = JSON.parse(trimmed)
        if (typeof parsed?.message === "string") {
          return parsed.message
        }
      } catch {
        // ignore JSON parse errors and fall through to return raw text
      }
    }

    return trimmed
  }

  return fallback
}
