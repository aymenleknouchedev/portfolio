import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import PageHeader from '../../Components/PageHeader';
import { TextInput, Textarea, Select, Field } from '../../Components/Form';
import ImageInput from '../../Components/ImageInput';
import SettingsTabs from './SettingsTabs';
import { storageUrl, useRoute } from '../../lib/utils';

interface HeroSettings {
  hero_title_line1: string;
  hero_title_line2: string;
  hero_title_line3: string;
  hero_description: string;
  hero_portrait: string | null;
  intro_video: string | null;
  site_logo: string | null;
  primary_color: string;
  hero_title_size: string;
  hero_description_size: string;
}

const titleSizes = ['4xl', '5xl', '6xl', '7xl', '8xl', '9xl'].map((v) => ({ value: v, label: v.toUpperCase() }));
const descSizes = ['sm', 'base', 'lg', 'xl', '2xl'].map((v) => ({ value: v, label: v.toUpperCase() }));

export default function Hero({ settings }: { settings: HeroSettings }) {
  const route = useRoute();
  const [data, setData] = useState({
    ...settings,
    hero_portrait: null as File | null,
    intro_video: null as File | null,
    site_logo: null as File | null,
  });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [processing, setProcessing] = useState(false);

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('hero_title_line1', data.hero_title_line1);
    fd.append('hero_title_line2', data.hero_title_line2);
    fd.append('hero_title_line3', data.hero_title_line3);
    fd.append('hero_description', data.hero_description);
    fd.append('primary_color', data.primary_color);
    fd.append('hero_title_size', data.hero_title_size);
    fd.append('hero_description_size', data.hero_description_size);
    if (data.hero_portrait) fd.append('hero_portrait', data.hero_portrait);
    if (data.intro_video) fd.append('intro_video', data.intro_video);
    if (data.site_logo) fd.append('site_logo', data.site_logo);
    fd.append('_method', 'PUT');

    setProcessing(true);
    router.post(route('admin.settings.hero.update'), fd, {
      forceFormData: true,
      onError: (errs) => setErrors(errs as Record<string, string>),
      onFinish: () => setProcessing(false),
    });
  };

  return (
    <>
      <Head title="Hero Settings" />
      <PageHeader title="Settings" />
      <SettingsTabs active="hero" />

      <form onSubmit={submit} className="grid gap-5 lg:grid-cols-3">
        <div className="a-card space-y-5 p-6 lg:col-span-2">
          <h3 className="text-sm font-semibold text-white">Hero copy</h3>
          <div className="grid gap-4 sm:grid-cols-3">
            <TextInput label="Title line 1" required value={data.hero_title_line1}
              onChange={(e) => setData({ ...data, hero_title_line1: e.target.value })} error={errors.hero_title_line1} />
            <TextInput label="Title line 2" required value={data.hero_title_line2}
              onChange={(e) => setData({ ...data, hero_title_line2: e.target.value })} error={errors.hero_title_line2} />
            <TextInput label="Title line 3" required value={data.hero_title_line3}
              onChange={(e) => setData({ ...data, hero_title_line3: e.target.value })} error={errors.hero_title_line3} />
          </div>
          <Textarea label="Description" required rows={4} value={data.hero_description}
            onChange={(e) => setData({ ...data, hero_description: e.target.value })} error={errors.hero_description} />

          <div className="grid gap-4 sm:grid-cols-2">
            <Select label="Title size" value={data.hero_title_size}
              onChange={(e) => setData({ ...data, hero_title_size: e.target.value })} options={titleSizes} />
            <Select label="Description size" value={data.hero_description_size}
              onChange={(e) => setData({ ...data, hero_description_size: e.target.value })} options={descSizes} />
          </div>

          <Field label="Primary color" error={errors.primary_color}>
            <div className="flex items-center gap-3">
              <input type="color" value={data.primary_color}
                onChange={(e) => setData({ ...data, primary_color: e.target.value })}
                className="h-10 w-16 cursor-pointer rounded border border-white/10 bg-transparent" />
              <input type="text" value={data.primary_color}
                onChange={(e) => setData({ ...data, primary_color: e.target.value })} className="a-input max-w-[140px]" />
            </div>
          </Field>
        </div>

        <div className="space-y-5">
          <div className="a-card space-y-5 p-6">
            <ImageInput label="Hero portrait" existing={settings.hero_portrait}
              onChange={(f) => setData({ ...data, hero_portrait: f })} error={errors.hero_portrait} />
            <ImageInput label="Site logo" existing={settings.site_logo}
              onChange={(f) => setData({ ...data, site_logo: f })} error={errors.site_logo} />
          </div>

          <div className="a-card space-y-3 p-6">
            <Field label="Intro video" error={errors.intro_video} hint="MP4 / WebM / MOV, up to 1 GB.">
              <input type="file" accept="video/mp4,video/webm,video/quicktime"
                onChange={(e) => setData({ ...data, intro_video: e.target.files?.[0] ?? null })} className="a-input" />
            </Field>
            {settings.intro_video && (
              <video src={storageUrl(settings.intro_video) ?? ''} controls className="w-full rounded-lg border border-white/10" />
            )}
          </div>

          <button type="submit" disabled={processing} className="a-btn-pri w-full">
            {processing ? 'Saving…' : 'Save hero settings'}
          </button>
        </div>
      </form>
    </>
  );
}
