try{fetch('api/event.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({event_type:'visit',route:location.pathname})});}catch(e){}
