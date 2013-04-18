//// For more information: https://gist.github.com/Fab1en/4586865

// for debug : trace every event
/*
var originalTrigger = wp.media.view.MediaFrame.Post.prototype.trigger;
wp.media.view.MediaFrame.Post.prototype.trigger = function(){
    console.log('Event Triggered:', arguments);
    originalTrigger.apply(this, Array.prototype.slice.call(arguments));
}
*/
 
 
// custom state : this controller contains your application logic
wp.media.controller.SavedGalleries = wp.media.controller.State.extend({
 
    initialize: function(){
        // this model contains all the relevant data needed for the application
        this.props = new Backbone.Model({ selected_gallery: '' });
        this.props.on( 'change:selected_gallery', this.refresh, this );
    },
    
    // called each time the model changes
    refresh: function() {
        // update the toolbar
    	this.frame.toolbar.get().refresh();
	},
	
	// called when the toolbar button is clicked
	customAction: function() {
      var gallery = '[gallery ids="'+ this.props.get('selected_gallery').join(',') +'"]';
      wp.media.editor.insert(gallery)
	}
    
});
 
// custom toolbar : contains the buttons at the bottom
wp.media.view.Toolbar.SavedGalleries = wp.media.view.Toolbar.extend({
	initialize: function() {
		_.defaults( this.options, {
		    event: 'gallery_selected',
		    close: false,
			items: {
			    gallery_selected: {
			        text: wp.media.view.l10n.savedGalleriesButton, // added via 'media_view_strings' filter,
			        style: 'primary',
			        priority: 80,
			        requires: false,
			        click: this.customAction
			    }
			}
		});
 
		wp.media.view.Toolbar.prototype.initialize.apply( this, arguments );
	},
 
    // called each time the model changes
	refresh: function() {
	    // you can modify the toolbar behaviour in response to user actions here
	    // disable the button if there is no custom data
		var selected_gallery = this.controller.state().props.get('selected_gallery');
		this.get('gallery_selected').model.set( 'disabled', ! selected_gallery );
		
	    // call the parent refresh
		wp.media.view.Toolbar.prototype.refresh.apply( this, arguments );
	},
	
	// triggered when the button is clicked
	customAction: function(){
	    this.controller.state().customAction();
	}
});
 
// custom content : this view contains the main panel UI
wp.media.view.SavedGalleries = wp.media.View.extend({
	className: 'saved-galleries',
	
	// bind view events
	events: {
		'click':  'custom_update'
	},
 
	initialize: function() {
	    
      var $ = jQuery.noConflict()
        , $view = this.$el

      $.ajax( wp.media.model.settings.ajaxurl, {
        dataType:'json',
        type:'POST',
        data: {
          action:'get-galleries',
          query: {
            post_mime_type:'image',
            posts_per_page:'-1'
          }
        },
        success: function(data) {
          var $ul = $('<ul class="attachments ui-sortable ui-sortable-disabled" />')
            , galleries = []
            , template = wp.media.template('gallery-list')

          $.each(data,function(index,post) {
             var title        = this.post_title
               , attachments  = this.post_content.match(/(\d+)/g)
               , images = attachments.join(',')
               , $li          = $('<li/>').width(200)


            if ( $.inArray(images, galleries) != -1)
              return;

            galleries[index] = images;

            $li
              .addClass('attachment')
              .data('gallery-attachments', attachments )


            $.ajax(wp.media.model.settings.ajaxurl, {
              type:'POST',
              data: {
                action:'query-attachments',
                query: {
                  'post__in':attachments
                }
              },
              success: function(res) {
               if ( res.success ) {
                var res = res.data[0];
                res.data = $.extend(res, {count:attachments.length,post_title:title})
                $li.html(template(res.data));
                $ul.append($li)
               }
              }
            })

          })

          $view.append($ul.attr('style','margin:30px;'));

        }
      })
      
	    this.model.on( 'change:selected_gallery', this.render, this );
	},
	
	render: function(){
	    //this.input.value = this.model.get('selected_gallery');
	    return this;
	},
	
	custom_update: function( event ) {
    var $target = jQuery(event.target)
      , $li = $target.closest('li.attachment')
      , classes = 'details selected'

    if ( $target.closest('a.check').length ) {
      $li.removeClass(classes);
      this.model.set( 'selected_gallery', null );
    } else {
      $li.addClass(classes).siblings().removeClass(classes)
      this.model.set( 'selected_gallery', $li.data('gallery-attachments') );
    }
    return false;
	}
});
 
 
// supersede the default MediaFrame.Post view
var oldMediaFrame = wp.media.view.MediaFrame.Post;
wp.media.view.MediaFrame.Post = oldMediaFrame.extend({
 
    initialize: function() {
        oldMediaFrame.prototype.initialize.apply( this, arguments );
        
        this.states.add([
            new wp.media.controller.SavedGalleries({
                id:         'list-galleries',
                menu:       'default', // menu event = menu:render:default
                content:    'custom',
                title:      wp.media.view.l10n.savedGalleriesMenuTitle, // added via 'media_view_strings' filter
                priority:   200,
                toolbar:    'main-list-galleries', // toolbar event = toolbar:create:main-list-galleries
                type:       'link'
            })
        ]);
 
        this.on( 'content:render:custom', this.customContent, this );
        this.on( 'toolbar:create:main-list-galleries', this.createCustomToolbar, this );
        this.on( 'toolbar:render:main-list-galleries', this.renderCustomToolbar, this );
    },
    
    createCustomToolbar: function(toolbar){
        toolbar.view = new wp.media.view.Toolbar.SavedGalleries({
		    controller: this
	    });
    },
 
    customContent: function(){
        
        // this view has no router
        this.$el.addClass('hide-router');
 
        // custom content view
        var view = new wp.media.view.SavedGalleries({
            controller: this,
            model: this.state().props
        });
 
        this.content.set( view );
    }
 
});
