// ad hoc fix to problems with mooTools JSON implementation breaking stuff

JSON.$specialChars = {'\b': '\\b', '\t': '\\t', '\n': '\\n', '\f': '\\f', '\r': '\\r', '"' : '\\"', '\\': '\\\\'};

JSON.$replaceChars = function(chr){
  return JSON.$specialChars[chr] || '\\u00' + Math.floor(chr.charCodeAt() / 16).toString(16) + (chr.charCodeAt() % 16).toString(16);
};


JSON.encode = function(obj){
  switch ($type(obj)){
    case 'string':
      return '"' + obj.replace(/[\x00-\x1f\\"]/g, JSON.$replaceChars) + '"';
    case 'array':
      return '[' + String(obj.map(JSON.encode).filter($defined)) + ']';
    case 'object': case 'hash':
      var string = [];
      Hash.each(obj, function(value, key){
        var json = JSON.encode(value);
        if (json) string.push(JSON.encode(key) + ':' + json);
      });
      return '{' + string + '}';
    case 'number': case 'boolean': return String(obj);
    case false: return 'null';
  }
  return null;
}

JSON.decode = function(string, secure){
  if ($type(string) != 'string' || !string.length) return null;
  if (secure && !(/^[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]*$/).test(string.replace(/\\./g, '@').replace(/"[^"\\\n\r]*"/g, ''))) return null;
  return eval('(' + string + ')');
}
