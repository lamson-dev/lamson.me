<!DOCTYPE html>
<html lang="en">
<head>
    <title>Son Nguyen's Personal WebPage</title>

    <?php include "includes/header.php" ?>

</head>

<body class="post_layout">

<div id="fb-root"></div>
<script>
    // Load the SDK Asynchronously
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>


<div id="royal-wrapper" style="width: 960px;">
    <div id="wrapper" style="width: 930px;">

        <div id="banner"></div>

        <div id="header">
            <div id="site-title"><a href="#" title="Home Page">Son
                    Nguyen</a></div>


            <div class="clear"></div>
        </div>


        <div id="main">

            <div id="content" class="clearfix">

                <div id="lcc" style="text-align: center; margin-bottom: 35px;">
                    <h1>LCC 3403 - Portfolio</h1>

                    <div id="menu" style="float: none; text-align: center;">
                        <ul>
                            <li><a href="journals" title="Projects">Journal
                                    Posts</a></li>
                            <li>&middot;</li>
                            <li><a href="infographic" title="About">Infographic</a>
                            </li>
                            <li>&middot;</li>
                            <li><a href="instructions" title="About">Instructions</a>
                            </li>
                            <li>&middot;</li>
                            <li><a href="descriptions" title="About">Descriptions</a>
                            </li>
                            <li>&middot;</li>
                            <li><a href="gHarmony" title="About">Team Project</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <img id="my-picture" src="images/me-mustache.jpg" alt="picture of me" title="my picture"/>

                <div id="my-info"><h1>Hi!</h1>

                    <p>My name is Son Nguyen, and welcome to my website! </p>

                    <p>I'm a computer science undergraduate at Georgia Tech with an emphasis in devices and
                        information-internetworks. I currently co-op at Apptio - a enterprise software company, where
                        I am
                        a Front-End Software Engineer. I'm interested in understanding the long-term relation between
                        user
                        experience and product appreciation. My career goal is to open my own start-up in the
                        future.</p>

                    <p>This page is to show off what I have done in
                        LCC 3403 - Technical Communication at Georgia Tech. It includes 5 artifacts where you can
                        browse through using the navigation above. Enjoy!</p>

                </div>


            </div>

        </div>
        <!-- end main div -->


        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="http://code.jquery.com/jquery-latest.js"></script>

        <div id="footer">
            <?php include "includes/footer.php" ?>
        </div>

    </div>
    <!-- end wrapper -->
</div>
<!-- end royal-wrapper -->

</body>