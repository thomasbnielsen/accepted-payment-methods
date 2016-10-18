<% if $SiteConfig.SortedPaymentMethods %>
	<ul class="accepted-payment-methods">
		<% loop $SiteConfig.SortedPaymentMethods %>
			<li>
				<% if $FileType == 'Image' %>
					$Icon
				<% else %>
					<img src="{$Icon.URL}">
				<% end_if %>
			</li>
		<% end_loop %>
	</ul>
<% end_if %>