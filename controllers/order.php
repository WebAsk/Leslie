<?php

/* 
 * Copyright (C) 2017 WebAsk di Francesco Luti
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace framework\controllers;

class order extends account {
   
    protected $costumer;
    protected $shipping;
    protected $payment;
    
    protected $type;

    function __construct($view, $model = '\\framework\\models\\account') {
        
        parent::__construct($view, $model);
        
        $this->type = $this->model->selone('SELECT id, prefix FROM item_types WHERE plural = "orders"');
        if (empty($this->type['prefix'])) { $this->type['prefix'] = 'items'; }
        
        $this->costumer['prefix'] = $this->model->selone('SELECT prefix FROM item_types WHERE accounts = 1', \PDO::FETCH_COLUMN);
        if (empty($this->costumer['prefix'])) { $this->costumer['prefix'] = 'items'; }
        
        $this->shipping['prefix'] = $this->model->selone('SELECT prefix FROM item_types WHERE plural = "shippings"', \PDO::FETCH_COLUMN);
        if (empty($this->shipping['prefix'])) { $this->shipping['prefix'] = 'items'; }
        
        $this->payment['prefix'] = $this->model->selone('SELECT prefix FROM item_types WHERE plural = "payments"', \PDO::FETCH_COLUMN);
        if (empty($this->payment['prefix'])) { $this->payment['prefix'] = 'items'; }
        
    }

    function summary () {
        
        if (!isset($_REQUEST['payment'], $_REQUEST['shipping'])) {
           header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/cart'); die;
        }
        
        $this->view->title = 'Order';
        $this->view->description = 'Order summary';
        
        $this->view->costumer = array();
        $this->view->costumer['fields'] = $this->model->sel(
            'SELECT t.label, f.value'
            . ' FROM ' . $this->costumer['prefix'] . '_list AS li'
                . ', items_fields AS f'
                . ', field_types AS t'
            . ' WHERE li.id = ' . $this->user['content']
            . ' AND f.id_content = li.id'
            . ' AND (f.id_item_type = li.id_type OR f.id_item_type = 0)'
            . ' AND t.id = f.id_type'
            . ' AND t.site = 1'
            . ' ORDER BY t.order'
        );

        $this->view->payment = $this->model->selectnoview(
            'SELECT li.id, li.code, la.title'
            . ' FROM ' . $this->payment['prefix'] . '_list AS li,'
            . ' ' . $this->payment['prefix'] . '_languages AS la'
            . ' WHERE li.code = :payment'
            . ' AND la.id_content = li.id', 
            ['payment' => $_REQUEST['payment']]
        );
        
        $this->view->shipping = $this->model->selectnoview(
            'SELECT li.code, la.title, f.value AS cost'
            . ' FROM ' . $this->shipping['prefix'] . '_list AS li'
                . ' LEFT JOIN items_fields AS f ON f.id_content = li.id AND f.id_item_type = li.id_type'
            . ', ' . $this->shipping['prefix'] . '_languages AS la'
            . ' WHERE li.code = :shipping'
            . ' AND la.id_content = li.id',
            ['shipping' => $_REQUEST['shipping']]
        );
        
        $this->view->name = 'order' . DIRECTORY_SEPARATOR . 'summary';
        
    }
    
    /*
    function paypal () {
        
        function_exists('curl_version') or die('curl not installed');
        
        isset($_POST['paypal']) or die('paypal post data error');
        
        $GLOBALS['PROJECT']['DEBUG'] = 0;

        require_once FRAMEWORK_PATH_LIB . '/paypal/utilFunctions.php';
        require_once FRAMEWORK_PATH_LIB . '/paypal/paypalFunctions.php';

        $checkout_token = getAccessToken();

        $_SESSION['access_token'] = $checkout_token;
        //print_r($_SESSION['access_token']); exit;
        
        $post = array();
        $post['payer']['payment_method'] = 'paypal';
        $post['intent'] = 'sale';
        $post['redirect_urls']['cancel_url'] = $GLOBALS['PROJECT']['URL']['BASE'] . '/cart?alert=danger&message=' . urlencode('payment aborted');
        $post['redirect_urls']['return_url'] = $GLOBALS['PROJECT']['URL']['BASE'] . '/order/register/' . $_REQUEST['payment'] . '/' . $_REQUEST['shipping'];
        $post = array_merge($post, $_POST['paypal']);

        $checkout_json = json_encode($post);
        //print_r($checkout_json); exit;
        $approval_url = getApprovalURL($checkout_token, $checkout_json). "&useraction=commit";
        
        header("location: " . $approval_url);
        die;
        
    }
    */
    
    function payment ($service = 'paypal') {
        
        switch ($service) {
            
            case 'paypal':
        
                function_exists('curl_version') or die('curl not installed');

                isset($_POST['paypal']) or die('paypal post data error');

                require_once FRAMEWORK_PATH_LIB . '/paypal/utilFunctions.php';
                require_once FRAMEWORK_PATH_LIB . '/paypal/paypalFunctions.php';

                $checkout_token = getAccessToken();

                $_SESSION['access_token'] = $checkout_token;
                //print_r($_SESSION['access_token']); exit;

                $post = array();
                $post['payer']['payment_method'] = 'paypal';
                $post['intent'] = 'sale';
                $post['redirect_urls']['cancel_url'] = $GLOBALS['PROJECT']['URL']['BASE'] . '/cart?alert=danger&message=' . urlencode('payment aborted');
                $post['redirect_urls']['return_url'] = $GLOBALS['PROJECT']['URL']['BASE'] . '/order/register/' . $_REQUEST['payment'] . '/' . $_REQUEST['shipping'];
                $post = array_merge($post, $_POST['paypal']);

                $checkout_json = json_encode($post);
                //print_r($checkout_json); exit;
                $approval_url = getApprovalURL($checkout_token, $checkout_json). "&useraction=commit";
                header("Location: " . $approval_url);
                $GLOBALS['PROJECT']['DEBUG'] = 0;
                die;
            
            default:
                
        }
        
    }

    function register ($payment = null, $shipping = null) {
        
        //echo $payment . '<hr>'; echo $shipping; exit;
        
        if (empty($_COOKIE['cart'])) {
           header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/cart?alert=danger&message=' . urlencode('cart error')); exit;
        }

        if (isset($_REQUEST['payment'])) { $payment = $_REQUEST['payment']; }
        $payment = $this->model->selectnoview('SELECT li.id, li.name, li.id_type, la.title, la.intro, la.description FROM ' . $this->payment['prefix'] . '_list AS li, ' . $this->payment['prefix'] . '_languages AS la WHERE li.code = :payment AND la.id_content = li.id', ['payment' => $payment]);
        //echo '<pre>'; print_r($payment); echo '</pre>'; exit;
        
        if (empty($payment)) {
           header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/cart?alert=danger&message=' . urlencode('payment error')); exit;
        }
        
        if (isset($_REQUEST['shipping'])) { $shipping = $_REQUEST['shipping']; }
        $shipping = $this->model->selectnoview('SELECT li.id, li.id_type, la.title, la.intro, la.description FROM ' . $this->shipping['prefix'] . '_list AS li, ' . $this->shipping['prefix'] . '_languages AS la WHERE li.code = :shipping AND la.id_content = li.id', ['shipping' => $shipping]);
        //echo '<pre>'; print_r($shipping); echo '</pre>'; exit;
        
        if (empty($shipping)) {
           header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/cart?alert=danger&message=' . urlencode('shipping error')); exit;
        }
        
        if (isset($_REQUEST['costumer'])) {
           $costumer = $this->model->selectnoview('SELECT li.id, li.name, li.id_type, users.email FROM ' . $this->costumer['prefix'] . '_list AS li, users WHERE li.code = :costumer_code AND users.content = li.id', array('costumer_code' => $_REQUEST['costumer']));
        } else {
           $costumer = $this->model->selectnoview('SELECT li.id, li.name, li.id_type, users.email FROM ' . $this->costumer['prefix'] . '_list AS li, users WHERE li.id = :user_content_id AND users.content = li.id', array('user_content_id' => $this->user['content']));
        }
        //echo '<pre>'; print_r($costumer); echo '</pre>';

        if (empty($costumer)) {
           header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/cart?alert=danger&message=' . urlencode('costumer data error')); exit;
        }
        
        $cart = unserialize($_COOKIE['cart']);
        //echo '<pre>'; print_r($cart); echo '</pre>'; die;

        $shipping_info = '<p>' . htmlentities($shipping['intro']) . '</p>';
        $payment_info = '<p>' . htmlentities($payment['intro']) . '</p>';

        if (stristr($payment['name'], 'paypal') && isset($_GET['paymentId'], $_GET['token'], $_GET['PayerID'])) {

           require_once FRAMEWORK_PATH_LIB . '/paypal/utilFunctions.php';
           require_once FRAMEWORK_PATH_LIB . '/paypal/paypalFunctions.php';
           
           $pay = doPayment(filter_input( INPUT_GET, 'paymentId', FILTER_SANITIZE_STRING ), filter_input( INPUT_GET, 'PayerID', FILTER_SANITIZE_STRING ), NULL);
           //print_r($pay); die;
           if ($pay['http_code'] == 200 || $pay['http_code'] == 201) {

              $payment_info .= '<ul>' . PHP_EOL;
              $payment_info .= '<li>Stato: ' . $pay['json']['state'] . '</li>' . PHP_EOL;
              $payment_info .= '<li>ID pagamento: ' . $pay['json']['id'] . '</li>' . PHP_EOL;
              $payment_info .= '<li>ID transazione: ' . $pay['json']['transactions'][0]['related_resources'][0]['sale']['id'] . '</li>' . PHP_EOL;
              $payment_info .= '<li>ID pagante: ' . $_GET['PayerID'] . '</li>' . PHP_EOL;
              $payment_info .= '<li>Importo: ' . $pay['json']['transactions'][0]['amount']['total'] . ' &euro;</li>' . PHP_EOL;
              $payment_info .= '</ul>' . PHP_EOL;
              $state_id = $this->model->query('SELECT id FROM item_states WHERE value = "paid"')->fetch(\PDO::FETCH_COLUMN) or die('order state paid id error');

           } else {
               
              header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/cart?alert=danger&message=' . urlencode('payment error')); die;
              
           }
           
        } else {
            
           $state_id = $this->model->query('SELECT id FROM item_states WHERE value = "pending"')->fetch(\PDO::FETCH_COLUMN) or die('order state pending id error');

           $payment_info .= $payment['description'];
           
        }

        $costumer['info'] = $this->model->query(
            'SELECT field_types.name, field_types.label, items_fields.value'
            . ' FROM items_fields, field_types'
            . ' WHERE (items_fields.id_item_type = ' . $costumer['id_type'] . ' OR items_fields.id_item_type = 0)'
            . ' AND items_fields.id_content = ' . $costumer['id']
            . ' AND field_types.id = items_fields.id_type'
            . ' AND field_types.site = 1'
            . ' ORDER BY field_types.`order`'
        )->fetchAll(\PDO::FETCH_ASSOC) or die('costumer info error');
        //print_r($costumer['info']); die;
        
        include_once FRAMEWORK_PATH_LIB . '/fpdf/fpdf.php';

        $pdf = new \FPDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->SetTextColor(32);

        $costumer_info = '<ul>'. PHP_EOL;
        
        foreach ($costumer['info'] as $info) {
            
            $pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', $info['value']), 0, 1, 'L');
            
            $costumer_info .= '<li>' . ucfirst(\leslie::translate($info['label'])) . ': ' . htmlentities($info['value']) . '</li>'. PHP_EOL;
            
        }
        //print_r($invoice); die;
        $costumer_info .= '<li>Email: <a href="mailto:' . $costumer['email'] . '">' . $costumer['email'] . '</a></li>'. PHP_EOL;
        $costumer_info .= '</ul>'. PHP_EOL;

        $orders = $this->model->selone("SELECT * FROM item_types WHERE plural = 'orders'");

        $order_code = $this->model->generate_code($this->type['prefix'] . '_list');
        $order_name = $costumer['name'] . ' ' . date('d/m/Y H:i');
        $this->model->insert($this->type['prefix'] . '_list', array(
            'id_user' => $this->user['id'],
            'id_type' => $this->type['id'],
            'name' => $order_name,
            'code' => $order_code,
            'state' => $state_id
        ));
        $order_id = $this->model->lastInsertId();

        $this->model->insert($this->type['prefix'] . '_joints', array(
            'type' => $payment['id_type'],
            'id_content' => $order_id,
            'id_joint' => $payment['id'],
            'active' => 1,
            'insert' => date('Y-m-d H:i:s')
        ));
        
        $this->model->insert($this->type['prefix'] . '_joints', array(
            'type' => $shipping['id_type'],
            'id_content' => $order_id,
            'id_joint' => $shipping['id'],
            'active' => 1,
            'insert' => date('Y-m-d H:i:s')
        ));

        $pdf->SetY(10);
        
        $pdf->Cell(0, 5, $GLOBALS['COMPANY']['NAME'], 0, 2, 'R');
        $pdf->Cell(0, 5,$GLOBALS['COMPANY']['ADDRESS'], 0, 2, 'R');
        $pdf->Cell(0, 5, 'P. IVA: ' . $GLOBALS['COMPANY']['VAT']['NUMBER'], 0, 2, 'R');
        $pdf->Cell(0, 5,$GLOBALS['COMPANY']['EMAIL'], 0, 2, 'R');
        $pdf->Cell(0, 5,'Tel: '.$GLOBALS['COMPANY']['TEL'], 0, 1, 'R');
        
        $pdf->Ln();
        
        $doc_name = ucfirst(\leslie::translate($GLOBALS['COMPANY']['VAT']['DOCUMENT']));
        $pdf->Cell(0, 6, $doc_name . ' ' . \leslie::translate('number') . ': ' . $order_id, 0, 1, 'L', true);
        $pdf->Ln();
        
        $pdf->Cell(0, 6, $doc_name . ' ' . \leslie::translate('date') . ': ' . date('d/m/Y'), 0, 1, 'L', true);
        $pdf->Ln();
        
        $pdf->Cell(130, 7, \leslie::translate('Product'), 1, 0, 'L');
        $pdf->Cell(30, 7, iconv('UTF-8', 'windows-1252', \leslie::translate('Quantity')), 1, 0, 'C');
        $pdf->Cell(30, 7, \leslie::translate('Price') . ' (' . chr(128) . ')', 1, 1, 'C');
        
        $order_list = '<ul>' . PHP_EOL;
        $total_price = 0;
        
        foreach ($cart as $item) {
            
            $product = $this->model->selectnoview('SELECT id, id_type FROM items_list WHERE code = :code', array('code' => $item['code']));
            
            for ($i = 1; $i <= $item['quantity']; $i++) {
                
                $this->model->insert($this->type['prefix'] . '_joints', [
                    'type' => $product['id_type'],
                    'id_content' => $order_id,
                    'id_joint' => $product['id'],
                    'active' => 1,
                    'insert' => date('Y-m-d H:i:s')
                ]);
            }
            
            $subtotal = number_format($item['price'] * $item['quantity'], 2, ',', '.');
            $total_price += $item['price'] * $item['quantity'];
            
            $pdf->Cell(130,7, iconv('UTF-8', 'windows-1252', $item['title']) . chr(32) . iconv('UTF-8', 'windows-1252', $item['size']['title']), 1, 0, 'L', 0);
            $pdf->Cell(30, 7, $item['quantity'], 1, 0, 'C', 0);
            $pdf->Cell(30, 7, $subtotal, 1, 1, 'C', 0);

            $order_list .= '<li><strong>' . htmlentities($item['title']) . '</strong>' . PHP_EOL;
            $order_list .= '<ul>' . PHP_EOL;
            $order_list .= '<li>Taglia: ' . htmlentities($item['size']['title']) . '</li>' . PHP_EOL;
            $order_list .= '<li>Prezzo unitario: ' . number_format($item['price'], 2, ',', '.') . ' &euro;</li>' . PHP_EOL;
            $order_list .= '<li>Quantit&agrave;: ' . $item['quantity'] . '</li>' . PHP_EOL;
            $order_list .= '<li>Subtotale: ' . $subtotal. ' &euro;</li>' . PHP_EOL;
            
            $order_list .= '</ul>' . PHP_EOL;
            
            $size = $this->model->selectnoview('SELECT id, id_type FROM items_list WHERE code = :code', array('code' => $item['size']['code']));
        
            $this->model->insert($this->type['prefix'] . '_joints', array(
                'type' => $size['id_type'],
                'id_content' => $order_id,
                'id_joint' => $size['id'],
                'active' => 1,
                'insert' => date('Y-m-d H:i:s')
            ));
        }
        
        $order_total = number_format($total_price, 2, ',', '.');
        
        //$order_list .= '<li><strong>Totale ordine</strong>: ' . $order_total . ' &euro;</li>' . PHP_EOL;
        
        $order_list .= '</ul>' . PHP_EOL;

        $payment_info .= '<p>Importo totale dell\'ordine: <strong>' . $order_total . ' &euro;</strong>.</p>';

        $pdf->Cell(160, 7, 'IVA ' . $GLOBALS['COMPANY']['VAT']['RATE'] . ' ' . chr(37), 1, 0, 'R', 0);
        $pdf->Cell(30, 7,  number_format($total_price / 100 * $GLOBALS['COMPANY']['VAT']['RATE'], 2, ',', '.'), 1, 1, 'C', 0);
        $pdf->Cell(160, 7, 'Totale', 1, 0, 'R', 0);
        $pdf->Cell(30, 7, $order_total, 1, 0, 'C', 0);
        
        $invoice_name = \functions::string_to_url($GLOBALS['COMPANY']['VAT']['DOCUMENT'] . ' ' . $order_name) . '.pdf';
        $pdf->Output($GLOBALS['PROJECT']['PATHS']['DOCUMENTS'] . '/invoices/' . $invoice_name, 'F');
        
        $invoice_type = $this->model->selectnoview('SELECT id, prefix FROM item_types WHERE plural = "invoices"');
        if (empty($invoice_type['prefix'])) { $invoice_type['prefix'] = 'items'; }
        
        $this->model->insert($invoice_type['prefix'] . '_list', array(
            'id_user' => $this->user['id'],
            'id_type' => $invoice_type['id'],
            'name' => $invoice_name,
            'code' => $this->model->generate_code($invoice_type['prefix'] . '_list'),
            'state' => 0
        ));
        
        $invoice_id = $this->model->lastInsertId();
        
        $this->model->insert($invoice_type['prefix'] . '_languages', array(
           'id_content' => $invoice_id,
           'id_language' => 1,
           'title' => $doc_name . ' ' . $order_name,
           'insert' => date('Y-m-d H:i:s'),
           'update' => date('Y-m-d H:i:s')
        ));
        
        $this->model->insert($this->type['prefix'] . '_joints', array(
            'type' => $invoice_type['id'],
            'id_content' => $order_id,
            'id_joint' => $invoice_id,
            'active' => 1,
            'insert' => date('Y-m-d H:i:s')
        ));

        $this->model->insert($this->type['prefix'] . '_joints', array(
            'type' => $costumer['id_type'],
            'id_content' => $order_id,
            'id_joint' => $costumer['id'],
            'active' => 1,
            'insert' => date('Y-m-d H:i:s')
        ));

        $order_info = '<h3>Ordine</h3>';
        $order_info .= $order_list;
        $order_info .= '<p>Codice identificativo dell\'ordine: <strong>' . $order_code  . '</strong></p>';
        $order_info .= '<h3>Cliente</h3>';
        $order_info .= $costumer_info;
        if (!empty($_POST['note'])) {
            $order_info .= '<h3>Note</h3>';
            $order_info .= htmlentities($_POST['note']);
        }
        $order_info .= '<h3>Pagamento</h3>';
        $order_info .= $payment_info;
        $order_info .= '<h3>' . $doc_name . '</h3>';
        $order_info .= '<ul>';
        $order_info .= '<li><a href="'.$GLOBALS['PROJECT']['URL']['DOCUMENTS'].'/invoices/'.$invoice_name.'">'.$invoice_name.'</a></li>';
        $order_info .= '</ul>';
        $order_info .= '<h3>Spedizione</h3>';
        $order_info .= $shipping_info;

        $order_title = 'Ordine di ' . $costumer['name'] . ' del ' . date('d/m/Y H:i');
        $this->model->insert($this->type['prefix'] . '_languages', array(
           'id_content' => $order_id,
           'id_language' => 1,
           'title' => $order_title,
           'description' => $order_info,
           'insert' => date('Y-m-d H:i:s'),
           'update' => date('Y-m-d H:i:s')
           
        ));

        include_once FRAMEWORK_PATH_LIB . DIRECTORY_SEPARATOR . 'php-mailer' . DIRECTORY_SEPARATOR . 'class.phpmailer.php';
        include_once FRAMEWORK_PATH_LIB . DIRECTORY_SEPARATOR . 'php-mailer' . DIRECTORY_SEPARATOR . 'class.smtp.php';

        $mail = new \PHPMailer;

        $mail->isSMTP();
        $mail->Host = $GLOBALS['PROJECT']['SMTP']['HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $GLOBALS['PROJECT']['SMTP']['USERNAME'];
        $mail->Password = $GLOBALS['PROJECT']['SMTP']['PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $GLOBALS['PROJECT']['SMTP']['PORT'];
        $mail->setFrom($GLOBALS['COMPANY']['EMAIL'], $GLOBALS['PROJECT']['NAME']);

        $mail->addAddress($costumer['email'], $costumer['name']);
        $mail->addBCC($GLOBALS['COMPANY']['EMAIL'], $GLOBALS['PROJECT']['NAME']);
        $mail->AddAttachment($GLOBALS['PROJECT']['PATHS']['DOCUMENTS'] . '/invoices/' . $invoice_name);
        $mail->isHTML(true);

        $mail->Subject = $order_title;

        ob_start();
        include FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'email' . DIRECTORY_SEPARATOR . 'index.php';
        $template = ob_get_clean();

        $mail->Body = str_replace('{$message}', $order_info, $template);

        $mail->send();

        unset($_COOKIE['cart']);
        setcookie('cart', null, -1, '/');

        header('location: ' . \leslie::$href . '/account/orders'); exit;

    }
   
   
}
