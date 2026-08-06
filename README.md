# 💱 CURRENCY CONVERTER

A lightweight, database-less currency converter built with native PHP, Tailwind CSS, and vanilla JavaScript — live exchange rates via Frankfurter API, swap currencies, conversion history in `localStorage`, and a simple JSON proxy for XAMPP / local apps.

---

## ✨ Features

- 💰 **Amount Input**: Accepts numerical values with a minimum of `0.01`.
- 🌍 **Currency Selectors**: From / To dropdowns for popular currencies (USD, EUR, GBP, MYR, JPY, CAD, AUD, SGD).
- 🔄 **Swap Button**: Instantly swap the selected From and To currencies.
- 📈 **Live Conversion**: Shows converted total plus the exchange rate ratio (e.g. `1 USD = 4.45 MYR`).
- 📜 **Local History**: Stores the last 5 conversions in the browser’s `localStorage` and displays them in a dynamic table.
- ⏳ **Loading & Errors**: Smooth loading spinner while fetching rates; clear alerts for network or input failures.
- 🎨 **Viewport UI**: Modern card layout with Tailwind CSS CDN, centered and fitted to `100vh` without vertical scrolling.
- 🚫 **No Database**: Front-end + PHP proxy only — no MySQL setup required.

---

## 🏗️ Tech Stack

| Category | Technology |
| --- | --- |
| 🖥️ Frontend | HTML / CSS / JavaScript (`index.php`) + Tailwind CSS CDN |
| 🔙 Backend | Native PHP 8+ proxy (`convert.php`) |
| 🗄️ Database | None (history via `localStorage`) |
| 🛰️ Rate Source | [Frankfurter API](https://www.frankfurter.app/) (`https://api.frankfurter.app/latest`) |
| 🔗 Architecture | Single-page UI + JSON proxy endpoint |
| 🛠️ Local Dev | PHP built-in server or XAMPP (Apache) |

---

## 🚀 Quick Start

### 1. Requirements

- PHP 8.0+ (cURL or `allow_url_fopen` enabled)
- No MySQL / MariaDB needed

### 2. Install

1. Place the project under your web root, e.g. `C:\xampp\htdocs\Currency_Converter`

2. Start the PHP built-in server from the project folder:

   ```bash
   cd C:\xampp\htdocs\Currency_Converter
   php -S localhost:8000
   ```

   Or with XAMPP PHP:

   ```bash
   C:\xampp\php\php.exe -S localhost:8000
   ```

3. Open **http://localhost:8000** in your browser.

   If using Apache/XAMPP without the built-in server, open:

   `http://localhost/Currency_Converter/`

---

## 🎬 Project Walkthrough

Click the preview image below to watch the full system walkthrough on Google Drive:

<a href="YOUR_DRIVE_VIDEO_LINK" target="_blank">
  <img src="docs/screenshots/walkthrough-cover.png" width="100%" style="border-radius: 8px; border: 1px solid #e1e4e8;" />
</a>

---

## 🖼️ Project Screenshots

<table border="0">
  <tr>
    <td width="50%">
      <img src="docs/screenshots/01-converter.png" width="100%" style="border-radius: 8px; border: 1px solid #e1e4e8;" />
    </td>
    <td width="50%">
      <img src="docs/screenshots/02-history.png" width="100%" style="border-radius: 8px; border: 1px solid #e1e4e8;" />
    </td>
  </tr>
</table>

---

## 🔌 API

Base: `http://localhost:8000/convert.php`

| Method | Endpoint | Params |
| --- | --- | --- |
| GET | `/convert.php` | `amount` (required, ≥ 0.01), `from` (currency code), `to` (currency code) |

Example:

```text
/convert.php?amount=100&from=USD&to=MYR
```

### Success Response

```json
{
  "amount": 100,
  "from": "USD",
  "to": "MYR",
  "rate": 4.0825,
  "result": 408.25,
  "date": "2026-07-30"
}
```

### Error Response

```json
{
  "error": "Amount must be at least 0.01."
}
```

| HTTP | When |
| --- | --- |
| `400` | Invalid amount or currency code |
| `405` | Non-GET method |
| `502` | Upstream Frankfurter API unreachable / unexpected payload |

---

## 📐 Domain Rules

- Supported currencies: **USD, EUR, GBP, MYR, JPY, CAD, AUD, SGD**.
- Amount must be **≥ 0.01**.
- Same From / To currency returns `rate: 1` without calling the external API.
- `convert.php` proxies Frankfurter (`cURL` preferred, `file_get_contents` fallback) and returns a normalized JSON shape.
- Conversion history is kept **only in the browser** (`localStorage` key `currency_converter_history`), capped at **5** rows.

---

## 📁 Project Structure

```text
Currency_Converter/
├── index.php              # UI entry: Tailwind CDN, form, swap, history, client JS
├── convert.php            # PHP proxy → Frankfurter API → JSON
├── docs/
│   └── screenshots/       # README walkthrough cover + UI screenshots
└── README.md
```

---

## 🧩 Frontend Behaviour

```js
// Convert via proxy
const params = new URLSearchParams({ amount: '100', from: 'USD', to: 'MYR' });
const res = await fetch(`convert.php?${params}`);
const data = await res.json();
// data.result, data.rate, data.from, data.to

// History (browser only)
localStorage.getItem('currency_converter_history'); // last 5 conversions
```

Open `index.php` after the PHP server is running; the page calls `convert.php` for live rates and never talks to Frankfurter directly from the browser.
