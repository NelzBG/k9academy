document.querySelectorAll('[data-training-gallery]').forEach(gallery=>{
 const rail=gallery.querySelector('[data-training-rail]'),photos=[...gallery.querySelectorAll('[data-training-photo]')],cards=photos.map(p=>p.closest('figure')),count=gallery.querySelector('[data-training-count]'),dialog=gallery.querySelector('dialog'),large=gallery.querySelector('[data-training-large]'),caption=gallery.querySelector('[data-lightbox-caption]');
 const reduced=matchMedia('(prefers-reduced-motion: reduce)');
 let current=0,opened=0,returnFocus=null,savedOverflow='',frame=0;
 const nearest=()=>{const left=rail.getBoundingClientRect().left;return cards.reduce((best,c,i)=>Math.abs(c.getBoundingClientRect().left-left)<Math.abs(cards[best].getBoundingClientRect().left-left)?i:best,0)};
 const update=()=>{current=nearest();count.textContent=String(current+1).padStart(2,'0')+' / '+photos.length};
 const move=delta=>{current=(nearest()+delta+photos.length)%photos.length;rail.scrollTo({left:rail.scrollLeft+cards[current].getBoundingClientRect().left-cards[0].getBoundingClientRect().left-(rail.scrollLeft),behavior:reduced.matches?'instant':'smooth'})};
 gallery.querySelector('[data-training-prev]').addEventListener('click',()=>move(-1));gallery.querySelector('[data-training-next]').addEventListener('click',()=>move(1));
 rail.addEventListener('scroll',()=>{cancelAnimationFrame(frame);frame=requestAnimationFrame(update)},{passive:true});
 rail.addEventListener('keydown',e=>{if(e.target!==rail)return;if(e.key==='ArrowRight'||e.key==='ArrowLeft'){e.preventDefault();move(e.key==='ArrowRight'?1:-1)}});
 const show=i=>{opened=(i+photos.length)%photos.length;large.src=photos[opened].href;large.alt=photos[opened].dataset.caption;caption.textContent=(opened+1)+' / '+photos.length+' — '+large.alt};
 photos.forEach((link,i)=>link.addEventListener('click',e=>{if(e.ctrlKey||e.metaKey||e.shiftKey||e.altKey)return;e.preventDefault();returnFocus=link;show(i);savedOverflow=document.body.style.overflow;document.body.style.overflow='hidden';dialog.showModal()}));
 dialog.addEventListener('close',()=>{document.body.style.overflow=savedOverflow;returnFocus?.focus({preventScroll:true})});
 dialog.querySelector('[data-lightbox-prev]').addEventListener('click',()=>show(opened-1));dialog.querySelector('[data-lightbox-next]').addEventListener('click',()=>show(opened+1));
 dialog.addEventListener('keydown',e=>{if(e.key==='ArrowRight'||e.key==='ArrowLeft'){e.preventDefault();show(opened+(e.key==='ArrowRight'?1:-1))}});
 let start=null;large.addEventListener('touchstart',e=>{start=e.touches.length===1?{x:e.touches[0].clientX,y:e.touches[0].clientY}:null},{passive:true});large.addEventListener('touchend',e=>{if(!start||e.touches.length)return;const dx=e.changedTouches[0].clientX-start.x,dy=e.changedTouches[0].clientY-start.y;if(Math.abs(dx)>65&&Math.abs(dx)>Math.abs(dy)*1.5)show(opened+(dx<0?1:-1));start=null},{passive:true});
 update();
});
