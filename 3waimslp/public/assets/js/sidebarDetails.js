if(window.outerWidth < 768) {
    let sidebarDetails = document.querySelector(".sidebarDetails");
    let menuHamburgerDetails = document.querySelector(".menuHamburgerDetails");
    let headerDetails = document.querySelector(".headerDetails");
    let containerTitleDetails = document.querySelector(".containerTitleDetails");
    if(sidebarDetails) {
        sidebarDetails.classList.add('d-none');
    }
    if(containerTitleDetails) {
        containerTitleDetails.classList.add('w-75');
    }

    if(headerDetails) {
        headerDetails.classList.add('d-flex');
    }

    menuHamburgerDetails.classList.remove('d-none');

    menuHamburgerDetails.addEventListener("click", () => {
        const menuResponsiveDetails = document.querySelector(".menuResponsiveDetails");
        if(menuResponsiveDetails.classList.contains("d-none") ) {
            menuResponsiveDetails.classList.remove('d-none')
        } else {
            menuResponsiveDetails.classList.add('d-none')
        }
    })

}
