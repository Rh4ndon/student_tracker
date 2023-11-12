 // Update the fake cursor when the input value changes
 var inputs = document.querySelectorAll('.input-container input');
 inputs.forEach(function(input) {
     input.addEventListener('input', function() {
         var fakeCursor = this.parentNode.querySelector('.fake-cursor');
         fakeCursor.textContent = this.value;
     });
 });
