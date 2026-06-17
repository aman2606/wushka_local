<?php
  /* Template Name: FAQ Template */
  get_header();
?>
 

<div class="faq-wrapper">
  <section class="banner-section">
    <div class="tracks-heading-wrapper">
        <h2 class="main-heading" id="main-content">Frequently Asked Questions</h2>
        <div class="search-box">
          <span class="search-icon"><img src="/wp-content/uploads/icon-search.svg" alt="Search icon" height="30"/></span> 
            <label for="pm-faq-search" class="sr-only">Don't waste time, type your question here and hit enter!</label>
            <input type="text" id="pm-faq-search" onkeyup="pmFaqSearch()" placeholder="Don't waste time, type your question here and hit enter!">
        </div>
      </div>
  </section>
  <section class="who-are-you">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <h2 class="sub-heading">Who are you?</h2>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
          <a href="#" onkeypress="schoolVisible(); clearMyField();" onclick="event.preventDefault(); schoolVisible(); clearMyField();" id="schoolBtn" class="whoBox active">
            <span class="tick-icon"><img src="/wp-content/uploads/icon-tick.svg" alt="Checked"/></span>
            <div class="who-icon"></div>
            <h3 class="who-title">I'm a School</h3>
          </a>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
          <a href="#" onkeypress="parentVisible(); clearMyField();" onclick="event.preventDefault(); parentVisible(); clearMyField();" id="parentBtn" class="whoBox">
            <span class="tick-icon"><img src="/wp-content/uploads/icon-tick.svg" alt="Checked"/></span>
            <div class="who-icon"></div>
            <h3 class="who-title">I'm a Parent</h3>
          </a>
        </div>
      </div>
    </div>
  </section>
  <section class="faq-below">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <h2 class="sub-heading withBorder"><span>See FAQ’s below</span></h2>
        </div>
      </div>
      <?php 
        if( have_posts() ) : 
          while( have_posts() ) : the_post(); 
            the_content(); 
          endwhile; 
        endif;
      ?>
    </div>
  </section>
</div>

<script>

  $(document).on('focus','#pm-faq-search', function(){
     $('.search-box').addClass('search-box-focus');
  });
  $(document).on('focusout','#pm-faq-search', function(){
     $('.search-box').removeClass('search-box-focus');
  });
 

  const parentTabSelected = window.location.search === "?parent";

  function pmFaqSearch() {
    var input, filter, faqs, button, i;
    input = document.getElementById("pm-faq-search");
    filter = input.value.toUpperCase();
    faqs = document.getElementsByClassName("pm-faq");
    for (i = 0; i < faqs.length; i++) {
      button = faqs[i].getElementsByTagName("a");
      div = faqs[i].getElementsByTagName("div");
      if (button[0].innerHTML.toUpperCase().indexOf(filter) > -1 ||
          div[0].innerHTML.toUpperCase().indexOf(filter) > -1) {
        faqs[i].style.display = "";
      } else {
        faqs[i].style.display = "none";
      }
    }
  }
  var acc = document.getElementsByClassName("faqaccordion");
  var i;
  for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function(){
        event.preventDefault();
        this.classList.toggle("active");
        var faqpanel = this.nextElementSibling;
        if (faqpanel.style.display === "block") {
            faqpanel.style.display = "none";
        } else {
            faqpanel.style.display = "block";
        }
    }
  }
  acc.onclick = function() {
  }

  var parent = document.getElementById('parentBtn');
  var school = document.getElementById('schoolBtn');
  function schoolVisible() {
    document.getElementById('schoolFAQ').style.display = 'block';
    document.getElementById('parentFAQ').style.display = 'none';
    school.classList.add('active');
    parent.classList.remove('active');
  }
  function parentVisible() {
    document.getElementById('parentFAQ').style.display = 'block';
    document.getElementById('schoolFAQ').style.display = 'none';
    parent.classList.add('active');
    school.classList.remove('active');
  }
  function clearMyField() {
    var search = document.getElementById('pm-faq-search');
    search.value='';
    search.placeholder = "Search...";
    faqs = document.getElementsByClassName("pm-faq");
    for (i = 0; i < faqs.length; i++) {
      faqs[i].style.display = "";
    }
  }
  document.addEventListener('click', function (event) {
    if (event.target.matches('.subtitle')) {
      event.preventDefault();
      if (event.target.nextElementSibling.classList.contains("visible-class") ) {
        event.target.nextElementSibling.className = "hidden-content";
        event.target.classList.remove('active');
      } else {
        event.target.nextElementSibling.className = "visible-class";
        event.target.classList.add('active');
      }
    }
  }, false);

  if (parentTabSelected) {
    parentVisible();
    clearMyField();
  }
</script>

<?php get_footer(); ?>
