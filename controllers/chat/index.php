<?php

/*
 * This file is part of the CRUD Admin Generator project.
 *
 * Author: Jon Segador <jonseg@gmail.com>
 * Web: http://crud-admin-generator.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../src/app.php';
require_once __DIR__.'/../../src/utils.php';

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/chat/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
    $start = 0;
    $vars = $request->request->all();
    $qsStart = (int)$vars["start"];
    $search = $vars["search"];
    $order = $vars["order"];
    $columns = $vars["columns"];
    $qsLength = (int)$vars["length"];    
    
    if($qsStart) {
        $start = $qsStart;
    }    
    
    $index = $start;   
    $rowsPerPage = $qsLength;
       
    $rows = array();
    
    $searchValue = $search['value'];
    $orderValue = $order[0];
    
    if($orderValue || $orderClause!="") {
        $orderClause = " ORDER BY ". $columns[(int)$orderValue['column']]['data'] . " " . $orderValue['dir'];
    }
    
    $table_columns = array(
        'c_id', 
        'r_firstname', 
        'c_message',
        'w_name',
        'c_is_replied',
        'c_datetime', 

    );
    
    $table_columns_type = array(
        'int(11)', 
        'text',
        'text',
        'text',
        'int(4)',
        'timestamp', 

    );    
    
    $whereClause = "";
    
    $i = 0;
    foreach($table_columns as $col){
        
        if ($i == 0) {
           $whereClause = " WHERE";
        }
        
        if ($i > 0) {
            $whereClause =  $whereClause . " OR"; 
        }
        
        $whereClause =  $whereClause . " " . $col . " LIKE '%". $searchValue ."%'";
        
        $i = $i + 1;
    }
    
    $recordsTotal = $app['db']->fetchColumn("SELECT COUNT(*) FROM `chat` c INNER JOIN `register` r on r.`r_id`=c.`c_r_id` INNER JOIN `schedule` s on r.`r_s_id`=s.`s_id` INNER JOIN `webinar` w on w.`w_id`=s.`s_w_id` " . $whereClause . $orderClause, array(), 0);
    
    $find_sql = "SELECT c.`c_id`,r.`r_firstname`,w.`w_name`,c.`c_message`,c.`c_datetime`,c.`c_is_replied` FROM `chat` c INNER JOIN `register` r on r.`r_id`=c.`c_r_id` INNER JOIN `schedule` s on r.`r_s_id`=s.`s_id` INNER JOIN webinar w on w.`w_id`=s.`s_w_id` ". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
        for($i = 0; $i < count($table_columns); $i++){

        if( $table_columns_type[$i] != "blob") {
                $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
        } else {                if( !$row_sql[$table_columns[$i]] ) {
                        $rows[$row_key][$table_columns[$i]] = "0 Kb.";
                } else {
                        $rows[$row_key][$table_columns[$i]] = " <a target='__blank' href='menu/download?id=" . $row_sql[$table_columns[0]];
                        $rows[$row_key][$table_columns[$i]] .= "&fldname=" . $table_columns[$i];
                        $rows[$row_key][$table_columns[$i]] .= "&idfld=" . $table_columns[0];
                        $rows[$row_key][$table_columns[$i]] .= "'>";
                        $rows[$row_key][$table_columns[$i]] .= number_format(strlen($row_sql[$table_columns[$i]]) / 1024, 2) . " Kb.";
                        $rows[$row_key][$table_columns[$i]] .= "</a>";
                }
        }

        }
    }    
    
    $queryData = new queryData();
    $queryData->start = $start;
    $queryData->recordsTotal = $recordsTotal;
    $queryData->recordsFiltered = $recordsTotal;
    $queryData->data = $rows;
    
    return new Symfony\Component\HttpFoundation\Response(json_encode($queryData), 200);
});




/* Download blob img */
$app->match('/chat/download', function (Symfony\Component\HttpFoundation\Request $request) use ($app) { 
    
    // menu
    $rowid = $request->get('id');
    $idfldname = $request->get('idfld');
    $fieldname = $request->get('fldname');
    
    if( !$rowid || !$fieldname ) die("Invalid data");
    
    $find_sql = "SELECT " . $fieldname . " FROM " . chat . " WHERE ".$idfldname." = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($rowid));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('menu_list'));
    }

    header('Content-Description: File Transfer');
    header('Content-Type: image/jpeg');
    header("Content-length: ".strlen( $row_sql[$fieldname] ));
    header('Expires: 0');
    header('Cache-Control: public');
    header('Pragma: public');
    ob_clean();    
    echo $row_sql[$fieldname];
    exit();
   
    
});



$app->match('/chat', function () use ($app) {
    
    $table_columns = array(
        'c_id', 
        'r_firstname', 
        'c_message', 
        'w_name',
        'c_is_replied',
        'c_datetime', 

    );

    $primary_key = "c_id";  

    return $app['twig']->render('chat/list.html.twig', array(
        "table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('chat_list');



$app->match('/chat/create', function () use ($app) {
    
    $initial_data = array(
        'c_r_id' => '', 
        'c_message' => '', 
        'c_datetime' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



    $form = $form->add('c_r_id', 'text', array('required' => true));
    $form = $form->add('c_message', 'textarea', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `chat` (`c_r_id`, `c_message`, `c_datetime`) VALUES (?, ?, NOW())";
            $app['db']->executeUpdate($update_query, array($data['c_r_id'], $data['c_message']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'chat created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('chat_list'));

        }
    }

    return $app['twig']->render('chat/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('chat_create');



$app->match('/chat/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `chat` WHERE `c_id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('chat_list'));
    }

    
    $initial_data = array(
        'c_r_id' => $row_sql['c_r_id'], 
        'c_message' => $row_sql['c_message'], 
        'c_reply_mail' => $row_sql['c_reply_mail'], 
        'c_reply_datetime' => $row_sql['c_reply_datetime'], 
        'c_datetime' => $row_sql['c_datetime'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


    $form = $form->add('c_r_id', 'text', array('required' => true));
    $form = $form->add('c_message', 'textarea', array('required' => true));
    $form = $form->add('c_datetime', 'text', array('required' => true));
    $form = $form->add('c_reply_mail', 'textarea', array('required' => true));
    $form = $form->add('c_reply_datetime', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `chat` SET `c_r_id` = ?, `c_message` = ?, `c_datetime` = ? WHERE `c_id` = ?";
            $app['db']->executeUpdate($update_query, array($data['c_r_id'], $data['c_message'], $data['c_datetime'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'chat edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('chat_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('chat/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('chat_edit');



$app->match('/chat/reply/{id}', function ($id) use ($app) {

    $find_sql = "SELECT c.`c_r_id`,r.`r_email`, r.`r_firstname`,c.`c_message`,w.`w_name`,c.`c_is_replied`,c.`c_datetime` FROM `chat` c INNER JOIN `register` r on r.`r_id`=c.`c_r_id` INNER JOIN `schedule` s on r.`r_s_id`=s.`s_id` INNER JOIN `webinar` w on w.`w_id`=s.`s_w_id` where `c_id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('chat_list'));
    }

    
    $initial_data = array(
        'c_r_id' => $row_sql['c_r_id'], 
        'r_firstname' => $row_sql['r_firstname'], 
        'c_message' => $row_sql['c_message'], 
        'w_name' => $row_sql['w_name'], 
        'c_datetime' => $row_sql['c_datetime'],
        'r_email'=>$row_sql['r_email'] 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


    $form = $form->add('c_reply', 'textarea', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `chat` SET `c_reply_mail` = ? WHERE `c_id` = ?";
            $app['db']->executeUpdate($update_query, array($data['c_reply'], $id)); 
          
            if(sendmail($id,$app))
            {       
            $update_query = "UPDATE `chat` SET `c_is_replied` = 1,  `c_reply_datetime` = NOW() WHERE `c_id` = ?";
            $app['db']->executeUpdate($update_query, array($id)); 
    
                $app['session']->getFlashBag()->add(
                    'success',
                    array(
                        'message' => 'replied!',
                    )
                );
            }}
            else{

                $app['session']->getFlashBag()->add(
                    'success',
                    array(
                        'message' => 'something went wrong!',
                    )
                );
            }
            return $app->redirect($app['url_generator']->generate('chat_reply', array("id" => $id)));

    }

    return $app['twig']->render('chat/reply.html.twig', array(
        "form" => $form->createView(),
        "id" => $id,
        "data"=>$initial_data
    ));
})
->bind('chat_reply');

$app->match('/chat/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `chat` WHERE `c_id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `chat` WHERE `c_id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'chat deleted!',
            )
        );
    }
    else{
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );  
    }

    return $app->redirect($app['url_generator']->generate('chat_list'));

})
->bind('chat_delete');



$app->match('/chat/downloadList', function (Symfony\Component\HttpFoundation\Request $request) use($app){
    
     $table_columns = array(
        'c_id', 
        'r_firstname', 
        'c_message',
        'w_name',
        'c_is_replied',
        'c_datetime', 

    );
    
    $table_columns_type = array(
        'int(11)', 
        'text',
        'text',
        'text',
        'int(4)',
        'timestamp', 

    );    

    $types_to_cut = array('blob');
    $index_of_types_to_cut = array();
    foreach ($table_columns_type as $key => $value) {
        if(in_array($value, $types_to_cut)){
            unset($table_columns[$key]);
        }
    }

    $columns_to_select = implode(',', array_map(function ($row){
        return '`'.$row.'`';
    }, $table_columns));
     
    $find_sql = "SELECT c.`c_id`, r.`r_firstname`,c.`c_message`,w.`w_name`,c.`c_is_replied`,c.`c_datetime` FROM `chat` c INNER JOIN `register` r on r.`r_id`=c.`c_r_id` INNER JOIN `schedule` s on r.`r_s_id`=s.`s_id` INNER JOIN `webinar` w on w.`w_id`=s.`s_w_id` ";
    $rows_sql = $app['db']->fetchAll($find_sql, array());
  
    $mpdf = new mPDF();

    $stylesheet = file_get_contents('../web/resources/css/bootstrap.min.css'); // external css
    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->WriteHTML('.table {
    border-radius: 5px;
    width: 100%;
    margin: 0px auto;
    float: none;
}',1);

    $mpdf->WriteHTML(build_table($rows_sql));
    $mpdf->Output();
})->bind('chat_downloadList');




function sendmail($id,$app)
{
    $find_sql = "SELECT c.`c_id`,s.`s_start`, r.`r_firstname`,w.`w_presenter_name`,w.`w_sub_title`,r.`r_email`,c.`c_message`,w.`w_name`,c.`c_is_replied`,c.`c_reply_mail`,c.`c_datetime` FROM `chat` c INNER JOIN `register` r on r.`r_id`=c.`c_r_id` INNER JOIN `schedule` s on r.`r_s_id`=s.`s_id` INNER JOIN `webinar` w on w.`w_id`=s.`s_w_id` where `c_id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('chat_list'));
    }


try {
  
$json   = file_get_contents( 'http://ip-api.com/json/' ); // this one service we gonna use to obtain timezone by IP
// maybe it's good to add some checks (if/else you've got an answer and if json could be decoded, etc.)if($json){
$ipData = json_decode( $json, true);
if ($ipData['timezone']) {

$timezone = new DateTimeZone($ipData['timezone']);
$country= $ipData['country'];
}

} catch (Exception $e) {
$timezone =  new DateTimeZone("asia/karachi");
$country= "Pakistan";

}
$UTC = new DateTimeZone("UTC");


    $time = new DateTime($row_sql['s_start'],$UTC);
    $time->setTimezone( $timezone);

    $wtime= $time->format(DateTime::COOKIE);

    $WEBINAR_REPLY_ADDRSS="webinar@samex.com";


    //You can add your email.
    $setFrom = array($WEBINAR_REPLY_ADDRSS=>$row_sql['w_presenter_name']);

    $setTo = array($row_sql['r_email']=>  $row_sql['r_firstname']);

    $Subject = "Reply to your message on webinar - ".  $row_sql['w_sub_title'];
    $Body = "
<p>Hey ".$row_sql['r_firstname']." </p>
<p>Thanks for attend webinar -".$row_sql['w_sub_title']."!</p>
<p>

".$row_sql['c_reply_mail']."
</p>
<br>
-".$row_sql['w_presenter_name']."</p>
<br><br><br>

<p><i>
On ".$wtime." ".$row_sql['r_firstname']." &lt;".$row_sql['r_email']."&gt; wrote:
</i>
</p>
<blockquote class='gmail_quote' style='margin: 0px 0px 0px 0.8ex; border-left: 1px solid rgb(204, 204, 204); padding-left: 1ex;'>
<p>
".$row_sql['c_message']."
</blockquote>
 <br>
 <br>
 <br>"
 ;
    $message = \Swift_Message::newInstance()
            ->setSubject($Subject)
            ->setFrom($setFrom )
            ->setTo($setTo)
            ->setBody($Body,'text/html');
            
            //var_dump($app['swiftmailer.spool']->getMessages());
            if($app['mailer']->send($message)) 
            {
                return $Body;
            } 
            else{
                return false;
            }
            

}