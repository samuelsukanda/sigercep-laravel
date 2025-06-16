<span class="
    px-2 py-1 rounded-full
    {{ $status === 'Done'
        ? 'bg-gradient-to-tl from-emerald-500 to-teal-400 px-3.6 text-xs rounded-1.8 py-2.2 inline-block whitespace-nowrap text-center align-baseline font-bold leading-none'
        : ($status === 'On Progress'
            ? 'bg-gradient-to-tl from-orange-500 to-yellow-500 px-3.6 text-xs rounded-1.8 py-2.2 inline-block whitespace-nowrap text-center align-baseline font-bold leading-none'
            : ($status === 'Pending'
                ? 'bg-gradient-to-tl from-red-600 to-orange-600 px-3.6 text-xs rounded-1.8 py-2.2 inline-block whitespace-nowrap text-center align-baseline font-bold leading-none'
                : 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-100 px-3.6 text-xs rounded-1.8 py-2.2 inline-block whitespace-nowrap text-center align-baseline font-bold leading-none'))
    }}
">
    {{ $status ?? '-' }}
</span>
