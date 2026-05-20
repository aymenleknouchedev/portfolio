import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import PageHeader from '../../Components/PageHeader';
import { TextInput, Textarea } from '../../Components/Form';
import ImageInput from '../../Components/ImageInput';
import SettingsTabs from './SettingsTabs';
import { useRoute } from '../../lib/utils';

interface GeneralSettings {
  site_name: string;
  contact_email: string;
  contact_phone: string;
  favicon: string | null;
  auth_heading: string;
  auth_description: string;
  auth_feature_1: string;
  auth_feature_2: string;
}

export default function General({ settings }: { settings: GeneralSettings }) {
  const route = useRoute();
  const [data, setData] = useState({ ...settings, favicon: null as File | null });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [processing, setProcessing] = useState(false);

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('site_name', data.site_name);
    fd.append('contact_email', data.contact_email ?? '');
    fd.append('contact_phone', data.contact_phone ?? '');
    fd.append('auth_heading', data.auth_heading ?? '');
    fd.append('auth_description', data.auth_description ?? '');
    fd.append('auth_feature_1', data.auth_feature_1 ?? '');
    fd.append('auth_feature_2', data.auth_feature_2 ?? '');
    if (data.favicon) fd.append('favicon', data.favicon);
    fd.append('_method', 'PUT');

    setProcessing(true);
    router.post(route('admin.settings.general.update'), fd, {
      forceFormData: true,
      onError: (errs) => setErrors(errs as Record<string, string>),
      onFinish: () => setProcessing(false),
    });
  };

  return (
    <>
      <Head title="General Settings" />
      <PageHeader title="Settings" />
      <SettingsTabs active="general" />

      <form onSubmit={submit} className="grid gap-5 lg:grid-cols-3">
        <div className="a-card space-y-5 p-6 lg:col-span-2">
          <h3 className="text-sm font-semibold text-white">Site identity</h3>
          <TextInput label="Site name" required value={data.site_name}
            onChange={(e) => setData({ ...data, site_name: e.target.value })} error={errors.site_name} />
          <div className="grid gap-4 sm:grid-cols-2">
            <TextInput label="Contact email" type="email" value={data.contact_email}
              onChange={(e) => setData({ ...data, contact_email: e.target.value })} error={errors.contact_email} />
            <TextInput label="Contact phone" value={data.contact_phone}
              onChange={(e) => setData({ ...data, contact_phone: e.target.value })} error={errors.contact_phone} />
          </div>

          <h3 className="border-t border-white/5 pt-5 text-sm font-semibold text-white">Auth page content</h3>
          <TextInput label="Auth heading" value={data.auth_heading}
            onChange={(e) => setData({ ...data, auth_heading: e.target.value })} error={errors.auth_heading} />
          <Textarea label="Auth description" rows={3} value={data.auth_description}
            onChange={(e) => setData({ ...data, auth_description: e.target.value })} error={errors.auth_description} />
          <div className="grid gap-4 sm:grid-cols-2">
            <TextInput label="Feature 1" value={data.auth_feature_1}
              onChange={(e) => setData({ ...data, auth_feature_1: e.target.value })} error={errors.auth_feature_1} />
            <TextInput label="Feature 2" value={data.auth_feature_2}
              onChange={(e) => setData({ ...data, auth_feature_2: e.target.value })} error={errors.auth_feature_2} />
          </div>
        </div>

        <div className="space-y-5">
          <div className="a-card space-y-5 p-6">
            <ImageInput label="Favicon" existing={settings.favicon}
              onChange={(f) => setData({ ...data, favicon: f })} error={errors.favicon} />
          </div>
          <button type="submit" disabled={processing} className="a-btn-pri w-full">
            {processing ? 'Saving…' : 'Save general settings'}
          </button>
        </div>
      </form>
    </>
  );
}
