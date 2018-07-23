/*
 * Nhan lenh tu stm:
 * '0' push id to server
*/

#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>

/*
 * GPIO pin
 * ON: esp available
 * OFF: esp busy
*/
#define AVAILABLE_PIN 15

//define bit flag
#define PUSH_ID_BIT 0 //push id to server
#define CHECK_DOOR_BIT 1 //check status door file in server
#define WRITE_CLOSE_DOOR_BIT 2 //write "CLOSE" to door_status in server
#define CHECK_SQL_BIT 3 //check status sql file in server
#define RESET_FILE_SQL_BIT 4 //write "" to sql_status in server
#define CONNECT_WIFI_BIT 5 //connect to wifi
#define CHECK_WIFI_BIT 6 //check wifi's connection
#define GET_SQL_STATUS_BIT 7 //check sql_status variable (String class)
#define GET_DOOR_STATUS_BIT 8 //check door_status variable (String class)

#define ABLE_WRITE_NONE_SQL_BIT 9 //if isset => able to write "" to sql_status file in server

//define command from stm
#define PUSH_ID '0' //enable push id
#define CHECK_DOOR '1' //enable check door
#define WRITE_CLOSE_DOOR '2' //enable write "CLOSE" to file
#define CHECK_SQL '3' //enable check sql
#define RESET_FILE_SQL '4' //enable write "" to file
#define CONNECT_WIFI '5' //enable connect to wifi
#define CHECK_WIFI '6' //enable check wifi's connection
#define GET_SQL_STATUS '7' //cmd usart
#define GET_DOOR_STATUS '8' //cmd usart

//host to send data
const char* Host_Push_Id_To_Server= "http://leeceecclub.000webhostapp.com/Receive_Id_From_Esp.php";

//host for read file from server
const char* Host_Read_File= "http://leeceecclub.000webhostapp.com/Read_File.php";

//host for write file
const char* Host_Write_File= "http://leeceecclub.000webhostapp.com/Write_File.php";

/*
 * status door file's name
 * this will content OPEN or CLOSE
*/
const char* Status_Door_File = "status_door";

const char* Status_Sql_File = "status_sql";

String door_status="CLOSE.";
String sql_status="NONE.";

//Number of retry when error
uint8_t NbrRetry=0;

uint16_t Flag=0x0000;
/*
 * bit 0: push id to server flag
 * bit 1: door status
 * bit 2: write "CLOSE" to door_status
 * bit 3: check sql status
 * bit 4: write "" to sql_status
 * bit 5: connect to wifi
 * bit 6: check wifi's connection
 * bit 7: get sql'status
 * bit 8: get door'status
 * bit 9: esp available
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
/*
 * Send "READY." start
 * check the status_door file
 * if content OPEN send OPEN. to stm
 * if content CLOSE send CLOSE. to stm
 * if fail will retry 3 times before send ERROR.
*/
void ProcessCheckDoor();
/*
 * Send "READY." start
 * Write "CLOSE" to door_status file server
 * Send "OK." or "ERROR."
 */
void WriteCloseDoor();
/*
 * Send "READY." start
 * check the status_sql file
 * status_sql's structure: [attribute]ID[attribute]ID....
 * Ex: [A]HGUTYRTGHD[R]URYTHHFNGH
 * Explain: A stand for added ID
 *          R stand for removed ID
 * if file no content send NONE. to stm
 * Send [attribute]ID[attribute]ID.
 * if fail will retry 3 times before send ERROR.
 */
void ProcessCheckSql();
/*
 * Send "READY." start
 * Write "" (None string) to sql_status file in server
 * Send "OK." or "ERROR."
 */
void WriteNoneSql();

/*
 * Send "SSID." to receive ssid
 * Send "PASS." to receive password
 * Send "OK." or "ERROR."
 */
void ConnectWifi();
/*
 * Send "OK." or "ERROR."
 */
void CheckConnectWifi();
/*
 * Send "NONE." or "[A]ID[R]ID."
 */
void GetSqlStatus();
/*
 * Send "OPEN." or "CLOSE."
 */
void GetDoorStatus();

void setup() {
  Serial.begin(115200);
  pinMode(AVAILABLE_PIN,OUTPUT);
}

void loop() {
  if (Serial.isRxEnabled()){
    ProcessCmd();
  }
  if(bitRead(Flag,PUSH_ID_BIT)) {
    digitalWrite(AVAILABLE_PIN,LOW); //busy
    ProcessPushID();
    bitClear(Flag,PUSH_ID_BIT);
  }
  if(bitRead(Flag,CHECK_DOOR_BIT)) {
    digitalWrite(AVAILABLE_PIN,LOW); //busy
    ProcessCheckDoor();
    bitClear(Flag,CHECK_DOOR_BIT);
  }
  if(bitRead(Flag,CHECK_SQL_BIT)) {
    digitalWrite(AVAILABLE_PIN,LOW); //busy
    ProcessCheckSql();
    bitClear(Flag,CHECK_SQL_BIT);
  }
  if(bitRead(Flag,WRITE_CLOSE_DOOR_BIT)) {
    digitalWrite(AVAILABLE_PIN,LOW); //busy
    WriteCloseDoor();
    bitClear(Flag,WRITE_CLOSE_DOOR_BIT);
  }
  if(bitRead(Flag,RESET_FILE_SQL_BIT)) {
    digitalWrite(AVAILABLE_PIN,LOW); //busy
    WriteNoneSql();
    bitClear(Flag,RESET_FILE_SQL_BIT);
  }
  if(bitRead(Flag,CONNECT_WIFI_BIT)) {
    digitalWrite(AVAILABLE_PIN,LOW); //busy
    ConnectWifi();
    bitClear(Flag,CONNECT_WIFI_BIT);
  }
  if(bitRead(Flag,CHECK_WIFI_BIT)) {
    digitalWrite(AVAILABLE_PIN,LOW); //busy
    CheckConnectWifi();
    bitClear(Flag,CHECK_WIFI_BIT);
  }
  if(bitRead(Flag,GET_SQL_STATUS_BIT)) {
    digitalWrite(AVAILABLE_PIN,LOW); //busy
    GetSqlStatus();
    bitClear(Flag,GET_SQL_STATUS_BIT);
  }
  if(bitRead(Flag,GET_DOOR_STATUS_BIT)) {
    digitalWrite(AVAILABLE_PIN,LOW); //busy
    GetDoorStatus();
    bitClear(Flag,GET_DOOR_STATUS_BIT);
  }
  digitalWrite(AVAILABLE_PIN,HIGH); //available
}

void ProcessCmd(void) {
  if(Serial.available()) {
    switch(Serial.read()) {
      case PUSH_ID:
        bitSet(Flag,PUSH_ID_BIT);
        break;
      case CHECK_DOOR:
        bitSet(Flag,CHECK_DOOR_BIT);
        break;
      case CHECK_SQL:
        bitSet(Flag,CHECK_SQL_BIT);
        break;
      case WRITE_CLOSE_DOOR:
        bitSet(Flag,WRITE_CLOSE_DOOR_BIT);
        break;
      case RESET_FILE_SQL:
        bitSet(Flag,RESET_FILE_SQL_BIT);
        break;
      case CONNECT_WIFI:
        bitSet(Flag,CONNECT_WIFI_BIT);
        break;
      case CHECK_WIFI:
        bitSet(Flag,CHECK_WIFI_BIT);
        break;
      case GET_SQL_STATUS:
        bitSet(Flag,GET_SQL_STATUS_BIT);
        break;
      case GET_DOOR_STATUS:
        bitSet(Flag,GET_DOOR_STATUS_BIT);
        break;
    }
  }
}

void ProcessPushID(void) {
  Serial.print("READY.");
  ReceiveString(10,'.');
  NbrRetry=0;
  while(NbrRetry<=2) { //try to send 3 times if fail
    HTTPClient http;
    http.begin(Host_Push_Id_To_Server);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    int httpCode=http.POST(String("ID=")+InString);
    String payload=http.getString();
    http.end();
    //if send successful
    if(httpCode==200 && payload =="OK") {
      NbrRetry=0;
      Serial.print("OK.");
      return;
    }
    else {
      NbrRetry++;
    }
  }
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

void ProcessCheckDoor() {
  Serial.print("READY.");
  NbrRetry=0;
  while(NbrRetry<=2) { //try to send 2 times if fail
    HTTPClient http;
    http.begin(Host_Read_File);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    int httpCode=http.POST(String("FILENAME=")+Status_Door_File);
    String payload=http.getString();
    http.end();
    //if send successful
    if(httpCode==200 && (payload =="OPEN" || payload =="CLOSE")) {
      NbrRetry=0;
      door_status=payload+'.';
      Serial.print(payload+'.');
      return;
    }
    else {
      NbrRetry++;
    }
  }
  NbrRetry=0;
  door_status="CLOSE."; //prevent no connection to server
  Serial.print("ERROR.");
}

void WriteCloseDoor() {
  Serial.print("READY.");
  NbrRetry=0;
  while(NbrRetry<=2) { //try to send 2 times if fail
    HTTPClient http;
    http.begin(Host_Write_File);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    int httpCode=http.POST(String("FILENAME=")+Status_Door_File+String("&CONTENT=CLOSE"));
    http.end();
    //if send successful
    if(httpCode==200) {
      door_status="CLOSE."; //incase stm want to read immediately
      NbrRetry=0;
      Serial.print("OK.");
      return;
    }
    else {
      NbrRetry++;
    }
  }
  NbrRetry=0;
  door_status="CLOSE."; //prevent no connection to server
  Serial.print("ERROR.");
}

void ProcessCheckSql() {
  Serial.print("READY.");
  NbrRetry=0;
  while(NbrRetry<=2) { //try to send 2 times if fail
    HTTPClient http;
    http.begin(Host_Read_File);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    int httpCode=http.POST(String("FILENAME=")+Status_Sql_File);
    String payload=http.getString();
    http.end();
    //if send successful
    if(httpCode==200) {
      NbrRetry=0;
      if(payload.length()==0) {
        sql_status="NONE."; //incase stm want to get data immediately
        Serial.print("NONE.");
      }
      else {
        sql_status=payload+'.'; //incase stm want to get data immediately
        Serial.print(payload+'.');
        bitSet(Flag,ABLE_WRITE_NONE_SQL_BIT); //neu qua trinh thanh cong thi co the write "" to sql_status in server
      }
      return;
    }
    else {
      NbrRetry++;
    }
  }
  NbrRetry=0;
  bitClear(Flag,ABLE_WRITE_NONE_SQL_BIT); //fail => prevent write "" to sql_status
  sql_status="NONE."; //prevent no connection to server
  Serial.print("ERROR.");
}

void WriteNoneSql() {
  Serial.print("READY.");
  NbrRetry=0;
  while(NbrRetry<=2 && bitRead(Flag,ABLE_WRITE_NONE_SQL_BIT)) { //try to send 2 times if fail and if able to write
    HTTPClient http;
    http.begin(Host_Write_File);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    int httpCode=http.POST(String("FILENAME=")+Status_Sql_File); //content="" none string
    http.end();
    //if send successful
    if(httpCode==200) {
      NbrRetry=0;
      Serial.print("OK.");
      return;
    }
    else {
      NbrRetry++;
    }
  }
  NbrRetry=0;
  Serial.print("ERROR.");
}

void ConnectWifi (void) {
  Serial.print("SSID.");
  ReceiveString(50,'.');
  char id[50]={};
  for(int i=0; i<InString.length(); i++) {
    id[i]=(char)InString[i];
  }
  Serial.print("PASS.");
  ReceiveString(50,'.');
  char pass[50]={};
  for(int i=0; i<InString.length(); i++) {
    pass[i]=(char)InString[i];
  }
  WiFi.begin(id, pass);
  delay(5000);
  if(WiFi.status() != WL_CONNECTED) {
    Serial.print("ERROR.");
  }
  else {
    Serial.print("OK.");
  }
}

void CheckConnectWifi(void) {
  if(WiFi.status() != WL_CONNECTED) {
    Serial.print("ERROR.");
  }
  else {
    Serial.print("OK.");
  }
}

void GetSqlStatus(void) {
  Serial.print(sql_status);
}

void GetDoorStatus(void) {
  Serial.print(door_status);
}





