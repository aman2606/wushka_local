<?php
  /* Template Name: Pricing*/
  get_header();
?> 
<div class="pricing-wrap">
  <div class="bubbles">
    <div class="b1">
      <picture>
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b2-purple-s2.webp" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png" type="image/jpeg">
        <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png" alt="">
      </picture>
    </div>
    <div class="b2">
      <picture>
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/webp/bubblrs-red.webp" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubblrs-red.png" type="image/jpeg">
        <img src="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubblrs-red.png" alt="">
      </picture>
    </div>
  </div>
  <div id="hero">
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <h2 class="hero-title" id="main-content">Wushka Subscription Pricing</h2>
          <p class="para">Currently Wushka is only available to schools.</p>
        </div>
      </div>
    </div>
  </div>
  <section class="container-wrapper" >
    <div class="container">
      <div class="pricing-block">
        <div class="row">
          <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="pricing-table">
              <div class="row-title">
                <picture>
                  <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/webp/levelled-library-icon.webp" type="image/webp">
                  <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/levelled-library-icon.png" type="image/jpeg">
                  <img src="<?php echo get_template_directory_uri(); ?>/img/pricing/levelled-library-icon.png" alt="Levelled Library" class="icon-thumb">
                </picture>
                <div class="sub-title">Levelled Library</div>
              </div>
              <div class="row-price margin45 mb20">
                <div class="price-licence">$15</div>
                <div class="price-tag">per student and <br/>$249 licence fee</div>
              </div>
              <div class="row-info">
                <p class="sub-para">12 month subscription<br/> 688 levelled ebooks <span class="k-tag">K-6</span></p>
              </div>
              <a href="#" onclick="pricingTable(); return false;" onkeypress="pricingTable()" class="btn btn-see desktop">See What's Included</a>
              <a href="#" onclick="show(0); return false;" class="btn btn-see mobile">See What's Included</a>
              <div class="see" style="display:none;">
                <table class="compare-table" role="presentation">
                  <tbody>
                    <tr>
                      <th class="th compare-title">What's Included</th>
                      <th class="th"><span class="sr-only">Status</span></th>
                    </tr>
                    <tr>
                      <th class="th heading">Levelled ebooks</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Decodable ebooks</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-grey.svg" alt="" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">New books each term</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading boxes organised by phonics phase</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-grey.svg" alt="" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading boxes organised by reading level</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Filter by fiction/non-fiction and theme</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Filter by comprehension strategy and text type</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Optional narration</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Comprehension quizzes</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Follow-up activity and BLM</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Discussion cards</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Sequence strips</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Printable books</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Wordless books</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading Records</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Phonics screening</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-grey.svg" alt="" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading management system</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Class and student statistics</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Program coordinator/administrator access</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Parent resources</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Training and support videos</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"></td>
                    </tr>
                    <tr>
                      <th class="th heading last-child">Professional development available</th>
                      <td class="td last-child">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true">
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <p class="blue-para first-child">12 month subscription</p>
                        <p class="blue-para">688 levelled ebooks</p>
                        <p class="blue-para k-tag">K-6</p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="close-func"><a onclick="show(0)" class="closeBtn"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/close.svg" alt=""> Close</a></div>
            </div>
          </div>
          <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="pricing-table">
                <div class="row-title">
                  <picture>
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/webp/decodable-library-icon.webp" type="image/webp">
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/decodable-library-icon.png" type="image/jpeg">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/pricing/decodable-library-icon.png" alt="Decodable Library" class="icon-thumb">
                  </picture>
                  <div class="sub-title">Decodable Library</div>
                <!-- <div class="intro-price">Introductory Price</div> -->
                </div>
                <div class="row-price row-decodable-price mb20">
                  <div class="price-licence">$15</div>
                  <div class="price-tag">per student and <br/>$249 licence fee</div>
                </div>
                <div class="row-info">
                  <p class="sub-para">12 month subscription<br/> 408 decodable ebooks</p>
                </div>
                <a href="#" onclick="pricingTable(); return false;" onkeypress="pricingTable()" class="btn btn-see desktop">See What's Included</a>
                <a href="#" onclick="show(1); return false;" class="btn btn-see mobile">See What's Included</a>
                <div class="see" style="display:none;">
                  <table class="compare-table" role="presentation">
                    <tr>
                      <th class="th compare-title">What's Included</th>
                      <th class="th"><span class="sr-only">Status</span></th>
                    </tr>
                    <tr>
                      <th class="th heading">Levelled ebooks</th>
                      <td class="td"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Decodable ebooks</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">New books each term</th>
                      <td  class="td"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading boxes organised by phonics phase</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading boxes organised by reading level</th>
                      <td  class="td"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Filter by fiction/non-fiction and theme</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Filter by comprehension strategy and text type</th>
                      <td  class="td"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Optional narration</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Comprehension quizzes</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Follow-up activity and BLM</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Discussion cards</th>
                      <td  class="td"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Sequence strips</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Printable books</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Wordless books</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading Records</th>
                      <td  class="td"></td>
                    </tr>
                    <tr>
                      <th class="th heading">Phonics screening</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading management system</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Class and student statistics</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Program coordinator/administrator access</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Parent resources</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Training and support videos</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading last-child">Professional development available</th>
                      <td class="td last-child"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <p class="blue-para first-child">12 month subscription</p>
                        <p class="blue-para">408 decodable ebooks</p>
                        <p class="blue-para k-tag">K-3</p>
                      </td>
                    </tr>
                  </table>
                </div>
              <div class="close-func"><a onclick="show(1)" class="closeBtn"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/close.svg" alt=""> Close</a></div>
              </div>
            </div>
          <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="pricing-table">
                <div class="row-title">
                  <picture>
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/webp/plus-icon.webp" type="image/webp">
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/plus-icon.png" type="image/jpeg">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/pricing/plus-icon.png" alt="Wushka Plus" class="icon-thumb">
                  </picture>
                  <div class="sub-title">Wushka Plus</div>
                <div class="intro-price">Best Value</div>
                </div>
                <div class="row-price row-plus-price">
                  <div class="price-licence">
                      $20*
					  <span>*Valued at $30</span>
                  </div>
                  <div class="price-tag">per student and <br/>$249 licence fee</div>
                </div>
                <div class="row-info">
                  <p class="sub-para">12 month subscription<br/> Access to all 1096 levelled and<br/> decodable ebooks <span class="k-tag">K-6</span></p>
                </div>
                <a href="#" onclick="pricingTable(); return false;" onkeypress="pricingTable()" class="btn btn-see desktop">See What's Included</a>
                <a href="#" onclick="show(2); return false;" class="btn btn-see mobile">See What's Included</a>
                <div class="see" style="display:none;">
                  <table class="compare-table" role="presentation">
                    <tr>
                      <th class="th compare-title">What's Included</th>
                      <th class="th"><span class="sr-only">Status</span></th>
                    </tr>
                    <tr>
                      <th class="th heading">Levelled ebooks</th>
                      <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Decodable ebooks</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">New books each term</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading boxes organised by phonics phase</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading boxes organised by reading level</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Filter by fiction/non-fiction and theme</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Filter by comprehension strategy and text type</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Optional narration</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Comprehension quizzes</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Follow-up activity and BLM</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Discussion cards</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Sequence strips</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Printable books</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Wordless books</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading Records</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Phonics screening</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Reading management system</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Class and student statistics</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Program coordinator/administrator access</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Parent resources</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading">Training and support videos</th>
                      <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
                    </tr>
                    <tr>
                      <th class="th heading last-child">Professional development available</th>
                      <td class="td last-child"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <p class="blue-para first-child">12 month subscription</p>
                        <p class="blue-para">Access to all 1096 levelled<br/> and decodable ebooks</p>
                        <p class="blue-para k-tag">K-6</p>
                      </td>
                    </tr>
                  </table>
                </div>
              <div class="close-func"><a onclick="show(2)" class="closeBtn"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/close.svg" alt=""> Close</a></div>
              </div>
            </div>
        </div>
      </div>
      <div class="button-block row">
        <div class="col-sm-12 col-md-12 col-lg-12">
          <a href="" class="btn btn-blue"
            data-toggle="modal" data-target="#wk-form-modal"><?= wushka_cta_button_text(); ?></a>
        </div>
      </div>
      <div class="subscription-block" id="pricing">
          <h2 class="sub-heading">Compare Subscription Features</h2>
          <table class="compare-table" role="presentation">
            <tr>
              <th class="th compare-title">What's Included</th>
              <th class="th">
                <div class="row-title">
                  <picture>
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/webp/levelled-library-icon.webp" type="image/webp">
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/levelled-library-icon.png" type="image/jpeg">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/pricing/levelled-library-icon.png" alt="Levelled Library" class="icon-thumb">
                  </picture>
                  <div class="compare-title">Levelled Library</div>
                </div>
              </th>
              <th class="th">
                <div class="row-title">
                  <picture>
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/webp/decodable-library-icon.webp" type="image/webp">
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/decodable-library-icon.png" type="image/jpeg">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/pricing/decodable-library-icon.png" alt="Decodable Library" class="icon-thumb">
                  </picture>
                  <div class="compare-title">Decodable Library</div>
                </div>
              </th>
              <th class="th">
                <div class="row-title">
                  <picture>
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/webp/plus-icon.webp" type="image/webp">
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/plus-icon.png" type="image/jpeg">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/pricing/plus-icon.png" alt="Wushka Plus" class="icon-thumb">
                  </picture>
                  <div class="compare-title">Wushka Plus</div>
                </div>
              </th>
            </tr>
            <tr>
              <th class="th heading">Levelled ebooks</th>
              <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td class="td"></td>
              <td class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Decodable ebooks</th>
              <td  class="td"></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">New books each term</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Reading boxes organised by phonics phase</th>
              <td  class="td"></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Reading boxes organised by reading level</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Filter by fiction/non-fiction and theme</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Filter by comprehension strategy and text type</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Optional narration</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Comprehension quizzes</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Follow-up activity and BLM</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Discussion cards</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Sequence strips</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Printable books</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Wordless books</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Reading Records</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Phonics screening</th>
              <td  class="td"></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Reading management system</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Class and student statistics</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Program coordinator/administrator access</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Parent resources</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading">Training and support videos</th>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
              <td  class="td"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/></td>
            </tr>
            <tr>
              <th class="th heading last-child">Professional development available</th>
              <td class="td last-child">
                <img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/>
                <p class="blue-para first-child">12 month subscription</p>
                <p class="blue-para">688 levelled ebooks</p>
                <p class="blue-para k-tag">K-6</p>
              </td>
              <td class="td last-child"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/>
                <p class="blue-para first-child">12 month subscription</p>
                <p class="blue-para">408 decodable ebooks</p>
              </td>
              <td class="td last-child"><img src="<?php echo get_template_directory_uri(); ?>/img/pricing/tick-green.svg" alt="True" class="tick-true"/>
                <p class="blue-para first-child">12 month subscription</p>
                <p class="blue-para">Access to all 1096 levelled<br/> and decodable ebooks</p>
                <p class="blue-para k-tag">K-6</p>
              </td>
            </tr>
          </table>
      </div>
    </div>
  </section>

  <div class="buy-section">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-7 com-lg-8">
          <h2 class="sub-heading">Try Before You Buy!</h2>
          <p class="para">We’re so confident that your school will love Wushka that we offer a no‑obligation FREE 30-day trial before you purchase. The free trial gives you complete access to Wushka Plus so you can immediately see how easy it is use and implement in your school or classroom.</p>
          <a class="btn btn-blue colour-white" href=""
            data-toggle="modal" data-target="#wk-form-modal"><?= wushka_cta_button_text(); ?></a>
        </div>
        <div class="col-sm-12 col-md-5 com-lg-4">
          <picture class="img-block">
            <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/webp/try-before-you-buy.webp" type="image/webp">
            <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/try-before-you-buy.png" type="image/jpeg">
              <img src="<?php echo get_template_directory_uri(); ?>/img/pricing/try-before-you-buy.png" class="buy-thumb" alt="Try before you buy">
          </picture>
        </div>
      </div>
    </div>
  </div>
  <div class="upgrade-section">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-7 com-lg-8">
          <h2 class="sub-heading">Want to Upgrade?</h2>
          <p class="para">Already have a Wushka Levelled Library subscription and want to upgrade?<br/> Contact us and we will work with you to tailor pricing to suit the needs of your school and classroom.</p>
          <a class="btn btn-blue colour-white" href="/contact-us">Contact Us Here</a>
        </div>
        <div class="col-sm-12 col-md-5 com-lg-4">
          <picture class="img-block desktop">
            <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/webp/want-to-upgrade.webp" type="image/webp">
            <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/want-to-upgrade.png" type="image/jpeg">
              <img src="<?php echo get_template_directory_uri(); ?>/img/pricing/want-to-upgrade.png" class="upgrade-thumb" alt="Buy Decodable Readers in Print">
          </picture>
          <picture class="img-block tablet">
            <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/webp/want-to-upgrade-tablet.webp" type="image/webp">
            <source srcset="<?php echo get_template_directory_uri(); ?>/img/pricing/want-to-upgrade-tablet.png" type="image/jpeg">
              <img src="<?php echo get_template_directory_uri(); ?>/img/pricing/want-to-upgrade-tablet.png" class="upgrade-thumb" alt="Buy Decodable Readers in Print">
          </picture>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
  function pricingTable(){ 
    //onkeypress="pricingTable()" tabIndex="1"
    var element = document.getElementById('pricing');
    element.scrollIntoView({behavior: "smooth"});
  }
</script>
<script>
  function show(elem) {
    var p = document.getElementsByClassName("see");
    var closeB = document.getElementsByClassName("closeBtn");
    if (p[elem] != undefined) {
      if (p[elem].style.display == "none") {
        p[elem].style.display = "block";
        p[elem].style.padding = "20px";
        closeB[elem].style.display = "block";
      } else {
        p[elem].style.display = "none";
        p[elem].style.padding = "0";
        closeB[elem].style.display = "none";
      }
    }
  }
</script>

<?php get_footer(); ?>
