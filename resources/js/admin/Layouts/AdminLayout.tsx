import React, { useEffect, useState } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import type { SharedProps } from '../types';
import { useRoute, storageUrl } from '../lib/utils';
import Flash from '../Components/Flash';

interface NavItem {
  label: string;
  route: string;
  icon: string; // emoji or single char for now
  // active-prefix patterns for highlighting
  match?: string[];
}

interface NavGroup {
  label: string;
  items: NavItem[];
}

const groups: NavGroup[] = [
  {
    label: 'Overview',
    items: [{ label: 'Dashboard', route: 'admin.dashboard', icon: '▦' }],
  },
  {
    label: 'Catalog',
    items: [
      { label: 'Categories', route: 'admin.categories.index', icon: '◆', match: ['admin.categories.'] },
      { label: 'Add-ons', route: 'admin.addons.index', icon: '★', match: ['admin.addons.'] },
      { label: 'Project Categories', route: 'admin.project-categories.index', icon: '◇', match: ['admin.project-categories.'] },
      { label: 'Projects', route: 'admin.projects.index', icon: '▣', match: ['admin.projects.'] },
      { label: 'Services', route: 'admin.services.index', icon: '✦', match: ['admin.services.'] },
      { label: 'Brands', route: 'admin.brands.index', icon: '◉', match: ['admin.brands.'] },
      { label: 'Articles', route: 'admin.articles.index', icon: '✎', match: ['admin.articles.'] },
    ],
  },
  {
    label: 'Sales',
    items: [
      { label: 'Promo Codes', route: 'admin.promo-codes.index', icon: '%', match: ['admin.promo-codes.'] },
      { label: 'Purchases', route: 'admin.purchases.index', icon: '$' },
      { label: 'Users', route: 'admin.users.index', icon: '◌' },
      { label: 'Waitlist', route: 'admin.waitlist.index', icon: '⌛' },
    ],
  },
  {
    label: 'Support',
    items: [
      { label: 'Contact Messages', route: 'admin.contact-messages.index', icon: '✉', match: ['admin.contact-messages.'] },
      { label: 'Reclamations', route: 'admin.reclamations.index', icon: '⚑', match: ['admin.reclamations.'] },
    ],
  },
  {
    label: 'Settings',
    items: [
      { label: 'General', route: 'admin.settings.general', icon: '⚙' },
      { label: 'Hero', route: 'admin.settings.hero', icon: '⬡' },
      { label: 'About', route: 'admin.settings.about', icon: 'ⓘ' },
      { label: 'Social', route: 'admin.settings.social', icon: '⛓' },
      { label: 'Payment', route: 'admin.settings.payment', icon: '◈' },
      { label: 'Account', route: 'admin.settings.account', icon: '◐' },
    ],
  },
];

export default function AdminLayout({ children }: { children: React.ReactNode }) {
  const { props } = usePage<SharedProps>();
  const route = useRoute();
  const { auth, settings } = props;
  const [open, setOpen] = useState(false);

  // Sync CSS custom-prop with current primary color (theming hot-swap).
  useEffect(() => {
    if (settings?.primary_color) {
      document.documentElement.style.setProperty('--admin-primary', settings.primary_color);
    }
  }, [settings?.primary_color]);

  const currentName: string | undefined = (props.ziggy?.location as string) ?? undefined;
  // Use Ziggy's current() if available for accurate matching.
  const ziggy = (window as any).route as any;
  const isActive = (name: string, match?: string[]) => {
    try {
      if (ziggy?.().current?.()) {
        if (match?.some((m) => ziggy().current(m + '*'))) return true;
        return ziggy().current(name);
      }
    } catch {
      /* ignore */
    }
    return false;
  };

  const logoutForm = (e: React.FormEvent) => {
    e.preventDefault();
    router.post(route('logout'));
  };

  return (
    <div className="flex min-h-screen bg-[color:var(--color-admin-bg,#0a0a0f)] text-gray-200">
      {/* Sidebar */}
      <aside
        className={
          'fixed inset-y-0 left-0 z-40 w-64 transform border-r border-white/5 bg-[#0f0f17] transition-transform lg:translate-x-0 lg:static ' +
          (open ? 'translate-x-0' : '-translate-x-full')
        }
      >
        <div className="flex h-16 items-center gap-3 border-b border-white/5 px-5">
          {settings?.site_logo ? (
            <img src={storageUrl(settings.site_logo)!} alt={settings.site_name} className="h-8 w-8 rounded object-cover" />
          ) : (
            <div
              className="grid h-8 w-8 place-items-center rounded font-bold text-white"
              style={{ backgroundColor: 'var(--admin-primary)' }}
            >
              F
            </div>
          )}
          <div className="flex flex-col">
            <span className="text-sm font-semibold tracking-wide text-white">{settings?.site_name ?? 'FraxionFX'}</span>
            <span className="text-[10px] uppercase tracking-widest text-gray-500">Admin Panel</span>
          </div>
        </div>

        <nav className="h-[calc(100vh-4rem)] overflow-y-auto px-3 py-4">
          {groups.map((g) => (
            <div key={g.label} className="mb-5">
              <div className="px-2 pb-2 text-[10px] font-semibold uppercase tracking-widest text-gray-500">
                {g.label}
              </div>
              <ul className="space-y-0.5">
                {g.items.map((item) => {
                  const active = isActive(item.route, item.match);
                  return (
                    <li key={item.route}>
                      <Link
                        href={route(item.route)}
                        className={
                          'flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition ' +
                          (active
                            ? 'bg-white/[0.07] text-white'
                            : 'text-gray-400 hover:bg-white/[0.04] hover:text-white')
                        }
                        style={active ? { borderLeft: '2px solid var(--admin-primary)' } : undefined}
                      >
                        <span className="grid h-5 w-5 place-items-center text-xs opacity-80">{item.icon}</span>
                        <span>{item.label}</span>
                      </Link>
                    </li>
                  );
                })}
              </ul>
            </div>
          ))}

          <div className="border-t border-white/5 pt-4">
            <a
              href="/"
              className="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-400 hover:bg-white/[0.04] hover:text-white"
              target="_blank"
              rel="noopener noreferrer"
            >
              <span className="grid h-5 w-5 place-items-center text-xs">↗</span>
              <span>View Site</span>
            </a>
            <form onSubmit={logoutForm}>
              <button
                type="submit"
                className="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-400 hover:bg-red-500/10 hover:text-red-300"
              >
                <span className="grid h-5 w-5 place-items-center text-xs">⎋</span>
                <span>Logout</span>
              </button>
            </form>
          </div>
        </nav>
      </aside>

      {/* Mobile overlay */}
      {open && (
        <div
          className="fixed inset-0 z-30 bg-black/60 lg:hidden"
          onClick={() => setOpen(false)}
          aria-hidden="true"
        />
      )}

      {/* Content */}
      <div className="flex flex-1 flex-col lg:pl-0">
        <header className="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-white/5 bg-[#0a0a0f]/80 px-5 backdrop-blur">
          <button
            className="rounded-lg border border-white/10 p-2 lg:hidden"
            onClick={() => setOpen((o) => !o)}
            aria-label="Toggle navigation"
          >
            <span aria-hidden>≡</span>
          </button>
          <div className="hidden lg:block" />
          <div className="flex items-center gap-3 text-sm text-gray-400">
            <span className="hidden sm:inline">{auth?.user?.name}</span>
            <span
              className="grid h-8 w-8 place-items-center rounded-full text-xs font-semibold text-white"
              style={{ backgroundColor: 'var(--admin-primary)' }}
            >
              {auth?.user?.name?.[0]?.toUpperCase() ?? '?'}
            </span>
          </div>
        </header>

        <main className="flex-1 px-5 py-6 lg:px-8">
          <Flash />
          {children}
        </main>
      </div>
    </div>
  );
}
