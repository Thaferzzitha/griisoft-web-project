import static com.kms.katalon.core.checkpoint.CheckpointFactory.findCheckpoint
import static com.kms.katalon.core.testcase.TestCaseFactory.findTestCase
import static com.kms.katalon.core.testdata.TestDataFactory.findTestData
import static com.kms.katalon.core.testobject.ObjectRepository.findTestObject
import static com.kms.katalon.core.testobject.ObjectRepository.findWindowsObject
import com.kms.katalon.core.checkpoint.Checkpoint as Checkpoint
import com.kms.katalon.core.cucumber.keyword.CucumberBuiltinKeywords as CucumberKW
import com.kms.katalon.core.mobile.keyword.MobileBuiltInKeywords as Mobile
import com.kms.katalon.core.model.FailureHandling as FailureHandling
import com.kms.katalon.core.testcase.TestCase as TestCase
import com.kms.katalon.core.testdata.TestData as TestData
import com.kms.katalon.core.testng.keyword.TestNGBuiltinKeywords as TestNGKW
import com.kms.katalon.core.testobject.TestObject as TestObject
import com.kms.katalon.core.webservice.keyword.WSBuiltInKeywords as WS
import com.kms.katalon.core.webui.keyword.WebUiBuiltInKeywords as WebUI
import com.kms.katalon.core.windows.keyword.WindowsBuiltinKeywords as Windows
import internal.GlobalVariable as GlobalVariable
import org.openqa.selenium.Keys as Keys

WebUI.openBrowser('')

WebUI.navigateToUrl('http://127.0.0.1:8000/')

WebUI.click(findTestObject('Object Repository/Page_Bienvenidoa - GRIISOFT/a_Iniciar Sesin'))

WebUI.setText(findTestObject('Object Repository/Page_Log in - GRIISOFT/input_Email_email'), 'thaferzzitha1@gmail.com')

WebUI.setEncryptedText(findTestObject('Object Repository/Page_Log in - GRIISOFT/input_Contrasea_password'), 'RigbBhfdqOBGNlJIWM1ClA==')

WebUI.sendKeys(findTestObject('Object Repository/Page_Log in - GRIISOFT/input_Contrasea_password'), Keys.chord(Keys.ENTER))

WebUI.click(findTestObject('Object Repository/Page_Bienvenidoa - GRIISOFT/a_Dashboard'))

WebUI.click(findTestObject('Object Repository/Page_Dashboard - GRIISOFT/a_Graficar Atractor'))

WebUI.selectOptionByValue(findTestObject('Object Repository/Page_Graficacin - GRIISOFT/select_Seleccione el atractor.Atractor de R_8c46b5'), 
    'rossler', true)

WebUI.setText(findTestObject('Object Repository/Page_Graficacin - GRIISOFT/input_Ttulo_title'), 'Atractor de Rossler')

WebUI.click(findTestObject('Object Repository/Page_Graficacin - GRIISOFT/button_Guardar'))

WebUI.click(findTestObject('Object Repository/Page_Graficacin - GRIISOFT/a_Historial de Grficos'))

