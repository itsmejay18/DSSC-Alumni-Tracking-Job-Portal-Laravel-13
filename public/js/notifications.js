document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".portal-toast").forEach((toast) => {
        setTimeout(() => {
            toast.remove();
        }, 4000);
    });
});
