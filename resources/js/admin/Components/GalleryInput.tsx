import React, { useRef, useState } from 'react';
import { storageUrl } from '../lib/utils';

interface Props {
  label?: string;
  existing: string[]; // existing storage paths
  removedExisting: string[]; // paths flagged for deletion
  onRemovedChange: (next: string[]) => void;
  newFiles: File[];
  onFilesChange: (files: File[]) => void;
  accept?: string;
  hint?: string;
  error?: string;
  className?: string;
}

/**
 * Multi-image gallery editor:
 *   - Lists existing images with a remove (X) toggle (stored in `removedExisting`).
 *   - Lets users append new files (stored in `newFiles`).
 */
export default function GalleryInput({
  label,
  existing,
  removedExisting,
  onRemovedChange,
  newFiles,
  onFilesChange,
  accept = 'image/*',
  hint,
  error,
  className,
}: Props) {
  const inputRef = useRef<HTMLInputElement>(null);
  const [previews] = useState(() => new Map<File, string>());

  const previewFor = (f: File) => {
    if (!previews.has(f)) previews.set(f, URL.createObjectURL(f));
    return previews.get(f)!;
  };

  const onAdd = (e: React.ChangeEvent<HTMLInputElement>) => {
    const files = Array.from(e.target.files ?? []);
    if (!files.length) return;
    onFilesChange([...newFiles, ...files]);
    if (inputRef.current) inputRef.current.value = '';
  };

  const toggleRemove = (path: string) => {
    if (removedExisting.includes(path)) {
      onRemovedChange(removedExisting.filter((p) => p !== path));
    } else {
      onRemovedChange([...removedExisting, path]);
    }
  };

  const removeNew = (i: number) => onFilesChange(newFiles.filter((_, idx) => idx !== i));

  return (
    <div className={className}>
      {label && <div className="a-label">{label}</div>}
      <div className="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
        {existing.map((path) => {
          const removed = removedExisting.includes(path);
          return (
            <div key={path} className="relative">
              <img
                src={storageUrl(path)!}
                alt=""
                className={
                  'h-28 w-full rounded-lg object-cover ring-1 ring-white/10 transition ' +
                  (removed ? 'opacity-30 grayscale' : '')
                }
              />
              <button
                type="button"
                onClick={() => toggleRemove(path)}
                title={removed ? 'Undo remove' : 'Remove'}
                className="absolute right-1 top-1 rounded-full bg-black/70 px-2 py-0.5 text-xs text-white hover:bg-red-600"
              >
                {removed ? '↺' : '×'}
              </button>
            </div>
          );
        })}
        {newFiles.map((f, i) => (
          <div key={i} className="relative">
            <img src={previewFor(f)} alt="" className="h-28 w-full rounded-lg object-cover ring-1 ring-emerald-500/40" />
            <span className="absolute left-1 top-1 rounded bg-emerald-500/80 px-1.5 py-0.5 text-[10px] font-medium text-white">
              NEW
            </span>
            <button
              type="button"
              onClick={() => removeNew(i)}
              className="absolute right-1 top-1 rounded-full bg-black/70 px-2 py-0.5 text-xs text-white hover:bg-red-600"
            >
              ×
            </button>
          </div>
        ))}
        <button
          type="button"
          onClick={() => inputRef.current?.click()}
          className="grid h-28 w-full place-items-center rounded-lg border border-dashed border-white/15 bg-white/[0.02] text-sm text-gray-400 hover:bg-white/[0.05]"
        >
          + Add images
        </button>
      </div>
      <input ref={inputRef} type="file" accept={accept} multiple className="hidden" onChange={onAdd} />
      {hint && !error && <p className="mt-1 text-xs text-gray-500">{hint}</p>}
      {error && <p className="a-error">{error}</p>}
    </div>
  );
}
