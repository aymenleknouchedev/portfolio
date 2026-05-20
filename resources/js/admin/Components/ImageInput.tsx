import React, { useRef, useState } from 'react';
import { storageUrl } from '../lib/utils';

interface Props {
  label?: string;
  existing?: string | null; // storage path
  onChange: (file: File | null) => void;
  accept?: string;
  error?: string;
  hint?: string;
  className?: string;
}

/**
 * Single-image picker with preview (existing or freshly selected).
 */
export default function ImageInput({ label, existing, onChange, accept = 'image/*', error, hint, className }: Props) {
  const inputRef = useRef<HTMLInputElement>(null);
  const [preview, setPreview] = useState<string | null>(storageUrl(existing ?? null));

  const handle = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0] ?? null;
    onChange(file);
    if (file) {
      const url = URL.createObjectURL(file);
      setPreview(url);
    } else {
      setPreview(storageUrl(existing ?? null));
    }
  };

  return (
    <div className={className}>
      {label && <div className="a-label">{label}</div>}
      <div className="flex flex-wrap items-start gap-4">
        {preview ? (
          <img src={preview} alt="" className="h-28 w-28 rounded-lg object-cover ring-1 ring-white/10" />
        ) : (
          <div className="grid h-28 w-28 place-items-center rounded-lg bg-white/[0.03] text-xs text-gray-500 ring-1 ring-white/10">
            no image
          </div>
        )}
        <div className="flex flex-col gap-2">
          <button type="button" className="a-btn-sec" onClick={() => inputRef.current?.click()}>
            Choose file
          </button>
          {(preview && preview !== storageUrl(existing ?? null)) && (
            <button
              type="button"
              className="a-btn-sec text-xs"
              onClick={() => {
                onChange(null);
                setPreview(storageUrl(existing ?? null));
                if (inputRef.current) inputRef.current.value = '';
              }}
            >
              Clear selection
            </button>
          )}
          <input ref={inputRef} type="file" accept={accept} className="hidden" onChange={handle} />
        </div>
      </div>
      {hint && !error && <p className="mt-1 text-xs text-gray-500">{hint}</p>}
      {error && <p className="a-error">{error}</p>}
    </div>
  );
}
