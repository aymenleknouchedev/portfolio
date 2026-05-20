import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import PageHeader from '../../Components/PageHeader';
import { TextInput, Textarea, Checkbox } from '../../Components/Form';
import ImageInput from '../../Components/ImageInput';
import { useRoute } from '../../lib/utils';

interface Service {
  id: number;
  title: string;
  description: string | null;
  price_range: string | null;
  example_image: string | null;
  is_active: boolean;
  whatsapp_number: string | null;
}

export default function Form({ service }: { service?: Service }) {
  const route = useRoute();
  const isEdit = !!service;

  const [data, setData] = useState({
    title: service?.title ?? '',
    description: service?.description ?? '',
    price_range: service?.price_range ?? '',
    whatsapp_number: service?.whatsapp_number ?? '',
    is_active: service?.is_active ?? true,
    example_image: null as File | null,
  });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [processing, setProcessing] = useState(false);

  const set = <K extends keyof typeof data>(k: K, v: (typeof data)[K]) => setData((d) => ({ ...d, [k]: v }));

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('title', data.title);
    fd.append('description', data.description ?? '');
    fd.append('price_range', data.price_range ?? '');
    fd.append('whatsapp_number', data.whatsapp_number ?? '');
    if (data.is_active) fd.append('is_active', '1');
    if (data.example_image) fd.append('example_image', data.example_image);
    if (isEdit) fd.append('_method', 'PUT');

    setProcessing(true);
    router.post(isEdit ? route('admin.services.update', service!.id) : route('admin.services.store'), fd, {
      forceFormData: true,
      onError: (errs) => setErrors(errs as Record<string, string>),
      onFinish: () => setProcessing(false),
    });
  };

  return (
    <>
      <Head title={isEdit ? 'Edit Service' : 'New Service'} />
      <PageHeader
        title={isEdit ? 'Edit Service' : 'New Service'}
        backHref={route('admin.services.index')}
      />

      <form onSubmit={submit} className="grid gap-5 lg:grid-cols-3">
        <div className="a-card space-y-5 p-6 lg:col-span-2">
          <TextInput
            label="Title"
            required
            value={data.title}
            onChange={(e) => set('title', e.target.value)}
            error={errors.title}
          />
          <Textarea
            label="Description"
            value={data.description}
            onChange={(e) => set('description', e.target.value)}
            error={errors.description}
          />
          <div className="grid gap-4 sm:grid-cols-2">
            <TextInput
              label="Price range"
              placeholder="e.g. $500 – $2000"
              value={data.price_range}
              onChange={(e) => set('price_range', e.target.value)}
              error={errors.price_range}
            />
            <TextInput
              label="WhatsApp number"
              placeholder="+213…"
              value={data.whatsapp_number}
              onChange={(e) => set('whatsapp_number', e.target.value)}
              error={errors.whatsapp_number}
            />
          </div>
          <Checkbox label="Active" checked={data.is_active} onChange={(v) => set('is_active', v)} />
        </div>

        <div className="a-card space-y-5 p-6">
          <ImageInput
            label="Example image"
            existing={service?.example_image ?? null}
            onChange={(f) => set('example_image', f)}
            error={errors.example_image}
          />
          <button type="submit" disabled={processing} className="a-btn-pri w-full">
            {processing ? 'Saving…' : isEdit ? 'Save changes' : 'Create service'}
          </button>
        </div>
      </form>
    </>
  );
}
