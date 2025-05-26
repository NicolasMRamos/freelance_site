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

    const form      = document.getElementById("formReview");
    const resultBox = document.getElementById("review-result");
    if (form && resultBox) {
        form.addEventListener("submit", async e => {
        e.preventDefault();
        resultBox.textContent = "";
        resultBox.className   = "";

        const data = await fetch('/action_dispatcher.php?action=create_review', {
            method: "POST",
            headers: {
            "X-Requested-With": "XMLHttpRequest"
            },
            body: new FormData(form)
        }).then(r => r.json())
        .catch(() => ({ status: "Error", message: "Something went wrong." }));

        resultBox.innerHTML   = escapeAndFormat(data.message);
        resultBox.className   = data.status.toLowerCase();
        if (data.status === "Success" && data.redirect) {
            localStorage.setItem("scrollToReviews", "true");
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 500);
        }
        });
    } else {
        console.warn("review.js: formReview or review-result not found");
    }

    const stars       = document.querySelectorAll("#star-container .star");
    const ratingInput = document.getElementById("rating-input");
    if (!stars.length || !ratingInput) {
        console.warn("review.js: couldn't find stars or rating-input");
        return;
    }

    let currentRating = 0;
    const fillStars = rating => {
        stars.forEach(star => {
        const val    = Number(star.dataset.value);
        const filled = val <= rating;
        star.textContent = filled ? "★" : "☆";
        star.classList.toggle("filled", filled);
        });
    };

    stars.forEach(star => {
        const val = Number(star.dataset.value);
        star.addEventListener("mouseover", () => fillStars(val));
        star.addEventListener("mouseout",  () => fillStars(currentRating));
        star.addEventListener("click",     () => {
        currentRating      = val;
        ratingInput.value  = String(val);
        fillStars(val);
        });
    });

    fillStars(currentRating);

    const scrollFlag = localStorage.getItem("scrollToReviews");
    if (scrollFlag === "true") {
        localStorage.removeItem("scrollToReviews");
        const reviewSection = document.getElementById("reviews");
        if (reviewSection) {
            reviewSection.scrollIntoView({ behavior: "smooth" });
        }
    }
});
