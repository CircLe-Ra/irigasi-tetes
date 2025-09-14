#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

const char* ssid = "Aset Masa Depan";
const char* password = "12345678";

// ID unik untuk tiap alat soil sensor
int deviceSoilId = 1;

int threshold; // variabel batas
int deviceRelayId; // ini untuk ukuran nodemcu valve
int relayChannel; // ini untuk channel mana yang mau dinyalakan

String serverHost = "http://192.168.1.158:8000";

int soilPin = A0;

int lastRelayState = -1; 

void setup() {
  Serial.begin(115200);

  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected!");

  fetchSoilConfig();
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    // Selalu cek reset flag & ambil threshold terbaru
    checkResetFlag();

    // Baca sensor kelembapan
    int soilValue = analogRead(soilPin);
    Serial.print("Soil Value: ");
    Serial.println(soilValue);

     int moisturePercent = map(soilValue, 1023, 0, 0, 100);

     sendSoilData(soilValue, moisturePercent);

     if (soilValue < threshold) {
      // Tanah kering → nyalakan relay
      if (lastRelayState != 1) {
        sendRelayCommand(1);
        lastRelayState = 1;
      }
    } else {
      // Tanah cukup basah → matikan relay
      if (lastRelayState != 0) {
        sendRelayCommand(0);
        lastRelayState = 0;
      }
    }

  }

  delay(5000); // cek tiap 5 detik
}


void fetchSoilConfig() {
  WiFiClient client;
  HTTPClient http;

  String url = serverHost + "/api/soils/" + String(deviceSoilId);
  http.begin(client, url);

  int httpCode = http.GET();
  if (httpCode == 200) {
    String payload = http.getString();
    Serial.println("Initial Config Response: " + payload);

    // Contoh response: {"threshold":600,"reset":0,"device_relay_id":2,"channel":3}
    threshold     = getJsonValue(payload, "threshold").toInt();
    deviceRelayId = getJsonValue(payload, "device_relay_id").toInt();
    relayChannel  = getJsonValue(payload, "channel").toInt();
  }

  http.end();
}

void checkResetFlag() {
  WiFiClient client;
  HTTPClient http;

  String url = serverHost + "/api/soils/" + String(deviceSoilId);
  http.begin(client, url);

  int httpCode = http.GET();
  if (httpCode == 200) {
    String payload = http.getString();
    Serial.println("Check Reset Response: " + payload);
    // Contoh response: {"threshold":600,"reset":0,"device_relay_id":2,"channel":3}
    int newThreshold = getJsonValue(payload, "threshold").toInt();
    int resetVal     = getJsonValue(payload, "reset").toInt();
    int newRelayId   = getJsonValue(payload, "device_relay_id").toInt();
    int newChannel   = getJsonValue(payload, "channel").toInt();

    if (resetVal == 1) {
      threshold     = newThreshold;
      deviceRelayId = newRelayId;
      relayChannel  = newChannel;
      Serial.println("Threshold updated: " + String(threshold));

      // Ubah kembali status reset di database
      HTTPClient postHttp;
      String resetUrl = serverHost + "/api/soils/" + String(deviceSoilId) + "/reset";
      postHttp.begin(client, resetUrl);
      postHttp.POST(""); 
      postHttp.end();
    }
  }

  http.end();
}

void sendSoilData(int soilValue, int moisturePercent) {
  WiFiClient client;
  HTTPClient http;

  String url = serverHost + "/api/soils/" + String(deviceSoilId) + "/data";
  http.begin(client, url);
  http.addHeader("Content-Type", "application/json");

  String body = "{\"soil_value\":" + String(soilValue) + ", \"soil_percent\":" + String(moisturePercent) + "}";
  int httpCode = http.POST(body);

  if (httpCode > 0) {
    String response = http.getString();
    Serial.println("Soil Data Response: " + response);
  } else {
    Serial.println("Failed to send soil data");
  }

  http.end();
}


void sendRelayCommand(int state) {
  WiFiClient client;
  HTTPClient http;

  String url = serverHost + "/api/relays/" + String(deviceRelayId) + "/channel/" + String(relayChannel);
  http.begin(client, url);
  http.addHeader("Content-Type", "application/json");

  String body = "{\"state\":" + String(state) + "}";
  int httpCode = http.POST(body);

  if (httpCode > 0) {
    String response = http.getString();
    Serial.println("Relay Response: " + response);
  } else {
    Serial.println("Failed to send relay command");
  }

  http.end();
}

String getJsonValue(String json, String key) {
  int keyIndex = json.indexOf("\"" + key + "\"");
  if (keyIndex == -1) return "";
  int colonIndex = json.indexOf(":", keyIndex);
  int commaIndex = json.indexOf(",", colonIndex);
  int endIndex = (commaIndex == -1) ? json.indexOf("}", colonIndex) : commaIndex;
  String value = json.substring(colonIndex + 1, endIndex);
  value.trim();
  value.replace("\"", "");
  return value;
}
