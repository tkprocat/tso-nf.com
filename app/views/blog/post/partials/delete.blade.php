<div class="modal fade" id="confirmDeleteBlogModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <p></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="deleteBlogConfirmed">Delete</button>
            </div>
            <input type="hidden" id="deleteID" value="0" />
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $('.deleteBlogPostBtn').click(function (e) {
        var title = $(e.currentTarget).closest('.panel-default').find('.panel-heading').text();
        $('#confirmDeleteBlogModal h4.modal-title').text("Please confirm deletion");
        $('#confirmDeleteBlogModal .modal-body p').html("Are you sure you want to delete the blog post titled <strong>" + title + "</strong> ?");
        $('#deleteID').val($(e.currentTarget).attr('data-target'));
        $('#confirmDeleteBlogModal').modal('toggle');
        e.preventDefault();
    });

    $('#deleteBlogConfirmed').click(function(e) {
       alert($('#deleteID').val());
    });
</script>