<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="Css/Home.css">
</head>
  <body>
<div>
<nav class="navbar navbar-expand-lg bg-body-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Bunna Bank</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="padding-left:100px">
        <li class="nav-item">
          <a style="text-decoration:none;font-size:20px;padding-left:25px" class="head_nav" href="#hero">Home</a>
        </li>
        <li class="nav-item">
          <a style="text-decoration:none;font-size:20px;padding-left:25px"  class="head_nav" href="#about">About</a>
        </li>
        <li class="nav-item">
          <a style="text-decoration:none;font-size:20px;padding-left:25px"  class="head_nav" href="#testimonial">Testimonial</a>
        </li>
        <li class="nav-item">
          <a style="text-decoration:none;font-size:20px; padding-left:25px"  class="head_nav" href="#contact">Contact us</a>
        </li>
      </ul> 
    </div>
    <a href="login.php">
      <button class="../login.php">Login</button>
    </a>  
  </div> 
</nav>

</div>
<!-- Hero section -->
<div class="container-fluid hero" id="hero">
  <div class="row">
    <div class="col-md">
        <h1 class="h1h">Fast and easy Loan system
        Bunna Bank</h1>
      <p class="fp">
      Unlock your financial potential with our flexible loan solutions. Whether you're looking to consolidate debt, fund a project, or make a big purchase, we're here to help you every step of the way. Fast approvals, competitive rates, and personalized service await you!.</p >
    <button class="btn btn-success fbtn">Loan</button>
    </div>
    <div class="col-md">
<img class="fimg" src="./images/bunna.png" alt="">
</div>
</div>
<!-- About section -->
<section class=" about" id="about">
<div class="row">
<div class="col-md">
<img  class="simg"  src="./images/about.svg" alt=""/>
</div>
<div class="col-md">
    <h1 class="about-title">Bunna Bank</h1>
    <p class="about-p">At Bunna, we understand that life can bring unexpected financial challenges. Whether you're looking to consolidate debt, finance a major purchase, or cover unexpected expenses, we're here to help.

Our dedicated team is committed to providing you with personalized service and tailored loan solutions that meet your unique needs. With competitive rates and flexible terms, we aim to make the borrowing process simple and stress-free.

Explore our range of loan options, and let us assist you in taking the next step toward achieving your financial goals. Your future starts here!

Thank you for choosing Buna. We're excited to partner with you on your financial journey!</p>
<button class= "btn btn-success about-btn">Read More</button>
</div>
</div>
</section>

<!-- testimonial section -->
<section class="testimonial" id="testimonial">
  <h2>Testimonial</h2>
  <div class="card">
    <div class="card1"><img class="img1"src="./images/teams 4.jpg" alt="">
    <p class="test_p">"Absolutely seamless experience! The team at Buna bank guided me through every step of the loan process. Highly recommend!"</p>
    </div>
    <div class="card2"><img class="img1"src="./images/testi1.jpg" alt=""><p class="test_p">        "I was impressed with how quickly I received my funds. The rates were competitive, and the customer service was top-notch!"</p>
    </div>
    <div class="card3"><img class="img1"src="./images/testi6.jpg" alt=""><p class="test_p">        "Thanks to Buna bank, I was able to renovate my home without any stress. The process was quick and easy!"</p>
    </div>
  </div>
</section>
<!-- contact us -->
<div class="contact" id="contact">
    <h1 class="contact_title">Contact us</h1><br>
<div class="message">
<div class="m1">
<h1 class='get'>Get in touch</h1>
<br/><br/>
<form>
    <label>Name</label>
    <br/><br/>
    <input type='text'/>
    <br/>
    <label>Email</label>
    <br/><br/>
    <input type='email'/>
    <br/>
    <label>Message</label>
    <br/><br/>
    <textarea class='mees'></textarea>
    <br/><br/>
    <button type='submit' class='btn3'>Send me</button>
</form>

</div>
<div class="m2">
    <h1 class='con'>Contact us</h1>
    <p  class='add'><LocalPhoneIcon/>Phone:+251973424545</p>
    <p class="add"> <MailOutlineIcon/>Email:ananyateshome2@gmail</p>
    <p class='add'><AddLocationAltIcon/> Address:Addis Ababa ,Kotebe</p>

</div>
</div>
</div>

<!-- footer section -->
 <div class="foot">
    <div>
    <img class="foot_img" src="./images/bunna.png" alt="">
    </div>
    <div class="foot_nav">
        <nav>
            <a href="#hero">Home</a><br><br>
            <a href="#about">About</a><br><br>
            <a href="#testimonial">Testimonial</a><br><br>
            <a href="#contact">Contact us</a><br>
        </nav>
    </div>
<div>
<h1 class='conn'>Contact us</h1>
    <p  class='ad'><LocalPhoneIcon/>Phone:+251973424545</p>
    <p class="ad"> <MailOutlineIcon/>Email:ananyateshome2@gmail</p>
    <p class='ad'><AddLocationAltIcon/> Address:Addis Ababa ,Kotebe</p>

</div>
 </div>










    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>