<?php '<?xml version="1.0" encoding="UTF-8" ?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo settings()->frontend_url;?></loc> 
        <priority><?php echo $settings->base_pr;?></priority>
        <changefreq><?php echo $settings->base_freq;?></changefreq>
    </url>
    <?php foreach($categories as $url) { ?>
    <url>
        <loc><?php  echo settings()->frontend_url."category/".$url->slug."-".$url->id; ?></loc>
        <priority><?php echo $settings->cat_pr;?></priority>
        <changefreq><?php echo $settings->cat_freq;?></changefreq>
    </url>
    <?php } ?>

    <?php foreach($products as $url) { ?>
    <url>
        <loc><?php  echo settings()->frontend_url."product/".$url->slug."-".$url->id; ?></loc>
        <priority><?php echo $settings->pro_pr;?></priority>
        <changefreq><?php echo $settings->pro_freq;?></changefreq>
    </url>
    <?php } ?>


    <?php foreach($pages as $url) { ?>
    <url>
        <loc><?php  echo settings()->frontend_url."page/".$url->slug; ?></loc>
        <priority><?php echo $settings->page_pr;?></priority>
        <changefreq><?php echo $settings->page_freq;?></changefreq>
    </url>
    <?php } ?>

    <url>
        <loc><?php  echo settings()->frontend_url;?>cart</loc> 
        <priority><?php echo $settings->cart_pr;?></priority>
        <changefreq><?php echo $settings->cart_freq;?></changefreq>
    </url>
    <url>
        <loc><?php  echo settings()->frontend_url;?>wishlist</loc> 
        <priority><?php echo $settings->wish_pr;?></priority>
        <changefreq><?php echo $settings->wish_freq;?></changefreq>
    </url>

    <url>
        <loc><?php  echo settings()->frontend_url;?>contact-us</loc> 
        <priority><?php echo $settings->page_pr;?></priority>
        <changefreq><?php echo $settings->page_freq;?></changefreq>
    </url>

    <url>
        <loc><?php  echo settings()->frontend_url;?>login</loc> 
        <priority><?php echo $settings->log_pr;?></priority>
        <changefreq><?php echo $settings->log_freq;?></changefreq>
    </url>

    <url>
        <loc><?php  echo settings()->frontend_url;?>signup</loc> 
        <priority><?php echo $settings->sign_pr;?></priority>
        <changefreq><?php echo $settings->sign_freq;?></changefreq>
    </url>


</urlset>