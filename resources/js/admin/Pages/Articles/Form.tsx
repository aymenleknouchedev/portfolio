import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import PageHeader from '../../Components/PageHeader';
import { TextInput, Textarea, Checkbox, Field } from '../../Components/Form';
import ImageInput from '../../Components/ImageInput';
import RichTextEditor from '../../Components/RichTextEditor';
import { useRoute } from '../../lib/utils';

interface Article {
  id: number;
  title: string;
  excerpt: string | null;
  content: string | null;
  hero_image: string | null;
  youtube_url: string | null;
  is_published: boolean;
}

export default function Form({ article }: { article?: Article }) {
  const route = useRoute();
  const isEdit = !!article;

  const [data, setData] = useState({
    title: article?.title ?? '',
    excerpt: article?.excerpt ?? '',
    content: article?.content ?? '',
    youtube_url: article?.youtube_url ?? '',
    is_published: article?.is_published ?? false,
    hero_image: null as File | null,
  });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [processing, setProcessing] = useState(false);

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('title', data.title);
    fd.append('excerpt', data.excerpt ?? '');
    fd.append('content', data.content ?? '');
    fd.append('youtube_url', data.youtube_url ?? '');
    if (data.is_published) fd.append('is_published', '1');
    if (data.hero_image) fd.append('hero_image', data.hero_image);
    if (isEdit) fd.append('_method', 'PUT');

    setProcessing(true);
    router.post(isEdit ? route('admin.articles.update', article!.id) : route('admin.articles.store'), fd, {
      forceFormData: true,
      onError: (errs) => setErrors(errs as Record<string, string>),
      onFinish: () => setProcessing(false),
    });
  };

  return (
    <>
      <Head title={isEdit ? 'Edit Article' : 'New Article'} />
      <PageHeader
        title={isEdit ? 'Edit Article' : 'New Article'}
        backHref={route('admin.articles.index')}
      />

      <form onSubmit={submit} className="grid gap-5 lg:grid-cols-3">
        <div className="a-card space-y-5 p-6 lg:col-span-2">
          <TextInput
            label="Title"
            required
            value={data.title}
            onChange={(e) => setData({ ...data, title: e.target.value })}
            error={errors.title}
          />
          <Textarea
            label="Excerpt"
            value={data.excerpt}
            onChange={(e) => setData({ ...data, excerpt: e.target.value })}
            error={errors.excerpt}
            hint="Short blurb shown in listings."
          />
          <Field label="Content" error={errors.content}>
            <RichTextEditor
              value={data.content}
              onChange={(v) => setData((d) => ({ ...d, content: v }))}
              uploadRouteName="admin.articles.upload-image"
              height={500}
            />
          </Field>
        </div>

        <div className="a-card space-y-5 p-6">
          <ImageInput
            label="Hero image"
            existing={article?.hero_image ?? null}
            onChange={(f) => setData({ ...data, hero_image: f })}
            error={errors.hero_image}
          />
          <TextInput
            label="YouTube URL"
            placeholder="https://youtu.be/…"
            value={data.youtube_url}
            onChange={(e) => setData({ ...data, youtube_url: e.target.value })}
            error={errors.youtube_url}
          />
          <Checkbox
            label="Published"
            checked={data.is_published}
            onChange={(v) => setData({ ...data, is_published: v })}
            hint="Unpublished articles are hidden from the public site."
          />
          <button type="submit" disabled={processing} className="a-btn-pri w-full">
            {processing ? 'Saving…' : isEdit ? 'Save changes' : 'Create article'}
          </button>
        </div>
      </form>
    </>
  );
}
