#include <main.h>



int main (void) {
	/*------------watchdog----------*/
	IWDG_WriteAccessCmd(IWDG_WriteAccess_Enable);
	IWDG_SetPrescaler(IWDG_Prescaler_256); //xung nhip IWDG la 40KHz, chon prescaler la 256
	IWDG_SetReload(625); //thoi gian de IWDG dem nguoc va reset lai la 4s => chon gia tri 625, 4=625/(40KHz/256)
	IWDG_ReloadCounter(); //reset lai thoi gian.
	IWDG_Enable();
	/*--------------------------------*/
	User_GPIO_Init();
	DelayInit();
	DelayUs(10);
	TM_MFRC522_Init();
	DelayUs(10);
	User_TIM_Init();
	DelayUs(10);
	User_USART2_Init(9600);
	DelayUs(10);
	User_USART3_Init(115200);
	DelayUs(10);
	DelayUs(10);
	User_GPIO_Init();
	DelayUs(10);
	Turn_buzz(OFF);
	Turn_led_close(OFF);
	Turn_led_open(OFF);
	Updata_Data_From_PAGE0();
	if(CheckDoorStatus()==CLOSE) {
		User_TIM_Handle(SERVO_ANGLE_CLOSE);
		Turn_led_close(ON);
		Door_status=CLOSE;
	}
	else {
		User_TIM_Handle(SERVO_ANGLE_OPEN);
		Turn_led_open(ON);
		Door_status=OPEN;
	}
	uint8_t count=0;
	while(!ESP_Available() && count <5) {
		count++;
		DelayMs(100);
	}
	count=0;
	if(ESP_Available()) {
		Connect_Wifi(SSID,PASS);
		while (!Check_Wifi() && count<5) {
			count++;
			DelayMs(100);
		}
	}
	/*---------------------loop while---------------------*/
	while (1) {
		TM_MFRC522_Init();
		//---------------------ALERT-----------------------
		if(CheckAlert()) {//if alert occure
			Alert();
		}
		else {
			if(ESP_Available() || (Count_Wait_Esp>=COUNT_ESP_MAX)) { //esp's not busy or Count_Wait_Esp reach COUNT_ESP_MAX
				Prevent_Increase_Count_Waite_Esp=TRUE; //prevent increase
				if(ESP_Available()) {
					Prevent_Increase_Count_Waite_Esp=FALSE; //allow increase
					Count_Wait_Esp=0; //reset value
				}
				if (TM_MFRC522_Check(ScanedID) == MI_OK){ //detected RFcard
					if(ESP_Available()) {
						if(Check_Wifi()) {
							Push_Id(ScanedID);
						}
					}
					if(CheckScanedID(ScanedID)) {//right ID
						if(Door_status!=OPEN) {
							User_USART2_SendSchar("\nID dung\n");
							OpenDoor();
						}
					}
					else {
						Turn_buzz(ON);
						DelayMs(1000);
						Turn_buzz(OFF);
						User_USART2_SendSchar("\nID sai!");
					}
				}
			}
			else {
				if(!Prevent_Increase_Count_Waite_Esp) {
					Count_Wait_Esp++; //inrease times wait esp
				}
			}
		}
		//update and add or remove id from server
		if(ESP_Available()) {
			if(Check_Wifi()) {
				if(!Update_Sql_Flag) {
					Update_Sql_Status();
					Update_Sql_Flag=TRUE;
				}
			}
		}
		if(ESP_Available()) {
			if(Check_Wifi()) {
				Process_Id_From_Esp();
				Update_Sql_Flag=FALSE;
			}
		}
		//update and close or open door base on server
		if(ESP_Available()) {
			if(Check_Wifi()) {
				if( (!Update_Door_Flag) ) {
					Update_Door_Status();
					Update_Door_Flag=TRUE;
				}
			}
		}
		if(ESP_Available()) {
			if(Get_Door_Status()==OPEN && Door_status==CLOSE) {
				OpenDoor();
				if(Check_Wifi()) {
					Write_Close_Door();
				}
			}
			else if(Get_Door_Status()==CLOSE && Door_status==OPEN && CheckDoorStatus()==CLOSE) {
				CloseDoor();
			}
			Update_Door_Flag=FALSE;
		}
		
		//-----------------------add new member-----------------------
		if(AddMember_flag) {
			Add_Mem_Procedure();
			AddMember_flag=FALSE;
		}
		if(RemoveMember_flag) {
			Remove_Mem_Procedure();
			RemoveMember_flag=FALSE;
		}
		//------------------------change password----------------------
		if(ChangePassword_flag) {
			ChangePass();
			ChangePassword_flag=FALSE;
		}
		//----------------------change one touch mode------------------------
		if(OneTouchMode_flag) {
			TurnOneTouchMode();
			OneTouchMode_flag=FALSE;
		}
		//----------------------Open door use button------------------------
		if(!Read_status(GPIO_BT,BT1_Pin) && Door_status!=OPEN) {
			if(OneTouch){
				OpenDoor();
			}
		}
		//----------------------Open door use USART------------------------
		if(OpenDoorUSART_flag && Door_status!=OPEN) {
			if(OneTouch){
				OpenDoor();
			}
			else {
				if(RequirePassword()) {
					OpenDoor();
				}
			}
			OpenDoorUSART_flag=FALSE;
		}
		//------------------------close door automatically-----------------------
		if(CheckDoorStatus()==CLOSE && Door_status!=CLOSE) {//able close door
			//delay 500ms in case someone open again
			DelayMs(500);
			if(CheckDoorStatus()==CLOSE){
				CloseDoor();
			}
		}
		if(ESP_Available()) {
			if(!Check_Wifi()) {
				User_USART2_SendSchar("\nESP Mat ket noi\n");
			}
		}
		IWDG_ReloadCounter(); //reset lai thoi gian.
		//--------------------end loop-------------------
	}
}
