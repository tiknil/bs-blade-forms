module.exports = {
  extends: [
    'plugin:@typescript-eslint/eslint-recommended',
    'plugin:@typescript-eslint/recommended',
    'plugin:vue/strongly-recommended',
    '@vue/typescript/recommended',
    'prettier',
  ],
  parserOptions: {
    parser: '@typescript-eslint/parser',
    sourceType: 'module',
    ecmaVersion: 2015,
  },
  plugins: ['prettier', '@typescript-eslint'],
  rules: {
    'prettier/prettier': [
      'warn',
      {
        singleQuote: true,
        trailingComma: 'all',
        semi: false,
        maxWidth: 120,
      },
    ],

    // Generics
    semi: ['warn', 'never'],
    'max-len': ['warn', { code: 120 }],
    'no-undef': ['off'],

    // Typescript
    '@typescript-eslint/no-non-null-assertion': ['off'],
    '@typescript-eslint/no-inferrable-types': ['off'],

    // Vue
    'vue/multi-word-component-names': 'off',
  },
  ignorePatterns: ['public/**/*'],
}
