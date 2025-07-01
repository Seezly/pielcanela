export default function searchProducts() {
	const searchInput = document.getElementById("searchInput");
	const resultsContainer = document.getElementById("results-container");
	const searchForm = document.getElementById("searchForm");

	searchInput.addEventListener("input", function () {
		const query = searchInput.value;

		if (query.length > 0) {
			fetch(`/src/api/products/read_by_name.php?name=${query}`)
				.then((response) => response.json())
				.then((data) => {
					resultsContainer.innerHTML = "";
					data.data.forEach((product) => {
						const productItem = document.createElement("a");
						productItem.href = `/producto/${product.nombre
							.replaceAll(" ", "-")
							.toLowerCase()}?id=${product.id}`;
						productItem.innerHTML = `
                            <div class="box-img">
                                <img src="${
																	product.imagen.split(",")[0]
																}" alt="${product.nombre}">
                            </div>
                            <div class="box-text">
                                <h3>${product.nombre}</h3>
                                <p class="product-description">${
																	product.descripcion
																}</p>
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
			window.location.href = `/productos/${query}?name=${query}`;
		}
	});
}
