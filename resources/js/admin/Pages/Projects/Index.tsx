import { Head, Link } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import DeleteButton from '../../Components/DeleteButton';
import EmptyState from '../../Components/EmptyState';
import { formatDate, storageUrl, useRoute } from '../../lib/utils';

interface Project {
  id: number;
  title: string;
  slug: string;
  hero_image: string | null;
  is_featured: boolean;
  published_at: string | null;
  sort_order: number;
}

export default function Index({
  projects,
  featuredProjects,
}: {
  projects: Project[];
  featuredProjects: Project[];
}) {
  const route = useRoute();

  return (
    <>
      <Head title="Projects" />
      <PageHeader title="Projects" description="Portfolio entries with galleries & process steps.">
        <Link href={route('admin.projects.create')} className="a-btn-pri">
          + New Project
        </Link>
      </PageHeader>

      {featuredProjects.length > 0 && (
        <section className="mb-8">
          <h2 className="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-400">Featured</h2>
          <div className="grid gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            {featuredProjects.map((p) => (
              <div key={p.id} className="a-card overflow-hidden">
                {p.hero_image ? (
                  <img src={storageUrl(p.hero_image)!} alt="" className="h-32 w-full object-cover" />
                ) : (
                  <div className="h-32 w-full bg-white/[0.03]" />
                )}
                <div className="p-3">
                  <h3 className="truncate text-sm font-medium text-white">{p.title}</h3>
                  <Link href={route('admin.projects.edit', p.id)} className="mt-2 inline-block text-xs text-gray-400 hover:text-white">
                    Edit →
                  </Link>
                </div>
              </div>
            ))}
          </div>
        </section>
      )}

      {projects.length === 0 ? (
        <EmptyState title="No projects yet." />
      ) : (
        <div className="a-card overflow-hidden">
          <table className="w-full">
            <thead>
              <tr className="border-b border-white/5">
                <th className="a-th">Title</th>
                <th className="a-th">Slug</th>
                <th className="a-th">Featured</th>
                <th className="a-th">Published</th>
                <th className="a-th text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-white/5">
              {projects.map((p) => (
                <tr key={p.id}>
                  <td className="a-td">
                    <div className="flex items-center gap-3">
                      {p.hero_image && (
                        <img src={storageUrl(p.hero_image)!} alt="" className="h-10 w-14 rounded object-cover" />
                      )}
                      <span className="font-medium text-white">{p.title}</span>
                    </div>
                  </td>
                  <td className="a-td text-gray-400">{p.slug}</td>
                  <td className="a-td">
                    {p.is_featured ? (
                      <span className="a-badge bg-amber-500/15 text-amber-300">Featured</span>
                    ) : (
                      <span className="text-gray-500">—</span>
                    )}
                  </td>
                  <td className="a-td text-gray-400">{formatDate(p.published_at)}</td>
                  <td className="a-td">
                    <div className="flex justify-end gap-2">
                      <a
                        href={route('admin.projects.download-images', p.id)}
                        className="a-btn-sec"
                        title="Download all images as ZIP"
                      >
                        ⬇ ZIP
                      </a>
                      <Link href={route('admin.projects.edit', p.id)} className="a-btn-sec">Edit</Link>
                      <DeleteButton url={route('admin.projects.destroy', p.id)} />
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
