<?php
  /* Template Name: How it works template */
  get_header();
  
?>

<main class="how-it-works-container">
    <div class="hero flex-center">
        <h2 class="text-center" id="main-content">How Wushka Works</h2>
    </div>
    <?php
      $extension = pathinfo($_SERVER['SERVER_NAME'], PATHINFO_EXTENSION);  
  ?>
    <div class="main container">
        <p class="intro text-center">
            Wushka is a<?php if($extension != "nz"){ ?>n Australian-developed,<?php } ?> cloud-based digital reading
            program
            accessible from all common browsers and devices. <br />Wushka offers 2
            specialised digital reading libraries, the Wushka Levelled Library with
            688 levelled digital readers and the Wushka Decodable Library with 408 decodable digital readers.
        </p>

        <section>
            <h2 class="text-center">Here's How It Works</h2>

            <section class="article-content">
                <div class="article-image flex-center">
                    <button type="button" class="cta cta-align">
                        <span class="tap-text">Tap to Enlarge</span>
                        <span class="click-text">Click to Enlarge</span>
                    </button>
                    <picture>
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/reading-boxes.webp"
                            type="image/webp" />
                        <source srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/reading-boxes.png"
                            type="image/png" />
                        <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/reading-boxes.png"
                            alt="Reading Boxes" data-name="reading-boxes" />
                    </picture>
                </div>
                <div class="article-text">
                    <h3>Reading Boxes</h3>
                    <p>
                        Within our libraries, our reading boxes organise readers for
                        students and teachers in the same way readers would be organised
                        within classrooms - by reading level or by phonics phases - and
                        books can be selected and assigned to students or reading groups.
                    </p>
                </div>
            </section>
            <div id="article-popup" class="container hide">
                <div class="popup-close-wrapper">
                    <button type="button" class="popup-close popup-btn" aria-label="close">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/menu-close.svg"
                            alt="Close" />
                    </button>
                </div>
                <section class="popup-content">
                    <div class="article-image">
                        <div id="popup-picture-container"></div>
                    </div>
                    <div class="popup-zoom-buttons">
                        <button type="button" class="popup-btn popup-zoom-in">
                            <span class="sr-only">Zoom In</span>
                            <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/plus.svg" alt="" />
                        </button>
                        <button type="button" class="popup-btn popup-zoom-out" disabled>
                            <span class="sr-only">Zoom Out</span>
                            <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/minus.svg" alt="" />
                        </button>
                    </div>
                    <div class="article-text">
                        <h3 id="popup-heading">#</h3>
                        <p id="popup-body">#</p>
                    </div>
                </section>
            </div>

            <hr />

            <section class="article-content">
                <div class="article-text">
                    <h3>Support Materials</h3>
                    <p>
                        Support materials are provided for every reader including online
                        comprehension quizzes, printable lessons plans, literacy activities,
                        blackline masters, sequencing templates, discussion cards and
                        printable take-home books, both complete and wordless. Assessment
                        materials are also provided in the form of reading records.
                    </p>
                </div>
                <div class="article-image flex-center">
                    <button type="button" class="cta">
                        <span class="tap-text">Tap to Enlarge</span>
                        <span class="click-text">Click to Enlarge</span>
                    </button>
                    <picture>
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/support-materials.webp"
                            type="image/webp" />
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/support-materials.png"
                            type="image/png" />
                        <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/support-materials.png"
                            alt="Support Materials" data-name="support-materials" />
                    </picture>
                </div>
            </section>

            <hr />

            <section class="article-content">
                <div class="article-image flex-center">
                    <button type="button" class="cta cta-align">
                        <span class="tap-text">Tap to Enlarge</span>
                        <span class="click-text">Click to Enlarge</span>
                    </button>
                    <picture>
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/quizzes.webp"
                            type="image/webp" />
                        <source srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/quizzes.png"
                            type="image/png" />
                        <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/quizzes.png"
                            alt="Comprehension Quizzes" data-name="quizzes" />
                    </picture>
                </div>
                <div class="article-text">
                    <h3>Comprehension Quizzes</h3>
                    <p>
                        Comprehension quizzes can be set as compulsory, optional or turned
                        off altogether at the teacher’s discretion. Quizzes focus on
                        literal, inferential and evaluative questioning.
                    </p>
                </div>
            </section>

            <hr />

            <section class="article-content">
                <div class="article-text">
                    <h3>Class Management</h3>
                    <p>
                        Teachers can manage their own class and set individual student
                        reading profiles for school and home reading, easily editing
                        settings such as reading levels, phonics phases, reading groups,
                        access permissions and quiz and narration options.
                    </p>
                </div>
                <div class="article-image flex-center">
                    <button type="button" class="cta cta-align">
                        <span class="tap-text">Tap to Enlarge</span>
                        <span class="click-text">Click to Enlarge</span>
                    </button>
                    <picture>
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/class-management.webp"
                            type="image/webp" />
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/class-management.png"
                            type="image/png" />
                        <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/class-management.png"
                            alt="Class Management" data-name="class-management" />
                    </picture>
                </div>
            </section>

            <hr />

            <section class="article-content">
                <div class="article-image flex-center">
                    <button type="button" class="cta cta-align">
                        <span class="tap-text">Tap to Enlarge</span>
                        <span class="click-text">Click to Enlarge</span>
                    </button>
                    <picture>
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/reading-groups.webp"
                            type="image/webp" />
                        <source srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/reading-groups.png"
                            type="image/png" />
                        <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/reading-groups.png"
                            alt="Reading Groups" data-name="reading-groups" />
                    </picture>
                </div>
                <div class="article-text">
                    <h3>Reading Groups</h3>
                    <p>
                        Wushka allows teachers to set up reading groups for independent and
                        instructional reading for the students in their class. The teacher
                        decides when students can access the groups - at school, at home or
                        at any time. In a few steps, teachers can set up a new reading
                        group, allocate the appropriate levelled readers and assign students
                        to the group.
                    </p>
                </div>
            </section>

            <hr />

            <section class="article-content">
                <div class="article-text">
                    <h3>Class & Student Statistics</h3>
                    <p>
                        Ongoing reading statistics are available at individual student and
                        whole class level and provide detailed insights into students’ level
                        of interaction, comprehension and progression, both at school and at
                        home. Via the statistics pages, teachers can see which readers
                        students have read, how long they read for and what time they read.
                        Detailed quiz results are also available for each student showing
                        the score for each quiz and highlighting which questions they
                        answered correctly or incorrectly. Quiz reports can be downloaded
                        for each student, or the whole class.
                    </p>
                </div>
                <div class="article-image flex-center">
                    <button type="button" class="cta">
                        <span class="tap-text">Tap to Enlarge</span>
                        <span class="click-text">Click to Enlarge</span>
                    </button>
                    <picture>
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/statistics.webp"
                            type="image/webp" />
                        <source srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/statistics.png"
                            type="image/png" />
                        <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/statistics.png"
                            alt="Class & Student Statistics" data-name="statistics" />
                    </picture>
                </div>
            </section>

            <hr />

            <section class="article-content">
                <div class="article-image flex-center">
                    <button type="button" class="cta">
                        <span class="tap-text">Tap to Enlarge</span>
                        <span class="click-text">Click to Enlarge</span>
                    </button>
                    <picture>
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/school-management.webp"
                            type="image/webp" />
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/school-management.png"
                            type="image/png" />
                        <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/school-management.png"
                            alt="Whole School Management" data-name="school-management" />
                    </picture>
                </div>
                <div class="article-text">
                    <h3>Whole School Management</h3>
                    <p>
                        The Literacy Program Coordinator has access across all classes so
                        can monitor usage and engagement and track progress and achievement
                        across year level cohorts. Program Coordinators can assign multiple
                        ‘teachers’ to any class to enable literacy support teachers and
                        aides to have access for small-group and one-on-one teaching.
                    </p>
                </div>
            </section>

            <hr />

            <section class="article-content">
                <div class="article-text">
                    <h3>Student Dashboard</h3>
                    <p>
                        The Student Dashboard is simple dashboard featuring the reading
                        boxes and readers assigned to students by their teacher. The
                        dashboard also shows readers students have completed and the results
                        of their quizzes. Students log in to Wushka using the username and
                        password provided by their teacher. Teachers can also log in for
                        their students via the Teacher Dashboard.
                    </p>
                </div>
                <div class="article-image flex-center">
                    <button type="button" class="cta">
                        <span class="tap-text">Tap to Enlarge</span>
                        <span class="click-text">Click to Enlarge</span>
                    </button>
                    <picture>
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/student-dashboard.webp"
                            type="image/webp" />
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/student-dashboard.png"
                            type="image/png" />
                        <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/student-dashboard.png"
                            alt="Student Dashboard" data-name="student-dashboard" />
                    </picture>
                </div>
            </section>
        </section> 
    </div>
    <div class="text-center">
        <button type="button" class="btn-request" data-toggle="modal" data-target="#wk-form-modal"
            id="request-free-trial">
            <?= wushka_cta_button_text(); ?>
        </button>
    </div>
    <div class="bubbles">
        <div class="b1">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/bubbles-green.webp"
                    type="image/webp" />
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-green.png"
                    type="image/png" />
                <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-green.png"
                    alt="" />
            </picture>
        </div>

        <div class="b2">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/bubbles-orange-small.webp"
                    type="image/webp" />
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-orange-small.png"
                    type="image/png" />
                <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-orange-small.png"
                    alt="" />
            </picture>
        </div>

        <div class="b3">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/bubbles-purple.webp"
                    type="image/webp" />
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-purple.png"
                    type="image/png" />
                <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-purple.png"
                    alt="" />
            </picture>
        </div>

        <div class="b4">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/bubbles-orange-large.webp"
                    type="image/webp" />
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-orange-large.png"
                    type="image/png" />
                <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-orange-large.png"
                    alt="" />
            </picture>
        </div>

        <div class="b5">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/bubbles-mix.webp"
                    type="image/webp" />
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-mix.png"
                    type="image/png" />
                <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-mix.png" alt="" />
            </picture>
        </div>

        <div class="b6">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/bubbles-blue.webp"
                    type="image/webp" />
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-blue.png"
                    type="image/png" />
                <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-blue.png" alt="" />
            </picture>
        </div>
    </div>
</main>


 

<script>
var popup = document.getElementById("article-popup");
var articles = document.getElementsByClassName("article-content");

function getOffset(element) {
    if (!element.getClientRects().length) {
        return {
            top: 0,
            left: 0
        };
    }
    let rect = element.getBoundingClientRect();
    let win = element.ownerDocument.defaultView;
    return {
        top: rect.top + win.pageYOffset,
        left: rect.left + win.pageXOffset,
    };
}

function setOffset(element, offset) {
    element.style.position = "absolute";
    // factor in popup height
    element.style.top = offset.top - 750 + "px";
}

function createPopup(i) {
    const article = articles[i];
    const heading = article.querySelector("h3").innerHTML;
    const body = article.querySelector("p").innerHTML;

    const baseUrl = "<?php echo get_template_directory_uri(); ?>";
    const imageName = article.querySelector("img").dataset.name;
    const newPopupImage = `
    <picture id="popup-picture">
      <source
        srcset="${baseUrl}/img/how-it-works/extra/webp/${imageName}-2x.webp"
        type="image/webp"
      />
      <source
        srcset="${baseUrl}/img/how-it-works/${imageName}-2x.png"
        type="image/png"
      />
      <img id="popup-image" src="${baseUrl}/img/how-it-works/${imageName}-2x.png" alt="Pop Up Box"/>
    </picture>
    `;

    if (i % 2) {
        popup.querySelector(".popup-content").classList.add("content-reverse");
    } else {
        popup.querySelector(".popup-content").classList.remove("content-reverse");
    }

    popup.querySelector("#popup-heading").innerHTML = heading;
    popup.querySelector("#popup-body").innerHTML = body;
    popup.querySelector("#popup-picture-container").innerHTML = newPopupImage;
}

function setButtonsEnlarge() {
    for (let i = 0; i < articles.length; i++) {
        articles[i].querySelector(".cta").addEventListener("click", function() {
            createPopup(i);
            setOffset(popup, getOffset(articles[i].querySelector(".cta")));
            if (i == articles.length - 1) popup.classList.add("final");
            if (i != articles.length - 1) popup.classList.remove("final");
            popup.classList.remove("hide");
        });
    }
}

function handleOnZoom(zoom) {
    return function() {
        const popupImage = popup.querySelector(".article-image");

        if (zoom == "in") {
            const height = document.querySelector("#popup-image").height;
            popupImage.style.height = height + "px";
            popupImage.classList.add("popup-zoom");
            popup.querySelector(".popup-zoom-in").disabled = true;
            popup.querySelector(".popup-zoom-out").disabled = false;

            // center zoom in
            popupImage.scroll(
                (popupImage.querySelector("#popup-image").width -
                    popupImage.offsetWidth) /
                2,
                0
            );
        }
        if (zoom == "out") {
            popupImage.style.height = "auto";
            popupImage.classList.remove("popup-zoom");
            popup.querySelector(".popup-zoom-in").disabled = false;
            popup.querySelector(".popup-zoom-out").disabled = true;
        }
    };
}

function setButtonPopupClose() {
    popup.querySelector(".popup-close").addEventListener("click", function() {
        popup.classList.add("hide");
        popup.querySelector("#popup-heading").innerHTML = "#";
        popup.querySelector("#popup-body").innerHTML = "#";
        popup.querySelector("#popup-picture").innerHTML = "";
        handleOnZoom("out")();
    });
}

function setButtonsPopupZoom() {
    popup
        .querySelector(".popup-zoom-in")
        .addEventListener("click", handleOnZoom("in"));
    popup
        .querySelector(".popup-zoom-out")
        .addEventListener("click", handleOnZoom("out"));
}

function articleImagePush(){ 
    if($( window ).width() <= 768){
        $('.article-image').each(function(){
            $(this).parents('.article-content').prepend(this);
        });
    }else{
        $('.article-content .article-image').each(function(i){ 
            if(i %2 == 0){ 
                $(this).parents('.article-content').prepend(this);
            }else{ 
                $(this).parents('.article-content').append(this);
            }
        });
    }
}

articleImagePush();
setButtonsEnlarge();
setButtonsPopupZoom();
setButtonPopupClose();

const articlePopUp = '<div id="article-popup" class="container hide">' +
    '<div class="popup-close-wrapper">' +
    '<button type="button" class="popup-close popup-btn" aria-label="close">' +
    '<img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/menu-close.svg" alt="Close"/>' +
    '</button>' +
    '</div>' +
    '<section class="popup-content">' +
    '<div class="article-image">' + 
    '<div id="popup-picture-container"></div>' +
    '</div>' +
    '<div class="popup-zoom-buttons">' +
    '<button type="button" class="popup-btn popup-zoom-in">' +
    '<span class="sr-only">Zoom In</span>' +
    '<img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/plus.svg" alt=""/>' +
    '</button>' +
    '<button type="button" class="popup-btn popup-zoom-out" disabled>' +
    '<span class="sr-only">Zoom Out</span>' +
    '<img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/minus.svg" alt=""/>' +
    '</button>' +
    '</div>' +
    '<div class="article-text">' +
    '<h3 id="popup-heading">#</h3>' +
    '<p id="popup-body">#</p>' +
    ' </div>' +
    ' </section>' +
    ' </div>';


$('.cta').on('focus', function() {
     if($("#article-popup")){
        $("#article-popup").remove();
    } 
     
    $(this).parents('.article-content').after(articlePopUp);

    popup = document.getElementById("article-popup");
    articles = document.getElementsByClassName("article-content");

    setButtonsEnlarge();
    setButtonsPopupZoom();
    setButtonPopupClose();
});

$(window).on('resize', function(){
    articleImagePush();
});

</script>

 


<?php get_footer(); ?>