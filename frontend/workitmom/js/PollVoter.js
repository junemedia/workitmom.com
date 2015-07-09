
var PollVoter = new Class({

	/**
	 *	This is the ID of the poll.
	 *	Used when sending Ajax request.
	 */
	_id: null,	
	
	/**
	 *	The <form> element.
	 *	Used for referencing DOM elements
	 */
	_form: null,

	/**
	 *	Takes the poll ID.
	 *	Automatically sends the answer of a vote whenever this <form> is submitted.
	 */
	initialize: function(pollid, formElement){
		this._id = pollid.toInt();
		this._form = $$(formElement)[0];
		this._form.addEvent('submit', function(event){
			
			// Get the chosen <input name="answer" /> option
			var chosen = this._form.getElements('input[name=answer]').filter(function(input){
				return input.get('checked');
			});
			// If no chosen answer, don't do anything.
			if (chosen.length > 0){
				this.vote.bind(this).run(chosen.get('value'));
			}
			
			return false;
			
		}.bindWithEvent(this));
	},
	
	/**
	 *	Adds an event handler to the DOM element, depending on the specified action.
	 */
	addTrigger: function(cssSelector, action){
		var trigger = this._form.getElement(cssSelector);
		switch(action){
			case 'view':
				trigger.addEvent('click', this.view.bind(this));
				break;
			default:
				break;
		}
		return this;
	},
	
	/**
	 *	Vote on the poll, by selecting an answer ID.
	 *
	 *	@args (int) answer: the answer ID for the poll.
	 */
	vote: function(answer){
		
		// Make ajax request
		new Request.HTML({
			url: SITEURL + "/destress/polls/",
			onComplete: function(tree, elements, html, js){
				// When it completes, show the new results.
				this.view.bind(this).run();
			}.bind(this)
		}).post({
			format: 'raw',
			subtask: 'vote',
			poll_id: this._id,
			answer_id: answer.toInt()
		});
		
		// Exit
		return false;
		
	},
	
	/**
	 *	Display the current results of the poll.
	 */
	view: function(){
		
		// Make ajax request
		new Request.HTML({
			url: SITEURL + "/destress/polls/",
			onComplete: function(tree, elements, html, js){
				this.displayResults(html, js);
			}.bind(this)
		}).post({
			format: 'raw',
			subtask: 'results',
			poll_id: this._id
		});
		
		// Exit
		return false;
		
	},
	
	/**
	 *	Replaces the current html content with the Ajax response html.
	 */
	displayResults: function(html, js){
		
		// Replace the static text
		this._form.set('html', html);
		
		// Slide div so that it is fully viewable
		this._form.get('slide').slideIn().chain(function(){
			
			// Then scroll viewport to the element
			new Fx.Scroll(window).toElement(this._form);
			
			// Exit
			return false;
			
		}.bind(this));
		
		// Iterate through each of the bars and slide right
		this._form.getElements('.pollbar').each(function(bar){
			var width = bar.setStyle('visibility', 'visible').getStyle('width');
			new Fx.Morph(bar, {unit: '%', duration: 4000, transition: 'elastic:out'}).set({'width': 10}).start({'width': width});
		});
		
		// Exit
		return false;
		
	}

});