import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import PageHeader from '../../Components/PageHeader';
import { TextInput, Textarea, Checkbox, Select, Field } from '../../Components/Form';
import ImageInput from '../../Components/ImageInput';
import GalleryInput from '../../Components/GalleryInput';
import TagInput from '../../Components/TagInput';
import RichTextEditor from '../../Components/RichTextEditor';
import { useRoute, storageUrl } from '../../lib/utils';

interface Category {
  id: number;
  name: string;
}

interface LicenseTier {
  label: string;
  quantity: number;
  price: number;
}

interface Addon {
  id: number;
  category_id: number;
  name: string;
  description: string | null;
  cover_image: string | null;
  price: number;
  original_price: number | null;
  demo_video_url: string | null;
  features: string[] | null;
  screenshots: string[] | null;
  is_featured: boolean;
  badge_text: string | null;
  requires_license: boolean;
  license_price: number | null;
  license_tiers: LicenseTier[] | null;
  file_path: string | null;
}

export default function Form({ addon, categories }: { addon?: Addon; categories: Category[] }) {
  const route = useRoute();
  const isEdit = !!addon;

  const [data, setData] = useState({
    category_id: addon?.category_id ?? (categories[0]?.id ?? ''),
    name: addon?.name ?? '',
    description: addon?.description ?? '',
    price: addon?.price ?? 0,
    original_price: addon?.original_price ?? '',
    demo_video_url: addon?.demo_video_url ?? '',
    badge_text: addon?.badge_text ?? '',
    is_featured: addon?.is_featured ?? false,
    requires_license: addon?.requires_license ?? false,
    license_price: addon?.license_price ?? '',
    download_url: addon?.file_path && /^https?:\/\//i.test(addon.file_path) ? addon.file_path : '',
    features: addon?.features ?? [],
    license_tiers: addon?.license_tiers ?? [],
    cover_image: null as File | null,
    file: null as File | null,
    remove_file: false,
  });
  const [newScreens, setNewScreens] = useState<File[]>([]);
  const [removedScreens, setRemovedScreens] = useState<string[]>([]);
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [processing, setProcessing] = useState(false);

  const setTier = (i: number, patch: Partial<LicenseTier>) => {
    setData((d) => ({
      ...d,
      license_tiers: d.license_tiers.map((t, idx) => (idx === i ? { ...t, ...patch } : t)),
    }));
  };
  const addTier = () =>
    setData((d) => ({ ...d, license_tiers: [...d.license_tiers, { label: '', quantity: 1, price: 0 }] }));
  const removeTier = (i: number) =>
    setData((d) => ({ ...d, license_tiers: d.license_tiers.filter((_, idx) => idx !== i) }));

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('category_id', String(data.category_id));
    fd.append('name', data.name);
    fd.append('description', data.description ?? '');
    fd.append('price', String(data.price));
    if (data.original_price !== '') fd.append('original_price', String(data.original_price));
    fd.append('demo_video_url', data.demo_video_url ?? '');
    fd.append('badge_text', data.badge_text ?? '');
    if (data.is_featured) fd.append('is_featured', '1');
    if (data.requires_license) fd.append('requires_license', '1');
    if (data.license_price !== '') fd.append('license_price', String(data.license_price));
    if (data.download_url) fd.append('download_url', data.download_url);
    fd.append('features', JSON.stringify(data.features));
    fd.append('license_tiers', JSON.stringify(data.license_tiers));

    if (data.cover_image) fd.append('cover_image', data.cover_image);
    if (data.file) fd.append('file', data.file);
    if (isEdit && data.remove_file) fd.append('remove_file', '1');

    newScreens.forEach((f) => fd.append('screenshots[]', f));
    if (isEdit) {
      fd.append('removed_screenshots', JSON.stringify(removedScreens));
      fd.append('_method', 'PUT');
    }

    setProcessing(true);
    router.post(isEdit ? route('admin.addons.update', addon!.id) : route('admin.addons.store'), fd, {
      forceFormData: true,
      onError: (errs) => setErrors(errs as Record<string, string>),
      onFinish: () => setProcessing(false),
    });
  };

  const existingFileIsUrl = isEdit && addon?.file_path && /^https?:\/\//i.test(addon.file_path);
  const existingFileIsLocal = isEdit && addon?.file_path && !/^https?:\/\//i.test(addon.file_path);

  return (
    <>
      <Head title={isEdit ? 'Edit Add-on' : 'New Add-on'} />
      <PageHeader
        title={isEdit ? 'Edit Add-on' : 'New Add-on'}
        backHref={route('admin.addons.index')}
      />

      <form onSubmit={submit} className="grid gap-5 lg:grid-cols-3">
        <div className="space-y-5 lg:col-span-2">
          <div className="a-card space-y-5 p-6">
            <div className="grid gap-4 sm:grid-cols-2">
              <Select
                label="Category"
                required
                value={data.category_id}
                onChange={(e) => setData({ ...data, category_id: parseInt(e.target.value, 10) })}
                options={categories.map((c) => ({ value: c.id, label: c.name }))}
                error={errors.category_id}
              />
              <TextInput
                label="Name"
                required
                value={data.name}
                onChange={(e) => setData({ ...data, name: e.target.value })}
                error={errors.name}
              />
            </div>
            <Field label="Description" error={errors.description}>
              <RichTextEditor
                value={data.description}
                onChange={(v) => setData((d) => ({ ...d, description: v }))}
                height={400}
              />
            </Field>
            <TagInput
              label="Features"
              value={data.features}
              onChange={(v) => setData({ ...data, features: v })}
              placeholder="High-res textures, 4K maps, …"
            />
          </div>

          <div className="a-card space-y-5 p-6">
            <h3 className="text-sm font-semibold text-white">Pricing</h3>
            <div className="grid gap-4 sm:grid-cols-3">
              <TextInput
                label="Price (USD)"
                type="number"
                step="0.01"
                required
                value={data.price}
                onChange={(e) => setData({ ...data, price: parseFloat(e.target.value || '0') })}
                error={errors.price}
              />
              <TextInput
                label="Original price"
                type="number"
                step="0.01"
                value={data.original_price ?? ''}
                onChange={(e) => setData({ ...data, original_price: e.target.value === '' ? '' : parseFloat(e.target.value) })}
                error={errors.original_price}
                hint="Optional — shown as struck-through."
              />
              <TextInput
                label="Badge text"
                value={data.badge_text}
                onChange={(e) => setData({ ...data, badge_text: e.target.value })}
                error={errors.badge_text}
                hint='e.g. "NEW", "30% off"'
              />
            </div>
            <Checkbox
              label="Featured"
              checked={data.is_featured}
              onChange={(v) => setData({ ...data, is_featured: v })}
            />
          </div>

          <div className="a-card space-y-5 p-6">
            <h3 className="text-sm font-semibold text-white">Licensing</h3>
            <Checkbox
              label="Requires a license"
              checked={data.requires_license}
              onChange={(v) => setData({ ...data, requires_license: v })}
              hint="When enabled, license keys are issued on purchase."
            />
            <TextInput
              label="License price (USD)"
              type="number"
              step="0.01"
              value={data.license_price ?? ''}
              onChange={(e) => setData({ ...data, license_price: e.target.value === '' ? '' : parseFloat(e.target.value) })}
              error={errors.license_price}
            />

            <div>
              <div className="mb-2 flex items-center justify-between">
                <span className="a-label mb-0">License tiers</span>
                <button type="button" className="a-btn-sec text-xs" onClick={addTier}>
                  + Add tier
                </button>
              </div>
              {data.license_tiers.length === 0 ? (
                <p className="text-xs text-gray-500">No tiers — checkout falls back to base price.</p>
              ) : (
                <div className="space-y-2">
                  {data.license_tiers.map((t, i) => (
                    <div key={i} className="grid gap-2 rounded-lg border border-white/10 bg-white/[0.02] p-3 sm:grid-cols-[1fr,100px,120px,auto]">
                      <input
                        className="a-input"
                        placeholder="Label (e.g. Single user)"
                        value={t.label}
                        onChange={(e) => setTier(i, { label: e.target.value })}
                      />
                      <input
                        className="a-input"
                        type="number"
                        min={1}
                        placeholder="Qty"
                        value={t.quantity}
                        onChange={(e) => setTier(i, { quantity: parseInt(e.target.value || '1', 10) })}
                      />
                      <input
                        className="a-input"
                        type="number"
                        step="0.01"
                        placeholder="Price"
                        value={t.price}
                        onChange={(e) => setTier(i, { price: parseFloat(e.target.value || '0') })}
                      />
                      <button type="button" className="a-btn-danger" onClick={() => removeTier(i)}>
                        ×
                      </button>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>

          <div className="a-card space-y-5 p-6">
            <h3 className="text-sm font-semibold text-white">Media</h3>
            <TextInput
              label="Demo video URL"
              value={data.demo_video_url}
              onChange={(e) => setData({ ...data, demo_video_url: e.target.value })}
              error={errors.demo_video_url}
            />
            <GalleryInput
              label="Screenshots"
              existing={addon?.screenshots ?? []}
              removedExisting={removedScreens}
              onRemovedChange={setRemovedScreens}
              newFiles={newScreens}
              onFilesChange={setNewScreens}
            />
          </div>
        </div>

        <div className="space-y-5">
          <div className="a-card space-y-5 p-6">
            <ImageInput
              label="Cover image"
              existing={addon?.cover_image ?? null}
              onChange={(f) => setData({ ...data, cover_image: f })}
              error={errors.cover_image}
            />
          </div>

          <div className="a-card space-y-5 p-6">
            <h3 className="text-sm font-semibold text-white">Downloadable file</h3>
            {isEdit && existingFileIsLocal && !data.remove_file && (
              <div className="rounded-lg border border-emerald-500/20 bg-emerald-500/5 px-3 py-2 text-xs text-emerald-300">
                Local file attached.
              </div>
            )}
            {isEdit && existingFileIsUrl && !data.remove_file && (
              <div className="rounded-lg border border-indigo-500/20 bg-indigo-500/5 px-3 py-2 text-xs text-indigo-300 break-all">
                External URL: {addon!.file_path}
              </div>
            )}

            <div>
              <label className="a-label">Upload file (max 100MB)</label>
              <input
                type="file"
                onChange={(e) => setData({ ...data, file: e.target.files?.[0] ?? null })}
                className="a-input"
              />
              {errors.file && <p className="a-error">{errors.file}</p>}
            </div>
            <TextInput
              label="Or external download URL"
              type="url"
              value={data.download_url}
              onChange={(e) => setData({ ...data, download_url: e.target.value })}
              error={errors.download_url}
            />
            {isEdit && addon?.file_path && (
              <Checkbox
                label="Remove current file / URL"
                checked={data.remove_file}
                onChange={(v) => setData({ ...data, remove_file: v })}
              />
            )}
          </div>

          <button type="submit" disabled={processing} className="a-btn-pri w-full">
            {processing ? 'Saving…' : isEdit ? 'Save changes' : 'Create add-on'}
          </button>
        </div>
      </form>
    </>
  );
}
