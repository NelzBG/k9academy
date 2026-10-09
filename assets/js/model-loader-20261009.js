(() => {
 const stage=document.querySelector('[data-dog-stage]');
 if(!stage)return;
 let loaded=false;
 const load=()=>{if(loaded)return;loaded=true;import('/assets/js/dog-viewer-20260906.js');};
 if('IntersectionObserver' in window){const watcher=new IntersectionObserver(entries=>{if(entries.some(e=>e.isIntersecting)){watcher.disconnect();load();}},{rootMargin:'100px'});watcher.observe(stage);}
 else stage.addEventListener('pointerdown',load,{once:true});
})();
