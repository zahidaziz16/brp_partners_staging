<?php $base = $tbData->fonts['body']['line-height']; ?>
<style scoped>
#<?php echo $widget->getDomId(); ?> ul.uiList {
  <?php if ($profile_name): ?>
  height: <?php echo ($base * 2 + 50) * $profiles_rows; ?>px;
  <?php else: ?>
  height: <?php echo ($base * 1.5 + 50) * $profiles_rows + 1; ?>px;
  <?php endif; ?>
}
</style>

<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>

<?php if (isset($twitter_data['errors'])): ?>
<?php foreach ($twitter_data['errors'] as $error): ?>
  <h3>twitter errors</h3>
  <p style="color: red"><?php echo $error; ?></p>
<?php endforeach; ?>
<?php else: ?>

<div class="tb_tw_follow_box tb_social_box<?php echo $like_box_classes; ?>">

  <div class="tb_tw_follow tb_social_button">
    <div>
      <a class="twitter-follow-button" href="https://twitter.com/<?php echo $twitter_data['screen_name'];?>" data-show-count="false" data-show-screen-name="false" data-lang="<?php echo $follow_button_lang; ?>">Follow @<?php echo $twitter_data['screen_name'];?></a>
      <script type="text/javascript">tbApp.onScriptLoaded(function() { !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.async=true;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs'); });</script>
    </div>
  </div>

  <?php if ($user_profile): ?>
  <div class="tb_profile">
    <a class="thumbnail" target="_blank" href="http://twitter.com/<?php echo $twitter_data['screen_name'];?>">
      <span class="image-holder" style="max-width: <?php echo $base * 3; ?>px;">
      <span style="padding-top: 100%;">
        <img
          <?php if (!$tbData->system['image_lazyload']): ?>
          src="<?php echo $twitter_data['profile_image_url']; ?>"
          <?php else: ?>
          src="<?php echo $tbData->theme_catalog_image_url; ?>pixel.gif"
          data-src="<?php echo $twitter_data['profile_image_url']; ?>"
          class="lazyload"
          <?php endif; ?>
          width="<?php echo $base * 3; ?>"
          height="<?php echo $base * 3; ?>"
          alt="<?php echo $twitter_data['name'];?>"
          style="margin-top: -100%;"
        />
      </span>
      </span>
    </a>
    <h3><a target="_blank" href="http://twitter.com/<?php echo $twitter_data['screen_name'];?>"><?php echo $twitter_data['name'];?> <small>@<?php echo $twitter_data['screen_name'];?></small></a></h3>
  </div>
  <?php endif; ?>

  <div class="plm tb_text_wrap">
    <p>
      <?php if ($profiles_type == 'followers'): ?>
      <?php echo $twitter_data['followers_count']; ?> people follow <a target="_blank" href="http://twitter.com/<?php echo $twitter_data['screen_name'];?>"><strong><?php echo $twitter_data['name'];?></strong></a>.
      <?php else: ?>
      <a target="_blank" href="http://twitter.com/<?php echo $twitter_data['screen_name'];?>"><strong><?php echo $twitter_data['name'];?></strong> is following <?php echo $twitter_data['friends_count']; ?> people.
      <?php endif; ?>
    </p>
  </div>

  <ul class="uiList">
    <?php foreach ($twitter_data['users'] as $user): ?>
    <li>
      <a class="link" target="_blank" href="http://twitter.com/<?php echo $user['screen_name'];?>" title="<?php echo $user['screen_name'];?>" rel="nofollow">
        <span class="image-holder" style="max-width: <?php echo $base * 2.5 + ($profile_border ? 10 : 0); ?>px;">
        <span style="padding-top: 100%;">
          <img
            <?php if (!$tbData->system['image_lazyload']): ?>
            src="<?php echo $user['profile_image_url']; ?>"
            <?php else: ?>
            src="<?php echo $tbData->theme_catalog_image_url; ?>pixel.gif"
            data-src="<?php echo $user['profile_image_url']; ?>"
            class="lazyload"
            <?php endif; ?>
            width="<?php echo $base * 2.5; ?>"
            height="<?php echo $base * 2.5; ?>"
            alt="<?php echo $user['screen_name'];?>"
            style="margin-top: -100%;" />
          />
        </span>
        </span>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>