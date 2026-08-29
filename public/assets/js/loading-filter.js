// Loading Filter JavaScript
$(document).ready(function () {
    function showLoading() {
        $("#loadingOverlay").css("display", "flex").hide().fadeIn(150);
    }

    window.showFilterLoading = showLoading;
    window.hideFilterLoading = function () {
        $("#loadingOverlay").stop(true, true).fadeOut(200);
    };

    // Submit filter
    $("form").on("submit", function () {
        showLoading();
    });

    $("[data-filter-submit]").on("click", function () {
        showLoading();
    });

    // Reset
    $(".btn-reset").on("click", function () {
        showLoading();
    });

    // Export
    $("a[title='Export ke Excel']").on("click", function () {
        if (!$(this).attr("onclick")) {
            showLoading();

            setTimeout(function () {
                $("#loadingOverlay").fadeOut(200);
            }, 1000);
        }
    });
});
