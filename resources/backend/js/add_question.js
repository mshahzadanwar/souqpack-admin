function removeChoiceSection(that){
	$(that).parent().parent().remove();
}
function moreChoice(that)
{

	$.post(base_url+'admin/questions/view_more_choice',{data:true},function(data){
		$("#add_more_choices_in_me").append(data);
	});
}
var already_shown = 1;
function toggleViewTypeChoices()
{
	if(already_shown==1)
	{
		$(".msq_type_div").hide();
		$("#text_type_div").show();
		already_shown = 2;
	}
	else{
		$("#text_type_div").hide();

		$(".msq_type_div").show();
		already_shown = 1;
	}
}