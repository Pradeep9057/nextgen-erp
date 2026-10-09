import React from 'react';
import { theme } from '../styles/tokens';

export const DynamicForm = ({ fields, values, onChange, onSubmit }) => {
  return (
    <form onSubmit={onSubmit} className="space-y-4">
      {fields.map((field) => (
        <div key={field.id} className="flex flex-col gap-1">
          <label className="text-sm font-medium text-slate-700">
            {field.label} {field.is_required && <span className="text-red-500">*</span>}
          </label>

          {field.type === 'text' && (
            <input
              type="text"
              className="p-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-navy-500 outline-none"
              value={values[field.id] || ''}
              onChange={(e) => onChange(field.id, e.target.value)}
            />
          )}

          {field.type === 'number' && (
            <input
              type="number"
              className="p-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-navy-500 outline-none"
              value={values[field.id] || ''}
              onChange={(e) => onChange(field.id, e.target.value)}
            />
          )}

          {field.type === 'select' && (
            <select
              className="p-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-navy-500 outline-none"
              value={values[field.id] || ''}
              onChange={(e) => onChange(field.id, e.target.value)}
            >
              <option value="">Select option...</option>
              {field.options?.map(opt => (
                <option key={opt.value} value={opt.value}>{opt.label}</option>
              ))}
            </select>
          )}
        </div>
      ))}
      <div className="flex justify-end gap-2">
        <Button variant="secondary">Cancel</Button>
        <Button type="submit">Save Changes</Button>
      </div>
    </form>
  );
};
