let opacity = 0;
let intervalID = 0;
function showNotification(message, type) {
    const notificationElement = document.createElement("div");
    notificationElement.classList.add("notification");
    notificationElement.setAttribute('id', 'notification');

    if (type === 'success') {
        notificationElement.classList.add("notification--success");
    } else if (type === 'error') {
        notificationElement.classList.add("notification--error");
    }

    const messageElement = document.createElement("p");
    messageElement.textContent = message;
    notificationElement.appendChild(messageElement);

    document.body.appendChild(notificationElement);

    setTimeout(() => {
        fadeIn(notificationElement);
    }, 500);

    setTimeout(() => {
        notificationElement.remove();
    }, 50000);
}

function fadeIn(object) {
    setInterval(show, 10, object);
}

function show(object) {
    const element = document.getElementById(object.id);
    opacity = Number(window.getComputedStyle(element)
        .getPropertyValue("opacity"));
    if (opacity < 1) {
        opacity = opacity + 0.05;
        element.style.opacity = opacity
    } else {
        clearInterval(intervalID);
    }
}