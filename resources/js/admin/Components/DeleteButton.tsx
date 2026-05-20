import { router } from '@inertiajs/react';

interface Props {
  url: string;
  message?: string;
  label?: string;
  className?: string;
}

export default function DeleteButton({ url, message, label = 'Delete', className }: Props) {
  const onClick = () => {
    if (!confirm(message ?? 'Delete this item? This cannot be undone.')) return;
    router.delete(url, { preserveScroll: true });
  };
  return (
    <button type="button" onClick={onClick} className={'a-btn-danger ' + (className ?? '')}>
      {label}
    </button>
  );
}
