#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>

// === WiFi Config ===
const char* ssid = "Hotspot Area";
const char* password = "sekolahvokasimadiun";

// === Server Laravel ===
const char* serverName = "https://34aa-202-47-188-225.ngrok-free.app/api/sensor";

// === Sensor Pins ===
#define SOIL_PIN 34
#define DHT_PIN 4
#define MQ135_PIN 35
#define MQ4_PIN 32
#define RELAY_PIN 5

#define DHTTYPE DHT22
DHT dht(DHT_PIN, DHTTYPE);

// === Thresholds
const int SOIL_DRY_THRESHOLD = 30;
const int SOIL_WET_THRESHOLD = 60;
bool pumpState = false;

void setup() {
  Serial.begin(115200);

  Serial.print("Menghubungkan WiFi");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Connected");

  pinMode(RELAY_PIN, OUTPUT);
  digitalWrite(RELAY_PIN, LOW); // Pompa off

  dht.begin();
}

void loop() {
  // === Baca Sensor ===
  int soilRaw = analogRead(SOIL_PIN);
  float soilPercent = constrain(map(soilRaw, 4095, 0, 0, 100), 0, 100);

  float temp = dht.readTemperature();
  float hum = dht.readHumidity();

  if (isnan(temp) || isnan(hum)) {
    Serial.println("Gagal baca DHT22, gunakan nilai 0");
    temp = 0.0;
    hum = 0.0;
  }

  int mq135 = analogRead(MQ135_PIN);
  int mq4 = analogRead(MQ4_PIN);

  // === Tampilkan ke Serial
  Serial.println("===================================");
  Serial.print("Soil Moisture (%): "); Serial.println(soilPercent);
  Serial.print("Temperature (*C): "); Serial.println(temp);
  Serial.print("Humidity (%):     "); Serial.println(hum);
  Serial.print("MQ135:            "); Serial.println(mq135);
  Serial.print("MQ4:              "); Serial.println(mq4);

  // === Kontrol Pompa
  if (!pumpState && soilPercent > SOIL_WET_THRESHOLD) {
    digitalWrite(RELAY_PIN, HIGH);
    pumpState = true;
    Serial.println("Pompa DINYALAKAN (Tanah terlalu basah)");
  } 
  else if (pumpState && soilPercent < SOIL_DRY_THRESHOLD) {
    digitalWrite(RELAY_PIN, LOW);
    pumpState = false;
    Serial.println("Pompa DIMATIKAN (Tanah terlalu kering)");
  } 
  else {
    Serial.println(pumpState ? "Pompa tetap NYALA" : "Pompa tetap MATI");
  }

  // === Kirim ke Server
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverName);
    http.addHeader("Content-Type", "application/json");

    String jsonData = "{";
    jsonData += "\"kelembapan_tanah\":" + String(soilPercent, 1) + ",";
    jsonData += "\"suhu\":" + String(temp, 1) + ",";
    jsonData += "\"gas_karbon\":" + String(mq135) + ",";
    jsonData += "\"gas_metana\":" + String(mq4);
    jsonData += "}";

    int httpResponseCode = http.POST(jsonData);
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("RESPON SERVER:");
      Serial.println(response);
    } else {
      Serial.println("Gagal kirim data ke server!");
    }

    http.end();
  } else {
    Serial.println("WiFi belum terhubung!");
  }

  delay(5000);
}
