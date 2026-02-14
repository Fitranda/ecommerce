import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

export default createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'vibrant',
        themes: {
            vibrant: {
                dark: false,
                colors: {
                    background: '#F5F3FF',
                    surface: '#FFFFFF',
                    'surface-variant': '#F8FAFC',
                    primary: '#7C3AED',
                    'primary-darken-1': '#6D28D9',
                    secondary: '#F59E0B',
                    'secondary-darken-1': '#D97706',
                    tertiary: '#06B6D4',
                    accent: '#EC4899',
                    error: '#EF4444',
                    info: '#3B82F6',
                    success: '#10B981',
                    warning: '#F97316',
                    'on-background': '#1E1B4B',
                    'on-surface': '#1E1B4B',
                    'on-primary': '#FFFFFF',
                    'on-secondary': '#FFFFFF',
                },
            },
        },
    },
    defaults: {
        VBtn: {
            rounded: 'lg',
            fontWeight: 600,
        },
        VCard: {
            rounded: 'xl',
            elevation: 0,
        },
        VTextField: {
            variant: 'outlined',
            density: 'comfortable',
            rounded: 'lg',
            color: 'primary',
        },
        VTextarea: {
            variant: 'outlined',
            density: 'comfortable',
            rounded: 'lg',
            color: 'primary',
        },
        VSelect: {
            variant: 'outlined',
            density: 'comfortable',
            rounded: 'lg',
            color: 'primary',
        },
        VCheckbox: {
            color: 'primary',
        },
        VChip: {
            rounded: 'lg',
        },
        VDataTable: {
            hover: true,
        },
    },
});
