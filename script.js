document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".tab-link.rd");
  const contents = document.querySelectorAll(".tab-content.rd");

  if (buttons.length > 0 && contents.length > 0) {
    contents.forEach(content => content.style.display = "none");
    buttons.forEach(btn => btn.classList.remove("active"));

    buttons.forEach(btn => {
      btn.addEventListener("click", () => {
        buttons.forEach(b => b.classList.remove("active"));
        contents.forEach(c => c.style.display = "none");

        btn.classList.add("active");
        const targetContent = document.getElementById(btn.dataset.target);
        if (targetContent) targetContent.style.display = "block";
      });
    });
  }

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
      window.location.href = redirectUrl;
    }
  };

  window.handleSubmit = function(event, redirectUrl) {
    event.preventDefault();
    window.location.href = redirectUrl;
  };
});
