document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll("#sidebar .event-link a");
    const contentItems = document.querySelectorAll(".content-item");

    /* SIDEBAR TOGGLE */
    if (typeof $ !== "undefined") {
      $(document).ready(function () {
        $("#sidebarCollapse").on("click", function () {
          $("#sidebar").toggleClass("active");
        });
      });
    } else {
      document
        .getElementById("sidebarCollapse")
        ?.addEventListener("click", function () {
          document.getElementById("sidebar").classList.toggle("active");
        });
    }

    /* START CONTENT AND SIDEBAR LOGIC */
    // Load the last active link and content from localStorage
    const lastActiveContentId = localStorage.getItem("activeContent");

    if (lastActiveContentId) {
      links.forEach((link) => {
        if (link.getAttribute("href") === `#${lastActiveContentId}`) {
          link.classList.add("active-link");
        }
      });

      contentItems.forEach((item) => {
        item.style.display = item.id === lastActiveContentId ? "block" : "none";
      });

      // Set initial title based on stored active content
      const activeContent = document.querySelector(`#${lastActiveContentId}`);
      if (activeContent) {
        document.title = `${activeContent.dataset.title}`;
      }
    } else {
      // Set Default Active Link
      const defaultLink = document.querySelector("#sidebar .event-link a[href='#content1']");
      if (defaultLink) {
        defaultLink.classList.add("active-link");
        document.querySelector("#content1").style.display = "block";
        document.title = `Dashboard`;
      }
    }

    // Click Event for Sidebar Links
    links.forEach((link) => {
      link.addEventListener("click", function (event) {
        event.preventDefault();

        const href = this.getAttribute("href");

        if (!href || !href.startsWith("#")) {
          console.error("Invalid href attribute:", href);
          return;
        }

        const targetContentId = href.substring(1);
        const targetContent = document.querySelector(`#${targetContentId}`);

        if (targetContent) {
          document.querySelectorAll(".content-item").forEach((item) => (item.style.display = "none"));
          targetContent.style.display = "block";

          // Remove active class from all links
          links.forEach((l) => l.classList.remove("active-link"));
          this.classList.add("active-link");

          // Save the active content to localStorage
          localStorage.setItem("activeContent", targetContentId);

          // Update the page title dynamically
          document.title = `${targetContent.dataset.title}`;
        } else {
          console.error(`Target content with ID ${targetContentId} not found.`);
        }
      });
    });

    /* END CONTENT AND SIDEBAR LOGIC */
});
