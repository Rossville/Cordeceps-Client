const open_mobile_nav = document.querySelector('#open-mobile-nav');
const close_mobile_nav = document.querySelector('#close-mobile-nav');
const mobile_nav = document.querySelector('.mobile-nav-bar');

open_mobile_nav.addEventListener('click',function(){
    console.log('menu clicked')
    mobile_nav.classList.remove('hide-mobile-nav');
    mobile_nav.classList.add('display-mobile-nav');
})


close_mobile_nav.addEventListener('click',function(){
    mobile_nav.classList.remove('display-mobile-nav');
    mobile_nav.classList.add('hide-mobile-nav')
})

