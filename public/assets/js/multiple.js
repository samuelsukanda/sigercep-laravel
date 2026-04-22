$(document).ready(function () {
    $(".select2-multiple").each(function () {
        let $select = $(this);

        $select.select2({
            width: "100%",
            closeOnSelect: false,
            placeholder: "Pilih Unit",
            allowClear: true,
            dropdownCssClass: "select2-checkbox-dropdown",
        });

        $select.on("select2:open", function () {
            setTimeout(() => {
                if ($(".select-all-container").length === 0) {
                    $(".select2-dropdown").prepend(`
                        <div class="select-all-container px-3 py-2 border-b cursor-pointer text-sm font-semibold text-blue-600">
                            ☑️ Pilih Semua
                        </div>
                    `);

                    $(".select-all-container").on("click", function () {
                        let allValues = [];

                        $select.find("option").each(function () {
                            allValues.push($(this).val());
                        });

                        let selected = $select.val() || [];

                        if (selected.length === allValues.length) {
                            $select.val(null).trigger("change");
                        } else {
                            $select.val(allValues).trigger("change");
                        }
                    });
                }
            }, 10);
        });

        $select.on("change", function () {
            let selected = $(this).val() || [];
            let total = $(this).find("option").length;

            let text = selected.length + " dipilih";
            $(this)
                .closest(".select-multiple-wrapper")
                .find(".count-selected")
                .text(text);

            let label =
                selected.length === total
                    ? "☑️ Batalkan Semua"
                    : "☑️ Pilih Semua";
            $(".select-all-container").text(label);
        });

        $select.trigger("change");
    });
});
