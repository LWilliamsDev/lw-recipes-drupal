
/* Variables */
const mobileMenuBtn = document.querySelector("#toggle-mobile-menu");
const menu = document.querySelector(".nav");
const submenu = document.querySelectorAll(".open-toggle");
const firstLink = document.querySelector("#primary-nav li:first-child a");

const mobileSearchBtn = document.querySelector("#search-btn-mobile");
const desktopSearchBtn = document.querySelector("#search-btn-desktop");
const searchBtn = document.querySelectorAll(".search-btn");
const search = document.querySelector("#search-form");
const searchSubmit = document.querySelector("#search-submit");
const searchInput = document.querySelector("#search-input");


/* Global Menu open/close */
if (mobileMenuBtn && menu) {

	//Open/close menu when mobile menu button is clicked
	mobileMenuBtn.addEventListener("click", () => {
		const ariaExpanded = mobileMenuBtn.getAttribute("aria-expanded");


		if (ariaExpanded == "true") {
			menu.classList.add("hidden");
			mobileMenuBtn.setAttribute("aria-expanded", false);

			if (mobileSearchBtn) {
				mobileSearchBtn.removeAttribute("disabled");
			}
		}
		else {
			menu.classList.remove("hidden");
			mobileMenuBtn.setAttribute("aria-expanded", true);

			//Automatically focus on the first link upon opening the menu
			if (firstLink) {
				firstLink.focus();
			}

			if (mobileSearchBtn) {
				mobileSearchBtn.setAttribute("disabled", "");
			}

		}
	});


	//Automatically close menu on mobile if it's open and the user tabs to the element before the menu button
	mobileMenuBtn.addEventListener("keydown", (event) => {
		if (event.key === 'Tab' && event.shiftKey) {
			if (mobileMenuBtn.getAttribute('aria-expanded') == 'true') {
				mobileMenuBtn.setAttribute("aria-expanded", false);

				menu.classList.add("hidden");

				if (mobileSearchBtn) {
					mobileSearchBtn.removeAttribute("disabled");
				}
			}
		}
	});

	//Mobile focus trap
	const allFocusableElements = menu.querySelectorAll('a, button, input, [tabindex="0"]');

		if (allFocusableElements.length > 0) {
			allFocusableElements[allFocusableElements.length - 1]?.addEventListener("keydown", (event) => {
				if (window.innerWidth < 769) {
					if (event.key === 'Tab' && !event.shiftKey) {
						event.preventDefault();
						mobileMenuBtn.focus();

					}
				}
			});
		}

	}

// Open/close submenus if the submenu has children
if (submenu) {
	submenu.forEach((menu) => {
		const thisSubmenu = menu.nextElementSibling;
		const thisParentMenu = menu.closest("li");
		const button = thisParentMenu.querySelector("button");

		if (thisSubmenu && thisParentMenu && button) {

			const allFocusableElements = thisParentMenu.querySelectorAll('a, button, input, [tabindex="0"]');

			//Add open/close behavior to submenu toggle button (appears only if submenu has children)
			//Should work for both desktop and mobile
			menu.addEventListener("click", () => {

				if (thisParentMenu.classList.contains("active")) {
					thisParentMenu.classList.remove("active");
					menu.setAttribute("aria-expanded", false);
				}
				else {
					thisParentMenu.classList.add("active");
					menu.setAttribute("aria-expanded", true);
				}

				//Add focus trap to the last element in the submenu
				allFocusableElements[allFocusableElements.length - 1]?.addEventListener("keydown", (event) => {
					if (event.key === 'Tab' && !event.shiftKey) {
						event.preventDefault();
						button.focus();

					}
				});

			});


			//Automatically close the submenu if the user tabs out of it
			allFocusableElements[0].addEventListener("keydown", (event) => {
				if (event.key === 'Tab' && event.shiftKey && button.getAttribute('aria-expanded') == 'true') {
					button.setAttribute("aria-expanded", false);
					thisParentMenu.classList.remove("active");
				}
			});
		}
	});
}


/* Global Search Button open/close */

if (searchBtn && search) {

	//There are 2 search buttons (one on mobile and one on desktop), therefore listeners mut be added to both
	searchBtn.forEach((btn) => {

		//ordinary listener to open/close search when button is clicked
		btn.addEventListener("click", () => {
			const ariaExpanded = btn.getAttribute("aria-expanded");

			if (ariaExpanded == 'true') {
				btn.setAttribute("aria-expanded", false);
				search.classList.add('hidden');

				//on mobile only either the menu or search button can be opened at one time
				if (window.innerWidth < 769 && mobileMenuBtn) {
					mobileMenuBtn.removeAttribute("disabled");
				}
			}	
			else {
				btn.setAttribute("aria-expanded", true);
				search.classList.remove('hidden');
				searchInput.focus();

				if (window.innerWidth < 769 && mobileMenuBtn) {
					mobileMenuBtn.setAttribute("disabled", "");
				}
			}
		});

		//If search is open, user is focused on the search button, and tabs forward or backward, close search
		btn.addEventListener("keydown", (event) => {
			if (event.key === 'Tab' && btn.getAttribute('aria-expanded') == 'true') {
				btn.setAttribute('aria-expanded', false);
				search.classList.add('hidden');

				if (window.innerWidth < 769 && mobileMenuBtn) {
					mobileMenuBtn.removeAttribute("disabled");
				}

			}
		});
	});

	//If search is open and user tabs out of it (by tabbing while focused on the submit button), return focus to the search toggle button
	if (searchSubmit) {
		searchSubmit.addEventListener("keydown", (event) => {
			if (event.key === 'Tab' && !event.shiftKey) {
				event.preventDefault();

				if (window.innerWidth < 769 && mobileSearchBtn) {
					mobileSearchBtn.focus();
				}
				else {
					if (desktopSearchBtn) {
						desktopSearchBtn.focus();
					}
				}
			}
		});
	}

	//If search is open and user tabs out of it (by tabbing backwards while focused on the input), return focus to the search toggle button
	if (searchInput) {
			searchInput.addEventListener("keydown", (event) => {
			if (event.key === 'Tab' && event.shiftKey) {
				event.preventDefault();

				if (window.innerWidth < 769 && mobileSearchBtn) {
					mobileSearchBtn.focus();
				}
				else {
					if (desktopSearchBtn) {
						desktopSearchBtn.focus();
					}
				}
			}
		});

	}

}

/* Automatically close everything if user resizes window past the mobile or desktop breakpoints */
window.addEventListener("resize", () => {
	
	if (!mobileSearchBtn || !desktopSearchBtn) return;

	if (window.innerWidth < 769) {
		if (desktopSearchBtn.getAttribute("aria-expanded") == 'true') {
			desktopSearchBtn.setAttribute("aria-expanded", false);
			mobileSearchBtn.setAttribute("aria-expanded", true);
		}
	}
	else {

		if (mobileSearchBtn.getAttribute("aria-expanded") == 'true') {
			mobileSearchBtn.setAttribute("aria-expanded", false);
			desktopSearchBtn.setAttribute("aria-expanded", true);
		}
	}

});

/* Close search and menus if the user hits ESC key */
document.addEventListener("keydown", (event) => {

	if (event.key !== "Escape") {
		return;
	}


	// Close mobile menu.
	if (
		window.innerWidth < 769 &&
		mobileMenuBtn &&
		menu &&
		mobileMenuBtn.getAttribute("aria-expanded") === "true"
	) {

		//if submenu is open, close the submenu
		const openSubmenuBtn = menu.querySelector("button[aria-expanded='true']");

		if (openSubmenuBtn) {
			openSubmenuBtn.click();
		}

		menu.classList.add("hidden");
		mobileMenuBtn.setAttribute("aria-expanded", false);
		mobileMenuBtn.focus();

		if (mobileSearchBtn) {
			mobileSearchBtn.removeAttribute("disabled");
		}
	}

	//Close desktop menu
	if (window.innerWidth >= 769 && menu && menu.querySelector("button[aria-expanded='true']")) {
		menu.querySelector("button[aria-expanded='true']").click();	
	}

	// Close search.
	if (search) {
		searchBtn.forEach((btn) => {
			if (btn.getAttribute("aria-expanded") === "true") {
				btn.setAttribute("aria-expanded", false);
				btn.focus();
			}
		});

		search.classList.add("hidden");

		if (window.innerWidth < 769 && mobileMenuBtn) {
			mobileMenuBtn.removeAttribute("disabled");
		}
	}
});



