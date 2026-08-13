function escapeHtml(text) {
	const div = document.createElement("div");
	div.textContent = text;
	return div.innerHTML;
}

function debounce(func, delay) {
	let timer;
	return (...args) => {
		clearTimeout(timer);
		timer = setTimeout(() => func(...args), delay);
	};
}

export default function searchProducts() {
	const searchInput = document.getElementById("searchInput");
	const resultsContainer = document.getElementById("results-container");
	const searchForm = document.getElementById("searchForm");

	if (!searchInput || !resultsContainer || !searchForm) return;

	let currentController = null;

	const runSearch = debounce(async function () {
		const query = searchInput.value.trim();

		if (currentController) {
			currentController.abort();
		}

		if (query.length === 0) {
			resultsContainer.innerHTML = "";
			return;
		}

		currentController = new AbortController();

		try {
			const response = await fetch(
				`${BASE_URL}src/api/products/read_by_name.php?name=${encodeURIComponent(
					query,
				)}`,
				{ signal: currentController.signal },
			);
			const data = await response.json();

			resultsContainer.innerHTML = "";

			(data.data || []).forEach((product) => {
				const productItem = document.createElement("a");
				const safeName = escapeHtml(product.nombre);
				const safeDesc = escapeHtml(product.descripcion);
				productItem.href = `${BASE_URL}producto/${product.nombre
					.replaceAll(/\W+/gi, "-")
					.toLowerCase()}?id=${product.id}`;
				productItem.innerHTML = `
                    <div class="box-img">
                        <img src="${BASE_URL}${product.imagen.split(",")[0]
							.replace(/^\//, "")}" alt="${safeName}">
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
		} catch (error) {
			if (error.name !== "AbortError") {
				console.error("Error:", error);
			}
		}
	}, 300);

	searchInput.addEventListener("input", runSearch);

	searchForm.addEventListener("submit", function (e) {
		e.preventDefault();
		const query = searchInput.value.trim().replaceAll(/\W+/gi, "-").toLowerCase();
		if (query.length > 0) {
			window.location.href = `${BASE_URL}productos/${query}?name=${query}`;
		}
	});
}
