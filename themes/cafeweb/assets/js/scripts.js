

function click(element, select, classEl, eventEl = null)
{
  let el = document.querySelector(element);
  let selected = document.querySelector(select)

  el.addEventListener('click', event =>{
     el.classList.toggle(eventEl);
     selected.classList.toggle(classEl);
  });
}

click('.button-nav', '.menu', 'nav-active', 'mobil-active');
if(document.querySelector("#filter")){
	click('#filter', '#boxFilter', 'filter-active');
}


 