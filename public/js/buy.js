// Función para obtener los productos del carrito desde indexedDB
export function getCartItems() {
	return new Promise((resolve, reject) => {
		const dbRequest = indexedDB.open("shoppingBagDB", 1);

		dbRequest.onsuccess = function (event) {
			const db = event.target.result;
			const transaction = db.transaction(["bag"], "readonly");
			const objectStore = transaction.objectStore("bag");
			const request = objectStore.getAll();

			request.onsuccess = function () {
				resolve(request.result);
			};

			request.onerror = function () {
				reject("Error al obtener los productos del carrito.");
			};
		};

		dbRequest.onerror = function () {
			reject("Error al acceder a la base de datos.");
		};
	});
}

export function clearCart() {
	return new Promise((resolve, reject) => {
		const dbRequest = indexedDB.open("shoppingBagDB", 1);

		dbRequest.onsuccess = function (event) {
			const db = event.target.result;
			const transaction = db.transaction(["bag"], "readwrite");
			const objectStore = transaction.objectStore("bag");
			const request = objectStore.clear(); // Borra todos los productos

			request.onsuccess = function () {
				console.log("Carrito eliminado correctamente.");
				resolve();
			};

			request.onerror = function () {
				console.error("Error al eliminar el carrito.");
				reject("Error al eliminar el carrito.");
			};
		};

		dbRequest.onerror = function () {
			reject("Error al acceder a la base de datos.");
		};
	});
}

// Función para generar el mensaje de WhatsApp con los productos
export function generateWhatsAppMessage(cartItems, name, address, parcel) {
	let mensaje = `Hola, mi nombre es ${name} 👋, me gustaría comprar los siguientes productos:\n\n`;
	let total = 0;

	cartItems.forEach((item) => {
		const precioFinal = item.priceD > 0 ? item.priceD : item.price;
		mensaje += `🗝 *Código*: ${item.sku}\n💎 *Producto*: ${item.name}\n🔰 *${item.attribute}*: ${item.option}\n❓ *Cantidad*: ${item.quantity}\n💲 *Precio*: $${precioFinal}\n💰 *Subtotal*: $${item.subtotal}\n\n`;
		total += item.subtotal;
	});

	mensaje += `💳 *Total*: $${total}\n\n${
		parcel != 0
			? `Estoy ubicado/a en: ${address} y me gustaría recibir el producto con: ${
					parcel == 1 ? "delivery a domicilio" : "envío nacional"
			  }.`
			: "Puedo retirar el producto en tienda."
	}\n\n¡Gracias ✨!`;

	return mensaje;
}

// Función para redirigir a WhatsApp con el mensaje generado
export function redirectToWhatsApp(message) {
	const numeroTelefono = "+584145522227"; // Reemplazar por el número de WhatsApp
	const url = `https://api.whatsapp.com/send/?phone=${numeroTelefono}&text=${encodeURIComponent(
		message
	)}`;
	window.open(url, "_blank");
}

export async function addOrder(cartItems) {
	let productsId = cartItems.map((el) => el.id);

	let formData = new FormData();

	productsId.forEach((item, index) => {
		formData.append(`id[]`, item);
	});

	formData.append(
		"csrf_token",
		typeof CSRF_TOKEN !== "undefined" ? CSRF_TOKEN : "",
	);

	let response = await fetch(`${BASE_URL}src/scripts/add_order.php`, {
		method: "POST",
		body: formData,
	});

	let result = await response.json();
}