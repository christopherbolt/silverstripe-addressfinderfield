
	<div class="addressfinderfield $extraClass" $AttributesHTML>
		<% with ChildFields.First %>
			$Field
			<% if $RightTitle %>
				<label class="right" for="$ID">$RightTitle</label>
			<% end_if %>
			<% if $Message %>
				<span class="message $MessageType">$Message</span>
			<% end_if %>
			<% if $Description %>
				<span class="description">$Description</span>
			<% end_if %>
		<% end_with %>
	</div>
</div>
<div class="addressfinderfield-values clear">
	<% loop ChildFields %>
		<% if Middle || Last %>
			<div class="addressfinderfield-children clear">
				<% if Top.HiddenFields %>
					<div class="middleColumn">
						$Field
					</div>
				<% else %>
					<% if $Title %>
						<label class="left" for="$ID">$Title</label>
					<% end_if %>
					<div class="middleColumn">
						$Field
					</div>
					<% if $RightTitle %>
						<label class="right" for="$ID">$RightTitle</label>
					<% end_if %>
					<% if $Message %>
						<span class="message $MessageType">$Message</span>
					<% end_if %>
					<% if $Description %>
						<span class="description">$Description</span>
					<% end_if %>
				<% end_if %>
			</div>
		<% end_if %>
	<% end_loop %>
</div>
<%-- Needed to fix a stray open tag from module above --%>
<div>
