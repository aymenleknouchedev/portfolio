import { Head, useForm } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import { TextInput, Textarea } from '../../Components/Form';
import SettingsTabs from './SettingsTabs';
import { useRoute } from '../../lib/utils';

interface AboutSettings {
  about_title: string;
  about_description_1: string;
  about_description_2: string;
  about_stat_1_number: string;
  about_stat_1_label: string;
  about_stat_2_number: string;
  about_stat_2_label: string;
  about_stat_3_number: string;
  about_stat_3_label: string;
  about_avatar_name: string;
  about_avatar_title: string;
}

export default function About({ settings }: { settings: AboutSettings }) {
  const route = useRoute();
  const { data, setData, put, processing, errors } = useForm({ ...settings });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    put(route('admin.settings.about.update'));
  };

  return (
    <>
      <Head title="About Settings" />
      <PageHeader title="Settings" />
      <SettingsTabs active="about" />

      <form onSubmit={submit} className="a-card max-w-4xl space-y-5 p-6">
        <h3 className="text-sm font-semibold text-white">Studio narrative</h3>
        <TextInput label="Section title" required value={data.about_title}
          onChange={(e) => setData('about_title', e.target.value)} error={errors.about_title} />
        <Textarea label="Description (paragraph 1)" required rows={4} value={data.about_description_1}
          onChange={(e) => setData('about_description_1', e.target.value)} error={errors.about_description_1} />
        <Textarea label="Description (paragraph 2)" rows={4} value={data.about_description_2}
          onChange={(e) => setData('about_description_2', e.target.value)} error={errors.about_description_2} />

        <h3 className="border-t border-white/5 pt-5 text-sm font-semibold text-white">Stats</h3>
        {[1, 2, 3].map((n) => {
          const numKey = `about_stat_${n}_number` as keyof AboutSettings;
          const lblKey = `about_stat_${n}_label` as keyof AboutSettings;
          return (
            <div key={n} className="grid gap-4 sm:grid-cols-2">
              <TextInput label={`Stat ${n} number`} required value={data[numKey]}
                onChange={(e) => setData(numKey, e.target.value)} error={errors[numKey]} />
              <TextInput label={`Stat ${n} label`} required value={data[lblKey]}
                onChange={(e) => setData(lblKey, e.target.value)} error={errors[lblKey]} />
            </div>
          );
        })}

        <h3 className="border-t border-white/5 pt-5 text-sm font-semibold text-white">Avatar caption</h3>
        <div className="grid gap-4 sm:grid-cols-2">
          <TextInput label="Name" required value={data.about_avatar_name}
            onChange={(e) => setData('about_avatar_name', e.target.value)} error={errors.about_avatar_name} />
          <TextInput label="Title" required value={data.about_avatar_title}
            onChange={(e) => setData('about_avatar_title', e.target.value)} error={errors.about_avatar_title} />
        </div>

        <div className="flex justify-end border-t border-white/5 pt-4">
          <button type="submit" disabled={processing} className="a-btn-pri">
            {processing ? 'Saving…' : 'Save about section'}
          </button>
        </div>
      </form>
    </>
  );
}
