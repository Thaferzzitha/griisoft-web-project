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

WebUI.click(findTestObject('Object Repository/Page_Bienvenidoa - GRIISOFT/a_Ver documentacin'))

WebUI.click(findTestObject('Object Repository/Page_GRIISOFT  API Documentacin/div_Registrar la informacin de un Usuario'))

WebUI.click(findTestObject('Object Repository/Page_GRIISOFT  API Documentacin/button_POSTapiauthloginIniciar sesin de usuario'))

WebUI.click(findTestObject('Object Repository/Page_GRIISOFT  API Documentacin/div_Cerrar sesin de usuario'))

WebUI.click(findTestObject('Object Repository/Page_GRIISOFT  API Documentacin/button_GETapigraphiclistListar grficos'))

WebUI.click(findTestObject('Object Repository/Page_GRIISOFT  API Documentacin/button_POSTapigraphicstoreCrear un nuevo grfico'))

WebUI.click(findTestObject('Object Repository/Page_GRIISOFT  API Documentacin/div_Mostrar detalles de un grfico por su ID'))

WebUI.click(findTestObject('Object Repository/Page_GRIISOFT  API Documentacin/button_PUTapigraphicgraphicActualizar un gr_f492c5'))

WebUI.click(findTestObject('Object Repository/Page_GRIISOFT  API Documentacin/button_DELETEapigraphicgraphicEliminar un g_6e34d1'))

