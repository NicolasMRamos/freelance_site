function escapeAndFormat(text) {
  return text
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;")
    .replace(/\n/g, "<br>");
}

document.addEventListener("DOMContentLoaded", () => {
  const form      = document.getElementById("formMessage");
  const resultBox = document.getElementById("message-result");

  form?.addEventListener("submit", async (e) => {
    e.preventDefault();
    resultBox.textContent = "";
    resultBox.className   = "";

    const formData = new FormData(form);
    const res = await fetch('/action_dispatcher.php?action=create_message', {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      },
      body: formData
    });

    try {
      const data = await res.json();
      resultBox.innerHTML = escapeAndFormat(data.message);
      resultBox.className = data.status.toLowerCase();

      if (data.status === "Success" && data.redirect) {
        localStorage.setItem("scrollToMessages", "true");
        setTimeout(() => {
          window.location.href = data.redirect;
        }, 500);
      }
    } catch (err) {
      resultBox.textContent = "Something went wrong.";
      resultBox.className = "error";
    }
  });
    const scrollFlag = localStorage.getItem("scrollToMessages");
    if (scrollFlag === "true") {
        localStorage.removeItem("scrollToMessages");
        const messageSection = document.getElementById("messages");
        if (messageSection) {
            messageSection.scrollIntoView({ behavior: "smooth" });
        }
    }
});
