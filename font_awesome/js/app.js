// ----------------- Header Menu Append Arrow Icon ----------------- //
//------------ Level one ------------ //

const header_menu_level_one = document.querySelectorAll('.header-main__menu-list > ul > li');

header_menu_level_one.forEach(item=>{
    if(item.querySelector('ul') !== null)
    {
        item.querySelector('a').insertAdjacentHTML(
            'beforeend', '<i class="fas fa-chevron-down"></i>');
    }
});

//------------ Level Two ------------ //

const header_menu_level_two = document.querySelectorAll('.header-main__menu-list > ul > li ul li');

header_menu_level_two.forEach(item=>{
    if(item.querySelector('ul') !== null)
    {
        item.querySelector('a').insertAdjacentHTML(
            'beforeend', '<i class="fas fa-chevron-left"></i>');
    }
});

//------------ Responsive Menu ------------ //

const responsive_menu_list = document.querySelectorAll('.respondive-menu_list ul li');

responsive_menu_list.forEach(item=>{
    if(item.querySelector('ul') !== null)
    {
        item.querySelector('a').insertAdjacentHTML(
            'afterend', '<i class="fas fa-chevron-down"></i>');

            const arrow = item.querySelector('.fa-chevron-down');
            arrow.addEventListener('click' , (e)=>{
                const target_el = e.target;
                const ul_el = target_el.parentElement.querySelector('ul');
                ul_el.classList.toggle('show');
                target_el.classList.toggle('rotate');
            })
    }
});