<?php
/**
 * Blvd\View\Helper
 * 
 * @author
 * @version 
 */
namespace Blvd\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * View Helper
 */
class mngcategory extends AbstractHelper
{

    public function __invoke($form, $action, $id)
    {
        
        $form = $this->view->form;
        $form->setAttribute('action', $this->view->url('blvd', array('action' => $action, 'id'=>$id)));
        $form->prepare();
        
        echo $this->view->form()->openTag($form);// . "<br>";
        echo $this->view->formHidden($form->get('id'));
        echo $this->view->formRow($form->get('category')) . "<br>";
        echo $this->view->formRow($form->get('top'));// . "<br>";
        echo $this->view->formRow($form->get('bottom'));// . "<br>";
        echo $this->view->formRow($form->get('disabled')) . "<br>";
        echo "<br>";
        echo $this->view->formInput($form->get('submit'));// . "<br>";
        echo $this->view->form()->closeTag($form) . "<br>";

    }
}
