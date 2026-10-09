import assert from 'node:assert/strict';
import {readFile} from 'node:fs/promises';
import {fileURLToPath} from 'node:url';
import {dirname, join} from 'node:path';
import vm from 'node:vm';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const source = await readFile(join(root, 'assets/js/editorial-20261009.js'), 'utf8');
const flush = async () => { await new Promise(setImmediate); };
let checks = 0;
function harness(choice = 'accepted', {reduced = false, saveData = false, apiAvailable = true, observer = true, lang = 'en'} = {}) {
 const calls = [], frames = [], players = [], timers = [], listeners = new Map();
 class Element {
  constructor(tag = 'div') { this.tagName = tag; this.children = []; this.attrs = {}; this.events = new Map(); this.dataset = {}; this.hidden = false; this.textContent = ''; this.disabled = false; this.classes = new Set(); this.classList = {add: name => this.classes.add(name), remove: name => this.classes.delete(name), contains: name => this.classes.has(name)}; }
  addEventListener(type, fn) { if (!this.events.has(type)) this.events.set(type, []); this.events.get(type).push(fn); }
  emit(type, event = {}) { for (const fn of this.events.get(type) || []) fn({target: this, currentTarget: this, ...event}); }
  append(...items) { this.children.push(...items); items.forEach(item => {item.parent = this;}); }
  replaceChildren(...items) { this.children = []; this.append(...items); }
  remove() { if (this.parent) this.parent.children = this.parent.children.filter(item => item !== this); }
  setAttribute(key, value) { this.attrs[key] = value; }
  removeAttribute(key) { delete this.attrs[key]; }
  cloneNode() { const copy = new Element(this.tagName); copy.attrs = {...this.attrs}; copy.textContent = this.textContent; copy.hidden = this.hidden; return copy; }
  focus(options) { calls.push(['focus', options]); }
  matches() { return this.covered || false; }
  showModal() { this.open = true; }
  close() { this.open = false; this.emit('close'); }
 }
 const container = new Element(); container.id = 'k9-youtube-home'; container.dataset = {youtube: '7LM1RGSomPU', start: '69'};
 const frame = new Element(), controls = new Element(), play = new Element('button'), watch = new Element('button'), status = new Element('p'), external = new Element('a');
 const cookieEssential = new Element('button'), cookieAccepted = new Element('button');
 const buttons = {'[data-pause-video]': play, '[data-watch-video]': watch, '[data-video-status]': status, '[data-youtube-link]': external};
 controls.querySelector = key => buttons[key]; container.querySelector = key => key === '.k9-youtube-frame' ? frame : null;
 const head = new Element('head'), body = new Element('body');
 const document = {head, body, hidden: false, documentElement: {lang},
  querySelectorAll: key => key === '[data-youtube]' ? [container] : key === '[data-cookie-choice]' ? [cookieEssential, cookieAccepted] : [],
  querySelector: () => controls,
  createElement: tag => { const node = new Element(tag); if (tag === 'iframe') frames.push(node); return node; },
  addEventListener: (key, fn) => listeners.set(key, fn)};
 const media = {matches: reduced, addEventListener: (_key, fn) => {media.change = fn;}};
 const connection = {saveData, addEventListener: (_key, fn) => {connection.change = fn;}};
 class Player {
  constructor(id, {events}) {this.id = id; this.events = events; this.state = -1; players.push(this); calls.push(['create', id]);}
  mute() {calls.push(['mute']);}
  playVideo() {calls.push(['play']);}
  pauseVideo() {calls.push(['pause']); if (this.state !== 2) this.emit(2);}
  seekTo(seconds) {calls.push(['seek', seconds]);}
  destroy() {calls.push(['destroy']);}
  ready() {this.events.onReady({target: this});}
  emit(data) {this.state = data; this.events.onStateChange({target: this, data});}
  blocked() {this.events.onAutoplayBlocked({target: this});}
  error() {this.events.onError({target: this, data: 150});}
 }
 let intersection, mutation;
 const sandbox = {document, navigator: {connection}, location: {origin: 'https://www.k9academy.bg'}, URLSearchParams,
  localStorage: {getItem: () => choice}, matchMedia: () => media, queueMicrotask,
  setTimeout: (fn, delay) => {const timer = {fn, delay}; timers.push(timer); return timer;}, clearTimeout: () => {},
  MutationObserver: class {constructor(fn) {mutation = fn;} observe() {}},
  ...(observer ? {IntersectionObserver: class {constructor(fn) {intersection = fn;} observe() {}}} : {})};
 sandbox.window = sandbox;
 if (apiAvailable) sandbox.YT = {Player};
 vm.runInNewContext(source, sandbox, {timeout: 1000});
 return {calls, frames, players, timers, container, frame, play, watch, status, external, head, body, document, media, connection,
  visible: value => intersection?.([{target: container, isIntersecting: value}]),
  visibility: value => {document.hidden = value; listeners.get('visibilitychange')();},
  overlay: value => {body.covered = value; mutation();},
  consent: value => {choice = value; (value === 'accepted' ? cookieAccepted : cookieEssential).emit('click');},
  apiReady: () => {sandbox.YT = {Player}; sandbox.onYouTubeIframeAPIReady();}};
}

// Consent and viewport eligibility must precede every third-party request.
let h = harness('essential'); h.visible(true); await flush();
assert.equal(h.frames.length, 0); assert.equal(h.head.children.length, 0); checks++;
h = harness(); await flush(); assert.equal(h.frames.length, 0); h.visible(true); await flush();
assert.equal(h.frames.length, 1); assert.equal(h.players.length, 1);
const url = new URL(h.frames[0].src);
assert.equal(url.origin, 'https://www.youtube-nocookie.com');
for (const [key, value] of Object.entries({mute: '1', autoplay: '1', start: '69', playsinline: '1', controls: '0', enablejsapi: '1'})) assert.equal(url.searchParams.get(key), value);
assert.equal(h.timers.length, 0, 'No artificial post-load start timer'); checks++;

// A ready player is not a playing player: keep the poster until PLAYING arrives.
assert.equal(h.container.classList.contains('is-video-ready'), false);
h.players[0].ready();
assert(h.calls.findIndex(call => call[0] === 'mute') < h.calls.findIndex(call => call[0] === 'play'));
assert.equal(h.container.classList.contains('is-video-ready'), false);
h.players[0].emit(1); assert.equal(h.container.classList.contains('is-video-ready'), true); assert.equal(h.play.textContent, 'Pause background'); checks++;

// User pause survives leaving/re-entering the viewport; explicit play resumes.
h.play.emit('click'); assert.equal(h.play.textContent, 'Play background');
let count = h.calls.filter(call => call[0] === 'play').length;
h.visible(false); h.visible(true); assert.equal(h.calls.filter(call => call[0] === 'play').length, count);
h.play.emit('click'); assert(h.calls.filter(call => call[0] === 'play').length > count); h.players[0].emit(1); checks++;

// Browser autoplay rejection offers a user gesture instead of endless retries.
h.players[0].blocked(); count = h.calls.filter(call => call[0] === 'play').length;
assert.equal(h.play.disabled, false); assert.equal(h.play.textContent, 'Play background'); assert.match(h.status.textContent, /Press Play/);
h.visible(true); assert.equal(h.calls.filter(call => call[0] === 'play').length, count);
h.play.emit('click'); assert(h.calls.filter(call => call[0] === 'play').length > count); h.players[0].emit(1); checks++;

// Hidden tabs, navigation/privacy overlays and offscreen video must pause.
for (const pause of [() => h.visibility(true), () => h.overlay(true), () => h.visible(false)]) {
 h.visibility(false); h.overlay(false); h.visible(true); h.players[0].emit(1);
 const paused = h.calls.filter(call => call[0] === 'pause').length;
 pause(); assert(h.calls.filter(call => call[0] === 'pause').length > paused);
}
checks++;

// A player error restores the poster and leaves an external viewing alternative.
h.players[0].error(); assert.equal(h.frame.children.length, 0); assert.equal(h.container.classList.contains('is-video-ready'), false);
assert.equal(h.external.hidden, false); assert.match(h.status.textContent, /unavailable/); checks++;

// Withdrawal invalidates an in-flight API load, so late readiness cannot embed it.
h = harness('accepted', {apiAvailable: false}); h.visible(true); await flush();
assert.equal(h.head.children[0].src, 'https://www.youtube.com/iframe_api');
h.consent('essential'); await flush(); assert.equal(h.frame.children.length, 0);
h.apiReady(); await flush(); assert.equal(h.players.length, 0); assert.equal(h.container.classList.contains('is-video-ready'), false); checks++;

// Reduced motion / data saver default to the poster, with an explicit play option.
for (const option of [{reduced: true}, {saveData: true}]) {
 h = harness('accepted', option); h.visible(true); await flush(); assert.equal(h.frames.length, 0);
 h.play.emit('click'); await flush(); assert.equal(h.frames.length, 1); h.players[0].ready(); h.players[0].emit(1);
 assert.equal(h.play.textContent, 'Pause background');
}
checks++;

// Foreground watching uses sound/controls and a separate disposable dialog player.
h = harness('essential'); h.visible(true); h.watch.emit('click');
assert.equal(new URL(h.frames[0].src).searchParams.get('mute'), '0'); assert.equal(new URL(h.frames[0].src).searchParams.get('controls'), '1');
const dialog = h.body.children[0]; assert.equal(dialog.tagName, 'dialog'); dialog.close();
assert.equal(h.body.children.length, 0); assert(h.calls.some(call => call[0] === 'focus')); checks++;

// Both languages must bind real controls to the hero/training background IDs.
for (const prefix of ['', 'en/']) for (const slug of ['', 'training/']) {
 const html = await readFile(join(root, prefix, slug, 'index.html'), 'utf8');
 const id = slug ? 'k9-youtube-training' : 'k9-youtube-home';
 assert.equal((html.match(new RegExp('id="' + id + '"', 'g')) || []).length, 1);
 assert.equal((html.match(new RegExp('data-video-controls="' + id + '"', 'g')) || []).length, 1);
 assert(!html.includes('data-pause-video hidden'));
 if (!slug) assert.match(html, /k9-hero-tools[\s\S]*data-effects-toggle[\s\S]*data-video-controls/);
}
checks++;
console.log('YouTube lifecycle checks passed: ' + checks + ' scenarios (consent, readiness, playback, pause, retry, errors, withdrawal, motion/data preferences, dialog and bilingual controls).');
