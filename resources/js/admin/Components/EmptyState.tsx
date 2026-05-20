import React from 'react';

interface Props {
  children?: React.ReactNode;
  title?: string;
}

export default function EmptyState({ title = 'No records yet.', children }: Props) {
  return (
    <div className="rounded-xl border border-dashed border-white/10 bg-white/[0.02] px-6 py-16 text-center">
      <p className="text-sm text-gray-400">{title}</p>
      {children && <div className="mt-4">{children}</div>}
    </div>
  );
}
