//Удалить куку
function deleteCookie(name) {
	var date = new Date(); // Берём текущую дату
	date.setTime(date.getTime() - 1); // Возвращаемся в "прошлое"
	document.cookie = name += "=; expires=" + date.toGMTString(); // Устанавливаем cookie пустое значение и срок действия до прошедшего уже времени
}
//Создать куку
function createCookie(name,value) {
	document.cookie = name+"="+value="; max-age=" + 60 * 60 * 24 * 100;
}

// возвращает cookie с именем name, если есть, если нет, то undefined
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}
////////////////////////////////////////////////////////////////
