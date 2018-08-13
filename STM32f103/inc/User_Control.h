/*
Button 1 : mo cua
Button 2 : tat bao dong
*/

#ifndef __USER_CONTROL_H
#define __USER_CONTROL_H

#include "User_USART2.h"
#include "User_USART3.h"
#include "stm32f10x.h"
#include "delay.h"
#include "User_TIM.h"
#include "tm_stm32f10x_mfrc522.h"
#include "User_FLASH.h"
#include "User_GPIO.h"
#include <string.h>
#include <stdio.h>
#include <stdarg.h>
#include <stdlib.h>
#include <ctype.h>

/*==================================*/
#define ID_NOT_FOUND -1

//angle for servo close or open door
#define SERVO_ANGLE_CLOSE 90
#define SERVO_ANGLE_OPEN	-90

//status of door (open or close)
#define CLOSE 0
#define OPEN 1

//define page
#define PAGE0 0
#define PAGE1 1
#define PAGE2 2
#define PAGE3 3
#define PAGE4 4

#define LENGTH_ID 5

#define REMOVE 0
#define ADD 1

/*========Variable==============*/

// page 0
extern uint16_t Emer_flag; //emergency case occure
extern uint16_t Nbr_ID; //number of ID
/*
*	If One touch mode turn on, evertime you press button, door will be opened without requiring password.
*/
extern uint8_t OneTouch; //one touch to open door
extern uint16_t password[31]; //password for user


extern volatile uint8_t Enter_pass_remove_alert;//flag allow enter password to remove alert

extern uint8_t Door_status;//door's status (open or close)


extern char TempBuff[100];

extern volatile uint16_t buff_pos;
extern volatile uint16_t buff[100];
extern volatile uint8_t receive_flag;

extern uint8_t ScanedID[5];

extern volatile uint8_t AddMember_flag;

extern volatile uint8_t RemoveMember_flag;

extern volatile uint8_t ChangePassword_flag;

extern volatile uint8_t OneTouchMode_flag;

extern volatile uint8_t OpenDoorUSART_flag;

//==============================esp8266=====================
#define CAN_NOT_CONVERT -1

//define command to esp
#define PUSH_ID '0' //enable push id
#define CHECK_DOOR '1' //enable check door
#define WRITE_CLOSE_DOOR '2' //enable write "CLOSE" to file
#define CHECK_SQL '3' //enable check sql
#define RESET_FILE_SQL '4' //enable write "" to file
#define CONNECT_WIFI '5' //enable connect to wifi
#define CHECK_WIFI '6' //enable check wifi's connection
#define GET_SQL_STATUS '7' //cmd usart
#define GET_DOOR_STATUS '8' //cmd usart

extern uint8_t Update_Door_Flag;

extern uint8_t Update_Sql_Flag;


int Convert_Char_Hex_To_Dec(char ch);
int Convert_String_Hex_To_Dec(char *str);

char *Convert_Dec_To_String_Hex(uint8_t num);

char *Convert_Id_To_String(uint8_t *Id);
uint8_t *Convert_String_To_Id(char *str);

extern const char* SSID;
extern const char* PASS;

void Connect_Wifi(char *id, char *password);
uint8_t Check_Wifi(void);

void Push_Id(uint8_t* Id);

void Update_Door_Status(void); //get door status on server end store in esp
/*get door status
*return OPEN or CLOSE
*/
uint8_t Get_Door_Status(void); 
void Write_Close_Door(void);

void Update_Sql_Status(void);
char* Get_Sql_Status(void);
void Write_None_Sql(void);

/*
*	used for parsed ID form esp
* Att: REMOVE or ADD
* content ID[LENGTH_ID]
*/
struct ID_PACKAGE {
	uint8_t _Att; //attribute [REMOVE] or [ADD]
	uint8_t _ID[LENGTH_ID];
};

/*
* Parse the string content [Attribute]ID
* Start_Pos is the start position in string to parse
* return struct ID_PACKAGE
*/

struct ID_PACKAGE* Parse_Id(char* String_Id, uint16_t Start_Pos);

void Process_Id_From_Esp(void);

uint8_t ESP_Available(void);

#define COUNT_ESP_MAX 1000

extern uint8_t Prevent_Increase_Count_Waite_Esp;
extern uint16_t Count_Wait_Esp; //this variable will count times esp busy. if it reach COUNT_ESP_MAX it won't wait

//======================printf()====================

/*--------------------printf()-----------------------------*/
/* We need to implement own __FILE struct */
/* FILE struct is used from __FILE */
struct __FILE {
    int dummy;
};
 
/* need this if want use printf */
/* Struct FILE is implemented in stdio.h */

static FILE __stdout;

int fputc(int ch, FILE *f);

//===================convert data=================
uint16_t* ConvertCharToUint16(char *s);
char* ConvertUint16ToChar(uint16_t* s);
char* ConvertUint8ToChar(uint8_t* s);
uint8_t* ConvertCharToUint8(char *s);
uint8_t* ConvertUint16ToUint8(uint16_t *s);

//===================PAGE 0==================
/*
- Read and Write (emergency flag; number of ID; one touch mode; password) to flash
*/
void Write_EmerFlag(uint16_t E_flag);
void Updata_EmerFlag(void);

void Write_NbrID(uint16_t Nbr);
void Update_NbrID(void);

void Write_OneTouchMode(uint16_t One_status);
void Update_OneTouchMode(void);

void Write_Password(uint16_t* pass);
void Update_Password(void);
//write all value in page 0
void Write_Page0(uint16_t E_flag, uint16_t Nbr, uint16_t One_status, uint16_t* pass);

void Updata_Data_From_PAGE0(void);

extern void Display_PAGE0(void);

//=======================READ and WRITE ID function prototype====================

/*
*	each pos will content 5 uint16_t variable (10 bytes) <=> 5pos in flash
*/
uint8_t* Read_Id_From_Page (uint8_t Pos, uint8_t Page);
void Write_Id_To_Page (uint8_t* Id, uint8_t Pos, uint8_t Page);

//=======================Compare 2 ID=======================
uint8_t Compare_Id (uint8_t* ID1, uint8_t* ID2);

//=======================PAGE 1 2 3====================

// SIZE: 2bytes/ID[index]; 10bytes/ID <=> 5pos/ID
/*
-	Add new ID[5] into flash
*/
void Add_Id (uint8_t* In_Id);
/*
-	compare InputID[5] vs list ID in flash
- return -1 as not found, else this is ID'index in flash
*/
int16_t Search_Id (uint8_t* In_Id);
/*
-	pick a ID[5] from flash
- return Null if not found
*/
uint8_t* Pick_Id (uint16_t index);
/*
- remove a ID from flash base on index
*/
void Remove_Id(uint16_t index);

/*-----------------For all function above--------------*/
void Add_Mem_Procedure(void);
void Add_NewMem(uint8_t* InID);
void Remove_Mem_Procedure(void);
void Remove_Mem(uint8_t index);
void Display_ID(uint8_t index);

/*
close and open door
*/
void CloseDoor(void);
void OpenDoor(void);

/*----------------------------
*	Turn ON or OFF LED close
*/
void Turn_led_close(uint8_t status);

/*------------------------
*	Turn ON or OFF LED open
*/
void Turn_led_open(uint8_t status);

/*-----------------------------
*	Turn ON or OFF BUZZ close
*/
void Turn_buzz(uint8_t status);

/*
check alert
return TRUE if alert occure
			FALSE for opposite
*/
uint8_t CheckAlert (void);

/*
check door's status base on switch
*/
uint8_t CheckDoorStatus(void);

/*
Alert
*/
void Alert(void);

/*
reset a array uint16_t to '\0', length
*/
void ResetArr(uint16_t* arr, uint32_t leng);

/*
compare password
*/
uint8_t ComparePassword(uint16_t* InputPass);

/*
require password
return true if right password
return false if wrong password
*/
uint8_t RequirePassword(void);

/*
check scaned ID vs list of ID
*/
uint8_t CheckScanedID(uint8_t* IDCheck);

/*
Display the option menu
*/
void DisplayMenu(void);

/*
compare 2 string uint16_t* vs char*
*/
uint8_t CompareUintChar(uint16_t* s1, char* s2, uint8_t leng);

/*
Change password current
*/
void ChangePass(void);

/*
turn on or off One touch mode
*/
void TurnOneTouchMode(void);

/*
Receive interrupt usart2
*/
void USART2_IRQHandler(void);

#endif /*__USER_CONTROL_H */
/*******END OF FILE****/
