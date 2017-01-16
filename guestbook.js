window.onload = function() {
    var goToLoginBtn = document.getElementById("go_to_login");
    goToLoginBtn.addEventListener("click", function() {
        window.location.replace("login.php");
    });
}
