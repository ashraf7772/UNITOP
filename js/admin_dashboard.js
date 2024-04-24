function showNotification(message) {

    const notification = document.createElement("div");
    notification.classList.add("notification");
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        document.body.removeChild(notification);
    }, 3000);
}

const urlParams = new URLSearchParams(window.location.search);
const successMessage =urlParams.get('success');
if(successMessage) {
    showNotification(successMessage);
}

/*
This script creates and displays a notification message on the
web page based on the value of the 'success' query parameter 
in the URL. If the success parameter isnt in the URL, its value 
will be shown as a notification on the webpage. 
*/