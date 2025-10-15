### Installation with Homebrew

```sh
brew install lame
```

-----

### LAME Command Examples

The examples below show how to encode an audio file. For this guide, assume you have a CD-quality audio file in your current directory named `input.wav`.

#### 1\. Basic Encoding (Default Quality)

This is the simplest way to create an MP3. LAME's default settings provide a good balance of quality and file size.

**Command:**

```sh
lame input.wav output.mp3
```

**Console Output (Snippet):**
When you run LAME, it shows you the encoding progress and a summary.

```
LAME 3.100 64bits (http://lame.sourceforge.net/)
...
Encoding as 44.1 kHz j-stereo MPEG-1 Layer III VBR(q=4)
    Frame      |  CPU time/estim | REAL time/estim | play/CPU |    ETA
    2616/2616  (100%)|    0:00.1/    0:00.1|    0:00.1/    0:00.1|   28.14x|    0:00.0
---------------------------------------------------------------------------------
   kbps        %     long block | short block
   175.5      100.0          2616 |           0
```

**Result:**
This creates a new file, `output.mp3`, encoded with LAME's default high-quality VBR (Variable Bitrate) settings.

#### 2\. High-Quality VBR Encoding

The `-V` flag controls VBR quality, from `-V9` (lowest) to `-V0` (highest). The `-V2` setting is widely considered "transparent," meaning it's audibly indistinguishable from the original source for most listeners.

**Command:**

```sh
lame -V2 input.wav high_quality.mp3
```

**Result:**
Creates `high_quality.mp3`. This is the recommended method for archiving music where quality is the top priority. The file size will be larger than the default but smaller than the original `.wav`.

#### 3\. Constant Bitrate (CBR) Encoding

Use the `-b` flag to force a constant bitrate. This is useful for older devices or some streaming applications that don't handle VBR well.

**Command:**

```sh
lame -b 192 input.wav constant_192.mp3
```

**Result:**
Creates `constant_192.mp3`, where every second of audio takes up exactly 192 kilobits. The file size is highly predictable. Common values are 128, 192, and 320.

#### 4\. Adding ID3 Tags (Metadata)

You can write metadata like artist, title, and album directly into the MP3 during encoding.

**Command:**

```sh
lame -V2 --tt "My Song Title" --ta "The Artist" --al "The Album" --ty "2025" --tc "Track 1" input.wav tagged_song.mp3
```

**Result:**
This creates a high-quality MP3 named `tagged_song.mp3`. When you open this file in a music player, it will correctly display all the track information you provided.

#### 5\. Encoding for Voice (Low Bitrate Mono)

For speech, like a podcast or audiobook, you can use settings that create a much smaller file.

**Command:**

```sh
lame -m m --abr 64 input.wav speech_output.mp3
```

**Result:**
Creates `speech_output.mp3`. The `-m m` flag makes the output mono (single channel), and `--abr 64` sets an average bitrate of 64 kbps, which is perfectly adequate for voice and results in a very small file size.

For a complete list of all options, run `man lame`.