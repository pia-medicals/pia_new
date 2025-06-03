$(function () {
  $("#customerLoginForm").validate({
    rules: {
      username: {
        required: true,
        email: true,
      },
      password: {
        required: true,
      },
    },
    messages: {
      username: {
        required: "Please enter your email-id",
        email: "Please enter a valid email-id",
      },
      password: {
        required: "Please enter your password",
      }
    },

    errorPlacement: function (error, element) {
      error.addClass("text-danger d-block small mt-1"); // Make error message small, red, and block-level
      error.insertAfter(element.closest(".input-group")); // Place error after input-group
    },
  });
});
