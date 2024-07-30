function scrollCarousel(direction) {
    const carousel = document.querySelector('.feedback-carousel');
    const scrollAmount = carousel.clientWidth / 3;
    carousel.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
}
