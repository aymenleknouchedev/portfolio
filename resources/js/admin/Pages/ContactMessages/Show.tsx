import { Head } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import DeleteButton from '../../Components/DeleteButton';
import { formatDate, useRoute } from '../../lib/utils';

interface Message {
  id: number;
  name: string;
  email: string;
  subject: string | null;
  message: string;
  created_at: string;
}

export default function Show({ contactMessage }: { contactMessage: Message }) {
  const route = useRoute();
  return (
    <>
      <Head title={contactMessage.subject ?? 'Message'} />
      <PageHeader
        title={contactMessage.subject ?? '(no subject)'}
        backHref={route('admin.contact-messages.index')}
      >
        <DeleteButton
          url={route('admin.contact-messages.destroy', contactMessage.id)}
          label="Delete message"
        />
      </PageHeader>

      <div className="a-card max-w-3xl space-y-4 p-6">
        <div className="grid gap-3 sm:grid-cols-3">
          <div>
            <div className="text-xs uppercase tracking-wider text-gray-500">From</div>
            <div className="text-white">{contactMessage.name}</div>
            <a href={`mailto:${contactMessage.email}`} className="text-sm text-indigo-300 hover:underline">
              {contactMessage.email}
            </a>
          </div>
          <div>
            <div className="text-xs uppercase tracking-wider text-gray-500">Received</div>
            <div className="text-gray-300">{formatDate(contactMessage.created_at, true)}</div>
          </div>
          <div>
            <div className="text-xs uppercase tracking-wider text-gray-500">Subject</div>
            <div className="text-gray-300">{contactMessage.subject ?? '—'}</div>
          </div>
        </div>

        <div className="border-t border-white/5 pt-4">
          <div className="mb-2 text-xs uppercase tracking-wider text-gray-500">Message</div>
          <p className="whitespace-pre-wrap text-gray-200">{contactMessage.message}</p>
        </div>

        <div className="flex gap-2 border-t border-white/5 pt-4">
          <a href={`mailto:${contactMessage.email}?subject=Re: ${encodeURIComponent(contactMessage.subject ?? '')}`} className="a-btn-pri">
            Reply via email
          </a>
        </div>
      </div>
    </>
  );
}
