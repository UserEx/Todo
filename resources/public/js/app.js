require(['jquery'], function($){
	var todos = {
		init: function () {
			$('.new-todo').keypress(function (e) { 
				if (e.which == 13) {
					console.log($(this).val());
					$.ajax({
					    url: getUrl('todo_add'),
					    method: 'POST',
					    dataType : "json",
					    data: { 'todo': $(this).val() },
					    success: function (data, textStatus) {
					    	if (data.code == 200) {
						        console.log('textStatus: ' + textStatus);
						        console.log('data.code: ' + data.code);
						        console.log('data.status: ' + data.msg);
						        $('.new-todo').val('');
						        todos.add(data.todo.id, data.todo.title)
					    	}
					    } 
					});
				}
			});
			
			todos.loadList();
			
			$('.toggle-all').click(function (e) {
				var completed = $(this).prop('checked');
				todos.toggleAll(completed, function () {
					if (completed) {
						$('.todo-list li').addClass('completed');
					} else {
						$('.todo-list li').removeClass('completed');
					}
					$('.todo-list li').find('.toggle').prop('checked', completed);
				});
			});
			
			todos.updateToggleAllCheckbox();
			
			$('.clear-completed').click(function (e) {
				todos.removeCompleted(function () {
					$('.todo-list li.completed').remove();
					todos.updateToggleAllCheckbox();
				})
			})
			
			$(window).on('hashchange',function(e){ 
				todos.applyFilters();
			});
 		},
		
		add: function (id, title, completed) {
			var todoItem = $('.empty-todo-item').clone()
				.removeClass('empty-todo-item')
				.data('todo-id', id);
				
			todoItem.find('label').append(title);
			if (completed) {
				todoItem.addClass('completed');
				todoItem.find('.toggle').prop('checked', true);
			}
			
			todoItem.find('.destroy').click(function (e) {
				todos.remove(id, function (e) {
					todoItem.remove();
					todos.updateToggleAllCheckbox();
				});
			});
			
			todoItem.find('.toggle').click(function (e) {
				var completed = !todoItem.hasClass('completed');
				todos.toggle(id, completed, function () {
					if (completed) {
						todoItem.addClass('completed');
					} else {
						todoItem.removeClass('completed');
					}
					todos.updateToggleAllCheckbox();
				});
			});
				
			todoItem.appendTo('.todo-list');
			todoItem.show();
			todos.updateToggleAllCheckbox();
		},
		
		remove: function (id, callback) {
			$.ajax({
				url: getUrl('todo_delete'),
			    method: 'POST',
			    dataType : "json",
			    data: { 'todo_id': id },
			    success: function (data, textStatus) {
			    	if (data.code == 200 && $.isFunction(callback)) {
		    			callback();
			    	}
			    }
			});
		}, 
		
		loadList: function () {
			$.ajax({
				url: getUrl('todo_load_list'),
			    method: 'POST',
			    dataType : "json",
			    success: function (data, textStatus) {
			    	if (data.code == 200) {
			    		$('.todo-list').empty();
			    		var list = data['todo_list'];
			    		for (i in list) {
			    			todos.add(list[i].id, list[i].title, list[i].completed);
			    		}
			    	}
			    }
			});
		},
		
		toggle: function (id, completed, callback) {
			$.ajax({
				url: getUrl('todo_toggle'),
			    method: 'POST',
			    dataType : "json",
			    data: { 'todo_id': id, 'completed': completed },
			    success: function (data, textStatus) {
			    	if (data.code == 200 && $.isFunction(callback)) {
		    			callback();
			    	}
			    }
			});
		},
		
		toggleAll: function (completed, callback) {
			$.ajax({
				url: getUrl('todo_toggle_all'),
			    method: 'POST',
			    dataType : "json",
			    data: { 'completed': completed },
			    success: function (data, textStatus) {
			    	if (data.code == 200 && $.isFunction(callback)) {
		    			callback();
			    	}
			    }
			});
		},
		
		updateToggleAllCheckbox: function () {
			var lengthList = $('.todo-list li').length;
			
			if (lengthList > 0) {
				var checked = lengthList == $('.todo-list li.completed').length;
				$('.toggle-all').prop('checked', checked);
				$('.toggle-all, [for="toggle-all"], .footer').show()
			} else {
				$('.toggle-all, [for="toggle-all"], .footer').hide();
			}
			
			count = $('.todo-list li:not(li.completed)').length;
			$('.todo-count strong').html(count);
			todos.applyFilters();
		},
		
		applyFilters: function () {
			$('.filters a.selected').removeClass('selected');
		    switch (location.hash.slice(1)) {
		    	case '/':	
		    		$('.todo-list li').show();
		    		$('.filters a[href="#/"]').addClass('selected');
		    		break;
		    		
		    	case '/active':
		    		$('.todo-list li:not(li.completed)').show();
		    		$('.todo-list li.completed').hide();
		    		$('.filters a[href="#/active"]').addClass('selected');
		    		break;
		    	
		    	case '/completed':
		    		$('.todo-list li:not(li.completed)').hide();
		    		$('.todo-list li.completed').show();
		    		$('.filters a[href="#/completed"]').addClass('selected');
		    		break;
		    }
		},
		
		removeCompleted: function (callback) {
			$.ajax({
				url: getUrl('todo_delete_completed'),
			    method: 'POST',
			    dataType : "json",
			    success: function (data, textStatus) {
			    	if (data.code == 200 && $.isFunction(callback)) {
		    			callback();
			    	}
			    }
			});
		},		
	};
	todos.init();
	
	window.todos = todos;
});