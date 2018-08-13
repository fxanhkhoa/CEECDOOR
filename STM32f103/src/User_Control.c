#include "User_Control.h"

uint16_t Emer_flag=FALSE; //emergency case occure
uint16_t Nbr_ID=0; //number of ID
uint8_t OneTouch=FALSE; //one touch to open door
uint16_t password[31]={};

volatile uint8_t Enter_pass_remove_alert=FALSE;//flag allow enter password to remove alert

uint8_t Door_status=OPEN;//preveous door's status (open or close)

char TempBuff[100];

volatile uint16_t buff_pos=0;
volatile uint16_t buff[100]={};
volatile uint8_t receive_flag=FALSE;

uint8_t ScanedID[5]={0x00,0x00,0x00,0x00,0x00};

volatile uint8_t AddMember_flag=FALSE;

volatile uint8_t RemoveMember_flag=FALSE;

volatile uint8_t ChangePassword_flag=FALSE;

volatile uint8_t OneTouchMode_flag=FALSE;

volatile uint8_t OpenDoorUSART_flag=FALSE;

//==============================esp8266=====================
uint8_t Prevent_Increase_Count_Waite_Esp=FALSE;
uint16_t Count_Wait_Esp=0;

uint8_t Update_Door_Flag=FALSE;

uint8_t Update_Sql_Flag=FALSE;

int Convert_Char_Hex_To_Dec(char ch) {
	switch(ch) {
		case '0':
			return 0;
			break;
		case '1':
			return 1;
			break;
		case '2':
			return 2;
			break;
		case '3':
			return 3;
			break;
		case '4':
			return 4;
			break;
		case '5':
			return 5;
			break;
		case '6':
			return 6;
			break;
		case '7':
			return 7;
			break;
		case '8':
			return 8;
			break;
		case '9':
			return 9;
			break;
		case 'A':
		case 'a':
			return 10;
			break;
		case 'B':
		case 'b':
			return 11;
			break;
		case 'C':
		case 'c':
			return 12;
			break;
		case 'D':
		case 'd':
			return 13;
			break;
		case 'E':
		case 'e':
			return 14;
			break;
		case 'F':
		case 'f':
			return 15;
			break;
	}
	return CAN_NOT_CONVERT;
}

int Convert_String_Hex_To_Dec(char *str) {
	if(Convert_Char_Hex_To_Dec(str[0])==CAN_NOT_CONVERT || Convert_Char_Hex_To_Dec(str[1])==CAN_NOT_CONVERT)
		return CAN_NOT_CONVERT;
	return Convert_Char_Hex_To_Dec(str[0])*16 + Convert_Char_Hex_To_Dec(str[1]);
}
	
char *Convert_Dec_To_String_Hex(uint8_t num) {
	static char ret[3];
	sprintf(ret,"%02X",num);
	return ret;
}

char *Convert_Id_To_String(uint8_t *Id) {
	/*
	* 2 position in char* for a ID
	* 1 lastest pos for end of string
	*/
	static char ret[LENGTH_ID*2+1];
	uint8_t i;
	for(i=0; i<11; i++){ //reset string
		ret[i]=0x00;
	}
	for(i=0; i<LENGTH_ID; i++) { //concatenate to return
		strcat(ret,Convert_Dec_To_String_Hex(Id[i]));
	}
	return ret;
}
uint8_t *Convert_String_To_Id(char *str) {
	static uint8_t ret[LENGTH_ID];
	uint8_t i;
	char temp[3];
	for(i=0; i<LENGTH_ID*2; i+=2) { //2 position in string for a ID[index]
		temp[0]=str[i];
		temp[1]=str[i+1];
		temp[2]=0x00; //end of string
		ret[i/2]=Convert_String_Hex_To_Dec(temp);
	}
	return ret;
}

const char* SSID="UIT_Guest.";
const char* PASS="1denmuoi1.";

void Connect_Wifi(char *id, char *password) {
	User_USART3_SendChar(CONNECT_WIFI);
	User_USART3_ReceiveString(TempBuff,'.',50);
	if(strcmp("SSID",TempBuff)!=0) return;
	printf(id);
	User_USART3_ReceiveString(TempBuff,'.',50);
	if(strcmp("PASS",TempBuff)!=0) return;
	printf(password);
}
uint8_t Check_Wifi(void) {
	User_USART3_SendChar('6');
	User_USART3_ReceiveString(TempBuff,'.',50);
	if(strcmp(TempBuff,"OK")==0) {
		return TRUE;
	}
	if(strcmp(TempBuff,"ERROR")==0) return FALSE;
	return FALSE;
}

void Push_Id(uint8_t* Id) {
	User_USART3_SendChar(PUSH_ID);
	User_USART3_ReceiveString(TempBuff,'.',10);
	if(strcmp("READY",TempBuff)!=0) return;// khong khop
	char Str_Push[LENGTH_ID*2+2]={};
	strcat(Str_Push,Convert_Id_To_String(Id));
	Str_Push[LENGTH_ID*2]='.';
	Str_Push[LENGTH_ID*2+1]=0x00;
	printf(Str_Push);
}

/*-------------------Door control----------------*/
void Update_Door_Status(void) {
	User_USART3_SendChar(CHECK_DOOR);
}
uint8_t Get_Door_Status(void) {
	User_USART3_SendChar(GET_DOOR_STATUS);
	User_USART3_ReceiveString(TempBuff,'.',10);
	if(strcmp("CLOSE",TempBuff)==0) return CLOSE;
	else if(strcmp("OPEN",TempBuff)==0) return OPEN;
	return CLOSE;
}
void Write_Close_Door(void) {
	User_USART3_SendChar(WRITE_CLOSE_DOOR);
}

/*------------------------Sql----------------------*/
void Update_Sql_Status(void) {
	User_USART3_SendChar(CHECK_SQL);
}
char* Get_Sql_Status(void) {
	static char RX_Buff[LENGTH_ID*100+100]; //spend 100 pos for ID and 100 for attribute [R] or [A]
	ResetArr(RX_Buff,LENGTH_ID*100+100);
	User_USART3_SendChar(GET_SQL_STATUS);
	User_USART3_ReceiveString(RX_Buff,'.',LENGTH_ID*100+100);
	return RX_Buff;
}
void Write_None_Sql(void) {
	User_USART3_SendChar(RESET_FILE_SQL);
}



struct ID_PACKAGE* Parse_Id(char* String_Id, uint16_t Start_Pos) {
	struct ID_PACKAGE* ret=(struct ID_PACKAGE*)malloc(sizeof(struct ID_PACKAGE));
	//start parse
	if(String_Id[Start_Pos]=='[' && 
		(String_Id[Start_Pos+1]=='A' || String_Id[Start_Pos+1]=='R') &&
		String_Id[Start_Pos+2]==']'
		) { //check attribute [R] or [A]
		
		//first parse arrtribute
		if(String_Id[Start_Pos+1]=='A') {
			ret->_Att=ADD;
		}
		else ret->_Att=REMOVE;
		//parse ID next
		uint8_t i;
		char Id_String[3];
		int16_t Id_Index;
		for(i=0; i<LENGTH_ID*2; i+=2) {
			Id_String[0]=String_Id[Start_Pos+2+1+i]; //first 4 bits of ID
			Id_String[1]=String_Id[Start_Pos+2+1+i+1]; // last 4 bits of ID
			Id_String[2]=0x00; //end of string
			
			Id_Index=Convert_String_Hex_To_Dec(Id_String);
			if(Id_Index==CAN_NOT_CONVERT) { //error occure with string
				return NULL;
			}
			else {
				ret->_ID[i/2]=Id_Index;
			}
		}
	}
	else { //return NULL if is not [R] or [A]
		return NULL;
	}
	return ret;
}

void Process_Id_From_Esp(void) {
	char* List_Id=Get_Sql_Status();
	if(strcmp("NONE",List_Id)==0) {
		return; //do notthing
	}
	else {
		uint8_t i;
		struct ID_PACKAGE *Id_Process;
		for (i=0; List_Id[i]; i++) {
			if(List_Id[i]=='[') {
				Id_Process=Parse_Id(List_Id,i);
				if(Id_Process!=NULL) {
					if(Id_Process->_Att==REMOVE) { //attribute is REMOVE
						if(Search_Id(Id_Process->_ID)!=ID_NOT_FOUND) { //found Id in flash
							Remove_Id(Search_Id(Id_Process->_ID));
							Nbr_ID--;
							Write_Page0(Emer_flag,Nbr_ID,OneTouch,password);
						}
					}
					else { //attribute is ADD
						if(Search_Id(Id_Process->_ID)==ID_NOT_FOUND) {
							Add_Id(Id_Process->_ID);
							Nbr_ID++;
							Write_Page0(Emer_flag,Nbr_ID,OneTouch,password);
						}
					}
					free(Id_Process);
				}	
			}
		}
		Write_None_Sql(); //complete process
	}
}

uint8_t ESP_Available(void){
	if(Read_status(GPIO_ESP,ESP_Available_Pin)) {
		return TRUE;
	}
	return FALSE;
}

uint8_t Retry_Connect_Wifi=0;

FILE __stdout;

/*------------------------------printf----------------------*/
int fputc(int ch, FILE *f) {
    /* Do your stuff here */
    /* Send your custom byte */
    /* Send byte to USART */
    User_USART3_SendChar(ch);
    
    /* If everything is OK, you have to return character written */
    return ch;
    /* If character is not correct, you can return EOF (-1) to stop writing */
    //return -1;
}


//===================convert data=================

uint16_t* ConvertCharToUint16(char *s) {
	uint8_t i;
	static uint16_t temp[31];
	ResetArr(temp,31);
	for(i=0; s[i]; i++) {
		temp[i]=s[i];
	}
	return temp;
}

char* ConvertUint16ToChar(uint16_t* s) {
	uint8_t i;
	static char temp[31];
	ResetArr((uint16_t*)temp,31);
	for(i=0; s[i]; i++) {
		temp[i]=s[i];
	}
	return temp;
}

char* ConvertUint8ToChar(uint8_t* s) {
	uint8_t i;
	static char temp[31];
	ResetArr((uint16_t*)temp,31);
	for(i=0; s[i]; i++) {
		temp[i]=s[i];
	}
	return temp;
}

uint8_t* ConvertCharToUint8(char *s) {
	uint8_t i;
	static uint8_t temp[31];
	ResetArr((uint16_t*)temp,31);
	for(i=0; s[i]; i++) {
		temp[i]=s[i];
	}
	return temp;
}

uint8_t* ConvertUint16ToUint8(uint16_t *s) {
	uint8_t i;
	static uint8_t temp[31];
	ResetArr((uint16_t*)temp,31);
	for(i=0; s[i]; i++) {
		temp[i]=s[i];
	}
	return temp;
}

/*=================Read and Write page 0===============*/

void Write_EmerFlag(uint16_t flag) {
	//position 0 in page 0
	User_FLASH_Write(flag,0,PAGE0);
}
void Updata_EmerFlag(void) {
	//position 0 in page 0
	Emer_flag=User_FLASH_Read(0,PAGE0);
}

void Write_NbrID(uint16_t Nbr) {
	//position 1 in page 0
	User_FLASH_Write(Nbr,1,PAGE0);
}
void Updata_NbrID(void) {
	//position 1 in page 0
	Nbr_ID=User_FLASH_Read(1,PAGE0);
}

void Write_OneTouchMode(uint16_t status) {
	//position 2 in page 0
	User_FLASH_Write(status,2,PAGE0);
}
void Updata_OneTouchMode(void) {
	//position 2 in page 0
	OneTouch=User_FLASH_Read(2,PAGE0);
}

void Write_Password(uint16_t* pass) {
	//stated at position 3 in page 0
	uint8_t i;
	for(i=0; pass[i]; i++) {
		User_FLASH_Write(pass[i],3+i,PAGE0);
	}
	for(i=i; i<31; i++) {
		User_FLASH_Write(0x00,3+i,PAGE0);
	}
}
void Updata_Password(void) {
	//stated at position 3 in page 0
	uint8_t i;
	for(i=0; i<31; i++) {
		password[i]=User_FLASH_Read(3+i,PAGE0);
	}
}

void Write_Page0(uint16_t E_flag, uint16_t Nbr, uint16_t One_status, uint16_t* pass) {
	User_FLASH_Erase(PAGE0);
	
	Write_EmerFlag(E_flag);
	Write_NbrID(Nbr);
	Write_OneTouchMode(One_status);
	Write_Password(pass);
}

void Updata_Data_From_PAGE0() {
	Updata_EmerFlag();
	Updata_NbrID();
	Updata_OneTouchMode();
	Updata_Password();
}

void Display_PAGE0(void) {
	sprintf(TempBuff,"\nEmer_flag:%d\n",Emer_flag);
	User_USART2_SendSchar (TempBuff);
	sprintf(TempBuff,"Nbr_ID:%d\n",Nbr_ID);
	User_USART2_SendSchar(TempBuff);
	sprintf(TempBuff,"One touch:%d\n",OneTouch);
	User_USART2_SendSchar(TempBuff);
	sprintf(TempBuff,"password:%s\n",ConvertUint16ToChar(password));
	User_USART2_SendSchar(TempBuff);
}

/*======================Read and Write ID==================*/
uint8_t* Read_Id_From_Page (uint8_t Pos, uint8_t Page) {
	static uint8_t ret[5];
	uint8_t i;
	if (Pos>99) return NULL; //beyond page
	for(i=0;i<5;i++) {
		ret[i]=User_FLASH_Read(Pos*5+i,Page);
	}
	return ret;
}
void Write_Id_To_Page (uint8_t* Id, uint8_t Pos, uint8_t Page) {
	uint8_t i;
	if(Pos>99) return; //beyond page
	for( i=0; i<5; i++) {
		User_FLASH_Write(Id[i],Pos*5+i,Page);
	}
}

uint8_t Compare_Id (uint8_t* ID1, uint8_t* ID2) {
	uint8_t i;
	for(i=0; i<5; i++) {
		if(ID1[i]!=ID2[i]) {
			return FALSE;
		}
	}
	return TRUE;
}

/*=================Read and Write page 1,2,3===============*/
void Add_Id (uint8_t* In_Id) {
	uint8_t Last_Id_Pos,Last_Page;
	uint8_t Insert_Pos,Insert_Page;
	uint8_t iPage;
	//define value
	if(Nbr_ID>=300) return; //full
	if(Nbr_ID<=100) { //belong page 1                              //Last_Id_Pos, Last Page
		Last_Id_Pos=Nbr_ID-1;
		Last_Page=PAGE1;
	}
	if(101<=Nbr_ID && Nbr_ID<=200) { //belong page 2
		Last_Id_Pos=Nbr_ID-1-100;
		Last_Page=PAGE2;
	}
	if(201<=Nbr_ID && Nbr_ID<=300) { //belong page 3
		Last_Id_Pos=Nbr_ID-1-200;
		Last_Page=PAGE3;
	}
	if(Last_Id_Pos==99) { //end of page
		Insert_Pos=0; //page next
		Insert_Page=Last_Page+1;
	}
	else {
		Insert_Pos=Last_Id_Pos+1; //in same page
		Insert_Page=Last_Page;
	}
	//copy insert page to page 4
	User_FLASH_Erase(PAGE4);
	for (iPage=0; iPage<Insert_Pos; iPage++) {
		Write_Id_To_Page(Read_Id_From_Page(iPage,Insert_Page),
		                 iPage,
		                 PAGE4);
	}
	//copy page 4 back to insert page
	User_FLASH_Erase(Insert_Page);
	for(iPage=0; iPage<Insert_Pos; iPage++) {
		Write_Id_To_Page(Read_Id_From_Page(iPage,PAGE4),
		                 iPage,
		                 Insert_Page);
	}
	//add new id
	Write_Id_To_Page(In_Id,Insert_Pos,Insert_Page);
}

int16_t Search_Id (uint8_t* In_Id) {
	uint16_t iPos;
	uint8_t iPage;
	uint8_t Page_Search;
	for (iPos=0; iPos<Nbr_ID; iPos++) {
		if(iPos<=99) {
			iPage=iPos;
			Page_Search=PAGE1;
		}
		else if(100<=iPos && iPos<=199) {
			iPage=iPos-100;
			Page_Search=PAGE2;
		}
		else if(200<=iPos && iPos<=299) {
			iPage=iPos-200;
			Page_Search=PAGE3;
		}
		if(Compare_Id(In_Id,Read_Id_From_Page(iPage,Page_Search))) {//Id found
			return iPos;
		}
	}
	return ID_NOT_FOUND;
}

uint8_t* Pick_Id (uint16_t index){
	uint16_t iPage;
	uint8_t Page_Pick;
	uint8_t i;
	static uint8_t ret[5];
	uint8_t* Read_Id=NULL;
	if(index<=99) {
		iPage=index;
		Page_Pick=PAGE1;
	}
	else if(100<=index && index<=199) {
		iPage=index-100;
		Page_Pick=PAGE2;
	}
	else if(200<=index && index<=299) {
		iPage=index-200;
		Page_Pick=PAGE3;
	}
	Read_Id=Read_Id_From_Page(iPage,Page_Pick);
	for (i=0; i<5; i++) {
		ret[i]=Read_Id[i];
	}
	return ret;
}

void Remove_Id (uint16_t index) {
	if(index>=Nbr_ID) return; //maximum is Nbr_ID-1
	uint8_t Cur_Page, Cur_Page_Start, Cur_Page_End;
	uint8_t Remove_Pos;
	uint8_t Last_Page, Last_Id_Pos;
	/*
	* Cur_Page: Current page content index
	* Cur_Page_Start: Start pos
	* Cur_Page_End: End pos of current page
	* Remove_Pos: Pos in current page want to remove
	* Last_Page: Lastest page content Lastest ID
	* Last_Id_Pos: Lastest ID'pos in Lastest page
	*/
	//=============define value=======
	Cur_Page_Start=0; //always 0                            //Cur_Page_Start
	
	if(index<=99) {//belong page 1
		Cur_Page=PAGE1;                                       //Cur_Page
		Remove_Pos=index;                                     //Remove_Pos
	}
	if(100<=index && index<=199) {//belong page 2
		Cur_Page=PAGE2;
		Remove_Pos=index-100;
	}
	if(200<=index && index<=299) {//belong page 3
		Cur_Page=PAGE3;
		Remove_Pos=index-200;
	}
	
	if(Nbr_ID<=100) {//belong page 1
		Last_Page=PAGE1;                                       //Last_Page
		Last_Id_Pos=Nbr_ID-1;                                  //Last_Id_Pos
	}
	if(101<=Nbr_ID && Nbr_ID<=200) {//belong page 2
		Last_Page=PAGE2;
		Last_Id_Pos=Nbr_ID-1-100;
	}
	if(201<=Nbr_ID && Nbr_ID<=300) {//belong page 3
		Last_Page=PAGE3;
		Last_Id_Pos=Nbr_ID-1-200;
	}
	
	if(Cur_Page==Last_Page) { //same page
		Cur_Page_End=Last_Id_Pos;
		Last_Page=PAGE4;
	}
	else { //different page
		Cur_Page_End=99; //lastest id'pos of a page
	}
	uint8_t iPage;
	//first copy data to page 4
	User_FLASH_Erase(PAGE4);
	for(iPage=Cur_Page_Start; iPage<=Cur_Page_End; iPage++) {
		Write_Id_To_Page(Read_Id_From_Page(iPage,Cur_Page),
		                 iPage,
		                 PAGE4);
	}
	//start remove id
	User_FLASH_Erase(Cur_Page);
	for(iPage=Cur_Page_Start; iPage<=Cur_Page_End; iPage++) {
		if(iPage==Remove_Pos) { //detected removed ID
			Write_Id_To_Page(Read_Id_From_Page(Last_Id_Pos,Last_Page),
			                 Remove_Pos,
			                 Cur_Page);
		}
		else {
			Write_Id_To_Page(Read_Id_From_Page(iPage,PAGE4),
			                 iPage,
			                 Cur_Page);
		}
	}
	
}

void Add_NewMem(uint8_t* InID) {
	if(Nbr_ID>=300) {
		User_USART2_SendSchar("\nDa du so luong luong, khong the add them\n");
		return;
	}
	Add_Id(InID);
	//update new Nbr_ID
	Nbr_ID++;
	Write_Page0(Emer_flag,Nbr_ID,OneTouch,password);
}

void Remove_Mem(uint8_t index) {
	if(index>=Nbr_ID) {
		User_USART2_SendSchar("\nChi so khong hop le!\n");
		return;
	}
	Remove_Id(index);
	Nbr_ID--;
	Write_Page0(Emer_flag,Nbr_ID,OneTouch,password);
}

void Display_ID(uint8_t index) {
	if(index>=Nbr_ID) {
		User_USART2_SendSchar ("\nchi so khong hop le!\n");
		return;
	}
	sprintf(TempBuff,"[%d].",index);
	User_USART2_SendSchar(TempBuff);
	uint8_t* temp=NULL;
	User_USART2_SendSchar("\nMa the:");
	temp=Pick_Id(index);
	sprintf(TempBuff,"[%02X,%02X,%02X,%02X,%02X]\n",temp[0],temp[1],temp[2],temp[3],temp[4]);
	User_USART2_SendSchar(TempBuff);
}

void CloseDoor(void) {
	User_TIM_Handle(SERVO_ANGLE_CLOSE);
	Door_status=CLOSE;
	Turn_led_close(ON);
	Turn_led_open(OFF);
	DelayMs(500);
	Turn_buzz(ON);
	DelayMs(500);
	Turn_buzz(OFF);
	User_USART2_SendSchar("\nClosed door\n");
	DelayMs(1000);
}
void OpenDoor(void){
	User_TIM_Handle(SERVO_ANGLE_OPEN);
	Door_status=OPEN;
	Turn_buzz(ON);
	DelayMs(300);
	Turn_buzz(OFF);
	DelayMs(300);
	Turn_buzz(ON);
	DelayMs(300);
	Turn_buzz(OFF);
	Turn_led_close(OFF);
	Turn_led_open(ON);
	User_USART2_SendSchar("\nOpened door\n");
	DelayMs(1000);
}

void Turn_led_close(uint8_t status){
	if(status==ON) {
		SC_bit(GPIOA,LED_Close_Pin,CLEAR);
	}
	else {
		SC_bit(GPIOA,LED_Close_Pin,SET);
	}
}


void Turn_led_open(uint8_t status){
	if(status==ON) {
		SC_bit(GPIOA,LED_Open_Pin,CLEAR);
	}
	else {
		SC_bit(GPIOA,LED_Open_Pin,SET);
	}
}


void Turn_buzz(uint8_t status){
	if(status==ON) {
		SC_bit(GPIOA,BUZZ_Pin,CLEAR);
	}
	else {
		SC_bit(GPIOA,BUZZ_Pin,SET);
	}
}

void ResetArr(uint16_t* arr, uint32_t leng) {
	int i;
	for(i=0;i<leng; i++) {
		arr[i]=0x0000;
	}
}

uint8_t ComparePassword(uint16_t* InputPass) {
	int i;
	for (i=0; i<31; i++) {
		if(InputPass[i]!=password[i]) {
			return FALSE;
		}
	}
	return TRUE;
}

uint8_t RequirePassword() {
	User_USART2_SendSchar("\nVui long nhap mat khau va ket thuc bang dau '.'\n");
	if (ComparePassword(User_USART2_ReceiveString(TempBuff,'.',30))) {
		User_USART2_SendSchar("\nMat khau dung\n");
		ResetArr((uint16_t*)TempBuff,50);
		return TRUE;
	}
	else {
		User_USART2_SendSchar("\nMat khau sai\n");
		ResetArr((uint16_t*)TempBuff,50);
		return FALSE;
	}
}

uint8_t CheckAlert (void) {
	if(Emer_flag|| //if enter wrong ID more than 3 times
	((Door_status==CLOSE) && (CheckDoorStatus()==OPEN))) { //if door is close but switch indicate open
		Emer_flag=TRUE;
		Write_Page0(Emer_flag,Nbr_ID,OneTouch,password);
	}
	return Emer_flag;
}

uint8_t CheckDoorStatus(void) {
	if(!Read_status(GPIOA,SWITCH_Pin)) {
		return CLOSE;
	}
	else
		return OPEN;
}

void Alert(void) {
	Run_alert:
	while (Emer_flag && !Enter_pass_remove_alert) {
		TM_MFRC522_Init();
		Updata_Data_From_PAGE0();
		User_USART2_SendSchar("\nBAO DONG!\n");
		if (TM_MFRC522_Check(ScanedID) == MI_OK){
			if(CheckScanedID(ScanedID)){
				Turn_buzz(OFF);
				Enter_pass_remove_alert=FALSE;
				Emer_flag=FALSE;
				Write_Page0(Emer_flag,Nbr_ID,OneTouch,password);
				User_USART2_SendSchar("\nDa tat bao dong!\n");
				if(CheckDoorStatus()==OPEN && Door_status==CLOSE) {
					OpenDoor();
				}
				return;
			}
		}
		Turn_buzz(ON);
		DelayMs(500);
		Turn_buzz(OFF);
		DelayMs(500);
		if(!Read_status(GPIO_BT,BT2_Pin) && OneTouch)
			Enter_pass_remove_alert=TRUE;
	}
	if(Enter_pass_remove_alert) {
		if(RequirePassword()) {
			Turn_buzz(OFF);
			Enter_pass_remove_alert=FALSE;
			Emer_flag=FALSE;
			Write_Page0(Emer_flag,Nbr_ID,OneTouch,password);
			User_USART2_SendSchar("\nDa tat bao dong!\n");
			if(CheckDoorStatus()==OPEN && Door_status==CLOSE) {
				OpenDoor();
			}
		}
		else {
			Enter_pass_remove_alert=FALSE;
			goto Run_alert;
		}
	}
}

uint8_t CheckScanedID(uint8_t* IDCheck) {
	if(Search_Id(IDCheck)==ID_NOT_FOUND)
		return FALSE;
	else 
		return TRUE;
}

void DisplayMenu(void) {
	User_USART2_SendSchar("\nm: Hien thi menu\n");
	User_USART2_SendSchar("d: Mo cua\n");
	User_USART2_SendSchar("a: Them thanh vien\n");
	User_USART2_SendSchar("r: Loai bo thanh vien\n");
	User_USART2_SendSchar("p: Doi password\n");
	User_USART2_SendSchar("o: One touch mode\n");
}

void Add_Mem_Procedure(void) {
	User_USART2_SendSchar("\nAdd memeber\n");
	if(RequirePassword()){
		User_USART2_SendSchar("\nVui long dua the lai gan may quet\n");
		while(TM_MFRC522_Check(ScanedID) == MI_ERR);
		User_USART2_SendSchar("\nDa nhan duoc the\n");
		if(CheckScanedID(ScanedID)) {//ID ton tai
			User_USART2_SendSchar("\nID da ton tai\n");
			return;
		}
		Add_NewMem(ScanedID);
		User_USART2_SendSchar("\nThem thanh vien thanh cong\n");
	}
}

void Remove_Mem_Procedure(void) {
	if(Nbr_ID<=0) {
		User_USART2_SendSchar("\nSo luong thanh vien hien la 0\n");
		return;
	}
	uint8_t i;
	User_USART2_SendSchar("\nRemove memeber\n");
	if(RequirePassword()){
		for(i=0; i<Nbr_ID; i++) {
			Display_ID(i);
		}
		User_USART2_SendSchar("\nChon thanh vien can loai bo (ket thuc bang dau '.'): ");
		do {
			User_USART2_ReceiveString(TempBuff,'.',10);
		} while(atoi(TempBuff)<0 || atoi(TempBuff)>=Nbr_ID);
		Remove_Mem(atoi(TempBuff));
		User_USART2_SendSchar("\nDa loai bo thanh vien\n");
	}
}

uint8_t CompareUintChar(uint16_t* s1, char* s2, uint8_t leng) {
	uint8_t i;
	for (i=0 ; i<leng ; i++) {
		if(s1[i]!=s2[i])
			return FALSE;
	}
	return TRUE;
}

void ChangePass(void) {
	User_USART2_SendSchar("\nChange password\nnhap lai mat khau cu: ");
	if(RequirePassword()) { //require old pass
		ResetArr((uint16_t*)TempBuff,31);
		User_USART2_SendSchar("\nNhap mat khau moi (ket thuc bang dau '.'): ");
		User_USART2_ReceiveString(TempBuff,'.',30);
		User_USART2_SendChar('\n');
		User_USART2_SendSchar("\nNhap lai mat khau moi (ket thuc bang dau '.'): ");
		if(CompareUintChar(User_USART2_ReceiveString2('.',30),TempBuff,30)) {
			User_USART2_SendSchar("\nMat khau trung khop, mat khau moi la:\n");
			User_USART2_SendSchar(TempBuff); //display new password
			Write_Page0(Emer_flag,Nbr_ID,OneTouch,ConvertCharToUint16(TempBuff)); //write password to flash
			Updata_Data_From_PAGE0(); //updata new password from flash
			return;
		}
		else {
			User_USART2_SendSchar("\nMat khau xac nhan khong dung:\n");
			return;
		}
	}
	else {
		User_USART2_SendSchar("Sai mat khau, truy cap bi tu choi\n");
		return;
	}
}

void TurnOneTouchMode(void) {
	User_USART2_SendSchar("\nTurn one touch mode:\n");
	if(RequirePassword()) {
		User_USART2_SendSchar("\nON: de bat\nOFF: de tat\n");
		ResetArr((uint16_t*)TempBuff,31);
		User_USART2_ReceiveString(TempBuff,'.',4);
		if(strcmp(TempBuff,"ON")==0) {
			OneTouch=ON;
			Write_Page0(Emer_flag,Nbr_ID,OneTouch,password);
			User_USART2_SendSchar("\nTurn ON\n");
			return;
		}
		else if(strcmp(TempBuff,"OFF")==0) {
			OneTouch=OFF;
			Write_Page0(Emer_flag,Nbr_ID,OneTouch,password);
			User_USART2_SendSchar("\nTurn OFF\n");
			return;
		}
		else {
			User_USART2_SendSchar("\nNhap khong dung\n");
			return;
		}
	}
	else {
		User_USART2_SendSchar("\nSai mat khau\n");
	}
}

//for interrupt receive usart2==============
void USART2_IRQHandler(void){
	//get data when ever receive interrupt excuse and receive_flag for receive string
	if((USART_GetITStatus(User_USART2, USART_IT_RXNE) != RESET) && receive_flag) {
		buff[buff_pos++]=User_USART2_ReceiveChar();
	}
	
	if(USART_GetITStatus(User_USART2, USART_IT_RXNE) != RESET && !Emer_flag && !Enter_pass_remove_alert && !receive_flag) {
		switch(User_USART2_ReceiveChar()) {
			case 'm': //display option menu
				DisplayMenu();
				break;
			
			case 'a': //add new member
				AddMember_flag=TRUE;
				break;
			
			case 'r': //remove mem
				RemoveMember_flag=TRUE;
				break;
			
			case 'p': //change new pass word
				ChangePassword_flag=TRUE;
				break;
			
			case 'o': //change one touch mode
				OneTouchMode_flag=TRUE;
				break;
			case 'd': //opendoor from usart
				OpenDoorUSART_flag=TRUE;
				break;
			
		}
	}
	//if alert occure, want to shutdown it
	if(USART_GetITStatus(User_USART2, USART_IT_RXNE) != RESET && Emer_flag && !Enter_pass_remove_alert && !receive_flag) {
		if(User_USART2_ReceiveChar()=='x') {
			Enter_pass_remove_alert=TRUE;
		}
	}
	
}
