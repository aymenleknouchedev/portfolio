import { Head } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import Pagination from '../../Components/Pagination';
import EmptyState from '../../Components/EmptyState';
import { formatDate, money } from '../../lib/utils';
import type { Paginator } from '../../types';

interface Purchase {
  id: number;
  amount: number;
  status: string;
  paypal_order_id: string | null;
  created_at: string;
  user: { id: number; name: string; email: string } | null;
  addon: { id: number; name: string } | null;
}

const statusClass: Record<string, string> = {
  completed: 'bg-emerald-500/15 text-emerald-300',
  pending: 'bg-amber-500/15 text-amber-300',
  failed: 'bg-rose-500/15 text-rose-300',
  refunded: 'bg-gray-500/15 text-gray-300',
};

export default function Index({ purchases }: { purchases: Paginator<Purchase> }) {
  return (
    <>
      <Head title="Purchases" />
      <PageHeader title="Purchases" description={`Total: ${purchases.total}`} />

      {purchases.data.length === 0 ? (
        <EmptyState title="No purchases yet." />
      ) : (
        <>
          <div className="a-card overflow-hidden">
            <table className="w-full">
              <thead>
                <tr className="border-b border-white/5">
                  <th className="a-th">#</th>
                  <th className="a-th">Customer</th>
                  <th className="a-th">Add-on</th>
                  <th className="a-th">Amount</th>
                  <th className="a-th">Status</th>
                  <th className="a-th">PayPal</th>
                  <th className="a-th">Date</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {purchases.data.map((p) => (
                  <tr key={p.id}>
                    <td className="a-td font-mono text-xs text-gray-500">#{p.id}</td>
                    <td className="a-td">
                      {p.user ? (
                        <>
                          <div className="font-medium text-white">{p.user.name}</div>
                          <div className="text-xs text-gray-500">{p.user.email}</div>
                        </>
                      ) : (
                        <span className="text-gray-500">—</span>
                      )}
                    </td>
                    <td className="a-td">{p.addon?.name ?? '—'}</td>
                    <td className="a-td font-medium text-white">{money(p.amount)}</td>
                    <td className="a-td">
                      <span className={`a-badge ${statusClass[p.status] ?? 'bg-gray-500/15 text-gray-300'}`}>
                        {p.status}
                      </span>
                    </td>
                    <td className="a-td font-mono text-xs text-gray-400">{p.paypal_order_id ?? '—'}</td>
                    <td className="a-td text-xs text-gray-400">{formatDate(p.created_at, true)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          <Pagination links={purchases.links} />
        </>
      )}
    </>
  );
}
