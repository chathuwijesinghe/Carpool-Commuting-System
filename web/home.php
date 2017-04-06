<?php require_once('header.php'); ?>
<?php require_once('header_navbar.php'); ?>
<?php
/**
 * Created by IntelliJ IDEA.
 * User: Nishen Peiris
 * Date: 4/5/17
 * Time: 17:52
 */

$internal_error = $from = $from_error = $to = $to_error = "";
?>

<div class="container main_home_container">
   <div class="find_a_ride_wrp">
       <h3>Find a Ride</h3>
       <span><?php echo $internal_error; ?></span><br/>
       <!-- htmlspecialchars is used to protect against XSS attacks -->
       <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
           <div class="input_wrp">
               <input type="text" placeholder="From" name="from" value="<?php echo htmlspecialchars($from); ?>"
                      autofocus="autofocus" ><i class="fa fa-map-marker" aria-hidden="true"></i>

           </div>
           <span><?php echo $from_error; ?></span><br/>

           <div class="input_wrp">
               <input type="text" name="to" placeholder="To" value="<?php echo htmlspecialchars($to); ?>" /><i class="fa fa-map-marker" aria-hidden="true"></i>
           </div>
           <span><?php echo $to_error; ?></span><br/>
           <div class="input_wrp">
               <input type="date" name="start_date" placeholder="Start Date" min="<?php echo date('Y-m-d'); ?>"
              value="<?php echo htmlspecialchars($start_date); ?>"/><br/>
           </div>
           <div class="search_btn">
<!--               <input type="submit" value="Search"/>-->
               <button type="submit" value="Search"><i class="fa fa-search" aria-hidden="true"></i> SEARCH</button>
           </div>


       </form>
       
   </div>
    <div class="quote"> Rideshare from Anywhere to Everywhere</div>

</div>



<?php require_once('footer.php'); ?>