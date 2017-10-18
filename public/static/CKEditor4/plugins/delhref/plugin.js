(function(){
	var name='delhref';
	var fun={
		exec: function(o){
			var _html = o.getData();
			var reg =/<a([\d\D]*?)href=["|'][\d\D]*?["|']([\d\D]*?)>([\d\D]*?)<\/a>/g;
			_html=_html.replace(reg,"<a$1$2>$3</a>");
			reg =/<a[\s]*>([\d\D]*?)<\/a>/g;
			_html=_html.replace(reg,"$1");
			o.setData(_html);
		}
	};
    CKEDITOR.plugins.add(name,{
        init: function(o){
			o.addCommand(name,fun);
			o.ui.addButton(name,{
				label: "清除所有链接",
				command : name,
				icon: this.path + "iocn.png"//这个g.ico是你的插件图标，放在同目录下
			});
        }
    });
})();