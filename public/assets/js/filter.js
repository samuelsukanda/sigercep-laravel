// FIlter
document.addEventListener("DOMContentLoaded", function() {
            var dari = flatpickr("input[name='periode_dari']", {
                dateFormat: "d-m-Y",
                allowInput: false,
                onChange: function(selectedDates, dateStr, instance) {
                    sampai.set("minDate", dateStr);
                },
            });

            var sampai = flatpickr("input[name='periode_sampai']", {
                dateFormat: "d-m-Y",
                allowInput: false,
                onChange: function(selectedDates, dateStr, instance) {
                    dari.set("maxDate", dateStr);
                },
            });
            const dariValue =
                "{{ request('periode_dari', now()->startOfMonth()->format('d-m-Y')) }}";
            const sampaiValue =
                "{{ request('periode_sampai', now()->format('d-m-Y')) }}";

            dari.setDate(dariValue);
            sampai.setDate(sampaiValue);
            sampai.set("minDate", dariValue);
            dari.set("maxDate", sampaiValue);
        });