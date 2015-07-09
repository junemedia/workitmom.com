
	function onABCommComplete(newContacts) {
		
		alert('hi');
		
		/* List of all contacts */
		var contacts = $H();
		
		/* Get existing contacts */
		var contactList = $('contactlist');		
		var oldContacts = contactList.getChildren('tr.contact');
		oldContacts.each(function(contact) {
			var email = contact.getElement('input.email').get('value');
			var name = contact.getElement('input.name').get('value');
			if (email != '') {
				contacts.include(email, name);
			}
		});
		
		/* Add new contacts */	
	    $A(newContacts).each(function(contact) {
	    	contacts.include(contact[0], contact[1]);
	    });
	    
	    /* Build list of contacts */
	    var html = '';
	    contacts.each(function(name, email) {
	    	html += '<tr class="contact">' +
	    		'<td><input type="text" name="contact_name[]" value="'+name+'" class="textinput name" /></td>' +
	    		'<td><input type="text" name="contact_email[]" value="'+email+'" class="textinput email" /></td>' +
	    		'</tr>';
	    });
	    
	     /* Extra contact fields */
	    var i = 0;
	    for (i = 0; i < 5; i++) {
	    	html += '<tr class="contact">' +
	    		'<td><input type="text" name="contact_name[]" value="" class="textinput name" /></td>' +
	    		'<td><input type="text" name="contact_email[]" value="" class="textinput email" /></td>' +
	    		'</tr>';
	    }

		/* Inject */
	    contactList.getElement('tbody').set('html', html);
	}