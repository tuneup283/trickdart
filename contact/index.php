<?php
// header("Content-Security-Policy: script-src 'self' trickdart.tokyo;");
header('X-Frame-Options: SAMEORIGIN');
ini_set('display_errors', "Off");

session_start();
$success_message = "";
$error_message = [];
$max_count = 200;
// トークンの生成
if(empty($_SESSION['csrf_token'])){
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //トークンが送信されているかの確認
    if (!isset($_POST['csrf_token'])) {
        $error_message[] = '不正なリクエストです。';
    //トークンが合っているかの検証
    }else if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message[] = '不正なリクエストです';
    }

    // フォームからのデータを取得
    $data['company_name'] = htmlspecialchars($_POST["company_name"]);
    $data['name'] = htmlspecialchars($_POST["name"]);
    $data['mail'] = htmlspecialchars($_POST["mail"]);
    $data['tel'] = htmlspecialchars($_POST["tel"]);
    $data['message'] = htmlspecialchars($_POST["message"]);
    // $data['agreement_check'] = htmlspecialchars($_POST["agreement_check"]);
  
    // 入力値のバリデーション
    if (empty($data['company_name'])) {
        $error_message[] = "貴社名（お名前） を入力してください。";
    }else if(isset($data['company_name']) && mb_strlen($data['company_name']) > $max_count){
        $error_message[] = "貴社名（お名前） は 200文字以内で入力してください。";
    }else if (preg_match('/[&"\'<>]/', $data['company_name'])) {
        die('貴社名（お名前）に使用できない文字が含まれています。');
    }
    if (empty($data['name'])) {
        $error_message[] = "ご担当者名 を入力してください。";
    }else if(isset($data['name']) && mb_strlen($data['name']) > $max_count){
        $error_message[] = "ご担当者名 は 200文字以内で入力してください。";
    }else if (preg_match('/[&"\'<>]/', $data['name'])) {
        die('ご担当者名 に使用できない文字が含まれています。');
    }
    if (empty($data['mail'])) {
        $error_message[] = "メールアドレス を入力してください。";
    }else if(isset($data['mail']) && mb_strlen($data['mail']) > $max_count){
        $error_message[] = "メールアドレス は 200文字以内で入力してください。";
    }else if (preg_match('/[&"\'<>]/', $data['mail'])) {
        die('メールアドレス に使用できない文字が含まれています。');
    }
    if (empty($data['tel'])) {
        $error_message[] = "ご連絡先電話番号 を入力してください。";
    }else if( isset($data['tel']) && !preg_match('/^(0{1}\d{1,4}-{0,1}\d{1,4}-{0,1}\d{4})$/', $data['tel'] ) ) {
        $error_message[] = "ご連絡先電話番号 を正しく入力してください。";
    }else if (preg_match('/[&"\'<>]/', $data['tel'])) {
        die('ご連絡先電話番号 に使用できない文字が含まれています。');
    }
    if (empty($data['message'])) {
        $error_message[] = "お問い合わせ内容 を入力してください。";
    }else if(isset($data['message']) && mb_strlen($data['message']) > 500){
        $error_message[] = "お問い合わせ内容 は 500文字以内で入力してください。";
    }else if (preg_match('/[&"\'<>]/', $data['message'])) {
        die('お問い合わせ内容 に使用できない文字が含まれています。');
    }

    // // 同意
    // if(empty($data['agreement_check'])){
    //     $error_message[] = "プライバシーポリシーへの同意がされてません。";
    // }else if(isset($data['agreement_check']) && $data['agreement_check'] != "1"){
    //     $error_message[] = "プライバシーポリシーへの同意がされてません。";
    // }else if (preg_match('/[&"\'<>]/', $data['agreement_check'])) {
    //     die('お問い合わせ内容 に使用できない文字が含まれています。');
    // }

    if (isset($data['company_name']) && isset($data['name']) && isset($data['mail']) && isset($data['tel']) &&
    isset($data['message']) && count($error_message) == 0) {

        // メールの送信 
        $to = "fujiwara@trickdart.tokyo";
        // 送信先のメールアドレスを指定 
        $subject = "サイト「http://trickdart.tokyo/」よりお問い合わせがありました。";
        $message_body = "貴社名（お名前）: " . $data['company_name'] . "\n"; 
        $message_body .= "担当者名: " . $data['name'] . "\n"; 
        $message_body .= "メールアドレス: " . $data['mail'] . "\n";
        $message_body .= "ご連絡先電話番号: " . $data['tel'] . "\n";
        $message_body .= "お問い合わせ内容:\n" . $data['message']; 
        $headers = "From: " . $data['mail'];

        $success_message = "お問い合わせを受け付けました。ありがとうございます！"; 
        if (mb_send_mail($to, $subject, $message_body, $headers)) { 
            $success_message = "お問い合わせを受け付けました。ありがとうございます！"; 
        } else { 
            $error_message = "メールの送信に失敗しました。後でもう一度お試しください。"; 
        }
         
        // 入力データ削除
        $data['company_name'] = null;
        $data['name'] = null;
        $data['mail'] = null;
        $data['tel'] = null;
        $data['message'] = null;
        // $data['agreement_check'] = null;
   }
}
?>
<!DOCTYPE html>
<!--[if lt IE 9 ]><html class="no-js oldie" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js oldie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="no-js" lang="ja">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>お問い合わせ | TRICK DART LLC</title>
    <meta name="description" content="TRICK DART LLC のホームページ。岩手県出身の盛岡人。照明デザイナーとして30年のキャリアを誇るプロフェッショナルです。">
    <meta name="keywords" content="お問い合わせ TRICK DART LLC,Takashi Fujiwara,藤原敬,照明デザイナー,盛岡人,釣り人">
    <meta name="author" content="">
    <meta charset="utf-8" />
    <!-- facebookのOGP -->
    <!-- <meta property="og:url" content="【ページのURL】" />
    <meta property="og:title" content="【ページのタイトル】" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="【ページの説明】" />
    <meta property="og:image" content="【サムネイル画像のURL】" />
    <meta property="og:site_name" content="【サイトのタイトル】" />
    <meta property="fb:app_id" content="【appID】" />
    <meta property="og:locale" content="ja_JP" /> -->
    <!-- TwitterのOGP -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@fujiwaratakasii" />
    <meta name="twitter:description" content="TRICK DART LLC" />
    <meta name="twitter:image:src" content="../images/summary_image.png" />
    <!-- レスポンシブ対応 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/vendor.css">
    <link rel="stylesheet" href="../css/colorbox.css">
    <link rel="stylesheet" href="../css/main.css?202403091855">
    <!-- JS -->
    <script src="../js/modernizr.js"></script>
    <script src="../js/pace.min.js"></script>
    <!-- favicons -->
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon-precomposed" href="" />
</head>

<body id="top">

    <!-- header -->
    <header class="s-header">

        <div class="header-logo">
            <a class="site-logo" href="../index.html">
                <img src="../images/logo.png" alt="TRICK DART LLC">
            </a>
        </div>
        
        <div class="header-navi-wrap pc-only">
            <ul class="header-nav-wrap__list">
                <li class="arrow"><a class="" href="../index.html#works" title="works">WORKS</a></li>
                <li class="arrow"><a class="" href="../index.html#profile" title="profile">PROFILE</a></li>
                <!-- <li class="arrow"><a class="" href="../index.html#gear" title="gear">GEAR</a></li> -->
                <li class="arrow"><a class="" href="../index.html#fishing" title="fishing">FISHING</a></li>
                <li class=""><a href="./" class="contact-btn bgleft"><span>CONTACT</span></a></li>
            </ul>
        </div>

        <!-- scroll -->
        <div class="home-content__scroll contact-ver pc-only">
            <a href="#form" class="scroll-link smoothscroll">
                <span>SCROLL</span>
            </a>
        </div>
        <div class="home-content__line contact-ver pc-only"></div>

        <nav class="header-nav sp-only">

            <a href="#0" class="header-nav__close" title="close"><span>Close</span></a>

            <div class="menu-logo">
                <a class="site-logo" href="../index.html">
                    <img src="../images/logo.png" alt="TRICK DART LLC">
                </a>
            </div>

            <div class="header-nav__content">

                <ul class="header-nav__list">
                    <li class="arrow"><a class="" href="../#works" title="works">WORKS</a></li>
                    <li class="arrow"><a class=""  zhref="../#profile" title="profile">PROFILE</a></li>
                    <!-- <li class="arrow"><a class="" href="../#gear" title="gear">GEAR</a></li> -->
                    <li class="arrow"><a class="" href="../#fishing" title="fishing">FISHING</a></li>
                </ul>
    
                <div class="tab-full">
                    <div class="header-nav-bottom">
                        <!-- <div class="mail"><span>MAIL</span></div>
                        <div class="tel"><span>TEL</span></div> -->
                    </div>
                    <div class="header-nav-btn-area">
                        <div>
                            <a href="./" class="contact-btn bgleft"><span>CONTACT</span></a>
                        </div>
                    </div>
                </div>
            </div>
            
        </nav> <!-- end header-nav -->

        <a class="header-menu-toggle sp-only opaque" href="#0">
            <span class="header-menu-icon"></span>
        </a>

    </header> <!-- end s-header -->

    <section id="contact" class="contact-contens">
        <div class="s-contact"></div>
    </section>

    <section id="contact" class="contact-contens-title">
        <h1 class="s-contact-h1 bgextend">CONTACT</h1>
        <div class="s-contact-subtitle">
            <div class="bgextend_line"></div>
            <div class="s-contact-text">お問い合わせはこちらから</div>
        </div>
    </section> 

    <section id="form" class="s-form">
        <div class="row services-list block-1-2 block-tab-full">
            <div class="s-form-h2">
                <h2>お問い合わせ</h2>
            </div>

        <!-- エラーメッセージ -->
        <?php if (isset($error_message)): ?> 
            <div class="message_area">
            <?php foreach($error_message as $_message): ?> 
                <p class="error"><?php echo $_message; ?></p> 
            <?php endforeach; ?> 
            </div>
        <?php endif; ?> 
        <!-- 完了メッセージ -->
        <?php if (isset($success_message)): ?> 
            <div class="message_area">
                <p class="success"><?php echo $success_message; ?></p> 
            </div>
        <?php endif; ?> 


            <form class="contact-form" method="POST" name="form1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="form-item">
                    <p class="form-item-label">貴社名（お名前）<span class="form-item-label-required">必須</span></p>
                    <input type="text" class="form-item-input" name="company_name" id="company_name" value="<?php echo $data['company_name']; ?>" maxlength="70" placeholder="貴社名をご入力ください。" required>
                </div>
                <div class="form-item">
                    <p class="form-item-label">ご担当者名<span class="form-item-label-required">必須</span></p>
                    <input type="text" class="form-item-input" name="name" id="name" value="<?php echo $data['name']; ?>" maxlength="50" placeholder="ご担当者名をご入力ください。" required>
                </div>
                <div class="form-item">
                    <p class="form-item-label">メールアドレス<span class="form-item-label-required">必須</span></p>
                    <input type="email" class="form-item-input" name="mail" id="mail" value="<?php echo $data['mail']; ?>" maxlength="70" placeholder="メールアドレスをご入力ください。" required>
                </div>
                <div class="form-item">
                    <p class="form-item-label">ご連絡先電話番号(携帯可)<span class="form-item-label-required">必須</span></p>
                    <input type="text" class="form-item-input" name="tel" id="tel" value="<?php echo $data['tel']; ?>" maxlength="20" placeholder="ご連絡先電話番号をご入力ください。" required>
                </div>
                <div class="form-item">
                    <p class="form-item-label isMsg">お問い合わせ内容<span class="form-item-label-required">必須</span></p>
                    <textarea class="form-item-textarea" name="message" id="message" maxlength="300" rows="30" placeholder="お問い合わせ内容をご入力ください。" required><?php echo $data['message']; ?></textarea>
                </div>

                <!-- <div id="agreement">
                    <div>
                        <p>個⼈情報保護⽅針<br>
                        当サイトでは、個⼈情報が重要な資産であると認識し、個⼈情報を保護することは社会的責務と考え、業務上使⽤いたしますお客様、及び従業員等の個⼈情報について、個⼈情報の保護に関する法令及びその他の規範を遵守し、次のとおり個⼈情報保護⽅針を定めます。<br>
                        <br>
                        【1】個⼈情報保護ポリシーの⽬的<br>
                        個⼈情報を的確に保護し、お客様、及び従業員等への継続的な安⼼を提供いたします。<br>
                        <br>
                        【2】個⼈情報保護ポリシー<br>
                        （1）個⼈情報の取得について<br>
                        個⼈情報を取得する際は、該当する個⼈情報のご本⼈に対し、取得する⽬的を明確にし、その⽬的達成に必要な範囲内で適法かつ公平な⼿段を⽤います。<br>
                        （2）個⼈情報の利⽤について<br>
                        個⼈情報を利⽤する際は、取得⽬的の範囲内で適切に⾏い、法令等で認められている場合を除き、該当する個⼈情報のご本⼈の同意なく第三者には開⽰いたしません。<br>
                        （3）法令・規範の遵守について<br>
                        個⼈情報を的確に保護するため、個⼈情報保護関連法令及びガイドライン等の規範を遵守いたします。<br>
                        （4）個⼈情報保護のための管理体制について<br>
                        業務上使⽤いたします個⼈情報の取扱いにつき、それぞれの業務実態に応じた個⼈情報保護のための管理体制を確⽴いたします。<br>
                        （5）開⽰・訂正等・利⽤停⽌等について<br>
                        お客様の個⼈情報につきまして、開⽰・訂正・利⽤停⽌等のお申出をされる場合、下記に定める連絡先にて対応させていただきます。<br>
                        <br>
                        【3】Googleアナリティクスの使⽤について<br>
                        当サイトでは、より良いサービスの提供、またユーザビリティの向上のため、Googleアナリティクスを使⽤し、当サイトの利⽤状況などのデータ収集及び解析を⾏っております。その際、「Cookie」を通じて、Googleがお客様のIPアドレスなどの情報を収集する場合がありますが、「Cookie」で収集される情報は個⼈を特定できるものではありません。<br>
                        収集されたデータはGoogleのプライバシーポリシーにおいて管理されます。<br>
                        なお、当サイトのご利⽤をもって、上述の⽅法・⽬的においてGoogle及び当サイトが⾏うデータ処理に関し、お客様にご承諾いただいたものとみなします。<br>
                        
                        Googleのプライバシーポリシー<br>
                        https://policies.google.com/privacy?hl=ja<br>
                        https://policies.google.com/technologies/partner-sites?hl=ja<br>
                        
                        【4】運営会社・お問い合わせ先<br>
                        運営会社：</p>
                    </div>
                    <p id="agree_check">
                    <?php if (isset($data['agreement_check']) && $data['agreement_check'] == 1): ?> 
                        <label><input type="checkbox" name="agreement_check" id="agreement_check" value="1" checked="checked">プライバシーポリシーへ同意して送信します。</label>
                    <?php else: ?> 
                        <label><input type="checkbox" name="agreement_check" id="agreement_check" value="1" >プライバシーポリシーへ同意して送信します。</label>
                    <?php endif; ?> 
                        
                    </p>
                </div> -->
                <div class="submit_area">
                    <!-- <input type="submit" class="form-btn" value="内容を確認のうえ送信"> -->
                    <!-- <a href="javascript:form1.submit();" class="form-btn"><span>内容を確認のうえ送信</span></a> -->
                    <a href="javascript:form1.submit();" class="form-btn"><span>送信</span></a>
                </div>
             </form>
        </div>
    </section> 

    <!-- footer -->
    <footer class="contact_footer">
        <div class="row footer-main">
            <div class="tab-full right footer-subscribe">

                <div class="footer-logo"></div>

                <div class="footer-navi pc-only">
                    <ul class="footer-nav-wrap__list">
                        <li class="current"><a class="" href="../index.html#works" title="works">WORKS</a></li>
                        <li><a class="" href="../index.html#profile" title="profile">PROFILE</a></li>
                        <!-- <li><a class="" href="../index.html#gear" title="gear">GEAR</a></li> -->
                        <li><a class="" href="../index.html#fishing" title="fishing">FISHING</a></li>
                    </ul>
                </div>

            </div>
        </div> 

        <div class="row footer-side">
            <div class="tab-full footer-side-item footer-subscribe">
                <div class="footer-side-contact pc-only">
                    <div class=""><span>MAIL</span>fujiwara@trickdart.tokyo</div>
                    <div class="_line"></div>
                    <!-- <div class="tel"><span>TEL</span></div> -->
                </div>
                <div class="contact-btn-area pc-only">
                    <div>
                        <a href="./" class="contact-btn bgleft"><span>CONTACT</span></a>
                    </div>
                </div>
                <div class="copyright">TRICK DART LLC.AllRights RESERVED.</div>
            </div>
        </div>

        <div class="row footer-bottom">
            <div class="col-twelve">
                <div class="go-top">
                    <a class="smoothscroll" title="Back to Top" href="#top"><i class="icon-arrow-up" aria-hidden="true"></i></a>
                </div>
            </div>
        </div> 

        <div class="footer-side-icon">
            <div class="back-img pc-only"><img src="../images/back-name-img-2.png"></div>
            <div class="back-icon" ><img src="../images/footer_chara_icon.png"></div>
        </div>

    </footer> <!-- end footer -->

    <div id="mouse-stalker" class="pc-only"><img src="../images/character-01.png"></div>

    <script src="../js/jquery-3.2.1.min.js"></script>
    <script src="../js/plugins.js"></script>
    <script src="../js/contact.js?202403121815"></script>
</body>
</html>