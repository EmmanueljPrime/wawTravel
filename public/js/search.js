document.getElementById('searchButton').addEventListener('click', function () {
    const query = document.getElementById('searchInput').value.trim(); // Récupère la valeur et enlève les espaces inutiles

    if (query) {
        window.location.href = `/search/${encodeURIComponent(query)}`;
    } else {
        alert('Please enter a search term.');
    }

});

document.getElementById('searchInput').addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        document.getElementById('searchButton').click();
    }
});