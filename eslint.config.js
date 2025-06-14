import config from '@battis/eslint-config';
import globals from 'globals';

export default {
  ...config,
  languageOptions: {
    globals: {
      ...globals.browser,
      bootstrap: 'readonly'
    }
  }
};
