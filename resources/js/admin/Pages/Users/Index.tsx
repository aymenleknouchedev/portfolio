import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import PageHeader from '../../Components/PageHeader';
import Pagination from '../../Components/Pagination';
import EmptyState from '../../Components/EmptyState';
import { formatDate, useRoute } from '../../lib/utils';
import type { Paginator } from '../../types';

interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  created_at: string;
}

export default function Index({
  users,
  filters,
}: {
  users: Paginator<User>;
  filters: { search?: string };
}) {
  const route = useRoute();
  const [search, setSearch] = useState(filters.search ?? '');

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    router.get(route('admin.users.index'), { search }, { preserveState: true, replace: true });
  };

  return (
    <>
      <Head title="Users" />
      <PageHeader title="Users" description={`${users.total} accounts`}>
        <a
          href={route('admin.users.export') + (search ? `?search=${encodeURIComponent(search)}` : '')}
          className="a-btn-sec"
        >
          Export CSV
        </a>
      </PageHeader>

      <form onSubmit={submit} className="mb-4 flex gap-2">
        <input
          type="search"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          placeholder="Search by name or email…"
          className="a-input max-w-md"
        />
        <button type="submit" className="a-btn-sec">Search</button>
        {search && (
          <button
            type="button"
            className="a-btn-sec"
            onClick={() => {
              setSearch('');
              router.get(route('admin.users.index'), {}, { preserveState: true, replace: true });
            }}
          >
            Clear
          </button>
        )}
      </form>

      {users.data.length === 0 ? (
        <EmptyState title="No users match." />
      ) : (
        <>
          <div className="a-card overflow-hidden">
            <table className="w-full">
              <thead>
                <tr className="border-b border-white/5">
                  <th className="a-th">Name</th>
                  <th className="a-th">Email</th>
                  <th className="a-th">Role</th>
                  <th className="a-th">Registered</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {users.data.map((u) => (
                  <tr key={u.id}>
                    <td className="a-td font-medium text-white">{u.name}</td>
                    <td className="a-td text-gray-300">{u.email}</td>
                    <td className="a-td">
                      <span className={`a-badge ${u.role === 'admin' ? 'bg-indigo-500/15 text-indigo-300' : 'bg-gray-500/15 text-gray-300'}`}>
                        {u.role}
                      </span>
                    </td>
                    <td className="a-td text-xs text-gray-400">{formatDate(u.created_at, true)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          <Pagination links={users.links} />
        </>
      )}
    </>
  );
}
