// Inertia React entry for the FraxionFX admin panel.
import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import AdminLayout from './Layouts/AdminLayout';

createInertiaApp({
  title: (title) => (title ? `${title} · Admin` : 'FraxionFX Admin'),
  resolve: async (name) => {
    const pages = import.meta.glob('./Pages/**/*.tsx');
    const importer = pages[`./Pages/${name}.tsx`];
    if (!importer) throw new Error(`Page not found: ./Pages/${name}.tsx`);
    const page: any = await importer();
    const component = page.default;
    // Apply the AdminLayout to every page unless the page opts out.
    component.layout ??= (pageNode: React.ReactNode) => <AdminLayout>{pageNode}</AdminLayout>;
    return component;
  },
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />);
  },
  progress: {
    color: 'var(--admin-primary)',
    showSpinner: false,
  },
});
