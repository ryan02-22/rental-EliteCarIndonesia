// ============================================================================
// EliteCar Indonesia - Rental Mobil Application
// ============================================================================

// API Configuration
const API_BASE_URL = '/api';
const CARS_ENDPOINT = `${API_BASE_URL}/cars`;
const BOOKINGS_ENDPOINT = `${API_BASE_URL}/bookings`;

// Fallback data mobil (jika API tidak tersedia)
const FALLBACK_CARS = [
  { id: 'c1', name: 'Toyota Fortuner', type: 'SUV', pricePerDay: 850000, image: ['fortuner-1.jpg', 'fortuner-2.jpg', 'fortuner-3.jpg'] },
  { id: 'c2', name: 'Honda CR-V', type: 'SUV', pricePerDay: 800000, image: ['crv-1.jpg', 'crv-2.jpg', 'crv-3.jpg'] },
  { id: 'c3', name: 'Daihatsu Terios', type: 'SUV', pricePerDay: 650000, image: ['terios-1.jpg', 'terios-2.jpg', 'terios-3.jpg'] },
  { id: 'c4', name: 'Hyundai Palisade', type: 'SUV', pricePerDay: 1200000, image: ['palisade-1.jpg', 'palisade-2.jpg', 'palisade-3.jpg'] },
  { id: 'c5', name: 'Toyota Avanza', type: 'Van', pricePerDay: 550000, image: ['tyt-avanza-1.webp', 'tyt-avanza-2.webp', 'tyt-avanza-3.webp'] },
  { id: 'c6', name: 'Mitsubishi Xpander', type: 'Van', pricePerDay: 600000, image: ['xpander-1.jpg', 'xpander-2.jpg', 'xpander-3.jpg'] },
  { id: 'c7', name: 'Suzuki Ertiga', type: 'Van', pricePerDay: 520000, image: ['ertiga-1.jpg', 'ertiga-2.jpg', 'ertiga-3.jpg'] },
  { id: 'c8', name: 'Kia Carnival', type: 'Van', pricePerDay: 900000, image: ['carnival-1.jpg', 'carnival-2.webp', 'carnival-3.jpg'] },
  { id: 'c9', name: 'Honda City', type: 'Sedan', pricePerDay: 500000, image: ['city-1.jpg', 'city-2.jpg', 'city-3.jpg'] },
  { id: 'c10', name: 'Honda Civic', type: 'Sedan', pricePerDay: 750000, image: ['civic-1.jpg', 'civic-2.jpg', 'civic-3.jpg'] },
  { id: 'c11', name: 'Toyota Camry', type: 'Sedan', pricePerDay: 900000, image: ['camry-1.jpg', 'camry-2.webp', 'camry-3.webp'] },
  { id: 'c12', name: 'Mazda 6', type: 'Sedan', pricePerDay: 950000, image: ['mazda-1.jpg', 'mazda-2.jpg', 'mazda-3.jpg'] }
];

// State
let cars = [];
let currentFilter = 'all';

// Utilities
const fmtIDR = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });

// DOM Elements
const carGrid = document.getElementById('carGrid');
const filters = document.querySelectorAll('.filter-btn');
const carSelect = document.getElementById('carSelect');
const pricePerDayEl = document.getElementById('pricePerDay');
const startDateEl = document.getElementById('startDate');
const endDateEl = document.getElementById('endDate');
const renterEmailEl = document.getElementById('renterEmail');
const totalPriceEl = document.getElementById('totalPrice');
const daysCountEl = document.getElementById('daysCount');
const dateErrorEl = document.getElementById('dateError');
const nameErrorEl = document.getElementById('nameError');
const bookingForm = document.getElementById('bookingForm');
const yearEl = document.getElementById('year');
const formSuccessEl = document.getElementById('formSuccess');

// ============================================================================
// API Functions
// ============================================================================

/**
 * Fetch cars dari API atau fallback ke data lokal
 * @returns {Promise<Array>} Array of car objects
 */
async function fetchCars() {
  try {
    const response = await fetch(CARS_ENDPOINT);
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    const data = await response.json();
    return Array.isArray(data) ? data : data.cars || [];
  } catch (error) {
    console.warn('API tidak tersedia, menggunakan data fallback:', error.message);
    return FALLBACK_CARS;
  }
}

/**
 * Submit booking ke API
 * @param {Object} payload - { name, email, carId, startDate, endDate, totalPrice }
 * @returns {Promise<Object>} Response dari API
 */
async function submitBooking(payload) {
  try {
    const response = await fetch(BOOKINGS_ENDPOINT, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return await response.json();
  } catch (error) {
    // Mock response jika API tidak tersedia
    console.warn('API tidak tersedia, menggunakan mock response:', error.message);
    return {
      success: true,
      bookingId: `BK-${Date.now()}`,
      message: 'Reservasi berhasil diproses (simulasi)'
    };
  }
}

// ============================================================================
// Render Functions
// ============================================================================

/**
 * Render kartu mobil dengan data-* attributes untuk backend mapping
 * @param {Array} list - Array of car objects
 */
function renderCars(list) {
  carGrid.innerHTML = '';
  list.forEach(car => {
    const article = document.createElement('article');
    article.className = 'card';
    article.setAttribute('role', 'listitem');
    article.setAttribute('data-id', car.id);
    article.setAttribute('data-type', car.type);
    article.setAttribute('data-price', car.pricePerDay);
    article.setAttribute('data-name', car.name);

    const images = Array.isArray(car.image) ? car.image : [car.image];
    const sliderControls = images.length > 1
      ? `<button class="nav prev" aria-label="Sebelumnya">‹</button><button class="nav next" aria-label="Berikutnya">›</button>`
      : '';

    const slides = images.map((spec, i) => {
      const activeCls = i === 0 ? ' active' : '';
      const src = String(spec);
      return `<img class="slide${activeCls}" src="${src}" alt="${car.name} - ${car.type} - gambar ${i+1}">`;
    }).join('');

    article.innerHTML = `
      <div class="card-media">
        <div class="slider" data-car-id="${car.id}" data-index="0">
          ${slides}
          ${sliderControls}
        </div>
      </div>
      <div class="card-body">
        <div class="car-name">${car.name}</div>
        <div class="car-meta">
          <span class="chip">${car.type}</span>
          <span class="price">${fmtIDR.format(car.pricePerDay)}/hari</span>
        </div>
        <button class="card-btn" data-car-id="${car.id}" aria-label="Pesan ${car.name}">Pesan</button>
      </div>
    `;

    carGrid.appendChild(article);
  });

  initSliders();
  attachCardButtons();
  injectProductJSONLD(list);
}

/**
 * Attach event listener ke tombol "Pesan" di setiap card
 */
function attachCardButtons() {
  const cardButtons = document.querySelectorAll('.card-btn');
  cardButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const carId = btn.getAttribute('data-car-id');
      carSelect.value = carId;
      carSelect.dispatchEvent(new Event('change'));
      document.getElementById('booking').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
}

/**
 * Inject JSON-LD Product schema untuk setiap mobil
 * @param {Array} list - Array of car objects
 */
function injectProductJSONLD(list) {
  const products = list.map(car => ({
    '@context': 'https://schema.org',
    '@type': 'Product',
    'name': car.name,
    'category': `Rental Mobil - ${car.type}`,
    'offers': {
      '@type': 'Offer',
      'price': car.pricePerDay,
      'priceCurrency': 'IDR',
      'availability': 'https://schema.org/InStock',
      'priceSpecification': {
        '@type': 'UnitPriceSpecification',
        'price': car.pricePerDay,
        'priceCurrency': 'IDR',
        'unitCode': 'DAY'
      }
    }
  }));

  const scriptEl = document.getElementById('jsonld-products');
  if (scriptEl) {
    scriptEl.textContent = JSON.stringify(products);
  }
}

// ============================================================================
// Filter Functions
// ============================================================================

/**
 * Setup filter buttons dengan event listeners
 */
function setupFilters() {
  filters.forEach(btn => {
    btn.addEventListener('click', () => {
      filters.forEach(b => {
        b.classList.remove('active');
        b.setAttribute('aria-pressed', 'false');
      });
      btn.classList.add('active');
      btn.setAttribute('aria-pressed', 'true');
      currentFilter = btn.dataset.filter;
      applyFilter(currentFilter);
    });
  });
}

/**
 * Apply filter berdasarkan tipe mobil
 * @param {string} type - 'all', 'SUV', 'Sedan', atau 'Van'
 */
function applyFilter(type) {
  const items = Array.from(carGrid.children);
  items.forEach(item => {
    const match = type === 'all' || item.dataset.type === type;
    item.style.display = match ? 'block' : 'none';
  });
}

// ============================================================================
// Booking Form Functions
// ============================================================================

/**
 * Setup booking form dengan validasi dan event listeners
 */
function setupBookingForm() {
  populateCarSelect(cars);

  carSelect.addEventListener('change', updatePricing);
  startDateEl.addEventListener('change', updatePricing);
  endDateEl.addEventListener('change', updatePricing);

  bookingForm.addEventListener('submit', handleFormSubmit);
}

/**
 * Get selected car dari select dropdown
 * @returns {Object|null} Car object atau null
 */
function getSelectedCar() {
  const id = carSelect.value;
  return cars.find(c => c.id === id) || null;
}

/**
 * Calculate jumlah hari sewa
 * @param {string} startStr - ISO date string
 * @param {string} endStr - ISO date string
 * @returns {number} Jumlah hari
 */
function calculateDays(startStr, endStr) {
  if (!startStr || !endStr) return 0;
  const start = new Date(startStr);
  const end = new Date(endStr);
  const startUTC = Date.UTC(start.getFullYear(), start.getMonth(), start.getDate());
  const endUTC = Date.UTC(end.getFullYear(), end.getMonth(), end.getDate());
  const diff = (endUTC - startUTC) / (1000 * 60 * 60 * 24);
  return diff > 0 ? diff : 0;
}

/**
 * Update pricing display berdasarkan mobil dan tanggal terpilih
 */
function updatePricing() {
  const selected = getSelectedCar();
  const days = calculateDays(startDateEl.value, endDateEl.value);

  if (selected) {
    pricePerDayEl.textContent = `Harga per hari: ${fmtIDR.format(selected.pricePerDay)}`;
  } else {
    pricePerDayEl.textContent = 'Harga per hari: -';
  }

  dateErrorEl.textContent = '';
  const hasDates = startDateEl.value && endDateEl.value;
  if (hasDates && days <= 0) {
    dateErrorEl.textContent = 'Tanggal selesai harus setelah tanggal mulai.';
  }

  const total = selected && days > 0 ? selected.pricePerDay * days : 0;
  totalPriceEl.textContent = fmtIDR.format(total);
  daysCountEl.textContent = `${days} hari`;
}

/**
 * Populate car select dropdown
 * @param {Array} list - Array of car objects
 */
function populateCarSelect(list) {
  carSelect.innerHTML = '<option value="" disabled selected>Pilih mobil</option>';
  list.forEach(car => {
    const opt = document.createElement('option');
    opt.value = car.id;
    opt.textContent = `${car.name} • ${car.type} • ${fmtIDR.format(car.pricePerDay)}/hari`;
    carSelect.appendChild(opt);
  });
}

/**
 * Handle form submission dengan validasi dan API call
 * @param {Event} e - Submit event
 */
async function handleFormSubmit(e) {
  e.preventDefault();
  formSuccessEl.textContent = '';
  nameErrorEl.textContent = '';
  dateErrorEl.textContent = '';

  const name = bookingForm.renterName.value.trim();
  const email = bookingForm.renterEmail.value.trim();
  const selected = getSelectedCar();
  const days = calculateDays(startDateEl.value, endDateEl.value);

  // Validasi
  if (!name) {
    nameErrorEl.textContent = 'Nama penyewa wajib diisi.';
    bookingForm.renterName.focus();
    return;
  }

  if (!email || !bookingForm.renterEmail.validity.valid) {
    const emailError = document.createElement('small');
    emailError.className = 'error';
    emailError.id = 'emailError';
    emailError.textContent = 'Email tidak valid.';
    renterEmailEl.parentElement.appendChild(emailError);
    renterEmailEl.focus();
    return;
  }

  if (!selected) {
    alert('Silakan pilih mobil.');
    carSelect.focus();
    return;
  }

  if (days <= 0) {
    dateErrorEl.textContent = 'Tanggal selesai harus setelah tanggal mulai.';
    endDateEl.focus();
    return;
  }

  // Submit ke API
  const payload = {
    name,
    email,
    carId: selected.id,
    startDate: startDateEl.value,
    endDate: endDateEl.value,
    totalPrice: selected.pricePerDay * days
  };

  try {
    const result = await submitBooking(payload);
    formSuccessEl.textContent = `Reservasi berhasil! ${name} memesan ${selected.name} selama ${days} hari. Total: ${fmtIDR.format(payload.totalPrice)}. ID Booking: ${result.bookingId || 'N/A'}`;
    bookingForm.reset();
    updatePricing();
  } catch (error) {
    formSuccessEl.textContent = '';
    alert(`Terjadi kesalahan: ${error.message}`);
  }
}

// ============================================================================
// Slider Functions
// ============================================================================

/**
 * Initialize image sliders untuk kartu mobil
 */
function initSliders() {
  const sliders = document.querySelectorAll('.slider');
  sliders.forEach(slider => {
    const slides = slider.querySelectorAll('.slide');
    if (slides.length <= 1) return;

    function show(index) {
      const max = slides.length;
      let i = index;
      if (i < 0) i = max - 1;
      if (i >= max) i = 0;
      slider.dataset.index = String(i);
      slides.forEach((img, idx) => img.classList.toggle('active', idx === i));
    }

    const prev = slider.querySelector('.prev');
    const next = slider.querySelector('.next');
    prev && prev.addEventListener('click', (e) => {
      e.stopPropagation();
      const current = Number(slider.dataset.index || 0) - 1;
      show(current);
    });
    next && next.addEventListener('click', (e) => {
      e.stopPropagation();
      const current = Number(slider.dataset.index || 0) + 1;
      show(current);
    });

    let timer = setInterval(() => {
      const current = Number(slider.dataset.index || 0) + 1;
      show(current);
    }, 3500);

    slider.addEventListener('mouseenter', () => { clearInterval(timer); });
    slider.addEventListener('mouseleave', () => {
      clearInterval(timer);
      timer = setInterval(() => {
        const current = Number(slider.dataset.index || 0) + 1;
        show(current);
      }, 3500);
    });
  });
}

// ============================================================================
// Initialization
// ============================================================================

/**
 * Initialize aplikasi
 */
async function init() {
  yearEl.textContent = new Date().getFullYear();

  cars = await fetchCars();
  renderCars(cars);
  setupFilters();
  setupBookingForm();
  applyFilter('all');
  updatePricing();
}

// Start aplikasi saat DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}
