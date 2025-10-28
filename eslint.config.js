import config from '@battis/eslint-config';
import { defineConfig } from 'eslint/config';
import globals from 'globals';

export default defineConfig(...config, {
  languageOptions: {
    globals: {
      ...globals.browser,
      bootstrap: 'readonly'
    }
}})

