<?php header("charset=utf-8"); ?>
<pages>
<link>
        <title>MStory.Ga</title>
        <url><?php echo HOME ?></url>
</link>
    <?php if($folders) foreach ($folders as $folder) : ?>
<link>
            <title><?php echo $folder['name'] ?></title>
            <url><?php echo $folder['full_url'] ?></url>
</link>
    <?php endforeach ?>
    <?php if($posts) foreach ($posts as $post) : ?>
<link>
            <title><?php echo $post['name'] ?></title>
            <url><?php echo $post['full_url'] ?></url>
</link>
    <?php endforeach ?>
</pages>