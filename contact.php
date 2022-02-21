<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php' ;
require 'vendor/PHPMailer/src/PHPMailer.php' ;
require 'vendor/PHPMailer/src/SMTP.php' ;

require 'includes/init.php' ;

$email = '' ;
$subject = '' ;
$message = '' ;
$sent = false ;

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  $err = [];

  if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
    $err[] = 'Please enter a valid email address' ;
  }

  if ( $subject == '' ) {
    $err[] = 'Please enter a subject' ;
  }

  if ( $message == '' ) {
    $err[] = 'Please enter a message' ;
  }

  if ( empty( $err ) ) {

    $mail = new PHPMailer(true);

    try {
        //Server settings              
        $mail->isSMTP();                                           
        $mail->Host       = SMTP_HOST ;                    
        $mail->SMTPAuth   = true;                                  
        $mail->Username   = SMTP_USER ;                    
        $mail->Password   = SMTP_PASS ;                               
        $mail->SMTPSecure = 'tls';            
        $mail->Port       = SMTP_PORT;                                   
    
        //Recipients
        $mail->setFrom( SMTP_SEND );
        $mail->addAddress( SMTP_SEND );
        $mail->addReplyTo( $email );
    
        //Content
        // $mail->isHTML(true);                                 
        $mail->Subject = $subject ;
        $mail->Body    = $message ;
    
        $mail->send();

        $sent = true ;

    } catch (Exception $e) {

        $err[] = $mail->ErrorInfo ;

    }
  }
}

?>

<?php require 'includes/header.php' ; ?>

<h2 class="">Contact</h2>

<?php if ( $sent ) : ?>
  <p class="">Message Sent.</p>
<?php else : ?>

  <?php if ( !empty( $err ) ) : ?>
    <ul>
      <?php foreach ( $err as $error ) : ?>
        <li>
          <?= $error ?>
        </li>
      <?php endforeach ; ?>
    </ul>
  <?php endif ; ?>

  <form id="contactForm" method="post" class="">

    <div class="form-group">
      <label for="email">Your Email</label>
      <input 
      class="form-control"
      type="email" 
      id="email" 
      name="email" 
      placeholder="Your email address"
      value="<?= htmlspecialchars($email) ?>">
    </div>

    <div class="form-group">
      <label for="subject">Subject</label>
      <input 
      class="form-control"
      type="subject" 
      id="subject" 
      name="subject" 
      placeholder="Subject"
      value="<?= htmlspecialchars($subject) ?>">
    </div>

    <div class="form-group">
      <label for="message">Your Email</label>
      <textarea 
      class="form-control"
      id="message" 
      name="message" 
      placeholder="Your message here"
      >
        <?= htmlspecialchars($message) ?>
      </textarea>
    </div>

    <button class="btn">Send</button>

  </form>

<?php endif ; ?>

<?php require 'includes/footer.php' ; ?>
