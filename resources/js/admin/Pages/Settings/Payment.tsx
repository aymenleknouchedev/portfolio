import { Head } from '@inertiajs/react';
import PageHeader from '../../Components/PageHeader';
import SettingsTabs from './SettingsTabs';

interface PaymentSettings {
  paypal_mode: string;
  paypal_client_id: string;
  paypal_client_secret: string;
}

function mask(value: string): string {
  if (!value) return '—';
  if (value.length <= 8) return '••••••••';
  return value.slice(0, 4) + '…' + value.slice(-4);
}

export default function Payment({ settings }: { settings: PaymentSettings }) {
  return (
    <>
      <Head title="Payment Settings" />
      <PageHeader title="Settings" />
      <SettingsTabs active="payment" />

      <div className="a-card max-w-3xl space-y-5 p-6">
        <div className="rounded-lg border border-amber-500/30 bg-amber-500/5 p-4 text-sm text-amber-200">
          <strong>Read-only.</strong> PayPal credentials are managed via the server <code>.env</code> file.
          Updates submitted from this page will be rejected.
        </div>

        <dl className="divide-y divide-white/5 text-sm">
          <div className="grid grid-cols-3 gap-4 py-3">
            <dt className="text-gray-400">Mode</dt>
            <dd className="col-span-2 text-white">
              <span className={`a-badge ${settings.paypal_mode === 'live' ? 'bg-emerald-500/15 text-emerald-300' : 'bg-amber-500/15 text-amber-300'}`}>
                {settings.paypal_mode}
              </span>
            </dd>
          </div>
          <div className="grid grid-cols-3 gap-4 py-3">
            <dt className="text-gray-400">Client ID</dt>
            <dd className="col-span-2 break-all font-mono text-white">{mask(settings.paypal_client_id)}</dd>
          </div>
          <div className="grid grid-cols-3 gap-4 py-3">
            <dt className="text-gray-400">Client secret</dt>
            <dd className="col-span-2 font-mono text-white">{mask(settings.paypal_client_secret)}</dd>
          </div>
        </dl>
      </div>
    </>
  );
}
