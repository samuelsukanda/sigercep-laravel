// Toggle Approval Note
$(document).ready(function () {
    function toggleApprovalNote() {
        let val = $("#approval_status").val();
        let container = $("#approval_note_container");
        let noteField = $("#approval_note");

        if (val === "Rejected" || val === "Need Clarification") {
            container.removeClass("hidden");
            noteField.prop("required", true);
        } else {
            container.addClass("hidden");
            noteField.prop("required", false);
            noteField.val("");
        }
    }

    $("#approval_status").on("change", function () {
        toggleApprovalNote();
    });

    toggleApprovalNote();
});
