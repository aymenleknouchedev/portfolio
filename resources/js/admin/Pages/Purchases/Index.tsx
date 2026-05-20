import { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import Pagination from '../../Components/Pagination';
import EmptyState from '../../Components/EmptyState';
import { formatDate, money } from '../../lib/utils';
import type { Paginator } from '../../types';

interface License {
  id: number;
  key: string;
  status: 'active' | 'inactive' | 'suspended';
  machine_id: string | null;
  is_lifetime: boolean;
  expires_at: string | null;
}

interface Purchase {
  id: number;
  amount: number;
  quantity: number;
  status: string;
  paypal_order_id: string | null;
  created_at: string;
  user: { id: number; name: string; email: string } | null;
  addon: { id: number; name: string } | null;
  licenses: License[];
}

const statusClass: Record<string, string> = {
  completed: 'bg-emerald-500/15 text-emerald-300',
  pending:   'bg-amber-500/15 text-amber-300',
  failed:    'bg-rose-500/15 text-rose-300',
  refunded:  'bg-gray-500/15 text-gray-300',
};

const licenseStatusClass: Record<string, string> = {
  active:    'bg-emerald-500/15 text-emerald-300',
  inactive:  'bg-gray-500/15 text-gray-300',
  suspended: 'bg-rose-500/15 text-rose-300',
};

function maskKey(key: string): string {
  if (key.length <= 8) return key;
  return key.slice(0, 4) + '—————' + key.slice(-4);
}

/** Amber "Refresh Key" button — reused in both single and multi-key layouts */
function RefreshBtn({ licenseId, refreshing, onRefresh }: {
  licenseId: number;
  refreshing: boolean;
  onRefresh: () => void;
}) {
  return (
    <button
      type="button"
      disabled={refreshing}
      onClick={onRefresh}
      title="Generate a new key and unbind from device"
      className="inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-amber-500/40 bg-amber-500/10 px-3 py-1.5 text-xs font-medium text-amber-300 transition hover:bg-amber-500/20 disabled:cursor-not-allowed disabled:opacity-50"
    >
      <svg className={`h-3.5 w-3.5 ${refreshing ? 'animate-spin' : ''}`}
        fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
      </svg>
      {refreshing ? 'Refreshing…' : 'Refresh Key'}
    </button>
  );
}

/** One row inside the expanded pack panel */
function LicenseRow({ license, refreshing, onRefresh }: {
  license: License;
  refreshing: boolean;
  onRefresh: () => void;
}) {
  const [revealed, setRevealed] = useState(false);
  return (
    <div className="flex flex-wrap items-center gap-3 rounded-lg border border-white/5 bg-white/[0.03] px-4 py-3">
      <div className="flex min-w-0 flex-1 items-center gap-2">
        <span className="font-mono text-xs text-gray-300 break-all">
          {revealed ? license.key : maskKey(license.key)}
        </span>
        <button type="button" onClick={() => setRevealed(r => !r)}
          className="shrink-0 text-xs text-gray-500 hover:text-gray-300 transition-colors"
          title={revealed ? 'Hide' : 'Reveal'}>
          {revealed ? '🙈' : '👁'}
        </button>
      </div>
      <div className="flex shrink-0 flex-wrap items-center gap-2">
        <span className={`a-badge ${licenseStatusClass[license.status] ?? 'bg-gray-500/15 text-gray-300'}`}>
          {license.status}
        </span>
        {license.machine_id
          ? <span className="a-badge bg-amber-500/15 text-amber-300">🖥 Bound</span>
          : <span className="a-badge bg-blue-500/15 text-blue-300">Unbound</span>
        }
      </div>
      <RefreshBtn licenseId={license.id} refreshing={refreshing} onRefresh={onRefresh} />
    </div>
  );
}

function PurchaseRow({ p }: { p: Purchase }) {
  const licenses = p.licenses ?? [];
  const [open, setOpen] = useState(false);
  const [refreshing, setRefreshing] = useState<number | null>(null);

  function doRefresh(licenseId: number) {
    if (refreshing !== null) return;
    setRefreshing(licenseId);
    router.post(
      route('admin.licenses.refresh', licenseId),
      {},
      { preserveScroll: true, onFinish: () => setRefreshing(null) }
    );
  }

  return (
    <>
      <tr className="hover:bg-white/[0.02] transition-colors">
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
        <td className="a-td text-center text-sm">{p.quantity ?? 1}</td>
        <td className="a-td">
          <span className={`a-badge ${statusClass[p.status] ?? 'bg-gray-500/15 text-gray-300'}`}>
            {p.status}
          </span>
        </td>
        <td className="a-td font-mono text-xs text-gray-400">{p.paypal_order_id ?? '—'}</td>
        <td className="a-td text-xs text-gray-400">{formatDate(p.created_at, true)}</td>

        {/* Keys / Refresh column */}
        <td className="a-td">
          {licenses.length === 0 && (
            <span className="text-xs text-gray-600">no keys</span>
          )}

          {/* Single license → Refresh button directly in the row */}
          {licenses.length === 1 && (
            <div className="flex flex-col gap-1.5">
              <div className="flex flex-wrap items-center gap-1.5">
                <span className={`a-badge text-[10px] ${licenseStatusClass[licenses[0].status] ?? ''}`}>
                  {licenses[0].status}
                </span>
                {licenses[0].machine_id
                  ? <span className="a-badge bg-amber-500/15 text-amber-300 text-[10px]">🖥 Bound</span>
                  : <span className="a-badge bg-blue-500/15 text-blue-300 text-[10px]">Unbound</span>
                }
              </div>
              <RefreshBtn
                licenseId={licenses[0].id}
                refreshing={refreshing === licenses[0].id}
                onRefresh={() => doRefresh(licenses[0].id)}
              />
            </div>
          )}

          {/* Pack (multiple keys) → expand toggle */}
          {licenses.length > 1 && (
            <button
              type="button"
              onClick={() => setOpen(o => !o)}
              className="inline-flex items-center gap-1.5 rounded-lg border border-blue-500/30 bg-blue-500/10 px-3 py-1.5 text-xs font-medium text-blue-300 transition hover:bg-blue-500/20"
            >
              <svg className={`h-3.5 w-3.5 transition-transform ${open ? 'rotate-180' : ''}`}
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
              </svg>
              {licenses.length} keys
            </button>
          )}
        </td>
      </tr>

      {/* Expanded panel for packs */}
      {open && licenses.length > 1 && (
        <tr>
          <td colSpan={9} className="px-4 pb-4 pt-1 bg-white/[0.01]">
            <div className="ml-4 space-y-2 border-l-2 border-blue-500/20 pl-4">
              <p className="text-xs font-medium uppercase tracking-wide text-gray-500 mb-2">
                Pack Keys — {p.addon?.name} — pick which key to refresh
              </p>
              {licenses.map((lic) => (
                <LicenseRow
                  key={lic.id}
                  license={lic}
                  refreshing={refreshing === lic.id}
                  onRefresh={() => doRefresh(lic.id)}
                />
              ))}
            </div>
          </td>
        </tr>
      )}
    </>
  );
}

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
                  <th className="a-th text-center">Qty</th>
                  <th className="a-th">Status</th>
                  <th className="a-th">PayPal</th>
                  <th className="a-th">Date</th>
                  <th className="a-th">Keys</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {purchases.data.map((p) => (
                  <PurchaseRow key={p.id} p={p} />
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
