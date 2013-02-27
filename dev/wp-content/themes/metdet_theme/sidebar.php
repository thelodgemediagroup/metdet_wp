<div class="sidebar">

    <div class="content-title">

        <h1>Current Issue</h1>

    </div>

    <?php if (function_exists('display_the_current_issue')) { display_the_current_issue(); } ?>

    <div id="sidebar-headline" class="content-title">

    	<h1>Past Issues</h1>

    </div>

    <div id="sidebar-issue-years">

    	<div class="headline-row">

            <a href="<?php echo esc_url(get_permalink(get_page_by_title('Yearly'))).'&amp;issue_year=2009'; ?>"><span id="year-09">09</span></a>
            <a href="<?php echo esc_url(get_permalink(get_page_by_title('Yearly'))).'&amp;issue_year=2010'; ?>"><span id="year-10">10</span></a>
            <a href="<?php echo esc_url(get_permalink(get_page_by_title('Yearly'))).'&amp;issue_year=2011'; ?>"><span id="year-11">11</span></a>
            <a href="<?php echo esc_url(get_permalink(get_page_by_title('Yearly'))).'&amp;issue_year=2012'; ?>"><span id="year-12">12</span></a>

        </div>

    </div>

    <div id="sidebar-topics">

        <a href=""><img src="<?php echo get_bloginfo('template_url') . '/images/topic_bar_food.png' ?>" alt="Food" title="Food" width="316px" height="86px"></a>
        <a href=""><img src="<?php echo get_bloginfo('template_url') . '/images/topic_bar_style.png' ?>" alt="Style &amp; Beauty" title="Style &amp; Beauty" width="316px" height="35px"></a>
        <a href=""><img src="<?php echo get_bloginfo('template_url') . '/images/topic_bar_fashion.png' ?>" alt="Fashion" title="Fasion" width="316px" height="54"></a>
        <a href=""><img src="<?php echo get_bloginfo('template_url') . '/images/topic_bar_art.png' ?>" alt="Art" title="Art" width="316px" height="84px"></a>
        <a href=""><img src="<?php echo get_bloginfo('template_url') . '/images/topic_bar_exhib.png' ?>" alt="Exhibition" title="Exhibition" width="316px" height="43px"></a>
        <a href=""><img src="<?php echo get_bloginfo('template_url') . '/images/topic_bar_nightlife.png' ?>" alt="Nightlife" title="Nightlife" width="316px" height="67px"></a>

    </div>

</div>