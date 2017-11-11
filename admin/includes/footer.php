  </div><br><br>

  <!-- Footer -->
  <footer>
         <div class="container">
            Copyright &copy; by Priyanka, Inga & Mona</a>
            All Rights Reserved from 2017 - 2027
         </div>
  </footer>

  <script>
  		function get_child_options(){
  			var parentID = $('#parent').val();
  			$.ajax({
  				url : "includes/child_categories.php",
  				type : "POST",
  				data : {parentID : parentID},
  				success: function(data){
  					$('#child').html(data);
  				},
  				error: function(){alert("Something went wrong with child options")},
  			});
  		}
  		$('select[name="parent"]').change(get_child_options);
  </script>
</body>
</html>