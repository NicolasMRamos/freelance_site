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
    const form = document.getElementById("formserv");
    const resultBox = document.getElementById("service-result");
  
    form?.addEventListener("submit", async (e) => {
      e.preventDefault();
      resultBox.textContent = "";
  
      const formData = new FormData(form);
      const res = await fetch('/action_dispatcher.php?action=create_service', {
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
          setTimeout(() => {
            window.location.href = data.redirect;
          }, 500);
        }
      } catch (err) {
        resultBox.textContent = "Something went wrong.";
        resultBox.className = "error";
      }
    });
  });
  