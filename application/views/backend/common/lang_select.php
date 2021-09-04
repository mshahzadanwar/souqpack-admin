<div>
    <?php

    if($sub_ids =="")
    {
        $sub_ids = "listing";
    }

     $languages = $this->db->where("status",1)
    ->where("is_deleted",0)
    ->order_by("is_default","DESC")
    ->get('languages')->result_object();
    $active = set_value("clang");
    if($active=='') $active = $languages[0]->id;
    if($_GET["len"]!="") $active = $_GET["len"];

        foreach($languages as $language){
    ?>
        <a id="lang-tab-<?php echo $language->id.$sub_ids; ?>" href="javascript:langTab('<?php echo $language->id.$sub_ids; ?>','<?php echo $sub_ids; ?>')" style="padding:5px;float: left;" class="lang-tabs<?php echo $sub_ids; ?> <?php if($language->id==$active) echo "active_tab_lang"; ?>">
            <img style="width: 30px; height:16px;border:1px solid #ccc;" src="<?php echo $url."resources/";?>uploads/languages/<?php echo $language->image;?>">
            <span style="margin-left: 5px;" class="<?php if($language->id==$active) echo "active_span_lang"; ?>"><?php echo $language->title; ?></span>
        </a>
    <?php } ?>
    <input type="hidden" name="clang" value="<?php echo $active; ?>">
</div>