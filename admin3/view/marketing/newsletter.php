<?php
  include('../../controller/marketing/newsletter.php');
  $newsletter = create_newsletter();
?>

<!-- Header -->
<?php include('../layout/header.php'); ?>
<!-- End of Header -->

<link rel="stylesheet" href="../../assets/css/datetimepicker.css">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.4/cropper.min.css'>
<style>
  p,
    h1,
    h2,
    h3,
    h4 {
      margin-top: 0;
      margin-bottom: 0;
      padding-top: 0;
      padding-bottom: 0;
    }

    span.preheader {
      display: none;
      font-size: 1px;
    }

    html {
      width: 100%;
    }

    table {
      font-size: 14px;
      border: 0;
    }
    /* ----------- responsivity ----------- */

    @media only screen and (max-width: 640px) {
      /*------ top header ------ */
      .main-header {
        font-size: 20px !important;
      }
      .main-section-header {
        font-size: 28px !important;
      }
      .show {
        display: block !important;
      }
      .hide {
        display: none !important;
      }
      .align-center {
        text-align: center !important;
      }
      .no-bg {
        background: none !important;
      }
      /*----- main image -------*/
      .main-image img {
        width: 440px !important;
        height: auto !important;
      }
      /* ====== divider ====== */
      .divider img {
        width: 440px !important;
      }
      /*-------- container --------*/
      .container590 {
        width: 440px !important;
      }
      .container590wide{
        width: 640px !important;
      }
      .container580 {
        width: 400px !important;
      }
      .main-button {
        width: 220px !important;
      }
      /*-------- secions ----------*/
      .section-img img {
        width: 320px !important;
        height: auto !important;
      }
      .team-img img {
        width: 100% !important;
        height: auto !important;
      }
    }

    @media only screen and (max-width: 479px) {
      /*------ top header ------ */
      .main-header {
        font-size: 18px !important;
      }
      .main-section-header {
        font-size: 26px !important;
      }
      /* ====== divider ====== */
      .divider img {
        width: 280px !important;
      }
      /*-------- container --------*/
      .container590 {
        width: 280px !important;
      }
      .container590Wide {
        width: 335px !important;
      }
      .container580 {
        width: 260px !important;
      }
      /*-------- secions ----------*/
      .section-img img {
        width: 280px !important;
        height: auto !important;
      }
    }

    /*---- edit styles ---*/

</style>
  <!-- pre-header -->
  <table style="display:none!important;">
    <tr>
      <td>
        <div style="overflow:hidden;display:none;font-size:1px;color:#ffffff;line-height:1px;font-family:Arial;maxheight:0px;max-width:0px;opacity:0;">
          Pre-header for the newsletter template
        </div>
      </td>
    </tr>
  </table>
  <!-- pre-header end -->
            <!-- header -->
            <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff">

<tr>
<td align="center">
    <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td align="center">

        <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

            <tr>
            <td align="center" height="40" style="height:40px;">
                <a href="http://www.mjtrends.com?eid=&nid=" class="imgLink" style="display: block; border-style: none !important; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/email/logo.png" style="height:40px"></a>
            </td>
            </tr>

            <tr>
            <td align="center">
                <table width="360 " border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                class="container590 hide">
                <tr>
                    <td width="120" align="center" style="font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 44px;">
                    <a href="http://www.MJTrends.com?eid=&nid=" style="color: #312c32; text-decoration: none;">FABRIC</a>
                    </td>
                    <td width="120" align="center" style="font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 44px;">
                    <a href="http://www.MJTrends.com/patterns?eid=&nid=" style="color: #312c32; text-decoration: none;">PATTERNS</a>
                    </td>
                    <td width="120" align="center" style="font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 44px;">
                    <a href="http://www.MJTrends.com/blog?eid=&nid=" style="color: #312c32; text-decoration: none;">BLOG</a>
                    </td>
                </tr>
                </table>
            </td>
            </tr>
        </table>
        </td>
    </tr>

    <tr>
        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
    </tr>

    </table>
</td>
</tr>
</table>
<!-- end header -->

<!-- big image section -->
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color section_edit">

<tr>
<td align="center">
    <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
    <tr>

        <td align="center" class="section-img">
        <a href="" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img class="editImg" id="editImg" src="https://mdbootstrap.com/img/Photos/Horizontal/E-commerce/Products/img%20(59).jpg" style="display: block; width: 590px;" width="590" border="0" alt=""/></a>
        </td>
    </tr>
    <tr>
        <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;"
        class="main-header">


        <div style="line-height: 35px" class="edit">

            NEW IN <span style="color: #5caad2;">NOVEMBER</span>

        </div>
        </td>
    </tr>

    <tr>
        <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
    </tr>

    <tr>
        <td align="center">
        <table border="0" width="40" align="center" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
            <tr>
            <td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
            </tr>
        </table>
        </td>
    </tr>

    <tr>
        <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
    </tr>

    <tr>
        <td align="center">
        <table border="0" width="400" align="center" cellpadding="0" cellspacing="0" class="container590">
            <tr>
            <td align="center" style="color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">


                <div style="line-height: 24px" class="edit">

                Winter is coming. Shop our latest AW16 range, and prepare for the damp, cold, British weather.
                </div>
            </td>
            </tr>
        </table>
        </td>
    </tr>

    <tr>
        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
    </tr>

    <tr>
        <td align="center">
        <table border="0" align="center" width="160" cellpadding="0" cellspacing="0" bgcolor="5caad2" style="">

            <tr>
            <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
            </tr>

            <tr>
            <td align="center" style="color: #ffffff; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 26px;">


                <div style="line-height: 26px;">
                <a href="" style="color: #ffffff; text-decoration: none;">SHOP NOW</a>
                </div>
            </td>
            </tr>

            <tr>
            <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
            </tr>

        </table>
        </td>
    </tr>


    </table>

</td>
</tr>

<tr class="hide">
<td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
</tr>
<tr>
<td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
</tr>

</table>
<!-- end section -->



<!-- big image section -->

<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="section_edit bg_color">

<tr>
<td align="center">
<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

  <tr>
    <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;"
      class="main-header">
      <!-- section text ======-->

      <div style="line-height: 35px" class="edit">

        Welcome to the future of <span style="color: #5caad2;">fashion</span>

      </div>
    </td>
  </tr>

  <tr>
    <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
  </tr>

  <tr>
    <td align="center">
      <table border="0" width="40" align="center" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
        <tr>
          <td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>

  <tr>
    <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
  </tr>

  <tr>
    <td align="left">
      <table border="0" width="590" align="center" cellpadding="0" cellspacing="0" class="container590">
        <tr>
          <td align="left" style="color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
            <!-- section text ======-->

            <p style="line-height: 24px; margin-bottom:15px;">

              [firstname],

            </p>
            <p style="line-height: 24px;margin-bottom:15px;" class="edit">
              Great news, you will now be the first to see exclusive previews of our latest collections, hear about news from the Abacus!
              community and get the most up to date news in the world of fashion.
            </p>
            <p style="line-height: 24px; margin-bottom:20px;"class="edit">
              You can access your account at any point using the link below.
            </p>
            <table border="0" align="center" width="180" cellpadding="0" cellspacing="0" bgcolor="5caad2" style="margin-bottom:20px;">

              <tr>
                <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
              </tr>

              <tr>
                <td align="center" style="color: #ffffff; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 22px; letter-spacing: 2px;">
                  <!-- main section button -->

                  <div style="line-height: 22px;">
                    <a href="" style="color: #ffffff; text-decoration: none;">MY ACCOUNT</a>
                  </div>
                </td>
              </tr>

              <tr>
                <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
              </tr>

            </table>
            <p style="line-height: 24px" class="edit">
              Love,</br>
              The MDB team
            </p>

          </td>
        </tr>
      </table>
    </td>
  </tr>





</table>

</td>
</tr>

<tr>
<td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
</tr>

</table>

<!-- end section -->



<!--  Blog section -->
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="section_edit">
<tr>
<td align="center">
<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

  <tr>
    <td>
      <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">


        <tr>
          <td align="center">
            <a href="<?php echo $newsletter['blog']['guid'][0]; ?>?eid=&nid=" class="imgLink" style="border-style: none !important; border: 0 !important;"><img src="<?php if(isset($newsletter['blog_image'][0]['guid'])) { echo $newsletter['blog_image'][0]['guid']; }?>" style="display: block; width: 280px; border:1px solid #C0C0C0" width="280" border="0" alt="" /></a>
          </td>
        </tr>
      </table>

      <table border="0" width="5" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">
        <tr>
          <td width="5" height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
        </tr>
      </table>

      <table border="0" width="260" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">
        <tr>
          <td align="left" style="color: #3d3d3d; font-size: 22px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;"
            class="align-center main-header">


            <div style="line-height: 35px" class="edit">
              <a href="<?php echo $newsletter['blog']['guid'][0]; ?>?eid=&nid=" style="text-decoration: none; color:#000"><?php echo $newsletter['blog']['post_title'][0]; ?></a>
            </div>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 12px; line-height: 12px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left">
            <table border="0" align="left" cellpadding="0" cellspacing="0" class="container590">
              <tr>
                <td align="center">
                  <table align="center" width="40" border="0" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
                    <tr>
                      <td height="2" style="font-size: 2px; line-height: 2px;"></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 12px; line-height: 12px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left" style="color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;"
            class="align-center">


            <div style="line-height: 24px" class="edit">

              <a href="<?php echo $newsletter['blog']['guid'][0]; ?>?eid=&nid=" style="text-decoration: none; color:#888888"><?php echo $newsletter['blog']['post_content'][0]; ?> ...</a>

            </div>
          </td>
        </tr>

        <tr>
          <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left">
            <table border="0" align="left" cellpadding="0" cellspacing="0" class="container590">
              <tr>
                <td align="center">
                  <table border="0" align="center" width="120" cellpadding="0" cellspacing="0" style="border: 1px solid #eeeeee; ">

                    <tr>
                      <td height="5" style="font-size: 5px; line-height: 5px;">&nbsp;</td>
                    </tr>

                    <tr>
                      <td align="center" style="color: #5caad2; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 20px;">


                        <div style="line-height: 20px;">
                          <a href="<?php echo $newsletter['blog']['guid'][0]; ?>?eid=&nid=" style="color: #5caad2; text-decoration: none;">READ MORE</a>
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <td height="8" style="font-size: 8px; line-height: 8px;">&nbsp;</td>
                    </tr>

                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>

    </td>
  </tr>

</table>
</td>
</tr>

<tr>
<td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
</tr>

</table>
<!-- end section -->

<!--  50% image -->
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="section_edit">
<tr>
<td align="center">
<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

  <tr>
    <td>
      <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">


        <tr>
          <td align="center">
            <a href="<?php echo $newsletter['blog']['guid'][1]; ?>?eid=&nid=" class="imgLink" style="border-style: none !important; border: 0 !important;"><img src="<?php if(isset($newsletter['blog_image'][1]['guid'])) { echo $newsletter['blog_image'][1]['guid']; }?>" style="display: block; width: 280px; border:1px solid #C0C0C0" width="280" border="0" alt="" /></a>
          </td>
        </tr>
      </table>

      <table border="0" width="5" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">
        <tr>
          <td width="5" height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
        </tr>
      </table>

      <table border="0" width="260" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">
        <tr>
          <td align="left" style="color: #3d3d3d; font-size: 22px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;"
            class="align-center main-header">


            <div style="line-height: 35px" class="edit">
              <a href="<?php echo $newsletter['blog']['guid'][1]; ?>?eid=&nid=" style="text-decoration: none; color:#000"><?php echo $newsletter['blog']['post_title'][1]; ?></a>
            </div>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 12px; line-height: 12px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left">
            <table border="0" align="left" cellpadding="0" cellspacing="0" class="container590">
              <tr>
                <td align="center">
                  <table align="center" width="40" border="0" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
                    <tr>
                      <td height="2" style="font-size: 2px; line-height: 2px;"></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 12px; line-height: 12px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left" style="color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;"
            class="align-center">


            <div style="line-height: 24px" class="edit">

              <a href="<?php echo $newsletter['blog']['guid'][1]; ?>?eid=&nid=" style="text-decoration: none; color:#888888"><?php echo $newsletter['blog']['post_content'][1]; ?> ...</a>

            </div>
          </td>
        </tr>

        <tr>
          <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left">
            <table border="0" align="left" cellpadding="0" cellspacing="0" class="container590">
              <tr>
                <td align="center">
                  <table border="0" align="center" width="120" cellpadding="0" cellspacing="0" style="border: 1px solid #eeeeee; ">

                    <tr>
                      <td height="5" style="font-size: 5px; line-height: 5px;">&nbsp;</td>
                    </tr>

                    <tr>
                      <td align="center" style="color: #5caad2; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 20px;">


                        <div style="line-height: 20px;">
                          <a href="<?php echo $newsletter['blog']['guid'][1]; ?>?eid=&nid=" style="color: #5caad2; text-decoration: none;">READ MORE</a>
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <td height="8" style="font-size: 8px; line-height: 8px;">&nbsp;</td>
                    </tr>

                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>

    </td>
  </tr>

</table>
</td>
</tr>

<tr>
<td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
</tr>

</table>
<!-- end section -->

<!--  50% image -->
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="section_edit">
<tr>
<td align="center">
<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

  <tr>
    <td>
      <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">


        <tr>
          <td align="center">
            <a href="<?php echo $newsletter['blog']['guid'][2]; ?>?eid=&nid=" class="imgLink" style="border-style: none !important; border: 0 !important;"><img src="<?php if(isset($newsletter['blog_image'][2]['guid'])) { echo $newsletter['blog_image'][2]['guid']; }?>" style="display: block; width: 280px; border:1px solid #C0C0C0" width="280" border="0" alt="" /></a>
          </td>
        </tr>
      </table>

      <table border="0" width="5" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">
        <tr>
          <td width="5" height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
        </tr>
      </table>

      <table border="0" width="260" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">
        <tr>
          <td align="left" style="color: #3d3d3d; font-size: 22px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;"
            class="align-center main-header">


            <div style="line-height: 35px" class="edit">

              <a href="<?php echo $newsletter['blog']['guid'][2]; ?>?eid=&nid=" style="text-decoration: none; color:#000"><?php echo $newsletter['blog']['post_title'][2]; ?></a>

            </div>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 12px; line-height: 12px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left">
            <table border="0" align="left" cellpadding="0" cellspacing="0" class="container590">
              <tr>
                <td align="center">
                  <table align="center" width="40" border="0" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
                    <tr>
                      <td height="2" style="font-size: 2px; line-height: 2px;"></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 12px; line-height: 12px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left" style="color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;"
            class="align-center">


            <div style="line-height: 24px" class="edit">

              <a href="<?php echo $newsletter['blog']['guid'][2]; ?>?eid=&nid=" style="text-decoration: none; color:#888888"><?php echo $newsletter['blog']['post_content'][2]; ?> ...</a>

            </div>
          </td>
        </tr>

        <tr>
          <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left">
            <table border="0" align="left" cellpadding="0" cellspacing="0" class="container590">
              <tr>
                <td align="center">
                  <table border="0" align="center" width="120" cellpadding="0" cellspacing="0" style="border: 1px solid #eeeeee; ">

                    <tr>
                      <td height="5" style="font-size: 5px; line-height: 5px;">&nbsp;</td>
                    </tr>

                    <tr>
                      <td align="center" style="color: #5caad2; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 20px;">


                        <div style="line-height: 20px;">
                          <a href="<?php echo $newsletter['blog']['guid'][2]; ?>?eid=&nid=" style="color: #5caad2; text-decoration: none;">READ MORE</a>
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <td height="8" style="font-size: 8px; line-height: 8px;">&nbsp;</td>
                    </tr>

                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>

    </td>
  </tr>

</table>
</td>
</tr>

<tr>
<td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
</tr>

</table>
<!-- end section -->

<!--  50% image -->
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="section_edit">
<tr>
<td align="center">
<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

  <tr>
    <td>
      <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">


        <tr>
          <td align="center">
            <a href="<?php echo $newsletter['blog']['guid'][3]; ?>?eid=&nid=" class="imgLink" style="border-style: none !important; border: 0 !important;"><img src="<?php if(isset($newsletter['blog_image'][3]['guid'])) { echo $newsletter['blog_image'][3]['guid']; }?>" style="display: block; width: 280px; border:1px solid #C0C0C0" width="280" border="0" alt="" /></a>
          </td>
        </tr>
      </table>

      <table border="0" width="5" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">
        <tr>
          <td width="5" height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
        </tr>
      </table>

      <table border="0" width="260" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">
        <tr>
          <td align="left" style="color: #3d3d3d; font-size: 22px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;"
            class="align-center main-header">


            <div style="line-height: 35px" class="edit">

              <a href="<?php echo $newsletter['blog']['guid'][3]; ?>?eid=&nid=" style="text-decoration: none; color:#000"><?php echo $newsletter['blog']['post_title'][3]; ?></a>

            </div>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 12px; line-height: 12px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left">
            <table border="0" align="left" cellpadding="0" cellspacing="0" class="container590">
              <tr>
                <td align="center">
                  <table align="center" width="40" border="0" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
                    <tr>
                      <td height="2" style="font-size: 2px; line-height: 2px;"></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 12px; line-height: 12px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left" style="color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;"
            class="align-center">


            <div style="line-height: 24px" class="edit">

              <a href="<?php echo $newsletter['blog']['guid'][3]; ?>?eid=&nid=" style="text-decoration: none; color:#888888"><?php echo $newsletter['blog']['post_content'][3]; ?> ...</a>

            </div>
          </td>
        </tr>

        <tr>
          <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left">
            <table border="0" align="left" cellpadding="0" cellspacing="0" class="container590">
              <tr>
                <td align="center">
                  <table border="0" align="center" width="120" cellpadding="0" cellspacing="0" style="border: 1px solid #eeeeee; ">

                    <tr>
                      <td height="5" style="font-size: 5px; line-height: 5px;">&nbsp;</td>
                    </tr>

                    <tr>
                      <td align="center" style="color: #5caad2; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 20px;">


                        <div style="line-height: 20px;">
                          <a href="<?php echo $newsletter['blog']['guid'][3]; ?>?eid=&nid=" style="color: #5caad2; text-decoration: none;">READ MORE</a>
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <td height="8" style="font-size: 8px; line-height: 8px;">&nbsp;</td>
                    </tr>

                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>

    </td>
  </tr>

</table>
</td>
</tr>

<tr>
<td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
</tr>

</table>
<!-- end section -->

<!-- coupon -->
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="section_edit">
<tr>
<td align="center">
<table border="0" width="200" align="center" cellpadding="0" cellspacing="0" bgcolor="333333" style="border: 3px solid #333333; ">
  <tr>
    <td align="center" bgcolor="333333" style="color: #ffffff; font-size: 15px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;  line-height: 32px;">
      20% OFF COUPON
    </td>
  </tr>
</table>
</td>
</tr>

<tr>
<td align="center">
<table border="0" width="590" align="center" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="container590" style="border: 3px solid #333; ">

  <tr>
    <td height="45" style="font-size: 45px; line-height: 45px;">&nbsp;</td>
  </tr>

  <tr>
    <td align="center">
      <table border="0" width="540" align="center" cellpadding="0" cellspacing="0" class="container580">

        <tr>

          <td width="30">&nbsp;</td>
          <td align="center" style="color: #333333; font-size: 18px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 32px;">

            <div style="line-height: 32px" class="edit">
              For a limited time only get <span style="color: #5caad2; text-decoration: none;">20% off</span>  purchases of white PVC fabric.
            </div>
          </td>
          <td width="30">&nbsp;</td>
        </tr>
        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>
        <tr>
          <td width="30">&nbsp;</td>
          <td align="center">
            <table border="0" align="center" width="160" cellpadding="0" cellspacing="0" bgcolor="5caad2" style="">

              <tr>
                <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
              </tr>

              <tr>
                <td align="center" style="color: #ffffff; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 26px;">


                  <div style="line-height: 26px;">
                    <a href="" style="color: #ffffff; text-decoration: none;">REDEEM NOW</a>
                  </div>
                </td>
              </tr>

              <tr>
                <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
              </tr>

            </table>
          </td>
          <td width="30">&nbsp;</td>
        </tr>

      </table>
    </td>
  </tr>

  <tr>
    <td height="45" style="font-size: 45px; line-height: 45px;">&nbsp;</td>
  </tr>

</table>
</td>
</tr>

<tr class="hide">
<td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
</tr>
<tr>
<td height="40" style="font-size: 64px; line-height: 40px;">&nbsp;</td>
</tr>

</table>
<!-- end section ====== -->


<!-- 2 Column  Layout-->
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="section_edit">

<tr>
<td align="center">

<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

  <tr>
    <td align="center" style="color: #343434; font-size: 22px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 2px; line-height: 35px;" class="main-header">


      <div style="line-height: 35px" class="edit">

        RECOMMENDED <span style="color: #5caad2;">FOR YOU</span>

      </div>
    </td>
  </tr>

  <tr>
    <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
  </tr>

  <tr>
    <td align="center">
      <table border="0" width="40" align="center" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
        <tr>
          <td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>

  <tr>
    <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
  </tr>

  <tr>
    <td align="center">
      <table border="0" width="440" align="center" cellpadding="0" cellspacing="0" class="container590">
        <tr>
          <td align="center" style="color: #888888; font-size: 15px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">


            <div style="line-height: 24px" class="edit">

              Our team has picked out a couple great new DIY-the-look projects just for you:
              .

            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <tr>
    <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
  </tr>

  <tr>
    <td>
      <table border="0" width="260" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <td align="center">
            <a href="" class="imgLink" style="border-style: none !important; border: 0 !important;"><img src="https://mdbootstrap.com/img/Photos/Others/tshirt1.jpg" style="display: block; width: 260px;" width="260" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: Quicksand, Calibri, sans-serif; font-weight: 600; line-height: 24px;" class="align-center">


            <div style="line-height: 24px" class="edit">

              THE T-SHIRT

            </div>
          </td>
        </tr>



      </table>

      <table border="0" width="5" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
        <tr>
          <td width="5" height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td>
        </tr>
      </table>

      <table border="0" width="260" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <td align="center">
            <a href="" class="imgLink" style="border-style: none !important; border: 0 !important;"><img src="https://mdbootstrap.com/img/Photos/Others/tshirt3.jpg" style="display: block; width: 260px;" width="260" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: Quicksand, Calibri, sans-serif; font-weight: 600; line-height: 24px;" class="align-center">


            <div style="line-height: 24px" class="edit">

              THE T-SHIRT

            </div>
          </td>
        </tr>

      </table>
    </td>
  </tr>

</table>
</td>
</tr>

<tr class="hide">
<td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
</tr>
<tr>
<td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
</tr>

</table>
<!-- end section -->


<!-- ======= 3 columns features ======= -->
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color section_edit">


<tr>
<td align="center">
<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

  <tr>
    <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;" class="main-header">
      <!-- ======= section text ====== -->

      <div style="line-height: 35px" class="edit">

        <a href="http://www.mjtrends.com/categories-clearance,sale?eid=&nid=" style="text-decoration:none;color:#000;"><?php echo $newsletter['count'][0]['count']; ?> NEW CLEARANCE ITEMS</a>

      </div>
    </td>
  </tr>

  <tr class="hide">
    <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
  </tr>

  <tr>
    <td>
      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][0]['color']; ?>,<?php echo $newsletter['clearance'][0]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['clearance'][0]['invid']; ?>/<?php echo json_decode($newsletter['clearance'][0]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][0]['color']; ?>,<?php echo $newsletter['clearance'][0]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['clearance'][0]['color'])." ".str_replace("-"," ",$newsletter['clearance'][0]['type']); ?></a>

            </div>
          </td>
        </tr>

        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['clearance'][0]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['clearance'][0]['saleprice']; ?>
          </td>
        </tr>

      </table>
      <table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
        <tr>
          <td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
        </tr>
      </table>
      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][1]['color']; ?>,<?php echo $newsletter['clearance'][1]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['clearance'][1]['invid']; ?>/<?php echo json_decode($newsletter['clearance'][1]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][1]['color']; ?>,<?php echo $newsletter['clearance'][1]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['clearance'][1]['color'])." ".str_replace("-"," ",$newsletter['clearance'][1]['type']); ?></a>

            </div>
          </td>
        </tr>
        
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['clearance'][1]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['clearance'][1]['saleprice']; ?>
          </td>
        </tr>

      </table>

      <table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
        <tr>
          <td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
        </tr>
      </table>

      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][2]['color']; ?>,<?php echo $newsletter['clearance'][2]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['clearance'][2]['invid']; ?>/<?php echo json_decode($newsletter['clearance'][2]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">
              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][2]['color']; ?>,<?php echo $newsletter['clearance'][2]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['clearance'][2]['color'])." ".str_replace("-"," ",$newsletter['clearance'][2]['type']); ?></a>
            </div>
          </td>
        </tr>
        
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['clearance'][2]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['clearance'][2]['saleprice']; ?>
          </td>
        </tr>                                

      </table>
    </td>
  </tr>

  <tr class="hide">
    <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
  </tr>

  <tr>
    <td>
      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][3]['color']; ?>,<?php echo $newsletter['clearance'][3]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['clearance'][3]['invid']; ?>/<?php echo json_decode($newsletter['clearance'][3]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][3]['color']; ?>,<?php echo $newsletter['clearance'][3]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['clearance'][3]['color'])." ".str_replace("-"," ",$newsletter['clearance'][3]['type']); ?></a>

            </div>
          </td>
        </tr>

        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['clearance'][3]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['clearance'][3]['saleprice']; ?>
          </td>
        </tr>

      </table>
      <table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
        <tr>
          <td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
        </tr>
      </table>
      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][4]['color']; ?>,<?php echo $newsletter['clearance'][4]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['clearance'][4]['invid']; ?>/<?php echo json_decode($newsletter['clearance'][4]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][4]['color']; ?>,<?php echo $newsletter['clearance'][4]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['clearance'][4]['color'])." ".str_replace("-"," ",$newsletter['clearance'][4]['type']); ?></a>

            </div>
          </td>
        </tr>

        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['clearance'][4]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['clearance'][4]['saleprice']; ?>
          </td>
        </tr>

      </table>

      <table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
        <tr>
          <td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
        </tr>
      </table>

      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][5]['color']; ?>,<?php echo $newsletter['clearance'][5]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['clearance'][5]['invid']; ?>/<?php echo json_decode($newsletter['clearance'][5]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">
              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][5]['color']; ?>,<?php echo $newsletter['clearance'][5]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['clearance'][5]['color'])." ".str_replace("-"," ",$newsletter['clearance'][5]['type']); ?></a>
            </div>
          </td>
        </tr>

        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['clearance'][5]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['clearance'][5]['saleprice']; ?>
          </td>
        </tr>                                

      </table>
    </td>
  </tr>

    <tr class="hide">
    <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
  </tr>

  <tr>
    <td>
      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][6]['color']; ?>,<?php echo $newsletter['clearance'][6]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['clearance'][6]['invid']; ?>/<?php echo json_decode($newsletter['clearance'][6]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][6]['color']; ?>,<?php echo $newsletter['clearance'][6]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['clearance'][6]['color'])." ".str_replace("-"," ",$newsletter['clearance'][6]['type']); ?></a>

            </div>
          </td>
        </tr>

        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['clearance'][6]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['clearance'][6]['saleprice']; ?>
          </td>
        </tr>                                

      </table>
      <table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
        <tr>
          <td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
        </tr>
      </table>
      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][7]['color']; ?>,<?php echo $newsletter['clearance'][7]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['clearance'][7]['invid']; ?>/<?php echo json_decode($newsletter['clearance'][7]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][7]['color']; ?>,<?php echo $newsletter['clearance'][7]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['clearance'][7]['color'])." ".str_replace("-"," ",$newsletter['clearance'][7]['type']); ?></a>

            </div>
          </td>
        </tr>

        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['clearance'][7]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['clearance'][7]['saleprice']; ?>
          </td>
        </tr>

      </table>

      <table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
        <tr>
          <td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
        </tr>
      </table>

      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][8]['color']; ?>,<?php echo $newsletter['clearance'][8]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['clearance'][8]['invid']; ?>/<?php echo json_decode($newsletter['clearance'][8]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['clearance'][8]['color']; ?>,<?php echo $newsletter['clearance'][8]['type']; ?>,Clearance?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['clearance'][8]['color'])." ".str_replace("-"," ",$newsletter['clearance'][8]['type']); ?></a>

            </div>
          </td>
        </tr>

        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['clearance'][8]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['clearance'][8]['saleprice']; ?>
          </td>
        </tr>                                

      </table>
    </td>
  </tr>

</table>

</td>
</tr>

</table>
<!-- ======= end section ======= -->



<!-- ======= 3 columns features ======= -->
<table>
<tr>
<td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
</tr>
</table>
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color section_edit">


<tr>
<td align="center">
<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

  <tr>

    <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;" class="main-header">
      <!-- ======= section text ====== -->

      <div style="line-height: 35px" class="edit">

        <?php echo $newsletter['sale_count'][0]['sale_count']; ?> ITEMS ON SALE

      </div>
    </td>
  </tr>

  <tr class="hide">
    <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
  </tr>

  <tr>
    <td>
      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][0]['color']; ?>,<?php echo $newsletter['sale'][0]['type']; ?>,<?php echo $newsletter['sale'][0]['cat']; ?>?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['sale'][0]['invid']; ?>/<?php echo json_decode($newsletter['sale'][0]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][0]['color']; ?>,<?php echo $newsletter['sale'][0]['type']; ?>,<?php echo $newsletter['sale'][0]['cat']; ?>?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['sale'][0]['color'])." ".str_replace("-"," ",$newsletter['sale'][0]['type']); ?></a>

            </div>
          </td>
        </tr>

        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['sale'][0]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['sale'][0]['saleprice']; ?>
          </td>
        </tr>                                   

      </table>
      <table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
        <tr>
          <td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
        </tr>
      </table>
      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][1]['color']; ?>,<?php echo $newsletter['sale'][1]['type']; ?>,<?php echo $newsletter['sale'][1]['cat']; ?>?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['sale'][1]['invid']; ?>/<?php echo json_decode($newsletter['sale'][1]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][1]['color']; ?>,<?php echo $newsletter['sale'][1]['type']; ?>,<?php echo $newsletter['sale'][1]['cat']; ?>?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['sale'][1]['color'])." ".str_replace("-"," ",$newsletter['sale'][1]['type']); ?></a>

            </div>
          </td>
        </tr>


        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['sale'][1]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['sale'][1]['saleprice']; ?>
          </td>
        </tr>                                  

      </table>

      <table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
        <tr>
          <td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
        </tr>
      </table>

      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][2]['color']; ?>,<?php echo $newsletter['sale'][2]['type']; ?>,<?php echo $newsletter['sale'][2]['cat']; ?>?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['sale'][2]['invid']; ?>/<?php echo json_decode($newsletter['sale'][2]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][2]['color']; ?>,<?php echo $newsletter['sale'][2]['type']; ?>,<?php echo $newsletter['sale'][2]['cat']; ?>?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['sale'][2]['color'])." ".str_replace("-"," ",$newsletter['sale'][2]['type']); ?></a>

            </div>
          </td>
        </tr>


        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['sale'][2]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['sale'][2]['saleprice']; ?>
          </td>
        </tr>  

      </table>
    </td>
  </tr>

  <tr class="hide">
    <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
  </tr>

  <tr>
    <td>
      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][3]['color']; ?>,<?php echo $newsletter['sale'][3]['type']; ?>,<?php echo $newsletter['sale'][3]['cat']; ?>?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['sale'][3]['invid']; ?>/<?php echo json_decode($newsletter['sale'][3]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][3]['color']; ?>,<?php echo $newsletter['sale'][3]['type']; ?>,<?php echo $newsletter['sale'][3]['cat']; ?>?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['sale'][3]['color'])." ".str_replace("-"," ",$newsletter['sale'][3]['type']); ?></a>

            </div>
          </td>
        </tr>


        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['sale'][3]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['sale'][3]['saleprice']; ?>
          </td>
        </tr>                                  

      </table>
      <table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
        <tr>
          <td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
        </tr>
      </table>
      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][4]['color']; ?>,<?php echo $newsletter['sale'][4]['type']; ?>,<?php echo $newsletter['sale'][4]['cat']; ?>?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['sale'][4]['invid']; ?>/<?php echo json_decode($newsletter['sale'][4]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][4]['color']; ?>,<?php echo $newsletter['sale'][4]['type']; ?>,<?php echo $newsletter['sale'][4]['cat']; ?>?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['sale'][4]['color'])." ".str_replace("-"," ",$newsletter['sale'][4]['type']); ?></a>

            </div>
          </td>
        </tr>

        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['sale'][4]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['sale'][4]['saleprice']; ?>
          </td>
        </tr>  

      </table>

      <table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
        <tr>
          <td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
        </tr>
      </table>

      <table border="0" width="170" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

        <tr>
          <!-- ======= image ======= -->
          <td align="center">
            <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][5]['color']; ?>,<?php echo $newsletter['sale'][5]['type']; ?>,<?php echo $newsletter['sale'][5]['cat']; ?>?eid=&nid=" class="imgLink" style="border-style: none !important; display: block; border: 0 !important;"><img src="http://mjtrends.b-cdn.net/images/product/<?php echo $newsletter['sale'][5]['invid']; ?>/<?php echo json_decode($newsletter['sale'][5]['img'])[0]->path; ?>_370x280.jpg" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #333333; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 500; line-height: 20px;">
            <!-- ======= section text ====== -->

            <div style="line-height: 20px" class="edit">

              <a href="http://www.mjtrends.com/products.<?php echo $newsletter['sale'][5]['color']; ?>,<?php echo $newsletter['sale'][5]['type']; ?>,<?php echo $newsletter['sale'][5]['cat']; ?>?eid=&nid=" class="imgLink" style="text-decoration:none; color:#000; border-style: none !important; display: block; border: 0 !important;"><?php echo str_replace("-"," ",$newsletter['sale'][5]['color'])." ".str_replace("-"," ",$newsletter['sale'][5]['type']); ?></a>

            </div>
          </td>
        </tr>

        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; text-decoration: line-through;">
            $<?php echo $newsletter['sale'][5]['retail']; ?>
          </td>
        </tr>
        <tr>
          <td align="center" style="color: #808080; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; ">
            $<?php echo $newsletter['sale'][5]['saleprice']; ?>
          </td>
        </tr>  

      </table>
    </td>
  </tr>

</table>

</td>
</tr>

</table>
<!-- ======= end section ======= -->


<table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
<tr>
<td width="40" height="50" style="font-size: 50px; line-height: 50px;"></td>
</tr>
</table>

<!-- main section -->

<table border="0" width="100%" cellpadding="0" cellspacing="0" class="section_edit">

<tr>
<td align="center" style="background-image: url(https://mdbootstrap.com/img/Photos/Others/slide.jpg); background-size: cover; background-position: top center; background-repeat: no-repeat;"
background="https://mdbootstrap.com/img/Photos/Others/slide.jpg">

<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590Wide" bgcolor="2a2e36">

  <tr>
    <td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td>
  </tr>

  <tr>
    <td align="center">
      <table border="0" width="380" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">

        <tr>
          <td align="center">
            <table border="0" align="center" cellpadding="0" cellspacing="0" class="container580">
              <tr>
                <td align="center" style="color: #cccccc; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 26px;">
                  <!-- section text ======-->

                  <div style="line-height: 26px" class="edit">

                    The all new AW16 range is out. View an exclusive preview.

                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>
    </td>
  </tr>

  <tr>
    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
  </tr>

  <tr>
    <td align="center">
      <table border="0" align="center" width="250" cellpadding="0" cellspacing="0" style="border:2px solid #ffffff;">

        <tr>
          <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="center" style="color: #ffffff; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 22px; letter-spacing: 2px;">
            <!-- main section button -->

            <div style="line-height: 22px;" class="edit">
              <a href="" style="color: #fff; text-decoration: none;">VIEW THE COLLECTION</a>
            </div>
          </td>
        </tr>

        <tr>
          <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
        </tr>

      </table>
    </td>
  </tr>


  <tr>
    <td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td>
  </tr>

</table>
</td>
</tr>

</table>

<!-- end section -->

<!-- contact section -->
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="">

<tr>
<td align="center">
<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590 bg_color">

  <tr>
    <td style="border-top: 1px solid #e0e0e0;">&nbsp;</td>
  </tr>

  <tr>
    <td>
      <table border="0" width="300" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">

        <tr>
          <!-- logo -->
          <td align="left">
            <a href="http://www.mjtrends.com?eid=&nid=" style="display: block; border-style: none !important; border: 0 !important;"><img width="130" border="0" style="display: block; width:130px;" src="http://mjtrends.b-cdn.net/images/email/logo.png" alt="" /></a>
          </td>
        </tr>

        <tr>
          <td height="35" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
        </tr>

        <tr>
          <td align="left" style="color: #888888; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 23px;"
            class="text_color">
            <div style="color: #333333; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 600; mso-line-height-rule: exactly; line-height: 23px;">

              Email us: <br/> <a href="mailto:sales@MJTrends.com" style="color: #888888; font-size: 14px; font-family: 'Hind Siliguri', Calibri, Sans-serif; font-weight: 400;">sales@MJTrends.com</a>

            </div>
          </td>
        </tr>

      </table>

      <table border="0" width="2" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">
        <tr>
          <td width="2" height="10" style="font-size: 10px; line-height: 10px;"></td>
        </tr>
      </table>

      <table border="0" width="200" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">

        <tr>
          <td class="hide" height="45" style="font-size: 45px; line-height: 45px;">&nbsp;</td>
        </tr>



        <tr>
          <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
        </tr>

        <tr>
          <td>
            <table border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <a href="https://www.pinterest.com/mjtrends/" style="display: block; border-style: none !important; border: 0 !important;"><img width="32" border="0" style="display: block;" src="http://mjtrends.b-cdn.net/images/newsletter_images/pinterest-icon-grey.svg" alt=""></a>
                </td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                  <a href="https://www.youtube.com/user/MJTrends/videos" style="display: block; border-style: none !important; border: 0 !important;"><img width="32" border="0" style="display: block;" src="http://mjtrends.b-cdn.net/images/newsletter_images/youtube-icon-grey.svg" alt=""></a>
                </td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                  <a href="https://www.facebook.com/mjtrendsCreate/" style="display: block; border-style: none !important; border: 0 !important;"><img width="32" border="0" style="display: block;" src="http://mjtrends.b-cdn.net/images/newsletter_images/facebook-icon-grey.svg" alt=""></a>
                </td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                  <a href="https://www.instagram.com/mjtrends/" style="display: block; border-style: none !important; border: 0 !important;"><img width="32" border="0" style="display: block;" src="http://mjtrends.b-cdn.net/images/newsletter_images/instagram-icon-grey.svg" alt=""></a>
                </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</td>
</tr>


</table>
<!-- end section -->


<!-- footer ====== -->
<table border="0" width="100%" cellpadding="0" cellspacing="0">

<tr>
<td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
</tr>

<tr>
<td align="center">

<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590Wide" bgcolor="f4f4f4">

  <tr>
    <td>
      <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590Wide">
        <tr>
          <td align="left" style="color: #aaaaaa; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;padding:20px">
            <div style="line-height: 24px;">

              <p style="color: #333333;padding:0;margin:0;line-height:18px;">www.MJTrends.com</p>
              <p style="color: #333333;padding:0;margin:0;line-height:18px;">Phone: 571-285-0000</p>
              <p style="color: #333333;padding:0;margin:0;line-height:18px;">45915 Maries Rd</p>
              <p style="color: #333333;padding:0;margin:0;line-height:18px;">Suite #114</p>
              <p style="color: #333333;padding:0;margin:0;line-height:18px;">Sterling, VA 20164</p>


            </div>
          </td>
        </tr>
      </table>

      <table border="0" align="left" width="5" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">
        <tr>
          <td height="20" width="5" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
        </tr>
      </table>

      <table border="0" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
        class="container590">

        <tr>
          <td align="center" style="padding:20px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0">
              <tr><td>&nbsp;</td></tr>
              <tr><td>&nbsp;</td></tr>
              <tr>
                <td align="center">
                  <a style="font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;color: #5caad2; text-decoration: none;font-weight:bold;"
                    href="http://www.mjtrends.com/unsubscribe.php?eid=&nid=">UNSUBSCRIBE</a>
                    <img src="http://www.mjtrends.com/newslettertracking.gif?eid=&nid=" width="1" height="1">
                </td>
              </tr>

            </table>
          </td>
        </tr>

      </table>
    </td>
  </tr>

</table>
</td>
</tr>

<tr>
<td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
</tr>

</table>
<!-- end footer ====== -->
<!--End-->
  
  <!-- Scheduler -->
  <div style="width: 250px; margin: 50px auto;text-align: center;">
    <div>
      <label for="email_subject">Email Subject:</label>
      <input type="text" id="email_subject" name="email_subject" required>
    </div>
    <div id="picker"></div>
    <input type="hidden" id="result" value="" required/>
    <label class="schedule_error" ></label>
    <br>
    <button id="schedule_blast" class="btn btn-success">Schedule Email BLAST</button></br></br>
    <a href="../customer/invoice-list.php"><button class="btn btn-default">Back</button></a>
  </div>
  <!-- End Scheduler -->


  <!-- Modals -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width:900px !important;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Change Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="box">
            <input type="file" id="file-input">
          </div>
          <!-- leftbox -->
          <div class="box-2">
            <div class="result"></div>
          </div>
          <!--rightbox-->
          <div class="box-2 img-result hide">
            <!-- result of crop -->
            <img class="cropped" src="" alt="">
          </div>
          <!-- input file -->
          <div class="box">
            <!-- save btn -->
            <button class="btn btn-success save hide">Save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
        
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="scheduledModal" tabindex="-1" aria-labelledby="scheduledModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width:900px !important;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="scheduledModalTitle">Email Blast Scheduled</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body">
            <!-- input file -->
            <div style="width:50%; float:left;">
              <h3>Email Blast Off scheduled!</h3>
              <p>Now go do something cool...</p>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <div style="float:left;">
              <img src="https://i.pinimg.com/originals/99/c6/45/99c645244cc99a91bbaffa22313ecf3e.jpg" style="width:200px;">
            </div>
          </div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- end modals -->
  <style>
    footer{display: none;}
  </style>
  <div>
    <div>
<!-- Footer -->
<?php include('../layout/footer.php'); ?>
<!-- End of Footer -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/cropperjs/0.8.1/cropper.min.js'></script>

<script src="../../assets/js/newsletter.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.0/moment-with-locales.min.js"></script>
<script src="../../assets/js/datetimepicker.js"></script>