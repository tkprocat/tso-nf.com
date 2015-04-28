<div class="modal fade" id="confirmDeleteBlogCommentModal" tabindex="-1" role="dialog">
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
                <button type="button" class="btn btn-danger" id="deleteBlogCommentConfirmed">Delete</button>
            </div>
            <input type="hidden" id="deleteID" value="0" />
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    var blogComment;
    $('.deleteBlogCommentBtn').click(function(e) {
        blogComment = $(e.currentTarget).closest('.panel')
        var title = $(e.currentTarget).closest('.panel-default').find('.panel-heading').text();
        $('#confirmDeleteBlogCommentModal h4.modal-title').text("Please confirm deletion");
        $('#confirmDeleteBlogCommentModal .modal-body p').html("Are you sure you want to delete this comment?");
        $('#deleteID').val($(e.currentTarget).attr('data-target'));
        $('#confirmDeleteBlogCommentModal').modal('toggle');
        e.preventDefault();
    });

    $('#deleteBlogCommentConfirmed').click(function(e) {
       var id = $('#deleteID').val();
       $.ajax({
           url: '/blog/'+id,
           type: 'POST',
           data: {
               _method: 'DELETE',
               _token: '{{ csrf_token() }}'
           },
           success: function(data) {
             $('#confirmDeleteBlogCommentModal').modal('toggle');
             if (blogComment != null) {
                 blogComment.remove();
                 blogComment = null;
             }
           }
       });
    });
</script>