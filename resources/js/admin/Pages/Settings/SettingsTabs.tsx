import { Link } from '@inertiajs/react';
import { useRoute } from '../../lib/utils';

const tabs = [
  { key: 'hero', label: 'Hero' },
  { key: 'general', label: 'General' },
  { key: 'about', label: 'About' },
  { key: 'social', label: 'Social' },
  { key: 'payment', label: 'Payment' },
  { key: 'account', label: 'Account' },
] as const;

export default function SettingsTabs({ active }: { active: (typeof tabs)[number]['key'] }) {
  const route = useRoute();
  return (
    <nav className="mb-6 flex flex-wrap gap-1 border-b border-white/5">
      {tabs.map((t) => (
        <Link
          key={t.key}
          href={route(`admin.settings.${t.key}`)}
          className={`px-4 py-2 text-sm font-medium transition-colors ${
            active === t.key
              ? 'border-b-2 border-indigo-400 text-white'
              : 'text-gray-400 hover:text-white'
          }`}
        >
          {t.label}
        </Link>
      ))}
    </nav>
  );
}
