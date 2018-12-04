<?php
class UserController extends Controller
{

  public function __construct($arrParams)
  {
    parent::__construct($arrParams);
    $this->_templateObj->setFolderTemplate('default/main/');
    $this->_templateObj->setFileTemplate('index.php');
    $this->_templateObj->setFileConfig('template.ini');
    $this->_templateObj->load();
  }

  public function indexAction()
  {
    $this->_view->_title = 'My Account';
    $this->_view->render('user/index');
  }

  public function cartAction()
  {
    $this->_view->_title = 'My Cart';
    $this->_view->Items = $this->_model->listItem($this->_arrParam, array('task' => 'books-in-cart'));
    $this->_view->render('user/cart');
  }

  public function orderAction()
  {
    $cart = Session::get('cart');
    $bookID = $this->_arrParam['book_id'];
    $price = $this->_arrParam['price'];

    if (empty($cart)) {
      $cart['quantity'][$bookID] = 1;
      $cart['price'][$bookID] = $price;
    } else {
      if (key_exists($bookID, $cart['quantity'])) {
        $cart['quantity'][$bookID] += 1;
        $cart['price'][$bookID] = $price * $cart['quantity'][$bookID];
      } else {
        $cart['quantity'][$bookID] = 1;
        $cart['price'][$bookID] = $price;
      }
    }

    Session::set('cart', $cart);
    URL::redirect('default', 'book', 'detail', array('book_id' => $bookID));
  }

  public function historyAction()
  {
    $this->_view->_title = 'History';
    $this->_view->Items = $this->_model->listItem($this->_arrParam, array('task' => 'history-cart'));
    $this->_view->render('user/history');
  }

  public function buyAction()
  {
    $this->_model->saveItem($this->_arrParam, array('task' => 'submit-cart'));
    URL::redirect('default', 'index', 'index');
  }

  public function orderCartAction()
  {
    // book_id
    // price
    $cart = Session::get('cart');
    $bookId = $this->_arrParam['book_id'];
    $price = $this->_arrParam['price'];
    if(empty($cart)) {
      $cart['quantity'][$bookId] = 1;
      $cart['price'][$bookId] = $price;
    } else {
      /* $cart = [
        'quantity' => [
          '1' => 1,
          '2' => 1,
          '3' => 1,
        ],
        'price' => [
          '1' => 10000,
          '2' => 20000,
          '3' => 30000,
        ]
      ]; */
      if(key_exists($bookId,$cart['price'])) {
        $cart['quantity'][$bookId] += 1;
        $cart['price'][$bookId] = $price * $cart['quantity'][$bookId];
      } else {
        $cart['quantity'][$bookId] = 1;
        $cart['price'][$bookId] = $price;
      }
    }

    Session::set('cart', $cart);
    Url::redirect('default','book','detail', ['book_id' => $bookId]);
  }
}

