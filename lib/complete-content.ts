type RecordValue = Record<string, unknown>;

const isRecord = (value: unknown): value is RecordValue =>
  typeof value === "object" && value !== null && !Array.isArray(value);

function mergeValue(stored: unknown, fallback: unknown): unknown {
  if (stored === undefined || stored === null) return fallback;

  if (typeof stored === "string") {
    return stored.trim() ? stored : fallback;
  }

  if (Array.isArray(stored) && Array.isArray(fallback)) {
    return stored.map((item, index) => mergeValue(item, fallback[index]));
  }

  if (isRecord(stored) && isRecord(fallback)) {
    const result: RecordValue = { ...stored };
    for (const [key, value] of Object.entries(fallback)) {
      result[key] = mergeValue(stored[key], value);
    }
    return result;
  }

  return stored;
}

export function completeSiteContent<T extends RecordValue>(stored: Partial<T> | null | undefined, defaults: T): T {
  const result = mergeValue(stored ?? {}, defaults) as T;

  for (const key of ["packages", "addons"] as const) {
    const current = result[key as keyof T];
    const fallback = defaults[key as keyof T];
    if (Array.isArray(current) && current.length === 0 && Array.isArray(fallback)) {
      (result as RecordValue)[key] = fallback;
    }
  }

  return result;
}
