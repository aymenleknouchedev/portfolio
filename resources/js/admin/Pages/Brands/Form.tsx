import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import PageHeader from '../../Components/PageHeader';
import { TextInput, Checkbox } from '../../Components/Form';
import ImageInput from '../../Components/ImageInput';
import { useRoute } from '../../lib/utils';

interface Brand {
  id: number;
  name: string;
  logo: string;
  url: string | null;
  sort_order: number;
  is_active: boolean;
}

export default function Form({ brand }: { brand?: Brand }) {
  const route = useRoute();
  const isEdit = !!brand;

  const [data, setData] = useState({
    name: brand?.name ?? '',
    url: brand?.url ?? '',
    sort_order: brand?.sort_order ?? 0,
    is_active: brand?.is_active ?? true,
    logo: null as File | null,
  });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [processing, setProcessing] = useState(false);

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('name', data.name);
    fd.append('url', data.url ?? '');
    fd.append('sort_order', String(data.sort_order ?? 0));
    if (data.is_active) fd.append('is_active', '1');
    if (data.logo) fd.append('logo', data.logo);
    if (isEdit) fd.append('_method', 'PUT');

    setProcessing(true);
    router.post(isEdit ? route('admin.brands.update', brand!.id) : route('admin.brands.store'), fd, {
      forceFormData: true,
      onError: (errs) => setErrors(errs as Record<string, string>),
      onFinish: () => setProcessing(false),
    });
  };

  return (
    <>
      <Head title={isEdit ? 'Edit Brand' : 'New Brand'} />
      <PageHeader
        title={isEdit ? 'Edit Brand' : 'New Brand'}
        backHref={route('admin.brands.index')}
      />

      <form onSubmit={submit} className="grid gap-5 lg:grid-cols-3">
        <div className="a-card space-y-5 p-6 lg:col-span-2">
          <TextInput
            label="Name"
            required
            value={data.name}
            onChange={(e) => setData({ ...data, name: e.target.value })}
            error={errors.name}
          />
          <TextInput
            label="URL"
            type="url"
            placeholder="https://…"
            value={data.url}
            onChange={(e) => setData({ ...data, url: e.target.value })}
            error={errors.url}
          />
          <TextInput
            label="Sort order"
            type="number"
            value={data.sort_order}
            onChange={(e) => setData({ ...data, sort_order: parseInt(e.target.value || '0', 10) })}
            error={errors.sort_order}
          />
          <Checkbox
            label="Active"
            checked={data.is_active}
            onChange={(v) => setData({ ...data, is_active: v })}
          />
        </div>
        <div className="a-card space-y-5 p-6">
          <ImageInput
            label="Logo"
            existing={brand?.logo ?? null}
            onChange={(f) => setData({ ...data, logo: f })}
            error={errors.logo}
            hint="Transparent PNG/SVG recommended."
          />
          <button type="submit" disabled={processing} className="a-btn-pri w-full">
            {processing ? 'Saving…' : isEdit ? 'Save changes' : 'Create brand'}
          </button>
        </div>
      </form>
    </>
  );
}
