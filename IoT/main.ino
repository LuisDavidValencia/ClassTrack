#include <WiFi.h>
#include <HTTPClient.h>
#include <Adafruit_Fingerprint.h>
#include <HardwareSerial.h>

// ==== CONFIG ====
const char* ssid     = "TU_SSID";
const char* password = "TU_PSW";
const char* server   = "http://192.168.1.68/universidad_api/verificar_y_registrar.php";
const int   id_salon = 1;

// Pines recomendados para UART en ESP32-S3
#define RXD2 18  // Cambia si es necesario según tu conexión
#define TXD2 17

HardwareSerial mySerial(1); // Serial1 para UART

Adafruit_Fingerprint finger(&mySerial);

void setup() {
  Serial.begin(115200);
  delay(500);

  // WiFi
  WiFi.begin(ssid, password);
  Serial.print("Conectando WiFi");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\n✅ WiFi conectado");

  // Inicia comunicación con el lector de huellas
  mySerial.begin(57600, SERIAL_8N1, RXD2, TXD2);
  finger.begin(57600);
  if (finger.verifyPassword()) {
    Serial.println("✅ Sensor de huellas listo");
  } else {
    Serial.println("❌ Sensor de huellas NO encontrado");
    while (1);
  }
}

void loop() {
  Serial.println("👉 Coloca el dedo...");
  if (getFingerprintID() > 0) delay(2000);
}

uint8_t getFingerprintID() {
  uint8_t p = finger.getImage();
  if (p != FINGERPRINT_OK) return 0;
  p = finger.image2Tz();
  if (p != FINGERPRINT_OK) return 0;
  p = finger.fingerSearch();
  if (p != FINGERPRINT_OK) {
    Serial.println("❌ Huella no encontrada");
    return 0;
  }

  int id = finger.fingerID;
  Serial.print("✅ Huella ID detectada: ");
  Serial.println(id);
  sendToServer(id);
  return id;
}

void sendToServer(int id_h) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("❌ WiFi desconectado");
    return;
  }

  HTTPClient http;
  http.begin(server);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  String data = "id_huella=" + String(id_h) + "&id_salon=" + String(id_salon);

  int code = http.POST(data);
  if (code > 0) {
    String resp = http.getString();
    Serial.println("📡 Respuesta del servidor: " + resp);
  } else {
    Serial.println("❌ Error en envío: " + String(code));
  }
  http.end();
}
