(() => {
 'use strict';
 const bg = document.documentElement.lang === 'bg';
 const reduced = matchMedia('(prefers-reduced-motion: reduce)');
 const videos = [...document.querySelectorAll('[data-youtube]')];
 const permitted = () => { try { return localStorage.getItem('k9-cookie-choice')==='accepted'; } catch { return false; } };
 function player(container, foreground) {
  const iframe = document.createElement('iframe');
  const args = new URLSearchParams({autoplay:'1',mute:foreground?'0':'1',playsinline:'1',controls:foreground?'1':'0',rel:'0',loop:foreground?'0':'1',playlist:container.dataset.youtube,start:container.dataset.start,enablejsapi:'1',origin:location.origin});
  iframe.src='https://www.youtube-nocookie.com/embed/'+container.dataset.youtube+'?'+args;
  iframe.title=bg?'K9 Academy тренировка':'K9 Academy training';
  iframe.allow='autoplay; encrypted-media; picture-in-picture; fullscreen';
  iframe.referrerPolicy='strict-origin-when-cross-origin';
  iframe.allowFullscreen=true;
  if (!foreground) {iframe.tabIndex=-1;iframe.setAttribute('aria-hidden','true');}
  return iframe;
 }
 function background(container) {
  if(container._player || !permitted() || reduced.matches || navigator.connection?.saveData || document.hidden || container._inView===false)return;
  container._player=player(container,false);
  container.querySelector('.k9-youtube-frame').append(container._player);
  container.classList.add('has-youtube');
  container.querySelector('[data-pause-video]').hidden=false;
 }
 const command=(container,func)=>container._player?.contentWindow?.postMessage(JSON.stringify({event:'command',func,args:[]}), 'https://www.youtube-nocookie.com');
 function stopAll(){videos.forEach(v=>command(v,'pauseVideo'));}
 const observer = 'IntersectionObserver' in window ? new IntersectionObserver(entries=>entries.forEach(e=>{e.target._inView=e.isIntersecting;if(!e.isIntersecting)command(e.target,'pauseVideo');else if(e.target._player&&!e.target._paused&&!document.hidden)command(e.target,'playVideo');else if(e.target._idle)background(e.target);}),{threshold:.15}) : null;
 videos.forEach(container=>{
  observer?.observe(container);
  container.querySelector('[data-pause-video]').addEventListener('click',event=>{container._paused=!container._paused;command(container,container._paused?'pauseVideo':'playVideo');event.currentTarget.textContent=container._paused?(bg?'Пусни фона':'Play background'):(bg?'Спри фона':'Pause background');});
  container.querySelector('[data-watch-video]').addEventListener('click',()=>{
   stopAll();
   const dialog=document.createElement('dialog');dialog.className='k9-video-dialog';
   const close=document.createElement('button');close.type='button';close.className='button button-acid';close.textContent=bg?'Затвори видеото':'Close video';
   dialog.setAttribute('aria-label',bg?'Гледайте K9 Academy':'Watch K9 Academy');
   dialog.append(close,player(container,true));document.body.append(dialog);dialog.showModal();
   close.addEventListener('click',()=>dialog.close());
   dialog.addEventListener('click',event=>{if(event.target===dialog)dialog.close();});
   dialog.addEventListener('close',()=>{dialog.remove();container.querySelector('[data-watch-video]').focus();});
  });
 });
 const schedule=()=>setTimeout(()=>videos.forEach(v=>{v._idle=true;background(v);}),12000);
 if(document.readyState==='complete')schedule();else addEventListener('load',schedule,{once:true});
 document.querySelectorAll('[data-cookie-choice]').forEach(button=>button.addEventListener('click',()=>queueMicrotask(()=>{
  if(permitted())videos.forEach(v=>{if(v._idle)background(v);});
  else videos.forEach(v=>{v._player?.remove();v._player=null;v.classList.remove('has-youtube');v.querySelector('[data-pause-video]').hidden=true;});
 })));
 reduced.addEventListener('change',()=>{if(reduced.matches)stopAll();});
 document.addEventListener('visibilitychange',()=>{if(document.hidden)stopAll();else videos.forEach(v=>{if(v._player&&v._inView&&!v._paused&&!reduced.matches)command(v,'playVideo');});});
})();
