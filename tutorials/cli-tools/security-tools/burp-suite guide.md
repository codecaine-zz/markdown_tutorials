# 🛡️ Complete **Burp Suite** Setup & Quick‑Start Guide for **Apple Silicon (ARM Mac)** using **Homebrew**

> This guide assumes a fresh macOS 13+ (Monterey, Ventura, Sonoma, …) on an M‑series chip.  
> All commands are meant for the **Terminal** (`⌘ Space → Terminal`).  
> If you already have Homebrew, you can skip the *Homebrew‑install* section.

---  

## Table of Contents
| # | Section |
|---|---------|
| 0 | Quick‑Start Checklist |
| 1 | Prerequisites (Homebrew, JDK, macOS permissions) |
| 2 | Install Burp Suite (Community edition) via Homebrew Cask |
| 3 | Launch Burp Suite for the first time |
| 4 | Configure Burp Suite’s proxy (localhost 8080) |
| 5 | Install Burp’s CA certificate into macOS Keychain (trusted for HTTPS) |
| 6 | Verify the proxy works with a browser and with `curl` / `wget` |
| 7 | Optional: Install Burp Suite **Professional** (license file) |
| 8 | Adding & managing extensions (BApp Store, user‑installed) |
| 9 | Basic workflow – intercepting, replaying, and scanning |
| 10 | Common pitfalls on Apple Silicon & quick fixes |
| 11 | Handy keyboard shortcuts inside Burp Suite |
| 12 | Further learning & official references |

---  

<a name="quick-start"></a>
## 0️⃣ Quick‑Start Checklist  

| ✅ | Item |
|----|------|
| ✅ | macOS 13+ on Apple Silicon |
| ✅ | Homebrew installed (`/opt/homebrew`) |
| ✅ | Java 17 (bundled with Burp) – no extra JDK needed |
| ✅ | Terminal access to `brew`, `open`, `curl` |
| ✅ | Administrator password (for installing the CA cert) |
| ✅ | (Optional) Burp Suite Professional license file (`burp.license`) |

If any item is missing, read the relevant section below.

---  

<a name="prereqs"></a>
## 1️⃣ Prerequisites  

### 1.1 Homebrew (if not already present)

```bash
# Install Homebrew (Apple‑Silicon puts it under /opt/homebrew)
 /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

*Verify:*  

```bash
brew --version
# Expected: Homebrew 4.x
```

### 1.2 Java runtime  

Burp Suite ships its own bundled **OpenJDK 17** (a universal binary). You **do not** need to install a separate JDK.  
If you ever need the system JDK (e.g. for custom scripts) you can run:

```bash
brew install openjdk@17   # optional, not required for Burp itself
```

### 1.3 macOS Permissions you’ll need later  

| Permission | Why it’s needed | How to enable (once) |
|------------|----------------|----------------------|
| **Accessibility** | Mouse/keyboard automation (if you use Burp’s “Intruder” or “Repeater” UI automation) | *System Settings → Privacy & Security → Accessibility → + → add Terminal (or the Burp app)* |
| **Full Disk Access** (optional) | Reading/writing to files outside your home folder (e.g. custom payload files) | *System Settings → Privacy & Security → Full Disk Access → + → add Terminal or the Burp.app* |
| **Network** (default) | Burp listens on `127.0.0.1:8080` and needs inbound connections from the same machine | No extra step; macOS allows loopback by default. |

---  

<a name="install"></a>
## 2️⃣ Install Burp Suite (Community Edition) via Homebrew Cask  

```bash
brew install --cask burp-suite
```

*What Homebrew does:*  

* Downloads the official **burp‑suite‑community‑2025.x.dmg** from PortSwigger.  
* Places the app in `/Applications/Burp Suite.app`.  
* Registers it with LaunchServices, so you can open it with `open -a "Burp Suite"`.

**Verify the install**

```bash
ls -l /Applications/Burp\ Suite.app
# Should show a folder with a size of ~300 MB (the bundled JRE is inside)
```

> **Note:** There are also casks for the Professional edition (`burp-suite-professional`) and early‑adopter builds. Replace `burp-suite` with the appropriate name if you have a license.

---  

<a name="launch"></a>
## 3️⃣ Launch Burp Suite for the First Time  

```bash
# Option 1 – from Finder:
open -a "Burp Suite"

# Option 2 – from Terminal (useful for debugging output):
open -a "Burp Suite" --args -console
```

*The `-console` flag opens a Java console window where you can see startup logs (helpful for troubleshooting).*

When Burp starts you’ll see the classic **Burp Suite** window with tabs: **Target**, **Proxy**, **Intruder**, **Repeater**, **Scanner**, **Extensions**, **Decoder**, **Comparer**, **Logger**.

### First‑time UI steps  

1. **Accept the license agreement** (Community edition is free).  
2. **Choose a workspace** (default: `~/BurpSuiteWorkspace`).  
3. **Do not click “Start Burp Suite automatically on login”** unless you want it always listening.  

---  

<a name="proxy"></a>
## 4️⃣ Configure Burp Suite’s Proxy  

Burp’s default proxy listener is **127.0.0.1:8080** (HTTP, **not** HTTPS).  

### 4.1 Verify the listener is active  

*Inside Burp → “Proxy” → “Options” → “Proxy Listeners”*  
You should see a line:

```
127.0.0.1:8080  (Running)  Bind to address: 127.0.0.1
```

If it’s not running, click **“Edit…” → “Bind to port 8080” → “OK”.**  

### 4.2 Set your browser (Safari/Chrome/Firefox) to use the proxy  

#### Safari  

1. **System Settings → Network → Wi‑Fi (or Ethernet) → Details… → Proxies**  
2. Check **Web Proxy (HTTP)** and **Secure Web Proxy (HTTPS)**, set **127.0.0.1** and **8080** for both.  
3. Click **OK → Apply**.

#### Chrome (uses system proxy, so same as Safari)  

#### Firefox (independent)  

1. Preferences → General → Network Settings → **Manual proxy configuration**.  
2. HTTP Proxy: `127.0.0.1` Port: `8080`  
3. Check **“Use this proxy server for all protocols”**.  
4. **OK**.

#### Quick test  

```bash
curl -I http://example.com
# If the proxy is active you’ll see Burp’s “Intercepted request” window pop up.
```

If you prefer not to intercept all traffic, you can enable **“Intercept is off”** in the **Proxy → Intercept** tab.

---  

<a name="cert"></a>
## 5️⃣ Install Burp’s CA Certificate (HTTPS traffic decryption)

Burp can’t read encrypted HTTPS payloads until you trust its *self‑signed* CA certificate.

### 5.1 Export the certificate from Burp  

1. In Burp go to **Proxy → Options → Intercept → “Import / export CA certificate”** → **“Export certificate in DER format”**.  
2. Save the file as `burp_ca.der` on your Desktop (or any location).

### 5.2 Add the certificate to the macOS **System** keychain  

```bash
# 1️⃣ Move the file where we can easily reference it
mv ~/Desktop/burp_ca.der /tmp/burp_ca.der

# 2️⃣ Add to System keychain (requires admin password)
sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain /tmp/burp_ca.der
```

*Explanation of flags*:

- `-d` – trust for “Always Trust” in the GUI.  
- `-r trustRoot` – treat as a root CA.  
- `-k` – target keychain.

### 5.3 Verify it worked  

Open **Keychain Access** (`⌘ Space → Keychain Access`) → **System** → locate **PortSwigger CA** → double‑click → ensure **“When using this certificate: Always Trust”** for **Secure Socket Layer (SSL)**.

Now HTTPS requests routed through Burp will be decrypted and appear in the **HTTP history** tab.

---  

<a name="verify"></a>
## 6️⃣ Verify the Proxy Works (Browser + CLI)

### 6.1 Browser test  

1. Open Safari (or Chrome).  
2. Navigate to `https://example.com`.  
3. In Burp → **Proxy → HTTP history** you should see the GET request and the response (including HTML).  

If you only see the request and **“SSL handshake failed”**, double‑check the CA certificate (step 5) and that the browser is actually using the proxy (system proxy settings).

### 6.2 `curl` test  

```bash
curl -x http://127.0.0.1:8080 -I https://httpbin.org/get
# Burp will log the request; you’ll see the raw headers in the console.
```

*Tip:* Use `-k` with curl **only** if you haven’t installed the CA yet (it disables certificate verification).

---  

<a name="pro"></a>
## 7️⃣ Optional – Install Burp Suite **Professional** (license file)

If you have a **Burp Suite Professional** license (a `.license` file), the steps are identical except for the cask name:

```bash
brew install --cask burp-suite-professional
```

After launching the app the first time:

1. **File → License…** → “Import License” → select your `burp.license`.  
2. Restart Burp to activate the professional features (Scanner, Intruder enhancements, etc.).

*If you bought a license directly from PortSwigger, you can also download the Professional DMG from the portal and drag‑drop it into `/Applications` – Homebrew just automates that.*

---  

<a name="extensions"></a>
## 8️⃣ Adding & Managing Extensions  

Burp’s power comes from **extensions** (Java, Python Jython, Ruby JRuby, or native).  

### 8.1 BApp Store (built‑in)  

1. Open **Extensions → BApp Store**.  
2. Browse categories (e.g., “Active Scan Checks”, “Passive Scan Checks”, “Decoders”).  
3. Click **“Install”** → the extension appears in the **Extensions → Loaded** tab.

### 8.2 Installing a custom extension (JAR / Python script)

```bash
# Example: install a community Python script
open -a "Burp Suite"      # make sure Burp is running
```

In Burp:  

1. **Extensions → Add**  
2. **Extension type:** choose **Python** (requires Jython) or **Java**.  
3. **File:** locate the `.py` or `.jar` you downloaded.  
4. **Load** – errors (if any) appear in the **Extensions → Errors** tab.

> **Jython** is bundled with Burp, so Python‑based extensions work out‑of‑the‑box. For **Ruby** extensions you may need to install JRuby manually.

---  

<a name="workflow"></a>
## 9️⃣ Basic Burp Suite Workflow  

| Step | What to do | Where in UI |
|------|------------|-------------|
| **1. Intercept** | Turn **Intercept is on** (Proxy → Intercept). Browse a target site; capture the request, modify headers or parameters, then **Forward**. | Proxy → Intercept |
| **2. HTTP history** | All captured traffic appears here for review, filtering, and export. | Proxy → HTTP history |
| **3. Repeater** | Right‑click a request → **Send to Repeater**. Modify and resend arbitrarily many times, view raw response. | Right‑click → Send to Repeater |
| **4. Intruder** | Automate fuzzing of a single parameter (payload list, cluster bomb, pitchfork). | Send request → Intruder → Positions |
| **5. Scanner** (Pro only) | Run **Active** or **Passive** scans on a target host to discover vulnerabilities. | Target → Site map → right‑click → Scan |
| **6. Decoder** | Decode/encode data (Base64, URL, HTML, etc.) | Decoder tab |
| **7. Comparer** | Compare two requests/responses side‑by‑side (useful for diffing). | Comparer tab |
| **8. Logger** | Persistent logging of all traffic (file output). | Logger tab → Options → “Write log to file” |

---  

<a name="troubleshooting"></a>
## 🔟 Common Pitfalls on Apple Silicon & Quick Fixes  

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| **Burp won’t start, crashes with “java.lang.UnsupportedClassVersionError”** | Running with an old JDK (Java 8) from the system path. | Ensure you start Burp via the bundled app (`/Applications/Burp Suite.app/Contents/MacOS/Burp`) – it uses the bundled JRE. Or delete any `JAVA_HOME` that points to an old JDK. |
| **Proxy works for HTTP but HTTPS shows “SSL handshake failed”** | CA cert not trusted or browser bypassing the proxy. | Re‑install the CA as described in § 5, and verify system proxy settings include **Secure Web Proxy**. |
| **Burp UI is sluggish, mouse moves slowly** | Running under **Rosetta 2** unintentionally (if you installed the Intel‑only version). | Make sure you installed the **ARM** cask (`brew info burp-suite` should show “arm64”). Delete any Intel‑only copy (`rm -rf /Applications/Burp\ Suite.app`). |
| **Extensions won’t load (Jython error “Could not find module”)** | Missing execute permission on the extension file. | `chmod +x /path/to/extension.py` or re‑download the file. |
| **Burp can’t bind to port 8080 (Address already in use)** | Another service (e.g., a Docker container) already listening. | `lsof -i :8080` to locate the process, stop it, or change Burp’s listener port under **Proxy → Options → Proxy Listeners**. |
| **Keychain says “This certificate is not valid” after import** | The certificate was imported into the *login* keychain instead of *System*. | Use the `sudo security add‑trusted‑cert … /Library/Keychains/System.keychain` command (see § 5). |
| **Burp can’t read/writing files outside your home folder** | macOS blocks the app due to missing *Full Disk Access*. | System Settings → Privacy & Security → Full Disk Access → add **Burp Suite** (or run Burp from Terminal with `sudo` – not recommended for regular use). |
| **Scanner shows “Too many connections – aborting”** | macOS limits simultaneous outbound connections for a single process. | Increase the limit: `sudo launchctl limit maxfiles 65536 65536` and restart Burp. |

---  

<a name="shortcuts"></a>
## 1️⃣1️⃣ Handy Keyboard Shortcuts inside Burp Suite  

| Shortcut | Action |
|----------|--------|
| **Ctrl R** | Send request to **Repeater** |
| **Ctrl I** | Send request to **Intruder** |
| **Ctrl S** | Send request to **Scanner** (Pro) |
| **Ctrl E** | Send request to **Extensions** (custom) |
| **Ctrl F** | Focus the **Filter** box in the **HTTP history** tab |
| **Ctrl L** | Open the **Logger** tab |
| **Ctrl Shift L** | Clear *all* logged traffic |
| **Ctrl U** | Decode/Encode selected text in **Decoder** |
| **Ctrl D** | Duplicate the selected request/response |
| **Alt + ← / →** | Navigate back/forward in **Repeater** tabs |
| **F5** | Refresh the **Target** site map |
| **⌘ + Shift + B** | Open **BApp Store** (macOS keyboard shortcut) |

---  

<a name="resources"></a>
## 1️⃣2️⃣ Further Learning & Official References  

| Resource | What you’ll find |
|----------|-------------------|
| **Burp Suite Docs (official)** | <https://portswigger.net/burp/documentation> – complete reference for every tab, UI, and API. |
| **BApp Store** | <https://portswigger.net/bappstore> – community‑maintained extensions. |
| **Burp Suite Academy (free labs)** | <https://portswigger.net/burp/academy> – hands‑on tutorials, from basics to advanced exploitation. |
| **PortSwigger YouTube channel** | <https://www.youtube.com/c/PortSwigger> – video walkthroughs of new features. |
| **Homebrew Cask Docs** | <https://docs.brew.sh/Cask-Cookbook> – how casks work, how to audit a cask. |
| **macOS Keychain Access guide** | <https://support.apple.com/guide/keychain-access/welcome/mac> – managing certificates. |
| **Apple Silicon Java FAQ** | <https://developer.apple.com/forums/thread/727332> – troubleshooting Java on ARM Macs. |
| **OWASP Web Security Testing Guide** | <https://owasp.org/www-project-web-security-testing-guide/> – a methodological companion for using Burp. |

---  

## 🎉 You’re Ready!  

You now have:

* **Burp Suite installed** via Homebrew on Apple Silicon.  
* **Proxy + HTTPS decryption** fully configured.  
* **Extensions** (BApp Store & custom) ready to load.  
* A **basic workflow** (intercept → repeat → intruder → scan).  

From here you can:

1. **Practice on the OWASP Juice Shop** (`https://juice.shop/`) – a deliberately vulnerable web app ideal for learning.  
2. **Write your own Jython/Python extensions** (e.g., a custom encoder).  
3. **Integrate Burp with CI/CD pipelines** (use `burp`’s command‑line API or the **Burp Suite Enterprise** API for automated scans).  

Enjoy hunting bugs, and remember: the best way to learn security testing is to **capture, modify, and replay** real traffic—Burp makes that simple, even on the newest Apple Silicon Macs. 🚀  