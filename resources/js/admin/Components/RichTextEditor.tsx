import { useEffect, useId, useRef } from 'react';
import { useRoute } from '../lib/utils';

declare global {
  interface Window {
    tinymce?: any;
    __tinymceLoading?: Promise<any>;
  }
}

const TINYMCE_CDN =
  'https://cdn.tiny.cloud/1/2ybotr2gj2jba7rs525xlvymht3kg2qv4833vglziifs7kj8/tinymce/6/tinymce.min.js';

function loadTinyMCE(): Promise<any> {
  if (window.tinymce) return Promise.resolve(window.tinymce);
  if (window.__tinymceLoading) return window.__tinymceLoading;
  window.__tinymceLoading = new Promise((resolve, reject) => {
    const s = document.createElement('script');
    s.src = TINYMCE_CDN;
    s.referrerPolicy = 'origin';
    s.onload = () => resolve(window.tinymce);
    s.onerror = (e) => reject(e);
    document.head.appendChild(s);
  });
  return window.__tinymceLoading;
}

interface Props {
  value: string;
  onChange: (v: string) => void;
  height?: number;
  uploadRouteName?: string;
  className?: string;
}

export default function RichTextEditor({ value, onChange, height = 420, uploadRouteName, className }: Props) {
  const id = useId().replace(/:/g, '');
  const editorRef = useRef<any>(null);
  const route = useRoute();

  useEffect(() => {
    let disposed = false;

    loadTinyMCE().then((tinymce) => {
      if (disposed) return;
      tinymce.init({
        selector: `#tmce-${id}`,
        height,
        menubar: false,
        skin: 'oxide-dark',
        content_css: 'dark',
        plugins: 'advlist autolink lists link image media table code wordcount preview',
        toolbar:
          'undo redo | blocks | bold italic underline | bullist numlist | link image media | alignleft aligncenter alignright | code preview',
        branding: false,
        promotion: false,
        images_upload_url: uploadRouteName ? route(uploadRouteName) : undefined,
        images_upload_credentials: true,
        automatic_uploads: !!uploadRouteName,
        setup: (ed: any) => {
          editorRef.current = ed;
          ed.on('init', () => ed.setContent(value || ''));
          ed.on('change keyup undo redo input', () => onChange(ed.getContent()));
        },
      });
    });

    return () => {
      disposed = true;
      try {
        editorRef.current?.remove?.();
      } catch {
        /* ignore */
      }
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    const ed = editorRef.current;
    if (ed && ed.initialized && ed.getContent() !== value) {
      ed.setContent(value || '');
    }
  }, [value]);

  return (
    <div className={className}>
      <textarea id={`tmce-${id}`} defaultValue={value} />
    </div>
  );
}
