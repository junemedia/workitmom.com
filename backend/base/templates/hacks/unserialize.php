
	<form action="/oversight/hacks/unserialize" method="post">
	
		<label for="q">Serialized string:</label>
		<input type="text" name="q" value="<?= $input; ?>" />
		<br />
		
		<input type="submit" value="Unserialize!" />
		
	</form>