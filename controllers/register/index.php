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

$app->match('/register/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
    
    $orderClause = "";
    if($orderValue) {
        $orderClause = " ORDER BY ". $columns[(int)$orderValue['column']]['data'] . " " . $orderValue['dir'];
    }
    
    $table_columns = array(
		'r_id', 
		'r_s_id', 
		'r_live_link', 
		'r_attend_webinar', 
		'r_firstname', 
		'r_email', 
		'r_email_sent', 
		'r_datetime', 

    );
    
    $table_columns_type = array(
		'int(11)', 
		'int(11)', 
		'text', 
		'tinyint(4)', 
		'varchar(255)', 
		'int(255)', 
		'tinyint(4)', 
		'datetime', 

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
    
    $recordsTotal = $app['db']->fetchColumn("SELECT COUNT(*) FROM `register`" . $whereClause . $orderClause, array(), 0);
    
    $find_sql = "SELECT * FROM `register`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
        for($i = 0; $i < count($table_columns); $i++){

		if( $table_columns_type[$i] != "blob") {
				$rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
		} else {				if( !$row_sql[$table_columns[$i]] ) {
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
$app->match('/register/download', function (Symfony\Component\HttpFoundation\Request $request) use ($app) { 
    
    // menu
    $rowid = $request->get('id');
    $idfldname = $request->get('idfld');
    $fieldname = $request->get('fldname');
    
    if( !$rowid || !$fieldname ) die("Invalid data");
    
    $find_sql = "SELECT " . $fieldname . " FROM " . register . " WHERE ".$idfldname." = ?";
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



$app->match('/register', function () use ($app) {
    
	$table_columns = array(
		'r_id', 
		'r_s_id', 
		'r_live_link', 
		'r_attend_webinar', 
		'r_firstname', 
		'r_email', 
		'r_email_sent', 
		'r_datetime', 

    );

    $primary_key = "r_id";	

    return $app['twig']->render('register/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('register_list');



$app->match('/register/create', function () use ($app) {
    
    $initial_data = array(
		'r_s_id' => '', 
		'r_live_link' => '', 
		'r_attend_webinar' => '', 
		'r_firstname' => '', 
		'r_email' => '', 
		'r_email_sent' => '', 
		'r_datetime' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('r_s_id', 'text', array('required' => true));
	$form = $form->add('r_live_link', 'textarea', array('required' => true));
	$form = $form->add('r_attend_webinar', 'text', array('required' => true));
	$form = $form->add('r_firstname', 'text', array('required' => true));
	$form = $form->add('r_email', 'text', array('required' => true));
	$form = $form->add('r_email_sent', 'text', array('required' => true));
	$form = $form->add('r_datetime', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `register` (`r_s_id`, `r_live_link`, `r_attend_webinar`, `r_firstname`, `r_email`, `r_email_sent`, `r_datetime`) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['r_s_id'], $data['r_live_link'], $data['r_attend_webinar'], $data['r_firstname'], $data['r_email'], $data['r_email_sent'], $data['r_datetime']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'register created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('register_list'));

        }
    }

    return $app['twig']->render('register/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('register_create');



$app->match('/register/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `register` WHERE `r_id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('register_list'));
    }

    
    $initial_data = array(
		'r_s_id' => $row_sql['r_s_id'], 
		'r_live_link' => $row_sql['r_live_link'], 
		'r_attend_webinar' => $row_sql['r_attend_webinar'], 
		'r_firstname' => $row_sql['r_firstname'], 
		'r_email' => $row_sql['r_email'], 
		'r_email_sent' => $row_sql['r_email_sent'], 
		'r_datetime' => $row_sql['r_datetime'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('r_s_id', 'text', array('required' => true));
	$form = $form->add('r_live_link', 'textarea', array('required' => true));
	$form = $form->add('r_attend_webinar', 'text', array('required' => true));
	$form = $form->add('r_firstname', 'text', array('required' => true));
	$form = $form->add('r_email', 'text', array('required' => true));
	$form = $form->add('r_email_sent', 'text', array('required' => true));
	$form = $form->add('r_datetime', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `register` SET `r_s_id` = ?, `r_live_link` = ?, `r_attend_webinar` = ?, `r_firstname` = ?, `r_email` = ?, `r_email_sent` = ?, `r_datetime` = ? WHERE `r_id` = ?";
            $app['db']->executeUpdate($update_query, array($data['r_s_id'], $data['r_live_link'], $data['r_attend_webinar'], $data['r_firstname'], $data['r_email'], $data['r_email_sent'], $data['r_datetime'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'register edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('register_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('register/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('register_edit');


$app->match('/register/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `register` WHERE `r_id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `register` WHERE `r_id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'register deleted!',
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

    return $app->redirect($app['url_generator']->generate('register_list'));

})
->bind('register_delete');



$app->match('/register/downloadList', function (Symfony\Component\HttpFoundation\Request $request) use($app){
    
    $table_columns = array(
		'r_id', 
		'r_s_id', 
		'r_live_link', 
		'r_attend_webinar', 
		'r_firstname', 
		'r_email', 
		'r_email_sent', 
		'r_datetime', 

    );
    
    $table_columns_type = array(
		'int(11)', 
		'int(11)', 
		'text', 
		'tinyint(4)', 
		'varchar(255)', 
		'int(255)', 
		'tinyint(4)', 
		'datetime', 

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
     
    $find_sql = "SELECT ".$columns_to_select." FROM `register`";
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
})->bind('register_downloadList');



