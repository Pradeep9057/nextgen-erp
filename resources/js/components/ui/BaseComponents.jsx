import React from 'react';
import { theme } from '../styles/tokens';

export const Button = ({ children, variant = 'primary', className = '', ...props }) => {
  const variants = {
    primary: `bg-[${theme.colors.primary}] text-white hover:opacity-90`,
    secondary: `bg-white text-slate-800 border border-slate-300 hover:bg-slate-50`,
    success: `bg-[${theme.colors.status.success}] text-white hover:opacity-90`,
    danger: `bg-[${theme.colors.status.error}] text-white hover:opacity-90`,
  };

  return (
    <button
      className={`px-4 py-2 rounded-md transition-all font-medium ${variants[variant]} ${className}`}
      {...props}
    >
      {children}
    </button>
  );
};

export const Card = ({ children, className = '', title }) => (
  <div className={`bg-white rounded-lg shadow-sm border border-slate-200 ${className}`}>
    {title && (
      <div className="px-6 py-4 border-b border-slate-100">
        <h3 className="text-lg font-semibold text-slate-800">{title}</h3>
      </div>
    )}
    <div className="p-6">{children}</div>
  </div>
);
