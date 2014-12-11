<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="public/css/grid.css"/>
    <link rel="stylesheet" href="public/css/main.css"/>
<!--    <link rel="stylesheet" href="public/css/common.css"/>
    <link rel="stylesheet" href="public/css/elements.css"/>-->

    <script type="text/javascript" src="public/js/jquery.js"></script>
    <script type="text/javascript" src="public/js/main.js"></script>

</head>
<body>

<div class="page">

    <div class="header grid clear">

        <div class="header_title grid_3 first">
            <h2>Snippets Notes</h2>
        </div>

        <div class="header_search_form grid_6">
            <input name="search" type="text" value=""/><a href="#">Search</a>
        </div>

        <div class="header_menu grid_3">
            <a href="#">Admin Panel</a>
            <a href="#">MySpace</a>
            <a href="#">Logout</a>
        </div>

    </div>

    <div class="menu grid clear">

        <div class="menu_public">
            <ul>
                <li><a href="#">PHP</a></li>
                <li><a href="#">Javascript</a></li>
                <li><a href="#">HTML</a></li>
                <li><a href="#">SQL</a></li>
                <li><a href="#">ActionScript</a></li>
                <li><a href="#">FW</a></li>
                <li><a href="#">CMS</a></li>
                <li><a href="#">Server</a></li>
            </ul>
        </div>

    </div>

    <div class="content grid clear">
        <div class="content_left grid_9 first">

            <div class="edit">
                <div class="edit_line">
                    <input type="text" value="" placeholder="Title"/>
                </div>
                <div class="edit_line">
                    <textarea name="" id="" cols="30" rows="10"></textarea>
                </div>

            </div>

            <div class="snip">

                <div class="snip_header">
                    <div class="snip_top grid clear">
                        <div class="grid_8 first snip_title"> <a href="#">Snippet name JS Processing</a> </div>
                        <div class="grid_4 txt_r">
                            <span class="help_dis">+ 42</span>
                            <span class="round_btn">it`s help you?</span>
                        </div>
                    </div>
                    <div class="snip_bottom grid clear">
                        <div class="grid_7 first">&nbsp;</div>
                        <div class="grid_5 txt_r snip_tags"> <a href="#">tags</a>  <a href="#">ajax</a>  <a href="#">form php</a> </div>
                    </div>
                    <div class="snip_private grid clear">
                        <div class="grid_7 first">
                            <a href="#">Oleg Werdffelynir</a> / 01.11.2014
                        </div>
                        <div class="grid_5 txt_r ">
                            <span class="round_btn">to favorite</span>
                            <span class="round_btn">edit</span>
                            <span class="round_btn">delete</span>
                        </div>
                    </div>
                </div>

                <div class="snip_description grid clear">
                    <strong>Description: </strong> Aliquid aperiam beatae commodi consectetur consequatur doloremque eos error et facilis id illum labore
                    maxime natus, officiis placeat praesentium, quaerat quasi sunt totam velit. Cumque eius esse impedit
                    quod quos 685881?
                </div>

                <div class="snip_content grid clear">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. At autem beatae, dicta eius porro possimus
                        provident quasi reprehenderit soluta veniam. Ab autem dolorem eligendi minima nisi quae repellat
                        sapiente totam?</p>
                    <p>Aliquid aperiam beatae commodi consectetur consequatur doloremque eos error et facilis id illum labore
                        maxime natus, officiis placeat praesentium, quaerat quasi sunt totam velit. Cumque eius esse impedit
                        quod quos?</p>
                </div>
            </div>

            <?php for($i=0;$i<16;$i++): ?>
                <div class="lists grid clear">
                    <div class="lists_left">
                        <div class="lists_top grid clear">
                            <div class="grid_6 first lists_title"> <a href="#">Snippet name JS 2</a> </div>
                            <div class="grid_6 txt_r">  </div>
                        </div>

                        <div class="lists_bottom grid clear">
                            <div class="grid_6 first"> <a href="#">JavaScript</a> / <a href="#">Processing Array JS</a> </div>
                            <div class="grid_6 txt_r lists_tags"> <a href="#">tags</a>  <a href="#">ajax</a>  <a href="#">form php</a> </div>
                        </div>
                    </div>

                    <div class="lists_right grid_1">
                        <span class="lists_helps"><?= $i*rand(5,25) + rand(2,20)?></span>
                    </div>

                    <div class="lists_description grid clear">
                        <strong>Description: </strong> Aliquid aperiam beatae commodi consectetur consequatur doloremque eos error et facilis id illum labore
                        maxime natus, officiis placeat praesentium, quaerat quasi sunt totam velit. Cumque eius esse impedit
                        quod quos?
                    </div>
                </div>
            <?php endfor; ?>




        </div>

        <div class="content_right grid_3">


            <div class="edit grid clear">
                <div class="line_menu">
                    <a href="#"> Create new snippet </a>
                </div>
                <div class="edit_line">
                    <span class="round_btn">Category</span> JavaScript
                </div>
                <div class="edit_line">
                    <span class="round_btn">SubCategory</span> Processing Array JS
                </div>
                <div class="edit_line">
                    <input type="checkbox" /> Locked snippet
                </div>
                <div class="edit_line">
                    <input type="submit" value="Save snippet" />
                </div>
            </div>






            <div class="line_menu">
                <a href="#"> Oleg Werdffelynir </a>
            </div>
            <div class="user_box grid clear">

                <div class="user_box_l grid_7 first">
                    <ul>
                        <li><span class="user_box_labels">Reputation: </span><strong>+ 15</strong></span></li>
                        <li><span class="user_box_labels">Specialize: </span>Programmer </li>
                        <li><span class="user_box_labels">Status: </span>Administrator </li>
                        <li><span class="user_box_labels">Programming Langs: </span> <br>
                            <a href="#">PHP</a>&nbsp;
                            <a href="#">Javascript</a>&nbsp;
                            <a href="#">Actionscript</a>&nbsp;
                        </li>
                    </ul>
                </div>
                <div class="user_box_r grid_5">
                    <div class="user_ava">
                        <img src="public/upload/user_ava/ava_1.png" alt="">
                    </div>
                </div>
            </div>

            <div class="tree_box grid clear">
                <div class="tree_menu">
                    <a href="#">Public</a>
                    <a href="#">Private</a>
                    <a href="#">Favorite</a>
                </div>
                <div class="tree_content">
                    <ul>
                        <li><a class="collapsed collapsible" href="#0"> CSS</a>
                            <ul style="/*display: none;*/">
                                <li><a href="#">Box-shadow</a></li>
                                <li><a href="#">Btn Hover Active</a></li>
                                <li><a href="#">IE FIX</a></li>
                                <li><a href="#">List of useful tagse</a></li>
                                <li><a href="#">Lock copy in page</a></li>
                                <li><a href="#">Menus menus</a></li>
                            </ul>
                        </li>
                        <li><a class="collapsed collapsible" href="#0"> PHP</a>
                            <ul style="/*display: none;*/">
                                <li><a href="#">Array Sort functions</a></li>
                                <li><a href="#">Directories Processing</a></li>
                                <li><a href="#">Files Processin</a></li>
                                <li><a href="#">HEREDOC and NOWDOC</a></li>
                                <li><a href="#">Path and Url (part 2)</a></li>
                                <li><a href="#">Pattern Observer</a></li>
                                <li><a href="#">Pattern Prototype</a></li>
                                <li><a href="#">Read big files (parts)</a></li>
                                <li><a href="#">Regular Expressions DOCX</a></li>
                                <li><a href="#">Validations with PHP</a></li>
                                <li><a href="#">Wrap text (limit)</a></li>
                            </ul>
                        </li>
                        <li><a class="collapsed collapsible" href="#0"> SQL</a>
                            <ul style="/*display: none;*/">
                                <li><a href="#">CREATE TABLE</a></li>
                                <li><a href="#">Directories Processing</a></li>
                                <li><a href="#">Files Processin</a></li>
                                <li><a href="#">HEREDOC and NOWDOC</a></li>
                                <li><a href="#">Path and Url (part 2)</a></li>
                                <li><a href="#">Pattern Observer</a></li>
                                <li><a href="#">Pattern Prototype</a></li>
                                <li><a href="#">Read big files (parts)</a></li>
                                <li><a href="#">Regular Expressions DOCX</a></li>
                                <li><a href="#">Validations with PHP</a></li>
                                <li><a href="#">Directories Processing</a></li>
                                <li><a href="#">Files Processin</a></li>
                                <li><a href="#">HEREDOC and NOWDOC</a></li>
                                <li><a href="#">Path and Url (part 2)</a></li>
                                <li><a href="#">Pattern Observer</a></li>
                                <li><a href="#">Pattern Prototype</a></li>
                                <li><a href="#">Read big files (parts)</a></li>
                                <li><a href="#">Regular Expressions DOCX</a></li>
                                <li><a href="#">Validations with PHP</a></li>
                            </ul>
                        </li>
                        <li><a class="collapsed collapsible" href="#0"> Последние просмотриные</a>
                            <ul style="/*display: none;*/">
                                <li><a href="#">CREATE TABLE</a></li>
                                <li><a href="#">Directories Processing</a></li>
                                <li><a href="#">Files Processin</a></li>
                                <li><a href="#">HEREDOC and NOWDOC</a></li>
                                <li><a href="#">Path and Url (part 2)</a></li>
                                <li><a href="#">Pattern Observer</a></li>
                                <li><a href="#">Pattern Prototype</a></li>
                                <li><a href="#">Read big files (parts)</a></li>
                                <li><a href="#">Regular Expressions DOCX</a></li>
                                <li><a href="#">Validations with PHP</a></li>
                                <li><a href="#">Directories Processing</a></li>
                                <li><a href="#">Files Processin</a></li>
                                <li><a href="#">HEREDOC and NOWDOC</a></li>
                                <li><a href="#">Path and Url (part 2)</a></li>
                                <li><a href="#">Pattern Observer</a></li>
                                <li><a href="#">Pattern Prototype</a></li>
                                <li><a href="#">Read big files (parts)</a></li>
                                <li><a href="#">Regular Expressions DOCX</a></li>
                                <li><a href="#">Validations with PHP</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="inside_box grid clear">
                <p>Drepellat rerum sed sunt, ullam ut veritatis voluptas.</p>
            </div>



        </div>
    </div>

    <div class="footer grid clear">
        <div class="footer_copy">
            Copyright © - 2014 SunLight, Inc. OL Werdffelynir. All rights reserved. <br>
            Was compiled per: <?php echo round(microtime(true) - START_TIMER, 4); ?> sec.
        </div>
    </div>

</div>

</body>
</html>