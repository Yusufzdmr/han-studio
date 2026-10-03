/*
 * Freistellen: das Gruen oder Schwarz eines Films im Browser herausstanzen.
 *
 * Ein Film ohne Alphakanal (.mov/.mp4, H.264) traegt seinen Grund als
 * echtes Bild - ein Greenscreen sein Gruen, ein auf Schwarz gedrehter Film
 * sein Schwarz. Ein <video> zeigt es so, wie es ist - durchsichtig wird nur,
 * was im Film schon durchsichtig ist (webm mit Alpha).
 *
 * Und das hilft nur halb: webm mit Alpha ist auf dem iPhone schwarz, HEVC
 * mit Alpha auf Android. Ein H.264-Film laeuft ueberall, und das Stanzen
 * hier auch - eine Datei fuer alle Geraete.
 *
 * Der Grafiker waehlt im Panel, was weg soll, Design::html() schreibt
 * data-stanze="gruen|schwarz" an den Film, und hier passiert der Rest:
 *
 *   - Eine Leinwand tritt an die Stelle des Films und uebernimmt seine
 *     Klassen. Damit gelten fuer sie dieselben Regeln aus Design::css()
 *     (Kasten, Drehung, object-fit), und der Editor findet sie unter
 *     .d-el-<id> wie jede andere Ebene.
 *   - Der Film selbst bleibt im Dokument, winzig und unsichtbar, und heisst
 *     jetzt d-gruen-film. invitation.js startet ihn unter diesem Namen.
 *     Ganz entfernt (display:none) wuerde manch ein Browser ihn nicht mehr
 *     dekodieren - dann bliebe die Leinwand stehen.
 *   - Jedes neue Bild des Films geht als Textur durch einen kleinen Shader,
 *     der den Grund zu Durchsichtigkeit macht.
 *
 * Ohne WebGL bleibt alles, wie es war: der Film mit seinem Grund. Lieber
 * ein sichtbarer Hintergrund als eine leere Stelle.
 */
(function () {
  "use strict";

  var ECKEN = new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]);

  var NETZ =
    "attribute vec2 p;" +
    "varying vec2 v;" +
    "void main(){" +
    // Die Textur steht kopf (Video: oben ist Zeile 0), also y umdrehen.
    "  v = vec2((p.x + 1.0) * 0.5, (1.0 - p.y) * 0.5);" +
    "  gl_Position = vec4(p, 0.0, 1.0);" +
    "}";

  /*
   * Wie gruen ist ein Punkt: wie weit sein Gruen ueber Rot und Blau liegt.
   * Ein Studiogruen liegt da grob bei 0.5, Gold, Rosa und Weiss bei null
   * oder darunter, ein olivfarbenes Blatt knapp darueber. Zwischen 0.12 und
   * 0.30 wird weich ausgeblendet, damit die Kanten nicht treppig werden.
   *
   * Der Saum: an halbdurchsichtigen Raendern traegt das Bild noch Gruen vom
   * Hintergrund. Dort wird Gruen auf Rot/Blau gedeckelt - je
   * durchsichtiger, desto mehr. Voll deckende Punkte bleiben unberuehrt,
   * damit ein echtes gruenes Blatt gruen bleibt.
   *
   * Schwarz (m = 1): die Deckkraft ist die Helligkeit, bis 0.5 linear,
   * darueber voll. Der Film wurde auf Schwarz gerechnet, seine Kanten sind
   * also schon "Farbe mal Deckung" - genau das, was eine Leinwand mit
   * premultipliedAlpha erwartet, die Farbe bleibt darum unangetastet.
   * Ausprobiert am Rosenbogen vom 02.10.2026 auf hellem Kartengrund: eine
   * harte Schwelle (Schwarz weg, alles andere voll) zog dunkle Konturen um
   * jedes Blatt, linear bis 0.85 liess die Blaetter verblassen. 0.5 hielt
   * beides klein.
   */
  var FARBE =
    "precision mediump float;" +
    "uniform sampler2D t;" +
    "uniform float m;" +
    "varying vec2 v;" +
    "void main(){" +
    "  vec4 c = texture2D(t, v);" +
    "  if (m > 0.5) {" +
    "    float h = max(c.r, max(c.g, c.b));" +
    "    gl_FragColor = vec4(c.rgb, clamp(h / 0.5, 0.0, 1.0));" +
    "    return;" +
    "  }" +
    "  float rb = max(c.r, c.b);" +
    "  float g = c.g - rb;" +
    "  float a = 1.0 - smoothstep(0.12, 0.30, g);" +
    "  c.g = mix(min(c.g, rb), c.g, a * a);" +
    "  gl_FragColor = vec4(c.rgb * a, a);" +
    "}";

  var shader = function (gl, art, quelle) {
    var s = gl.createShader(art);
    gl.shaderSource(s, quelle);
    gl.compileShader(s);
    return gl.getShaderParameter(s, gl.COMPILE_STATUS) ? s : null;
  };

  var stanze = function (film) {
    var leinwand = document.createElement("canvas");
    var gl = leinwand.getContext("webgl", { alpha: true, premultipliedAlpha: true, antialias: false })
      || leinwand.getContext("experimental-webgl");
    if (!gl) return;

    var netz = shader(gl, gl.VERTEX_SHADER, NETZ);
    var farbe = shader(gl, gl.FRAGMENT_SHADER, FARBE);
    if (!netz || !farbe) return;

    var prog = gl.createProgram();
    gl.attachShader(prog, netz);
    gl.attachShader(prog, farbe);
    gl.linkProgram(prog);
    if (!gl.getProgramParameter(prog, gl.LINK_STATUS)) return;
    gl.useProgram(prog);
    gl.uniform1f(gl.getUniformLocation(prog, "m"), film.getAttribute("data-stanze") === "schwarz" ? 1 : 0);

    var puffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, puffer);
    gl.bufferData(gl.ARRAY_BUFFER, ECKEN, gl.STATIC_DRAW);
    var ort = gl.getAttribLocation(prog, "p");
    gl.enableVertexAttribArray(ort);
    gl.vertexAttribPointer(ort, 2, gl.FLOAT, false, 0, 0);

    var textur = gl.createTexture();
    gl.bindTexture(gl.TEXTURE_2D, textur);
    // Filmmasse sind selten Zweierpotenzen: ohne CLAMP und ohne Mipmaps
    // bleibt die Textur in WebGL 1 schwarz.
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);

    // Die Leinwand nimmt den Platz des Films ein: dieselben Klassen, dieselben
    // Inline-Stile, dieselbe Stelle im Dokument.
    leinwand.className = film.className;
    var stil = film.getAttribute("style");
    if (stil) leinwand.setAttribute("style", stil);
    // Auch die data-Marken: Countdown- und Abschnittsschmuck werden ueber sie
    // gefunden (data-cdzeichen, data-secdeko - der Editor zieht daran, die
    // Vorschau malt daran). data-stanze bleibt am Film, sonst fande los()
    // die Leinwand beim naechsten Durchgang als Film.
    Array.prototype.forEach.call(film.attributes, function (a) {
      if (a.name.indexOf("data-") === 0 && a.name !== "data-stanze") leinwand.setAttribute(a.name, a.value);
    });
    leinwand.setAttribute("aria-hidden", "true");
    film.parentNode.insertBefore(leinwand, film);

    film.className = "d-gruen-film";
    film.removeAttribute("style");
    film.style.cssText = "position:absolute;left:0;top:0;width:2px;height:2px;opacity:0;pointer-events:none;";

    var kaputt = false;
    var zeichne = function () {
      if (kaputt || film.readyState < 2 || !film.videoWidth) return;
      if (leinwand.width !== film.videoWidth || leinwand.height !== film.videoHeight) {
        leinwand.width = film.videoWidth;
        leinwand.height = film.videoHeight;
        gl.viewport(0, 0, leinwand.width, leinwand.height);
      }
      try {
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, film);
      } catch (e) {
        // Ein Film von fremdem Host ohne CORS: die Textur ist gesperrt. Dann
        // lieber den Film mit seinem Grund zeigen als eine leere Leinwand.
        kaputt = true;
        leinwand.parentNode.removeChild(leinwand);
        film.className = leinwand.className;
        film.removeAttribute("style");
        if (stil) film.setAttribute("style", stil);
        return;
      }
      gl.clearColor(0, 0, 0, 0);
      gl.clear(gl.COLOR_BUFFER_BIT);
      gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    };

    // Erstes Bild auch ohne Abspielen: wer Bewegung abbestellt hat, startet
    // den Film nie, soll aber trotzdem den Bogen sehen und nicht nichts.
    film.addEventListener("loadeddata", zeichne);
    film.addEventListener("seeked", zeichne);

    if (typeof film.requestVideoFrameCallback === "function") {
      // Genau ein Durchgang je neuem Filmbild - nicht 60 je Sekunde fuer
      // einen Film mit 30. Haelt still, solange der Film steht.
      var jeBild = function () {
        zeichne();
        if (!kaputt) film.requestVideoFrameCallback(jeBild);
      };
      film.requestVideoFrameCallback(jeBild);
    } else {
      var laeuft = false;
      var schleife = function () {
        zeichne();
        if (laeuft && !kaputt) window.requestAnimationFrame(schleife);
      };
      film.addEventListener("play", function () {
        if (laeuft) return;
        laeuft = true;
        window.requestAnimationFrame(schleife);
      });
      film.addEventListener("pause", function () { laeuft = false; });
      film.addEventListener("ended", function () { laeuft = false; });
    }

    zeichne();
  };

  var los = function () {
    var filme = document.querySelectorAll("video[data-stanze]");
    for (var i = 0; i < filme.length; i++) {
      if (filme[i].getAttribute("src")) stanze(filme[i]);
    }
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", los);
  } else {
    los();
  }
})();
