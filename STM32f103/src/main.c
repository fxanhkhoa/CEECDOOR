#include <main.h>




int main (void) {
	uint8_t i;
	DelayInit();
	DelayUs(10);
	TM_MFRC522_Init();
	DelayUs(10);
	User_TIM_Init();
	DelayUs(10);
	User_USART2_Init(115200);
	DelayUs(10);
	User_ADC_DMA_Init();
	DelayUs(10);
	User_GPIO_Init();
	DelayUs(10);
	Updata_Data_From_PAGE0();
	Turn_buzz(OFF);
	Turn_led_close(OFF);
	Turn_led_open(OFF);
	
	while(1)
	{
		OpenDoor();
		CloseDoor();
	}
	if(SENSOR_CLOSE_DOOR<=SENSOR_CLOSE_DOOR_VALUE) {
		CloseDoor();
	}
	else {
		if(Emer_flag==FALSE) {
			OpenDoor();
		}
	}
	/*---------------------loop while---------------------*/
	while (1) {
		TM_MFRC522_Init();
		Updata_Data_From_PAGE0();
		//---------------------ALERT-----------------------
		if(CheckAlert()) {//if alert occure
			Alert();
		}
		else {
			if (TM_MFRC522_Check(ScanedID) == MI_OK){
				if(CheckScanedID(ScanedID)) {//right ID
					if(Door_status!=OPEN) {
						User_USART2_SendSchar("\nID dung, cho mo cua\n");
						OpenDoor();
					}
				}
				else {
					Wrong_Nbr_ID++;
					Turn_buzz(ON);
					DelayMs(1000);
					Turn_buzz(OFF);
					User_USART2_SendSchar("\nID sai, Nhap lai!");
				}
			}
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
		if(SENSOR_CLOSE_DOOR<=SENSOR_CLOSE_DOOR_VALUE && Door_status!=CLOSE) {//able close door
			//delay 2s in case someone open again
			DelayMs(2000);
			if(SENSOR_CLOSE_DOOR<=SENSOR_CLOSE_DOOR_VALUE){
				CloseDoor();
			}
		}
		//--------------------end loop-------------------
	}
}
