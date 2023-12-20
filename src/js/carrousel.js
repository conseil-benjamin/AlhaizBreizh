/*********************************************/
/*********************************************/
//Carrousel

var carroussel = document.querySelector('.carousel');
var containerImg = document.querySelector('.carousel > .conteneur-images');
var dots = document.querySelectorAll('.carousel-dot');
var images = document.querySelectorAll('.carousel > .conteneur-images img');

console.log(images);

// Index of the current image
var currentIndex = 0;

// Function to update the carousel image
function updateCarouselImage() {

    // Hide all images
    for (var i = 0; i < images.length; i++) {
        images[i].style.display = 'none';
        dots[i].style.opacity = '0.5';
    }

    images[currentIndex].style.display = 'block';
    dots[currentIndex].style.opacity = '1';
}

// Call the function to set the initial image
updateCarouselImage();

// Handle the Previous button click
document.getElementById('prevButton').addEventListener('click', function() {
    // Decrease the currentIndex, or loop back to the end if it's already at the start
    console.log(currentIndex);
    currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
    updateCarouselImage();
});

// Handle the Next button click
document.getElementById('nextButton').addEventListener('click', function() {
    // Increase the currentIndex, or loop back to the start if it's already at the end
    console.log(currentIndex);
    currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
    updateCarouselImage();
});

// Handle the dot click
for (var i = 0; i < dots.length; i++) {
    dots[i].addEventListener('click', function() {
        currentIndex = parseInt(this.getAttribute('data-index'));
        updateCarouselImage();
    });
}