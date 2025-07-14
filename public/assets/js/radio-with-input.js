document.addEventListener('DOMContentLoaded', function () {
    const allRadioGroups = document.querySelectorAll('[data-radio-with-input]');

    allRadioGroups.forEach(group => {
        const radios = group.querySelectorAll('input[type="radio"]');
        const input = group.querySelector('[data-input-field]');

        const toggleInput = () => {
            let show = false;
            radios.forEach(radio => {
                if (radio.checked && radio.value.toLowerCase() === 'other') {
                    show = true;
                }
            });

            if (input) {
                input.style.display = show ? 'inline-block' : 'none';
                input.required = show;
            }
        };

        toggleInput();

        radios.forEach(radio => {
            radio.addEventListener('change', toggleInput);
        });
    });
});
