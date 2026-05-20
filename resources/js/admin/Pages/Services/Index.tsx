import { Head, Link } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import DeleteButton from '../../Components/DeleteButton';
import EmptyState from '../../Components/EmptyState';
import { storageUrl, useRoute } from '../../lib/utils';

interface Service {
  id: number;
  title: string;
  slug: string;
  price_range: string | null;
  example_image: string | null;
  is_active: boolean;
  whatsapp_number: string | null;
}

export default function Index({ services }: { services: Service[] }) {
  const route = useRoute();

  return (
    <>
      <Head title="Services" />
      <PageHeader title="Services" description="Offerings featured on the public site.">
        <Link href={route('admin.services.create')} className="a-btn-pri">
          + New Service
        </Link>
      </PageHeader>

      {services.length === 0 ? (
        <EmptyState title="No services yet." />
      ) : (
        <div className="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
          {services.map((s) => (
            <div key={s.id} className="a-card overflow-hidden">
              {s.example_image && (
                <img src={storageUrl(s.example_image)!} alt="" className="h-36 w-full object-cover" />
              )}
              <div className="space-y-2 p-4">
                <div className="flex items-start justify-between gap-2">
                  <h3 className="font-semibold text-white">{s.title}</h3>
                  {s.is_active ? (
                    <span className="a-badge bg-emerald-500/15 text-emerald-300">Active</span>
                  ) : (
                    <span className="a-badge bg-gray-500/15 text-gray-400">Hidden</span>
                  )}
                </div>
                {s.price_range && <p className="text-xs text-gray-400">{s.price_range}</p>}
                {s.whatsapp_number && <p className="text-xs text-gray-500">WhatsApp: {s.whatsapp_number}</p>}
                <div className="flex gap-2 pt-2">
                  <Link href={route('admin.services.edit', s.id)} className="a-btn-sec flex-1 justify-center">
                    Edit
                  </Link>
                  <DeleteButton url={route('admin.services.destroy', s.id)} />
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </>
  );
}
