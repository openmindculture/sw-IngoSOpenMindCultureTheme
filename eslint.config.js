export default [
    {
        languageOptions: {
            ecmaVersion: 2022,
            sourceType: "module",
            globals: {
                // Explicitly whitelists native browser targets so they don't throw errors
                window: "readonly",
                document: "readonly",
                console: "readonly"
            }
        },
        rules: {
            // Re-enables the primary tracking features manually
            "no-unused-vars": "warn",
            "no-undef": "error"
        }
    }
];