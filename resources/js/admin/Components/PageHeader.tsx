import React from 'react';
import { Link } from '@inertiajs/react';

interface Props {
  title: string;
  description?: string;
  children?: React.ReactNode;
  backHref?: string;
}

export default function PageHeader({ title, description, children, backHref }: Props) {
  return (
    <div className="mb-6 flex flex-wrap items-end justify-between gap-4">
      <div>
        {backHref && (
          <Link href={backHref} className="mb-2 inline-flex items-center gap-1 text-xs text-gray-400 hover:text-white">
            ← Back
          </Link>
        )}
        <h1 className="text-2xl font-semibold text-white">{title}</h1>
        {description && <p className="mt-1 text-sm text-gray-400">{description}</p>}
      </div>
      <div className="flex items-center gap-2">{children}</div>
    </div>
  );
}
