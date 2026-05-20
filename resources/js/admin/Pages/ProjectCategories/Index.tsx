import { Head, Link } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import DeleteButton from '../../Components/DeleteButton';
import EmptyState from '../../Components/EmptyState';
import { formatDate, useRoute } from '../../lib/utils';

interface Category {
  id: number;
  name: string;
  slug: string;
  is_active: boolean;
  projects_count: number;
  created_at: string;
}

export default function Index({ categories }: { categories: Category[] }) {
  const route = useRoute();

  return (
    <>
      <Head title="Project Categories" />
      <PageHeader title="Project Categories" description="Buckets used in the portfolio filters.">
        <Link href={route('admin.project-categories.create')} className="a-btn-pri">
          + New Category
        </Link>
      </PageHeader>

      {categories.length === 0 ? (
        <EmptyState title="No project categories yet." />
      ) : (
        <div className="a-card overflow-hidden">
          <table className="w-full">
            <thead>
              <tr className="border-b border-white/5">
                <th className="a-th">Name</th>
                <th className="a-th">Slug</th>
                <th className="a-th">Projects</th>
                <th className="a-th">Active</th>
                <th className="a-th">Created</th>
                <th className="a-th text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-white/5">
              {categories.map((c) => (
                <tr key={c.id}>
                  <td className="a-td font-medium text-white">{c.name}</td>
                  <td className="a-td text-gray-400">{c.slug}</td>
                  <td className="a-td">{c.projects_count}</td>
                  <td className="a-td">
                    {c.is_active ? (
                      <span className="a-badge bg-emerald-500/15 text-emerald-300">Active</span>
                    ) : (
                      <span className="a-badge bg-gray-500/15 text-gray-400">Hidden</span>
                    )}
                  </td>
                  <td className="a-td text-gray-400">{formatDate(c.created_at)}</td>
                  <td className="a-td">
                    <div className="flex justify-end gap-2">
                      <Link href={route('admin.project-categories.edit', c.id)} className="a-btn-sec">
                        Edit
                      </Link>
                      <DeleteButton url={route('admin.project-categories.destroy', c.id)} />
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
