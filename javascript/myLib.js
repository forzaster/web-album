function createXMLHttpRequest() {
  if (!window.XMLHttpRequest) {
    try {
      return new ActiveXObject("Msxml2.XMLHTTP.6.0");
    } catch (e) {}
    try {
      return new ActiveXObject("Msxml2.XMLHTTP.3.0");
    } catch (e) {}
    try {
      return new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {}
    throw new Error("This browser does not support XMLHttpRequest.");
  };
  return new XMLHttpRequest();
}
