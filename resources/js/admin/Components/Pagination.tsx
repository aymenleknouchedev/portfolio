import { Link } from '@inertiajs/react';
import type { PaginatedLink } from '../types';

export default function Pagination({ links }: { links: PaginatedLink[] }) {
  if (!links || links.length <= 3) return null;
  return (
    <nav className="mt-4 flex flex-wrap gap-1">
      {links.map((l, i) => {
        const label = l.label.replace(/&laquo;|&raquo;/g, (m) => (m === '&laquo;' ? '«' : '»'));
        const base = 'rounded-md border px-3 py-1.5 text-xs transition ';
        if (!l.url) {
          return (
            <span key={i} className={base + 'border-white/5 text-gray-600'} dangerouslySetInnerHTML={{ __html: label }} />
          );
        }
        return (
          <Link
            key={i}
            href={l.url}
            preserveScroll
            preserveState
            className={
              base +
              (l.active
                ? 'border-transparent text-white'
                : 'border-white/10 text-gray-300 hover:bg-white/5')
            }
            style={l.active ? { backgroundColor: 'var(--admin-primary)' } : undefined}
            dangerouslySetInnerHTML={{ __html: label }}
          />
        );
      })}
    </nav>
  );
}
