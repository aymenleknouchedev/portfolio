import { Head } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import Pagination from '../../Components/Pagination';
import EmptyState from '../../Components/EmptyState';
import { formatDate } from '../../lib/utils';
import type { Paginator } from '../../types';

interface Entry {
  id: number;
  email: string;
  course_name: string | null;
  created_at: string;
}

export default function Index({ entries }: { entries: Paginator<Entry> }) {
  return (
    <>
      <Head title="Waitlist" />
      <PageHeader title="Waitlist" description={`${entries.total} signups`} />

      {entries.data.length === 0 ? (
        <EmptyState title="No waitlist entries yet." />
      ) : (
        <>
          <div className="a-card overflow-hidden">
            <table className="w-full">
              <thead>
                <tr className="border-b border-white/5">
                  <th className="a-th">Email</th>
                  <th className="a-th">Course / Interest</th>
                  <th className="a-th">Joined</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {entries.data.map((e) => (
                  <tr key={e.id}>
                    <td className="a-td font-medium text-white">{e.email}</td>
                    <td className="a-td text-gray-300">{e.course_name ?? '—'}</td>
                    <td className="a-td text-xs text-gray-400">{formatDate(e.created_at, true)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          <Pagination links={entries.links} />
        </>
      )}
    </>
  );
}
