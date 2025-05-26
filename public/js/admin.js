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
  const form = document.getElementById("formadmin");
  const resultBox = document.getElementById("admin-result");

  form?.addEventListener("submit", async (e) => {
    e.preventDefault();
    resultBox.textContent = "";

    const formData = new FormData(form);

    const fields = [
      ['pro_user', 'promote_user'],
      ['dem_user', 'demote_user'],
      ['del_user', 'delete_user'],
      ['del_cat', 'delete_category'],
      ['del_serv', 'delete_service'],
      ['del_review', 'delete_review'],
      ['del_mes', 'delete_message'],
      ['del_ord', 'delete_order'],
      ['del_cus_ord', 'delete_custom_order'],
      ['add_cat', 'add_category']
    ];

    let action = null;
    let payloadKey = null;
    let payloadValue = null;

    for (const [field, act] of fields) {
      const value = formData.get(field);
      if (value) {
        action = act;
        payloadKey = field.includes('add_cat') ? 'name' : 'id';
        payloadValue = value;
        break;
      }
    }

    if (!action) {
      resultBox.textContent = "Please fill in at least one field if you wish to make changes";
      resultBox.className = "error";
      return;
    }

    form.reset();

    const sendData = new FormData();
    sendData.append('action', action);
    sendData.append(payloadKey, payloadValue);
    sendData.append('csrf', formData.get('csrf'));

    const res = await fetch('/action_dispatcher.php?action=admin', {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      },
      body: sendData
    });

    try {
      const data = await res.json();
      resultBox.innerHTML = escapeAndFormat(data.message);
      resultBox.className = data.status.toLowerCase();
    } catch (err) {
      resultBox.textContent = "Something went wrong.";
      resultBox.className = "error";
    }
  });
});
