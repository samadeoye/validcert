<?php
require_once 'inc/utils.php';
$pageTitle = 'Certificate Verification';
require_once 'inc/head.php';
?>

<main class="main">

  <!-- Hero Section -->
  <section id="hero" class="hero section light-background">
    <img src="assets/img/hero-bg.jpg" alt="Certificate Verification" data-aos="fade-in">

    <div class="container position-relative">
      <div class="welcome position-relative" data-aos="fade-down" data-aos-delay="100">
        <span>WELCOME TO </span><h2><?php echo SITE_NAME; ?></h2>
        <p>Verify and log certificates with ease. Secure and trusted by institutions.</p>
        <a href="verify" class="btn btn-block btn-theme fw-bold mt-4">Verify Certificate</a>
      </div><!-- End Welcome -->
    </div>
  </section>
<!-- /Hero Section -->

  <!-- How It Works Section -->
  <section id="how-it-works" class="how-it-works section">
    <div class="container section-title" data-aos="fade-up">
      <h2>How It Works</h2>
      <p>Our platform offers a secure and easy way for issuers to log certificates and for third-parties to verify them instantly.</p>
    </div>

    <div class="container">
      <div class="row gy-4">
        
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
          <div class="feature-box">
            <i class="bi bi-file-earmark-check fs-1"></i>
            <h3>Issuers Log Certificates</h3>
            <p>Issuers can easily log certificates to our platform, attaching details the necessary details.</p>
          </div>
        </div><!-- End Feature Item -->

        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
          <div class="feature-box">
            <i class="bi bi-check-circle fs-1"></i>
            <h3>Instant Verification</h3>
            <p>Third-party verifiers can check the authenticity of certificates with just a few clicks.</p>
          </div>
        </div><!-- End Feature Item -->

        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
          <div class="feature-box">
            <i class="bi bi-shield-lock fs-1"></i>
            <h3>Secure and Trusted</h3>
            <p>We use advanced encryption and security protocols to ensure the authenticity of every certificate logged and verified.</p>
          </div>
        </div><!-- End Feature Item -->

      </div>
    </div>
  </section><!-- /How It Works Section -->

  <!-- About Section -->
  <section id="about" class="about section">
    <div class="container section-title" data-aos="fade-up">
      <h2>About Us</h2>
      <p>We provide an easy-to-use solution for logging and verifying certificates online.</p>
    </div>

    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-md-5">
          <div class="about-img-wrapper">
            <img src="assets/img/logo.png" class="w-50" alt="About Us">
          </div>
        </div>
        <div class="col-md-7">
          <h3 class="fw-bold">Why Choose Us?</h3>
          <p>Our platform simplifies the certificate logging and verification process for institutions, organizations, and individuals. Whether you're issuing certificates for educational programs, professional certifications, or any other related purpose, they can be verified quickly, securely, and reliably.</p>
        </div>
      </div>
    </div>
  </section><!-- /About Section -->

  <!-- Contact Section -->
  <section id="contact" class="contact section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Contact</h2>
      <p>Have any questions? Reach out to us!</p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4">
        <div class="col-lg-6">
          <div class="info-item d-flex">
            <i class="bi bi-telephone flex-shrink-0"></i>
            <div>
              <h3>Call Us</h3>
              <p><?php echo SITE_PHONE; ?></p>
            </div>
          </div><!-- End Info Item -->
        </div>

        <div class="col-lg-6">
          <div class="info-item d-flex">
            <i class="bi bi-envelope flex-shrink-0"></i>
            <div>
              <h3>Email Us</h3>
              <a href="mailto:<?php echo SITE_EMAIL; ?>"><p><?php echo SITE_EMAIL; ?></p></a>
            </div>
          </div><!-- End Info Item -->
        </div>

      </div><!-- End Row -->
    </div><!-- End Container -->
  </section><!-- /Contact Section -->

</main>

<?php
require_once 'inc/foot.php';
?>