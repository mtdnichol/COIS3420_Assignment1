// Dynamic Modal system
// Binds every button with a data-open-modal property to open the modal with that ID on click
window.addEventListener('DOMContentLoaded', () => {
    let elements = document.querySelectorAll("*");
    for (let element of elements) {
        let id = element.getAttribute("data-open-modal");
        if(id == null) continue;
        let modal = document.querySelector("#" + id);
        element.addEventListener("click", (event) => {
                modal.style.display = "block";
        });

        // Handle close button within modal
        document.querySelector("#" + id + " .close-btn").addEventListener("click", (event) => {
           modal.style.display = "none";
        });
    }

    // Also if click outside of modal, exit modal
    window.onclick = function(e){
        if(e.target.classList.contains("modal")){
            e.target.style.display = "none"
        }
    }
});