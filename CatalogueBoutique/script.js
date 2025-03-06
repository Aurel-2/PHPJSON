let catalog = []

async function init() {
    try {
        // Charger le fichier JSON
        const response = await fetch('data/catalog.json');
        if (!response.ok) {
            throw new Error('Erreur lors du chargement du fichier JSON');
        }
        catalog = await response.json();

        let form = document.querySelector('form');
        let errorDiv = document.createElement('div');
        errorDiv.style.color = 'red';
        form.appendChild(errorDiv);

        let imgInput = document.getElementById("image");
        let imgElement = document.getElementById("img-src");

        imgInput.addEventListener('change', (event) => {
            let file = event.target.files[0];
            if (file) {
                imgElement.src = URL.createObjectURL(file);
            }
        });

        form.addEventListener('submit', async (event) => {
            const response = await fetch('data/catalog.json');
            if (!response.ok) {
                throw new Error('Erreur lors du chargement du fichier JSON');
            }
            catalog = await response.json();
        });
        displayItem(catalog);
        updateFilter();
        totalPrice();
    } catch (error) {
        console.error('Erreur lors de l\'initialisation :', error);
    }
}


function updateFilter() {
    let filter = document.getElementById("cat-filter");
    filter.innerHTML = '<option value="all">Toutes les catégories</option>';
    let list = new Set(catalog.map(produit => produit.Category));
    list.forEach((cat) => {
        let option = document.createElement("option");
        option.value = cat;
        option.textContent = cat;
        filter.appendChild(option);
    });
}


function filterProducts() {
    let s_category = document.getElementById("cat-filter").value;
    let f_products = s_category == "all" ? catalog : catalog.filter((item) => item.Category == s_category);
    displayItem(f_products);
}

function searchProducts() {
    let s_category = document.getElementById("cat-filter").value;
    let f_products = s_category === "all" ? catalog : catalog.filter((item) => item.Category == s_category);
    let entry = document.getElementById("search-products").value.toLowerCase();
    let ff_products = f_products.filter(p => p.Name.toLowerCase().includes(entry));
    displayItem(ff_products);
}

function totalPrice() {
    let total = catalog.reduce((accumulator, currentValue) => accumulator + currentValue.Price, 0);
    let totalHTML = document.createElement("h3");
    totalHTML.innerHTML = "Prix total du catalogue : " + total.toFixed(1) + " €";
    let container = document.getElementById("total");
    let oldTotal = container.querySelector("h3");
    if (oldTotal) {
        container.removeChild(oldTotal);
    }
    container.appendChild(totalHTML);
}

function applyDiscount() {
    let s_category = document.getElementById("cat-filter").value;
    let f_products = s_category === "all" ? catalog : catalog.filter((item) => item.Category == s_category);
    let n_products = f_products.map(p => ({...p, Price: p.Price - (p.Price * 0.1)}));
    displayItem(n_products);

}

function resetDisplay() {
    let s_category = document.getElementById("cat-filter").value;
    let f_products = s_category === "all" ? catalog : catalog.filter((item) => item.Category == s_category);
    displayItem(f_products)
}

function displayItem(iterable) {
    let productGrid = document.getElementById('product-grid');
    productGrid.innerHTML = '';
    iterable.forEach((product) => {
        let productCard = document.createElement('div');
        productCard.classList.add('product-card');

        let productImage = document.createElement('img');
        productImage.src = product.Image;
        productImage.alt = product.Name;

        let productName = document.createElement('h3');
        productName.textContent = product.Name;

        let productPrice = document.createElement('p');
        productPrice.classList.add('price');
        productPrice.textContent = `Prix : ${product.Price.toFixed(1)} €`;

        let productStock = document.createElement('p');
        productStock.classList.add('stock');
        productStock.textContent = product.Stock ? 'En stock' : 'Rupture de stock';

        productCard.appendChild(productImage);
        productCard.appendChild(productName);
        productCard.appendChild(productPrice);
        productCard.appendChild(productStock);
        productGrid.appendChild(productCard);
    });
}
