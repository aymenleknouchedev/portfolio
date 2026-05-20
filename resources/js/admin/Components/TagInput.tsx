import React, { useState } from 'react';

interface Props {
  label?: string;
  value: string[];
  onChange: (next: string[]) => void;
  placeholder?: string;
  error?: string;
  hint?: string;
  className?: string;
}

/**
 * Comma/enter-separated tag input — used for `features`, `software_used`, etc.
 */
export default function TagInput({ label, value, onChange, placeholder, error, hint, className }: Props) {
  const [draft, setDraft] = useState('');

  const add = (raw: string) => {
    const tag = raw.trim();
    if (!tag) return;
    if (value.includes(tag)) return;
    onChange([...value, tag]);
    setDraft('');
  };

  const onKey = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if (e.key === 'Enter' || e.key === ',') {
      e.preventDefault();
      add(draft);
    } else if (e.key === 'Backspace' && draft === '' && value.length) {
      onChange(value.slice(0, -1));
    }
  };

  return (
    <div className={className}>
      {label && <div className="a-label">{label}</div>}
      <div className="flex flex-wrap items-center gap-1.5 rounded-lg border border-white/10 bg-white/[0.03] p-2">
        {value.map((tag, i) => (
          <span
            key={tag + i}
            className="inline-flex items-center gap-1 rounded-md bg-white/10 px-2 py-1 text-xs text-gray-100"
          >
            {tag}
            <button
              type="button"
              className="text-gray-400 hover:text-red-400"
              onClick={() => onChange(value.filter((_, idx) => idx !== i))}
              aria-label={`Remove ${tag}`}
            >
              ×
            </button>
          </span>
        ))}
        <input
          value={draft}
          onChange={(e) => setDraft(e.target.value)}
          onKeyDown={onKey}
          onBlur={() => add(draft)}
          placeholder={placeholder ?? 'Type and press Enter…'}
          className="flex-1 min-w-[120px] bg-transparent px-1 py-0.5 text-sm text-white placeholder-gray-500 focus:outline-none"
        />
      </div>
      {hint && !error && <p className="mt-1 text-xs text-gray-500">{hint}</p>}
      {error && <p className="a-error">{error}</p>}
    </div>
  );
}
