const API_URL = `${document.location.protocol}//api.${document.location.hostname}`;

let listPlaces = [];
let listCity = [];
let listType = [];

getData = async () => {
  setListPlaces(await apiFetch());
  setListCity(await apiFetch("/city"));
  setListType(await apiFetch("/type"));
};

setListPlaces = (listPlaces) => {
  let element = document.getElementById("resultados");
  let html = "";
  listPlaces.forEach((e) => {
    html += `<div
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
                        <button class="btn btn-success">Guardar</button>
                    </div>
                </div>`;
  });
  element.innerHTML = html;
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

apiFetch = async (endpoint = "", method = "get") => {
  switch (method) {
    case "get":
      try {
        return await fetch(`${API_URL}${endpoint}`)
          .then((res) => res.json())
          .then((res) => res.data);
      } catch (error) {}

      break;

    default:
      break;
  }
};

getData();
