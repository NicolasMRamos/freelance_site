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
  const form = document.getElementById("formlog");
  const resultBox = document.getElementById("login-result");

  form?.addEventListener("submit", async (e) => {
    e.preventDefault();
    resultBox.textContent = "";

    const formData = new FormData(form);
    const res = await fetch('/action_dispatcher.php?action=login', {
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
  

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formreg");
  const resultBox = document.getElementById("register-result");

  form?.addEventListener("submit", async (e) => {
    e.preventDefault();
    resultBox.textContent = "";

    const formData = new FormData(form);
    const res = await fetch('/action_dispatcher.php?action=register', {
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
        }, 1000);
      }
    } catch (err) {
      resultBox.textContent = "Something went wrong.";
      resultBox.className = "error";
    }
  });
});
  