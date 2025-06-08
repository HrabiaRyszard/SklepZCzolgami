window.onload = function () {
    updateCart();
};

function addToCart(element) {

    var cardIDs = element.getElementsByClassName('cardID');
    if (cardIDs.length > 0) {
        var cardID = cardIDs[0];
        var id = cardID.innerHTML;

        var cookie = getCookie('koszyk');

        var cookiesValues = id;
        if (cookie != null && cookie != "") {
            cookiesValues = cookiesValues + "," + cookie;
        }
        let date = new Date();
        date.setFullYear(date.getFullYear() + 1); // Dodajemy rok do aktualnej daty


        document.cookie = "koszyk=" + cookiesValues + "; expires=" + date.toUTCString() + ";path=/";
        updateCart();
        element.classList.add("clicked");
        setTimeout(() => {
            element.classList.remove("clicked");
        }, 2000);
    }
}

function updateCart() {
    var cookie = getCookie('koszyk');
    var userCart = document.getElementById("userCart");
    if (userCart) {
        if (cookie == null || cookie == "") {
            userCart.classList.remove('full');
            userCart.innerText = "🛒 Koszyk";
        } else {
            userCart.classList.add('full');
            var len = cookie.split(",").length;

            userCart.innerText = "🛒 Koszyk (" + len + ")";
        }

        setCartSum();
    }
}

function removeCart() {

    document.cookie = "koszyk=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    updateCart();
}

function getCookie(name) {
    var cookies = document.cookie.split('; ');
    for (var cookie of cookies) {
        var [key, value] = cookie.split('=');
        if (key === name) return value;
    }
    return null;
}


function showButton() {
    var button = document.getElementById('form-button');
    button.style.display = 'inline';
}

function hideButton() {
    var button = document.getElementById('form-button');
    button.style.display = 'none';
    return true;
}
function changeValue($id) {
    var $price = document.getElementById('price' + $id).textContent;
    var $count = document.getElementById('count' + $id).value;
    var $value = document.getElementById('value' + $id);

    $value.innerHTML = (Math.round(($count * $price) * 100) / 100).toFixed(2);
    updateCartInCart($id, $count);
}
function updateCartInCart($id, $count) {
    var $cookie = getCookie('koszyk');
    var $cookieArray = $cookie.split(',');
    var $newCookie = "";
    for ($i = 0; $i < $cookieArray.length; $i++) {
        if ($cookieArray[$i] != $id) {
            if ($newCookie == "") {
                $newCookie = $newCookie + $cookieArray[$i];
            }
            else {
                $newCookie = $newCookie + "," + $cookieArray[$i];
            }
        }
    }
    for ($i = 0; $i < $count; $i++) {
        if ($newCookie == "") {
            $newCookie = $newCookie + $id;
        }
        else {
            $newCookie = $newCookie + "," + $id;
        }
    }
    var $date = new Date();
    $date.setFullYear($date.getFullYear() + 1); // Dodajemy rok do aktualnej daty


    document.cookie = "koszyk=" + $newCookie + "; expires=" + $date.toUTCString() + ";path=/";
    updateCart();
}


function onChangeDetailsValue(selectElement, orderID) {
    fetch("orderDetails.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "statusID=" + selectElement.value + "&orderID=" + orderID
    })
        .then(response => response.text())
        .then(data => {
            //console.log(data);
        })
        .catch(error => console.error("Błąd:", error));
}
function onChangeDelivery(selectElement) {

    let date = new Date();
    date.setFullYear(date.getFullYear() + 1);
    var id = selectElement.value;
    document.cookie = "przesylka=" + id + "; expires=" + date.toUTCString() + ";";

    setCartSum();
}

function setCartSum() {
    var sum = 0;
    var span = document.getElementById("spanDelivery" + getCookie('przesylka'));
    if (span) {
        sum = parseFloat(span.innerHTML.replace(/,/g, ''));
    }
    var cookies = getCookie('koszyk');
    if (cookies) {
        var cookieArray = cookies.split(",");
        var len = cookieArray.length;
        var tempArray = [];
        for (var i = 0; i < len; i++) {
            if (!tempArray.includes(cookieArray[i])) {
                var row = document.getElementById("value" + cookieArray[i]);
                if (row) {
                    sum += parseFloat(row.innerHTML.replace(/,/g, ''));
                    tempArray.push(cookieArray[i]);
                }
            }
        }

        var sumSpan = document.getElementById("cartSum");
        if (sumSpan) {
            sumSpan.innerHTML = sum.toFixed(2);
        }
    }
}

function validatePassword() {

    var passwordElement = document.getElementById("password");
    if (passwordElement) {
        var password = passwordElement.value;



        if (password.length < 8) {
            alert("Hasło musi mieć co najmniej 8 znaków.");
            return false;
        }
        if (!/[A-Z]/.test(password)) {
            alert("Hasło musi zawierać co najmniej jedną dużą literę.");
            return false;
        }
        if (!/[a-z]/.test(password)) {
            alert("Hasło musi zawierać co najmniej jedną małą literę.");
            return false;
        }
        if (!/[0-9]/.test(password)) {
            alert("Hasło musi zawierać co najmniej jedną cyfrę.");
            return false;
        }
        if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            alert("Hasło musi zawierać co najmniej jeden znak specjalny (!@#$%^&* itp.).");
            return false;
        }

        var confirmPasswordElement = document.getElementById("confirmPassword");
        if (confirmPasswordElement) {
            var confirmPassword = confirmPasswordElement.value;
            if (password != confirmPassword) {
                alert("Hasła nie są takie same!");
                return false;
            } else {

                return true;
            }
        }
        else {
            return false;
        }
    }

    else {
        return false;
    }

}