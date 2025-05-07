#include <WiFi.h>
#include <HTTPClient.h>
#include <Adafruit_Fingerprint.h>
#include <HardwareSerial.h>

// ==== CONFIGURA TU WIFI ====
const char* ssid     = "INFINITUMCBB0";
const char* password = "2xVTMTmeN2";

// ==== URL DEL PHP ====
const char* server = "http://192.168.1.68/ClassTrack/api/verificar_y_registrar.php";
const int   id_salon = 1;  // Cambia esto si usas otro salón

// ==== UART PINS PARA ESP32-S3 ====
#define RXD2 18
#define TXD2 17

HardwareSerial mySerial(1); // UART1
Adafruit_Fingerprint finger(&mySerial);

void setup() {
  Serial.begin(115200);
  delay(500);

  // Conectar a WiFi
  WiFi.begin(ssid, password);
  Serial.print("🔌 Conectando WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\n✅ WiFi conectado");

  // Iniciar lector de huellas
  mySerial.begin(57600, SERIAL_8N1, RXD2, TXD2);
  finger.begin(57600);

  if (finger.verifyPassword()) {
    Serial.println("✅ Sensor de huellas listo");
  } else {
    Serial.println("❌ Sensor de huellas NO encontrado. Verifica conexiones.");
    while (1) delay(1000);
  }
}

void loop() {
  Serial.println("👉 Coloca tu dedo...");
  int id = getFingerprintID();
  if (id > 0) {
    Serial.println("⌛ Esperando para evitar doble lectura...");
    delay(3000); // Evita múltiples lecturas seguidas
  }
}

// === FUNCIONES ===

int getFingerprintID() {
  uint8_t p = finger.getImage();
  if (p != FINGERPRINT_OK) return 0;

  p = finger.image2Tz();
  if (p != FINGERPRINT_OK) return 0;

  p = finger.fingerSearch();
  if (p != FINGERPRINT_OK) {
    Serial.println("❌ Huella no encontrada en base de datos");
    return 0;
  }

  int id = finger.fingerID;
  Serial.print("✅ Huella detectada con ID: ");
  Serial.println(id);

  sendToServer(id);
  return id;
}

void sendToServer(int id_huella) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("❌ WiFi desconectado");
    return;
  }

  HTTPClient http;
  http.begin(server);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  String data = "id_huella=" + String(id_huella) + "&id_salon=" + String(id_salon);

  int httpResponseCode = http.POST(data);

  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("📡 Respuesta del servidor:");
    Serial.println(response);
  } else {
    Serial.print("❌ Error HTTP: ");
    Serial.println(httpResponseCode);
  }

  http.end();
}