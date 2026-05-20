import { Head, Link } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import DeleteButton from '../../Components/DeleteButton';
import EmptyState from '../../Components/EmptyState';
import { formatDate, money, storageUrl, useRoute } from '../../lib/utils';

interface Addon {
  id: number;
  name: string;
  slug: string;
  price: number;
  original_price: number | null;
  cover_image: string | null;
  is_featured: boolean;
  badge_text: string | null;
  created_at: string;
  category: { id: number; name: string } | null;
}

export default function Index({ addons }: { addons: Addon[] }) {
  const route = useRoute();
  return (
    <>
      <Head title="Add-ons" />
      <PageHeader title="Add-ons" description="Premium downloadable products.">
        <Link href={route('admin.addons.create')} className="a-btn-pri">+ New Add-on</Link>
      </PageHeader>

      {addons.length === 0 ? (
        <EmptyState title="No add-ons yet." />
      ) : (
        <div className="a-card overflow-hidden">
          <table className="w-full">
            <thead>
              <tr className="border-b border-white/5">
                <th className="a-th">Add-on</th>
                <th className="a-th">Category</th>
                <th className="a-th">Price</th>
                <th className="a-th">Flags</th>
                <th className="a-th">Created</th>
                <th className="a-th text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-white/5">
              {addons.map((a) => (
                <tr key={a.id}>
                  <td className="a-td">
                    <div className="flex items-center gap-3">
                      {a.cover_image ? (
                        <img src={storageUrl(a.cover_image)!} alt="" className="h-10 w-14 rounded object-cover" />
                      ) : (
                        <div className="h-10 w-14 rounded bg-white/[0.05]" />
                      )}
                      <span className="font-medium text-white">{a.name}</span>
                    </div>
                  </td>
                  <td className="a-td text-gray-400">{a.category?.name ?? '—'}</td>
                  <td className="a-td">
                    <span className="font-medium text-white">{money(a.price)}</span>
                    {a.original_price ? (
                      <span className="ml-2 text-xs text-gray-500 line-through">{money(a.original_price)}</span>
                    ) : null}
                  </td>
                  <td className="a-td space-x-1">
                    {a.is_featured && <span className="a-badge bg-amber-500/15 text-amber-300">Featured</span>}
                    {a.badge_text && <span className="a-badge bg-indigo-500/15 text-indigo-300">{a.badge_text}</span>}
                  </td>
                  <td className="a-td text-gray-400">{formatDate(a.created_at)}</td>
                  <td className="a-td">
                    <div className="flex justify-end gap-2">
                      <Link href={route('admin.addons.edit', a.id)} className="a-btn-sec">Edit</Link>
                      <DeleteButton url={route('admin.addons.destroy', a.id)} />
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
