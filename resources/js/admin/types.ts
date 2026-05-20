// Global type augmentations for Inertia shared props + ziggy route().

import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import type { route as ZiggyRoute } from 'ziggy-js';

export interface AuthUser {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'client' | null;
}

export interface SharedSettings {
  site_name: string;
  site_logo: string | null;
  primary_color: string;
}

export interface FlashBag {
  success?: string | null;
  error?: string | null;
}

export interface SharedProps extends InertiaPageProps {
  auth: { user: AuthUser | null };
  flash: FlashBag;
  settings: SharedSettings;
  errors: Record<string, string>;
  ziggy: any;
}

declare global {
  // eslint-disable-next-line no-var
  var route: typeof ZiggyRoute;
}

// Re-export Paginator shape from Laravel.
export interface PaginatedLink {
  url: string | null;
  label: string;
  active: boolean;
}

export interface Paginator<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number | null;
  to: number | null;
  links: PaginatedLink[];
  next_page_url: string | null;
  prev_page_url: string | null;
  path: string;
}
