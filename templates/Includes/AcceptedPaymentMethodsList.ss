<% if $SiteConfig.SortedPaymentMethods %>
	<ul class="accepted-payment-methods">
		<% loop $SiteConfig.SortedPaymentMethods %>
			<li>
				<% if $HasFileOrImage %>
					<img src="{$Icon.URL}" name="$Name">
				<% else %>
					<img src="$Icon" alt="$Name">
				<% end_if %>

			</li>
		<% end_loop %>
	</ul>
<% end_if %>