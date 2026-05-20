import { usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import type { SharedProps } from '../types';

export default function Flash() {
  const { props } = usePage<SharedProps>();
  const flash = props.flash ?? {};
  const [shown, setShown] = useState<{ success?: string; error?: string }>({});

  useEffect(() => {
    if (flash.success || flash.error) {
      setShown({ success: flash.success ?? undefined, error: flash.error ?? undefined });
      const t = setTimeout(() => setShown({}), 5000);
      return () => clearTimeout(t);
    }
    return undefined;
  }, [flash.success, flash.error]);

  if (!shown.success && !shown.error) return null;

  return (
    <div className="mb-4 space-y-2">
      {shown.success && (
        <div className="rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-2.5 text-sm text-emerald-300">
          {shown.success}
        </div>
      )}
      {shown.error && (
        <div className="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2.5 text-sm text-red-300">
          {shown.error}
        </div>
      )}
    </div>
  );
}
