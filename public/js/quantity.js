import { updateItem, getProductCartById } from "./bag.js";

export function addQuantity(container, productId) {
	const quantityInput = container.querySelector("input[name='cantidad']");

	let quantity = parseFloat(quantityInput.value);
	quantity++;
	quantityInput.value = quantity;

	getProductCartById(productId)
		.then((existingProduct) => {
			// Si el producto ya está en el carrito, aumentar la cantidad
			existingProduct.quantity = quantity;
			existingProduct.subtotal =
				existingProduct.quantity *
				(existingProduct.price -
					(existingProduct.price - existingProduct.priceD));
			updateItem(existingProduct.id, existingProduct);
		})
		.catch(() => console.error("Error al actualizar cantidad:", error));
}

export function deleteQuantity(container, productId) {
	const quantityInput = container.querySelector("input[name='cantidad']");

	let quantity = parseFloat(quantityInput.value);
	if (quantity > 1) {
		quantity--;
		quantityInput.value = quantity;

		getProductCartById(productId)
			.then((existingProduct) => {
				// Si el producto ya está en el carrito, aumentar la cantidad
				existingProduct.quantity = quantity;
				existingProduct.subtotal =
					existingProduct.quantity *
					(existingProduct.price -
						(existingProduct.price - existingProduct.priceD));
				updateItem(existingProduct.id, existingProduct);
			})
			.catch(() => console.error("Error al actualizar cantidad:", error));
	}
}
