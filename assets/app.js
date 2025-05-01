// import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 * Or encore_entry_script_tags('app') that I added instead
 */
// import './styles/app.css';

console.log('This log comes from assets/app.js 🎉');

const cards = document.getElementsByClassName("playing-card");
// console.log(cards[0].innerHTML.trim().codePointAt(0).toString(16).toUpperCase());

for (let card of cards) {
  // https://regex101.com/
  // korten renderas som symboler. Istället för att jämföra mot en array av 26 symboler verkar det lätt att konvertera tillbaka.
  // trim, hämta codePoint value, gör om till string16()unicodechars -> uppercase. Urk.
  if (/1F0[A|D]./gm.test(card.innerHTML.trim().codePointAt(0).toString(16).toUpperCase())){
    card.classList.add("playing-card-black")
  } else {
    card.classList.add("playing-card-red")
  }
}

// document.body.addEventListener("load", setSuits);
