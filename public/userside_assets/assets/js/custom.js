const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', () => {
  if (window.scrollY > 100) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});

let currentStep = 1;
  function displayStep(stepNumber) {
    if (stepNumber >= 1 && stepNumber <= 3) {
      document.querySelector(".step-" + currentStep).style.display = "none";
      document.querySelector(".step-" + stepNumber).style.display = "block";
      currentStep = stepNumber;
      updateProgressBar();
    }
  }

  // Hide all steps except first
  document.querySelectorAll('#multi-step-form .step')
    .forEach((step, index) => {
      if (index !== 0) step.style.display = "none";
    });

  // Next Step
  document.querySelectorAll(".next-step").forEach(btn => {
    btn.addEventListener("click", () => {
      if (currentStep < 3) {
        const currentEl = document.querySelector(".step-" + currentStep);
        currentEl.classList.add("animate__animated", "animate__fadeOutLeft");

        currentStep++;

        setTimeout(() => {
          document.querySelectorAll(".step").forEach(s => {
            s.classList.remove("animate__animated", "animate__fadeOutLeft");
            s.style.display = "none";
          });

          const nextEl = document.querySelector(".step-" + currentStep);
          nextEl.style.display = "block";
          nextEl.classList.add("animate__animated", "animate__fadeInRight");

          updateProgressBar();
        }, 500);
      }
    });
  });

  // Previous Step
  document.querySelectorAll(".prev-step").forEach(btn => {
    btn.addEventListener("click", () => {
      if (currentStep > 1) {
        const currentEl = document.querySelector(".step-" + currentStep);
        currentEl.classList.add("animate__animated", "animate__fadeOutRight");

        currentStep--;

        setTimeout(() => {
          document.querySelectorAll(".step").forEach(s => {
            s.classList.remove("animate__animated", "animate__fadeOutRight");
            s.style.display = "none";
          });

          const prevEl = document.querySelector(".step-" + currentStep);
          prevEl.style.display = "block";
          prevEl.classList.add("animate__animated", "animate__fadeInLeft");

          updateProgressBar();
        }, 500);
      }
    });
  });

  // Progress Bar Update
  function updateProgressBar() {
    let progressPercentage = ((currentStep - 1) / 2) * 100;
    document.querySelectorAll(".progress-bar").forEach(bar => {
      bar.style.width = progressPercentage + "%";
    });
  }




const guestSelect = document.getElementById("filterGuests");
const timeSelect  = document.getElementById("filterTime");
const dateSelect  = document.getElementById("filterDate");
const cards       = document.querySelectorAll("#cardRow .card-block");

function filterCards() {
  const g = guestSelect.value;
  const t = timeSelect.value;
  const d = dateSelect.value;

  // If any filter is empty -> alert & stop
  if (!g || !t || !d) {
    alert("⚠️ Please select Guests, Time and Date!");
    return;
  }

  cards.forEach(card => {
    const match = 
      card.dataset.guests === g &&
      card.dataset.time   === t &&
      card.dataset.date   === d;

    card.style.display = match ? "block" : "none";
  });
}

// Trigger filter whenever any select changes
[guestSelect, timeSelect, dateSelect].forEach(sel => 
  sel.addEventListener("change", filterCards)
);



let split, animation;

function setup() {
  if(split) split.revert();
  if(animation) animation.revert();

  // .text class ko split karega
  split = new SplitText(".video-caption .text", { type: "chars" });

  // Characters per staggered animation
  animation = gsap.from(split.chars, {
    duration: 1,
    opacity: 0,
    y: 50,
    stagger: 0.05,
    ease: "power3.out"
  });
}

setup();