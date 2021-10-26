const menuItems = $(".menuItem");
menuItems.each((index, item) => item.addEventListener('click', (e) => menuIndicator(e.target)) );