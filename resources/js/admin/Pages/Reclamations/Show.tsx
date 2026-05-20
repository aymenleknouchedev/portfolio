import { Head, useForm, router } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import DeleteButton from '../../Components/DeleteButton';
import { Textarea, Select } from '../../Components/Form';
import { formatDate, useRoute } from '../../lib/utils';

interface Reclamation {
  id: number;
  subject: string;
  message: string;
  status: 'open' | 'in_progress' | 'resolved' | 'closed';
  admin_reply: string | null;
  replied_at: string | null;
  created_at: string;
  user: { id: number; name: string; email: string } | null;
  purchase: { id: number; addon: { id: number; name: string } | null } | null;
}

const statusOptions = [
  { value: 'open', label: 'Open' },
  { value: 'in_progress', label: 'In progress' },
  { value: 'resolved', label: 'Resolved' },
  { value: 'closed', label: 'Closed' },
];

export default function Show({ reclamation }: { reclamation: Reclamation }) {
  const route = useRoute();

  const { data, setData, post, processing, errors } = useForm({
    admin_reply: reclamation.admin_reply ?? '',
    status: reclamation.status,
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('admin.reclamations.reply', reclamation.id));
  };

  const setStatus = (status: string) => {
    router.patch(route('admin.reclamations.status', reclamation.id), { status });
  };

  return (
    <>
      <Head title={reclamation.subject} />
      <PageHeader
        title={reclamation.subject}
        backHref={route('admin.reclamations.index')}
      >
        <DeleteButton url={route('admin.reclamations.destroy', reclamation.id)} label="Delete" />
      </PageHeader>

      <div className="grid gap-5 lg:grid-cols-3">
        <div className="space-y-5 lg:col-span-2">
          <div className="a-card p-6">
            <div className="mb-2 flex items-center justify-between">
              <div>
                <div className="font-medium text-white">{reclamation.user?.name ?? 'Unknown'}</div>
                <div className="text-xs text-gray-500">{reclamation.user?.email}</div>
              </div>
              <div className="text-xs text-gray-500">{formatDate(reclamation.created_at, true)}</div>
            </div>
            <p className="whitespace-pre-wrap text-gray-200">{reclamation.message}</p>
          </div>

          {reclamation.admin_reply && (
            <div className="a-card border-l-4 border-l-indigo-500 p-6">
              <div className="mb-2 flex items-center justify-between">
                <div className="text-sm font-medium text-indigo-300">Your previous reply</div>
                {reclamation.replied_at && (
                  <div className="text-xs text-gray-500">{formatDate(reclamation.replied_at, true)}</div>
                )}
              </div>
              <p className="whitespace-pre-wrap text-gray-200">{reclamation.admin_reply}</p>
            </div>
          )}

          <form onSubmit={submit} className="a-card space-y-4 p-6">
            <h3 className="text-sm font-semibold text-white">Reply to client</h3>
            <Textarea
              label="Message"
              required
              rows={6}
              value={data.admin_reply}
              onChange={(e) => setData('admin_reply', e.target.value)}
              error={errors.admin_reply}
            />
            <Select
              label="Status after reply"
              value={data.status}
              onChange={(e) => setData('status', e.target.value as Reclamation['status'])}
              options={statusOptions}
              error={errors.status}
            />
            <button type="submit" disabled={processing} className="a-btn-pri">
              {processing ? 'Sending…' : 'Send reply'}
            </button>
          </form>
        </div>

        <div className="space-y-5">
          <div className="a-card space-y-3 p-6">
            <h3 className="text-sm font-semibold text-white">Quick status</h3>
            <div className="grid grid-cols-2 gap-2">
              {statusOptions.map((o) => (
                <button
                  key={o.value}
                  type="button"
                  onClick={() => setStatus(o.value)}
                  className={`a-btn-sec text-xs ${reclamation.status === o.value ? 'ring-1 ring-indigo-400' : ''}`}
                >
                  {o.label}
                </button>
              ))}
            </div>
          </div>

          {reclamation.purchase && (
            <div className="a-card space-y-2 p-6">
              <h3 className="text-sm font-semibold text-white">Linked purchase</h3>
              <div className="text-sm text-gray-300">#{reclamation.purchase.id}</div>
              {reclamation.purchase.addon && (
                <div className="text-sm text-gray-400">{reclamation.purchase.addon.name}</div>
              )}
            </div>
          )}
        </div>
      </div>
    </>
  );
}
