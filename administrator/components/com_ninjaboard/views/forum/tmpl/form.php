<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<? @ninja('behavior.tooltip', array('selector' => '[title].hasTip')) ?>
<? @ninja('behavior.livetitle', array('title' => @$forum->title)) ?>

<script type="text/javascript">
	window.addEvent('domready', function(){
		var aliasvalidator = function(){
			this.value = this.value.slugify();
		};
		var titlevalidator = function(){
			if(!$('alias').defaultValue) $('alias').value = this.value.slugify();
		};
		
		$('title').addEvents({'keyup': titlevalidator, 'keydown': titlevalidator, 'blur': titlevalidator});
		$('alias').addEvents({'blur': aliasvalidator});
	});
</script>

<form action="<?= @route('id='.@$forum->id) ?>" method="post" id="<?= @id() ?>" class="validator-inline">
	<div class="col width-50">
		<fieldset class="adminform ninja-form">
			<legend><?= @text('Details') ?></legend>
			<div class="element">	
				<label for="title" class="key"><?= @text('Title') ?></label>
				<input type="text" name="title" id="title" class="inputbox required value" data-warn-msg="<?= @text('Forums require a title!') ?>" size="50" value="<?= @escape($forum->title) ?>" maxlength="150" onkeyup="" autocomplete="off" />
			</div>
			<div class="element">
				<label for="alias" class="key"><?= @text('Alias') ?></label>
				<input type="text" name="alias" id="alias" class="inputbox required validate-no-space value" size="50" value="<?= @$forum->alias ?>" maxlength="150" />
			</div>
			<div class="element">
				<label for="description" class="key"><?= @text('Description') ?></label>
				<textarea name="description" id="description" rows="5" cols="50" class="inputbox value"><?= @$forum->description ?></textarea>
			</div>
			<div class="element">
				<label for="header" class="key hasTip" title="<?= @text("Forum header is used on the forum detail page. If left empty, the forum description is used instead.") ?>"><?= @text('Forum header') ?></label>
				<textarea name="params[forum][header]" id="params-forum-header" rows="5" cols="50" class="inputbox value" placeholder="<?= @text('Leave empty for using the description as the header.') ?>"><?= \@@$forum->params['forum']['header'] ?></textarea>
			</div>
			<div class="element">
				<label class="key"><?= @text('State') ?></label>
				<?= @ninja('select.statelist', array('selected' => @$forum->enabled)) ?>
				<?/* @TODO properly implement locked forums */?>
				<?/*= @ninja('select.statelist', array('name' => 'locked', 'id' => 'locked', 'attribs' => array('class' => 'validate-reqchk-byname label:\'locked\' value'), 'selected' => @$forum->locked, 'yes' => 'Lock', 'no' => 'Unlock', 'img_x' => '/16/unlock.png', 'img_y' => '/16/lock.png')) */?>
			</div>
		</fieldset>
		<fieldset class="adminform ninja-form">
			<legend><?= @text('Forum Hierarchy') ?></legend>
			<div class="element">
				<label class="key" for="path"><?= @text('Parent Forum') ?></label>
					<? $size = max(max(@total+1, 17)-2, 1) ?>
					<?= JHTML::_('select.genericlist', KFactory::get($this->getView())->forums, 'path', array('class' => 'value required', 'size' => $size), 'path', 'title', @$forum->path ? @$forum->path : ' ') ?>
			</div>
		</fieldset>

		<link rel="stylesheet" href="/admin.forum.css" />
		<script type="text/javascript" src="/elements/toggle/touch.js"></script>
		<script type="text/javascript" src="/switch.js"></script>

		<script type="text/javascript">
			var Permissions = Switch.extend({
				focus: true,
				initialize: function(target, options){


					this.targets = $$('#'+target.get('id') + '-switch', '#'+target.get('id') + '-wrap');
					this.clones  = this.targets.clone();
					this.name    = target.get('id');

					this.parent($(target.get('id') + '-switch'), options);

					target.set('tween', this.fx.options);

					this.table = target.getElement('table');
					$(target.get('id') + '-switch').getParent().getNext().set('html', '<span class="on">'+this.options.text.on+'</span><span class="off">'+this.options.text.off+'</span>');

					this.targets.inject(new Element('th', {'class': 'switch'}).inject(this.table.getElement('thead tr')), 'inside');

					this.table.getElements('tbody tr').each(function(tr, i){
						
						return tr.getLast('td').set('colspan', 2);

						var td = new Element('td', {id: [this.name, 'tr', i].join('-'), 'class': 'switch'}).inject(tr, 'bottom');
						
						new Switch(
							this.clones[1].clone().inject(td, 'inside').set('id', [this.name, i, 'wrapper'].join('-')).getElement('.toggle').set('id', [this.name, i, 'switch'].join('-'))
						);

						$([this.name, i, 'switch'].join('-')).getParent().getNext().set('html', '<span class="on">'+this.options.text.on+'</span><span class="off">'+this.options.text.off+'</span>');
					}.bind(this));
					
					this.addEvent('onChange', this.onChange.bind(this)).fireEvent('onChange', this.container.getPrevious().value);

					return this;
				},
				onChange: function(state) {
					var value = (state) ? 1 : 0;
					this.container.getPrevious().value = value;
					if(state == 1){
						this.container.addClass('enabled').removeClass('disabled');
						this.table.removeClass('disabled').fade(1).getElements('input').set('disabled', false);
					} else {
						this.container.addClass('disabled').removeClass('enabled');
						this.table.addClass('disabled').fade(0.6).getElements('input').set('disabled', 'disabled');
					}
				}
			});
			
			Element.Properties.switcher = {
			
				set: function(options){
					var switcher = this.retrieve('switcher');
					return this.eliminate('switcher').store('switcher:options', options);
				},
			
				get: function(options){
					if (options || !this.retrieve('switcher')){
						if (this.retrieve('switcher')) this.retrieve('switcher').destroy();
						if (options || !this.retrieve('switcher:options')) this.set('switcher', options);
						new Permissions(this, this.retrieve('switcher:options'));
					}
					return this.retrieve('switcher');
				}
			
			};
			
			Element.implement({
			
				switcher: function(options){
					this.get('switcher', options);
					return this;
				}
			
			});
		</script>
			

		<style type="text/css">
			.ninja-form {
				position: relative;
			}
			.wrapper.switch {
				position: absolute;
				right: 8px;
				top: 8px;
				z-index: 1;
			}
			.permissionlist .wrapper.switch {
				position: relative;
				display: inline-block;
				top: -3px;
				right: 10px;
			}
			th.switch {
				text-align: right;
				font-weight: normal;
			}
			.permissionlist td .wrapper.switch {
				top: 1px;
			}
			.ninja-form .permissionlist td.switch {
				padding-left: 10px;
			}
			.permissions-level input[disabled] + label {
				color: gray;
			}
		</style>

		<fieldset class="adminform ninja-form">
			<legend><?= @text('Permissions') ?></legend>
			<?= @ninja('accordions.startpane', array('id' => @id('permissions'), 'options' => array('opacity' => true, 'scroll' => true))) ?>
			<? foreach (@$permissions as $permission) : ?>
				<? @js("\n\twindow.addEvent('domready', function(){ $('" . $permission['id'] . "').switcher(".json_encode(array('text' => array('on' => @text('on'), 'off' => @text('off'))))."); });\n") ?>

				<?= @ninja('accordions.startpanel', array('title' => $permission['title'], 'translate' => false)) ?>

					<? $permissions = \@@$forum->params['permissions'][$permission['group']]['enabled'] == 1 ? 1 : 0 ?>
					<? $checked = $permissions ? ' checked="checked"' : null ?>
					<div class="wrapper switch" id="<?= $permission['id'] ?>-wrap">
						<input name="params[permissions][<?= $permission['group'] ?>][enabled]" value="<?= $permissions ?>" type="hidden" />
						<input type="checkbox" class="toggle inclToggle" id="<?= $permission['id'] ?>-switch"<?= $checked ?> />
					</div>

					<?= $permission['form']->render() ?>
				<?= @ninja('accordions.endpanel') ?>
			<? endforeach ?>
			<?= @ninja('accordions.endpane') ?>
			<input type="hidden" name="editpermissions" value="true" />
		</fieldset>
	</div>
	<div class="col width-50 validation-advice-align-left">
		<?= @$form->render() ?>
	</div>
</form>