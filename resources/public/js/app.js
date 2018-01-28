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
					    data: {'todo': $(this).val()},
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
		},
		
		add: function (id, title, completed) {
			var todoItem = $('.empty-todo-item').clone()
				.removeClass('empty-todo-item')
				.data('todo-id', id);
				
			todoItem.find('label').append(title);
			if (completed) {
				todoItem.addClass('completed');
			}
			
			todoItem.find('.destroy').click(function (e) {
				todos.remove(id, function (e) {
					todoItem.remove();
				});
			});
				
			todoItem.appendTo('.todo-list');
			todoItem.show();
		},
		
		remove: function (id, callback) {
			$.ajax({
				url: getUrl('todo_delete'),
			    method: 'POST',
			    dataType : "json",
			    data: {'todo_id': id},
			    success: function (data, textStatus) {
			    	if (data.code == 200) {
			    		if ($.isFunction(callback)) {
			    			callback();
			    		}
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
		}
		
	};
	todos.init();
	
	
});