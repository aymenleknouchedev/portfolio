import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import PageHeader from '../../Components/PageHeader';
import { TextInput, Textarea, Checkbox, Select, Field } from '../../Components/Form';
import ImageInput from '../../Components/ImageInput';
import GalleryInput from '../../Components/GalleryInput';
import TagInput from '../../Components/TagInput';
import RichTextEditor from '../../Components/RichTextEditor';
import { useRoute } from '../../lib/utils';

interface Category {
  id: number;
  name: string;
}

interface Project {
  id: number;
  title: string;
  description: string | null;
  category_id: number | null;
  hero_image: string | null;
  hero_video: string | null;
  url: string | null;
  gallery: string[] | null;
  software_used: string[] | null;
  process_steps: string[] | null;
  published_at: string | null;
  is_featured: boolean;
}

export default function Form({ project, categories }: { project?: Project; categories: Category[] }) {
  const route = useRoute();
  const isEdit = !!project;

  const [data, setData] = useState({
    title: project?.title ?? '',
    description: project?.description ?? '',
    category_id: project?.category_id ?? '',
    hero_video: project?.hero_video ?? '',
    url: project?.url ?? '',
    published_at: project?.published_at ? project.published_at.slice(0, 10) : '',
    is_featured: project?.is_featured ?? false,
    software_used: project?.software_used ?? [],
    process_steps: project?.process_steps ?? [],
    hero_image: null as File | null,
  });
  const [gallery, setGallery] = useState<File[]>([]);
  const [removedGallery, setRemovedGallery] = useState<string[]>([]);
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [processing, setProcessing] = useState(false);

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('title', data.title);
    fd.append('description', data.description ?? '');
    fd.append('category_id', String(data.category_id ?? ''));
    fd.append('hero_video', data.hero_video ?? '');
    fd.append('url', data.url ?? '');
    fd.append('published_at', data.published_at ?? '');
    if (data.is_featured) fd.append('is_featured', '1');
    fd.append('software_used', JSON.stringify(data.software_used));
    fd.append('process_steps', JSON.stringify(data.process_steps));
    if (data.hero_image) fd.append('hero_image', data.hero_image);
    gallery.forEach((f) => fd.append('gallery[]', f));
    if (isEdit) {
      fd.append('removed_gallery', JSON.stringify(removedGallery));
      fd.append('_method', 'PUT');
    }

    setProcessing(true);
    router.post(isEdit ? route('admin.projects.update', project!.id) : route('admin.projects.store'), fd, {
      forceFormData: true,
      onError: (errs) => setErrors(errs as Record<string, string>),
      onFinish: () => setProcessing(false),
    });
  };

  return (
    <>
      <Head title={isEdit ? 'Edit Project' : 'New Project'} />
      <PageHeader
        title={isEdit ? 'Edit Project' : 'New Project'}
        backHref={route('admin.projects.index')}
      />

      <form onSubmit={submit} className="grid gap-5 lg:grid-cols-3">
        <div className="space-y-5 lg:col-span-2">
          <div className="a-card space-y-5 p-6">
            <TextInput
              label="Title"
              required
              value={data.title}
              onChange={(e) => setData({ ...data, title: e.target.value })}
              error={errors.title}
            />
            <Field label="Description" error={errors.description}>
              <RichTextEditor
                value={data.description}
                onChange={(v) => setData((d) => ({ ...d, description: v }))}
                uploadRouteName="admin.projects.upload-image"
                height={420}
              />
            </Field>
            <div className="grid gap-4 sm:grid-cols-2">
              <Select
                label="Category"
                placeholder="— None —"
                value={data.category_id ?? ''}
                onChange={(e) => setData({ ...data, category_id: e.target.value ? parseInt(e.target.value, 10) : '' })}
                options={categories.map((c) => ({ value: c.id, label: c.name }))}
                error={errors.category_id}
              />
              <TextInput
                label="Project URL"
                type="url"
                value={data.url}
                onChange={(e) => setData({ ...data, url: e.target.value })}
                error={errors.url}
              />
            </div>
            <div className="grid gap-4 sm:grid-cols-2">
              <TextInput
                label="Hero video URL"
                type="url"
                value={data.hero_video}
                onChange={(e) => setData({ ...data, hero_video: e.target.value })}
                error={errors.hero_video}
              />
              <TextInput
                label="Published at"
                type="date"
                value={data.published_at}
                onChange={(e) => setData({ ...data, published_at: e.target.value })}
                error={errors.published_at}
              />
            </div>
            <Checkbox
              label="Featured project"
              checked={data.is_featured}
              onChange={(v) => setData({ ...data, is_featured: v })}
            />
          </div>

          <div className="a-card space-y-5 p-6">
            <TagInput
              label="Software used"
              value={data.software_used}
              onChange={(v) => setData({ ...data, software_used: v })}
              placeholder="Blender, Houdini, …"
            />
            <TagInput
              label="Process steps"
              value={data.process_steps}
              onChange={(v) => setData({ ...data, process_steps: v })}
              placeholder="Concept, Modeling, Simulation, …"
            />
          </div>

          <div className="a-card space-y-5 p-6">
            <GalleryInput
              label="Gallery"
              existing={project?.gallery ?? []}
              removedExisting={removedGallery}
              onRemovedChange={setRemovedGallery}
              newFiles={gallery}
              onFilesChange={setGallery}
              error={errors.gallery}
            />
          </div>
        </div>

        <div className="space-y-5">
          <div className="a-card space-y-5 p-6">
            <ImageInput
              label="Hero image"
              existing={project?.hero_image ?? null}
              onChange={(f) => setData({ ...data, hero_image: f })}
              error={errors.hero_image}
            />
          </div>
          <button type="submit" disabled={processing} className="a-btn-pri w-full">
            {processing ? 'Saving…' : isEdit ? 'Save changes' : 'Create project'}
          </button>
        </div>
      </form>
    </>
  );
}
