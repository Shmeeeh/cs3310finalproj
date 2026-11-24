const book_data = document.getElementById("books");
const book_array = JSON.parse(book_data.getAttribute("data-id"));
const cart_list = document.getElementById("cart-list");
const remove_all = document.getElementById("reset");
const submit = document.getElementById("submit");
const form = document.getElementById("form");
let cart = [];

function create_cart() {
    cart_list.innerHTML= "";
    for (let ii = 0; ii < cart.length; ii++) {
        const title = cart[ii]["Name"];
        const author = cart[ii]["Author"];
        const id = cart[ii]["BookID"];
        cart_list.insertAdjacentHTML("beforeend", `<div class="cart-item">
                    <li>
                        <p>${title} by ${author}</p>
                    </li>
                    <button id="remove-${ii}" name="remove" value="${id}" formnovalidate>X</button> 
                </div>`);

        const button = document.getElementById("remove-" + ii);
        button.addEventListener("click", () => {
            cart.splice(ii, 1);
            if (cart.length === 4) {
                let cart_ids = [];
                for (let jj = 0; jj < cart.length; jj++) {
                    cart_ids.push(cart[jj]["id"]);
                }

                for (let jj = 0; jj < book_array.length; jj++) {
                    const button = document.getElementById("add-" + book_array[jj]["BookID"]);
                    if (!cart_ids.includes(jj)) {
                        button.disabled = false;
                    }
                    
                }  
            }
            create_cart();
            const add_button = document.getElementById("add-" + button.value);
            add_button.disabled = false;
            if (cart.length === 0) {
                submit.disabled = true;
            }
        });
    }
}

function add_to_cart(book) {
    cart.push(book);
    create_cart()
    if (cart.length >= 5) {
        for (let ii = 0; ii < book_array.length; ii++) {
            const button = document.getElementById("add-" + book_array[ii]["BookID"]);
            button.disabled = true;
        }  
    }
}

for (let ii = 0; ii < book_array.length; ii++) {
    const button = document.getElementById("add-" + book_array[ii]["BookID"]);
    button.addEventListener("click", () => {
        if (cart.length < 5) {
            button.disabled = true;
            add_to_cart(book_array[ii]);
        }
        submit.disabled = false;
    })
}

reset.addEventListener("click", () => {
    cart = [];
    cart_list.innerHTML = "";
    submit.disabled = true;
    for (let ii = 0; ii < book_array.length; ii++) {
        const button = document.getElementById("add-" + book_array[ii]["BookID"]);
        if (!(book_array[ii]["Restricted"] === "1" || parseInt(book_array[ii]["Available"]) < 1)) {
            button.disabled = false;
        }
    }  
});

form.addEventListener("submit", () => {
    const jsonCart = JSON.stringify(cart);
    const cartInput = document.getElementById("cart-data");
    cartInput.value = jsonCart;
});