</div>

<!-- Footer -->
<footer class="text-center" id="footer">&copy; Copyright 2017-2019 Online Farm Products</footer>




<script type="text/javascript">
    $(window).scroll(function(){
    var vscroll = $(this).scrollTop();
    $('#logotext').css({
      "transform" : "translate(0px,"+vscroll/2+"px)"
    });

    var vscroll = $(this).scrollTop();
    $('#background').css({
      "transform" : "translate("+vscroll/5+"px,-"+vscroll/12+"px)"
    });

    var vscroll = $(this).scrollTop();
    $('#foreground').css({
      "transform" : "translate(0px,"+vscroll/2+"px)"
    });
  });

  function detailsmodal(id){
    var data = {"id" : id};
    $.ajax({
      url : <?=BASEURL;?> +'/includes/detailsmodal.php',
      method : "post",
      data : data,
      success : function(data){
        $('body').append(data);
        $('#details-modal').modal('toggle');
      },
      error : function(){
        alert("Something went wrong");
      }
    });
  }
</script>
</body>
</html>