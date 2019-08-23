This is home
<?=$username; ?>
<script>
  let getBody = document.querySelector(".bodycon");

  let newElement = document.createElement("h2");

  let date = new Date();
  let currentHour = date.getHours();
  
  let createMsg;

  if (currentHour >= 0 && currentHour < 10) {
    createMsg = "Good Morning";
  } else if (currentHour >= 10 && currentHour < 12) {
    createMsg = "Good Day";
  } else if (currentHour >= 12 && currentHour < 16) {
    createMsg = "Good Afternoon";
  } else if (currentHour >= 16 && currentHour < 20) {
    createMsg = "Good Evening";
  } else if (currentHour >= 20 && currentHour < 24) {
    createMsg = "Good Night";
  } else {
    createMsg = "Are you from another planet :O";
  }

  newElement.innerHTML = createMsg;
  newElement.style.cssText = "text-align: center;"; 

   getBody.prepend(newElement);
</script>