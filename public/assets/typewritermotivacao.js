const quoteElement = document.getElementById("typedtext");
const authorElement = document.getElementById("typedauthor");

let iSpeed = 30;
let quoteText = "";
let authorText = "";
let quotePos = 0;

function typeQuote() {
    if (quotePos < quoteText.length) {
        quoteElement.innerHTML = quoteText.substring(0, quotePos + 1) + "<span class='cursor'>|</span>";
        quotePos++;
        setTimeout(typeQuote, iSpeed);
    } else {
        document.querySelector(".cursor")?.remove();
        setTimeout(typeAuthor, 400); // pequena pausa antes de começar o autor
    }
}

function typeAuthor() {
    if (authorPos < authorText.length) {
        authorElement.innerHTML = "— " + authorText.substring(0, authorPos + 1) + "<span class='cursor'>|</span>";
        authorPos++;
        setTimeout(typeAuthor, iSpeed);
    } else {
        document.querySelector(".cursor")?.remove();
    }
}

function loadRandomPhrase() {
    fetch("assets/phrases.json")
        .then(response => response.json())
        .then(data => {
            const randomIndex = Math.floor(Math.random() * data.length);
            const phrase = data[randomIndex];

            // define textos e zera posições
            quoteText = phrase.quote;
            authorText = phrase.author;
            quotePos = 0;
            authorPos = 0;

            // limpa os campos
            quoteElement.innerHTML = "";
            authorElement.innerHTML = "";

            typeQuote();
        })
        .catch(error => {
            quoteElement.textContent = "Erro ao carregar a frase.";
            console.error("Erro:", error);
        });
}

document.addEventListener("DOMContentLoaded", loadRandomPhrase);
