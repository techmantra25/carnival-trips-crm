
(function () {
    // 🔹 Loader
    window.addEventListener("load", function () {
        const loader = document.getElementById("loader_id");
        if (loader) loader.classList.add("!hidden");
    });

    // 🔹 Current Year
    const year = document.getElementById("year");
    if (year) year.innerHTML = new Date().getFullYear();

    // 🔹 Custom Scrollbars
    const scrollIds = ["sidebar-scroll", "header-cart-items-scroll", "header-notification-scroll"];
    scrollIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) new SimpleBar(el, { autoHide: true });
    });

    // 🔹 Choices.js Init
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("[data-trigger]").forEach(el => {
            new Choices(el, {
                allowHTML: true,
                placeholderValue: "This is a placeholder set in the config",
                searchPlaceholderValue: "Search"
            });
        });
    });

    // 🔹 Box actions
    const boxSelector = ".box";

    document.querySelectorAll(".box-remove").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const box = this.closest(boxSelector);
            if (box) box.remove();
        });
    });

    document.querySelectorAll(".box-fullscreen").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const box = this.closest(boxSelector);
            if (box) {
                box.classList.toggle("box-fullscreen");
                box.classList.remove("box-collapsed");
            }
        });
    });

    // 🔹 Toggle Button
    document.querySelectorAll(".toggle").forEach(el => {
        el.addEventListener("click", () => el.classList.toggle("on"));
    });

    // 🔹 Scroll To Top
    const scrollBtn = document.querySelector(".scrollToTop");
    const doc = document.documentElement;

    window.addEventListener("scroll", () => {
        if (!scrollBtn) return;
        scrollBtn.style.display = window.scrollY > 100 ? "flex" : "none";
    });

    if (scrollBtn) {
        scrollBtn.addEventListener("click", () => window.scrollTo({ top: 0, behavior: "smooth" }));
    }

    // 🔹 Cart & Notifications Remove
    document.querySelectorAll(".header-remove-btn").forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();

            btn.parentNode.remove();

            // Cart update
            const cartContainer = document.getElementById("allCartsContainer");
            if (cartContainer) {
                document.getElementById("cart-data").innerText = `${cartContainer.children.length} Items`;
                document.getElementById("cart-data2").innerText = `${cartContainer.children.length}`;

                if (cartContainer.children.length === 0) {
                    cartContainer.parentNode.innerHTML = `
                        <div class="p-6 pb-8 text-center">
                          <div>
                            <i class="ri ri-shopping-cart-2-line leading-none text-4xl avatar avatar-lg bg-primary/20 text-primary rounded-full p-3 flex justify-center mx-auto"></i>
                            <div class="mt-5">
                              <p class="text-base font-semibold text-gray-800 dark:text-white mb-1">
                                No Items In Cart
                              </p>
                              <p class="text-xs text-gray-500 dark:text-white/70">
                                When you have Items added here, they will appear here.
                              </p>
                              <a href="javascript:void(0);" class="m-0 ti-btn ti-btn-primary py-1 mt-5">
                                <i class="ti ti-arrow-right text-base leading-none"></i> Continue Shopping
                              </a>
                            </div>
                          </div>
                        </div>`;
                }
            }

            // Notifications update
            const notifyContainer = document.getElementById("allNotifyContainer");
            if (notifyContainer) {
                document.getElementById("notify-data").innerText = `${notifyContainer.children.length}`;
                if (notifyContainer.children.length === 0) {
                    notifyContainer.parentNode.innerHTML = `
                        <div class="p-6 pb-8 text-center">
                          <div>
                            <i class="ri ri-notification-off-line leading-none text-4xl avatar avatar-lg bg-secondary/20 text-secondary rounded-full p-3 flex justify-center mx-auto"></i>
                            <div class="mt-5">
                              <p class="text-base font-semibold text-gray-800 dark:text-white mb-1">
                                No Notifications Found
                              </p>
                              <p class="text-xs text-gray-500 dark:text-white/70">
                                When you have notifications added here, they will appear here.
                              </p>
                            </div>
                          </div>
                        </div>`;
                }
            }
        });
    });

    // 🔹 Cart dropdown item remove
    document.querySelectorAll(".dropdown-item-close").forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();

            btn.closest(".dropdown-item")?.remove();

            const count = document.querySelectorAll(".dropdown-item-close").length;
            document.getElementById("cart-data").innerText = `${count} Items`;
            document.getElementById("cart-icon-badge").innerText = `${count}`;

            if (count === 0) {
                document.querySelector(".empty-header-item")?.classList.add("hidden");
                document.querySelector(".empty-item")?.classList.remove("hidden");
            }
        });
    });

    // 🔹 Notification dropdown item remove
    document.querySelectorAll(".dropdown-item-close1").forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();

            btn.closest(".dropdown-item")?.remove();

            const count = document.querySelectorAll(".dropdown-item-close1").length;
            document.getElementById("notifiation-data").innerText = `${count} Unread`;
            document.getElementById("notification-icon-badge").innerText = `${count}`;

            if (count === 0) {
                document.querySelector(".empty-header-item1")?.classList.add("hidden");
                document.querySelector(".empty-item1")?.classList.remove("hidden");
            }
        });
    });
})();

// 🔹 Fullscreen Toggle
const docEl = document.documentElement;
window.openFullscreen = function () {
    const openBtn = document.querySelector(".full-screen-open"),
          closeBtn = document.querySelector(".full-screen-close");

    if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
        if (docEl.requestFullscreen) docEl.requestFullscreen();
        else if (docEl.webkitRequestFullscreen) docEl.webkitRequestFullscreen();
        else if (docEl.msRequestFullscreen) docEl.msRequestFullscreen();

        closeBtn?.classList.replace("hidden", "block");
        openBtn?.classList.add("hidden");
    } else {
        if (document.exitFullscreen) document.exitFullscreen();
        else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
        else if (document.msExitFullscreen) document.msExitFullscreen();

        closeBtn?.classList.replace("block", "hidden");
        openBtn?.classList.remove("hidden");
    }
};

// 🔹 Count Up
let counter = 1;
setInterval(() => {
    document.querySelectorAll(".count-up").forEach(el => {
        const target = parseInt(el.getAttribute("data-count"), 10);
        if (counter <= target) {
            el.innerText = counter;
        }
    });
    counter++;
}, 10);

