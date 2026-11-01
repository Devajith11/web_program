# GEC Wayanad TechFest Hub (FUTURA '26)
### Assignment 1: Data-Driven Web Application (Vanilla HTML5, CSS3, JavaScript)

A modern, responsive, data-driven web application developed for **Government Engineering College Wayanad (GECW)**. The portal implements the **TechFest Hub** domain use case (events, competitions, workshops, schedules, and live updates), utilizing a structured **RO-Crate-compatible linked JSON graph** to dynamically generate presentation without page reloads or external frameworks.

---

## 🎯 Domain Selection: TechFest Hub

- **Institution:** Government Engineering College Wayanad (GECW)
- **Domain:** TechFest Hub (FUTURA '26)
- **Entities Handled:**
  - `Event` (Festival root metadata: theme, dates, venue, contacts)
  - `Competition` (HackTheHills 2026, RoboRumble, CodeSprint, CyberSiege CTF)
  - `Workshop` (Federated AI & Edge Deployments, Cloud Native & Kubernetes)
  - `Talk` (Fireside chats with startup founders, Geospatial AI by ISRO scientists)
  - `Schedule` (Multi-day chronological itinerary slots with venues and speakers)
  - `Update` (Real-time announcements, logistical bulletins, and problem track notices)

---

## 🏛️ System Architecture: Separation of Data from Presentation

```
Web Program/
├── index.html          # Semantic HTML5 Structure (<header>, <nav>, <main>, <section>, <article>, <footer>)
├── css/
│   └── style.css       # External CSS3: Custom properties, Box-Model, CSS Grid, Flexbox, Fluid Clamp (Mobile to 8K)
├── js/
│   └── app.js          # Pure Vanilla JavaScript: Graph indexing, real-time filtering, DOM generation, modal & bookmarks
├── data/
│   └── techfest.json   # RO-Crate compatible linked data graph with heterogeneous entity types
└── README.md           # Technical documentation and deployment instructions
```

### RO-Crate Compatible Data Model
The dataset adheres strictly to the prescribed graph format:
```json
{
  "graph": [
    { "id": "techfest-2026", "type": "Event", ... },
    { "id": "event-001", "type": "Competition", ... },
    { "id": "event-002", "type": "Workshop", ... },
    { "id": "event-003", "type": "Talk", ... },
    { "id": "sched-001", "type": "Schedule", ... },
    { "id": "update-001", "type": "Update", ... }
  ]
}
```
All data presentation is generated on the client side at runtime. `app.js` fetches `data/techfest.json` (with an embedded offline fallback for local `file://` execution where strict browser CORS blocks local file fetching), traversing and indexing nodes by their `@type` / `type`.

---

## ✨ Key Features & Technical Tasks

### 1. Semantic HTML5 Page Structure
- **Landmarks:** Built using semantic elements:
  - `<header>`: Sticky backdrop-blurred navigation, institutional branding, quick bookmark counter.
  - `<nav>`: Accessible navigation links with keyboard focus states.
  - `<main>`: Main content wrapper hosting hero, events, schedule, and live updates sections.
  - `<section>`: Distinct landmark areas (`#overview`, `#events-section`, `#schedule-section`, `#updates-section`, `#rocrate-section`).
  - `<article>`: Self-contained cards for individual events and bulletin updates.
  - `<footer>`: Institutional attribution, academic links, and assignment specifications.
  - `<dialog>`: Native HTML5 dialog modal for detailed event specs.

### 2. CSS3 Box-Model & Responsive Layouts (Mobile to 8K)
- **Box-Model Concept:** Global `box-sizing: border-box`, explicit padding, margins, and border radii.
- **Fluid Typography:** Utilizes CSS `clamp()` (`clamp(1rem, 0.95rem + 0.25vw, 1.125rem)`) scaling seamlessly from 320px mobile viewports up to 8K (7680px) displays without breaking.
- **Layouts:**
  - Modern **CSS Grid** with dynamic column scaling: `repeat(auto-fill, minmax(clamp(290px, 30vw, 380px), 1fr))`.
  - **Flexbox** with flexible wrapping and gap properties.
- **Zero Horizontal Overflow:** Enforced via `overflow-x: hidden`, `width: 100%`, and percentage-based sizing.
- **Theme:** High-contrast dark cyberpunk aesthetic (deep slate, cyan, emerald, purple) with glassmorphism and WCAG AAA compliant text contrast.

### 3. Vanilla JavaScript Functionality
- **Instant Client-Side Search:**
  - Listens to `input` events on `#event-search-input` for instant, non-blocking filtering without page reload.
  - Searches dynamically across event names, short codes, descriptions, categories, venues, faculty coordinators, speakers, and tags.
- **Multi-Faceted Filtering & Sorting:**
  - Category tabs: All Tracks, Competitions, Workshops, Tech Talks.
  - Status filters: Registration Open, Free Entry, Limited Seats.
  - Sorting: Name (A-Z, Z-A), Category.
- **Empty State Feedback:** Displays an empty state banner with a one-click "Reset Filters & Show All" button when no results match.
- **Interactive Schedule:** Day-by-day filterable itinerary (All Days, Day 1, Day 2, Day 3).
- **Live Announcements Feed:** Formatted timestamps and badges.
- **Event Bookmarking:** Persists user-saved events in `localStorage` with a live badge counter and toggle view.
- **Underlying RO-Crate Architecture:** Clean separation where structured JSON feeds the UI dynamically behind the scenes without exposing technical metadata blocks to festival visitors.

---

## 🚀 How to Run Locally

You can test the application directly in any modern browser:

### Option A: Double-Click or Open Directly
Open `index.html` directly in Chrome or Firefox. The built-in fallback dataset ensures immediate rendering even under strict `file://` security settings.

### Option B: Using a Lightweight HTTP Server
To test dynamic `fetch('data/techfest.json')`:
```bash
# Using Python 3
python -m http.server 8000

# Or using Node.js (npx)
npx serve .
```
Then visit `http://localhost:8000` in Google Chrome or Mozilla Firefox.

---

## 🌐 Deployment to GitHub Pages

Follow these simple steps to deploy and obtain your live URL:

1. **Initialize Git Repository:**
   ```bash
   git init
   git add .
   git commit -m "feat: complete Assignment 1 TechFest Hub with RO-Crate data"
   ```

2. **Create a GitHub Repository & Push:**
   ```bash
   git branch -M main
   git remote add origin https://github.com/<YOUR_GITHUB_USERNAME>/<YOUR_REPOSITORY_NAME>.git
   git push -u origin main
   ```

3. **Enable GitHub Pages:**
   - In your GitHub repository, navigate to **Settings** > **Pages** (under "Code and automation").
   - Under **Build and deployment** > **Source**, choose **Deploy from a branch**.
   - Select branch: `main` and folder: `/ (root)`.
   - Click **Save**.
   - Your live URL will be active at:
     `https://<YOUR_GITHUB_USERNAME>.github.io/<YOUR_REPOSITORY_NAME>/`
