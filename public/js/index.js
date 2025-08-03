import searchProducts from "./search.js";
import {
	openDatabase,
	addItem,
	getItems,
	updateItem,
	deleteItem,
	displayCartItems,
	getProductCartById,
	getCount,
} from "./bag.js";
import {
	getCartItems,
	clearCart,
	generateWhatsAppMessage,
	redirectToWhatsApp,
	addOrder,
} from "./buy.js";
import { addQuantity, deleteQuantity } from "./quantity.js";

const readCategories = async () => {
	try {
		const response = await fetch(
			`${BASE_URL}src/api/categories/read_all_categories.php`
		);
		const result = await response.json();

		if (result.status === "success") {
			const body = document.querySelector(
				".menu-categories .categories-list ul"
			);
			body.innerHTML = ""; // Limpiar contenido previo

			result.data.forEach((category) => {
				// Crear contenedor de categoría
				const categoryContainer = document.createElement("li");
				categoryContainer.classList.add("category-item");

				// Contenedor del título + botón expandir
				const titleContainer = document.createElement("div");
				titleContainer.classList.add("category-title");

				// Link de la categoría (click navega)
				const categoryLink = document.createElement("a");
				categoryLink.href = `/categoria/${category.nombre
					.replaceAll(" ", "-")
					.toLowerCase()}?id=${category.id}`;
				categoryLink.textContent = category.nombre;
				categoryLink.classList.add("category-link");

				// Botón de expandir/cerrar
				const toggleButton = document.createElement("button");
				toggleButton.classList.add("toggle-subcategories");
				toggleButton.innerHTML = "+"; // Inicialmente cerrado

				toggleButton.addEventListener("click", (e) => {
					e.stopPropagation(); // Prevenir que haga click en el link
					e.preventDefault(); // No recargar ni navegar

					// Cerrar todas las categorías abiertas (acordeón)
					document.querySelectorAll(".category-item.open").forEach((item) => {
						if (item !== categoryContainer) {
							item.classList.remove("open");
							const btn = item.querySelector(".toggle-subcategories");
							if (btn) btn.innerHTML = "+";
						}
					});

					// Alternar esta categoría
					const isOpen = categoryContainer.classList.toggle("open");
					toggleButton.innerHTML = isOpen ? "−" : "+";
				});

				// Añadir link y botón
				titleContainer.appendChild(categoryLink);
				titleContainer.appendChild(toggleButton);
				categoryContainer.appendChild(titleContainer);

				// Subcategorías
				if (category.subcategorias && category.subcategorias.length > 0) {
					const subList = document.createElement("ul");
					subList.classList.add("subcategory-list");

					category.subcategorias.forEach((subcat) => {
						const subItem = document.createElement("li");
						const subLink = document.createElement("a");
						subLink.href = `/categoria/${category.nombre
							.replaceAll(" ", "-")
							.toLowerCase()}/${subcat.nombre
							.replaceAll(" ", "-")
							.toLowerCase()}?id=${category.id}&id_s=${subcat.id}`;
						subLink.textContent = subcat.nombre;

						subItem.appendChild(subLink);
						subList.appendChild(subItem);
					});

					categoryContainer.appendChild(subList);
				}

				body.appendChild(categoryContainer);
			});
		} else {
			console.error("Error:", result.message);
		}
	} catch (error) {
		console.error("Error en la solicitud:", error);
	}
};

const getProductById = async (id, opcion, name, address, parcel) => {
	try {
		const response = await fetch(
			`${BASE_URL}src/api/products/id_product.php?id=${id}`
		);
		const result = await response.json();

		if (result.status === "success") {
			let product = result.data;

			const message = `Hola, mi nombre es ${name} 👋, me gustaría comprar los siguientes productos:\n\n🗝 *Código*: ${
				product.sku
			}\n💎 *Producto*: ${product.nombre}\n🔰 *${
				product.atributo
			}*: ${opcion}\n❓ *Cantidad*: 1\n💲 *Precio*: $${
				product.precio - (product.precio - product.precioD)
			}\n💰 *Subtotal*: $${
				product.precio - (product.precio - product.precioD)
			}\n\n💳 *Total*: $${
				product.precio - (product.precio - product.precioD)
			}\n\n${
				parcel != 0
					? `Estoy ubicado/a en: ${address} y me gustaría recibir el producto con: ${
							parcel == 1 ? "delivery a domicilio" : "envío nacional"
					  }.`
					: "Puedo retirar el producto en tienda."
			}\n\n¡Gracias ✨!`;

			redirectToWhatsApp(message);
		} else {
			console.error("Error:", result.message);
		}
	} catch (error) {
		console.error("Error en la solicitud:", error);
	}
};

document.addEventListener("DOMContentLoaded", async function () {
	const overlay = document.querySelector(".overlay");
	const sliderElement = document.querySelector(".keen-slider");
	const categoriesSlider = document.querySelector(".categorias-slider");
	const loader = document.getElementById("loader");

	if (document.querySelector("input[type=radio")) {
		document.querySelector("input[type=radio").checked = true;
	}

	if (sliderElement) {
	        new KeenSlider(
	                sliderElement,
	                {
	                        loop: true,
	                        created: function (instance) {
	                                // Crear flechas
	                                const arrowLeft = document.createElement("div");
	                                const arrowRight = document.createElement("div");

	                                arrowLeft.classList.add("arrow", "arrow-left");
	                                arrowRight.classList.add("arrow", "arrow-right");

	                                arrowLeft.innerHTML = "❮";
	                                arrowRight.innerHTML = "❯";

	                                sliderElement.appendChild(arrowLeft);
	                                sliderElement.appendChild(arrowRight);

	                                arrowLeft.addEventListener("click", () => instance.prev());
	                                arrowRight.addEventListener("click", () => instance.next());
	                        },
	                },
	                [
	                        (slider) => {
	                                let timeout;
	                                let mouseOver = false;

	                                function clearNextTimeout() {
	                                        clearTimeout(timeout);
	                                }

	                                function nextTimeout() {
	                                        clearTimeout(timeout);
	                                        if (mouseOver) return;
	                                        timeout = setTimeout(() => {
	                                                slider.next();
	                                        }, 3000);
	                                }

	                                slider.on("created", () => {
	                                        slider.container.addEventListener("mouseover", () => {
	                                                mouseOver = true;
	                                                clearNextTimeout();
	                                        });
	                                        slider.container.addEventListener("mouseout", () => {
	                                                mouseOver = false;
	                                                nextTimeout();
	                                        });
	                                        nextTimeout();
	                                });

	                                slider.on("dragStarted", clearNextTimeout);
	                                slider.on("animationEnded", nextTimeout);
	                                slider.on("updated", nextTimeout);
	                        },
	                ]
	        );
	}

	if (categoriesSlider) {
	        new KeenSlider(categoriesSlider, {
	                loop: true,
	                slides: {
	                        perView: 2,
	                        spacing: 10,
	                },
	                breakpoints: {
	                        "(min-width: 768px)": {
	                                slides: { perView: 4, spacing: 15 },
	                        },
	                        "(min-width: 1024px)": {
	                                slides: { perView: 6, spacing: 20 },
	                        },
	                },
	        });
	}

	try {
	        await readCategories();
	        await searchProducts();
	        await openDatabase()
			.then(() => {
				getItems((items, total) => {
					displayCartItems(items, total);
				});
			})
			.catch((error) =>
				console.error("No se pudo abrir la base de datos:", error)
			);
		loader.style.display = "none";
	} catch (error) {
		console.error("Error durante la carga inicial:", error);
		loader.style.display = "none";
	}

	function updateUserActivity() {
		fetch(`${BASE_URL}src/scripts/onUser.php`)
			.then((response) => {
				if (!response.ok) {
					throw new Error("Error al actualizar la actividad del usuario.");
				}
			})
			.catch((error) => console.error("Error:", error));
	}

	// Llamar la función cada 30 segundos
	setInterval(updateUserActivity, 30000);

	let page = 1;
	let isLoading = false; // Flag para evitar múltiples llamadas

	window.addEventListener(
		"scroll",
		debounce(() => {
			if (
				(!isLoading &&
					window.innerHeight + window.scrollY >=
						document.body.offsetHeight - 800 &&
					window.location.pathname.includes("/categoria/")) ||
				(!isLoading &&
					window.innerHeight + window.scrollY >=
						document.body.offsetHeight - 800 &&
					window.location.pathname.includes("/productos/"))
			) {
				loadMoreProducts();
			}
		}, 50)
	); // Espera 200ms antes de ejecutar nuevamente

	async function loadMoreProducts() {
		isLoading = true; // Evita llamadas múltiples mientras se carga
		page++;

		if (window.location.search.includes("id_s")) {
			try {
				const response = await fetch(
					`${BASE_URL}src/scripts/load_more_products_specific.php?id=${document
						.getElementById("productos")
						.getAttribute("data-subcategory")}&page=${page}`
				);

				if (response.ok) {
					const newProductsHTML = await response.text();
					document.getElementById("productos").innerHTML += newProductsHTML;
				}
			} catch (error) {
				console.error("Error loading more products:", error);
			}
			isLoading = false; // Permite nuevas llamadas después de la carga
		} else {
			try {
				const response = await fetch(
					`${BASE_URL}src/scripts/load_more_products.php?id=${document
						.getElementById("productos")
						.getAttribute("data-category")}&page=${page}`
				);

				if (response.ok) {
					const newProductsHTML = await response.text();
					document.getElementById("productos").innerHTML += newProductsHTML;
				}
			} catch (error) {
				console.error("Error loading more products:", error);
			}

			isLoading = false; // Permite nuevas llamadas después de la carga
		}
	}

	// Debounce para limitar la frecuencia de ejecución del evento scroll
	function debounce(func, delay) {
		let timer;
		return (...args) => {
			clearTimeout(timer);
			timer = setTimeout(() => func(...args), delay);
		};
	}

	// Evento para los filtros
	const filterIcon = document.querySelector(".icon-filter");
	if (filterIcon) {
		filterIcon.addEventListener("click", (e) => {
			const filter = document.querySelector(".filters");
			if (filter) {
				filter.classList.toggle("active");
			} else {
				console.warn("No se encontró el contenedor de filtros.");
			}
		});
	}

	// Manejo de acordeones
	document.querySelectorAll(".accordion-header").forEach((button) => {
		button.addEventListener("click", () => {
			button.classList.toggle("active");
		});
	});

	// Manejo de iconos de modal
	document.querySelectorAll(".modal-icon").forEach((icon) => {
		icon.addEventListener("click", (e) => {
			e.stopPropagation();
			const modalId = icon.getAttribute("data-modal");
			const modalToggle = document.querySelector(`.${modalId}`);
			const activeModal = document.querySelectorAll(".modal.active");

			if (activeModal.length > 1) {
				activeModal.forEach((modal) => {
					if (modal !== modalToggle) {
						modal.classList.remove("active");
					}
				});
			}

			if (modalToggle) {
				overlay.classList.add("active");
				modalToggle.classList.add("active");
			} else {
				console.warn(`No se encontró el modal con ID: ${modalId}`);
			}
		});
	});

	// Cerrar modales
	document.querySelectorAll(".toggle").forEach((close) => {
		close.addEventListener("click", () => {
			const modalId = close.getAttribute("data-modal");
			const modalToggle = document.querySelector(`.${modalId}`);

			if (modalToggle) {
				overlay.classList.remove("active");
				modalToggle.classList.remove("active");
			}
		});
	});

	document.addEventListener("click", function (e) {
		const activeModal = document.querySelector(".modal.active");

		if (
			activeModal &&
			!activeModal.contains(e.target) &&
			!e.target.classList.contains("modal-icon") &&
			!e.target.closest(".size-options") // Agregar esta línea
		) {
			overlay.classList.remove("active");
			activeModal.classList.remove("active");
		}
	});

	// Manejo de productos
	document.querySelectorAll(".producto").forEach((producto) => {
		producto.addEventListener("click", (e) => {
			if (e.target.closest(".icon")) {
				e.preventDefault();
				e.stopPropagation();
			}
		});
	});

	// Manejo de añadir producto al carrito y ver detalles
	document.querySelectorAll(".icon").forEach((icon) => {
		icon.addEventListener("click", (e) => {
			e.preventDefault();
			e.stopPropagation();

			const productId = icon.getAttribute("data-product");
			const productIdCart = icon.getAttribute("data-id");
			if (!productId) return;

			if (productId === "cart") {
				getProductCartById(productIdCart)
					.then((existingProduct) => {
						// Si el producto ya está en el carrito, aumentar la cantidad
						existingProduct.quantity += 1;
						existingProduct.subtotal =
							existingProduct.quantity * existingProduct.price;
						updateItem(existingProduct.id, existingProduct);
					})
					.catch(() => {
						// Si el producto no está en el carrito, obtener sus datos desde el HTML
						const product = {
							id: parseInt(icon.getAttribute("data-id")),
							name: icon.getAttribute("data-name"),
							sku: icon.getAttribute("data-sku"),
							price: parseFloat(icon.getAttribute("data-price")),
							priceD: parseFloat(icon.getAttribute("data-priceD")),
							image: icon.getAttribute("data-image"),
							quantity: 1,
							attribute: icon.getAttribute("data-attribute"),
							option: icon.getAttribute("data-option"),
							subtotal:
								parseFloat(icon.getAttribute("data-priceD")) ||
								parseFloat(icon.getAttribute("data-price")),
						};

						if (isNaN(product.price) || !product.name || !product.image) {
							console.error("Datos inválidos para el producto:", product);
							return;
						}

						addItem(product)
							.then(() => console.log("Producto añadido al carrito:", product))
							.catch((error) =>
								console.error("Error al añadir el producto:", error)
							);
					});
			} else if (productId === "see") {
				fetch(
					`${BASE_URL}src/api/products/id_product.php?id=${icon.getAttribute(
						"data-id"
					)}`
				)
					.then((response) => response.json())
					.then((data) => {
						if (!data.data) {
							console.error("No se encontró información del producto.");
							return;
						}

						const modal = document.querySelector(".product-modal");
						if (modal) {
							modal.querySelector("h2").textContent = data.data.nombre;
							if (data.data.descuento > 0) {
								const descuento = document.createElement("p");
								descuento.classList.add("discount");
								modal
									.querySelector(".producto-precio p")
									.classList.add("midline");
								modal.querySelector(
									".producto-precio p"
								).textContent = `$${data.data.precio}`;
								descuento.textContent = `$${data.data.precioD}`;
								modal.querySelector(".producto-precio").append(descuento);
							}
							modal.querySelector(
								".producto-precio p"
							).textContent = `$${data.data.precio}`;
							modal.querySelector(".product-cta + p").textContent =
								data.data.descripcion;
							modal.querySelector("img").src = data.data.imagen.split(",")[0];
							modal
								.querySelector(".quantity")
								.setAttribute("data-id", data.data.id);
							modal
								.querySelector(".size-options")
								.setAttribute("data-attribute", data.data.atributo);
							modal.querySelector(".size-options").innerHTML = "";
							data.data.opciones.split(",").forEach((element, index) => {
								const option = document.createElement("input");
								option.type = "radio";
								option.id = `size-${element}-modal`;
								option.name = "size";
								option.value = element;
								option.setAttribute(
									"data-image",
									data.data.imagen.split(",")[index]
								);
								option.setAttribute("data-location", "modal");
								const label = document.createElement("label");
								label.htmlFor = `size-${element}-modal`;
								label.textContent = element;
								modal.querySelector(".size-options").append(option, label);
							});
							modal
								.querySelector(".btn[data-action=cart]")
								.setAttribute("data-id", data.data.id);
							modal
								.querySelector(".btn[data-action=cart]")
								.setAttribute("data-sku", data.data.sku);
							modal
								.querySelector(".comprarProducto")
								.setAttribute("data-id", data.data.id);
							overlay.classList.add("active");
							modal.classList.add("active");
						} else {
							console.warn("No se encontró el modal del producto.");
						}

						document
							.querySelectorAll(".product-modal input[type=radio]")
							.forEach((input) => {
								input.addEventListener("click", (e) => {
									const image = input.getAttribute("data-image");
									const productImage =
										document.querySelector(".product-modal img");

									console.log(input);

									if (!image) {
										console.error(
											"No se encontró la imagen del producto para comprar.",
											image
										);
										return;
									}

									productImage.src = image;
								});
							});
					})
					.catch((error) =>
						console.error("Error al obtener el producto:", error)
					);
			}
		});
	});

	document.querySelectorAll(".btn[data-action=cart]").forEach((button) => {
		button.addEventListener("click", (e) => {
			e.preventDefault();
			const productId = button.getAttribute("data-id");
			const productQuantity = parseFloat(
				document.querySelector(".product input[name='cantidad']").value
			);
			const productOption = parseFloat(
				document.querySelector(".size-options input[type=radio]:checked").value
			);

			if (!productId) {
				console.error("No se encontró el ID del producto.");
				return;
			}

			getProductCartById(productId)
				.then((existingProduct) => {
					// Si el producto ya está en el carrito, aumentar la cantidad
					existingProduct.quantity += productQuantity;
					existingProduct.option += productOption;
					existingProduct.subtotal =
						existingProduct.priceD > 0
							? existingProduct.quantity *
							  (existingProduct.price -
									(existingProduct.price - existingProduct.priceD))
							: existingProduct.quantity * existingProduct.price;
					updateItem(existingProduct.id, existingProduct);
				})
				.catch(() => {
					// Si el producto no está en el carrito, obtener sus datos desde el HTML
					const productElement = button.closest(".product");
					const name = productElement.querySelector("h2").textContent;
					const price = parseFloat(
						productElement
							.querySelector(".producto-precio p")
							.textContent.replace("$", "")
							.trim()
					);
					const image = productElement.querySelector("img").src;
					const quantity = parseFloat(
						productElement.querySelector("input[name='cantidad']").value
					);
					const sku = e.target.getAttribute("data-sku");
					const attribute = document
						.querySelector(".size-options")
						.getAttribute("data-attribute");
					const option = document.querySelector(
						".size-options input[type=radio]:checked"
					).value;
					const priceD = productElement.querySelector(".discount")
						? parseFloat(
								productElement
									.querySelector(".producto-precio .discount")
									.textContent.replace("$", "")
									.trim()
						  )
						: 0;
					const newItem = {
						id: Number(productId),
						name,
						price,
						priceD,
						sku,
						attribute,
						option,
						quantity,
						subtotal:
							priceD > 0
								? (price - (price - priceD)) * quantity
								: price * quantity,
						image,
					};

					addItem(newItem);
				});
		});
	});

	// Manejo de compra de un solo producto
	document.querySelectorAll(".btn[data-action=buy]").forEach((button) => {
		button.addEventListener("click", (e) => {
			e.preventDefault();
			const productId = button.getAttribute("data-id");
			const opcion = document.querySelector("input[type=radio]:checked").value;

			if (!productId) {
				console.error("No se encontró el ID del producto para comprar.");
				return;
			}

			document.querySelector(".overlay").classList.add("active");
			document.querySelector(".checkout-modal").classList.add("active");
			document.querySelector(".checkout-modal").dataset.checkout = "product";
		});
	});

	document.querySelectorAll("input[type=radio]").forEach((input) => {
		input.addEventListener("click", (e) => {
			const image = input.getAttribute("data-image");
			const productImage =
				input.getAttribute("data-location") === "modal"
					? document.querySelector(".product-modal img")
					: document.querySelector(".product img");

			console.log(input);

			if (!image) {
				console.error(
					"No se encontró la imagen del producto para comprar.",
					image
				);
				return;
			}

			productImage.src = image;
		});
	});

	// Manejo de compra de todo el carrito
	const comprarCarritoBtn = document.querySelector("#comprarCarrito");
	if (comprarCarritoBtn) {
		comprarCarritoBtn.addEventListener("click", () => {
			getCartItems()
				.then((cartItems) => {
					if (cartItems.length === 0) {
						alert("Tu carrito está vacío. Agrega productos antes de comprar.");
						return;
					}
					document
						.querySelectorAll(".modal")
						.forEach((el) => el.classList.remove("active"));
					document.querySelector(".checkout-modal").classList.add("active");
					document.querySelector(".checkout-modal").dataset.checkout = "cart";
				})
				.catch((error) => {
					console.error(error);
					alert("Hubo un error al obtener los productos del carrito.");
				});
		});
	} else {
		console.warn("No se encontró el botón de comprar carrito.");
	}

	document.getElementById("proceed").addEventListener("click", (e) => {
		e.preventDefault();
		const form = document.getElementById("checkoutForm");
		const modal = document.querySelector(".checkout-modal");

		if (modal.dataset.checkout === "cart") {
			getCartItems()
				.then((cartItems) => {
					if (cartItems.length === 0) {
						alert("Tu carrito está vacío. Agrega productos antes de comprar.");
						return;
					}
					addOrder(cartItems);

					const message = generateWhatsAppMessage(
						cartItems,
						form.name.value,
						form.address.value,
						form.parcel.value
					);
					redirectToWhatsApp(message);

					clearCart()
						.then(() => {
							console.log("Carrito vaciado después de la compra.");
							displayCartItems();
						})
						.catch((error) => console.error(error));

					modal.classList.remove("active");
					overlay.classList.remove("active");
					form.reset();
				})
				.catch((error) => {
					console.error(error);
					alert("Hubo un error al obtener los productos del carrito.");
				});
		} else if (modal.dataset.checkout === "product") {
			getProductById(
				productId,
				opcion,
				form.name.value,
				form.address.value,
				form.parcel.value
			)
				.then((product) => form.reset())
				.catch((error) =>
					console.error("Error al comprar el producto:", error)
				);
		}
	});

	document.querySelectorAll(".ad a").forEach((ad) => {
		ad.addEventListener("click", (e) => {
			e.stopPropagation();
			fetch(
				`${BASE_URL}src/scripts/ad_visit.php?id=${e.target
					.closest(".ad a")
					.getAttribute("data-id")}`
			);
		});
	});

	document.addEventListener("click", (e) => {
		const btn = e.target.closest(".quantity button");
		if (!btn) return; // Si no es un botón dentro de .quantity, salir

		const container = btn.closest(".quantity");
		const productId = container.getAttribute("data-id");

		if (!productId) {
			console.error("No se encontró el ID del producto.");
			return;
		}

		if (btn.classList.contains("plus") && !btn.classList.contains("bag")) {
			container.querySelector("input[name='cantidad']").value++;
		} else if (
			btn.classList.contains("minus") &&
			!btn.classList.contains("bag")
		) {
			container.querySelector("input[name='cantidad']").value--;
		} else if (
			btn.classList.contains("plus") &&
			btn.classList.contains("bag")
		) {
			addQuantity(container, productId);
		} else if (
			btn.classList.contains("minus") &&
			btn.classList.contains("bag")
		) {
			deleteQuantity(container, productId);
		}
	});
});
