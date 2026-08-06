<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Currency Converter</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['"Instrument Serif"', 'Georgia', 'serif'],
            sans: ['"DM Sans"', 'system-ui', 'sans-serif'],
          },
          colors: {
            ink: '#0c1f1a',
            mint: '#1a6b54',
            leaf: '#2d9a78',
            mist: '#e8f5f0',
            sand: '#f7f4ef',
          },
        },
      },
    };
  </script>
  <style>
    html, body { height: 100%; overflow: hidden; }
    body {
      background:
        radial-gradient(ellipse 80% 60% at 10% 20%, rgba(45, 154, 120, 0.18), transparent 50%),
        radial-gradient(ellipse 70% 50% at 90% 80%, rgba(26, 107, 84, 0.12), transparent 45%),
        linear-gradient(160deg, #f7f4ef 0%, #eef6f2 45%, #e4efe9 100%);
    }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    @keyframes pulseSoft {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.55; }
    }
    .anim-in { animation: fadeUp 0.45s ease-out both; }
    .anim-in-delay { animation: fadeUp 0.5s ease-out 0.08s both; }
    .spinner {
      width: 1.1rem; height: 1.1rem;
      border: 2px solid rgba(255,255,255,0.35);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
    }
    .result-pulse { animation: pulseSoft 1.2s ease-in-out; }
    select, input { outline: none; }
    select:focus, input:focus {
      box-shadow: 0 0 0 3px rgba(45, 154, 120, 0.25);
    }
  </style>
</head>
<body class="font-sans text-ink antialiased">
  <div class="h-[100vh] w-full flex items-center justify-center px-4 py-3">
    <div class="w-full max-w-lg anim-in">
      <!-- Brand -->
      <header class="text-center mb-4">
        <p class="font-display text-3xl sm:text-4xl text-ink tracking-tight">Currency Converter</p>
        <p class="text-sm text-ink/55 mt-1">Live rates · no account needed</p>
      </header>

      <!-- Converter card -->
      <section class="bg-white/80 backdrop-blur-sm rounded-2xl border border-ink/8 shadow-[0_8px_40px_-12px_rgba(12,31,26,0.18)] p-5 sm:p-6">
        <form id="convertForm" class="space-y-4" novalidate>
          <!-- Amount -->
          <div>
            <label for="amount" class="block text-xs font-semibold uppercase tracking-wider text-ink/50 mb-1.5">Amount</label>
            <input
              type="number"
              id="amount"
              name="amount"
              min="0.01"
              step="any"
              value="100"
              required
              class="w-full rounded-xl border border-ink/12 bg-sand/60 px-4 py-2.5 text-lg font-medium text-ink transition"
            />
          </div>

          <!-- From / Swap / To -->
          <div class="grid grid-cols-[1fr_auto_1fr] gap-2 items-end">
            <div>
              <label for="fromCurrency" class="block text-xs font-semibold uppercase tracking-wider text-ink/50 mb-1.5">From</label>
              <select id="fromCurrency" class="w-full rounded-xl border border-ink/12 bg-sand/60 px-3 py-2.5 text-sm font-medium text-ink transition appearance-none cursor-pointer">
                <option value="USD" selected>USD — US Dollar</option>
                <option value="EUR">EUR — Euro</option>
                <option value="GBP">GBP — British Pound</option>
                <option value="MYR">MYR — Malaysian Ringgit</option>
                <option value="JPY">JPY — Japanese Yen</option>
                <option value="CAD">CAD — Canadian Dollar</option>
                <option value="AUD">AUD — Australian Dollar</option>
                <option value="SGD">SGD — Singapore Dollar</option>
              </select>
            </div>

            <button
              type="button"
              id="swapBtn"
              title="Swap currencies"
              aria-label="Swap currencies"
              class="mb-0.5 h-10 w-10 flex items-center justify-center rounded-full bg-mist text-mint hover:bg-leaf hover:text-white transition-colors duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
              </svg>
            </button>

            <div>
              <label for="toCurrency" class="block text-xs font-semibold uppercase tracking-wider text-ink/50 mb-1.5">To</label>
              <select id="toCurrency" class="w-full rounded-xl border border-ink/12 bg-sand/60 px-3 py-2.5 text-sm font-medium text-ink transition appearance-none cursor-pointer">
                <option value="USD">USD — US Dollar</option>
                <option value="EUR">EUR — Euro</option>
                <option value="GBP">GBP — British Pound</option>
                <option value="MYR" selected>MYR — Malaysian Ringgit</option>
                <option value="JPY">JPY — Japanese Yen</option>
                <option value="CAD">CAD — Canadian Dollar</option>
                <option value="AUD">AUD — Australian Dollar</option>
                <option value="SGD">SGD — Singapore Dollar</option>
              </select>
            </div>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            id="convertBtn"
            class="w-full flex items-center justify-center gap-2 rounded-xl bg-mint hover:bg-leaf active:scale-[0.99] text-white font-semibold py-2.5 transition-all duration-200"
          >
            <span id="btnLabel">Convert</span>
            <span id="btnSpinner" class="spinner hidden" aria-hidden="true"></span>
          </button>
        </form>

        <!-- Alert -->
        <div id="alert" class="hidden mt-3 rounded-xl px-3.5 py-2.5 text-sm font-medium" role="alert"></div>

        <!-- Result -->
        <div id="resultBox" class="hidden mt-4 pt-4 border-t border-ink/8">
          <p class="text-xs font-semibold uppercase tracking-wider text-ink/45 mb-1">Converted amount</p>
          <p id="resultTotal" class="font-display text-3xl sm:text-4xl text-mint leading-tight"></p>
          <p id="resultRate" class="mt-1 text-sm text-ink/55"></p>
        </div>
      </section>

      <!-- History -->
      <section class="mt-3 anim-in-delay">
        <div class="flex items-center justify-between mb-1.5 px-0.5">
          <h2 class="text-xs font-semibold uppercase tracking-wider text-ink/45">Recent conversions</h2>
          <button type="button" id="clearHistory" class="text-xs text-ink/40 hover:text-mint transition-colors">Clear</button>
        </div>
        <div class="bg-white/70 backdrop-blur-sm rounded-xl border border-ink/8 overflow-hidden max-h-[9.5rem]">
          <table class="w-full text-left text-xs sm:text-sm">
            <thead class="bg-mist/70 text-ink/50 sticky top-0">
              <tr>
                <th class="px-3 py-1.5 font-semibold">From</th>
                <th class="px-3 py-1.5 font-semibold">To</th>
                <th class="px-3 py-1.5 font-semibold text-right">Result</th>
                <th class="px-3 py-1.5 font-semibold text-right hidden sm:table-cell">Rate</th>
              </tr>
            </thead>
            <tbody id="historyBody" class="divide-y divide-ink/6 text-ink/80">
              <tr id="historyEmpty">
                <td colspan="4" class="px-3 py-3 text-center text-ink/35">No conversions yet</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>

  <script>
    const HISTORY_KEY = 'currency_converter_history';
    const MAX_HISTORY = 5;

    const form = document.getElementById('convertForm');
    const amountInput = document.getElementById('amount');
    const fromSelect = document.getElementById('fromCurrency');
    const toSelect = document.getElementById('toCurrency');
    const swapBtn = document.getElementById('swapBtn');
    const convertBtn = document.getElementById('convertBtn');
    const btnLabel = document.getElementById('btnLabel');
    const btnSpinner = document.getElementById('btnSpinner');
    const alertEl = document.getElementById('alert');
    const resultBox = document.getElementById('resultBox');
    const resultTotal = document.getElementById('resultTotal');
    const resultRate = document.getElementById('resultRate');
    const historyBody = document.getElementById('historyBody');
    const clearHistoryBtn = document.getElementById('clearHistory');

    function formatNum(n, decimals = 4) {
      return Number(n).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: decimals,
      });
    }

    function showAlert(message, type = 'error') {
      alertEl.textContent = message;
      alertEl.classList.remove('hidden', 'bg-red-50', 'text-red-700', 'bg-leaf/10', 'text-mint');
      if (type === 'error') {
        alertEl.classList.add('bg-red-50', 'text-red-700');
      } else {
        alertEl.classList.add('bg-leaf/10', 'text-mint');
      }
    }

    function hideAlert() {
      alertEl.classList.add('hidden');
      alertEl.textContent = '';
    }

    function setLoading(loading) {
      convertBtn.disabled = loading;
      convertBtn.classList.toggle('opacity-80', loading);
      btnLabel.classList.toggle('hidden', loading);
      btnSpinner.classList.toggle('hidden', !loading);
    }

    function getHistory() {
      try {
        const raw = localStorage.getItem(HISTORY_KEY);
        return raw ? JSON.parse(raw) : [];
      } catch {
        return [];
      }
    }

    function saveHistory(items) {
      localStorage.setItem(HISTORY_KEY, JSON.stringify(items.slice(0, MAX_HISTORY)));
    }

    function addToHistory(entry) {
      const items = getHistory();
      items.unshift(entry);
      saveHistory(items);
      renderHistory();
    }

    function renderHistory() {
      const items = getHistory();
      historyBody.innerHTML = '';

      if (!items.length) {
        historyBody.innerHTML = `
          <tr id="historyEmpty">
            <td colspan="4" class="px-3 py-3 text-center text-ink/35">No conversions yet</td>
          </tr>`;
        return;
      }

      items.forEach((item) => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-mist/40 transition-colors';
        tr.innerHTML = `
          <td class="px-3 py-1.5 whitespace-nowrap">${formatNum(item.amount, 2)} ${item.from}</td>
          <td class="px-3 py-1.5 whitespace-nowrap">${item.to}</td>
          <td class="px-3 py-1.5 text-right font-medium whitespace-nowrap">${formatNum(item.result)} ${item.to}</td>
          <td class="px-3 py-1.5 text-right text-ink/50 whitespace-nowrap hidden sm:table-cell">1 ${item.from} = ${formatNum(item.rate, 6)}</td>
        `;
        historyBody.appendChild(tr);
      });
    }

    swapBtn.addEventListener('click', () => {
      const tmp = fromSelect.value;
      fromSelect.value = toSelect.value;
      toSelect.value = tmp;
      swapBtn.classList.add('scale-90');
      setTimeout(() => swapBtn.classList.remove('scale-90'), 150);
    });

    clearHistoryBtn.addEventListener('click', () => {
      localStorage.removeItem(HISTORY_KEY);
      renderHistory();
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      hideAlert();

      const amount = parseFloat(amountInput.value);
      const from = fromSelect.value;
      const to = toSelect.value;

      if (Number.isNaN(amount) || amount < 0.01) {
        showAlert('Please enter an amount of at least 0.01.');
        resultBox.classList.add('hidden');
        return;
      }

      setLoading(true);
      resultBox.classList.add('hidden');

      try {
        const params = new URLSearchParams({ amount: String(amount), from, to });
        const res = await fetch(`convert.php?${params.toString()}`);
        const data = await res.json();

        if (!res.ok || data.error) {
          throw new Error(data.error || 'Conversion failed. Please try again.');
        }

        resultTotal.textContent = `${formatNum(data.result)} ${data.to}`;
        resultRate.textContent = `1 ${data.from} = ${formatNum(data.rate, 6)} ${data.to}`;
        resultBox.classList.remove('hidden');
        resultTotal.classList.remove('result-pulse');
        void resultTotal.offsetWidth;
        resultTotal.classList.add('result-pulse');

        addToHistory({
          amount: data.amount,
          from: data.from,
          to: data.to,
          result: data.result,
          rate: data.rate,
          date: data.date,
        });
      } catch (err) {
        const msg = err instanceof TypeError
          ? 'Network error. Check your connection and try again.'
          : (err.message || 'Something went wrong.');
        showAlert(msg);
      } finally {
        setLoading(false);
      }
    });

    renderHistory();
  </script>
</body>
</html>
