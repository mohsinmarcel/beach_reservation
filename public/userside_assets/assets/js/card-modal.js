let currentStep = 1;
  function displayStep(stepNumber) {
    if (stepNumber >= 1 && stepNumber <= 4) {
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
      if (currentStep < 4) {
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
    let progressPercentage = ((currentStep - 1) / 3) * 100;
    document.querySelectorAll(".progress-bar").forEach(bar => {
      bar.style.width = progressPercentage + "%";
    });
  }
