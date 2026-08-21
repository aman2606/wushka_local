<style>
    /* HTML */
    html, body {
        overflow-x: hidden;
    }
    body {
        background-image: none !important;
        font-size: 18px;
        line-height:28px;
        letter-spacing: 0.36px;
        color: #5E5E5E;
        font-weight: normal;
        position: relative;
        font-family:LatoRegular;
    }
    h1, h2, h3, h4, h5, h6 {
        font-weight: bold;
        line-height: 1.4;
    }
    img {
        max-width: 100%;
    }


    /* Bootstrap */
    .container {
        position: relative;
    }


    .btn.cta {
        background-color: #00BDF2;
        border-radius: 3px;
        color: #fff;
        font-size: 16px;
        text-transform: capitalize;
        padding: 1.25rem 4rem;
    }
    .cta__trial {
        margin: 4rem 0 4rem;
    }
    .cta__trial_mobile {
        display: none;
    }
    .cta.spaced {
        margin: 2.25rem 0;
    }
    .row.spaced {
        padding: 3rem 0 6rem;
    }
    .row.row_about {
        padding-top: 6rem;
    }
    .section__title {
        font: 40px/50px LatoBold;
        margin-bottom: 2rem;
        color: #5E5E5E;
    }
    .section__subtitle {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 2.5rem;
    }

    #subscription-offer{
        background-color: #00bef2 !important;
        border-color: #00bef2 !important;
        border-radius: 5px;
        padding: 12px 25px;

    }
    #subscription-offer:hover{
        border: 1px solid #fff !important;
    }

    .p-contact{
        font-size: 17px;
    }

    .navbar-wushka{
        border-bottom: 1px solid #E6E6E6;
    }

    .section__hr {
        border-top: 1px solid #999;
        margin: 3rem 0;
    }
    .shadowed {
        /* box-shadow: 0 0 5px rgba(0, 0, 0, 0.2); */
        box-shadow: 0px 3px 6px #00000029;
    }


    #hero {
        background-image: url('<?php echo get_template_directory_uri(); ?>/img/pages/students-using-wushka-classroom.jpg');
        background-size: cover;
        background-position: center;
        color: #fff;
        display: flex;
        align-items: center;
        min-height: 37rem;
        text-align: center;
        padding: 8rem 0 3.5rem;
    }
    #hero__title {
        font: 50px/60px LatoBold;
        text-shadow: 0px 3px 6px #0000001A;
    }


    .pinwheel {
        max-width: 6rem;
    }
    .book-collection {
        background-color: #FFBE02;
        color: #fff;
        border-radius: 10px;
        box-shadow: 0px 3px 6px #00000029;
        margin-top: 5rem;
        overflow: hidden;
    }
    .book-collection__head {
        background-color: #FFBE02;
        display: flex;
        justify-content: flex-start;
        padding: 1.5rem 2rem;
    }
    .book-collection__head p.m0{
        font:20px/28px LatoRegular;
    }
    .book-collection__head .fa {
        margin-right: 0.75rem;
    }
    .book-collection__books {
        background-color: #fff;
        display: flex;
        justify-content: space-around;
        padding: 0 2.25rem;
        margin: 0 auto;
    }
    .book-collection__book-wrapper {
        box-shadow: 0px 3px 6px #00000029;
        flex-basis: 13%;
        margin: 3.5rem 2.25rem;
        position: relative;
    }
    .book {
        border-radius: 10px;
        box-shadow: 0px 3px 6px #00000029;
        max-width: 100%;
    }
    .play-icon {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        max-width: 45%;
        z-index: 5;
    }
    .book-collection__progress {
        background-color: #FFBE02;
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 2rem 0.75rem;
    }
    .progress-body {
        line-height: 20px;
        margin: 0 1.875rem 0 0;
        font-size:16px;
    }
    .progress-bar {
        background-color: #fff;
        border-radius: 3px;
        flex-grow: 1;
        overflow: hidden;
        text-align: left;
    }
    .progress-bar::before {
        background-color: #FF3117;
        content: '0%';
        display: inline-block;
        padding: 0 0.625rem;
    }

    /* * { border: 1px solid rgba(0,0,0, 0.15); } */
    .icons {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        margin-top: 2.5rem;
    }
    .icon-wrapper {
        margin: 2.5rem 0.75rem;
        max-width: 18rem;
    }
    .icon-image {
        margin-bottom: 1.25rem;
        max-height: 4rem;
        max-width: 4rem;
    }
    .icon-body {
        font-size: 16px;
        line-height: 1.2;
        margin: 0;
    }


    .prevButton-img {
        transform: scaleX(-1);
    }


    .book-cards-container {
        display: flex;
    }
    .book-card {
        display: flex;
        flex-basis: 100%;
        align-items: center;
        height: 200px;
        margin: 2rem 2rem;
        max-width: 50%;
    }
    .book-card:hover .book,
    .book-card:focus .book,
    .book-card:active .book {
        box-shadow: 0px 3px 9px #00000070 !important;
    }
    .card {
        border-radius: 0 5px 5px 0;
        color: #fff;
        flex-direction: column;
        height: 85%;
        justify-content: space-between;
        padding: 2.5rem 3rem 1.25rem 1.5rem;
        text-align: left;
        display: flex;
        flex-basis: 120%;
        position: relative;
        z-index: 1;
    }
    .card.yellow {
        background: transparent linear-gradient(246deg, #E6BC03 0%, #FFD82E 100%) 0% 0% no-repeat padding-box;
    }
    .card.purple {
        background: transparent linear-gradient(247deg, #8E568E 0%, #EA8DEA 100%) 0% 0% no-repeat padding-box;
    }
    .card.black {
        background: transparent linear-gradient(243deg, #222121 0%, #898989 100%) 0% 0% no-repeat padding-box;
    }
    .card-title {
        line-height: 1.5;
        margin: 0;
        font-size:18px;
    }
    .level-container {
        border-top: 1px solid #fff;
        display: flex;
        padding-top: 1rem;
    }
    .level-container .fa {
        margin-right: 1rem;
        margin-top: 0.5rem;
    }
    .level-copy {
        line-height: 1.3;
        font-size:16px;
    }
    .book-wrapper {
        max-width: 26rem;
        position: relative;
    }
    .book-wrapper .book {
        position: relative;
        z-index: 2;
    }


    .cta-section {
        background-color: #F2FCFE;
        padding: 7rem 0;
    }


    #bubbles-1-purple {
        position: absolute;
        top: 20%;
        right: 0;
        transform: translateX(55%);
    }
    #bubbles-2-green {
        position: absolute;
        top: 40%;
        left: 0;
        transform: translateX(-55%);
    }
    #bubbles-3-red {
        position: absolute;
        top: 44%;
        right: 0;
        transform: translateX(55%);
    }
    #bubbles-4-mix {
        position: absolute;
        top: 10%;
        right: 0;
        transform: translateX(55%);
    }
    #bubbles-5-blue {
        position: absolute;
        top: -13%;
        left: 0;
        transform: translateX(-50%) rotate(-23deg);
    }


    .box-shadow_none {
        box-shadow: none !important;
    }
    .position_relative {
        position: relative !important;
    }

    @media screen and (max-width: 1200px) {
        .book-collection__books {
            padding: 0 1.75rem;
        }
        .book-collection__book-wrapper {
            margin: 2rem 1.75rem;
        }

        .book-cards-container {
            flex-direction: column;
            align-items: center;
        }

        .book-card {
            width: 46rem;
        }

        #bubbles-1-purple,
        #bubbles-2-green,
        #bubbles-3-red {
            display: none;
        }
    }
    @media screen and (max-width: 1024px) {
        #hero__title {
            font: 55px/65px LatoBold;
        }
        .book-collection__head {
            padding: 0.75rem 2rem;
        }

        .section_every-levelled {
            margin-left: auto;
            margin-right: auto;
            max-width: 80rem;
        }

        #bubbles-4-mix {
            top: 0;
        }
        #bubbles-5-blue {
            top: -80%;
        }
        .level-copy {
            line-height:28px;
        }
        .level-copy br{
            display:none;
        }
        .level-container {
            padding: 3rem 0 2rem 0;
        }
        .card-title {
            padding-top: 2rem;
        }
    }
    @media screen and (max-width: 992px) {
        #hero {
            background-image: url('<?php echo get_template_directory_uri(); ?>/img/pages/students-wushka-ipad@2x.jpg');
        }
        .icons {
            justify-content: space-around;
        }
        .icon-wrapper {
            max-width: 16rem;
        }
        .icon-body {
            word-break: break-word;
        }
        .book-collection__head p.m0{
            line-height:26px;
        }
        .book-card {
            width: 60rem;
            max-width: 60%;
        }
    }
    @media screen and (min-width: 769px) {
        p.px20 {
            padding: 0;
        }
    }
    @media screen and (max-width: 768px) {
        #hero {
            padding: 4.5rem 0 3.5rem;
        }
        .cta__trial {
            margin: 2rem 0 3rem;
        }
        .cta-section {
            text-align: center;
        }
        .cta-section__image {
            max-width: 80%;
        }

        #bubbles-5-blue {
            top: -55%;
        }
        .btn.cta {
            font-size: 18px;
        }
    }
    @media screen and (max-width: 576px) {

        .book-collection__head {
            padding-top: 2rem;
        }
        .book-collection__books {
            flex-wrap: wrap;
            padding: 2.5rem 0.5rem;
            width: calc(100% - 1.5rem);
        }
        .book-collection__book-wrapper {
            flex-basis: 28%;
            margin: 0.5rem;
        }
        .book-collection__progress {
            flex-wrap: wrap;
            padding-top: 2.5rem;
            padding-bottom: 2.5rem;
        }
        .progress-body {
            flex-basis: 100%;
            margin-bottom: 1rem;
            text-align: left;
        }
        .progress-bar {
            flex-basis: 100%;
        }

        .icons {
            flex-wrap: wrap;
        }
        .icon-wrapper {
            flex-basis: 45%;
            max-width: none;
        }
        .icon-body {
            margin: 0 auto;
        }

        .book-card {
            flex-direction: column;
        }
        .card {
            border-radius: 5px;
            padding: 7rem 2.75rem 2rem;
            min-height: 20rem;
            max-width: 37rem;
            width: 80vw;
            position: relative;
            top: -4rem;
        }

        .cta__trial_desktop {
            margin: -1rem 0 3rem;
        }
        .cta__trial_desktop {
            display: none;
        }
        .cta__trial_mobile {
            display: inline-block;
            margin: 3.5rem 0 0;
        }
        .cta-section__image {
            max-width: 100%;
        }

        .bubble-artifact {
            display: none;
        }


        #bubbles-4-mix ,
        #bubbles-5-blue {
            display: none;
        }
        .card-title {
            padding-top: 0;
        }
        .level-container {
            padding: 1rem 0 0 0;
        }
    }
    @media screen and (max-width: 378px) {
        #hero__title, .section__title{
            font-size: 35px;
            line-height:45px;
        }
        .card {
            min-height: 18rem;
        }
        .level-container{
            padding-top:1.5rem;
        }
    }
</style>
<div class="position_relative">
    <div class="container text-center">
        <div class="row row_about spaced">
            <div class="col-sm-12">
                <p class="section__subtitle">
                    <img class="pinwheel" src="<?php echo get_template_directory_uri(); ?>/img/pages/pinwheel.png" alt>
                    Levelled Library</p>

                <h2 class="section__title">About the Wushka Levelled Library</h2>
                <p class="px20">Our levelled library consists of over 660 fiction and non-fiction levelled readers carefully levelled to support primary school students who are learning to read. The Wushka Levelled Library coloured reading boxes align with all common reading systems and provide coverage from Kindergarten through to Year 6.</p>

                <div class="book-collection">
                    <div class="book-collection__head">
                        <p class="m0"><i class="fa fa-signal fa-lg" aria-hidden="true"></i> <strong>Yellow</strong> - Levels 6-8</p>
                    </div>
                    <div class="book-collection__books">
                        <div class="book-collection__book-wrapper">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/look-at-my-face@2x.jpg" alt="Book - Look at my Face" class="book">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/play-icon.svg" alt="" class="play-icon">
                        </div>
                        <div class="book-collection__book-wrapper">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/lets-go-to-the-market@2x.jpg" alt="Book - Let's go to the Market" class="book">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/play-icon.svg" alt="" class="play-icon">
                        </div>
                        <div class="book-collection__book-wrapper">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/i-want-an-ice-cream@2x.jpg" alt="Book - I want an Ice-Cream" class="book">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/play-icon.svg" alt="" class="play-icon">
                        </div>
                        <div class="book-collection__book-wrapper">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/a-new-balloon@2x.jpg" alt="Book - A New Balloon" class="book">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/play-icon.svg" alt="" class="play-icon">
                        </div>
                        <div class="book-collection__book-wrapper">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/my-shadow@2x.jpg" alt="Book - My Shadow" class="book">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/play-icon.svg" alt="" class="play-icon">
                        </div>
                        <div class="book-collection__book-wrapper">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/welcome-to-the-desert@2x.jpg" alt="Book - Welcome to the Desert" class="book">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/play-icon.svg" alt="" class="play-icon">
                        </div>
                    </div>
                    <div class="book-collection__progress">
                        <p class="progress-body">Readers Completed</p>
                        <div class="progress-bar"></div>
                    </div>

                </div>
                <p class="py30 p-contact">You can add the Wushka Levelled Library if you upgrade to our Wushka Plus subscription.  Contact us for more information using the form below.</p>
                <a class="navbar-btn btn btn-primary"  href="/contact-us" id="subscription-offer">Contact Us</a>
            </div>
        </div>
    </div>
</div>