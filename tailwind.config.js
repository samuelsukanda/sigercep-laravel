export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    corePlugins: {
        preflight: false,
    },
    theme: {
        extend: {},
    },
    plugins: [],

    safelist: [
    'bg-blue-500',
    'bg-green-500',
    'bg-gray-400',
    'text-white',
],
};
