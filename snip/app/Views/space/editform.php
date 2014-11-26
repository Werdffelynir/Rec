<?php

/**
 * @var array $formData
 * @var array $fields
 * @var array $categories
 * @var array $subcategories
 * @var $userId
 */

$id = '';
?>



<form action="/space/save/<?=$id?>" method="post" class="save_form">

    <div class="content_left box grid_9 first">
        <div class="edit">
            <textarea name="content" id="" cols="30" rows="10"><?=$fields['content']?></textarea>
        </div>
    </div>

    <div class="content_right box grid_3">

            <a class="simple_btn space_btn" href="/space/profile">Back</a>
            <input type="submit" value="Save" class="simple_btn big_btn space_btn"/>
            <hr/>

        <div class="edit">

            <p>Snippet name</p>
            <input name="title" type="text" value="<?=$fields['title']?>" placeholder="Title"/>

            <p>Category</p>
            <select name="id_category" id="">
                <option value="new">New Category</option>
                <?php foreach ($categories as $category): ?>
                    <option <?= ($fields['id_category']==$category['id'])?'selected':'';?> value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                <?php endforeach; ?>
            </select>
            <input name="new_category" type="text" value="" placeholder="Category" style="display:none"/>

            <p>Sub category</p>
            <select name="id_sub_category" id="" style="display:none">
                <option value="new">New Sub Category</option>
            </select>
            <input name="new_sub_category" type="text" value="" placeholder="Sub Category"/>


            <p>Search tags</p>
            <input name="tags" type="text" value="<?= $fields['tags']?>" placeholder="Search tags" />

            <div class="save_btn">
                <input name="hide_id_sub_category" type="text" value="<?= $fields['id_sub_category']?>" hidden />
            </div>

        </div>
    </div>

</form>

<script type="application/javascript">

    var snippetId = '';
    var selectCat = $('select[name=id_category]');
    var inputCat = $('input[name=new_category]');
    var selectSubCat = $('select[name=id_sub_category]');
    var inputSubCat = $('input[name=new_sub_category]');
    var subCatData = $.parseJSON('<?=json_encode($subcategories)?>');

    if(snippetId != "" || snippetId != null){
        inputCat.css('display', 'block');
    }

    //for update
    /*
    var hideSubCat = parseInt($('input[name=hide_id_sub_category]').val());
    if(hideSubCat >= 1){
        var catId = parseInt('<?//=$snippet['id_category']?>');
        var currentOptionHtml = createSelectList(subCatData[catId], hideSubCat);
        inputCat.css('display', 'none');
        inputSubCat.css('display', 'none');
        selectSubCat.css('display', 'block');
        selectSubCat.html(currentOptionHtml);
    }*/

    selectCat.change(function () {
        var currentCat = $(this).val();
        if (currentCat == 'new') {
            inputCat.css('display', 'block');
            selectSubCat.css('display', 'none');
            inputSubCat.css('display', 'block');
        } else {
            inputCat.css('display', 'none');
            selectSubCat.css('display', 'block');
            if (selectSubCat.val() != 'new') {
                inputSubCat.css('display', 'none');
            }
            var currentCatData = subCatData[currentCat];
            console.log(currentCatData);
            var optionHtml = createSelectList(currentCatData);
            selectSubCat.html(optionHtml);
        }
    });

    selectSubCat.change(function () {
        var currentSubCat = $(this).val();
        if (currentSubCat == 'new') {
            inputSubCat.css('display', 'block');
        } else {
            inputSubCat.css('display', 'none');
        }
    });

    function createSelectList(currentCatData, idSubCat)
    {
        var select = '';
        var optionHtml = '<option value="new">New Sub Category</option>';
        if(currentCatData !== undefined){
            for (var ci = 0; ci < currentCatData.length; ci++) {
                if(idSubCat==parseInt(currentCatData[ci].id))
                    select = 'selected';
                optionHtml += '<option '+select+' value="' + currentCatData[ci].id + '">' + currentCatData[ci].title + '</option>';
                select = '';
            }
        }
        return optionHtml;
    }

</script>