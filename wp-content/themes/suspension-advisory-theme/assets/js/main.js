function filter() {

    let dropdown = document.querySelectorAll(".dropdown");

    for(let i = 0; dropdown.length > i; i++) {

        let dropdownSeleted = dropdown[i];

        const select = dropdownSeleted.querySelector(".select");
        const caret = dropdownSeleted.querySelector(".caret");
        const menu = dropdownSeleted.querySelector(".dropdown-list");
        const options = dropdownSeleted.querySelectorAll(".dropdown-list li");
        const selected = dropdownSeleted.querySelector(".selected");

       

            select.addEventListener("click", () => {
                select.classList.toggle("select-clicked");
                caret.classList.toggle("caret-rotate");
                menu.classList.toggle("menu-open")

              
            })

         if(selected) {
            options.forEach(option => {
                option.addEventListener("click", () => {
                    selected.innerText = option.innerText;
                    select.classList.remove("select-clicked");
                    caret.classList.remove("caret-rotate");
                    menu.classList.remove("menu-open");
                    options.forEach(option => {
                        option.classList.remove("active")
                    })
                    option.classList.add("active")
                })
            })

        }

    }
 
} 
filter();