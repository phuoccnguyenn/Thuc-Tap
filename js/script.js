let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   profile.classList.remove('active');
   navbar.classList.remove('active');
}

function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}

  function scrollToTop() {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  // Function để xử lý sự kiện khi người dùng nhấp vào mũi tên
  function handleArrowClick() {
    scrollToTop();
  }

function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}
document.querySelector(".arrow-box").addEventListener("click", function() {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });