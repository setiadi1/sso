
<script type="text/javascript">
	$(document).ready(function() {
		var method = "<?php echo $method; ?>";
		$(".tag:contains('" + method + "')").addClass("activex");
	});
</script>