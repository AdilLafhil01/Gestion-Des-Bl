












<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html  xmlns="http://www.w3.org/1999/xhtml"
      xmlns:ui="http://java.sun.com/jsf/facelets"
      xmlns:f="http://java.sun.com/jsf/core"
      xmlns:h="http://java.sun.com/jsf/html"
      xmlns:form="http://www.springframework.org/tags/form"
      xmlns:p="http://primefaces.org/ui">

<h:head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" 
integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"/>
<script src="./js/detail.js"></script>


<!-- Le reste de votre code HTML ici -->











<style>
        img.image-resize {
            width: 1000px; /* Définissez la largeur souhaitée pour les images et les PDF ici */
            /* Définissez la hauteur souhaitée pour les images et les PDF ici */
            height: 620px;
            margin: 0 auto;
            max-width: 490px; /* Pour s'assurer que l'élément ne dépasse pas la largeur de son conteneur */
            max-height: 100%; /* Pour s'assurer que l'élément ne dépasse pas la hauteur de son conteneur */
        }
        
    </style>
<link
  href=".css/globale.css"
  rel="stylesheet"
/>
<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display:block;"
  rel="stylesheet"
/>
<!-- MDB -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css"
  rel="stylesheet"
/>

</h:head> 
<h:body>
  

<section class="vh-100" style="background-color: #9A616D;">
  <div class="container py-5 h-100 " style="direction:left;">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="./images/blimg.png" alt="login form" class="image-resize" style="border-radius: 1rem 0 0 1rem;" />
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

              <!--   <h:form id="login" prependId="false"              
                onsubmit="document.getElementById('login').action='/login';">
 -->
 <form action="authentification.php" method="post">

                  <div class="d-flex align-items-center mb-3 pb-1">
                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                    <span  class="h1 fw-bold mb-0"><img src="./images/intermediate.jpg"></img> </span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">LOG IN TO YOUR ACCOUNT</h5>

                  <div class="form-floating mb-4">
                  <input type="text" id="username" name="username" class="form-control" placeholder="USER NAME" />
                    
                    <label class="form-label" for="username">USER NAME</label>
                    
                  </div>

                  <div class="form-floating mb-4">
                  <input type="password" id="password" name="password" class="form-control"   placeholder="PASSWORD "  />
                    
                    <label class="form-label" for="password"> PASSWORD</label>
                  </div>
                  <div class="form-floating mb-4">
    <input type="text" id="role" name="role" class="form-control"   placeholder="LEVEL " />
    <label class="form-label" for="role">LEVEL</label>
</div>


                  <div class="pt-1 mb-4">
                  <button type="submit" class="btn btn-dark btn-lg btn-block" >LOG IN</button>
                <!--  <p:linkButton  value="ok llink" styleClass="btn btn-dark btn-lg btn-block" href="/home"  >
                 
                  </p:linkButton> --> 
                 <!--  -   <p:commandButton type="submit" styleClass="btn btn-dark btn-lg btn-block" action="/login"  value="الدخول" />-->
                  <!--  <p:commandLink value="ok" styleClass="btn btn-dark btn-lg btn-block"  action="/login" ></p:commandLink> --> 
                  </div>

                  <p class="small text-muted">
    <a href="#!">Forgot your password?</a> <a href="../../utilisateur.php">Register</a>
    
</p>

    
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


</h:body> 

</html>
