document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".tab-link.rd");
  const contents = document.querySelectorAll(".tab-content.rd");

  // Initially show the active tab content
  contents.forEach(content => {
    if (content.classList.contains("active")) {
      content.style.display = "block";
    } else {
      content.style.display = "none";
    }
  });

  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      // Remove active from all buttons and hide all contents
      buttons.forEach(b => b.classList.remove("active"));
      contents.forEach(c => c.style.display = "none");

      // Activate clicked tab and show its content
      btn.classList.add("active");
      const targetContent = document.getElementById(btn.dataset.target);
      targetContent.style.display = "block";
    });
  });

  // Function to handle login form submission and redirection
  window.handleLoginSubmit = function(event) {
    event.preventDefault();
    const userType = document.getElementById("user-type").value;
    let redirectUrl = "";
    if (userType === "Learner") {
      redirectUrl = "Learner.html";
    } else if (userType === "Educator") {
      redirectUrl = "Educator.html";
    }
    if (redirectUrl) {
      // Here you can add validation or form data processing if needed
      window.location.href = redirectUrl;
    }
  };

  // Function to handle signup form submission and redirection
  window.handleSubmit = function(event, redirectUrl) {
    event.preventDefault();
    // Here you can add validation or form data processing if needed
    window.location.href = redirectUrl;
  };
});