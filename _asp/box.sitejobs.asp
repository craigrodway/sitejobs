<!-- First Aid -->
<% Call HTMLBoxStart("sitejobs","Site Team Problem Report","52","mcentre") %>

<%
siteteam = false
Select Case sUsername
	Case "stuart.fieldson"
	Case "brett.osborne"
	Case "sean.mcaloon"
	'Case "craig.rodway"
		siteteam = true
End Select
%>

<% If siteteam = False Then %>

	<div style="padding:6px;" id="sitejobsbox">
		
		<form name="firstaid" id="sitejobs" action="http://intranet/v3/asp.sitejobs.asp" method="POST" style="padding:0;margin:0;">
		
		<input type="hidden" name="creator" value="<%= sUsername %>" />
		
		<label for="sj_room"><strong>Room:</strong></label><br />
		<input name="room" id="sj_room" value="" size="30" />
		<br /><br />
		
		<label for="sj_type"><strong>Problem type:</strong></label><br />
		<select name="type" id="sj_type">
			<option value="damage">Damage</option>
			<option value="replacement">Replacement</option>
			<option value="wear-and-tear">Wear &amp; Tear</option>
			<option value="fault">Fault</option>
		</select>
		<br /><br />
		
		<label for="sj_first"><strong>First report:</strong></label><br />
		<input type="checkbox" name="first" id="sj_first" value="1" checked="true" style="width: 16px; height: 16px; margin: 0; padding: 0;" />
		<br /><br />
		
		<label for="sj_description"><strong>Description:</strong></label><br />
		<textarea name="description" id="sj_description" rows="6"></textarea>
		<br /><br />
		
		<br /><br />

		<input type="submit" name="btnSitejob" id="btnSitejob" value="Submit" style="cursor:default;font-weight:bold;padding:2px 4px;" />
		
		</form>
		
	</div>

<% Else %>

	<button onclick="location.href = 'http://10.0.1.45/projects/sitejobs/'">View job list</button>

<% End If %>

<% Call HTMLBoxEnd %>
