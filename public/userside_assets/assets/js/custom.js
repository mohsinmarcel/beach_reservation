const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', () => {
  if (window.scrollY > 100) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});



document.addEventListener("DOMContentLoaded", function () {

  const filterGuests = document.getElementById("filterGuests");
  const filterTime   = document.getElementById("filterTime");
  const filterDate   = document.getElementById("filterDate");
  const cards        = document.querySelectorAll(".card-block");

  function applyFilters() {
    const gVal = filterGuests.value;
    const tVal = filterTime.value;
    const dVal = filterDate.value;

    cards.forEach(card => {
      const matchGuests = (gVal === "Guests" || card.dataset.guests === gVal);
      const matchTime   = (tVal === "Time"   || card.dataset.time   === tVal);
      const matchDate   = (dVal === "Date"   || card.dataset.date   === dVal);

      if (matchGuests && matchTime && matchDate) {
        card.style.display = "block";  // show
      } else {
        card.style.display = "none";   // hide
      }
    });
  }

  // Trigger filtering on every change
  [filterGuests, filterTime, filterDate].forEach(el => {
    el.addEventListener("change", applyFilters);
  });

});

const checkbox = document.getElementById('more_addons');
const addonsContainer = document.querySelector('.addons-container');

  checkbox.addEventListener('change', () => {
    if (checkbox.checked) {
      addonsContainer.style.display = 'block';
    } else {
      addonsContainer.style.display = 'none';
    }
  });
