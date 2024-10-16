$(document).ready(function () {
  window._token = $('meta[name="csrf-token"]').attr('content')

  moment.updateLocale('en', {
    week: {dow: 1} // Monday is the first day of the week
  })

  $('.date').datetimepicker({
    format: 'YYYY-MM-DD',
    locale: 'en',
    icons: {
      up: 'fas fa-chevron-up',
      down: 'fas fa-chevron-down',
      previous: 'fas fa-chevron-left',
      next: 'fas fa-chevron-right'
    }
  })

  $('.datetime').datetimepicker({
    format: 'YYYY-MM-DD HH:mm:ss',
    locale: 'en',
    sideBySide: true,
    icons: {
      up: 'fas fa-chevron-up',
      down: 'fas fa-chevron-down',
      previous: 'fas fa-chevron-left',
      next: 'fas fa-chevron-right'
    }
  })

  $('.timepicker').datetimepicker({
    format: 'HH:mm:ss',
    icons: {
      up: 'fas fa-chevron-up',
      down: 'fas fa-chevron-down',
      previous: 'fas fa-chevron-left',
      next: 'fas fa-chevron-right'
    }
  })

  $('.select-all').click(function () {
    let $select2 = $(this).parent().siblings('.select2')
    $select2.find('option').prop('selected', 'selected')
    $select2.trigger('change')
  })
  $('.deselect-all').click(function () {
    let $select2 = $(this).parent().siblings('.select2')
    $select2.find('option').prop('selected', '')
    $select2.trigger('change')
  })

  $('.select2').select2()

  $('.treeview').each(function () {
    var shouldExpand = false
    $(this).find('li').each(function () {
      if ($(this).hasClass('active')) {
        shouldExpand = true
      }
    })
    if (shouldExpand) {
      $(this).addClass('active')
    }
  })

  $('.c-header-toggler.mfs-3.d-md-down-none').click(function (e) {
    $('#sidebar').toggleClass('c-sidebar-lg-show');

    setTimeout(function () {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    }, 400);
  });

})

$(document).on('click', '#ajaxSubmit', function (e) {

  e.preventDefault();
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  var data = $('#save_record').serialize();
  $.ajax({
      type: "POST",
      url: $(this).attr('action'),
      data: data,
      beforeSend:function()
      {
          $("#ajaxSubmit").prop('disabled', true);
          //displayLoader();
      },
      success: function (data) {
         

          $("#ajaxSubmit").prop('disabled', false);
          if(data.errors)
          {
               console.log(data.errors);
              $('.alert-danger').html('');
              $.each(data.errors, function(key, value){
                  $('.alert-danger').show();
                  $('.alert-danger').append('<li>'+value+'</li>');
              });
          }else{
              if (data.status == 'ok'){
                  console.log(data.url)
                  pnotice('ok');
                  var url = data.url;
                          window.open(url, "_blank");
              }
              else
                  pnotice('error');

              $("#dotmodal").modal('hide');
              $("#save_record")[0].reset();
              oTable.ajax.reload();
          }
      }
  });


});
