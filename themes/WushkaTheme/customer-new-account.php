<?php
/**
 * Customer new account email
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates/Emails
 * @version       1.6.4
 */
if( ! defined('ABSPATH') ) {
    exit; // Exit if accessed directly
}
error_log('start of email');
$user_school = 'Your school';
$s_feature_permalink = "";

if( user_can($user->ID, 'teacher') ) {
    $s_feature_permalink = '<a href="' . home_url('/features-for-teachers/') . '">School Features Page</a>';
    $s_permalink         = '<a href="' . home_url('/') . 'new-teacher-confirmation/?u=' . $user->id_hash . '&c=' . $user->tmp_pwd_verify . '">Activate Your Wushka Account</a>';
    $activation_link = home_url('/') . 'new-teacher-confirmation/?u=' . $user->id_hash . '&c=' . $user->tmp_pwd_verify;
} else if( user_can($user->ID, 'parent') ) {
    $s_feature_permalink = '<a href="' . home_url('/features-for-homes/') . '">our features page</a>';
    $s_permalink         = '<a style="color:#D64242 !important;" href="' . home_url('/') . 'new-trial-activation/?u=' . $user->id_hash . '&c=' . $user->tmp_pwd_verify . '">Click Here - 5 Day Free Login</a>';
} else {
    $s_feature_permalink = '<a href="' . home_url('/features-for-homes/') . '">School Features Page</a>';
    $s_permalink         = '<a href="' . wp_login_url() . '">Login Now</a>';
}
?>

<?php if( user_can($user->ID, 'teacher') ) { ?>
    <?php $i_count = (int)get_user_meta($user->ID, 'reminder_email_count', TRUE); ?>
    <?php if( $i_count > 0 ) { ?>
        <p><?php printf(__('Hi %s, ', 'woocommerce'), $user->first_name); ?></p>
        <p>We've noticed that you have not yet activated your Wushka account.</p>
        <p>You can activate your Teacher User Account by clicking on the link below. </p>
        <p><?php printf(__('%s', 'woocommerce'), $s_permalink); ?></p>
        <p><?php printf(__("Your username is: %s ", 'woocommerce'), esc_html($user->user_email)); ?></p>
        <p style="color: red;"><B>IMPORTANT: You must activate your Teacher User Account as soon as possible.</B></p>
        <p>We look forward to working alongside you in your classroom.</p>
        <p>The Wushka Support Team </p>
    <?php } else { ?>
        <p>
        <img width="180" src='data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABQAAD/4QMvaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA3LjEtYzAwMCA3OS45Y2NjNGRlOTMsIDIwMjIvMDMvMTQtMTQ6MDc6MjIgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCAyMy4zIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpENTE1NjgwMEQwMTIxMUVDQTNBQUY4QTY5OTdBQTZCOSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpENTE1NjgwMUQwMTIxMUVDQTNBQUY4QTY5OTdBQTZCOSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkQ1MTU2N0ZFRDAxMjExRUNBM0FBRjhBNjk5N0FBNkI5IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkQ1MTU2N0ZGRDAxMjExRUNBM0FBRjhBNjk5N0FBNkI5Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+/+4ADkFkb2JlAGTAAAAAAf/bAIQAAgICAgICAgICAgMCAgIDBAMCAgMEBQQEBAQEBQYFBQUFBQUGBgcHCAcHBgkJCgoJCQwMDAwMDAwMDAwMDAwMDAEDAwMFBAUJBgYJDQsJCw0PDg4ODg8PDAwMDAwPDwwMDAwMDA8MDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwM/8AAEQgALwDIAwERAAIRAQMRAf/EAMEAAAICAwEBAQAAAAAAAAAAAAcIBgkAAwUEAgoBAAEEAwEBAAAAAAAAAAAAAAYEBQcIAAIDAQkQAAEDAgQEAwUEBgMRAAAAAAECAwQFBgAREgchMRMIQSIUUWFxMgmRQlIVgbFyIzMWocGTYoKSonODwyQ0hJSktDUXJzcRAAECAwUEBgUJBQkBAQAAAAECAwARBCExEgUGQVFhkXGBoSITB7HB0TJi4UJScoKSsnMU8PGiIzPCQ1PDJDQVJTWzFv/aAAwDAQACEQMRAD8AvPui/rGslDbl43jRbWS8CpgVWfHiFwDnoS8tJV+gY4u1DbXvqA6TKHPLskr8xJFKw47K/AlSpdOEGUAWv96PbpQC62L7NckNZ/uKRBmSwojwS8GQyf7TCBzO6RHz59AJ+SDSi8ptR1Uj+nwA7VqSn+GeL+GAdcH1HLBipWm19vbgrTqcwhVSei05pXvCmlzF5fFGEDmpGh7iCemQ9sF9F5D5gs/6mpaQPhCnD2hsdsB2o/Uc3CdmtuUnbq3YFOSrN6HLkS5by0+xL6DHSk+/pnCNWpHSbEADrPsgqY8h8uSgh2qdUreAlI+6cZP3hFl+z25EXdzba1twokBVKTcMdxUimKcDpjyI7zkZ9sOAJ1pS40rSrSMxkSBywTUdSKllLgEp/uiv+qcgXkWZvUKlYvDIkqUsSVAKSZWyOFQmJmR2mCXhVA/GYyMjMZGRmMjIzGRkZjIyMxkZGYyMjRIlRoiOrKkNRm88uo6sITn8VEDHN15DQmtQSOJl6YycfbbrTyQtpxLqFcloIUD+kY2QtKxNJmOEZGzG0ZGYyMhAu767rgj3HQrRi1J6JQnKSioyobC1Nh95195r97pI1BKWhpB4AknEq+X9AyphdQpIK8eEE7AADZunO2K4ededVaKxqiQspaLYWQDLEoqUnvbwAkSHTCYplSU/LIdT8FqH9eJEKEnYIgoOrFyjzjaKhUE/LOkJ+Dqx/XjzwUfRHKNxVOj56uZjs0S87qtqox6vRK/Ogz4igtt1DyylWRz0rQolK0nxSoEHxwmqcup6lstuoSUnh6Nx4iHDLs9rqB5LzDy0rBvmeRFxG8GYMXRQJCpcGFKWkJXJYbdWkcgVpCiB9uK6OowLKRsJEXrpnC60lZvIB5iPXjnHaMxkZGYyMj81+4jN11Lc29o9wSJtzXe1XKhEqsvQt+Q+7EfW0opQgEhCQjypSNKUgAAJGI0qQtTygqZVMz6ov3kSqRnLKdTAS2wW0FIsSkBQBvO0ztJtUbTaYgPu5EcCMJ4eozGRke6nUuqViT6OkUyZVpmhTnpILDkh3QjipWhpKlZDxOWPUoKjIAnoji/UNMJxurShN01EJE90zIRcv9PmvLqeyNSorqvPa1zzojTZ5paktsTBw8M3HnMGmnnMVOU7lH1GKp+d9EGc8Q6P71lJ60lSPQlMPQpSUpKlEJSkZqUeAAHMk4fSQBMxDoE7BAZrO/e3NHlLhoqMisONK0uuU5kutJI9jqihCx70E4B67zFyelWUBanCLygYh94yB+yTBZSaJzKoRjKQgH6RkeVpHXKJpaW4Fp3u24beqyJMhhOqRAcSpmQ2OA1FpYBKczlqGY9+HzJtSUGbg/pnASL0maVD7JtlxExxhozPI6zLSPHRIG4i1J6xt4G3hEzw+w0wi/dV3q29sG+uy7WgR7u3OcYS9IgvLIgUht1Oppc5TZC1uLBCksoKVFHmUpAUjWW6e0q5mQ8Vw4Wt+1W/Dw+LfYAbZDOeajboD4aBic3bE9Ps3brJpHG3A+pJuSwLpoEO5YNGlj1NPZhUum0yMWlcU9BEttLzqMuRUpeftOCtVHp2kPhrKSoWGalKPXKwdkDaavPakY0BQBusA5b+2OnYfftvvtLdQtLuItmRX4jC0iqok09uk1+I2s5B1pKEsR30gAkJUhOvwdGOdZo+hrWvEolBJ2SOJB9JHOzdG9LqmrpHPDrEzG2zCoegH174uMs+77bv62aLeNo1Vmt25cEZMql1JjPStBJBCkqAUhaFApWhQCkqBSoBQIxGVTTOUzimnBJSTIj9uw7YkGnqEVDYcbM0m4wrG/NQMq9UxNZW1Saey2GSc0pccKnVEDkCpKk5n3DFffMmq8XM8E5htAEtgJmo8wUz6BHF896Ojde20WzLKp10UisT2ar/AKt64oc6aF+oA/h9MJUnSTwzJwqzrSbeUZYisYdWHO7ikZA490pESPEx6tvCmYgdwdxL5p2XprpqBA5JkOepH2PhzAvTapzWn9yoX1nH+PFHMOKG2JtSd97xgutGqIiVmIkj1CVNBl5SfHQtrSkH4oOCCi8x8yZUPGwuJ22YVS4FMhP7JjcPqF8CXu6KZG5duZq6KHraiZqWPkC5kviR7sXX8vVzy5agP7wn+FMVo86wFZ2yCZfyE/jciIb67NQNp12s5SqtJq8KvMPB56SlCSmRH6ZUUaAAErDgIBzIy5nDnpjUS82DocSElBF24z37RL5IY/MLQzWmjTllxS0ugzKgPeTKcpbDisFpG8wHIlsXFPo1QuGFRJsuhUpQRUqs0ypUdlRy4LWBkMsxn7MxnghcrWG3UsqWAtVyZ2nqgHYymsfp11LbSlNIsUsAlKek/tKccFXyn4YVQ3iLvqe6zFokF+Q6hiPHgtOPvuKCUIQhsFSlKOQAAGZJxWl+11XSfTF/qEfyGwPop9AipruC+ofWTVZ9rbCpixaZDWph/cWYymS7JWk5FVPjOgtJbGXBx1K9fghIyUohocjTIKev+j7fkg7y7TacIXUX/RHrPqHOFDp3eR3M02pCpo3ZqMxzWFuxJkaC/GWM8yksqj6Ug8vJpPsIw6KyqlIlgHb7YeVZLRqEvDHbP0xbR2nd2lP7gYU23bhgx7f3LoUcSp9OjFXpKhECkoVLhhxSlo0LUA42pSinUkhSgfKM5llhpTiTag9nAwH5vk5oiFJM0HmDuPqMCCw6lSduu/rc63p8RptG4kYfkk9wAKblSosepKShR5B5YdScuagkYBKdSWMzWk/Ou6SAe22Jlzqnezby/pH0E/6c94b0pUpq36owngCYW3u72dmUTuJYgW5CSiNu8/FmUBlI0tioS3kxZTfAcP3xDpy5BeGzOKMoqpJHvyl0mw9tvXB75YapRU6cK31W0gUle/AkFaD93uj6sau4/tJe2HtG3Lug3Y5dEOdMRS68h6MmP6eU60txtxnStWbai2pOSuIOnic+HmZZR+kbSsKmJyPTG2gvM1Opqx2lWyGlJSVokrFiSCAQqwd4TBssNtlkNL9Puzodt7dXxupV0ojfnstcWNOdGRbptJQVPLCjySp5S9X7A9mHXTzIQ0p07T2D5fREded2aLq8yp8tatwJBIG1x090dISBL60c7sSv9u69xe4NYbRCau2oM3TTaa0AhDSHZUtLulI4DJLzIONchqPEde+I4u0/JHfzlyQ0OW5aJzLSSypRvJCUS7Urhnt6K9Vq9XaBtLbkoRZNw6Xa5KzIAYVq0tKKeOnShS1gcSAkciRgV11mL9ZVM5NSqwqdtWfhtkk8JAqUNowi4mI90lRM0tO5mlQJhuxA42TPTaEpOwzN4EK7uDZErb+vmhS5zVR1Rm5TMtpJbBQ4VJyUgk6SCg+JxEmpMhXklX+nWsL7oUCBKwzFo2XHbEj5HnCM1p/GSkptIINtolt646si0L229plrbhIebgCc625TXGXCp5lTiC42l5GQGlxAOacyMvKrnhY5kuZZGzT5kCE4iCmR7yZiYxDcpM5i2ywwmRmlDmzr1CQVYQQqYsMjI4TvSbjZbaIceobqQIGzVb3bVHDkehW7OrUqnBXN+Ay4t2OFH2uNlAPjzxZTStYnPWadxFniyB+FU8Kh9lU4gvUNMcoceQq3w5kcRLEnmJRQ/wBqbMLdfuwsyfuW+iuSK/VqjXqn6wJU3NqbUZ+a0laCNJT1kBQRllwCcsuGJ71CVUWVLDFmEBIlsTMJPZtiF8ilV5khT1syTbtMifTDzd4XcX3T7SblvQ7Loooe2kaNGcpFwikJqLFQWtpK5HqJLiFpaUhwqR0wUHSArjqBwJaZyTLK2mm6qbpJmMWEp3SG2y2dsFGoM3zCkfk2mTYlIynPfMm63ZCKdxndTM7j7dsCFXLGptCuW0lSVVW5YTqnPWddKU9NltadTLWadakKcX5ssiPEuyPTwypxwocKkqlJJ2S37z1CBbOc9OYtoCkAKTed/sHWYbn6YO5s/wDMr+2gnSFvU4w03TbzKjmmO4263EnpSTyDnVYUE8gUqPNRwN6+oE4W6kC2eA8bynlI9kP+ia1RK6c3SxDhsPOY5Qw+5s4VC+bskA5hExccf7shLH+jxRvV1R4+a1KvjKfuAI/swZOGajDKbnMPSdqWG2GXJDqkU0paaSVqPmb5JSCTiWNXtqcyBISCTJuwCZvG6FLvuQnLrTrDimn2lsOp+ZpxJQofFKgDiCVoUg4VAg7iJHthHHQokL8yrVHpxGYqE6NGUPc66lB/oOFOX0/6ipaa+mtKfvECPUiZlGjvC/8AplGA8LZi5f8AFzMfQ/y+/wBgv8w/hTFavO3/ANpr8hP43IKe+1Ln35tPs5NpLBnVSry6bHitp5qcqEEkgnwGpAzPhzwyaYfRQZlVpcMkpCiehCvYYL/MKkdznIcsWyMTjimwOlxv2i2PjeOdTdmNmKNtVRXUO1i4mFMT5AHmU0SFzZChzHVWemkH7pOXy490825nWarrnB3EGYHH5ieoWnj0x5rioZ0npxrKGCC46JKPC9xf2j3Uz+bP6MIEr5T8MSpFcBDW/UH3gmWXtfbG1tDlqi1LcdlZuB9tWlaKLDS2HWSQQR6pxaUHwU2l1J54gTJ6UOvqdNyTZ0n2eyPpNpGhDknFXISJfWl6vTKARsV2e7bQNrEb39y9aeoltzYqKhTbf9Q5CaYgu5dB6W40Outx/UC202QcikeZR0pVVmaOl3wacTO++3h0b4eK/OXlP+BSiarp328NkhvMHCmdlPahuXZtQ3A26uCupt6swnnKHUYtQccjwXY3US4roy2usSlacltvKz4ZDTnnhGrNqtlYQ4BMX2X8vVCBeeVzDgbdAmDbZfys6xFWmxV8ydt94dubziSS03Sq5FRUXE8A5T5S/TTE5exUd1eWfI5HwwR1jIeZUg7R23jtgrr6cP060HaDzFo7YfjvxRU7J372/wBw6KfSz1UaFMp8lPDOdR5zq8yf2XGgfdiDM/m1UocTfIc0n90Sh5MlrMcgqaF61PiKSR8DqAPSFHpiwKZbFtb8Q9h914biEptmoR7qpij5ipqTDWHIpIHzJf6Sj728EJaRWBp4bDi7LucuUQi1mNTpleY5Ysf1EllXSlQkvoKcQ6Fx2N+7ARuvtHftkww1JrD8JTtGb1JzbqcUJlREqJPkKlJSDn91XsON8wp/1DC0C+VnSLRCXRedHI83pqtcwgKkri2qaF9MhPrELtvk8z2+9nUKxILvp6vUaXEtRlSFAKXJnpLlTdGX4kddWftIw21x/R0AbF5ATzv9cHej0q1TrJVasTQlanuhKLGhzwDqhK+wyvpo3cFT6epWlN1UGp0pCfDW2lueP6IhwyZA5hqgN4I9fqiWfOeiNRp5S/8ACcQvnNv/ADBFgUJ1Unugkqf5tPOttA+AbpgSnL9eA1hZc1soq2EgdTVkQo8nBpUS2gdrkQPuKcUvceWg8mqZEQn4ELV+s4HvM1ROcKG5tPrMPWg0yy1J3rV6oK+9gK9nLJcSPKHqYSfZnCcywY68E9PUp4t//MwM6QszqoHBf4xA5h0mp3Z2l74W5AbclTnKbXWqTFbGa3HBT25CWUDxK3Mxl7Tgl8karAygrPdTUS6AQgntJMMPmvT4nFBItUzPpIKvYBFHe2Fv7i3JeVIa2op1TqV70tX5tSDSOEmOYhDnqErJCUhBy4k5EkJ455G3uYPU7TJNQQGzYZ3GeyKw0LNQ48AwCVi0S2SixSxfqZ3nQm00PdrbePcMyApUSp1WlvGmzNbaihzrwnkLa6gIIUEqbGfgMBNXoNlzv0zuEG0A94dShbLnBhS6zdb7lQ3Mi8iw8v3RLe63bnZreLtzb7o9rqIzb1UjdCXNeZjohLmx3JiYElibHb8hfYdVmHBmTpI1KSQQn07XVlDmH/H1CsQtF85GWIFJ3EbI757R0tbQ/rWEyPKduEz4g7YEX0x6FNlbzXrcyWz+VW/aDsSdJ+6l+oTYymEH9pEV0/3uHDX76UUSEE2lc+pIM/SIb9EtKNStewJl1kiXoMNHUpiqjNqNQX88996Sv4vLUs/rx89Kt8vuLdN6lKV94k+uDwmcPsa/EtyxYdfnhS48GlRnVtIy1rUptAShOZAzUogYsoczby/Kk1LlqUtpMhebBIDpMLsWFM4iMlq2t5rOkzIkQsVGP1GojzyUiRFlISFBJUknNCsxnxyIPt5MbyKLV2WqcQmTgmATLEhYE5TF6TZwIO+7SxxMLftZBM3cK22HEf7PIcfdT+Ex2luDP4KSMRTo2m8bOGEkXKJP2Uk+kCE7QmoRB+7857n0ofhtuKP+alnF9PL8f9er8w/hTFZvOw/903+Qn8bkOPsW4xVNotvJLzSHlwoOiOtYCi25HU7G1JJ5HSCMx4HEe6nBazN8Aymq3jOSonHy+UmpyCjWoA4U2cCkqRMcZTEJR3P2zfEK+X7ouRLUiiVg+mtyXFUpbLDDAOiMsKCShzLNZ8FEqKTwOUkaKraRdGGGZhabVg3kn5w3jZwsnEC+bWVZm1mZq6qRac7rZTMpSlNyDMCSvnHYSSQb5LKeR+GDOIoEdLvbZF092Ng2tUlaaa5S7Wo6wo5J6E2oPF4/p65B+GIUyo4KVahfNR5CPp7pv+VlpUL7TySPZBw+pvWpNPtXaGzoSjFo0+oVCdJht+VpRpzDDMZOQ4ZIElRA+GEOnkArWs3yHb+6E2lkBS3Fm8ADnOfoiabICfYH09q9XSlTcyXbN0ViEDmClMxclMdY+KdKx8ccqyTuYhPFI5SjhXyezUJ2YkjlKcU32TQZFyXfZ9sQklUqv1mnUuKBz1ypLbKT+gqwUvLwIUo7ATBo+4G21LOwE8hFwf1IaB1rX2wupI/7XV51IcPuqEdMhOfw9EftxCWpW5oQvcSOY+SCTyFrcNVV030kJX9xRT/mR6eyvf8A28oW0K7Kve8qfbNVs+bMdit1WQmOl6nyXDIQphThAWULWtJQnzDhw4jHuSZg0hjA4oApJv3Xxp5taJzGpzgVdGwpxDyUg4AVSWkYTildMAGZs5RE9lu721o++O8c286yqj2Ff89Ey1qrKQ4W4y6c2mGwHUpCi2JEZtBJy4KSAeeOVFnCBUuFZkhRsPRZ2iHLVnlfVLyKhRSN46lhJS4kETV4hxqlvwLJHEEkQBO8ff2kbz3jR6VZ8tcyx7NadEKeUKbTOnSCOtIQhYSrQlCUoQVDM+Y8iMN+c5gmqcAR7qe074NPKvRT2nqNbtUmVQ8RMX4EJ91JIsmSSpUvhF4gOdvFcdt3fXaWps56jc8CCsDn06i56Fz/ABHzhFly8FS2fiA52euCrXNGKvIa1s/4K1dbY8QdqYtr3JQbB3sty+X0qFHrC2nZT4GYSW2xEkpAHilspX788DeqU/8AC6kZzBX9JyRJ6B4a+ScKuM4rPp8/8pkbtGn+oiYA6TjTzVNMEHcDZqHuRcFLuiHX24kCRFaaqPSb63XaQSpC2XAsJBUlWWZzHI4JNSaHbz6rbq0PBKCkBUhixJFoKTOVoMtuwwx5HqxeT066ZbRKgolMzLCTeFCU7xON+77NFrW01bTRZ0aREth5gIUy4lSG1w3ENra1A5aghWWWOmtUU1XkLoYUkpZKbjMAoIBTPeAY00st+nzdvxkkKdBvFpCwSD0TESDZW2nra29pEeYyWZ1VK6lMZUMikyci2lQPEENBAIPI4ctCZWrL8pbSsSWuayPrXdeHDPjCHV2YJrMxWUmaUyQPs3/xTlwisncHay8eyjfhG/u3ltP3PsxVHZKbhpMFPnpUOeQqTCdCR+7aQsByM6RoGlLbhByK5+oswZz+h/RvqwviUifnEXK4nYoX3kcIYqqF3Jaz9UynE0bwPmg3jo3HoB4kq5aN9PruRnK3CqN7wbMuOpgSK8kVNFvzHnMvMZcaUC0tzwU42CVc9aueELDufZUnwUtlaRdZjHURbLgeULH28mzE+KpYSo324T1g2dY5wP8AfrdCzL22/tzs97RaM/e7Mhcduqv0hLjkRmFFfEjR6p7ILLkjS48+tQbSM81kq8qzJ6B6nqFZnmSsF8p3zIlcNwsSm/hZCXNKxp9hOX0Axb5XSBnf02k3epzNiNiInbRsVX6Up9mo3pUoMqrXjWo4PTcmiOpLbDClALLMdPlQSBmdbmlJWUiPNe6hXWMVD6ZhKG14BukDaeJNp3XbIJMmyoZbTYL1m1R47uge07YARGSCB4JyH2Yp6RZCmHE3CUn/AMMRjqGS4VK0HPn5mTw9uJ21QR/+aT9Rr+zCtz+nHJ7fpEVm3ri6slttbU4PPoUoJKGuijzqz5J4Hj7sIvLF1tFG/NQBC5m24YRaeF9vCPGLjEM2PjJmX9WaikamYsSU40vwCn30BH2p1YYPLtkPZs66LkpUR9pQl2TjRkTVAN7vT/7Rp3ut2J/1MrF1NAf+er8w+hMVh86//bR+Qn8S4Me3dQmI7Ta29T5T0WbTIFZSzJjrUh1soedczSpJBSQFeBwPZu0k6kQFgEKUiw3GwCDjTFS4nQbqm1FKkIdkQZEd4qvF1hj0W9Inb7duFUpcxYqt3UdLkdtxZBecmQSHoyyT991ohBUeZKvfjWrQjIs9StPdaVbwCVWK6gbZbLI65a67rHR7jSzjqG5j4ituSkHpUnuk7STxhFbhsm7rUjQJVy27OobFV6noFTGi0XC1kFjSriCMxwIHDjyxJ1JmVNVqUllxKymU5GcpxXvMsgr8sShdUytsLnhxCU5X32jr6Y7H1IrGqtHvTbbdumhxqHUaa1Qn5zaeEapU11yZEUpX4nUOrKf8kcQ3kTwUlbR3z6jYf24x9IdKPpUwpk3i3qIAP7cYKNU3m7T+6vbS0jvtc/8AJN1WcsS6nTPUOwnxJLaW5QiuJbcD8eRpB0o/eDIfKoZ4TJpKuidV4IxA9fRPcRHBFFXZc8r9OnElW2/onuI5RKme7Htw3B2l3a29eqTW39t0ejy7ctCmTEKQ7UKWYAYjPw2ACoqDmpKWuKwAgqy1HLkcsqWnkLliJMzwM9vtjkcoq2X23JYlEgngZ2z9sKf9PTZSfee5Cd1qvDUm1duNYp7zifJLrjzWhttGYyUIzbhdUQc0rLXtOTnnlWG2vDF6vR8vth31HXBpnwUnvL7E/LdzixbvTtaJdux1RgLrFHo9UiVWDOt5VbqEamMSJTJXrjtyJbjTQdXHU7oBUMyOYGZEcZ20HKYiYBmCJkC3dM8Jw4eU2Yroc9SsIcWgoUlfhoU4UpMpKKUAqwheCZAs7IpZpm3teq0hUdifbMYtr0renXPQYjYPtCn56Aoe9OeApNOpRvT1qSPXFsqjPKdhOIpeP1WHlHklsy65QaKB2zt1EtLrm/u0NtsL/iI/mmNNkI/zbWls/wBrha3lmL3nWx9oH9ucCdb5gFmYZy2ucP5CkJ5mav4YN9v9p/bmyUquvuytypD7zNHqFGgZe7XIlzM/8EYXN5TSD36hJ6CkekmBCu8ytSK/22TOp+uh1fYlDfphqdm9qez+w7npMuybzty776U4WqDKnXHCqc7qqSc/SxWXUthzSDkpDWsDPI5Z4daKkoGVgoUlStk1AnqHyRHGqtSaxzOlWmrYdap5TWEsrbRL41qBVh4KXhushnNxaRZ9dt1dMvKoxKTBkOj0FRkvtR1MyglRQplbpA1BOrh4pzB4Z45anosvrKQtVy0oST3VKUE4VyMikqsnKdm0T2RHuQ1VZTVPiUiVLUBakAqmnaFAbLrdhlthPZ22l6QIbzduXnTq9axKspUGstx42jxLrTjyW0n2gKUPfiE6jSuYstkUtSh2n3peCUy+JJUEjjIq6YlRnUNE6sGoYW29uU2VK6iEknrAiX7a7a23C0Vm8r7oUmkxZLTibeh1Rl2GuSMi0Za9YbUQeSADnwzURmkvWltLUbUn66rZLaVA+GlxJQV/N8QzwngkTnvImC16g1BUufyqSncCyD31IIWE7cAlMfW2bp2w5+JziJY+HOn019XT0tJ6mvLTpy4558MsseiMMJhclu9gdVq0l+4pGzTVYaeX69P5pR4TpeCvP122ZDWpWfPWM/bgnYfz1CAEB/Ds7qjymDA+6zlC1TV4U+lI9Bg/7UtbLMUaS1sobNVQm3QmcbNXAcj9YDh1lwSQV5eKjnhnzA1hWDVY8WzHOfVihzohShJ/T4JbcMu2UE93pdJ3r6OhoV1uplo0ZebVnwyy554bV4cJxSlK2d0uMLYBdQtHYyoqcdTVaPAcdJJXDqzbaRn+FvqlsfAJyxHNTkelXySHGkk/RdAHUMWEco4FDZ/fHKrO19rOUSD09zZMSga86WJ8yPIghRz/AIXmZSfHkcIq/R9AqlRKvUlmfcxrSpufw2oHIx4WhL3rIGszbCE11DSty7YmpCT1A9MRGOn36FvDL4nAm/o9pM/Br6dW+awizqK45FrcRBk2ZteHbqa46LkpNdqM0MB6PSpCZKY7TZc0lShkc1lR+6Bw8cHmgcnboA8fHadWrDMNqCwkCcpm/vT3C7bHZlMp2ws/d1brrt50qvsVSmLSqjsxZNKXOjtTmi2++pLvpnHEuKbXryCkg8Uqzy4Z2e0DVgUqmilXvkg4SUmwWYgJAiVx3iK4edOWKVmLdSlxv+mElJWkOCSlSVgUQopVOwgG0GcrJ8Tbe+rmt/Zi9LYRt9U7hpNRRUPT3LFSVwI6ZEcNyPUOpCkgNZa+B8eOXPCnOMsp6jNWXvHShacM0H3jIzThHxXe2EGl9Q1tFpyqpBRuOtrC5OJtbSFJwrxqAI7nvX9Mr4Gm09d3YtebUZ+2lNnVhCktIrUKJDXUIyh5i11kNBWk/NpIIPPjlnh6z2ly2qQlNapKb8JKgg8ZT6p3wKaNzHPsudW5lTa3BZjSlBcTtw4gmcjfI2G+2+JFfkvd/cqtUCLuOwmzoJdU3TXa00KJTWNWnruhcso6ikpyJAUpWXBI44R5W3lmWNLVRnxFStwHxVncJJnLkBvh01C/qDP6llGaD9OiZCS6P07SbsSpuSxECUxNStgEPzvJQNr7l24rVvbu1KmUqyKohqPKq1UmM09uO+VD0zrMp9SUNvJcALZz58MiCQYZpVuodCmgSrcBPpsi4uXreacSpgEqG4TmOgbIo03A7YadQapJ/kHfray/KAtwmA49dlKptQSg8g+y/IDOaeWpDvm56U8sF7GYFQ77a0n6pIg/p81K0/zGnEn6pI9E+yJPtb2r2NValGmbudxe2lrW+2oLk0ai3TTJtTfAPFvrF0R2M/xgun+5HPHOpzJxIk00sneUkD2nsjlV5s6kSZZWTvKSB7T2Rd5thT9uqVYtv0zad2kP2FT2Vx6E9Q5LcyGsNuKS6oSGluB1ZdCuosqKlL1FRKs8CNQpxThLs8W2d8AlWp5TpL08ZvnYeXo4R//Z'/>
        <br/><br/>
        <strong>WELCOME TO WUSHKA!</strong> <br/>
        You're almost there! Just a few simple steps to get you started. <br/><br/>

        <ul>
            <li>
                <strong>Log in now!</strong> <br/>
                Click <a href="<?= $activation_link; ?>" target="_blank">here</a> to create your Wushka password. Then you're good to go!<br/>
                <i><strong>Hint!</strong> Your username is your email address.</i><br/><br/>
            </li>
            <li>
                <strong>Need a hand getting started?</strong><br/>
                We've got your back. Get your class up & Wushka-ing in 5 minutes flat with <a href="https://www.youtube.com/watch?v=ulykDyd5TX4" target="_blank">Ready Set Read!</a>. Or, for a deeper dive, jump on over to the <a href="https://www.youtube.com/watch?v=ulykDyd5TX4&list=PLB4grSdWGPd5BcK7PnHmPAF0Q0ZncYYeh" target="_blank">Wushka Getting Start Series</a>.<br/><br/>
            </li>
            <li>
                <strong>Meet your Wushka specialist</strong><br/>
                If you have signed up for a trial, your Wushka specialist will check-in soon. In the meantime, feel free to reach out to our eLearning team for a Wushka demo, some Professional Learning or anything at all!<br/><br/>
            </li>
        </ul>

        It's great to have you onboard!<br/><br/>

        <strong>The Wushka eLearning Team</strong><br/>
        <a href="mailto:onlinelearning@teaching.com.au" title="onlinelearning@teaching.com.au">onlinelearning@teaching.com.au</a>
        </p>


        <?php
        /* 
        <p><?php printf(__('Hi %s, ', 'woocommerce'), $user->first_name); ?></p>
        <p>
            The Wushka Coordinator at your school has asked us to set you up as a Teacher User in the school’s Wushka account.
        </p>
        <p>To activate your Teacher User Account please click on the link below. We look forward to supporting you with Wushka.
        </p>
        <p>The Wushka Support Team </p>
        <p><?php printf(__('%s', 'woocommerce'), $s_permalink); ?></p>
        <p><?php printf(__("Your username is: %s ", 'woocommerce'), esc_html($user->user_email)); ?></p>
        <p style="color: red;"><B>IMPORTANT: You must activate your Teacher User Account as soon as possible.</B></p>
        */
        ?>
    <?php } ?>
<?php } else if( user_can($user->ID, 'parent') ) { ?>
    <p><?php printf(__('Hi %s, ', 'woocommerce'), $user->first_name); ?></p>
    <p>Please find a link below to your Wushka Account</p>
    <p><?php printf(__("Your username is <strong>%s</strong>.", 'woocommerce'), esc_html($user->user_email)); ?></p>
    <p><?php printf(__('<br/>%s.', 'woocommerce'), $s_permalink); ?></p>
    <p>The Wushka Support Team </p>
<?php } else { ?>
    <p>Dear Subscriber,</p>
    <p>Welcome to Wushka.</p>
    <p><?php printf(__("Your username is <strong>%s</strong>.", 'woocommerce'), esc_html($user->user_email)); ?></p>
    <p>Start Today<br/><a href="http://wushka.com.au/">Login Now</a></p>
    <p>The Wushka Support Team </p>
<?php } ?>

<?php error_log('finished email'); ?>
