const techno = document.getElementById('thread_search_technology');
const form = document.querySelector("[name='thread_search']");
function onChange() {
    form.submit();
}
techno.addEventListener('change', onChange);
