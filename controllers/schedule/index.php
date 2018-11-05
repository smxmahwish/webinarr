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

$app->match('/schedule/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		's_id', 
		's_w_id', 
        's_start', 
        's_end', 
        's_add_date_time', 

    );
    
    $table_columns_type = array(
		'int(11)', 
		'int(11)', 
		'datetime',
        'datetime',
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
    
    $recordsTotal = $app['db']->fetchColumn("SELECT COUNT(*) FROM `schedule`" . $whereClause . $orderClause, array(), 0);
    
    $find_sql = "SELECT * FROM `schedule`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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
$app->match('/schedule/download', function (Symfony\Component\HttpFoundation\Request $request) use ($app) { 
    
    // menu
    $rowid = $request->get('id');
    $idfldname = $request->get('idfld');
    $fieldname = $request->get('fldname');
    
    if( !$rowid || !$fieldname ) die("Invalid data");
    
    $find_sql = "SELECT " . $fieldname . " FROM " . schedule . " WHERE ".$idfldname." = ?";
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



$app->match('/schedule', function () use ($app) {
    
	$table_columns = array(
		's_id', 
		's_w_id',
        's_start',
        's_end', 
		's_add_date_time', 

    );

    $primary_key = "s_id";	

    return $app['twig']->render('schedule/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('schedule_list');



$app->match('/schedule/create', function () use ($app) {
    
    $initial_data = array(
		's_w_id' => '', 
		's_date_time' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	//$form = $form->add('s_w_id', 'text', array('required' => true));
	$form = $form->add('s_date_time', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();
            $start=substr($data['s_date_time'],0,19);
            $end=substr($data['s_date_time'],22);

            $update_query = "INSERT INTO `schedule` (`s_w_id`, `s_start`,`s_end`) VALUES (?, ?,?)";
            $app['db']->executeUpdate($update_query, array($_POST['s_w_id'],$start,$end));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'schedule created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('schedule_list'));

        }
    }
    $find_sql="SELECT * FROM `webinar`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());


    return $app['twig']->render('schedule/create.html.twig', array(
        "form" => $form->createView(),"webinar"=>$rows_sql
    ));
        
})
->bind('schedule_create');



$app->match('/schedule/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `schedule` WHERE `s_id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('schedule_list'));
    }

    
    $initial_data = array(
		's_w_id' => $row_sql['s_w_id'], 
's_start' => $row_sql['s_start'], 
's_end' => $row_sql['s_end'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('s_w_id', 'hidden', array('required' => true));
    $form = $form->add('s_start', 'text', array('required' => true));

    $form = $form->add('s_end', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `schedule` SET `s_w_id` = ?, `s_start` = ? , `s_end` = ? WHERE `s_id` = ?";
            $app['db']->executeUpdate($update_query, array($_POST['s_w_id'],$data['s_start'],$data['s_end'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'schedule edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('schedule_edit', array("id" => $id)));

        }
    }
    $find_sql="SELECT * FROM `webinar`";
    $allwebinar = $app['db']->fetchAll($find_sql, array());


    return $app['twig']->render('schedule/edit.html.twig', array(
        "form" => $form->createView(),"webinar"=>$allwebinar,"old_w_id"=> $row_sql['s_w_id'],
        "id" => $id
    ));
        
})
->bind('schedule_edit');


$app->match('/schedule/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `schedule` WHERE `s_id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `schedule` WHERE `s_id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'schedule deleted!',
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

    return $app->redirect($app['url_generator']->generate('schedule_list'));

})
->bind('schedule_delete');



$app->match('/schedule/downloadList', function (Symfony\Component\HttpFoundation\Request $request) use($app){
    
    $table_columns = array(
		's_id', 
		's_w_id', 
		's_date_time', 

    );
    
    $table_columns_type = array(
		'int(11)', 
		'int(11)', 
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
     
    $find_sql = "SELECT ".$columns_to_select." FROM `schedule`";
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
})->bind('schedule_downloadList');



