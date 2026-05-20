import { Head, Link } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import DeleteButton from '../../Components/DeleteButton';
import EmptyState from '../../Components/EmptyState';
import { storageUrl, useRoute } from '../../lib/utils';

interface Brand {
  id: number;
  name: string;
  logo: string;
  url: string | null;
  sort_order: number;
  is_active: boolean;
}

export default function Index({ brands }: { brands: Brand[] }) {
  const route = useRoute();

  return (
    <>
      <Head title="Brands" />
      <PageHeader title="Brands" description="Logos rendered in the trusted-by strip.">
        <Link href={route('admin.brands.create')} className="a-btn-pri">
          + New Brand
        </Link>
      </PageHeader>

      {brands.length === 0 ? (
        <EmptyState title="No brands yet." />
      ) : (
        <div className="grid gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
          {brands.map((b) => (
            <div key={b.id} className="a-card flex flex-col items-center gap-3 p-4 text-center">
              <img
                src={storageUrl(b.logo)!}
                alt={b.name}
                className="h-16 w-full object-contain"
              />
              <h3 className="text-sm font-medium text-white">{b.name}</h3>
              <div className="text-xs text-gray-500">
                Sort: {b.sort_order} ·{' '}
                {b.is_active ? (
                  <span className="text-emerald-300">Active</span>
                ) : (
                  <span className="text-gray-400">Hidden</span>
                )}
              </div>
              <div className="flex w-full gap-2">
                <Link href={route('admin.brands.edit', b.id)} className="a-btn-sec flex-1 justify-center">
                  Edit
                </Link>
                <DeleteButton url={route('admin.brands.destroy', b.id)} />
              </div>
            </div>
          ))}
        </div>
      )}
    </>
  );
}
