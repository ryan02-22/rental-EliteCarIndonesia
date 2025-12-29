// ============================================================================
// EliteCar Indonesia - Rental Mobil Application
// ============================================================================

// API Configuration
const API_BASE_URL = '/api';
const CARS_ENDPOINT = `${API_BASE_URL}/cars.php`;
const BOOKINGS_ENDPOINT = `${API_BASE_URL}/bookings.php`;

// Fallback data mobil (Digunakan jika API tidak dapat dijangkau)
// ID menggunakan format integer murni untuk sinkronisasi dengan database
const FALLBACK_CARS = [
  { id: 1, name: 'Toyota Fortuner', type: 'SUV', pricePerDay: 850000, image: ['fortuner-1.jpg', 'fortuner-2.jpg', 'fortuner-3.jpg'] },
  { id: 2, name: 'Honda CR-V', type: 'SUV', pricePerDay: 800000, image: ['crv1.webp', 'crv2.webp', 'crv3.webp', 'crv4.webp', 'crv5.webp', 'crv6.jpg'] },
  { id: 3, name: 'Daihatsu Terios', type: 'SUV', pricePerDay: 650000, image: ['terios-2b.webp', 'terios-3c.webp', 'terios-4d.webp', 'terios-5e.webp', 'terios-6e.jpg'] },
  { id: 4, name: 'Hyundai Palisade', type: 'SUV', pricePerDay: 1200000, image: ['palisade1.jpg', 'palisade2.jpg', 'palisade3.jpg', 'palisade4.jpg', 'palisade5.jpg', 'palisade6.jpg', 'palisade7.jpg'] },
  { id: 5, name: 'Toyota Avanza', type: 'Van', pricePerDay: 550000, image: ['avanza1.webp', 'avanza2.webp', 'avanza3.webp', 'avanza4.webp'] },
  { id: 6, name: 'Mitsubishi Xpander', type: 'Van', pricePerDay: 600000, image: ['xpander1.jpg', 'xpander2.jpg', 'xpander3.jpg'] },
  { id: 7, name: 'Suzuki Ertiga', type: 'Van', pricePerDay: 520000, image: 'xpander1.jpg' },
  { id: 8, name: 'Kia Carnival', type: 'Van', pricePerDay: 900000, image: ['carnivalkia-01.jpg', 'carnivalkia-02.jpg', 'carnivalkia-03.jpg', 'carnivalkia-04.jpg'] },
  { id: 9, name: 'Honda City', type: 'Sedan', pricePerDay: 500000, image: ['city1-a.webp', 'city2-b.webp', 'city3-c.webp', 'city4-d.webp', 'city5-e.webp', 'city6-f.jpg'] },
  { id: 10, name: 'Honda Civic', type: 'Sedan', pricePerDay: 750000, image: ['civic1.avif', 'civic2.avif', 'civic3.avif', 'civic4.avif', 'civic5.avif', 'civic6.jpg'] },
  { id: 11, name: 'Toyota Camry', type: 'Sedan', pricePerDay: 900000, image: 'palisade1.jpg' },
  { id: 12, name: 'Mazda 6', type: 'Sedan', pricePerDay: 950000, image: 'palisade2.jpg' }
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

// Defensive: jika elemen penting tidak ada, catat dan hentikan inisialisasi lebih lanjut
const _missingDOM = (!carGrid || !carSelect || !bookingForm || !pricePerDayEl || !startDateEl || !endDateEl);
if (_missingDOM) {
  console.warn('Beberapa elemen DOM penting tidak ditemukan. Beberapa fitur mungkin dinonaktifkan.');
}

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
    // API may return shape { data: [...] } or { cars: [...] } or an array
    if (Array.isArray(data)) return data;
    if (Array.isArray(data.data)) return data.data;
    if (Array.isArray(data.cars)) return data.cars;
    return [];
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

    // Check if car has multiple images (array) or single image (string)
    let imageHTML = '';

    if (Array.isArray(car.image) && car.image.length > 1) {
      // Multiple images - create slider
      const slidesHTML = car.image.map((img, index) =>
        `<img src="images/${img}" alt="${car.name} ${index + 1}" class="slide ${index === 0 ? 'active' : ''}" loading="lazy">`
      ).join('');

      imageHTML = `
        <div class="slider" data-index="0">
          ${slidesHTML}
          <button class="nav prev" aria-label="Previous image">‹</button>
          <button class="nav next" aria-label="Next image">›</button>
        </div>
      `;
    } else {
      // Single image - simple img tag
      const imgSrc = Array.isArray(car.image) ? car.image[0] : car.image;
      imageHTML = `<img src="images/${imgSrc}" alt="${car.name}" class="card-image" loading="lazy">`;
    }

    article.innerHTML = `
      ${imageHTML}
      <div class="card-body">
        <h3 class="car-name">${car.name}</h3>
        <p class="car-meta"><span>${car.type}</span><span class="price">${fmtIDR.format(car.pricePerDay)}/hari</span></p>
        <button class="card-button" data-car-id="${car.id}" aria-label="Pilih ${car.name}">Pilih Mobil</button>
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
  const cardButtons = document.querySelectorAll('.card-button');
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

  const reserveTrigger = document.getElementById('reserve-btn-trigger');
  const confirmReserve = document.getElementById('confirm-reserve');
  const cancelReserve = document.getElementById('cancel-reserve');
  const reserveModal = document.getElementById('reserve-modal');

  if (reserveTrigger) {
    reserveTrigger.addEventListener('click', handleValidationAndModal);
  }

  if (confirmReserve) {
    confirmReserve.addEventListener('click', function () {
      // Programmatik submit form menggunakan prototype Call untuk menjamin orisinalitas event.
      // Ini memastikan data terkirim meskipun ada interference dari script lain.
      if (bookingForm) {
        HTMLFormElement.prototype.submit.call(bookingForm);
      }
      if (reserveModal) reserveModal.classList.add('hidden');
    });
  }

  if (cancelReserve) {
    cancelReserve.addEventListener('click', function () {
      if (reserveModal) reserveModal.classList.add('hidden');
    });
  }
}

/**
 * Get selected car dari select dropdown
 * @returns {Object|null} Car object atau null
 */
function getSelectedCar() {
  const id = carSelect.value;
  // Menggunakan loose equality (atau eksplisit string conversion) untuk memastikan 
  // ID dari DOM (string) cocok dengan ID dari data (integer).
  return cars.find(c => String(c.id) === String(id)) || null;
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
 * Validasi form dan tampilkan modal konfirmasi jika valid
 */
function handleValidationAndModal() {
  // Reset error messages
  formSuccessEl.textContent = '';
  nameErrorEl.textContent = '';
  dateErrorEl.textContent = '';

  const name = bookingForm.renterName.value.trim();
  const email = bookingForm.renterEmail.value.trim();
  const selected = getSelectedCar();
  const days = calculateDays(startDateEl.value, endDateEl.value);

  // Validasi frontend
  if (!name) {
    nameErrorEl.textContent = 'Nama penyewa wajib diisi.';
    bookingForm.renterName.focus();
    return;
  }

  if (!email || !bookingForm.renterEmail.validity.valid) {
    const errorMsg = !email ? 'Email wajib diisi.' : 'Email tidak valid.';
    nameErrorEl.textContent = ''; // clear other errors
    const existingError = document.getElementById('emailError');
    if (existingError) existingError.remove();

    const emailError = document.createElement('small');
    emailError.className = 'error';
    emailError.id = 'emailError';
    emailError.textContent = errorMsg;
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

  // Jika valid, tampilkan modal
  const reserveModal = document.getElementById('reserve-modal');
  if (reserveModal) {
    reserveModal.classList.remove('hidden');
  } else {
    if (confirm('Apakah Anda yakin ingin melakukan reservasi?')) {
      HTMLFormElement.prototype.submit.call(bookingForm);
    }
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
// Navigation Functions
// ============================================================================

/**
 * Setup smooth scroll navigation dengan animasi klik
 * Hanya untuk link internal (#hash), biarkan link eksternal (.php) berfungsi normal
 */
function setupNavigation() {
  const navLinks = document.querySelectorAll('.nav-link');

  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      const targetId = link.getAttribute('href');

      // ====================================================================
      // PENTING: Hanya intercept link internal (yang dimulai dengan #)
      // Biarkan link eksternal (.php files) berfungsi normal
      // ====================================================================
      if (!targetId || !targetId.startsWith('#')) {
        // Ini adalah link eksternal (login.php, register.php, logout.php, dll)
        // Jangan preventDefault, biarkan browser handle navigasi normal
        return;
      }

      // Untuk link internal (#home, #cars, #booking, #contact), lakukan smooth scroll
      e.preventDefault();

      // Animasi klik
      link.classList.add('clicked');
      setTimeout(() => {
        link.classList.remove('clicked');
      }, 300);

      // Smooth scroll ke target dengan offset untuk sticky header
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        // Hitung tinggi header secara dinamis
        const header = document.querySelector('.app-header');
        const headerHeight = header ? header.offsetHeight : 100;

        // Hitung posisi target dengan offset
        const elementPosition = targetElement.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerHeight - 10; // 10px extra spacing

        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });

        // Update URL hash tanpa trigger scroll
        history.pushState(null, null, targetId);
      }
    });
  });

  // Handle hash pada URL saat page load (jika user refresh dengan hash)
  if (window.location.hash) {
    setTimeout(() => {
      const hash = window.location.hash;
      const targetElement = document.querySelector(hash);
      if (targetElement) {
        const header = document.querySelector('.app-header');
        const headerHeight = header ? header.offsetHeight : 100;
        const elementPosition = targetElement.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerHeight - 10;
        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    }, 100);
  }
}

// ============================================================================
// Initialization
// ============================================================================

/**
 * Initialize aplikasi
 */
async function init() {
  try {
    yearEl.textContent = new Date().getFullYear();

    cars = await fetchCars();
    // Pastikan tampil berdasarkan nama (ascending)
    cars.sort((a, b) => {
      if (!a || !b) return 0;
      const na = String(a.name || '')
      const nb = String(b.name || '')
      return na.localeCompare(nb, 'id', { sensitivity: 'base' });
    });

    renderCars(cars);
    setupFilters();
    setupBookingForm();
    setupNavigation();
    applyFilter('all');
    updatePricing();
  } catch (err) {
    console.error('Inisialisasi aplikasi gagal:', err);
    // Tampilkan pesan ke UI jika memungkinkan
    try {
      const banner = document.createElement('div');
      banner.style.background = '#fee2e2';
      banner.style.color = '#7f1d1d';
      banner.style.padding = '12px';
      banner.style.border = '1px solid #fecaca';
      banner.style.borderRadius = '8px';
      banner.style.margin = '12px 0';
      banner.textContent = 'Terjadi kesalahan saat memuat aplikasi. Buka konsol untuk detail.';
      const main = document.querySelector('main') || document.body;
      main.insertBefore(banner, main.firstChild);
    } catch (e) {
      // ignore UI errors
    }
  }
}

// Start aplikasi saat DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}
