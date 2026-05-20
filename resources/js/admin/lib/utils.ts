// Convenience hook for accessing Ziggy's global `route()` helper.
// Inertia + ziggy-js: the @routes directive in Blade exposes window.route.
export function useRoute() {
  if (typeof window !== 'undefined' && typeof (window as any).route === 'function') {
    return (window as any).route as (
      name?: string,
      params?: Record<string, any> | (string | number),
      absolute?: boolean,
    ) => string;
  }
  // Fallback no-op so SSR / type-checks don't blow up.
  return ((name?: string) => name ?? '') as any;
}

/** Storage URL helper for files saved via Laravel's `public` disk. */
export function storageUrl(path: string | null | undefined): string | null {
  if (!path) return null;
  if (/^https?:\/\//i.test(path)) return path;
  return `/storage/${path}`;
}

/** Format a number as USD currency. */
export function money(value: number | string | null | undefined): string {
  const n = typeof value === 'string' ? parseFloat(value) : value ?? 0;
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(n || 0);
}

/** Format an ISO/SQL datetime for display. */
export function formatDate(value: string | null | undefined, withTime = false): string {
  if (!value) return '—';
  const d = new Date(value);
  if (Number.isNaN(d.getTime())) return '—';
  return withTime
    ? d.toLocaleString(undefined, { dateStyle: 'medium', timeStyle: 'short' })
    : d.toLocaleDateString(undefined, { dateStyle: 'medium' });
}
