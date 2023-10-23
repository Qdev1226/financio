
"use strict";
var elements = document.getElementsByClassName('financio-animate');

function playAnimation(element) {
    if ( element.getBoundingClientRect().top > 0 && element.getBoundingClientRect().top <= (window.innerHeight * 0.75) ) {
        element.classList.add('financio-animate-init');
    }
}

for (let element of elements) {
    window.addEventListener('load', function() {
        playAnimation(element);
    });
    window.addEventListener('scroll', function() {
        playAnimation(element);
    });
}