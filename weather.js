let city = "Your_Assigned_city";
async function fetchWeatherData(searchCity) {
    try {
        const api_URL = `http://localhost/prototype_2/connnection.php?q=${searchCity}`;
        const response = await fetch(api_URL);
        const data = await response.json();

        console.log(data);

        if(data.error) {
            document.getElementById("errorMsg").innerHTML = data.error;
            return ;
        }

        document.getElementById("cityName").innerHTML = searchCity;

        const date = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById("date").innerHTML = date.toLocaleDateString('en-US', options);

        document.getElementById("conditionDetail").innerHTML = data.weather_description;

        const tempCelsius = Math.round(data.temperature);
        document.getElementById("temperature").innerHTML = tempCelsius + "°C";
        document.getElementById("tempValue").innerHTML = tempCelsius + "°C";

        document.getElementById("pressure").innerHTML = data.pressure + " hPa";

        document.getElementById("humidity").innerHTML = data.humidity + "%";

        document.getElementById("windSpeed").innerHTML = data.wind + " m/s";

        document.getElementById("windDirection").innerHTML = data.wind_direction + "°";

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
