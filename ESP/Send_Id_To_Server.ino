/*
 * Nhan lenh tu stm:
 * '0' push id to server
*/

#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>

//define bit flag
#define PUSH_ID_BIT 0 //push id to server

//define command from stm
#define PUSH_ID '0' //enable push id


// WiFi parameters
const char* ssid = "Baby I'm unreal";//"UIT-Guest";
const char* password = "417417417";//"motdenmuoi1";

//host to send data
const char* Host_Push_Id_To_Server= "http://leeceecclub.000webhostapp.com/Receive_Id_From_Esp.php";

//Number of retry when error
uint8_t NbrRetry=0;

uint8_t Flag=0x00;
/*
 * bit 0: push id to server flag
 * bit 1:
 * bit 2:
 * bit 3:
 * bit 4:
 * bit 5:
 * bit 6:
 * bit 7:
*/

/*
 * process command from stm
*/
void ProcessCmd(void);
/*
 * Push ID to server
*/
void ProcessPushID(void);
/*
 * Send "READY." start
 * receive a string from stm
 * storage in InString(200 max leng)
 * Send "OK." or "ERROR." ket thuc
*/
String InString="";
void ReceiveString(uint8_t leng, char delim);

void setup() {
  Serial.begin(115200);
  // We start by connecting to a WiFi network
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  if (Serial.isRxEnabled()){
    ProcessCmd();
  }
  if(bitRead(Flag,PUSH_ID_BIT)) {
    ProcessPushID();
  }
}

void ProcessCmd(void) {
  if(Serial.available()) {
    switch(Serial.read()) {
      case PUSH_ID:
        bitSet(Flag,PUSH_ID_BIT);
        break;
    }
  }
}

void ProcessPushID(void) {
  Serial.print("READY.");
  ReceiveString(10,'.');
  while(NbrRetry<=10) { //try to send 10 times if fail
    HTTPClient http;
    http.begin(Host_Push_Id_To_Server);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    int httpCode=http.POST(String("ID=")+InString);
    String payload=http.getString();
    http.end();
    //if send successful
    if(httpCode==200 && payload =="OK") {
      bitClear(Flag,PUSH_ID_BIT); //disable
      NbrRetry=0;
      Serial.print("OK.");
      return;
    }
    else {
      NbrRetry++;
    }
  }
  bitClear(Flag,PUSH_ID_BIT); //disable
  NbrRetry=0;
  Serial.print("ERROR.");
}

void ReceiveString(uint8_t leng, char delim) {
  InString="";
  while (InString.length() < leng) { //until reach enough length or buff[lastest byte]==delim
    if (Serial.isRxEnabled()){
      if(Serial.available()){
        char InChar=Serial.read();
        if(InChar==delim) return;
        InString+=InChar;
      }
    }
  }
}

