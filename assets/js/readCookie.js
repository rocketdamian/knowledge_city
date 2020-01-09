function readCookie(cookieName) {
  var allCookies = document.cookie;
  cookieArray = allCookies.split(";");

  var foundCookie = null;
  for (var i = 0; i < cookieArray.length; i++) {
    name = cookieArray[i].split("=")[0].trim();
    value = cookieArray[i].split("=")[1];
    if (cookieName === name) {
      foundCookie = value;
    }
  }

  return foundCookie;
}