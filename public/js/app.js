document.addEventListener("DOMContentLoaded", () => {
    // SECURE: Trix attachments are disabled so job posts stay text-only and easier to sanitize safely.
    document.addEventListener("trix-file-accept", (event) => {
        event.preventDefault();
    });

    document.querySelectorAll("trix-editor[data-portal-editor]").forEach((editor) => {
        editor.classList.add("portal-trix-ready");
    });
});
