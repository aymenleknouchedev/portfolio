import { Head, Link } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import Pagination from '../../Components/Pagination';
import EmptyState from '../../Components/EmptyState';
import { formatDate, useRoute } from '../../lib/utils';
import type { Paginator } from '../../types';

interface Message {
  id: number;
  name: string;
  email: string;
  subject: string | null;
  message: string;
  is_read: boolean;
  created_at: string;
}

export default function Index({
  messages,
  unreadCount,
}: {
  messages: Paginator<Message>;
  unreadCount: number;
}) {
  const route = useRoute();
  return (
    <>
      <Head title="Contact Messages" />
      <PageHeader
        title="Contact Messages"
        description={`${messages.total} total · ${unreadCount} unread`}
      />

      {messages.data.length === 0 ? (
        <EmptyState title="No messages yet." />
      ) : (
        <>
          <div className="a-card overflow-hidden">
            <table className="w-full">
              <thead>
                <tr className="border-b border-white/5">
                  <th className="a-th"></th>
                  <th className="a-th">From</th>
                  <th className="a-th">Subject</th>
                  <th className="a-th">Received</th>
                  <th className="a-th"></th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {messages.data.map((m) => (
                  <tr key={m.id} className={!m.is_read ? 'bg-indigo-500/[0.03]' : ''}>
                    <td className="a-td">
                      {!m.is_read && <span className="inline-block h-2 w-2 rounded-full bg-indigo-400" />}
                    </td>
                    <td className="a-td">
                      <div className={`${!m.is_read ? 'font-semibold' : ''} text-white`}>{m.name}</div>
                      <div className="text-xs text-gray-500">{m.email}</div>
                    </td>
                    <td className="a-td text-gray-300">{m.subject ?? <span className="text-gray-500">(no subject)</span>}</td>
                    <td className="a-td text-xs text-gray-400">{formatDate(m.created_at, true)}</td>
                    <td className="a-td">
                      <Link href={route('admin.contact-messages.show', m.id)} className="a-btn-sec">
                        Open
                      </Link>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          <Pagination links={messages.links} />
        </>
      )}
    </>
  );
}
