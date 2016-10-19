<% if $SiteConfig.SortedPaymentMethods %>
	<ul class="accepted-payment-methods">
		<% loop $SiteConfig.SortedPaymentMethods %>
			<li>
				<% if $HasFileOrImage %>
					<img src="{$Icon.URL}" alt="$Name">
				<% else %>
					<img src="$Icon" alt="$Name">
				<% end_if %>
			</li>
		<% end_loop %>
	</ul>
<% end_if %>