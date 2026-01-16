const api_key = "8e67b2065f4c070146565abbdcbc8616";
let city = "janakpur";

async function fetchWeatherData(searchCity) {
    try {
        const api_URL = ;
        const response = await fetch(api_URL);
        const data = await response.json();

        console.log(data);

        //city name
        document.getElementById("cityName").innerHTML = data.name;

        //date 
        const date = new Date(data.dt * 1000);
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById("date").innerHTML = date.toLocaleDateString('en-US', options);

        //detailed condition in table
        document.getElementById("conditionDetail").innerHTML = data.weather[0].description;

        //temperature
        const tempCelsius = Math.round(data.main.temp);
        document.getElementById("temperature").innerHTML = tempCelsius + "°C";
        document.getElementById("tempValue").innerHTML = tempCelsius + "°C";

        //pressure
        document.getElementById("pressure").innerHTML = data.main.pressure + " hPa";

        //humidity
        document.getElementById("humidity").innerHTML = data.main.humidity + "%";

        //wind speed
        document.getElementById("windSpeed").innerHTML = data.wind.speed + " m/s";

        //wind direction
        document.getElementById("windDirection").innerHTML = data.wind.deg + "°";

        document.getElementById("errorMsg").innerHTML = "";

    } catch (err) {
        console.log(err);
        document.getElementById("errorMsg").innerHTML = "Error fetching weather data. Please check the city name and try again.";
    }
}

fetchWeatherData(city);
document.getElementById("searchButton").addEventListener("click", function() {
    const cityInput = document.getElementById("cityInput").value.trim();
    if (cityInput !== "") {
        fetchWeatherData(cityInput);
        document.getElementById("cityInput").value = "";
    } else {
        document.getElementById("errorMsg").innerHTML = "Please enter a city name.";
    }
});