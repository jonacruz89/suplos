const API_URL = `${document.location.protocol}//api.${document.location.hostname}`;

let listPlaces = [];
let myListPlaces = [];

getData = async () => {
  listPlaces = await apiFetch();
  setListPlaces(listPlaces);
  myListPlaces = await apiFetch("/favorite");
  myListPlaces.forEach((e) => {
    setMyListPlaces(e);
  });
  setListCity(await apiFetch("/city"));
  setListType(await apiFetch("/type"));
};

setListPlaces = (listPlaces, city = "", type = "") => {
  let element = document.getElementById("resultados");
  listPlaces.forEach((e) => {
    if (
      (city.length == 0 || city == e.city) &&
      (type.length == 0 || type == e.type)
    ) {
      let el = htmlToElement(`
        <div
            class="d-flex justify-content-start align-items-center my-2 p-2 rounded"
            >
            <img src="img/home.jpg" alt="" width="250px" class="mr-3"/>
            <div class="">
                <p><strong>Dirección:</strong> ${e.address}</p>
                <p><strong>Ciudad:</strong> ${e.city}</p>
                <p><strong>Teléfono:</strong> ${e.phone}</p>
                <p><strong>código postal:</strong> ${e.code}</p>
                <p><strong>Tipo:</strong> ${e.type}</p>
                <p><strong>Precio:</strong> ${e.price}</p>
                ${
                  e.is_favorite == 1
                    ? ""
                    : `<button class="btn btn-success">Guardar</button>`
                }
            </div>
        </div>`);

      let btn = el.querySelector(".btn-success");
      if (btn) {
        btn.addEventListener("click", (_this) => {
          _this.target.parentNode.removeChild(_this.target);
          myListPlaces.push(e);
          setMyListPlaces(e);

          apiFetch("/place", "post", {
            id: e.id,
          });
        });
      }
      element.append(el);
    }
  });
};

setMyListPlaces = (place) => {
  let element = document.getElementById("misResultados");

  let el = htmlToElement(`
        <div
            class="d-flex justify-content-start align-items-center my-2 p-2 rounded"
            >
            <img src="img/home.jpg" alt="" width="250px" class="mr-3"/>
            <div class="">
                <p><strong>Dirección:</strong> ${place.address}</p>
                <p><strong>Ciudad:</strong> ${place.city}</p>
                <p><strong>Teléfono:</strong> ${place.phone}</p>
                <p><strong>código postal:</strong> ${place.code}</p>
                <p><strong>Tipo:</strong> ${place.type}</p>
                <p><strong>Precio:</strong> ${place.price}</p>
            </div>
        </div>`);

  element.append(el);
};

setListCity = (citys = []) => {
  let element = document.getElementById("selectCiudad");
  citys.forEach((e) => {
    let _e = document.createElement("option");
    _e.value = e.city;
    _e.textContent = e.city;

    element.append(_e);
  });
};

setListType = (types = []) => {
  let element = document.getElementById("selectTipo");
  types.forEach((e) => {
    let _e = document.createElement("option");
    _e.value = e.type;
    _e.textContent = e.type;

    element.append(_e);
  });
};

apiFetch = async (endpoint = "", method = "get", data = []) => {
  switch (method) {
    case "get":
      try {
        return await fetch(`${API_URL}${endpoint}`)
          .then((res) => res.json())
          .then((res) => res.data);
      } catch (error) {}

      break;

    case "post":
      try {
        return await fetch(`${API_URL}${endpoint}`, {
          method: "POST",
          body: JSON.stringify(data),
        })
          .then((res) => res.json())
          .then((res) => res.data);
      } catch (error) {}

      break;

    default:
      break;
  }
};

document.getElementById("submitButton").addEventListener("click", () => {
  let city = document.getElementById("selectCiudad").value;
  let type = document.getElementById("selectTipo").value;

  setListPlaces(listPlaces, city, type);
});

htmlToElement = (html) => {
  var template = document.createElement("template");
  html = html.trim();
  template.innerHTML = html;
  return template.content.firstChild;
};

getData();
