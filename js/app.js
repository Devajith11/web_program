/**
 * GEC Wayanad TechFest Hub (FUTURA '26)
 * Assignment 1: Web Programming
 * Vanilla JavaScript Application Logic
 * Adheres strictly to constraints: No external frameworks, libraries, or build tools.
 */

// Fallback dataset in exact RO-Crate graph format in case file:// CORS prevents fetch
const FALLBACK_GRAPH_DATA = {
  "graph": [
    {
      "id": "techfest-2026",
      "type": "Event",
      "name": "FUTURA '26 - GEC Wayanad TechFest",
      "edition": "12th Edition",
      "tagline": "Innovate for the Hills, Code for the Globe",
      "description": "Annual national technology festival of Government Engineering College Wayanad, bringing together coders, roboticists, innovators, and thinkers to tackle real-world challenges through sustainable technology.",
      "startDate": "2026-10-02",
      "endDate": "2026-10-04",
      "venue": "GEC Wayanad Campus, Mananthavady, Kerala",
      "organizer": "Government Engineering College Wayanad (GECW)",
      "contactEmail": "techfest@gecwyd.ac.in",
      "contactPhone": "+91 4935 241215",
      "events": [
        "event-001",
        "event-002",
        "event-003",
        "event-004",
        "event-005",
        "event-006",
        "event-007",
        "event-008"
      ],
      "schedules": [
        "sched-001",
        "sched-002",
        "sched-003",
        "sched-004",
        "sched-005",
        "sched-006",
        "sched-007",
        "sched-008"
      ],
      "updates": [
        "update-001",
        "update-002",
        "update-003",
        "update-004"
      ]
    },
    {
      "id": "event-001",
      "type": "Competition",
      "name": "Hackathon: HackTheHills 2026",
      "shortCode": "HTH-26",
      "description": "A 24-hour team-based programming and rapid prototyping challenge addressing disaster resilience, eco-tourism, and edge computing for rural ecosystems.",
      "category": "Competition",
      "department": "Computer Science & Engineering",
      "teamSize": "2-4 Members",
      "registrationRequired": true,
      "registrationFee": "Free (Shortlisted Teams)",
      "prizePool": "₹45,000",
      "venue": "CSE Advanced Computing Lab & CCC",
      "date": "2026-10-02 to 2026-10-03",
      "time": "10:00 AM (24 Hours)",
      "tags": ["Coding", "AI/ML", "IoT", "Web3", "Hackathon"],
      "coordinator": "Dr. Alice A & Student Lead Arjun K",
      "status": "Registration Open"
    },
    {
      "id": "event-002",
      "type": "Workshop",
      "name": "Introduction to Federated AI & Edge Deployments",
      "shortCode": "WKS-FEDAI",
      "description": "Hands-on, project-driven workshop on training privacy-preserving machine learning models and deploying lightweight inference engines on Raspberry Pi and ESP32 edge hardware.",
      "category": "Workshop",
      "department": "Electronics & Communication Engineering",
      "teamSize": "Individual (1 Person)",
      "duration": "3 Hours",
      "registrationRequired": true,
      "registrationFee": "₹250 per participant",
      "prizePool": "Certification & Hardware Kit Discount",
      "venue": "ECE Seminar Hall, Block B",
      "date": "2026-10-02",
      "time": "02:00 PM - 05:00 PM",
      "tags": ["Machine Learning", "Edge Computing", "Python", "IoT"],
      "instructor": "Prof. Bob B (IIIT Bangalore Alum)",
      "status": "Limited Seats"
    },
    {
      "id": "event-003",
      "type": "Talk",
      "name": "Building Scalable Products from Ideas: Kerala to Silicon Valley",
      "shortCode": "TLK-STARTUP",
      "description": "A fireside conversation and interactive AMA with Y-Combinator alumni and deep-tech startup founders sharing frameworks on product-market fit, technical architecture, and open-source monetization.",
      "category": "Talk",
      "department": "IEDC (Innovation & Entrepreneurship)",
      "teamSize": "Open to All",
      "duration": "1.5 Hours",
      "speaker": "Rohan Menon (Founder & CEO, NeuralStream AI)",
      "registrationRequired": false,
      "registrationFee": "Free Entry",
      "venue": "Main APJ Abdul Kalam Auditorium",
      "date": "2026-10-03",
      "time": "11:30 AM - 01:00 PM",
      "tags": ["Entrepreneurship", "Startups", "Product Management", "AI"],
      "status": "Open for All"
    },
    {
      "id": "event-004",
      "type": "Competition",
      "name": "RoboRumble: All-Terrain Robot Challenge",
      "shortCode": "CMP-ROBO",
      "description": "Combat and obstacle-course navigation arena designed to test custom-built wired and wireless rovers tackling sand pits, inclined bridges, and water hurdles.",
      "category": "Competition",
      "department": "Mechanical Engineering & Robotics Club",
      "teamSize": "2-5 Members",
      "registrationRequired": true,
      "registrationFee": "₹400 per team",
      "prizePool": "₹30,000",
      "venue": "Open Air Amphitheatre Arena",
      "date": "2026-10-03",
      "time": "02:00 PM - 06:00 PM",
      "tags": ["Robotics", "Hardware", "Arduino", "Mechanics"],
      "coordinator": "Prof. Vignesh R & Robotech Society",
      "status": "Registration Open"
    },
    {
      "id": "event-005",
      "type": "Competition",
      "name": "CodeSprint: Speed Coding & Algorithmic Duel",
      "shortCode": "CMP-ALGO",
      "description": "Fast-paced, multi-round competitive programming contest testing dynamic programming, graph theory, mathematical logic, and code optimization against strict time limits.",
      "category": "Competition",
      "department": "Computer Science & Engineering",
      "teamSize": "Individual (1 Person)",
      "registrationRequired": true,
      "registrationFee": "₹100 per participant",
      "prizePool": "₹15,000",
      "venue": "Systems Programming Lab 2",
      "date": "2026-10-04",
      "time": "09:30 AM - 12:30 PM",
      "tags": ["C++", "Python", "Data Structures", "Algorithms", "Competitive Coding"],
      "coordinator": "Prof. Charlie C & FOSS Club",
      "status": "Registration Open"
    },
    {
      "id": "event-006",
      "type": "Workshop",
      "name": "Cloud Native & Kubernetes Microservices Mastery",
      "shortCode": "WKS-K8S",
      "description": "Architecting resilient microservices using Docker containers, Helm charts, Envoy proxies, and automated Kubernetes multi-cluster rollouts on Google Cloud.",
      "category": "Workshop",
      "department": "Computer Science & Engineering",
      "teamSize": "Individual (1 Person)",
      "duration": "4 Hours",
      "registrationRequired": true,
      "registrationFee": "₹300 per participant",
      "prizePool": "Google Cloud Credits & Certificate",
      "venue": "Cloud Computing Center, IT Block",
      "date": "2026-10-03",
      "time": "09:00 AM - 01:00 PM",
      "tags": ["DevOps", "Kubernetes", "Docker", "Cloud", "Microservices"],
      "instructor": "Anjali Nair (Senior SRE, Red Hat)",
      "status": "Limited Seats"
    },
    {
      "id": "event-007",
      "type": "Competition",
      "name": "CyberSiege: 8-Hour CTF (Capture The Flag)",
      "shortCode": "CMP-CTF",
      "description": "Jeopardy-style offensive and defensive cybersecurity tournament spanning binary exploitation, web vulnerabilities, reverse engineering, cryptography, and digital forensics.",
      "category": "Competition",
      "department": "CSE & Cyber Security Cell",
      "teamSize": "1-3 Members",
      "registrationRequired": true,
      "registrationFee": "₹200 per team",
      "prizePool": "₹25,000",
      "venue": "Network Security & Cyber Lab",
      "date": "2026-10-04",
      "time": "10:00 AM - 06:00 PM",
      "tags": ["Cybersecurity", "Reverse Engineering", "Cryptography", "Ethical Hacking"],
      "coordinator": "Prof. Bob B & NullChapter GECW",
      "status": "Registration Open"
    },
    {
      "id": "event-008",
      "type": "Talk",
      "name": "Geospatial AI & Remote Sensing for Mountain Terrain Mitigation",
      "shortCode": "TLK-GEOAI",
      "description": "Case study review on utilizing radar satellite imagery, LiDAR point clouds, and geospatial neural networks to forecast landslide hazards across the Western Ghats region.",
      "category": "Talk",
      "department": "Civil Engineering & Environmental Cell",
      "teamSize": "Open to All",
      "duration": "2 Hours",
      "speaker": "Dr. K. S. Radhakrishnan (Director of Geological Remote Sensing, ISRO)",
      "registrationRequired": false,
      "registrationFee": "Free Entry",
      "venue": "Main APJ Abdul Kalam Auditorium",
      "date": "2026-10-04",
      "time": "02:30 PM - 04:30 PM",
      "tags": ["Geospatial AI", "Disaster Management", "Satellite Imagery", "Environmental Science"],
      "status": "Open for All"
    },
    {
      "id": "sched-001",
      "type": "Schedule",
      "day": "Day 1 - Oct 2, 2026",
      "dayKey": "Day 1",
      "time": "09:00 AM - 10:00 AM",
      "title": "Grand Inauguration Ceremony & Keynote Address",
      "venue": "Main APJ Abdul Kalam Auditorium",
      "category": "Ceremony",
      "eventId": "techfest-2026",
      "speaker": "Principal & Chief Guest"
    },
    {
      "id": "sched-002",
      "type": "Schedule",
      "day": "Day 1 - Oct 2, 2026",
      "dayKey": "Day 1",
      "time": "10:00 AM - Onwards",
      "title": "HackTheHills 2026 Kickoff & Problem Statement Reveal",
      "venue": "CSE Advanced Computing Lab",
      "category": "Competition",
      "eventId": "event-001",
      "speaker": "Jury Panel"
    },
    {
      "id": "sched-003",
      "type": "Schedule",
      "day": "Day 1 - Oct 2, 2026",
      "dayKey": "Day 1",
      "time": "02:00 PM - 05:00 PM",
      "title": "Workshop: Federated AI & Edge Deployments",
      "venue": "ECE Seminar Hall, Block B",
      "category": "Workshop",
      "eventId": "event-002",
      "speaker": "Prof. Bob B"
    },
    {
      "id": "sched-004",
      "type": "Schedule",
      "day": "Day 2 - Oct 3, 2026",
      "dayKey": "Day 2",
      "time": "09:00 AM - 01:00 PM",
      "title": "Workshop: Cloud Native & Kubernetes Microservices Mastery",
      "venue": "Cloud Computing Center, IT Block",
      "category": "Workshop",
      "eventId": "event-006",
      "speaker": "Anjali Nair"
    },
    {
      "id": "sched-005",
      "day": "Day 2 - Oct 3, 2026",
      "dayKey": "Day 2",
      "type": "Schedule",
      "time": "11:30 AM - 01:00 PM",
      "title": "Fireside Talk: Building Scalable Products from Ideas",
      "venue": "Main APJ Abdul Kalam Auditorium",
      "category": "Talk",
      "eventId": "event-003",
      "speaker": "Rohan Menon"
    },
    {
      "id": "sched-006",
      "type": "Schedule",
      "day": "Day 2 - Oct 3, 2026",
      "dayKey": "Day 2",
      "time": "02:00 PM - 06:00 PM",
      "title": "RoboRumble: All-Terrain Robot Arena Battles",
      "venue": "Open Air Amphitheatre Arena",
      "category": "Competition",
      "eventId": "event-004",
      "speaker": "Robotics Club Coordinators"
    },
    {
      "id": "sched-007",
      "type": "Schedule",
      "day": "Day 3 - Oct 4, 2026",
      "dayKey": "Day 3",
      "time": "09:30 AM - 12:30 PM",
      "title": "CodeSprint: Speed Coding & Algorithmic Duel",
      "venue": "Systems Programming Lab 2",
      "category": "Competition",
      "eventId": "event-005",
      "speaker": "Prof. Charlie C"
    },
    {
      "id": "sched-008",
      "type": "Schedule",
      "day": "Day 3 - Oct 4, 2026",
      "dayKey": "Day 3",
      "time": "05:00 PM - 07:00 PM",
      "title": "Valedictory Function, Prize Distribution & Cultural Pro-Show",
      "venue": "College Main Ground",
      "category": "Ceremony",
      "eventId": "techfest-2026",
      "speaker": "Dignitaries and Student Union"
    },
    {
      "id": "update-001",
      "type": "Update",
      "timestamp": "2026-10-01T18:30:00+05:30",
      "title": "HackTheHills 2026 Problem Tracks Announced",
      "tag": "Announcement",
      "badge": "Urgent",
      "content": "Problem statements for Disaster Management, Sustainable Agritech, and Smart Campus Solutions are now released on the portal. Participants should verify their team registration by 9 PM tonight."
    },
    {
      "id": "update-002",
      "type": "Update",
      "timestamp": "2026-10-01T14:15:00+05:30",
      "title": "ECE Seminar Hall Venue Relocation Notice",
      "tag": "Venue Update",
      "badge": "Important",
      "content": "The Federated AI workshop will now be hosted in ECE Seminar Hall Block B (Ground Floor) to accommodate additional hardware testbeds."
    },
    {
      "id": "update-003",
      "type": "Update",
      "timestamp": "2026-09-30T10:00:00+05:30",
      "title": "Free Shuttle Service for Outstation Participants",
      "tag": "Logistics",
      "badge": "Info",
      "content": "Complimentary college bus shuttles will be operating between Mananthavady KSRTC Bus Stand and GEC Wayanad campus every 45 minutes between 7:30 AM and 10:00 PM on all festival days."
    },
    {
      "id": "update-004",
      "type": "Update",
      "timestamp": "2026-09-29T16:45:00+05:30",
      "title": "Spot Registrations for CyberSiege CTF",
      "tag": "Registration",
      "badge": "New",
      "content": "Due to overwhelming demand, 5 additional team slots have been enabled for CyberSiege CTF. Check the registration desk at Network Security Lab."
    }
  ]
};

// Global Application State Container
const AppState = {
  rawGraph: [],
  festival: null,
  events: [],
  schedules: [],
  updates: [],
  savedEventIds: new Set(),
  activeCategory: 'all',
  activeStatus: 'all',
  activeSort: 'default',
  searchQuery: '',
  activeScheduleDay: 'all',
  showingSavedOnly: false
};

// DOM Elements Cache
const DOM = {
  // Festival Hero
  festMainTitle: document.getElementById('fest-main-title'),
  festEditionPill: document.getElementById('fest-edition-pill'),
  festSummary: document.getElementById('fest-summary'),
  festMetaBar: document.getElementById('fest-meta-bar'),
  statEventsCount: document.getElementById('stat-events-count'),
  
  // Async Fetch UI Feedback States
  fetchLoadingState: document.getElementById('fetch-loading-state'),
  fetchErrorState: document.getElementById('fetch-error-state'),
  fetchErrorMessage: document.getElementById('fetch-error-message'),
  retryFetchBtn: document.getElementById('retry-fetch-btn'),

  // Search & Filter
  searchInput: document.getElementById('event-search-input'),
  clearSearchBtn: document.getElementById('clear-search-btn'),
  filterChips: document.querySelectorAll('.filter-chip'),
  statusFilter: document.getElementById('status-filter'),
  sortSelect: document.getElementById('sort-select'),
  visibleCount: document.getElementById('visible-count'),
  totalCount: document.getElementById('total-count'),
  eventsGrid: document.getElementById('events-grid-container'),
  noResultsState: document.getElementById('no-results-state'),
  resetFiltersBtn: document.getElementById('reset-filters-btn'),
  
  // Schedule & Updates
  scheduleTabs: document.getElementById('schedule-tabs'),
  scheduleTimeline: document.getElementById('schedule-timeline'),
  updatesContainer: document.getElementById('updates-container'),
  
  // Saved / Bookmark
  viewSavedBtn: document.getElementById('view-saved-btn'),
  savedCounterBadge: document.getElementById('saved-counter-badge'),

  // Modal Dialog
  modal: document.getElementById('event-detail-modal'),
  modalCloseBtn: document.getElementById('modal-close-btn'),
  modalCategoryBadge: document.getElementById('modal-category-badge'),
  modalStatusBadge: document.getElementById('modal-status-badge'),
  modalTitle: document.getElementById('modal-event-title'),
  modalDesc: document.getElementById('modal-event-desc'),
  modalSpecGrid: document.getElementById('modal-spec-grid'),
  modalTagsContainer: document.getElementById('modal-tags-container'),
  modalBookmarkToggle: document.getElementById('modal-bookmark-toggle'),
  modalRegisterAction: document.getElementById('modal-register-action')
};

/**
 * 1. Initialize Application
 */
async function initApp() {
  loadSavedBookmarks();
  setupEventListeners();
  await loadDataset();
}

/**
 * 2. Asynchronous Fetch from PHP RESTful Endpoint (Assignment 2)
 * Demonstrates: JSON file -> PHP server endpoint -> HTTP Response -> Client Fetch -> DOM rendering
 */
async function loadDataset() {
  // Show UI Loading Feedback
  if (DOM.fetchLoadingState) DOM.fetchLoadingState.classList.remove('hidden');
  if (DOM.fetchErrorState) DOM.fetchErrorState.classList.add('hidden');
  if (DOM.eventsGrid) DOM.eventsGrid.style.opacity = '0.3';

  const PRIMARY_ENDPOINT = 'api/events.php';
  const FALLBACK_STATIC_ENDPOINT = 'data/techfest.json';

  try {
    let response;
    let dataProvider = 'PHP RESTful Endpoint (api/events.php)';

    // Attempt 1: Fetch asynchronously from the PHP RESTful backend
    try {
      response = await fetch(PRIMARY_ENDPOINT, {
        headers: { 'Accept': 'application/json' },
        cache: 'no-cache'
      });

      if (!response.ok) {
        throw new Error(`PHP Endpoint returned HTTP ${response.status} (${response.statusText})`);
      }
    } catch (phpError) {
      console.warn('PHP endpoint unreachable (running on static host or local file origin). Falling back to direct JSON file...', phpError);
      // Attempt 2: Fallback to standalone .json file
      response = await fetch(FALLBACK_STATIC_ENDPOINT, {
        headers: { 'Accept': 'application/json' }
      });
      dataProvider = 'Static JSON Data Provider (data/techfest.json)';

      if (!response.ok) {
        throw new Error(`Fallback HTTP ${response.status} (${response.statusText})`);
      }
    }

    const payload = await response.json();
    console.info(`[TechFest Hub] Data received successfully via ${dataProvider}:`, payload);

    // Hide UI Loading State
    if (DOM.fetchLoadingState) DOM.fetchLoadingState.classList.add('hidden');
    if (DOM.eventsGrid) DOM.eventsGrid.style.opacity = '1';

    processGraphData(payload);

  } catch (err) {
    console.error('[TechFest Hub] Asynchronous fetch request failed:', err);

    // Protocol check: if local file://, use embedded data, otherwise display Error UI state
    if (typeof FALLBACK_GRAPH_DATA !== 'undefined' && window.location.protocol === 'file:') {
      console.warn('Operating via local file:// protocol. Ingesting embedded RO-Crate graph dataset.');
      if (DOM.fetchLoadingState) DOM.fetchLoadingState.classList.add('hidden');
      if (DOM.eventsGrid) DOM.eventsGrid.style.opacity = '1';
      processGraphData(FALLBACK_GRAPH_DATA);
    } else {
      // Display UI Failed Fetch Request Feedback
      if (DOM.fetchLoadingState) DOM.fetchLoadingState.classList.add('hidden');
      if (DOM.fetchErrorState) {
        DOM.fetchErrorState.classList.remove('hidden');
        if (DOM.fetchErrorMessage) {
          DOM.fetchErrorMessage.textContent = `Server Connection Failure: ${err.message}. Ensure the PHP server (php -S localhost:8000) is running and accessible.`;
        }
      }
    }
  }
}

/**
 * 3. Process & Index the RO-Crate Graph Model
 */
function processGraphData(jsonData) {
  if (!jsonData || !Array.isArray(jsonData.graph)) {
    console.error('Invalid RO-Crate data format: expected root "graph" array.');
    return;
  }

  AppState.rawGraph = jsonData.graph;

  // Separate entities by 'type' within the heterogeneous graph array
  AppState.festival = jsonData.graph.find(item => item.id === 'techfest-2026' || item.type === 'Event') || null;
  
  AppState.events = jsonData.graph.filter(item => 
    item.type === 'Competition' || item.type === 'Workshop' || item.type === 'Talk'
  );

  AppState.schedules = jsonData.graph.filter(item => item.type === 'Schedule');
  AppState.updates = jsonData.graph.filter(item => item.type === 'Update');

  // Render all UI components
  renderFestivalMetadata();
  renderEvents();
  renderSchedule();
  renderUpdates();
}

/**
 * 4. Render Festival Hero Metadata
 */
function renderFestivalMetadata() {
  if (!AppState.festival) return;

  const fest = AppState.festival;
  if (DOM.festMainTitle) DOM.festMainTitle.textContent = fest.name || "GEC Wayanad TechFest";
  if (DOM.festEditionPill && fest.edition) {
    DOM.festEditionPill.textContent = `${fest.edition} • National Level Festival`;
  }
  if (DOM.festSummary) DOM.festSummary.textContent = fest.description;
  if (DOM.statEventsCount) DOM.statEventsCount.textContent = AppState.events.length;

  if (DOM.festMetaBar) {
    DOM.festMetaBar.innerHTML = `
      <div class="meta-item">
        <span class="meta-label">Dates</span>
        <span class="meta-val">${fest.startDate} to ${fest.endDate}</span>
      </div>
      <div class="meta-item">
        <span class="meta-label">Venue</span>
        <span class="meta-val">${fest.venue}</span>
      </div>
      <div class="meta-item">
        <span class="meta-label">Organizer</span>
        <span class="meta-val">${fest.organizer}</span>
      </div>
      <div class="meta-item">
        <span class="meta-label">Official Contact</span>
        <span class="meta-val">${fest.contactEmail}</span>
      </div>
    `;
  }
}

/**
 * 5. Instant Client-Side Search and Filtering Logic
 */
function getFilteredEvents() {
  const query = AppState.searchQuery.toLowerCase().trim();

  let filtered = AppState.events.filter(event => {
    // Category Filter
    if (AppState.activeCategory !== 'all' && event.category !== AppState.activeCategory) {
      return false;
    }

    // Status Filter
    if (AppState.activeStatus !== 'all') {
      const statusLower = (event.status || '').toLowerCase();
      const feeLower = (event.registrationFee || '').toLowerCase();
      
      if (AppState.activeStatus === 'open' && !statusLower.includes('open')) return false;
      if (AppState.activeStatus === 'free' && !feeLower.includes('free') && event.registrationRequired !== false) return false;
      if (AppState.activeStatus === 'limited' && !statusLower.includes('limited')) return false;
    }

    // Bookmarked Only Filter
    if (AppState.showingSavedOnly && !AppState.savedEventIds.has(event.id)) {
      return false;
    }

    // Instant Text Search across multiple entity attributes
    if (query) {
      const tagsString = Array.isArray(event.tags) ? event.tags.join(' ') : '';
      const searchableBlob = [
        event.name,
        event.shortCode,
        event.description,
        event.category,
        event.department,
        event.venue,
        event.coordinator,
        event.instructor,
        event.speaker,
        tagsString
      ].filter(Boolean).join(' ').toLowerCase();

      if (!searchableBlob.includes(query)) {
        return false;
      }
    }

    return true;
  });

  // Sorting
  if (AppState.activeSort === 'name-asc') {
    filtered.sort((a, b) => a.name.localeCompare(b.name));
  } else if (AppState.activeSort === 'name-desc') {
    filtered.sort((a, b) => b.name.localeCompare(a.name));
  } else if (AppState.activeSort === 'category') {
    filtered.sort((a, b) => a.category.localeCompare(b.category));
  }

  return filtered;
}

/**
 * 6. Render Dynamic Events Grid
 */
function renderEvents() {
  const filteredEvents = getFilteredEvents();

  // Update counters
  if (DOM.visibleCount) DOM.visibleCount.textContent = filteredEvents.length;
  if (DOM.totalCount) DOM.totalCount.textContent = AppState.events.length;

  // Toggle No-Results State
  if (filteredEvents.length === 0) {
    DOM.eventsGrid.innerHTML = '';
    DOM.noResultsState.classList.remove('hidden');
    return;
  } else {
    DOM.noResultsState.classList.add('hidden');
  }

  // Generate HTML for each event <article>
  const cardsHtml = filteredEvents.map(event => {
    const isBookmarked = AppState.savedEventIds.has(event.id);
    const tagsHtml = (event.tags || [])
      .slice(0, 4)
      .map(tag => `<span class="tag-pill-mini">${escapeHTML(tag)}</span>`)
      .join('');

    const coordinatorOrSpeaker = event.speaker || event.instructor || event.coordinator || 'Department Faculty';
    const prizeOrFee = event.prizePool ? `Prize: ${event.prizePool}` : (event.registrationFee || 'Free');

    let statusClass = '';
    if ((event.status || '').toLowerCase().includes('limited')) {
      statusClass = 'limited';
    } else if ((event.status || '').toLowerCase().includes('closed')) {
      statusClass = 'closed';
    }

    return `
      <article class="event-card" data-event-id="${escapeHTML(event.id)}">
        <div>
          <div class="event-card-header">
            <span class="event-badge" data-type="${escapeHTML(event.category)}">${escapeHTML(event.category)}</span>
            <button 
              type="button" 
              class="bookmark-icon-btn ${isBookmarked ? 'active' : ''}" 
              data-bookmark-id="${escapeHTML(event.id)}" 
              title="${isBookmarked ? 'Remove from Saved' : 'Save Event'}"
              aria-label="${isBookmarked ? 'Remove' : 'Save'} ${escapeHTML(event.name)}"
            >
              ★
            </button>
          </div>

          <h3 class="event-title">${escapeHTML(event.name)}</h3>
          <p class="event-desc">${escapeHTML(event.description)}</p>

          <div class="event-meta-list">
            <div class="event-meta-row">
              <span class="meta-icon" aria-hidden="true">📍</span>
              <span>${escapeHTML(event.venue || 'GECW Campus')}</span>
            </div>
            <div class="event-meta-row">
              <span class="meta-icon" aria-hidden="true">⏱</span>
              <span>${escapeHTML(event.date || 'Festival Dates')} (${escapeHTML(event.time || event.duration || 'Full Day')})</span>
            </div>
            <div class="event-meta-row">
              <span class="meta-icon" aria-hidden="true">👤</span>
              <span>${escapeHTML(coordinatorOrSpeaker)}</span>
            </div>
          </div>

          <div class="event-tags-list" aria-label="Tags">
            ${tagsHtml}
          </div>
        </div>

        <div class="event-card-footer">
          <div class="status-indicator ${statusClass}">
            <span class="live-dot" style="width:6px;height:6px;" aria-hidden="true"></span>
            <span>${escapeHTML(event.status || 'Active')}</span>
          </div>
          <button type="button" class="btn btn-secondary btn-details" data-open-modal="${escapeHTML(event.id)}">
            View Details
          </button>
        </div>
      </article>
    `;
  }).join('');

  DOM.eventsGrid.innerHTML = cardsHtml;
}

/**
 * 7. Render Festival Schedule
 */
function renderSchedule() {
  if (!DOM.scheduleTimeline) return;

  let schedules = AppState.schedules;
  if (AppState.activeScheduleDay !== 'all') {
    schedules = schedules.filter(s => s.dayKey === AppState.activeScheduleDay || (s.day && s.day.includes(AppState.activeScheduleDay)));
  }

  if (schedules.length === 0) {
    DOM.scheduleTimeline.innerHTML = `
      <div class="no-results-card">
        <p>No itinerary items scheduled for this selection.</p>
      </div>
    `;
    return;
  }

  DOM.scheduleTimeline.innerHTML = schedules.map(item => `
    <div class="timeline-item">
      <div class="timeline-time-block">
        <span class="timeline-day">${escapeHTML(item.day || 'Day Event')}</span>
        <span class="timeline-time">${escapeHTML(item.time || '')}</span>
      </div>
      <div>
        <h3 class="timeline-body-title">${escapeHTML(item.title)}</h3>
        <div class="timeline-meta-wrap">
          <span>📍 ${escapeHTML(item.venue || 'GECW')}</span>
          ${item.speaker ? `<span>🎙 ${escapeHTML(item.speaker)}</span>` : ''}
        </div>
      </div>
      <span class="timeline-badge">${escapeHTML(item.category || 'Event')}</span>
    </div>
  `).join('');
}

/**
 * 8. Render Real-Time Announcements / Updates
 */
function renderUpdates() {
  if (!DOM.updatesContainer) return;

  DOM.updatesContainer.innerHTML = AppState.updates.map(update => {
    let dateStr = '';
    try {
      const d = new Date(update.timestamp);
      dateStr = d.toLocaleDateString('en-IN', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    } catch (e) {
      dateStr = update.timestamp;
    }

    return `
      <article class="update-card">
        <div>
          <div class="update-card-header">
            <span class="update-tag-pill">${escapeHTML(update.tag || 'Notice')}</span>
            <span class="update-time">${escapeHTML(dateStr)}</span>
          </div>
          <h3 class="update-title">${escapeHTML(update.title)}</h3>
          <p class="update-content">${escapeHTML(update.content)}</p>
        </div>
      </article>
    `;
  }).join('');
}

/**
 * 9. Event Details Modal Dialog Logic
 */
let currentlyViewedEvent = null;

function openEventModal(eventId) {
  const event = AppState.events.find(e => e.id === eventId);
  if (!event || !DOM.modal) return;

  currentlyViewedEvent = event;

  DOM.modalCategoryBadge.textContent = event.category;
  DOM.modalCategoryBadge.setAttribute('data-type', event.category);
  DOM.modalStatusBadge.textContent = event.status || 'Active';
  DOM.modalTitle.textContent = event.name;
  DOM.modalDesc.textContent = event.description;

  const coordinatorOrSpeaker = event.speaker || event.instructor || event.coordinator || 'Department Faculty';
  const feeOrPool = event.prizePool ? `Prize Pool: ${event.prizePool}` : `Fee: ${event.registrationFee || 'Free'}`;

  DOM.modalSpecGrid.innerHTML = `
    <div class="modal-spec-item">
      <span class="modal-spec-key">Department / Host</span>
      <span class="modal-spec-val">${escapeHTML(event.department || 'GEC Wayanad')}</span>
    </div>
    <div class="modal-spec-item">
      <span class="modal-spec-key">Participation</span>
      <span class="modal-spec-val">${escapeHTML(event.teamSize || 'Individual')}</span>
    </div>
    <div class="modal-spec-item">
      <span class="modal-spec-key">Date &amp; Time</span>
      <span class="modal-spec-val">${escapeHTML(event.date || 'Oct 2-4')} • ${escapeHTML(event.time || event.duration || '')}</span>
    </div>
    <div class="modal-spec-item">
      <span class="modal-spec-key">Venue</span>
      <span class="modal-spec-val">${escapeHTML(event.venue || 'GECW Campus')}</span>
    </div>
    <div class="modal-spec-item">
      <span class="modal-spec-key">Lead / Speaker</span>
      <span class="modal-spec-val">${escapeHTML(coordinatorOrSpeaker)}</span>
    </div>
    <div class="modal-spec-item">
      <span class="modal-spec-key">Prizes / Registration</span>
      <span class="modal-spec-val">${escapeHTML(feeOrPool)}</span>
    </div>
  `;

  if (DOM.modalTagsContainer) {
    DOM.modalTagsContainer.innerHTML = (event.tags || [])
      .map(t => `<span class="tag-pill-mini">${escapeHTML(t)}</span>`)
      .join('');
  }

  updateModalBookmarkState();

  if (typeof DOM.modal.showModal === 'function') {
    DOM.modal.showModal();
  } else {
    DOM.modal.setAttribute('open', 'true');
  }
}

function closeEventModal() {
  if (!DOM.modal) return;
  if (typeof DOM.modal.close === 'function') {
    DOM.modal.close();
  } else {
    DOM.modal.removeAttribute('open');
  }
  currentlyViewedEvent = null;
}

function updateModalBookmarkState() {
  if (!currentlyViewedEvent || !DOM.modalBookmarkToggle) return;
  const isBookmarked = AppState.savedEventIds.has(currentlyViewedEvent.id);
  DOM.modalBookmarkToggle.innerHTML = isBookmarked 
    ? `<span class="btn-star-icon" style="color:var(--accent-amber)">★</span> Saved to Bookmarks`
    : `<span class="btn-star-icon">☆</span> Bookmark Event`;
}

/**
 * 10. Bookmark & Local Storage Management
 */
function loadSavedBookmarks() {
  try {
    const raw = localStorage.getItem('gecw_techfest_saved');
    if (raw) {
      const arr = JSON.parse(raw);
      AppState.savedEventIds = new Set(arr);
    }
  } catch (e) {
    console.warn('LocalStorage unavailable or restricted.', e);
  }
  updateSavedBadgeCount();
}

function toggleBookmark(eventId) {
  if (AppState.savedEventIds.has(eventId)) {
    AppState.savedEventIds.delete(eventId);
  } else {
    AppState.savedEventIds.add(eventId);
  }

  try {
    localStorage.setItem('gecw_techfest_saved', JSON.stringify(Array.from(AppState.savedEventIds)));
  } catch (e) {
    console.warn('Could not persist to LocalStorage.', e);
  }

  updateSavedBadgeCount();
  renderEvents();
  updateModalBookmarkState();
}

function updateSavedBadgeCount() {
  if (DOM.savedCounterBadge) {
    DOM.savedCounterBadge.textContent = AppState.savedEventIds.size;
  }
}

/**
 * 11. Setup UI Event Listeners
 */
function setupEventListeners() {
  // Real-Time Instant Search Input
  DOM.searchInput.addEventListener('input', (e) => {
    AppState.searchQuery = e.target.value;
    if (AppState.searchQuery.length > 0) {
      DOM.clearSearchBtn.classList.remove('hidden');
    } else {
      DOM.clearSearchBtn.classList.add('hidden');
    }
    renderEvents();
  });

  // Clear Search Button
  DOM.clearSearchBtn.addEventListener('click', () => {
    DOM.searchInput.value = '';
    AppState.searchQuery = '';
    DOM.clearSearchBtn.classList.add('hidden');
    DOM.searchInput.focus();
    renderEvents();
  });

  // Category Filter Chips
  DOM.filterChips.forEach(chip => {
    chip.addEventListener('click', () => {
      DOM.filterChips.forEach(c => {
        c.classList.remove('active');
        c.setAttribute('aria-selected', 'false');
      });
      chip.classList.add('active');
      chip.setAttribute('aria-selected', 'true');
      AppState.activeCategory = chip.dataset.category || 'all';
      renderEvents();
    });
  });

  // Status Filter Select
  DOM.statusFilter.addEventListener('change', (e) => {
    AppState.activeStatus = e.target.value;
    renderEvents();
  });

  // Sort Order Select
  DOM.sortSelect.addEventListener('change', (e) => {
    AppState.activeSort = e.target.value;
    renderEvents();
  });

  // Reset Filters Button
  DOM.resetFiltersBtn.addEventListener('click', () => {
    DOM.searchInput.value = '';
    AppState.searchQuery = '';
    DOM.clearSearchBtn.classList.add('hidden');
    AppState.activeCategory = 'all';
    DOM.filterChips.forEach(c => {
      if (c.dataset.category === 'all') {
        c.classList.add('active');
        c.setAttribute('aria-selected', 'true');
      } else {
        c.classList.remove('active');
        c.setAttribute('aria-selected', 'false');
      }
    });
    DOM.statusFilter.value = 'all';
    AppState.activeStatus = 'all';
    DOM.sortSelect.value = 'default';
    AppState.activeSort = 'default';
    AppState.showingSavedOnly = false;
    DOM.viewSavedBtn.classList.remove('active');
    renderEvents();
  });

  // Saved / Bookmarks Filter Toggle
  DOM.viewSavedBtn.addEventListener('click', () => {
    AppState.showingSavedOnly = !AppState.showingSavedOnly;
    DOM.viewSavedBtn.classList.toggle('active', AppState.showingSavedOnly);
    renderEvents();
  });

  // Event Delegation for Grid (View Details & Bookmark)
  DOM.eventsGrid.addEventListener('click', (e) => {
    const detailsBtn = e.target.closest('[data-open-modal]');
    if (detailsBtn) {
      openEventModal(detailsBtn.dataset.openModal);
      return;
    }

    const bookmarkBtn = e.target.closest('[data-bookmark-id]');
    if (bookmarkBtn) {
      toggleBookmark(bookmarkBtn.dataset.bookmarkId);
      return;
    }
  });

  // Schedule Day Tabs
  if (DOM.scheduleTabs) {
    DOM.scheduleTabs.addEventListener('click', (e) => {
      const tab = e.target.closest('.day-tab-btn');
      if (!tab) return;
      DOM.scheduleTabs.querySelectorAll('.day-tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
      AppState.activeScheduleDay = tab.dataset.day || 'all';
      renderSchedule();
    });
  }

  // Retry Connection Button on Failed Fetch Requests
  if (DOM.retryFetchBtn) {
    DOM.retryFetchBtn.addEventListener('click', () => {
      loadDataset();
    });
  }

  // Modal Dialog Actions
  DOM.modalCloseBtn.addEventListener('click', closeEventModal);

  DOM.modal.addEventListener('click', (e) => {
    // Backdrop click detection
    const rect = DOM.modal.getBoundingClientRect();
    const isInDialog = (
      rect.top <= e.clientY &&
      e.clientY <= rect.top + rect.height &&
      rect.left <= e.clientX &&
      e.clientX <= rect.left + rect.width
    );
    if (!isInDialog) {
      closeEventModal();
    }
  });

  DOM.modalBookmarkToggle.addEventListener('click', () => {
    if (currentlyViewedEvent) {
      toggleBookmark(currentlyViewedEvent.id);
    }
  });

  DOM.modalRegisterAction.addEventListener('click', () => {
    if (currentlyViewedEvent) {
      const name = currentlyViewedEvent.name;
      alert(`Registration Portal: You are registering for "${name}". In a production environment, this integrates with the GECW RO-Crate Student Registry.`);
    }
  });
}

/**
 * Utility: HTML Escape for XSS prevention
 */
function escapeHTML(str) {
  if (typeof str !== 'string') return String(str || '');
  return str.replace(/[&<>'"]/g, 
    tag => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      "'": '&#39;',
      '"': '&quot;'
    }[tag] || tag)
  );
}

// Start application when DOM is ready
document.addEventListener('DOMContentLoaded', initApp);
