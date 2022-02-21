( ($) => {

// Delete a post 
$('a.delete').on('click',  function(e) {

  e.preventDefault();

  if ( confirm('Are you sure?') ) {

    var frm = $("<form>");
    frm.attr('method', 'post');
    frm.attr('action', $(this).attr('href'));
    frm.appendTo('body');
    frm.submit();
  }
});

// Validate new post form
$.validator.addMethod('dateTime', function(value, element) {
  return (value == '') || isNaN(Date.parse(value));
}, 'Must be a valid date time.');

$('#articleForm').validate({
  rules: {
    title: {
      required: true
    },
    content: {
      required: true
    },
    published: {
      dateTime: true
    },
  }
});

//Publish article
$('button.publish').on('click', function (e) {

  var id = $(this).data('id');
  var button = $(this);

  $.ajax({
    url: '/php_course/blog/admin/publish-article.php',
    type: 'POST',
    data: {id: id}
  })
  .done( function(data) {
    button.parent().html(data);
  })
  .fail( function(data) {
    alert("Opps! We are sorry it looks like an error occured");
  });

});

$('#published').datetimepicker( {
  format: 'Y-m-d H:i:s'
});

// Validate Contact Form 
$('#contactForm').validate({
  rules: {
    email: {
      required: true,
      eamil: true
    },
    subject: {
      required: true
    },
    message: {
      required: true
    },
  }
})


})(jQuery);