#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>

// === WiFi Config ===
const char* ssid = "Poco X5 Pro 5G";
const char* password = "87654321";

// === Server Laravel ===
const char* serverName = "https://ecofarm.tifpsdku.com/api/sensor";

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

// === Kalibrasi MQ-4 ===
const float Ro = 1.71;      // nilai rata-rata Ro dari kalibrasi
const float RL = 10.0;      // resistor beban (kOhm)
const float a = 4.4;        // konstanta dari datasheet MQ-4
const float b = -2.2;

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
  int mq4Raw = analogRead(MQ4_PIN);

  // === Hitung ppm CH₄ dari MQ-4 ===
  float voltage = mq4Raw * (5.0 / 4095.0);  // ADC 12-bit
  float Rs = ((5.0 - voltage) / voltage) * RL;
  float ratio = Rs / Ro;
  float ppmCH4 = a * pow(ratio, b);
  ppmCH4 = constrain(ppmCH4, 0, 10000); // batas maksimum ppm

  // === Konversi ke Persentase Gas Metana
  float persenCH4 = ppmCH4 / 10000.0 * 100.0;

  // === Tampilkan ke Serial
  Serial.println("===================================");
  Serial.print("Soil Moisture (%): "); Serial.println(soilPercent);
  Serial.print("Temperature (*C): "); Serial.println(temp);
  Serial.print("Humidity (%):     "); Serial.println(hum);
  Serial.print("MQ135:            "); Serial.println(mq135);
  Serial.print("MQ4 Raw:          "); Serial.println(mq4Raw);
  Serial.print("MQ4 CH4 (ppm):    "); Serial.println(ppmCH4);
  Serial.print("CH4 (%):          "); Serial.println(persenCH4, 2);

  // === Kontrol Pompa
  if (soilPercent > SOIL_WET_THRESHOLD) {
  digitalWrite(RELAY_PIN, HIGH);  // Pompa ON
  Serial.println("Pompa DINYALAKAN (Tanah terlalu basah)");
} else {
  digitalWrite(RELAY_PIN, LOW);   // Pompa OFF
  Serial.println("Pompa DIMATIKAN");
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
    jsonData += "\"gas_metana\":" + String(persenCH4, 2);  // Hanya kirim dalam persen
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

  delay(2000);
}