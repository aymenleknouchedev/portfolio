import { Head, useForm, usePage } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import { TextInput } from '../../Components/Form';
import SettingsTabs from './SettingsTabs';
import { useRoute } from '../../lib/utils';
import type { SharedProps } from '../../types';

export default function Account() {
  const route = useRoute();
  const { auth } = usePage<SharedProps>().props;

  const { data, setData, put, processing, errors, reset } = useForm({
    name: auth.user?.name ?? '',
    email: auth.user?.email ?? '',
    current_password: '',
    password: '',
    password_confirmation: '',
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    put(route('admin.settings.account.update'), {
      onSuccess: () => reset('current_password', 'password', 'password_confirmation'),
    });
  };

  return (
    <>
      <Head title="Account Settings" />
      <PageHeader title="Settings" />
      <SettingsTabs active="account" />

      <form onSubmit={submit} className="a-card max-w-2xl space-y-5 p-6">
        <h3 className="text-sm font-semibold text-white">Profile</h3>
        <TextInput label="Name" required value={data.name}
          onChange={(e) => setData('name', e.target.value)} error={errors.name} />
        <TextInput label="Email" type="email" required value={data.email}
          onChange={(e) => setData('email', e.target.value)} error={errors.email} />

        <h3 className="border-t border-white/5 pt-5 text-sm font-semibold text-white">Change password</h3>
        <p className="text-sm text-gray-400">Leave the password fields blank to keep your current password.</p>
        <TextInput label="Current password" type="password" value={data.current_password}
          onChange={(e) => setData('current_password', e.target.value)} error={errors.current_password}
          autoComplete="current-password" />
        <div className="grid gap-4 sm:grid-cols-2">
          <TextInput label="New password" type="password" value={data.password}
            onChange={(e) => setData('password', e.target.value)} error={errors.password}
            autoComplete="new-password" />
          <TextInput label="Confirm new password" type="password" value={data.password_confirmation}
            onChange={(e) => setData('password_confirmation', e.target.value)}
            autoComplete="new-password" />
        </div>

        <div className="flex justify-end border-t border-white/5 pt-4">
          <button type="submit" disabled={processing} className="a-btn-pri">
            {processing ? 'Saving…' : 'Save account'}
          </button>
        </div>
      </form>
    </>
  );
}
