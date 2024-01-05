document.querySelector("form").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const action = this.getAttribute("action");
    const options = {
        method: "POST",
        body: formData,
    };

    fetch(action, options).then((response) => {
        if (response.ok) {
            console.log("Success:", response);
        } else {
            console.log("Error:", response);
        }
    });
});

$(document).ready(function () {
    var lastStatus = "{{ auth()->user()->last_status }}";

    if (lastStatus === "OFF") {
        $("#end, #break_start, #break_end")
            .prop("disabled", true)
            .css("color", "lightgrey");
        $("#start").prop("disabled", false).css("color", "black"); // ここを追加
    } else if (lastStatus === "working") {
        $("#start, #break_end")
            .prop("disabled", true)
            .css("color", "lightgrey");
        $("#end, #break_start").prop("disabled", false).css("color", "black");
    } else if (lastStatus === "breaking") {
        $("#break_start, #start, #end")
            .prop("disabled", true)
            .css("color", "lightgrey");
        $("#break_end").prop("disabled", false).css("color", "black");
    }

    $("#start").on("click", function () {
        $("#start, #break_end")
            .prop("disabled", true)
            .css("color", "lightgrey");
        $("#end, #break_start").prop("disabled", false).css("color", "black");
    });

    $("#end").on("click", function () {
        $("#start, #end, #break_start, #break_end")
            .prop("disabled", true)
            .css("color", "lightgrey");
        $("#start").prop("disabled", false).css("color", "black");
    });

    $("#break_start").on("click", function () {
        $("#break_start, #start, #end")
            .prop("disabled", true)
            .css("color", "lightgrey");
        $("#break_end").prop("disabled", false).css("color", "black");
    });

    $("#break_end").on("click", function () {
        $("#break_end, #start")
            .prop("disabled", true)
            .css("color", "lightgrey");
        $("#break_start, #end").prop("disabled", false).css("color", "black");
    });
});
