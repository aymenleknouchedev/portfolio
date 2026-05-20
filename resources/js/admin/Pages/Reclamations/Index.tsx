import { Head, Link } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import Pagination from '../../Components/Pagination';
import EmptyState from '../../Components/EmptyState';
import { formatDate, useRoute } from '../../lib/utils';
import type { Paginator } from '../../types';

interface Reclamation {
  id: number;
  subject: string;
  status: 'open' | 'in_progress' | 'resolved' | 'closed';
  is_read_admin: boolean;
  created_at: string;
  user: { id: number; name: string; email: string } | null;
  purchase: { id: number; addon: { name: string } | null } | null;
}

const statusClass: Record<string, string> = {
  open: 'bg-amber-500/15 text-amber-300',
  in_progress: 'bg-sky-500/15 text-sky-300',
  resolved: 'bg-emerald-500/15 text-emerald-300',
  closed: 'bg-gray-500/15 text-gray-300',
};

export default function Index({
  reclamations,
  unreadCount,
}: {
  reclamations: Paginator<Reclamation>;
  unreadCount: number;
}) {
  const route = useRoute();
  return (
    <>
      <Head title="Reclamations" />
      <PageHeader
        title="Reclamations"
        description={`${reclamations.total} total · ${unreadCount} unread`}
      />

      {reclamations.data.length === 0 ? (
        <EmptyState title="No reclamations yet." />
      ) : (
        <>
          <div className="a-card overflow-hidden">
            <table className="w-full">
              <thead>
                <tr className="border-b border-white/5">
                  <th className="a-th"></th>
                  <th className="a-th">Subject</th>
                  <th className="a-th">Customer</th>
                  <th className="a-th">Add-on</th>
                  <th className="a-th">Status</th>
                  <th className="a-th">Opened</th>
                  <th className="a-th"></th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {reclamations.data.map((r) => (
                  <tr key={r.id} className={!r.is_read_admin ? 'bg-indigo-500/[0.03]' : ''}>
                    <td className="a-td">
                      {!r.is_read_admin && <span className="inline-block h-2 w-2 rounded-full bg-indigo-400" />}
                    </td>
                    <td className="a-td">
                      <div className={`${!r.is_read_admin ? 'font-semibold' : ''} text-white`}>{r.subject}</div>
                    </td>
                    <td className="a-td">
                      {r.user ? (
                        <>
                          <div className="text-gray-300">{r.user.name}</div>
                          <div className="text-xs text-gray-500">{r.user.email}</div>
                        </>
                      ) : (
                        <span className="text-gray-500">—</span>
                      )}
                    </td>
                    <td className="a-td text-gray-300">{r.purchase?.addon?.name ?? '—'}</td>
                    <td className="a-td">
                      <span className={`a-badge ${statusClass[r.status]}`}>{r.status.replace('_', ' ')}</span>
                    </td>
                    <td className="a-td text-xs text-gray-400">{formatDate(r.created_at, true)}</td>
                    <td className="a-td">
                      <Link href={route('admin.reclamations.show', r.id)} className="a-btn-sec">
                        Open
                      </Link>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          <Pagination links={reclamations.links} />
        </>
      )}
    </>
  );
}
