function toggleSection(id) {
    const section = document.getElementById(id);
    section.style.display = section.style.display === 'none' ? 'block' : 'none';
}

document.addEventListener("DOMContentLoaded", () => {

  const filterForm = document.querySelector("form[action='/index.php']");
  if (filterForm) {
    filterForm.addEventListener("submit", () => {
      localStorage.setItem("scrollToServices", "true");
    });
  }

  if (localStorage.getItem("scrollToServices") === "true") {
    localStorage.removeItem("scrollToServices");

    setTimeout(() => {
      const section = document.getElementById("service_filter");
      if (section) {
        section.scrollIntoView({ behavior: "smooth" });
      }
    }, 100);
  }
});

