#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

const char* ssid = "tselhome_B55C";
const char* password = "egm35bTtM8A";

String serverName = "http://192.168.8.100/api/relays/5";

int relayPins[4] = {D1, D2, D5, D6};

void setup() {
  Serial.begin(115200);

  for (int i = 0; i < 4; i++) {
    pinMode(relayPins[i], OUTPUT);
    digitalWrite(relayPins[i], HIGH);
  }

  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected!");
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;

    http.begin(client, serverName);
    int httpCode = http.GET();

    if (httpCode == 200) {
      String payload = http.getString();
      Serial.println(payload);

      // contoh respon {"channels":[1,0,1,0]}
      int states[4];
      int index = 0;
      int pos = payload.indexOf("[");
      int end = payload.indexOf("]");
      String arr = payload.substring(pos + 1, end);
      
      for (int i = 0; i < arr.length(); i++) {
        if (arr.charAt(i) == ',' || i == arr.length() - 1) {
          String val = arr.substring(index, (i == arr.length() - 1) ? i + 1 : i);
          states[(index == 0 ? 0 : index / 2)] = val.toInt();
          index = i + 1;
        }
      }

      for (int i = 0; i < 4; i++) {
        if (states[i] == 1) {
          digitalWrite(relayPins[i], LOW); // ON
        } else {
          digitalWrite(relayPins[i], HIGH); // OFF
        }
      }
    }

    http.end();
  }
  delay(1000); 
}
