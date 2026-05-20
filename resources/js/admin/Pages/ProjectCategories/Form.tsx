import { Head, useForm } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import { TextInput, Textarea, Checkbox } from '../../Components/Form';
import { useRoute } from '../../lib/utils';

interface Category {
  id: number;
  name: string;
  description: string | null;
  is_active: boolean;
}

export default function Form({ category }: { category?: Category }) {
  const route = useRoute();
  const isEdit = !!category;

  const { data, setData, post, put, processing, errors } = useForm({
    name: category?.name ?? '',
    description: category?.description ?? '',
    is_active: category?.is_active ?? true,
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    if (isEdit) put(route('admin.project-categories.update', category!.id));
    else post(route('admin.project-categories.store'));
  };

  return (
    <>
      <Head title={isEdit ? 'Edit Project Category' : 'New Project Category'} />
      <PageHeader
        title={isEdit ? 'Edit Project Category' : 'New Project Category'}
        backHref={route('admin.project-categories.index')}
      />

      <form onSubmit={submit} className="a-card max-w-2xl space-y-5 p-6">
        <TextInput
          label="Name"
          required
          value={data.name}
          onChange={(e) => setData('name', e.target.value)}
          error={errors.name}
        />
        <Textarea
          label="Description"
          value={data.description ?? ''}
          onChange={(e) => setData('description', e.target.value)}
          error={errors.description}
        />
        <Checkbox label="Active" checked={data.is_active} onChange={(v) => setData('is_active', v)} />
        <div className="flex justify-end gap-2 border-t border-white/5 pt-4">
          <button type="submit" disabled={processing} className="a-btn-pri">
            {processing ? 'Saving…' : isEdit ? 'Save changes' : 'Create category'}
          </button>
        </div>
      </form>
    </>
  );
}
