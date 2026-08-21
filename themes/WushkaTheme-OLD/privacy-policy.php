<?php
/* Template Name: Privacy Policy Template*/
get_header();
$extension = pathinfo($_SERVER['SERVER_NAME'], PATHINFO_EXTENSION);

$teachingLink = 'teaching.com.au';
$wushkaLink = 'wushka.com.au';
if ($extension == 'nz') {
  $teachingLink = 'teaching.co.nz';
  $wushkaLink = 'wushka.co.nz';
}

?>
<style>
  .privacy-wrap .sub-title {
    margin-top: 1.5em !important;
  }

  .listing {
    list-style-type: disc !important;
  }
</style>


<div class="privacy-wrap">
  <div class="bubbles">
    <div class="b1">
      <picture>
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-green-orange.webp" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" type="image/jpeg">
        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" alt="">
      </picture>
    </div>
    <div class="b2">
      <picture>
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b2-purple-s2.webp" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png" type="image/jpeg">
        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png" alt="">
      </picture>
    </div>
    <div class="b3">
      <picture>
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-orange.webp" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-orange.png" type="image/jpeg">
        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-orange.png" alt="">
      </picture>
    </div>
    <div class="b4">
      <picture>
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-green-orange.webp" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" type="image/jpeg">
        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" alt="">
      </picture>
    </div>
    <div class="b5">
      <picture>
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/webp/bubbles-blue.webp" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-blue.png" type="image/jpeg">
        <img src="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-blue.png" alt="">
      </picture>
    </div>
    <div class="b6">
      <picture>
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b2-purple-s2.webp" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png" type="image/jpeg">
        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png" alt="">
      </picture>
    </div>
    <div class="b7">
      <picture>
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-orange.webp" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-orange.png" type="image/jpeg">
        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-orange.png" alt="">
      </picture>
    </div>
    <div class="b8">
      <picture>
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/webp/bubbles-mix.webp" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-mix.png" type="image/jpeg">
        <img src="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-mix.png" alt="">
      </picture>
    </div>
    <div class="b9">
      <picture>
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/webp/bubbles-blue.webp" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-blue.png" type="image/jpeg">
        <img src="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-blue.png" alt="">
      </picture>
    </div>
  </div>
  <div id="hero">
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <h2 class="hero-title">Privacy Policy</h2>
        </div>
      </div>
    </div>
  </div>
  <section class="container-wrapper" id="main-content">
    <div class="container">
      <div class="main">
        <h2 class="sub-title">Wushka Literacy Pty Ltd Privacy Policy</h2>
        <p class="main-para">This is the Privacy Policy of Wushka Literacy Pty Ltd (<strong>Wushka Literacy Pty Ltd</strong>, <strong>we</strong>, <strong>us</strong> and <strong>our</strong>). </p>
        <h2 class="sub-title">Open & transparent management of personal information</h2>
        <p class="main-para">Wushka Literacy Pty Ltd and our <a href="<?php echo get_site_url() ?>/wushka-associated-entities/" target="_blank">associated entities*</a> will observe this Privacy Policy for the collection, storage, protection and use of your personal information. “Personal information” is information or an opinion about an identified individual, or an individual who is reasonably identifiable from the information, whether the information is true or not and whether it is recorded in material form or not.</p>

        <h2 class="sub-title">Collection and use of Student information</h2>
        <p class="main-para">We understand the importance of protecting the personal information of students who use our digital products (students). We collect information about students from their teachers or authorised school and Government administrators. We only collect student information which is necessary to provide students with access to the resources, products and services that we supply. This may include collecting personal information such as their name, year level, school, class number, teacher name, digital learning account password/ password hints and in some cases the student’s school/ departmental email address.</p>
        <p class="main-para">We do not knowingly directly market to students. We use personal information of students submitted to us to allow the student to access and use our products and services and for digital products to generate a unique identifier by which class teachers and authorised school and Government administrators can monitor a child’s use of, or progress with our websites, resources, products and services. Student education records are only accessible to users who are logged-in to the relevant platform or website and have been successfully authenticated by entering a valid username and password. Teachers can only view students from within their school. Student data can only be accessed by our authorised staff and authorised third-party software developers who built the platform and authorised third party service providers (for the purposes of maintenance, system improvement and trouble-shooting). Each third-party software developer and service provider is subject to confidentiality/ non-disclosure obligations.</p>

        <h2 class="sub-title">What personal information do we collect and hold about you?</h2>
        <p class="main-para">The personal information we may collect about you depends on the nature of your interaction with us and may include your name, date of birth, gender, whether you are a parent, teacher, educator, administrator, your e-mail addresses, name of employer, school, class name, teaching subjects, interest areas, educational institution or company, ABN or ACN, postal and/or delivery addresses, title, contact telephone number(s), social handle(s), facsimile number(s), country, product purchases or preferences, and account history information, attended conferences or training, information to process payments, password and password hints for account access, web form information, cookie and tracking information, browse history information, and encrypted billing information and if you are an employee, contractor or visitor to our premises COVID 19 health and vaccination status and thermal temperature readings (as permitted by applicable law and/or in compliance with applicable public health directives). If connected to a business we may also collect the names and address of the directors, proprietors and owners, any personal guarantors and reports from credit reporting agencies, which is collected as part of our credit application processes. If you are a job applicant, contractor or employee, we may collect your qualifications and work history, information about your employer or the organisation you represent, your tax file number and superannuation information.</p>

        <h2 class="sub-title">What happens if you do not provide your personal information? </h2>
        <p class="main-para">
          You may choose not to give Wushka Literacy Pty Ltd any personal information we request, but that may mean we cannot provide you with some or all of the products or services you have requested or we may not be able to do business with you effectively.
        </p>

        <h2 class="sub-title">How do we collect personal information?</h2>
        <p class="main-para">Generally, we will collect personal information directly from you. We may collect and update your personal information in person, over the phone, by email and when payments are made to us. We may also collect and update your personal information through your interactions, use, and experiences with our websites, platforms, products and services, from our associated entities, public sources (including the internet and social media) and credit reporting agencies. In certain circumstances, we may collect this information through third party sources. We protect information obtained from third party sources in accordance with the privacy practices set out in this policy. These third-party sources vary over time and may include recruitment agencies or referees if you apply for a job with us, email providers and social networks where you give permission to access your information on such third-party services or networks, partners with which we offer products or services or engage in joint marketing activities, publicly available sources such as Linked -in or open government databases. We may also be provided with contact lists containing your personal information from a third party such as your employer, company head office, industry associations, industry consultants, conferences, events or State/Territory-based education departments and/or regulatory bodies.</p>
        <h2 class="sub-title">Use of Cookies</h2>
        <p class="main-para">Cookies are small files that a site or its service provider transfers to your computer’s hard drive through your web browser (if you allow) that enables the sites or service providers systems to recognise your browser and capture and remember certain information. Our websites use cookies to anonymously distinguish you from other users of our services and to provide increased functionality when you are using our services. This helps us to provide you with a better experience when you browse our websites and also allows us to improve our websites and our services. Unless you have adjusted your browser settings (where possible) to refuse cookies, our systems will issue cookies as soon you visit our websites or access other services. If you have switched off cookies then some of the functionality of our services may not be available to you. The cookies that we use on our websites only collect anonymous information to optimise our services, and do not collect personal information.</p>
        <p class="main-para">To find out more about cookies including how to disable/enable and delete them at the following website <a href="https://www.aboutcookies.org/" target="_blank">www.aboutcookies.org</a>. If you use your browser settings to block all cookies you may not be able to access all or parts of our sites, or you may experience reduced functionality when accessing certain services.</p>

        <h2 class="sub-title">Anonymity & Pseudonymity</h2>
        <p class="main-para">We will not collect or monitor any personal information about an individual without their express or implied consent. Personal information is required for the purposes of establishing a relationship with our customers, performing the necessary credit checks in accordance with our credit application processes, providing and marketing our products and services to customers and receiving payment for the same and conducting our business.</p>

        <h2 class="sub-title">Collection of solicited information other than sensitive information</h2>
        <p class="main-para">If personal information concerns particular beliefs or backgrounds, it is considered to be of a sensitive nature. Such information may include:</p>
        <ul class="listing">
          <li class="lt big-font">racial background</li>
          <li class="lt big-font">political opinions and political party memberships</li>
          <li class="lt big-font">religious or philosophical beliefs</li>
          <li class="lt big-font">trade union affiliations or membership</li>
          <li class="lt big-font">sexual preferences</li>
          <li class="lt big-font">criminal record</li>
          <li class="lt big-font">health record</li>
        </ul>
        <p class="main-para">Except as detailed in this Privacy Policy we do not collect sensitive information.</p>


        <h2 class="sub-title">Dealing with unsolicited personal information</h2>
        <p class="main-para">Should we receive personal information, and did not solicit the information, we will decide if the personal information would have not been collected normally and such information will be de-identified and destroyed.</p>

        <h2 class="sub-title">Notification of the collection of personal information</h2>
        <p class="main-para">Your consent to any collection, use or disclosure of your personal information by us in accordance with this privacy policy may be provided in writing, given verbally or implied. When you use our websites and platforms and when you do business with us you consent to the collection, storage, protection and use of your information as described in this privacy policy.</p>

        <h2 class="sub-title">Use and disclosure of your personal information</h2>
        <p class="main-para">We use and share the personal information we collect so that we may continue to conduct our business of providing and improving our products and services including:</p>
        <ul class="listing">
          <li class="lt big-font">Delivering and maintaining our products and services.</li>
          <li class="lt big-font">Providing access to relevant curriculum material and inspiration in support of your purchases or teaching needs.</li>
          <li class="lt big-font">Product recommendations.</li>
          <li class="lt big-font">Establishing and maintaining your accounts.</li>
          <li class="lt big-font">Measuring credit and payment risk.</li>
          <li class="lt big-font">Providing account related services and information.</li>
          <li class="lt big-font">Helping you with customer service and support issues or questions.</li>
          <li class="lt big-font">Helping us to improve and personalise our products and services. </li>
          <li class="lt big-font">Providing you with marketing and promotional communications and delivering targeted and relevant advertising and offers to you, including better predicting content and marketing offers that may interest you.</li>
          <li class="lt big-font">Authenticating you.</li>
          <li class="lt big-font">Detecting and preventing fraud.</li>
          <li class="lt big-font">Managing and protecting our networks, services, and customers.</li>
          <li class="lt big-font">Meeting our legal obligations and conducting research.</li>
          <li class="lt big-font">Considering you for current and future employment, to manage our employment relationship or to manage our business relationships</li>
          <li class="lt big-font">Promoting the overall health and safety of the workplace, including to help reduce the risk of COVID-19 infections at our premises.</li>
        </ul>
        <p class="main-para">We may disclose your personal information to our associated entities for the same or similar uses as set out in this Privacy Policy.</p>
        <p class="main-para">We may also disclose your personal information to our suppliers and third parties that perform services for us in connection with the sale and provision of our products and services, including third parties who:</p>
        <ul class="listing">
          <li class="lt big-font">Provide delivery and logistics services</li>
          <li class="lt big-font">Provide Credit Reports and assessments</li>
          <li class="lt big-font">Provide Insurance </li>
          <li class="lt big-font">Provide Debt Collection</li>
          <li class="lt big-font">Provide Legal Advice</li>
          <li class="lt big-font">Conduct market research and analysis</li>
          <li class="lt big-font">Provide marketing or data analytics services</li>
        </ul>
        <p class="main-para">The Credit Reporting Bodies to which we are likely to disclose credit information are as follows:</p>
        <ul class="listing">
          <li class="lt big-font">Equifax - <a href="https://www.equifax.com.au/contact" target="_blank">https://www.equifax.com.au/contact</a></li>
        </ul>
        <h2 class="sub-title">Direct Marketing</h2>
        <p class="main-para">The personal information you provide may be utilised at a later date to allow us to market direct to you information about products and services offered by us and our associated entities. At times we may disclose your personal information to third parties and our associated entities to market direct to you our and our associated entities products and/or services. We take steps to ensure that our service providers are obliged to protect the privacy and security of your personal information and use it only for the purpose for which it is disclosed.</p>
        <p class="main-para">We respect an individual’s choice to opt-out of direct marketing communications activities. Should you decide you do not wish to receive marketing or promotional materials from us, please contact us in writing or as directed in any particular promotional material you may receive. If you opt out of receiving marketing material from us, we may still contact you in relation to any ongoing relationship with you covered by this Privacy Policy.</p>

        <h2 class="sub-title">Cross Border disclosure</h2>
        <p class="main-para">We may disclose personal information to third-party data storage facilities, software and service providers that may be located in Australia and other countries including but not limited to the United States of America and Japan.</p>

        <h2 class="sub-title">Adoption, use or disclosure of Governmental related identifiers</h2>
        <p class="main-para">We do not use Commonwealth Identifiers as a means of identifying the personal information we collect from you.</p>

        <h2 class="sub-title">Quality of personal information</h2>
        <p class="main-para">We rely on the personal information we hold to efficiently continue our business relationship with you. For this reason, it is of utmost importance that the personal information we collect from you is accurate, complete and up to date. At times, we will ask you to inform us of any changes to your personal information. You can also contact us at any time to update your personal information or advise us if any information we hold is inaccurate or incomplete.</p>

        <h2 class="sub-title">Security of personal information</h2>
        <p class="main-para">We ensure that your information is secure by protecting it from unauthorised access, modification and disclosure. We maintain physical security over our paper and electronic data stores and premises, such as locks and security systems. We also maintain computer and network security; for example, we use firewalls and other security systems such as user identifiers and passwords to control access to computer systems.</p>
        <p class="main-para">Payments to us are made through both secure internal and third party payment gateways. We receive notification of the processing of payments from the secure internal and third party payment gateway in which any credit card information is provided in an encrypted format.</p>
        <p class="main-para">We keep personal information only for as long as is reasonably necessary for the purpose for which it was collected or to comply with any applicable legal or accounting reporting or document retention requirements.</p>
        <p class="main-para">We do offer links to third party websites on our websites. Before disclosing your personal information on any other website, we advise you to examine the terms and conditions of using that website and its privacy statement.</p>

        <h2 class="sub-title">Access to Personal Information</h2>
        <p class="main-para">You are entitled to access your personal information held by us on request. Upon receipt of your written request, and enough information to allow us to confirm your identify and identify the information, we will disclose to you the personal information we hold about you, unless it is legally permitted to deny a request for access. Should we refuse you access to personal information or credit eligibility information, we will provide you with written notice that sets out the reason for refusal and the mechanism available to complain about the refusal.</p>
        <p class="main-para">
          To access details of your personal information, please contact the Privacy Officer at the address below.
        </p>
        <h2 class="sub-title">Correction of personal information</h2>
        <p class="main-para">We will also correct, amend or delete any personal information that we agree is inaccurate, out-of-date, incomplete, irrelevant or misleading. You are entitled to request us to correct any personal information we hold about you. To do so we need confirmation of your identity and sufficient details about the information. Please be aware that your request does not guarantee complete access or comprehensive removal as we may be required to retain records for legal or accounting purposes in certain circumstances.</p>
        <p class="main-para">To update details of your personal information, please contact the Privacy Officer at the address below.</p>
        <p class="main-para">In respect of teachers or educators, we may correct the school or other educational institution where you have specified that you are employed or contracted if evidence comes to our attention that you have commenced employment or contracting services at a different school or other educational institution, or if you are no longer employed by or provide contracting services to any school or other educational institution. Such actions may be taken by us in respect of ‘Classroom Connect’ accounts or individual accounts linked to employer or educational institutions or any of our other resources, products, services, Websites, social media, digital accounts, platforms and portals.</p>

        <h2 class="sub-title">Contact Details and complaints</h2>
        <p class="main-para">If you have any queries regarding the collection, use, disclosure or storage of your personal information under this Privacy Policy or if you wish to make a complaint about a breach of your privacy, please contact our Privacy Officer by using the details below. Once we receive your complaint, we will aim to review and respond in writing to you within a reasonable timeframe.</p>
        <div style="display:flex;margin-bottom:0px" class="para">
          <span style="padding-right:20px;"><strong>By post:</strong></span>
          <span><strong>The Privacy Officer<br />
              Modern Star Group<br />
              PO Box 1008<br />
              Dee Why NSW 2099</strong></span>
        </div>
        <p class="para"><strong>By email:&nbsp;&nbsp;privacyinfo@modernstar.com.au</strong></p>
        <h2 class="sub-title">Changes to Privacy Policy</h2>
        <p class="main-para">We may make changes to this Privacy Policy from time to time for any reason. We will publish the updated policy on our websites. This version of the Privacy Policy was amended with effect from [ 29 September 2022 ].</p>
      </div>
  </section>
</div>

<?php get_footer(); ?>