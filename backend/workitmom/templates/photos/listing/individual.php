
		<tr class="<?= $row; ?> <?= $priority.'_priority'; ?>">

			<td><?= $photo['id']; ?></td>

			<td class="textfield" style="width:540px;">
				<!--<span class="fr"><a class="small link" href="<?= SITEURL; ?>/photos/details/<?= $photo['id']; ?>/">(view)</a></span>-->
				<?= Text::trim($photo['title'], 100); ?>
			</td>

			<td><?= $photo['time']; ?></td>

			<td><?= $photo['comment_count']; ?></td>

			<td><span class="fr"><a class="small link" href="<?= SITEURL; ?>/photos/delete/?photo=<?= $photo['id']; ?>">Delete</a></span></td>
			
			<td><span class="fr"><a class="small link" href="<?= SITEURL; ?>/photos/details/<?= $photo['id']; ?>/">Edit</a></span></td>
			
			<td><?= $photo['status']? 'live':'pending'; ?></td>

		</tr>