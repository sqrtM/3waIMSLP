
if(window.outerWidth < 768) {
    let sidebarHome = document.querySelector(".sidebarHome");
    let menuHamburger = document.querySelector(".menuHamburger");
    let header = document.querySelector(".headerHome");
    let containerTitle = document.querySelector(".containerTitle");
    if(sidebarHome ) {
        sidebarHome.classList.add('d-none');
    }

    if(containerTitle) {
        containerTitle.classList.add('w-75');
    }

    if(header) {
        header.classList.add('d-flex');
    }
    if(menuHamburger) {
        menuHamburger.classList.remove('d-none');
    }

    menuHamburger.addEventListener("click", () => {
        const menuResponsive = document.querySelector(".menuResponsive");
        if(menuResponsive.classList.contains("d-none") ) {
            menuResponsive.classList.remove('d-none')
        } else {
            menuResponsive.classList.add('d-none')
        }
    })
}





