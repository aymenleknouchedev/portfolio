import { Head, Link } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import DeleteButton from '../../Components/DeleteButton';
import EmptyState from '../../Components/EmptyState';
import { formatDate, money, useRoute } from '../../lib/utils';

interface PromoCode {
  id: number;
  code: string;
  description: string | null;
  type: 'percentage' | 'fixed';
  value: number;
  min_order: number | null;
  max_discount: number | null;
  max_uses: number | null;
  used_count: number;
  starts_at: string | null;
  expires_at: string | null;
  is_active: boolean;
}

export default function Index({ promoCodes }: { promoCodes: PromoCode[] }) {
  const route = useRoute();
  return (
    <>
      <Head title="Promo Codes" />
      <PageHeader title="Promo Codes" description="Discounts applied at checkout.">
        <Link href={route('admin.promo-codes.create')} className="a-btn-pri">+ New Code</Link>
      </PageHeader>

      {promoCodes.length === 0 ? (
        <EmptyState title="No promo codes yet." />
      ) : (
        <div className="a-card overflow-hidden">
          <table className="w-full">
            <thead>
              <tr className="border-b border-white/5">
                <th className="a-th">Code</th>
                <th className="a-th">Discount</th>
                <th className="a-th">Usage</th>
                <th className="a-th">Window</th>
                <th className="a-th">Active</th>
                <th className="a-th text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-white/5">
              {promoCodes.map((p) => (
                <tr key={p.id}>
                  <td className="a-td">
                    <span className="font-mono font-medium text-white">{p.code}</span>
                    {p.description && <div className="text-xs text-gray-500">{p.description}</div>}
                  </td>
                  <td className="a-td">
                    {p.type === 'percentage' ? `${p.value}%` : money(p.value)}
                    {p.max_discount && <div className="text-xs text-gray-500">max {money(p.max_discount)}</div>}
                  </td>
                  <td className="a-td">
                    {p.used_count}
                    {p.max_uses ? ` / ${p.max_uses}` : ''}
                  </td>
                  <td className="a-td text-xs text-gray-400">
                    {p.starts_at ? formatDate(p.starts_at) : '—'}
                    {' → '}
                    {p.expires_at ? formatDate(p.expires_at) : '∞'}
                  </td>
                  <td className="a-td">
                    {p.is_active ? (
                      <span className="a-badge bg-emerald-500/15 text-emerald-300">Active</span>
                    ) : (
                      <span className="a-badge bg-gray-500/15 text-gray-400">Disabled</span>
                    )}
                  </td>
                  <td className="a-td">
                    <div className="flex justify-end gap-2">
                      <Link href={route('admin.promo-codes.edit', p.id)} className="a-btn-sec">Edit</Link>
                      <DeleteButton url={route('admin.promo-codes.destroy', p.id)} />
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </>
  );
}
