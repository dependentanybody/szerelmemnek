document.addEventListener('mousemove', function(e){
  createHeart(e.pageX, e.pageY);
});

document.addEventListener('touchmove', function(e){
  e.preventDefault(); // Prevent default touch behavior
  let touch = e.touches[0];
  createHeart(touch.pageX, touch.pageY);
});

function createHeart(x, y) {
  let container = document.getElementById('container');
  let heart = document.createElement('span');
  heart.classList.add('heart');
  heart.style.left = x + 'px';
  heart.style.top = y + 'px';

  let size = Math.random() * 80;
  heart.style.width = 20 + size + 'px';
  heart.style.height = 20 + size + 'px';

  let transformValue = Math.random() * 360;
  heart.style.transform = 'rotate(' + transformValue + 'deg)';

  container.appendChild(heart);

  setTimeout(function(){
    heart.remove();
  }, 1000);
}
