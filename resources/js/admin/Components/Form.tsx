import React, { TextareaHTMLAttributes, InputHTMLAttributes, SelectHTMLAttributes } from 'react';

interface FieldShellProps {
  label?: string;
  hint?: string;
  error?: string;
  required?: boolean;
  className?: string;
  children: React.ReactNode;
}

export function Field({ label, hint, error, required, className, children }: FieldShellProps) {
  return (
    <div className={className}>
      {label && (
        <label className="a-label">
          {label}
          {required && <span className="ml-1 text-red-400">*</span>}
        </label>
      )}
      {children}
      {hint && !error && <p className="mt-1 text-xs text-gray-500">{hint}</p>}
      {error && <p className="a-error">{error}</p>}
    </div>
  );
}

type TextInputProps = InputHTMLAttributes<HTMLInputElement> & {
  label?: string;
  error?: string;
  hint?: string;
};

export function TextInput({ label, error, hint, className, required, ...props }: TextInputProps) {
  return (
    <Field label={label} error={error} hint={hint} required={required} className={className}>
      <input {...props} className="a-input" />
    </Field>
  );
}

type TextareaProps = TextareaHTMLAttributes<HTMLTextAreaElement> & {
  label?: string;
  error?: string;
  hint?: string;
};

export function Textarea({ label, error, hint, className, required, ...props }: TextareaProps) {
  return (
    <Field label={label} error={error} hint={hint} required={required} className={className}>
      <textarea {...props} className="a-input min-h-[100px]" />
    </Field>
  );
}

type SelectProps = SelectHTMLAttributes<HTMLSelectElement> & {
  label?: string;
  error?: string;
  hint?: string;
  options: { value: string | number; label: string }[];
  placeholder?: string;
};

export function Select({ label, error, hint, className, required, options, placeholder, ...props }: SelectProps) {
  return (
    <Field label={label} error={error} hint={hint} required={required} className={className}>
      <select {...props} className="a-input">
        {placeholder && <option value="">{placeholder}</option>}
        {options.map((o) => (
          <option key={o.value} value={o.value}>
            {o.label}
          </option>
        ))}
      </select>
    </Field>
  );
}

interface CheckboxProps {
  label: string;
  checked: boolean;
  onChange: (v: boolean) => void;
  hint?: string;
  className?: string;
}

export function Checkbox({ label, checked, onChange, hint, className }: CheckboxProps) {
  return (
    <label className={'flex cursor-pointer items-start gap-3 ' + (className ?? '')}>
      <input
        type="checkbox"
        checked={checked}
        onChange={(e) => onChange(e.target.checked)}
        className="mt-0.5 h-4 w-4 rounded border-white/20 bg-white/[0.03] accent-[color:var(--admin-primary)]"
      />
      <span>
        <span className="text-sm text-gray-200">{label}</span>
        {hint && <span className="block text-xs text-gray-500">{hint}</span>}
      </span>
    </label>
  );
}
