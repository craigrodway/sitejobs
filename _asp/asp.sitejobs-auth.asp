<% Response.ContentType = "text/javascript" %>
<!--#include file="asp.username.asp"-->
<!--#include file="asp.sha1.asp"-->
<%
secret = "RRN4G11KTA1"
ua = Request.ServerVariables("HTTP_USER_AGENT")
token = hex_sha1(sUsername & "_" & secret & "_" & ua)
Response.write	"User.setup({username: '" & sUsername & _
				"', display: '" & sDisplay & _
				"', token: '" & token & "'});"
%>