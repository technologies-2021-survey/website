	<a id="back-to-top"></a>
		<script>
			var btn = $('#back-to-top');

			$(window).scroll(function() {
				if ($(window).scrollTop() > 300) {
					btn.addClass('show');
				} else {
					btn.removeClass('show');
				}
			});

			btn.on('click', function(e) {
				e.preventDefault();
				$('html, body').animate({scrollTop:0}, '300');
			});

			window.sr = ScrollReveal();
			sr.reveal('.row', {duration: 1500,origin: 'bottom', reset: true});
			sr.reveal('.container', {duration: 1000,origin: 'bottom', reset: true});

			document.querySelectorAll('a[href^="#"]').forEach(anchor => {
				anchor.addEventListener('click', function (e) {
					e.preventDefault();

					document.querySelector(this.getAttribute('href')).scrollIntoView({
						behavior: 'smooth'
					});
				});
			});
		</script>
    </body>
</html>