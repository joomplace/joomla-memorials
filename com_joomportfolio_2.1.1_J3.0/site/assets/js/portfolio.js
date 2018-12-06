window.addEvent('load', function() {
	var main = $$('#gallery-main a');
	var options = {
		'origin': 'img',
		'shadow': 'onOpenEnd',
		'resizeFactor': 0.8,
		'cutOut': false,
		'opacityResize': 0.4,
		'dragging': false,
		'centered': true
	}
	
	var box = new CeraBox();
	box.addItems('#gallery-main a.image', {
		animation: 'ease',
		loaderAtItem: true
	});
	
	$$('div.gallery-menu ul li a').addEvent('click', function(e) {
		new Event(e).stop();
		var l = new Element('div', {'class': 'loading'}).inject($('gallery-main'));
		var a = $('gallery-main').getChildren('a');
		var img = $('gallery-main').getElement('img');
		var w = img.getProperty('width');
		var h = img.getProperty('height');
		var item_id = $('global_item_id').value;
		var previews = $('preview_width').value+'x'+$('preview_height').value;
		
		for(i=0; i<a.length;i++)
		{
			if (a[i].getProperty('rel') == this.getProperty('rel'))
			{
				image = a[i].getElement('img');
				a[i].setProperty('rel', a[0].getProperty('rel'));
				a[i].setProperty('href', base + 'images/joomportfolio/'+item_id+'/original/'+a[0].getProperty('rel'));
				a[i].setProperty('title', a[0].getProperty('title'));
				a[i].setProperty('rev', a[0].getProperty('rev'));
				
				//img.setProperty('src', 'index.php?option=com_joomportfolio&task=item.image&i='+a[0].getProperty('rel')+'&w='+w+'&h='+h)
				img.setProperty('src', base + 'images/joomportfolio/'+item_id+'/'+previews+'/th_'+a[0].getProperty('rel')).addEvent('load', function() {
					$('gallery-main').getChildren('div.loading').destroy();
				});
				break;
			}
		}
		
		a[0].setProperty('rel', this.getProperty('rel'));
		a[0].setProperty('href', base + 'images/joomportfolio/'+item_id+'/original/'+this.getProperty('rel'));
		a[0].setProperty('title', this.getProperty('title'));
		a[0].setProperty('rev', this.getProperty('rev'));
		
		//img.setProperty('src', 'index.php?option=com_joomportfolio&task=item.image&i='+this.getProperty('rel')+'&w='+w+'&h='+h)
		img.setProperty('src', base + 'images/joomportfolio/'+item_id+'/'+previews+'/th_'+this.getProperty('rel')).addEvent('load', function() {
			$('gallery-main').getChildren('div.loading').destroy();
		});
		main.removeEvents();
		var box = new CeraBox();
			box.addItems('#gallery-main a.image', {
				animation: 'ease',
				loaderAtItem: true
			});
	});
	
	//var myFx = new Fx.Scroll(window);
   /*var myFx = new Fx.Scroll(window);
	$('scrollTop').addEventListener('click', function(e){ 
		new Event(e).stop();
		myFx.toTop(); 
	});*/
});
