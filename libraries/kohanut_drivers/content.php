<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohanut_content_Driver extends Kohanut_Element {

    protected $id;

    public function __construct($id=null) {
        parent::__construct();
        $this->id = $id;
    }

    public function add($page,$area) {
        // create the object in the element_contents table via the Kohanut_element_Model
        $element = new Kohanut_element_Model('content');
        // put some default stuff in
        $element->type = 'html';
        $element->code = '<p>Content</p>';
        $element->html = '<p>Content</p>';
        $element->save();
        
        // Now create the row to tie the freshly created content element to the page and area
        $pagecontent = ORM::factory('pagecontent');
        $pagecontent->page_id = $page;
        $pagecontent->area_id = $area;
        $pagecontent->order = 1;   // <<<<=========== BROKEN =====================================================
        $pagecontent->elementtype_id = 1;
        $pagecontent->element_id = $element->id;
        $pagecontent->save();
        
        // now send them to edit that element
        url::redirect('/admin/pages/edit_element/' . $pagecontent->id);
    }

    public function render() {
        // create an element_model
        $element = new Kohanut_element_Model("content");
        
        $element->find($this->id);
        
        if (!$element->loaded)
            echo "Could not find element_contents id: $element not loaded :(";
        else
            echo $element->html;
    }
    
    public function edit($id) {
        
        $element = new Kohanut_element_Model('content');
        $element->find($id);
        $types = array(
            'html' => 'Html',
            'markdown' => 'Markdown'
        );
        
        $view = new View('kohanut/admin/empty');
        $formo = Formo::factory()
            ->add_select('type',$types)
            ->add('textarea','code')->value($element->code)
            ->add('submit')
            
            ;
        
        if ($formo->validate()) {
            // save the type and raw code
            $element->type = $formo->type->value;
            $element->code = $formo->code->value;
            
            // create the html code based on the type
            if ($formo->type->value == "html")
            {
                $element->html = $formo->code->value;
            }
            else if ($formo->type->value == "markdown")
            {
                echo "need to write markdown stuff";
            }
            
            // save
            $element->save();            
        }
        
        
        $view->body = $formo;
        return $view;
    }
    
}

?>