function reset_text(nodeid, text)
{
  var e=document.getElementById(nodeid);
  // decode node content from b64
  e.innerHTML=atob(text);
}

function add_form_tags(id, tags)
{
  var node=id+"-tag";
  var e=document.getElementById(node);
  var i=id+"-edit";
  var text=e.innerHTML;

  // Does a form already exists?
  if (text.substr(0,5)=="<form")
    return;

  // encode node content to b64 to catch all special characters
  text=btoa(text); 
  e.innerHTML="<form>" +
    "<input type=\"hidden\" name=\"section\" value=\"image\"/>"+
    "<input type=\"hidden\" name=\"image\" value=\""+id+"\"/>"+
    "<input type=\"text\" id=\"" + i + "\" name=\"tags\" value=\"" + tags + "\" />"+
    "<br/>"+
    "<input class=\"submit\" type=\"submit\" value=\"Send\"/> or "+
    "<input class=\"reset\" type=\"reset\" onclick=\"reset_text('"+node+"','"+text+"')\"/>"+
  "</form>";
  document.getElementById(i).focus();
}

