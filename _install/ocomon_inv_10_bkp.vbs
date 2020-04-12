''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
' Script de inventario com integração ao ocomon 2.0 RC6
' 
' Desenvolvido por Leandro R. Gamito
' E-mail: lr.gamito[at]gmail.com
' 
' Pode ser alterado e distribuido desde que, por gentileza, 
' cite os autores anteriores.
' Baseado nos scripts
' 
' Data 24/11/2011
'
' versao 1.0
''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''

'''''''''''''''''''''''''''''''''''''
'   Variáveis de insersão em banco
'''''''''''''''''''''''''''''''''''''

Dim ie_page
Dim ie
Dim strPcName
Dim strPath
Dim strUserName

' Página PHP para capturar as informações
ie_page = "http://192.168.10.14:8080/ocomon_2.0-RC6/invmon/geral/add_comp_script.php"

''''''''''''''''''''''''''''''''''
'Funções
''''''''''''''''''''''''''''''''''

Function Trata_Erro()
strEmail = "lr.gamito@gmail.com"
strAutor = "Leandro Gamito"
  If Err.Number <> 0 Then
    MsgBox "Ocorreu um Erro no Script e ele será terminado, desculpe o transtorno" & vbCrLf & "NUM: " & Err.Number & vbCrLf & "-->" & vbCrLf & Err.Description & vbCrLf & "Entre em contato com o Desenvolvedor do Script:" & strEmail & " - " & strAutor & " e Informe o número do Erro.", 16, "NUM: " & Err.Number & vbCrLf & "-->" 
    'wScript.Quit(1)
    Err.Clear
  End If

  On Error Resume Next

End Function

Function GetKey(rpk)
Const rpkOffset=52:i=28
szPossibleChars="BCDFGHJKMPQRTVWXY2346789"
Do 'Rep1
  dwAccumulator=0 : j=14
  Do 
    dwAccumulator=dwAccumulator*256 
    dwAccumulator=rpk(j+rpkOffset)+dwAccumulator
    rpk(j+rpkOffset)=(dwAccumulator\24) and 255 
    dwAccumulator=dwAccumulator Mod 24
    j=j-1
  Loop While j>=0
  i=i-1 : 
  szProductKey=mid(szPossibleChars,dwAccumulator+1,1)&szProductKey
  if (((29-i) Mod 6)=0) and (i<>-1) then 
    i=i-1 : szProductKey="-"&szProductKey
  End If
Loop While i>=0
GetKey=szProductKey
End Function

'''''''''''''''''''''''''''''''
'Pegando Usuario e Maquina
''''''''''''''''''''''''''''''''
On Error Resume Next
Set objNetWork = CreateObject("Wscript.Network")
strUserName = objNetwork.UserName
strPcName = objNetwork.ComputerName

comp_comment = strUserName

''''''''''''''''''''''''''''''''''
' Não mudar os dados a seguir
''''''''''''''''''''''''''''''''''
Const HKEY_CLASSES_ROOT  = &H80000000
Const HKEY_CURRENT_USER  = &H80000001
Const HKEY_LOCAL_MACHINE = &H80000002
Const HKEY_USERS         = &H80000003
Const ForAppending = 8

''''''''''''''''''''''''''''''''''''
'Seta atributos
'''''''''''''''''''''''''''''''''''''
strSO = "Win32_OperatingSystem"
strProcessor = "Win32_Processor"
strMemory = "Win32_PhysicalMemory"
strLMemory = "Win32_LogicalMemoryConfiguration"
strDisk = "Win32_DiskDrive"
strPrint = "Win32_Printer"
strLAN = "Win32_NetworkAdapterConfiguration"
strNIC = "Win32_NetworkAdapter"
srtCSYS = "Win32_ComputerSystem"
strHOTF = "Win32_QuickFixEngineering"
strLOGDISK = "Win32_LogicalDisk"
strVGA = "Win32_VideoController"
strCDR = "Win32_CDROMDrive"
strSound = "Win32_SoundDevice"
strLocalUser = "Win32_UserAccount"
strComputer = "."
On Error Resume Next
Set objWMI = GetObject("winmgmts:\\" & strComputer & "\root\cimv2")
Set oReg = GetObject("winmgmts:{impersonationLevel=impersonate}!\\" & strComputer & "\root\default:StdRegProv")

Trata_Erro()

'''''''''''''''''''''''''''''''''''''
' Coleta as  Informações 
'''''''''''''''''''''''''''''''''''''

'''''''''''''''''''''''''''''''''''''
' SO
'''''''''''''''''''''''''''''''''''''
On Error Resume Next
Set colWMI = objWMI.InstancesOF(strSO)
For Each strWMI In colWMI
	comp_os = strWMI.Caption
Next

'''''''''''''''''''''''''''''''''''''
' SERIAL
'''''''''''''''''''''''''''''''''''''
if Not IsNull(strPcName) then
  path = "SOFTWARE\Microsoft\Windows NT\CurrentVersion"
  subKey = "DigitalProductId"
  oReg.GetBinaryValue HKEY_LOCAL_MACHINE,path,subKey,key
  strXPKey=GetKey(key)
	if Not IsNull(strXPKey) then
		comp_sn_os = strXPKey
	else
		comp_sn_os = ""
	end if
	'MsgBox "* " & comp_sn_os & " *"
end if

''''''''''''''''''''''''''''''''''''''
' MAC ADDRESS
''''''''''''''''''''''''''''''''''''''
On Error Resume Next
Set colWMI = objWMI.InstancesOF(strLAN)
comp_comment = comp_comment & " MAC ADD: "
For Each strWMI in colWMI
	if Not IsNull(strWMI.MACAddress) then
		comp_comment = comp_comment & strWMI.MACAddress
		
		   Set colWMI2 = objWMI.InstancesOF(strNIC)
			For Each strWMI2 in colWMI2
				comp_rede = "NIC ADAP: " & strWMI2.AdapterType & " - " & strWMI2.Manufacturer
			Next
	end if
	
Next

''''''''''''''''''''''''''''''''''''''
' PROCESSADOR
''''''''''''''''''''''''''''''''''''''
On Error Resume Next
Set colWMI = objWMI.InstancesOF(strProcessor)
For Each strWMI In colWMI
	if Not IsNull(strWMI.Name) then
		comp_proc = "PROCESSOR: " & strWMI.Name & " - " & strWMI.MaxClockSpeed
	end if
Next

''''''''''''''''''''''''''''''''''''''
' MEMORIA
''''''''''''''''''''''''''''''''''''''
On Error Resume Next
Set colWMI = objWMI.InstancesOF(strMemory)
For Each strWMI In colWMI
	mem_size = mem_size + strWMI.Capacity
	comp_memo = "MEM: " & mem_size/1024/1024 &" - "& strWMI.MemoryType

Next

''''''''''''''''''''''''''''''''''''''
' DISCO
''''''''''''''''''''''''''''''''''''''
On Error Resume Next
Set colWMI = objWMI.InstancesOF(strDisk)
For Each strWMI In colWMI
	If Not IsNull(strWMI.Size) Then
		comp_modelohd = "HD: " & strWMI.Caption & " - " & strWMI.Manufacturer & " - " & Round(strWMI.Size/1024/1024/1024)
	End If
Next

''''''''''''''''''''''''''''''''''''''
' VIDEO
''''''''''''''''''''''''''''''''''''''
On Error Resume Next
Set colWMI = objWMI.InstancesOF(strVGA)
For Each strWMI In colWMI
	if Not IsNull(strWMI.Caption) then
		comp_video = "VGA: " & strWMI.Caption
	end if
Next

''''''''''''''''''''''''''''''''''''''
' CD / DVD
''''''''''''''''''''''''''''''''''''''
On Error Resume Next
Set colWMI = objWMI.InstancesOF(strCDR)
For Each strWMI In colWMI
	if Not IsNull(strWMI.Caption) then
		comp_cdrom = "DVDCDR: " & strWMI.Caption & " - " & strWMI.Drive
	end if
Next

''''''''''''''''''''''''''''''''''''''
'       Sound
''''''''''''''''''''''''''''''''''''''
On Error Resume Next
Set colWMI = objWMI.InstancesOF(strSound)
For Each strWMI In colWMI
	if Not IsNull(strWMI.Manufacturer) then
		comp_som = "SOUND: " & strWMI.Manufacturer & " - " & strWMI.Name
	end if
Next

''''''''''''''''''''''''''''''''''''''
'      Local Users
''''''''''''''''''''''''''''''''''''''
On Error Resume Next
Set colWMI = objWMI.InstancesOF(strLocalUser)
comp_comment = comp_comment & " LOCAL USERS: "
For Each strWMI In colWMI
	if strWMI.LocalAccount= "TRUE" then
		if Not IsNull(strWMI.Name) then
			comp_comment = comp_comment & "USER: " & strWMI.Name & " - " & strWMI.FullName & " - " & strWMI.Disabled
		end if
	end if
Next

Trata_Erro()




''''''''''''''''''''''''''''''''''''
'      Coleta de Software
''''''''''''''''''''''''''''''''''''

strStartProg = "Win32_StartupCommand"
strServices = "Win32_Service"

''''''''''''''''''''''''''''''''
' Programas na Inialização
''''''''''''''''''''''''''''''''
On Error Resume Next
Set colWMI = objWMI.InstancesOF(strStartProg)
comp_comment = comp_comment & " STARTUP PROG: "
For Each strWMI In colWMI
	if strWMI.Location <> "Startup" AND (strWMI.User <> ".DEFAULT" OR strWMI.User <> "NT AUTHORITY\SYSTEM") then 
		'comp_comment = comp_comment &" -- "& strWMI.Caption & " -- " & strWMI.Command  & " -- " & strWMI.Description & " -- " & strWMI.Location & " -- " & strWMI.Name & " -- " & strWMI.User
		comp_comment = comp_comment & " PROG: " &strWMI.Command
    end if
	
Next

'''''''''''''''''''''''''''''''
' Softwares Instalados
'''''''''''''''''''''''''''''''
strKey = "SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\"
strEntry1a = "DisplayName"
strEntry1b = "QuietDisplayName"
strEntry2 = "InstallDate"
strEntry3 = "VersionMajor"
strEntry4 = "VersionMinor"
strEntry5 = "EstimatedSize"

oReg.EnumKey HKEY_LOCAL_MACHINE, strKey, arrSubkeys

Trata_Erro()
comp_soft = "INSTALLED SOFTWARES: "
For Each strSubKey In arrSubkeys 
	intRet1 = oReg.GetStringValue(HKEY_LOCAL_MACHINE, strKey & strSubkey, strEntry1a, strValue1) 
	If intRet1 <> 0 Then 
		oReg.GetStringValue HKEY_LOCAL_MACHINE, strKey & strSubkey, strEntry1b, strValue1
	End If 
	
	If strValue1 <> "" Then 
			'comp_soft = comp_soft & "SOFTWARE: "
			comp_soft = comp_soft & " NAME: " & Replace(strValue1,Chr(38),"")
	End If 
	
	'oReg.GetStringValue HKEY_LOCAL_MACHINE, strKey & strSubkey, strEntry2, strValue2 
	'If strValue2 <> "" Then 
	'	comp_soft = comp_soft & " DATE: " & strValue2
	'End If 
	'oReg.GetDWORDValue HKEY_LOCAL_MACHINE, strKey & strSubkey, strEntry3, intValue3 
	'oReg.GetDWORDValue HKEY_LOCAL_MACHINE, strKey & strSubkey, strEntry4, intValue4 
	'If intValue3 <> "" Then 
	'	comp_soft =  comp_soft & " VERSION: " & intValue3 & "." & intValue4 
	'End If 
	'oReg.GetDWORDValue HKEY_LOCAL_MACHINE, strKey & strSubkey, strEntry5, intValue5 
	'If intValue5 <> "" Then 
	'	comp_soft =  comp_soft & " SIZE: "&Round(intValue5/1024, 3) & " MB"
	'End If
Next
Trata_Erro()

		
''''''''''''''''''''''''''''''''
'   MS CD Keys for Office  2010'
''''''''''''''''''''''''''''''''
strKeyPath = "SOFTWARE\Microsoft\Office\14.0\Registration"
oReg.EnumKey HKEY_LOCAL_MACHINE, strKeyPath, arrSubKeys
For Each subkey In arrSubKeys
  path = strKeyPath & "\" & subkey
  strOffXPRU = "HKLM\" & path & "\DigitalProductId"
  subKey = "DigitalProductId"
  oReg.GetBinaryValue HKEY_LOCAL_MACHINE,path,subKey,key
  if IsNull(key) then
  else
    strOffXPRUKey=GetKey(key)
      comp_comment = comp_comment & " Office 2010 key: " & strOffXPRUKey
  end if
Next

'''''''''''''''''''''''''''
'   Services              '
'''''''''''''''''''''''''''
On Error Resume Next
Set colWMI = objWMI.InstancesOF(strServices)
For Each strWMI in colWMI
  if strWMI.Name = "uvnc_service" then
    vnc = "True"
  else
	vnc = "False"
  end if
Next

Trata_Erro()

''''''''''''''''''''''''''''''''''
' Monta variável para Metodo GET '
''''''''''''''''''''''''''''''''''
varget_hard = "?comp_nome="&strPcName&"&comp_os="&comp_os&"&comp_sn_os="&comp_sn_os _
'varget_hard = "?comp_nome=HCC13999&comp_os="&comp_os&"&comp_sn_os="&comp_sn_os _
		& "&comp_rede="&comp_rede&"&comp_proc="&comp_proc _
		& "&comp_memo="&comp_memo&"&comp_hd="&comp_modelohd _
		& "&comp_video="&comp_video&"&comp_cdrom="&comp_cdrom _
		& "&comp_som="&comp_som&"&comp_comment="&replace(comp_comment,Chr(92)&Chr(92),Chr(92)) _
		& "&comp_vnc="&vnc _
		& "&script_hard=true"

varget_soft = "?comp_nome="&strPcName&"&comp_soft="&comp_soft _
		& "&script_soft=true"

on Error Resume Next
Trata_Erro()


''''''''''''''''''''''''''''''''''''''''
' Cria Instancia do Internet Explorer  '
''''''''''''''''''''''''''''''''''''''''
on Error Resume Next
Set ie = CreateObject("InternetExplorer.Application")

	ie_page_hw = ie_page & varget_hard
	ie_page_sf = ie_page & varget_soft
		'MsgBox ie_page
	ie.navigate ie_page_hw
	
	' Janela do Browser aparece
	ie.visible= True
    
	Do Until IE.readyState = 4
		'WScript.Echo " " & IE.readyState
		WScript.sleep(200)
		
	Loop
			'WScript.Echo " " & ie_page_sf
		
on Error Resume Next
Trata_Erro()	