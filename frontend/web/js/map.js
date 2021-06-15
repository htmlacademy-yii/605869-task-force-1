// Дождёмся загрузки API и готовности DOM.
ymaps.ready(init);

function init() {
  let mapDom;
  mapDom = document.getElementById('#map');
  if (mapDom) {
    let myMap;
    myMap = new ymaps.Map("map", {
      center: [ mapDom.dataset.longitude, mapDom.dataset.latitude],
      zoom: 10
    });
  }
}
