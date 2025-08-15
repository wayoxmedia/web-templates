/*
Template Name: Admin Template
Author: Wayox Media

File: js
*/
// ==============================================================
// Auto select left navbar
// ==============================================================
$(function () {
  "use strict";
  let url = window.location + "";
  let path = url.replace(
    window.location.protocol + "//" + window.location.host + "/",
    ""
  );
  let element = $("ul#sidebarNav a").filter(function () {
    return this.href === url || this.href === path; // || url.href.indexOf(this.href) === 0;
  });

  function findMatchingElement() {
    let currentUrl = window.location.href;
    let anchors = document.querySelectorAll("#sidebarNav a");
    for (let i = 0; i < anchors.length; i++) {
      if (anchors[i].href === currentUrl) {
        return anchors[i];
      }
    }

    return null; // Return null if no matching element is found
  }
  let elements = findMatchingElement();

  // Do something with the matching element
  if(elements){
    elements.classList.add("active");
  }

  document
    .querySelectorAll("ul#sidebarNav ul li a.active")
    .forEach(function (link) {
      link.closest("ul").classList.add("in");
      link.closest("ul").parentElement.classList.add("selected");
    });

  document.querySelectorAll("#sidebarNav li").forEach(function (li) {
    const isActive = li.classList.contains("selected");
    if (isActive) {
      const anchor = li.querySelector("a");
      if (anchor) {
        anchor.classList.add("active");
      }
    }
  });

  document.querySelectorAll("#sidebarNav a").forEach(function (link) {
    link.addEventListener("click", function () {
      const isActive = this.classList.contains("active");
      const parentUl = this.closest("ul");
      if (!isActive) {
        // hide any open menus and remove all other classes
        parentUl.querySelectorAll("ul").forEach(function (submenu) {
          submenu.classList.remove("in");
        });
        parentUl.querySelectorAll("a").forEach(function (navLink) {
          navLink.classList.remove("active");
        });

        // open our new menu and add the open class
        const submenu = this.nextElementSibling;
        if (submenu) {
          submenu.classList.add("in");
        }

        this.classList.add("active");
      } else {
        this.classList.remove("active");
        parentUl.classList.remove("active");
        const submenu = this.nextElementSibling;
        if (submenu) {
          submenu.classList.remove("in");
        }
      }
    });
  });
});
