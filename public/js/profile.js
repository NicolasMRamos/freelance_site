document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formedit");
    const resultBox = document.getElementById("edit-result");
  
    form?.addEventListener("submit", async (e) => {
      e.preventDefault();
      resultBox.textContent = "";
  
      const formData = new FormData(form);
      const res = await fetch('/action_dispatcher.php?action=edit_profile', {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
      });
  
      try {
        const data = await res.json();
        resultBox.textContent = data.message;
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
  