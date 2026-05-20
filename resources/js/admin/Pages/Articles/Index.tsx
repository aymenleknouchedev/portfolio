import { Head, Link } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import DeleteButton from '../../Components/DeleteButton';
import EmptyState from '../../Components/EmptyState';
import { formatDate, useRoute } from '../../lib/utils';

interface Article {
  id: number;
  title: string;
  slug: string;
  is_published: boolean;
  published_at: string | null;
  created_at: string;
}

export default function Index({ articles }: { articles: Article[] }) {
  const route = useRoute();
  return (
    <>
      <Head title="Articles" />
      <PageHeader title="Articles" description="Long-form tutorials and posts.">
        <Link href={route('admin.articles.create')} className="a-btn-pri">+ New Article</Link>
      </PageHeader>

      {articles.length === 0 ? (
        <EmptyState title="No articles yet." />
      ) : (
        <div className="a-card overflow-hidden">
          <table className="w-full">
            <thead>
              <tr className="border-b border-white/5">
                <th className="a-th">Title</th>
                <th className="a-th">Slug</th>
                <th className="a-th">Published</th>
                <th className="a-th">Created</th>
                <th className="a-th text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-white/5">
              {articles.map((a) => (
                <tr key={a.id}>
                  <td className="a-td font-medium text-white">{a.title}</td>
                  <td className="a-td text-gray-400">{a.slug}</td>
                  <td className="a-td">
                    {a.is_published ? (
                      <span className="a-badge bg-emerald-500/15 text-emerald-300">
                        {a.published_at ? formatDate(a.published_at) : 'Live'}
                      </span>
                    ) : (
                      <span className="a-badge bg-gray-500/15 text-gray-400">Draft</span>
                    )}
                  </td>
                  <td className="a-td text-gray-400">{formatDate(a.created_at)}</td>
                  <td className="a-td">
                    <div className="flex justify-end gap-2">
                      <Link href={route('admin.articles.edit', a.id)} className="a-btn-sec">Edit</Link>
                      <DeleteButton url={route('admin.articles.destroy', a.id)} />
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
