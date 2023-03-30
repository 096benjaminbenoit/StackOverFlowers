// FONCTION DE TRI //
const techno = document.getElementById('thread_search_technology');
const form = document.querySelector("[name='thread_search']");

function onChange() {
    form.submit();
}
techno.addEventListener('change', onChange);

// BARRE DE RECHERCHE //
const inputSearch = document.getElementById('search-bar');
const btnSearch = document.getElementById('search-submit');
const getThreads = document.querySelectorAll('.allThreads');
const pagination = document.querySelector('.pagination');

inputSearch.addEventListener('keyup', searchThread);
btnSearch.addEventListener('click', searchThread);

function searchThread(event) {
             
    const getValue = inputSearch.value.toLowerCase();
    
    getThreads.forEach(function(thread){
        if(thread.querySelector('.card-title').textContent.toLowerCase().includes(getValue)) {
            thread.style.display='block';
            pagination.style.display = 'flex';
        } else {
            thread.style.display = 'none';
            pagination.style.display = 'none';
            
        }
    })
}

