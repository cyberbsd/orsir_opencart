<?php

//conection, and set charset
$cake_link = mysqli_connect("localhost","root","karajan","cust_db") or die("Error " . mysqli_error($cake_link));

mysqli_query( $cake_link, "SET NAMES utf8") or die( "Error " . mysqli_error($cake_link) );

$opencart_link = mysqli_connect("localhost","root","karajan","opencart") or die("Error " . mysqli_error($opencart_link));

mysqli_query( $opencart_link, "SET NAMES utf8") or die( "Error " . mysqli_error($opencart_link) );


// query source
$cake_cust = "SELECT * FROM cake_customers WHERE id > 3 ORDER BY id LIMIT 10";

$result = mysqli_query($cake_link, $cake_cust) or die( "Error " . mysqli_error($cake_link) );

//display information:
while($row = mysqli_fetch_array($result))
{
  $id     = $row['id'];
  $name   = mysqli_real_escape_string($opencart_link,trim($row['name']));
  $birth  = $row['birth'];
  $add1   = mysqli_real_escape_string($opencart_link,trim($row['address1']));
  $add2   = mysqli_real_escape_string($opencart_link,trim($row['address2']));
  $add3   = mysqli_real_escape_string($opencart_link,trim($row['address3']));
  $phone1 = trim($row['phone1']);
  $phone2 = trim($row['phone2']);
  $phone3 = trim($row['phone3']);
  $vat    = mysqli_real_escape_string($opencart_link,trim($row['vat']));
  $vat_title = mysqli_real_escape_string($opencart_link,trim($row['vat_title']));
  $note      = mysqli_real_escape_string($opencart_link,trim($row['note']));
  $email     = mysqli_real_escape_string($opencart_link,trim($row['email']));
  $atm       = mysqli_real_escape_string($opencart_link,trim($row['atm']));
  $ent       = trim($row['ent']);
  $oversea   = trim($row['oversea']);
  $hundred   = trim($row['hundred']);

  $sql = sprintf( 'INSERT INTO oc_customer SET '
       . 'customer_id=%u, firstname="%s", birth="%s", email="%s",customer_group_id=1,'
       . 'telephone="%s",telephone2="%s",telephone3="%s",vat="%s",approved=1,status=1,'
       . 'vat_title="%s",atm="%s",ent=%u,oversea=%u,hundred=%u,note="%s";'
       , $id, $name, $birth, $email
       , $phone1, $phone2, $phone3, $vat
       , $vat_title, $atm, $ent, $oversea, $hundred, $note );

  print "$sql\n";

  mysqli_query( $opencart_link, $sql ) or die( "Error " . mysqli_error($opencart_link) );

  $sAddSql = sprintf( 'INSERT INTO oc_address SET '
                    . 'customer_id=%u,firstname="%s",address_1="%s",address_2="%s",address_3="%s";'
                    , $id, $name, $add1, $add2, $add3 );

  print "$sAddSql\n";

  mysqli_query( $opencart_link, $sAddSql ) or die( "Error " . mysqli_error($opencart_link) );


  $iAddressId = mysqli_insert_id($opencart_link);

  $sUpdateSql = sprintf( 'UPDATE oc_customer SET address_id=%u WHERE customer_id=%u LIMIT 1'
                       , $iAddressId, $id );

  mysqli_query( $opencart_link, $sUpdateSql ) or die( "Error " . mysqli_error($opencart_link) );

}



