<?php

/**
 * @var array $formData
 * @var $userId
 */
?>

<form action="/edit/insert" method="post" class="save_form">

    <div class="content_left box grid-8 first">

        <div class="edit">
            <textarea name="content" id="" cols="30" rows="10"></textarea>
        </div>

    </div>

    <div class="content_right box grid-4">

        <div class="edit">

            <p>Snippet name</p>
            <input name="title" type="text" value="" placeholder="Title"/>

            <p>Category</p>
            <select name="id_category" id="">
                <?php foreach ($formData['categories'] as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                <?php endforeach; ?>
                <option value="new">New Category</option>
            </select>
            <input name="new_category" type="text" value="" placeholder="Category" style="display:none"/>

            <p>Sub category</p>
            <select name="id_sub_category" id="" style="display:none">
                <option value="new">New Sub Category</option>
            </select>
            <input name="new_sub_category" type="text" value="" placeholder="Sub Category"/>

            <p>Search tags</p>
            <input name="tags" type="text" value="" placeholder="Search tags" />

            <p>Type published</p>
            <select name="type" id="">
                <option value="public">public</option>
                <option value="private">private</option>
            </select>

            <div class="save_btn">
                <input name="id_user" type="text" value="<?=$userId?>" hidden />
                <input type="submit" value="Save" class="simple_btn"/>
            </div>
        </div>

    </div>

</form>

<script type="application/javascript">

    var selectCat = $('select[name=id_category]');
    var inputCat = $('input[name=new_category]');
    var selectSubCat = $('select[name=id_sub_category]');
    var inputSubCat = $('input[name=new_sub_category]');
    var subCatData = $.parseJSON('<?=$formData['subcategories']?>');

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
            var optionHtml = '<option value="new">New Sub Category</option>';
            for (var ci = 0; ci < currentCatData.length; ci++) {
                optionHtml += '<option value="' + currentCatData[ci].id + '">' + currentCatData[ci].title + '</option>';
            }
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

</script>