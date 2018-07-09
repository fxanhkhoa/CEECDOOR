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
	Write_Page0(0,0,0,ConvertCharToUint16("159753"));
	Updata_Data_From_PAGE0();
	Display_PAGE0();
	while (1){
	}
}
