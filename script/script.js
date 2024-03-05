let opacity = 0;
let intervalID = 0;
function showNotification(message, type) {
    const notificationElement = document.createElement("div");
    notificationElement.classList.add("notification");

    if (type === 'success') {
        notificationElement.classList.add("notification--success");
    } else if (type === 'error') {
        notificationElement.classList.add("notification--error");
    }

    notificationElement.innerHTML = '<i class="fa-solid fa-xmark notification__icon"></i>';

    const messageElement = document.createElement("p");
    messageElement.textContent = message;

    notificationElement.appendChild(messageElement);

    setTimeout(hideNotification, 5000)

    document.body.appendChild(notificationElement);

    notificationElement.addEventListener("click", hideNotification);
}

function hideNotification() {
    const notificationElement = document.querySelector(".notification");
    notificationElement.classList.add("notification--hide");
    setTimeout( () => {
        notificationElement.parentNode.removeChild(notificationElement);
    }, 1000);
}
