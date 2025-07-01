let db; // Variable para almacenar la base de datos

export function openDatabase() {
	return new Promise((resolve, reject) => {
		const dbName = "shoppingBagDB";
		const dbVersion = 1;
		const request = indexedDB.open(dbName, dbVersion);

		request.onupgradeneeded = function (event) {
			db = event.target.result;
			const objectStore = db.createObjectStore("bag", {
				keyPath: "id",
				autoIncrement: true,
			});
			objectStore.createIndex("name", "name", { unique: false });
			objectStore.createIndex("sku", "sku", { unique: false });
			objectStore.createIndex("price", "price", { unique: false });
			objectStore.createIndex("priceD", "priceD", { unique: false });
			objectStore.createIndex("quantity", "quantity", { unique: false });
			objectStore.createIndex("subtotal", "subtotal", { unique: false });
			objectStore.createIndex("attribute", "attribute", { unique: false });
			objectStore.createIndex("options", "options", { unique: false });
			objectStore.createIndex("total", "total", { unique: false });
			objectStore.createIndex("image", "image", { unique: false });
		};

		request.onsuccess = function (event) {
			db = event.target.result;
			resolve(db); // Resuelve la promesa cuando la base de datos está abierta
		};

		request.onerror = function (event) {
			reject("Error opening database: " + event.target.errorCode); // Rechaza la promesa si hay error
		};
	});
}

export function addItem(item) {
	return new Promise((resolve, reject) => {
		const transaction = db.transaction(["bag"], "readwrite");
		const objectStore = transaction.objectStore("bag");
		const request = objectStore.add(item);

		request.onsuccess = function (event) {
			console.log("Producto añadido al carrito:", item);
			displayCartItems(); // Actualizar el carrito después de añadir el producto
			resolve(); // Resuelve la promesa cuando el producto es añadido exitosamente
		};

		request.onerror = function (event) {
			console.error(
				"Error al añadir el producto al carrito:",
				event.target.errorCode
			);
			reject(event.target.errorCode); // Rechaza la promesa en caso de error
		};
	});
}

export function getItems(callback) {
	if (!db) {
		console.error("Database is not initialized yet.");
		return;
	}

	const transaction = db.transaction(["bag"], "readonly");
	const objectStore = transaction.objectStore("bag");
	const request = objectStore.getAll();

	request.onsuccess = function (event) {
		const items = event.target.result;

		// Calcula el total sumando los subtotales
		let total = items.reduce((sum, item) => sum + item.subtotal, 0);
		let discount = items.reduce(
			(sum, item) => sum + (item.price - item.priceD) * item.quantity,
			0
		);

		// Devuelve los productos y el total
		callback(items, total, discount);
	};

	request.onerror = function (event) {
		console.error("Error fetching items: " + event.target.errorCode);
	};
}

export function updateItem(id, updatedItem) {
	if (!db) {
		console.error("Database not initialized yet.");
		return;
	}

	const transaction = db.transaction(["bag"], "readwrite");
	const objectStore = transaction.objectStore("bag");
	const request = objectStore.put({ ...updatedItem, id });

	request.onsuccess = function (event) {
		console.log("Item updated in the bag");
		displayCartItems(); // Actualizar el carrito después de actualizar el producto
	};

	request.onerror = function (event) {
		console.error("Error updating item: " + event.target.errorCode);
	};
}

export function deleteItem(id) {
	if (!db) {
		console.error("Database not initialized yet.");
		return;
	}

	return new Promise((resolve, reject) => {
		const transaction = db.transaction(["bag"], "readwrite");
		const objectStore = transaction.objectStore("bag");
		const request = objectStore.delete(id);

		request.onsuccess = function (event) {
			console.log("Item deleted from the bag");

			// Después de eliminar el artículo, puedes recalcular el total si es necesario
			displayCartItems(); // Actualiza la vista del carrito
		};

		request.onerror = function (event) {
			console.error("Error deleting item: " + event.target.errorCode);
		};
	});
}

export function displayCartItems() {
	// Recuperar todos los productos y el total del carrito
	getItems(function (items, total, discount) {
		const cartContainer = document.querySelector(".cart-items"); // Contenedor donde se mostrarán los productos
		const totalElement = document.querySelector(".cart-total p#total"); // Elemento para mostrar el total
		const discountElement = document.querySelector(".cart-total p#discount"); // Elemento para mostrar el descuento

		// Limpiar el contenedor del carrito
		cartContainer.innerHTML = "";

		// Si no hay productos en el carrito, mostrar mensaje
		if (items.length === 0) {
			cartContainer.innerHTML = "<p>El carrito está vacío.</p>";
			totalElement.innerHTML = "Total: $0.00"; // Mostrar total 0 si no hay productos
			discountElement.innerHTML = "Descuento: $0.00"; // Mostrar total 0 si no hay productos

			getCount().then((count) => {
				const number = document.querySelectorAll(".counter-items");

				number.forEach((element) => {
					element.textContent = count;
				});
			});

			return;
		}

		getCount().then((count) => {
			const number = document.querySelectorAll(".counter-items");

			number.forEach((element) => {
				element.textContent = count;
			});
		});

		// Iterar sobre los productos y mostrarlos
		items.forEach((item) => {
			const productElement = document.createElement("div");
			productElement.classList.add("cart-item");

			productElement.innerHTML = `
                <div class="box-img">
                    <img src="${item.image}" alt="${item.name}">
                </div>
                <div class="item-details">
                    <p>${item.name}</p>
                    <div class="quantity" data-id="${item.id}">
                    	<button class="minus bag">-</button>
                    	<button class="plus bag">+</button>
                    	<input type="number" name="cantidad" value="${
												item.quantity
											}" min="1" inputmode="numeric">
                	</div>
                    <p>Precio: $${
											item.priceD > 0
												? item.price - (item.price - item.priceD)
												: item.price
										}</p>
                    <p>Subtotal: $${item.subtotal.toFixed(2)}</p>
                </div>
                <div class="cart-item-actions">
                    <button class="remove-item" data-id="${
											item.id
										}"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg></button>
                </div>
            `;

			cartContainer.appendChild(productElement);

			// Añadir evento para eliminar producto
			const removeButton = productElement.querySelector(".remove-item");
			removeButton.addEventListener("click", function () {
				deleteItem(item.id)
					.then(() => {
						displayCartItems(); // Actualizar carrito después de eliminar
					})
					.catch((error) => {
						console.error("Error al eliminar el producto del carrito:", error);
					});
			});
		});

		// Mostrar el total del carrito
		totalElement.innerHTML = `Total: $${total.toFixed(2)}`;
		discountElement.innerHTML = `Descuento: $${discount.toFixed(2)}`;
	});
}

export function getProductCartById(id) {
	return new Promise((resolve, reject) => {
		const transaction = db.transaction(["bag"], "readonly");
		const objectStore = transaction.objectStore("bag");
		const request = objectStore.get(Number(id));

		request.onsuccess = function (event) {
			const product = event.target.result;
			if (product) {
				resolve(product);
			} else {
				reject("Producto no encontrado en el carrito.");
			}
		};

		request.onerror = function () {
			reject("Error al recuperar el producto.");
		};
	});
}

export function getCount() {
	return new Promise((resolve, reject) => {
		const transaction = db.transaction(["bag"], "readonly");
		const objectStore = transaction.objectStore("bag");
		const request = objectStore.count();

		request.onsuccess = function (event) {
			const count = event.target.result;
			if (count) {
				resolve(count);
			} else {
				resolve(0);
			}
		};

		request.onerror = function () {
			reject("Error al recuperar el producto.");
		};
	});
}
