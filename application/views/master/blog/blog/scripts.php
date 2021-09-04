<script type="text/javascript">
function updateStatus(id,status) {
    if (status==0) {
    	var statusText = 'Pending';
    }else{
    	var statusText = 'Publish';
    }

    Swal.fire({
        title: "Confirm",
        text: "Are you sure you want to "+statusText+" this !",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#1FAB45",
        confirmButtonText: "Yes,"+statusText+" it.",
        cancelButtonText: "Cancel",
        buttonsStyling: true
    }).then((result) => {
            if (result.value == true) {
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url('blog-status')?>/" +id+"/"+status,
                    cache: false,
                    success: function(response) {
                        var json_data = jQuery.parseJSON(response);
                        if (json_data.status == "200") {
                            Swal.fire({
                                title: "Success!",
                                text: "Status Updated successfully!",
                                type: "success",
                                timer: 800
                            });
                            blog_tbl._fnAjaxUpdate();
                        } else {
                            Swal.fire(
                                "Internal Error",
                                "Oops,Error Occurred.",
                                "error"
                            )
                        };
                    }
                });
            } else {
                Swal.fire({
                    title: "Cancelled",
                    text: "Your data is safe Now! ",
                    type: "error",
                    timer: 800
                });
            }
        },
        function(dismiss) {
            if (dismiss === "cancel") {
                Swal.fire(
                    "Internal Error",
                    "Oops, Some Error Occurred.",
                    "error"
                );
            }
        })
}
</script>