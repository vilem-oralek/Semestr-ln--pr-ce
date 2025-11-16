const galleryGrid = document.getElementById("gallery-grid");
const prevButton = document.getElementById("prev-button");
const nextButton = document.getElementById("next-button");

const imagesPerPage = 8; // Počet obrázků na stránku
let currentPage = 1;

// Generování obrázků
const totalImages = 32; // Celkový počet obrázků
const images = Array.from({ length: totalImages }, (_, i) => `placeholderimg.jpg`);

// Funkce pro zobrazení obrázků na aktuální stránce
function displayImages(page) {
  galleryGrid.innerHTML = ""; // Vyčištění galerie
  const startIndex = (page - 1) * imagesPerPage;
  const endIndex = Math.min(startIndex + imagesPerPage, images.length);

  for (let i = startIndex; i < endIndex; i++) {
    const img = document.createElement("img");
    img.src = images[i];
    img.alt = `Obrázek ${i + 1}`;
    img.classList.add("gallery-image");
    img.addEventListener("click", () => openModal(images[i])); // Přidání události pro otevření modalu
    galleryGrid.appendChild(img);
  }

  console.log(`Zobrazuji stránku ${page}`); // Kontrola, že stránkování funguje
}

// Funkce pro přepínání stránek
function updatePagination() {
  prevButton.disabled = currentPage === 1;
  nextButton.disabled = currentPage === Math.ceil(images.length / imagesPerPage);
}

// Události pro tlačítka
prevButton.addEventListener("click", () => {
  if (currentPage > 1) {
    currentPage--;
    displayImages(currentPage);
    updatePagination();
  }
});

nextButton.addEventListener("click", () => {
  if (currentPage < Math.ceil(images.length / imagesPerPage)) {
    currentPage++;
    displayImages(currentPage);
    updatePagination();
  }
});

// Funkce pro otevření modalu
function openModal(imageSrc) {
  const modal = document.getElementById("image-modal");
  const modalImage = document.getElementById("modal-image");
  modalImage.src = imageSrc;
  modal.style.display = "flex";
}

// Funkce pro zavření modalu
function closeModal() {
  const modal = document.getElementById("image-modal");
  modal.style.display = "none";
}

// Inicializace galerie
displayImages(currentPage);
updatePagination();

// Přidání události pro zavření modalu
document.getElementById("image-modal").addEventListener("click", closeModal);