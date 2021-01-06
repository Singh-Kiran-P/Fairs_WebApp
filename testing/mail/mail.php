<?php
// //Transform our POST array into a URL-encoded query string.
// $postStr = [
//   "personalizations" => [
//     [
//       "to" => [
//         [
//           "email" => "kiran.singh@student.uhasselt.be"
//         ]
//       ]
//     ]
//   ],
//   "from" =>     ["email" => "singh.kiran2456@hotmail.com"],
//   "subject" => "Hello, World!",
//   "content" => [
//     [
//       "type" => "text/plain",
//       "value" => "Heya!"
//     ]
//   ]
// ];
// print_r(json_encode($postStr));

// //Create an $options array that can be passed into stream_context_create.
// $options = array(
//   'https' =>
//   array(
//     'method'  => 'POST', //We are using the POST HTTP method.
//     'header' => [
//       "Authorization: Bearer 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",
//       "Content-type: application/json"
//     ],
//     'content' => http_build_query($postStr) //Our URL-encoded query string.
//   )
// );
// //Pass our $options array into stream_context_create.
// //This will return a stream context resource.
// $streamContext  = stream_context_create($options);
// //Use PHP's file_get_contents function to carry out the request.
// //We pass the $streamContext variable in as a third parameter.
// $result = file_get_contents("https://api.sendgrid.com/v3/mail/send", false, $streamContext);
// //If $result is FALSE, then the request has failed.
// if ($result === false) {
//   //If the request failed, throw an Exception containing
//   //the error.
//   $error = error_get_last();
//   throw new Exception('POST request failed: ' . $error['message']);
// }
// //If everything went OK, return the response.
// return $result;


$params = array(
  'api_user'  => 'api_key',
  'api_key'   => 'your_api_key',
  'to'        => "kiran.singh@student.uhasselt.be",
  'from'      => "singh.kiran2456@hotmail.com",
  'subject'   => '$subject',
  'html'      => '$message',
);
$request = 'https://api.sendgrid.com/api/mail.send.json';
$session = curl_init($request);
curl_setopt ($session, CURLOPT_POST, true);
curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($session);
curl_close($session);
$msg = json_decode($response);
return $msg->message;
