jQuery(document).ready(function($) {
    var itineraryLocationGallerySlider1 = new Swiper(".itinerary-location-gallery-slider-1", {
        slidesPerView: 1,
        navigation: {
            nextEl: ".itinerary-location-gallery-slider1-next",
            prevEl: ".itinerary-location-gallery-slider1-prev",
        },
    });

    var activitiesSwiper = new Swiper(".activities-swiper", {
        slidesPerView: 4,
        spaceBetween: 30,
        navigation: {
            nextEl: ".activities-swiper-button-next",
            prevEl: ".activities-swiper-button-prev",
        },
    });

    var specialExperienceSwiper = new Swiper(".special-experience-swiper", {
        slidesPerView: 4,
        spaceBetween: 30,
        navigation: {
            nextEl: ".special-experience-swiper-button-next",
            prevEl: ".special-experience-swiper-button-prev",
        },
    });
});