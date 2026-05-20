import { Head, Link } from '@inertiajs/react';
import PageHeader from '../Components/PageHeader';
import { formatDate, money, useRoute } from '../lib/utils';

interface Stats {
  total_users: number;
  total_addons: number;
  total_projects: number;
  total_purchases: number;
  total_articles: number;
  total_services: number;
  revenue: number;
  monthly_revenue: number;
  visits_today: number;
  visits_week: number;
  visits_month: number;
  max_revenue: number;
  monthly_chart: { month: string; revenue: number }[];
  recent_purchases: Array<{
    id: number;
    amount: number;
    status: string;
    created_at: string;
    user: { name: string; email: string } | null;
    addon: { name: string } | null;
  }>;
  recent_users: Array<{
    id: number;
    name: string;
    email: string;
    created_at: string;
  }>;
}

const STAT_CARDS: Array<{ key: keyof Stats; label: string; icon: string; format?: 'currency' }> = [
  { key: 'total_users', label: 'Users', icon: '◌' },
  { key: 'total_addons', label: 'Add-ons', icon: '★' },
  { key: 'total_projects', label: 'Projects', icon: '▣' },
  { key: 'total_purchases', label: 'Purchases', icon: '$' },
  { key: 'total_articles', label: 'Articles', icon: '✎' },
  { key: 'total_services', label: 'Services', icon: '✦' },
  { key: 'monthly_revenue', label: 'Revenue (this month)', icon: '◐', format: 'currency' },
  { key: 'revenue', label: 'Lifetime Revenue', icon: '◈', format: 'currency' },
];

export default function Dashboard({ stats }: { stats: Stats }) {
  const route = useRoute();

  return (
    <>
      <Head title="Dashboard" />
      <PageHeader title="Dashboard" description="Overview of activity, sales, and traffic." />

      {/* Stats grid */}
      <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
        {STAT_CARDS.map((c) => {
          const v = stats[c.key] as number;
          const value = c.format === 'currency' ? money(v) : (v ?? 0).toLocaleString();
          return (
            <div key={c.key} className="a-card p-4">
              <div className="flex items-center justify-between">
                <span className="text-xs uppercase tracking-wider text-gray-400">{c.label}</span>
                <span className="text-gray-500">{c.icon}</span>
              </div>
              <div className="mt-2 text-2xl font-semibold text-white">{value}</div>
            </div>
          );
        })}
      </div>

      {/* Traffic + revenue chart */}
      <div className="mt-6 grid gap-4 lg:grid-cols-3">
        <div className="a-card p-4 lg:col-span-2">
          <div className="mb-4 flex items-center justify-between">
            <h3 className="text-sm font-semibold text-white">Revenue (last 6 months)</h3>
            <span className="text-xs text-gray-500">Peak: {money(stats.max_revenue)}</span>
          </div>
          <div className="flex h-48 items-end gap-3">
            {stats.monthly_chart.map((m) => {
              const pct = stats.max_revenue ? (m.revenue / stats.max_revenue) * 100 : 0;
              return (
                <div key={m.month} className="flex flex-1 flex-col items-center gap-1">
                  <div className="flex h-full w-full items-end">
                    <div
                      className="w-full rounded-t"
                      style={{
                        height: `${Math.max(pct, 3)}%`,
                        backgroundColor: 'var(--admin-primary)',
                        opacity: pct < 5 ? 0.3 : 1,
                      }}
                      title={money(m.revenue)}
                    />
                  </div>
                  <span className="text-[10px] uppercase tracking-wider text-gray-500">{m.month}</span>
                </div>
              );
            })}
          </div>
        </div>
        <div className="a-card p-4">
          <h3 className="mb-3 text-sm font-semibold text-white">Site Traffic</h3>
          <ul className="space-y-3 text-sm">
            <li className="flex items-center justify-between">
              <span className="text-gray-400">Today</span>
              <span className="font-semibold text-white">{stats.visits_today.toLocaleString()}</span>
            </li>
            <li className="flex items-center justify-between">
              <span className="text-gray-400">Last 7 days</span>
              <span className="font-semibold text-white">{stats.visits_week.toLocaleString()}</span>
            </li>
            <li className="flex items-center justify-between">
              <span className="text-gray-400">Last 30 days</span>
              <span className="font-semibold text-white">{stats.visits_month.toLocaleString()}</span>
            </li>
          </ul>
        </div>
      </div>

      {/* Recent activity */}
      <div className="mt-6 grid gap-4 lg:grid-cols-2">
        <div className="a-card overflow-hidden">
          <div className="flex items-center justify-between border-b border-white/5 px-4 py-3">
            <h3 className="text-sm font-semibold text-white">Recent Purchases</h3>
            <Link href={route('admin.purchases.index')} className="text-xs text-gray-400 hover:text-white">
              View all →
            </Link>
          </div>
          {stats.recent_purchases.length === 0 ? (
            <p className="px-4 py-6 text-sm text-gray-500">No purchases yet.</p>
          ) : (
            <table className="w-full">
              <thead>
                <tr className="border-b border-white/5">
                  <th className="a-th">User</th>
                  <th className="a-th">Add-on</th>
                  <th className="a-th">Amount</th>
                  <th className="a-th">Status</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {stats.recent_purchases.map((p) => (
                  <tr key={p.id}>
                    <td className="a-td">{p.user?.name ?? '—'}</td>
                    <td className="a-td">{p.addon?.name ?? '—'}</td>
                    <td className="a-td">{money(p.amount)}</td>
                    <td className="a-td">
                      <span
                        className={
                          'a-badge ' +
                          (p.status === 'completed'
                            ? 'bg-emerald-500/15 text-emerald-300'
                            : p.status === 'pending'
                            ? 'bg-amber-500/15 text-amber-300'
                            : 'bg-red-500/15 text-red-300')
                        }
                      >
                        {p.status}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </div>

        <div className="a-card overflow-hidden">
          <div className="flex items-center justify-between border-b border-white/5 px-4 py-3">
            <h3 className="text-sm font-semibold text-white">New Users</h3>
            <Link href={route('admin.users.index')} className="text-xs text-gray-400 hover:text-white">
              View all →
            </Link>
          </div>
          {stats.recent_users.length === 0 ? (
            <p className="px-4 py-6 text-sm text-gray-500">No users yet.</p>
          ) : (
            <table className="w-full">
              <thead>
                <tr className="border-b border-white/5">
                  <th className="a-th">Name</th>
                  <th className="a-th">Email</th>
                  <th className="a-th">Joined</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {stats.recent_users.map((u) => (
                  <tr key={u.id}>
                    <td className="a-td">{u.name}</td>
                    <td className="a-td text-gray-400">{u.email}</td>
                    <td className="a-td text-gray-400">{formatDate(u.created_at)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </div>
      </div>
    </>
  );
}
