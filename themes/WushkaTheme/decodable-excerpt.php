<style>
    body.page-template-decodable-library-php{
        background:#fff;
    }
    img{
        max-width:100%;
        height:auto;
    }
    .decodable-digital-wrap{
        background:#fff;
        margin:0;
        padding:0;
        width:100%;
        position:relative;
        overflow:hidden;
    }
    /*---BUBBLES BACkGROUND CSS CODES---*/
    .bubbles{
        width:100%;
        overflow:hidden;
    }
    .b1, .b2, .b3, .b4, .b5 {
        position: absolute;
        z-index: 0;
    }
    .b1 {
        right: -50px;
        top: 16%;
    }
    .b2 {
        left: -65px;
        top: 40%;
        transform: scale(1.6);
    }
    .b3 {
        right: -25px;
        top: 50.5%;
    }
    .b4 {
        right: -55px;
        top: 74%;
        z-index:10;
    }
    .b5 {
        left: -60px;
        top: 88%;
        transform: rotate(-25deg);
    }
    .navbar-wushka{
        border-bottom: 1px solid #E6E6E6;
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


    .p-contact {
        font-size: 17px;
        color: #5E5E5E;
    }

    /* --- Heading and Texts Css Block --- */
    .track-wrapper.decodable-digital .tracks-heading{
        font: 50px/1.2 LatoBold;
        letter-spacing: 0px;
        color: #FFFFFF;
        text-shadow: 0px 3px 6px #0000001A;
        margin:0;
        padding:0;
    }
    .sub-title{
        text-align: left;
        font: 25px/1.2 LatoBold;
        letter-spacing: 0.5px;
        color: #5E5E5E;
        margin: 0;
    }
    .sub-heading{
        font: 40px/1.12 LatoBold;
        letter-spacing: 0.8px;
        color: #5E5E5E;
        margin:0 0 20px 0;
    }
    .about-wushka .sub-heading, .scope-sequence .sub-heading, .decodable-reader .sub-heading{
        text-align:center;
    }
    .decodable-reader .sub-heading{
        margin:0 5% 20px 5%;
    }
    .about-wushka .sub-heading{
        margin-top:20px;
    }
    .para{
        font: 18px/1.6 LatoRegular;
        letter-spacing: 0.2px;
        color: #5E5E5E;
        margin:0 0 30px 0;
        padding:0;
    }
    .about-wushka .para{
        margin-bottom:5rem;
    }
    /*---  Border Radius, Gradient and color css ---*/
    .radius-box{
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        padding:22px 20px;
        margin:0 0 20px 0;
        box-shadow: 0px 3px 6px #00000033;
    }
    .radius-box:last-child{
        margin:0;
    }
    .dark-blue{
        background:#1B698D;
    }
    .dark-orange{
        background:#F7951B;
    }
    .dark-purple{
        background:#2F2770;
    }
    .dark-green{
        background:#8CC63E;
    }
    .light-purple{
        background:#92268B;
    }
    .dark-red{
        background:#EA2725;
    }
    .orange-grad{
        background: transparent linear-gradient(246deg, #F7941D 60%, #F0C898 130%) 0% 0% no-repeat;
    }
    .purple-grad{
        background: transparent linear-gradient(247deg, #2F286F 60%, #B9B4E5 130%) 0% 0% no-repeat;
    }
    .pink-grad{
        background: transparent linear-gradient(243deg, #92278F 60%, #EAAFE8 130%) 0% 0% no-repeat;
    }
    .border-bottom{
        margin:0 0 8rem 0;
        padding:0 0 10rem 0;
    }
    .decodable-digital-wrap .btn.btn-blue{
        background:#00BDF2;
        border: 1px solid #00BDF2;
        border-radius: 3px;
        font: 16px/1 LatoRegular;
        letter-spacing: 0px;
        color: #FFFFFF;
        margin:4rem 0 5rem 0;
        padding:12px 30px;
        min-width:150px;
    }
    /* --- PAGES Css --- */
    .track-wrapper.decodable-digital{
        background-image: url("/wp-content/themes/WushkaTheme/img/decodable-library/decodable-digital-reading-library-for-emergent-early-readers.jpg");
        background-position: center top;
        background-repeat: no-repeat;
        background-size: cover;
        min-height: 415px;
    }
    .about-wushka{
        margin-top:5rem;
    }
    .phase-set-wrap{
        box-shadow: 0px 3px 6px #00000033;
        border-radius:10px;
        background:#fff;
        padding:0;
    }
    .panel-heading{
        -webkit-border-top-left-radius: 10px;
        -webkit-border-top-right-radius: 10px;
        -moz-border-radius-topleft: 10px;
        -moz-border-radius-topright: 10px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background: #F7941D;
        padding:20px;
        display:flex;
        flex-wrap:wrap;
        justify-content: flex-start;
        text-align: left;
        align-items: start;
        color:#fff;
    }
    .panel-heading p.m0{
        font-size:20px;
        line-height:28px;
    }
    .panel-heading .fa.fa-signal{
        margin-right:10px;
    }
    .phase-set-wrap .panel-body:before{
        display:none;
    }
    .panel-body-phase2{
        display:flex;
        flex-wrap:wrap;
        justify-content:space-evenly;
        align-items:center;
        padding:25px 20px;
    }
    .panel-body-phase2 .phase2-thumb{
        position:relative;
    }
    .panel-body-phase2 .phase2-thumb .play-btn{
        top:32%;
        left:18%;
        height:60px;
    }
    .library-wrap .panel-footer{
        -webkit-border-bottom-right-radius: 10px;
        -webkit-border-bottom-left-radius: 10px;
        -moz-border-radius-bottomright: 10px;
        -moz-border-radius-bottomleft: 10px;
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 10px;
        padding:8px 20px;
        background: #F7941D;
        text-align: left;
    }
    .progress-label{
        font: 16px/20PX LatoRegular;
        letter-spacing: 0.32px;
        color: #FFFFFF;
        padding-right:10px;
    }
    .library-wrap .panel-footer .progress{
        background: #FFFFFF;
    }
    .library-wrap .progress-bar{
        background: #EA2725;
        font: 13px/20px LatoRegular;
        color:#fff;
    }
    .phase-list{
        margin-top:5rem;
    }
    .phase-single{
        display:flex;
        flex-wrap:wrap;
        justify-content: left;
    }
    .level-bar{
        flex:0 0 25px;
        margin:1px 15px 0 0;
    }
    .phaseTitle{
        font: 20px/2rem LatoRegular;
        letter-spacing: 0.4px;
        color: #FFFFFF;
        padding:0;
        flex:0 0 95%;
    }
    .phaseTitle strong, #deco-caption strong{
        font-family: LatoBold;
    }
    .support-materials{
        padding:2rem 0 4rem 0;
        display: flex;
        justify-content:space-between;
    }
    .support-materials .heading{
        font: 16px/22px LatoRegular;
        letter-spacing: 0.5px;
        color: #5E5E5E;
        margin:10px 0 0 0;
        padding:0;
    }
    .svg-thumb{
        min-height:45px;
        max-height:45px;
        margin:0;
    }
    .para .click-here{
        display:inline-block;
        text-decoration:underline;
        font-weight:700;
        color:#5E5E5E;
        font-style: italic;
    }
    .para .click-here:hover{
        text-decoration:none;
        color:#5E5E5E;
    }
    .sample-row{
        display:flex;
        flex-wrap:wrap;
        justify-content: space-between;
        margin-top:4rem;
    }
    .sample-reader-single{
        position:relative;
        flex: 0 0 33.33%;
        padding:0 10px;
    }
    .sample-reader-single:nth-child(1){
        justify-content:flex-start;
    }
    .sample-reader-single:nth-child(2){
        justify-content:center;
    }
    .sample-reader-single:nth-child(3){
        justify-content:flex-end;
    }
    .sample-reader{
        box-shadow: 0px 3px 6px #00000029;
        border-radius: 5px;
        position:relative;
        z-index:10;
    }
    .sample-phase{
        padding:4rem 30px 2rem 5rem;
        max-width: 60%;
        position:relative;
        z-index:1;
        margin:15px 0 15px -10px;
        border-radius: 5px;
        flex: 0 0 60%;
    }
    .phase-box{
        display: flex;
        justify-content: flex-start;
    }
    .subTitle{
        font: 18px/20px LatoRegular;
        text-align: left;
        letter-spacing: 0.36px;
        color: #FFFFFF;
        margin:0 0 30px 0;
        padding:0 0 30px 0;
        border-bottom:1px solid #fff;
    }
    .phase-title{
        text-align: left;
        font: 16px/20px LatoBold;
        letter-spacing: 0.32px;
        color: #FFFFFF;
    }
    .level-thumb{
        min-width:22px;
        min-height:16px;
        margin:0 10px 0 0;
    }
    /*---BUY DECODABLE CSS---*/
    .buy-decodable{
        background: rgba(0, 189, 242, 0.05);
        padding:6.5rem 0;
        margin:5rem 0 0 0;
    }
    .buy-decodable .btn-blue {
        margin: 0;
    }
    .buy-decodable-readers{
        right:0;
        position:absolute;
        z-index:100;
        width:50%;
        top:50px;
        text-align:right;
    }
    .buy-decodable .para{
        width:50%;
        margin:0;
    }
    .desktop{
        display:block;
    }
    .mobile{
        display:none;
    }
    .decodable-reader .home-popover-wrapper {
        margin: 0;
    }
    .sample-reader-single .item-detail{
        display:flex;
        text-decoration:none;
        outline:none;
    }
    .sample-reader-single .item-detail{
        opacity:0.9;
        text-decoration:none;
        outline:none;
    }
    .book-wrapper{
        position:relative;
    }
    .play-icon {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        max-width: 45%;
        z-index: 100;
    }

    /* --- @media css codes --- */
    @media screen and (min-width: 1024px) {
        .buy-decodable{
            position:relative;
        }
        .decodable-digital-wrap .tracks-heading-wrapper {
            top: 58%;
            max-width:100%;
        }
        .support-materials .support-col{
            flex-grow: 1;
        }
    }
    @media screen and (max-width: 1200px)  and ( min-width: 1025px){
        .sample-phase {
            padding: 3rem 20px 20px 30px;
        }
        .subTitle {
            margin: 0px 0 20px 0;
            padding: 0 0 20px 0;
        }
    }
    @media screen and (max-width: 1024px) {
        .track-wrapper.decodable-digital {
            background-image: url("/wp-content/themes/WushkaTheme/img/decodable-library/main-banner-ipad.jpg");
        }
        .decodable-digital-wrap .tracks-heading-wrapper {
            max-width:100%;
            top:55%;
        }
        .track-wrapper.decodable-digital .tracks-heading br{
            display:none;
        }
        .track-wrapper.decodable-digital .tracks-heading{
            font-size:55px;
            line-height:1.2;
        }
        .phaseTitle {
            flex: 0 0 92%;
        }
        .sample-row {
            display:block;
        }
        .sample-reader-single {
            margin: 0 0 30px 0;
        }
        .sample-reader-single:last-child {
            margin: 0;
        }
        .sample-reader-single .item-detail {
            justify-content: center;
        }
        .b1, .b2, .b3{
            display:none;
        }
        .b4{
            top:70%;
        }
        .b5{
            top:84.5%;
        }
        .decodable-reader .sub-heading{
            margin-left:0;
            margin-right:0;
        }
        .buy-decodable .para{
            width:50%;
        }
        .decodable-thumb .smallThumb{
            max-height:150px;
        }
        .buy-thumb{
            max-width:95%
        }
        .para{
            font-size:18px;
        }
        .support-materials .heading{
            margin-top:10px;
        }
    }
    @media screen and (max-width: 991px) {
        .orange-grad{
            background: transparent linear-gradient(246deg, #F7941D 50%, #F0C898 150%) 0% 0% no-repeat;
        }
        .purple-grad{
            background: transparent linear-gradient(247deg, #2F286F 50%, #B9B4E5 150%) 0% 0% no-repeat;
        }
        .pink-grad{
            background: transparent linear-gradient(243deg, #92278F 50%, #EAAFE8 150%) 0% 0% no-repeat;
        }
        .phase-title-bar{
            padding:10px;
        }
        .panel-body-phase2 {
            padding: 20px;
            justify-content:space-around;
        }
        .panel-body-phase2 .phase2-thumb .phase2-img{
            max-height:100px;
        }
        .panel-body-phase2 .phase2-thumb .play-btn {
            top: 28%;
            left: 0%;
            height: 50px;
        }
        .btn.btn-blue, .decodable-digital-wrap .btn.btn-blue {
            padding: 15px 35px;
        }
        .decodable-digital-wrap .tracks-heading-wrapper{
            max-width: 90%;
            top: 55%;
        }
        .panel-heading .fa.fa-signal{
            float:left;
            margin-top:5px;
            max-height:50px;
        }
        .panel-heading p{
            line-height:20px;
        }
    }
    @media screen and (max-width: 768px) {
        .track-wrapper.decodable-digital {
            background-image: url("/wp-content/themes/WushkaTheme/img/decodable-library/tablet-decodable-digital-reading-library-for-emergent-early-readers.jpg");
        }
        .sub-heading{
            text-align:center;
        }
        .phaseTitle{
            font-size: 18px;
            line-height:24px;
            flex:0 0 90%;
        }
        .buy-decodable{
            text-align:center;
        }
        .buy-decodable .para {
            width: 100%;
        }
        .buy-decodable-readers{
            position:static;
            width:100%;
            text-align:center;
        }
        .buy-decodable {
            padding: 5rem 0;
        }
        .home-popover-wrapper .para{
            padding:0;
        }
        .decodable-thumb .deco-thumb{
            padding:10px;
        }
        .about-wushka .para{
            margin-bottom:3rem;
        }
        .decodable-reader .sub-heading {
            padding: 0;
        }
        .buy-thumb{
            max-width: 100%;
            display: block;
            margin: auto;
        }
        .b4 {
            top: 66.5%;
        }
        .b5{
            top: 80.5%;
        }
        .para {
            font-size: 18px;
        }
        .library-wrap .panel-footer .progress {
            margin: 0 0 5px 0;
        }
        .decodable-digital-wrap .btn.btn-blue{
            font-size:18px;
        }
        .panel-heading p.m0{
            font-size:18px;
            line-height:28px;
        }
        .level-bar {
            margin-top: 2px;
        }
    }
    @media screen and (max-width: 550px) {
        .track-wrapper.decodable-digital {
            background-image: url("/wp-content/themes/WushkaTheme/img/decodable-library/mobile-decodable-digital-reading-library-for-emergent-early-readers.jpg");
        }
        .track-wrapper.decodable-digital .tracks-heading{
            font-size: 40px;
            line-height:1.1;
        }
        .phaseTitle{
            flex:0 0 82%;
        }
        .support-materials{
            flex-wrap: wrap;
            padding:2rem 0;
        }
        .support-materials .support-col{
            flex: 0 0 50%;
            max-width: 50%;
        }
        .support-materials .heading{
            margin-bottom:5rem;
            font: 16px/20px LatoRegular;
            letter-spacing: 0.3px;
            color: #5E5E5E;
            margin: 5px 0 0 0;
        }
        .b4, .b5, .bubbles{
            display:none;
        }
        .desktop{
            display:none;
        }
        .mobile{
            display:block;
        }
        .decodable-thumb .smallThumb{
            max-height:120px;
        }
        .panel-body-phase2 .phase2-thumb{
            margin:5px 0;
        }
        .panel-body-phase2 .phase2-thumb .phase2-img{
            max-height:90px;
        }
        .panel-body-phase2 .phase2-thumb .play-btn {
            top: 30%;
            left: 18%;
            width: 40px;
            height: auto;
        }
        .level-bar {
            margin-right: 10px;
        }
    }

    @media screen and (max-width: 450px) {
        .panel-body-phase2 .phase2-thumb .phase2-img {
            max-height: 150px;
        }
        .border-bottom{
            margin:0 0 6rem 0;
            padding:0 0 8rem 0;
        }
        .decodable-digital-wrap .tracks-heading-wrapper {
            top: 50%;
        }
    }
    @media screen and (max-width: 375px) {
        .track-wrapper.decodable-digital .tracks-heading{
            font-size: 35px;
            line-height:1.3;
        }
        .phaseTitle{
            flex: 0 0 78%;
        }
        .panel-body-phase2{
            border-left: 8px solid #F7941D;
            border-right: 8px solid #F7941D;
            padding: 15px 20px;
            justify-content:space-between;
        }
        .panel-body-phase2 .phase2-thumb .phase2-img {
            max-height: 130px;
        }
        .panel-body-phase2 .phase2-thumb .play-btn {
            top: 35%;
            left: 25%;
            width: 40px;
            height: auto;
        }
        .sample-reader-single:nth-child(2) .book-wrapper, .sample-reader-single:nth-child(3) .book-wrapper{
            margin:8rem 0 0 0;
        }
        .sample-reader-single {
            max-width: 100%;
            flex: 0 0 100%;
            margin-bottom: 4rem;
        }
        .sample-reader-single .item-detail {
            display: block;
        }
        .sample-phase {
            padding: 60px 20px 20px 20px;
            max-width: 90%;
            margin: -4rem auto;
            flex: 0 0 90%;
        }
        .prevButton{
            margin:0 10px 0 0;
        }
        .decodable-reader .home-popover-wrapper{
            margin-top:4rem;
        }
        .home-popover-wrapper .para {
            padding: 0 10px;
        }
        .decodable-thumb{
            margin-top:10px;
        }
        .decodable-thumb .smallThumb{
            max-height:80px;
        }
        .scope-sequence{
            display:none;
        }
        .sample-decodable-readers{
            padding-top:5rem;
        }
        .buy-decodable .btn.btn-blue{
            margin-top: 3rem;
        }
        .support-materials {
            padding: 2rem 0;
        }
        .svg-thumb{
            margin:0;
        }
        .support-materials .heading {
            margin-bottom: 3rem;
        }
        .library-wrap .panel-footer{
            padding:10px;
            min-height:70px;
            border:none;
            margin:-1px 0 0 0;
        }
        .container-wrapper .row{
            margin-left:-10px;
            margin-right:-10px;
        }
        .container-wrapper .col-sm-12 {
            padding-left: 2px;
            padding-right: 2px;
        }
        .buy-decodable .col-sm-12{
            padding-left:5px;
            padding-right:5px;
        }
        .buy-decodable .para {
            width: 95%;
            margin: auto;
        }
        .para{
            line-height:28px;
            padding:0 22px;
        }
        .para .click-here {
            display: block;
        }
        .phase-set-wrap{
            margin:auto;
        }
        .sub-heading{
            font-size: 35px;
        }
    }
    @media screen and (max-width: 335px) {
        .panel-body-phase2 .phase2-thumb .phase2-img {
            max-height: 90px;
        }
        .panel-body-phase2 .phase2-thumb .play-btn {
            top: 30%;
            left: 20%;
            height: auto;
            width: 40px;
        }
        .panel-heading .fa.fa-signal {
            min-height: 72px;
        }
    }
    @media screen and (max-width: 300px) {
        .track-wrapper.decodable-digital .tracks-heading{
            font-size: 30px;
        }
        .sub-title{
            display:block;
            text-align: center;
            margin-bottom: 30px;
        }
        .sub-heading{
            font-size: 30px;
        }
        .support-materials .support-col{
            flex: 0 0 100%;
            max-width: 100%;
        }
        .para {
            line-height: 1.6;
            font-size:18px;
        }
    }
</style>

<section class="container-wrapper">
    <div class="container">
        <div class="row about-wushka">
            <article class="col-sm-12 col-md-12 col-lg-12 text-center">
                <h3 class="letters-sounds">
                    <picture>
                        <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/webp/icon-letters-sounds.webp" type="image/webp">
                        <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/icon-letters-sounds.png" type="image/jpeg">
                        <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/icon-letters-sounds.png" alt="Letter &amp; Sounds - decodable Library">
                    </picture>
                    <span class="sub-title">Decodable Library</span>
                </h3>
                <h2 class="sub-heading">About Wushka Decodable Library</h2>
                <p class="para">Our decodable library consists of 408 readers and supporting resources. The readers provide students with text-based context to
                        practise emerging reading skills and build confidence to master the phonics code. Our decodable
                        reading boxes are organised into six Phonics Phases, with fiction and non-fiction for each, and
                        specialty science readers.</p>
                <section class="library-wrap border-bottom">
                    <div class="phase-set-wrap">
                        <div class="panel-heading">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22.373" height="16.897" viewBox="0 0 22.373 16.897" class="level-bar">
                                <g id="Group_190" data-name="Group 190" transform="translate(0.001 0)">
                                    <rect id="Rectangle_7" data-name="Rectangle 7" width="3.256" height="16.897" rx="1.628" transform="translate(19.116 0)" fill="#fff"></rect>
                                    <rect id="Rectangle_8" data-name="Rectangle 8" width="3.256" height="12.705" rx="1.628" transform="translate(14.337 4.193)" fill="#fff"></rect>
                                    <rect id="Rectangle_9" data-name="Rectangle 9" width="3.256" height="9.553" rx="1.628" transform="translate(9.558 7.345)" fill="#fff"></rect>
                                    <rect id="Rectangle_10" data-name="Rectangle 10" width="3.256" height="7.182" rx="1.628" transform="translate(4.779 9.715)" fill="#fff"></rect>
                                    <rect id="Rectangle_11" data-name="Rectangle 11" width="3.256" height="5.4" rx="1.628" transform="translate(-0.001 11.498)" fill="#fff"></rect>
                                </g>
                            </svg>
                            <span class="phaseTitle"><strong>Phase 2 Set 1</strong>:  Letter Sounds - s, a, t, p, i, n, m, d, g, o, c, k, ck, e, u, r, h, b, f, ff, l, ll, ss</span>
                        </div>
                        <div class="panel-body-phase2">
                            <div class="phase2-thumb">
                                <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/pages/play-icon.svg" alt="" class="play-icon">
                                <picture>
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/webp/pat-tap-sat.webp" type="image/webp">
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/pat-tap-sat.jpg" type="image/jpeg">
                                    <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/pat-tap-sat.jpg" alt="Pat Tap Sat" class="phase2-img">
                                </picture>
                            </div>
                            <div class="phase2-thumb">
                                <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/pages/play-icon.svg" alt="" class="play-icon">
                                <picture class="phase2-thumb">
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/webp/a-man-in-a-pit.webp" type="image/webp">
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/a-man-in-a-pit.jpg" type="image/jpeg">
                                    <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/a-man-in-a-pit.jpg" alt="A man in a pit" class="phase2-img">
                                </picture>
                            </div>
                            <div class="phase2-thumb">
                                <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/pages/play-icon.svg" alt="" class="play-icon">
                                <picture class="phase2-thumb">
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/webp/a-cat-on-top.webp" type="image/webp">
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/a-cat-on-top.jpg" type="image/jpeg">
                                    <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/a-cat-on-top.jpg" alt="A cat on top" class="phase2-img">
                                </picture>
                            </div>
                            <div class="phase2-thumb">
                                <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/pages/play-icon.svg" alt="" class="play-icon">
                                <picture class="phase2-thumb">
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/webp/tap-tap-tap.webp" type="image/webp">
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/tap-tap-tap.jpg" type="image/jpeg">
                                    <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/tap-tap-tap.jpg" alt="Tap Tap Tap" class="phase2-img">
                                </picture>
                            </div>
                            <div class="phase2-thumb">
                                <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/pages/play-icon.svg" alt="" class="play-icon">
                                <picture class="phase2-thumb">
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/webp/a-man-sits.webp" type="image/webp">
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/a-man-sits.jpg" type="image/jpeg">
                                    <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/a-man-sits.jpg" alt="A man sits" class="phase2-img">
                                </picture>
                            </div>
                            <div class="phase2-thumb">
                                <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/pages/play-icon.svg" alt="" class="play-icon">
                                <picture class="phase2-thumb">
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/webp/phase-dog-on-a-mat.webp" type="image/webp">
                                    <source srcset="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/phase-dog-on-a-mat.jpg" type="image/jpeg">
                                    <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/decodable-library/phase-dog-on-a-mat.jpg" alt="Dog on a mat" class="phase2-img">
                                </picture>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="progress-label">Readers Completed</div>
                            <div class="progress">
                                <div class="progress-bar 1-awareness-speaking-listening" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 0%">0%</div>
                            </div>
                        </div>
                    </div>
                    <p class="py30 p-contact">You can add the Wushka Decodable Library if you upgrade to our Wushka Plus subscription.  Contact us for more information using the form below.</p>
                    <a class="navbar-btn btn btn-primary"  href="/contact-us" id="subscription-offer">Contact Us</a>
                </section>
            </article>
        </div>
    </div>
</section>