import { Head, useForm } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import { TextInput } from '../../Components/Form';
import SettingsTabs from './SettingsTabs';
import { useRoute } from '../../lib/utils';

const fields: { key: keyof SocialSettings; label: string; type?: string }[] = [
  { key: 'social_twitter', label: 'Twitter / X' },
  { key: 'social_github', label: 'GitHub' },
  { key: 'social_instagram', label: 'Instagram' },
  { key: 'social_linkedin', label: 'LinkedIn' },
  { key: 'social_youtube', label: 'YouTube' },
  { key: 'social_behance', label: 'Behance' },
  { key: 'social_whatsapp', label: 'WhatsApp (number or link)', type: 'text' },
  { key: 'social_facebook', label: 'Facebook' },
  { key: 'social_dribbble', label: 'Dribbble' },
  { key: 'social_artstation', label: 'ArtStation' },
  { key: 'social_sketchfab', label: 'Sketchfab' },
];

interface SocialSettings {
  social_twitter: string;
  social_github: string;
  social_instagram: string;
  social_linkedin: string;
  social_youtube: string;
  social_behance: string;
  social_whatsapp: string;
  social_facebook: string;
  social_dribbble: string;
  social_artstation: string;
  social_sketchfab: string;
}

export default function Social({ settings }: { settings: SocialSettings }) {
  const route = useRoute();
  const { data, setData, put, processing, errors } = useForm({ ...settings });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    put(route('admin.settings.social.update'));
  };

  return (
    <>
      <Head title="Social Links" />
      <PageHeader title="Settings" />
      <SettingsTabs active="social" />

      <form onSubmit={submit} className="a-card max-w-3xl space-y-5 p-6">
        <h3 className="text-sm font-semibold text-white">Social media links</h3>
        <p className="text-sm text-gray-400">Leave a field blank to hide that icon site-wide.</p>
        <div className="grid gap-4 sm:grid-cols-2">
          {fields.map((f) => (
            <TextInput
              key={f.key}
              label={f.label}
              type={f.type ?? 'url'}
              value={data[f.key] ?? ''}
              onChange={(e) => setData(f.key, e.target.value)}
              error={errors[f.key]}
            />
          ))}
        </div>
        <div className="flex justify-end border-t border-white/5 pt-4">
          <button type="submit" disabled={processing} className="a-btn-pri">
            {processing ? 'Saving…' : 'Save links'}
          </button>
        </div>
      </form>
    </>
  );
}
