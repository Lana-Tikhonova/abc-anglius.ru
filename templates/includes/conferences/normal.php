  <div class="cols2" style="background:#fff">
    <div class="container">
<!--      <div class="dropdown">-->
<!--        <button class="dropdown-btn">Меню</button>-->
<!--        <div class="dropdown-content">-->
<!--          --><?php //echo html_query('menu/left', "
//            SELECT module,name,url
//            FROM pages
//            WHERE level=1 AND display=1 AND menu2 = 1
//            ORDER BY left_key
//        ", '', 60 * 60, 'json');
//          ?>
<!--        </div>-->
<!--      </div>-->
      <div>
        <?php if ($page['current']) echo $page['current']; ?>
        <div class="list conferences">
          <?php echo html_query('conferences/list1', "
              SELECT id,name,img,shortdesc,url
              FROM conferences 
              WHERE display=1
              ORDER BY rank DESC,id ASC
          ", '', 60 * 60, 'json');
          ?>
        </div>
      </div>
    </div>
  </div>

  <?php if ($page['text'] != '') { ?>
    <div class='diploms'>
      <div class='container'>
        <a id='participationterms'></a>
        <?= $page['text'] ?>
      </div>
    </div>
  <?php } ?>
