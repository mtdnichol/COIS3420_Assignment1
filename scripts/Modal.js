window.addEventListener('DOMContentLoaded', () => {
    let elements = document.querySelectorAll("*");
    for (let element of elements) {
        let id = element.getAttribute("data-open-modal");
        if(id == null) continue;
        let modal = document.querySelector("#" + id);
        element.addEventListener("click", (event) => {
                modal.style.display = "block";
        });
        document.querySelector("#" + id + " .close-btn").addEventListener("click", (event) => {
           modal.style.display = "none";
        });
    }

    window.onclick = function(e){
        if(e.target.classList.contains("modal")){
            e.target.style.display = "none"
        }
    }
});