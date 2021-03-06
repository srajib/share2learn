if (!$defined(Napi)) var Napi;
var Napi = new Class;


/**
 * Form behavior
 */
Napi.Grid = new Class({

    getOptions: function(){
        return {
        	
        	id: {
        		form:		'form',
        		checkAll: {
        			id: 	'input.id',
        			toggle: 'input.toggle',
        			count: 	'[name="boxchecked"]'	
        		}	
        	}
        };
    },

    initialize: function(options){
        this.setOptions(this.getOptions(), options);
        if (this.options.initialize) this.options.initialize.call(this);
    },
	
	/**
	* Toggles the check state of a group of boxes
	*/
	checkAll: function(){
		var checked = $$(this.options.id.checkAll.toggle)[0].getProperty('checked');
		var els = $$(this.options.id.form + ' ' + this.options.id.checkAll.id).filter(function(el){
			return $(el).getProperty('checked') !== checked;
		});
		
		
		els.each(function(el){
			el.setProperty('checked', checked).fireEvent('change');
		});
		$(els[0].form).fireEvent('change').validate();		
		this.count();
	},
	
	count: function(){
		var els = $$(this.options.id.form + ' ' + this.options.id.checkAll.id);
		var count = els.filter(function(el){
			return $(el).getProperty('checked') !== false ;
		}).length;
		$$(this.options.id.checkAll.toggle)[0].setProperty('checked', els.length === count);
	}
});

/*
Class: Element
	Custom class to allow all of its methods to be used with any DOM element via the dollar function <$>.
*/

Element.extend({

	checkall: function(options){
		(new Napi.Grid(options)).checkAll();
		
		return this;
	},
	
	count: function(options){
		(new Napi.Grid(options)).count();
		//grid.count(true);
		
		return this;
	},
	
	getSiblings: function() {
	    return this.getParent().getChildren().remove(this);
	},
	
	selectables: function(){
		this.getElements('tr').each(function(tr){
			var checkbox = tr.getElement('input[type=checkbox]');
			if(!checkbox) return;
			checkbox.addEvent('change', function(tr){
				this.getProperty('checked') ? tr.addClass('selected') : tr.removeClass('selected');
				var selected = tr.hasClass('selected') + tr.getSiblings('.selected').length, parent = tr.getParent();
				if(selected > 1) {
					parent.addClass('selected-multiple').removeClass('selected-single')
				} else {
					parent.removeClass('selected-multiple').addClass('selected-single');
				}
			}.pass(tr, checkbox)).fireEvent('change');
			tr.addEvent('click', function(event){
				if(event.target.hasClass('toggle-state') || event.target.getProperty('type') == 'checkbox') return;
				var checkbox = this.getElement('input[type=checkbox]'), checked = checkbox.getProperty('checked');
				if(checked) {
					this.removeClass('selected');
					checkbox.setProperty('checked', false);
				} else {
					this.addClass('selected');
					checkbox.setProperty('checked', true);
				}
				checkbox.fireEvent('change').count();
			});
		});

		return this;
	}
});


Napi.Grid.implement(new Events, new Options);