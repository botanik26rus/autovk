tinyMCE.init({

		// режим

		selector: "#mytextarea",
		mode : "textareas",
		theme : "modern", // тема, есть простая -simple

		language:"ru", // язык

		// подключаем плагины, это подкаталоги в каталоге plugins
		plugins : "textcolor,colorpicker,link,anchor,code,image,imagetools,spellchecker,pagebreak,table,save,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,template",

		// теперь перечисляем какие кнопки вывести на панель
		
		toolbar1:"save,|,undo,redo,|,print,preview,fullscreen,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,anchor,help,code,|,removeformat,|,sub,sup,|,media,|,ltr,rtl,|,spellchecker,|,visualchars,nonbreaking,template,blockquote,pagebreak,",
		toolbar2: "styleselect,formatselect,fontselect,fontsizeselect,|,bold,italic,underline,strikethrough,|,forecolor,backcolor,|,cut,copy,paste,pastetext,",


});


