
if(window.outerWidth < 768) {
    const sidebarHome = document.querySelector(".sidebarHome");
    let menuHamburger = document.querySelector(".menuHamburger");
    const header = document.querySelector(".headerHome");
    const containerTitle = document.querySelector(".containerTitle");
    if(sidebarHome) {
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
        console.log(menuResponsive)
        console.log('here')

        if(menuResponsive.classList.contains("d-none") ) {
            menuResponsive.classList.remove('d-none')
        } else {
            menuResponsive.classList.add('d-none')
        }
    })
}





