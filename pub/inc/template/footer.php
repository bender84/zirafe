</div>
<div id="copyright">
<p>powered by <a href="http://zirafe.github.com">Zirafe</a></p>
</div>
<script type="text/javascript">
function DoSubmit(){
  document.getElementById('submit-loader').style.display = "block";
  document.getElementById('submit-btn').style.display = "none";
  return true;
}

$(document).ready(function()
{
	$("input").click(function(event)
	{
		this.select();
	});

	$("p.options").click(function(event)
	{
		$("p.options").hide();
	 	$("#upload").hide();
		$(".hide").show();
	});
});
</script>
</body>
</html>
