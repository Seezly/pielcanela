function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

export default function searchProducts() {
	const searchInput = document.getElementById("searchInput");
	const resultsContainer = document.getElementById("results-container");
	const searchForm = document.getElementById("searchForm");

	searchInput.addEventListener("input", function () {
		const query = searchInput.value;

                if (query.length > 0) {
                        fetch(`${BASE_URL}src/api/products/read_by_name.php?name=${query}`)
				.then((response) => response.json())
				.then((data) => {
					resultsContainer.innerHTML = "";
                                        data.data.forEach((product) => {
                                                const productItem = document.createElement("a");
                                                const safeName = escapeHtml(product.nombre);
                                                const safeDesc = escapeHtml(product.descripcion);
            productItem.href = `${BASE_URL}producto/${product.nombre
                    .replaceAll(" ", "-")
                    .toLowerCase()}?id=${product.id}`;
                                                productItem.innerHTML = `
                            <div class="box-img">
                                <img src="${product.imagen.split(",")[0]}" alt="${safeName}">
                            </div>
                            <div class="box-text">
                                <h3>${safeName}</h3>
                                <p class="product-description">${safeDesc}</p>
                                <p class="price">$${
                                                                               product.descuento > 0
                                                                               ? product.precioD
                                                                               : product.precio
																}</p>
                            </div>
                            `;
						resultsContainer.appendChild(productItem);
					});
				})
				.catch((error) => console.error("Error:", error));
		} else {
			resultsContainer.innerHTML = "";
		}
	});

	searchForm.addEventListener("submit", function (e) {
		e.preventDefault();
		const query = searchInput.value;
        if (query.length > 0) {
                window.location.href = `${BASE_URL}productos/${query}?name=${query}`;
        }
	});
}
