$(document).ready(function() {
        // Handle DIT button click
        $("#DITButton").click(function() {
			// Show DIT checklist and hide others
			$("#DITChecklist").show();
			$("#DIETChecklist, #DCEEChecklist, #DCEAChecklist, #DAFEChecklist").hide();
		});
		$("#DIETButton").click(function() {
			// Show DIT checklist and hide others
			$("#DIETChecklist").show();
			$("#DITChecklist, #DCEEChecklist, #DCEAChecklist, #DAFEChecklist").hide();
		});
		$("#DCEEButton").click(function() {
			// Show DIT checklist and hide others
			$("#DCEEChecklist").show();
			$("#DIETChecklist, #DITChecklist, #DCEAChecklist, #DAFEChecklist").hide();
		});
		$("#DCEAButton").click(function() {
			// Show DIT checklist and hide others
			$("#DCEAChecklist").show();
			$("#DIETChecklist, #DCEEChecklist, #DITChecklist, #DAFEChecklist").hide();
		});
		$("#DAFEButton").click(function() {
			// Show DIT checklist and hide others
			$("#DAFEChecklist").show();
			$("#DIETChecklist, #DCEEChecklist, #DCEAChecklist, #DITChecklist").hide();
		});
		$("#selectAllDIT").click(function() {
			var isChecked = $(this).prop("checked");

			$(".dit-checkbox").prop("checked", isChecked);
		});
		$("#selectAllDIET").click(function() {
			var isChecked = $(this).prop("checked");

			$(".diet-checkbox").prop("checked", isChecked);
		});
		$("#selectAllDCEE").click(function() {
			var isChecked = $(this).prop("checked");

			$(".dcee-checkbox").prop("checked", isChecked);
		});
		$("#selectAllDCEA").click(function() {
			var isChecked = $(this).prop("checked");

			$(".dcea-checkbox").prop("checked", isChecked);
		});
		$("#selectAllDAFE").click(function() {
			var isChecked = $(this).prop("checked");

			$(".dafe-checkbox").prop("checked", isChecked);
		});
    });