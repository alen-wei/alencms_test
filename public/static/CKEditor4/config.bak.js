CKEDITOR.editorConfig = function( config ) {
	config.extraPlugins = 'delhref';  //载入插件
	//config.toolbarStartupExpanded = false;  //工具栏默认是否展开
	config.language = 'zh-cn';  //语言
	config.uiColor = '#F0F0F0'; //背景
	config.tabSpaces = 4;  //tab键缩进空格数
	//config.disableObjectResizing = false;  //是否开启 图片和表格 的改变大小的功能，IE有效
	config.toolbarCanCollapse = false; //工具栏是否可以被收缩
	//config.removePlugins = 'elementspath';
	config.resize_enabled = false; //取消 “拖拽以改变尺寸”功能
	config.baseHref = '';  //设置是使用绝对目录还是相对目录，为空为相对目录
	config.disableObjectResizing = true;   //是否开启 图片和表格 的改变大小的功能 
	config.enterMode = CKEDITOR.ENTER_P; //可选：CKEDITOR.ENTER_P、CKEDITOR.ENTER_BR或CKEDITOR.ENTER_DIV
	config.filebrowserImageUploadUrl="?action=upload&ctrl=editor&inpname=upload&upext=jpg,jpeg,gif,png";
	config.filebrowserFlashUploadUrl="?action=upload&ctrl=editor&inpname=upload&upext=swf";
	config.filebrowserLinkUploadUrl="?action=upload&ctrl=editor&inpname=upload";
	config.toolbar = 'Full';
	config.toolbar_Full = [
		['Format','Font','FontSize'],
		['TextColor','BGColor','Bold','Italic','Underline','Strike','Subscript','Superscript'],
		['Link','Unlink','delhref','Anchor'],['Preview','Maximize','Source'],
		'/',
		['NumberedList','BulletedList','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Image','Flash','Table','HorizontalRule','SpecialChar'],
		['Cut','Copy','Paste','PasteText','PasteFromWord'],
		['Undo','Redo','Find','Replace','RemoveFormat']
	];
	config.toolbar_Basic = [
		['Format','Font','FontSize'],
		['TextColor','BGColor','Bold','Italic','Underline','Strike','Subscript','Superscript'],
		['Link','Unlink','Anchor'],['Preview','Maximize','Source'],
		'/',
		['NumberedList','BulletedList','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Image','Flash','Table','HorizontalRule','SpecialChar'],
		['Cut','Copy','Paste','PasteText','PasteFromWord']
	];
};
