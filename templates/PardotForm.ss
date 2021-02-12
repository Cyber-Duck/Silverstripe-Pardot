<noscript>
    $Code
</noscript>

<script type="text/javascript">
 var form = '{$FormURL}';
 var params = window.location.search;
 var thisScript = document.scripts[document.scripts.length - 1];
 var iframe = document.createElement('iframe');

 iframe.setAttribute('src', form + params);
 iframe.setAttribute('width', '100%');
 iframe.setAttribute('height', 500);
 iframe.setAttribute('type', 'text/html');
 iframe.setAttribute('frameborder', 0);
 iframe.setAttribute('allowTransparency', 'true');
 <% if $CSSClass %>
    iframe.setAttribute('class', '{$CSSClass}');
 <% end_if %>
 iframe.style.border = '0';

 thisScript.parentElement.replaceChild(iframe, thisScript);
</script>
