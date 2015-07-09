
	<form action="/oversight/hacks/serialize" method="post">
		
		<label for="q">Query string:</label>
		<input type="text" name="q" value="<?= $input; ?>" />
		<br />
		
		<label for="cast">Type cast:</label>
		<input type="text" name="cast" value="string" />
		<br />
		
		<input type="submit" value="Serialize!" />
		
	</form>