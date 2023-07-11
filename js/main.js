function validateUser() {
    passAproved = false;
    nameAproved = false;
    if (nombre.value.length > 5) {
        nameAproved = true;
    }
    if (pass.value == "" || confPass.value == "") {
        document.getElementById("pass").className = "form-control mb-4";
        document.getElementById("confPass").className = "form-control mb-4";
        return;
    }
    if (pass.value != confPass.value) {
        document.getElementById("pass").className = "form-control mb-4";
    }
    if (pass.value == confPass.value) {
        document.getElementById("pass").className = "form-control mb-4 is-valid";
        document.getElementById("confPass").className = "form-control mb-4 is-valid";
        passAproved = true;
    } else {
        document.getElementById("confPass").className = "form-control mb-4 is-invalid";
    }

    if (nameAproved && passAproved) {
        document.getElementById("btn").removeAttribute("disabled");
    } else {
        document.getElementById("btn").setAttribute("disabled", "disabled");
    }
}

pass.addEventListener("keyup", validateUser);
confPass.addEventListener("keyup", validateUser);
nombre.addEventListener("keyup", validateUser);