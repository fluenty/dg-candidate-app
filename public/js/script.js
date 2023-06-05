$(document).ready(function() {
    $.noConflict();
    var list_table = $('#table-list').DataTable();

    $(document).off('click', '.btn-delete');
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var message = $(this).attr('data-message');
        var view = $(this).attr('data-view');
        var parent = $(this).attr('data-parent');
        var id = $(this).attr('data-id');
        swal({
            title: message,
            text: " ",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: view,
                    type: 'GET',
                    success: function(data) {
                        if (data.success) {

                            list_table.destroy();
                            $('.'+parent).remove();
                            $('#table-list').DataTable();
                            swal(
                                data.message,
                                ' ',
                                'success'
                            )
                        }else{
                            swal(
                                data.message,
                                ' ',
                                'error'
                            )
                        }
                    },
                    error: function() {
                        swal({
                          type: 'error',
                          title: 'Oops...',
                          text: 'Something went wrong!'
                      });
                    }
                });
            }
        });
    });

    $R('.redactor-textarea');

    $("#sort-scoring").sortable({
        placeholder: 'drop-placeholder',
        cursor: "move",
        update: function( event, ui ) {
            var token = $('meta[name="csrf-token"]').attr('content');
            var sortedScoring = $(this).sortable('toArray', {attribute: 'data-id'});
            var sortedScoring = $.map(sortedScoring, function(val, index) {
                var str = val;
                return str;
            }).join(",");

            var typeId = $("#typeId").val();

            $.ajax({
                url: '/admin/scoring/sort/update/'+typeId,
                type: 'POST',
                data:
                {
                    scoring: sortedScoring,
                    _token: token
                },
                success: function(data) {
                    swal({
                      type: data.type,
                      title: data.message,
                      position: 'top-end',
                      showConfirmButton: false,
                      toast: true,
                      timer: 3000
                    })
                },
                error: function() {

                }
            });
        }
    });
    $("#sort-scoring").disableSelection();

    $("#sort-question").sortable({
        placeholder: 'drop-placeholder',
        cursor: "move",
        update: function( event, ui ) {
            var token = $('meta[name="csrf-token"]').attr('content');
            var sortedQuestions = $(this).sortable('toArray', {attribute: 'data-id'});
            var sortedQuestions = $.map(sortedQuestions, function(val, index) {
                var str = val;
                return str;
            }).join(",");

            var clientId = $("#clientId").val();

            $.ajax({
                url: '/admin/question/sort/update/'+clientId,
                type: 'POST',
                data:
                {
                    questions: sortedQuestions,
                    _token: token
                },
                success: function(data) {
                    swal({
                      type: data.type,
                      title: data.message,
                      position: 'top-end',
                      showConfirmButton: false,
                      toast: true,
                      timer: 3000
                    })
                },
                error: function() {

                }
            });
        }
    });
    $("#sort-question").disableSelection();

    $("#accordion li > h4").click(function () {
        if ($(this).next().is(':visible')) {
            $(this).next().slideUp(300);
            $(this).children(".plusminus").text('+');
        } else {
            $(this).next("#accordion ul").slideDown(300);
            $(this).children(".plusminus").text('-');
        }
    });
} );

setTimeout(function(){
   $('#ytbg').fadeOut(2000);
   setTimeout(function(){
      $('.fadeIn').fadeIn();
  }, 2000);
}, 13000);

// Youtube plugin
$(function(){
  $('[data-youtube]').youtube_background({
    'loop': false,
    'mobile': true,
    'load-background': false
  });
});


