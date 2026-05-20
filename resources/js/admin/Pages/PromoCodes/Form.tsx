import { Head, useForm } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import { TextInput, Textarea, Checkbox, Select } from '../../Components/Form';
import { useRoute } from '../../lib/utils';

interface PromoCode {
  id: number;
  code: string;
  description: string | null;
  type: 'percentage' | 'fixed';
  value: number;
  min_order: number | null;
  max_discount: number | null;
  max_uses: number | null;
  starts_at: string | null;
  expires_at: string | null;
  is_active: boolean;
}

function toDateInput(value: string | null | undefined): string {
  if (!value) return '';
  return value.length >= 10 ? value.slice(0, 10) : value;
}

export default function Form({ promoCode }: { promoCode?: PromoCode }) {
  const route = useRoute();
  const isEdit = !!promoCode;

  const { data, setData, post, put, processing, errors } = useForm({
    code: promoCode?.code ?? '',
    description: promoCode?.description ?? '',
    type: promoCode?.type ?? 'percentage',
    value: promoCode?.value ?? 10,
    min_order: promoCode?.min_order ?? '',
    max_discount: promoCode?.max_discount ?? '',
    max_uses: promoCode?.max_uses ?? '',
    starts_at: toDateInput(promoCode?.starts_at),
    expires_at: toDateInput(promoCode?.expires_at),
    is_active: promoCode?.is_active ?? true,
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    if (isEdit) put(route('admin.promo-codes.update', promoCode!.id));
    else post(route('admin.promo-codes.store'));
  };

  return (
    <>
      <Head title={isEdit ? 'Edit Promo Code' : 'New Promo Code'} />
      <PageHeader
        title={isEdit ? 'Edit Promo Code' : 'New Promo Code'}
        backHref={route('admin.promo-codes.index')}
      />

      <form onSubmit={submit} className="a-card max-w-3xl space-y-5 p-6">
        <div className="grid gap-4 sm:grid-cols-2">
          <TextInput
            label="Code"
            required
            value={data.code}
            onChange={(e) => setData('code', e.target.value.toUpperCase())}
            error={errors.code}
          />
          <Select
            label="Type"
            value={data.type}
            onChange={(e) => setData('type', e.target.value as 'percentage' | 'fixed')}
            options={[
              { value: 'percentage', label: 'Percentage (%)' },
              { value: 'fixed', label: 'Fixed amount (USD)' },
            ]}
            error={errors.type}
          />
        </div>
        <Textarea
          label="Description"
          value={data.description ?? ''}
          onChange={(e) => setData('description', e.target.value)}
          error={errors.description}
        />
        <div className="grid gap-4 sm:grid-cols-3">
          <TextInput
            label="Value"
            type="number"
            step="0.01"
            required
            value={data.value}
            onChange={(e) => setData('value', parseFloat(e.target.value || '0'))}
            error={errors.value}
          />
          <TextInput
            label="Minimum order"
            type="number"
            step="0.01"
            value={data.min_order ?? ''}
            onChange={(e) => setData('min_order', e.target.value === '' ? '' : parseFloat(e.target.value))}
            error={errors.min_order}
          />
          <TextInput
            label="Max discount (percentage only)"
            type="number"
            step="0.01"
            value={data.max_discount ?? ''}
            onChange={(e) => setData('max_discount', e.target.value === '' ? '' : parseFloat(e.target.value))}
            error={errors.max_discount}
          />
        </div>
        <div className="grid gap-4 sm:grid-cols-3">
          <TextInput
            label="Max uses"
            type="number"
            value={data.max_uses ?? ''}
            onChange={(e) => setData('max_uses', e.target.value === '' ? '' : parseInt(e.target.value, 10))}
            error={errors.max_uses}
          />
          <TextInput
            label="Starts at"
            type="date"
            value={data.starts_at}
            onChange={(e) => setData('starts_at', e.target.value)}
            error={errors.starts_at}
          />
          <TextInput
            label="Expires at"
            type="date"
            value={data.expires_at}
            onChange={(e) => setData('expires_at', e.target.value)}
            error={errors.expires_at}
          />
        </div>
        <Checkbox label="Active" checked={data.is_active} onChange={(v) => setData('is_active', v)} />

        <div className="flex justify-end gap-2 border-t border-white/5 pt-4">
          <button type="submit" disabled={processing} className="a-btn-pri">
            {processing ? 'Saving…' : isEdit ? 'Save changes' : 'Create code'}
          </button>
        </div>
      </form>
    </>
  );
}
