// Contenu de votre_script.js
function searchByBL() {
    var blNumber = document.getElementById("rechercheTxt").value;
    console.log("Numéro de BL saisi : " + blNumber);
    // Vous pouvez implémenter la recherche ici
}

  
  // Fonction JavaScript pour vérifier le rôle et rediriger l'utilisateur si nécessaire
  function redirectToUserPage() {
      var roleInput = document.getElementById('role');
      var role = roleInput.value.toLowerCase(); // Assurez-vous que le rôle est en minuscules pour la comparaison

      if (role === 'admin') {
          // Si le rôle est "admin", redirigez vers la page "utilisateur.php"
          window.location.href = 'utilisateur.php';
      } else {
          // Sinon, affichez un message d'erreur ou effectuez une autre action
          alert('Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
          // Empêchez la redirection en supprimant le lien "Register"
          var registerLink = document.getElementById('register-link');
          registerLink.style.display = 'none';
      }
  }

