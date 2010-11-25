<% Response.ContentType = "application/json" %>

<%
ip = Request.ServerVariables("REMOTE_HOST")
set Sh = createobject("WScript.Shell")
set Oe = Sh.Exec("ping -a " & ip & " -n 1 -w 1")
Re = Oe.StdOut.ReadAll()
hn1 = split(Re," ")
hn2 = split(hn1(1),".")

creator = Request.Form("creator")
computer = hn2(0)
room = Request.Form("room")
ptype = Request.Form("type")
first = Request.Form("first")
description = Request.Form("description")

url = "http://10.0.1.45/projects/sitejobs/api.php?action=create"

Set xhr = Server.CreateObject("MSXML2.ServerXMLHTTP")
Set xml = Server.CreateObject("MSXML2.DOMDocument")
xhr.open "POST", url, false
xhr.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
xhr.send "creator=" & creator & _
			"&computer=" & computer & _
			"&room=" & room & _
			"&type=" & ptype & _
			"&first=" & first & _
			"&description=" & description

strStatus = xhr.Status
strRetval = xhr.responseText
response.write xhr.responseText
%>