#include <ESP8266WiFi.h>

int sensorID = 0;
String hostname = "node-" + String(sensorID);

// WiFi Settings
#ifndef STASSID
#define STASSID "ssid"
#define STAPSK "password"
#endif

const char* ssid = STASSID;
const char* password = STAPSK;

// Webserver Settings
int    HTTP_PORT      = 80;
String HTTP_METHOD    = "GET";
String HOST_NAME      = "<ip>";
String TEMP_PATH_NAME = "/net-sense/temp.php";

void SetUpWiFi()
{
  // We start by connecting to a WiFi network
  Serial.print("Connecting to ");
  Serial.println(ssid);
  /* Explicitly set the ESP8266 to be a WiFi-client, otherwise, it by default,
     would try to act as both a client and an access-point and could cause
     network-issues with your other WiFi-devices on your WiFi-network. */
  WiFi.mode(WIFI_STA);
  WiFi.hostname(hostname);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());

  // WiFi.mode(WIFI_OFF); // deactivate WiFi but keep credentials
  // WiFi.mode(WIFI_STA); // activate WiFi
  // WiFi.begin();
}

void sendDataToWebserver(String path, double value)
{
  String queryString = "?sensor_id=" + String(sensorID) +  "&value=" + String(value);
  WiFiClient client;
  // connect to web server on port 80:
  if(client.connect(HOST_NAME, HTTP_PORT)) 
  {
    // if connected:
    Serial.print("Connected to server: ");
    Serial.println(HOST_NAME + path + queryString);
    // make a HTTP request:
    // send HTTP header
    client.println(HTTP_METHOD + " " + path + queryString + " HTTP/1.1");
    client.println("Host: " + String(HOST_NAME));
    client.println("Connection: close");
    client.println(); // end HTTP header

    while(client.connected()) {
      if(client.available()){
        // read an incoming byte from the server and print it to serial monitor:
        char c = client.read();
        Serial.print(c);
      }
    }
    // the server's disconnected, stop the client:
    client.stop();
    Serial.println("Disconnected.");
  }
  else // if not connected:
  {
    Serial.println("connection failed");
  }
}


void setup() {
  Serial.begin(9600);
  SetUpWiFi();
  sendDataToWebserver(TEMP_PATH_NAME, random(15,25));
  ESP.deepSleep(60e6);
}

void loop() {
  // Nothing because after deepSleep a restart happens
}
