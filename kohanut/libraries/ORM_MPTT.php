<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Modified Preorder Tree Traversal Class.
 *
 * @package ORM_MPTT
 * @author Mathew Davies
 * @author Kiall Mac Innes
 **/
abstract class ORM_MPTT_Core extends ORM
{
	/**
	 * Left Column
	 *
	 * @var string
	 **/
	protected $left_column = 'lft';
	
	/**
	 * Right Column
	 *
	 * @var string
	 **/
	protected $right_column = 'rgt';
	
	/**
	 * Level Column
	 *
	 * @var string
	 **/
	protected $level_column = 'lvl';
	
	/**
	 * View directory
	 *
	 * @var string
	 **/
	protected $directory = 'mptt';
	
	/**
	 * View file
	 *
	 * @var string
	 **/
	protected $style = 'default';

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 **/
	public function __construct($id = NULL)
	{
		// Prepare the directory var...
		$this->directory = trim($this->directory, '/').'/';
		
		parent::__construct($id);
	}

	/**
	 * Locks the MPTT table.
	 *
	 * @access private
	 * @return void
	 **/
	private function lock()
	{
		$this->db->query('LOCK TABLE '.$this->table_name.' WRITE');
	}
	
	/**
	 * Unlocks the MPTT table.
	 *
	 * @access private
	 * @return void
	 **/
	private function unlock()
	{
		$this->db->query('UNLOCK TABLES');
	}

	/**
	 * Does the node have a child?
	 *
	 * $node = ORM::factory('table', 12)->has_children();
	 *
	 * if ($node)
	 * {
	 *	 print 'This node has a child.';
	 * }
	 *
	 * @access public
	 * @return boolean
	 **/
	public function has_children()
	{
		// If the gap between the left and right values is more than 1
		// then we know the node has children.
		return (($this->{$this->right_column} - $this->{$this->left_column}) > 1);
	}
	
	/**
	 * Is the current node a leaf node
	 * (Has no children)
	 *
	 * $node = ORM::factory('table', 12)->is_leaf();
	 *
	 * if ($node)
	 * {
	 *	 print 'This node is a leaf node.';
	 * }
	 *
	 * @access public
	 * @return boolean
	 **/
	public function is_leaf()
	{
		return !$this->has_children();
	}
	
	/**
	 * Is the current node a descendant of the supplied node
	 *
	 * @access public
	 * @param $target node Target Node
	 * @return boolean
	 * @author Gallery3
	 **/
	public function is_descendant($target)
	{
		return ($this->{$this->left_column} > $target->{$this->left_column} AND $this->{$this->right_column} < $target->{$this->right_column});
	}
	
	/**
	 * Is the current node a direct child of the supplied node
	 *
	 * @access public
	 * @param $target node Target Node
	 * @return boolean
	 **/
	public function is_child($target)
	{
		return ($this->parent->{$this->primary_key} === $target->{$this->primary_key});
	}
	
	/**
	 * Is the current node the direct parent of the supplied node
	 *
	 * @access public
	 * @param $target node Target Node
	 * @return boolean
	 **/
	public function is_parent($target)
	{
		return ($this->{$this->primary_key} === $target->parent->{$this->primary_key});
	}
	
	/**
	 * Is the current node a sibling of the supplied node
	 *
	 * @access public
	 * @param $target node Target Node
	 * @return boolean
	 **/
	public function is_sibling($target)
	{
		return ($this->parent->{$this->primary_key} === $target->parent->{$this->primary_key});
	}
	
	/**
	 * Is the current node a root node?
	 *
	 * $is_root = ORM::factory('table', 12)->is_root();
	 *
	 * if ($node)
	 * {
	 *	 print 'This node is a root node.';
	 * }
	 *
	 * @access public
	 * @return boolean
	 **/
	public function is_root()
	{
		return ($this->{$this->left_column} === 1);
	}
	
	/**
	 * Returns the root node
	 *
	 * $root = $this->root();
	 *
	 * @access protected
	 * @return object
	 **/
	protected function root()
	{
		return self::factory($this->object_name)->where($this->left_column, 1)->find();
	}
	
	/**
	 * Returns the parent node
	 *
	 * $node = $this->parent();
	 *
	 * @access protected
	 * @return object
	 **/
	protected function parent()
	{	
		// SELECT * FROM `table` WHERE `left` < 9 AND `right` > 10 ORDER BY `right` ASC LIMIT 1
		return self::factory($this->object_name)->where('`'.$this->left_column.'` < '.$this->{$this->left_column}.' AND `'.$this->right_column.'` > '.$this->{$this->right_column})->orderby($this->right_column, 'ASC')->find();
	}
	
	/**
	 * Returns the parents of this node
	 *
	 * $parents = $this->parents();
	 *
	 * @access public
	 * @param $root Include the root node or not
	 * @param $direction Direction to order the left column by
	 * @return object
	 **/
	public function parents($root = TRUE, $direction = 'ASC')
	{
		// SELECT * FROM `table` WHERE `lft` <= 9 AND `rgt` >= 10 AND id <> 6 ORDER BY `lft` ASC
		$result = self::factory($this->object_name)->where('`'.$this->left_column.'` <= '.$this->{$this->left_column}.' AND `'.$this->right_column.'` >= '.$this->{$this->right_column}.' AND '.$this->primary_key.' <> '.$this->{$this->primary_key})->orderby($this->left_column, $direction);
		
		if ( ! $root)
			$result->where('`'.$this->left_column.'` != 1');
			
		return $result;
	}
	
	/**
	 * Returns the children of this node
	 *
	 * $children = $this->children();
	 *
	 * @access public
	 * @param $self Include this node
	 * @param $direction Direction to order the left column by
	 * @return object
	 **/
	public function children($self = FALSE, $direction = 'ASC')
	{	
		$child_level = $this->{$this->level_column} + 1;
		
		if ($self === TRUE) {
			return self::factory($this->object_name)->where('`'.$this->left_column.'` >= '.$this->{$this->left_column}.' AND `'.$this->right_column.'` <= '.$this->{$this->right_column}.' AND '.$this->level_column.' = '.$child_level)->orderby($this->left_column, $direction);
		}
		
		return self::factory($this->object_name)->where('`'.$this->left_column.'` > '.$this->{$this->left_column}.' AND `'.$this->right_column.'` < '.$this->{$this->right_column}.' AND '.$this->level_column.' = '.$child_level)->orderby($this->left_column, $direction);
	}
	
	/**
	 * Returns the subtree of the currently loaded
	 * node
	 *
	 * $root = ORM::factory('table')->root()->find();
	 *
	 * $descendants = ORM::factory('table', $root->id)->descendants;
	 *
	 * @access public
	 * @param $self boolean Should it include this node?
	 * @return object
	 **/
	public function descendants($self = FALSE, $direction = 'ASC')
	{		
		if ($self === TRUE)
			return self::factory($this->object_name)->where('`'.$this->left_column.'` >= '.$this->{$this->left_column}.' AND `'.$this->right_column.'` <= '.$this->{$this->right_column})->orderby($this->left_column, $direction);
			
		return self::factory($this->object_name)->where('`'.$this->left_column.'` > '.$this->{$this->left_column}.' AND `'.$this->right_column.'` < '.$this->{$this->right_column})->orderby($this->left_column, $direction);
	}
	
	/**
	 * Returns the siblings of this node
	 *
	 * $siblings = $this->siblings();
	 *
	 * @access public
	 * @param $direction Direction to order the left column by
	 * @return object
	 **/
	public function siblings($self = FALSE, $direction = 'ASC')
	{	
		if ($self === TRUE)
			return self::factory($this->object_name)->where('`'.$this->left_column.'` > '.$this->parent->{$this->left_column}.' AND `'.$this->right_column.'` < '.$this->parent->{$this->right_column}.' AND '.$this->level_column.' = '.$this->{$this->level_column})->orderby($this->left_column, $direction);
		
		return self::factory($this->object_name)->where('`'.$this->left_column.'` > '.$this->parent->{$this->left_column}.' AND `'.$this->right_column.'` < '.$this->parent->{$this->right_column}.' AND '.$this->level_column.' = '.$this->{$this->level_column}.' AND '.$this->primary_key.' <> '.$this->{$this->primary_key})->orderby($this->left_column, $direction);
	}
	
	/**
	 * Returns a list of leaf nodes.
	 *
	 * $leaf_nodes = ORM::factory('table', 1)->leaves;
	 *
	 * @access public
	 * @return object
	 **/
	public function leaves()
	{
		return self::factory($this->object_name)->where('`'.$this->left_column.'` = (`'.$this->right_column.'` - 1)	AND `'.$this->left_column.'` >= '.$this->{$this->left_column}.' AND `'.$this->right_column.'` <= '.$this->{$this->right_column})->orderby($this->left_column, 'ASC');
	}
	
	/**
	 * Get Size
	 *
	 * Returns the current size of the node.
	 *
	 * @return void
	 **/
	protected function get_size()
	{
		return ($this->{$this->right_column} - $this->{$this->left_column}) + 1;
	}

	/**
	 * Create a gap in the tree to make room for a new node
	 *
	 * @access private
	 * @param $start integer Start position.
	 * @param $size integer The size of the gap (default is 2)
	 * @return void
	 **/
	private function create_space($start, $size = 2)
	{
		// Update the left values.
		$this->db->query('UPDATE '.$this->table_name.' SET `'.$this->left_column.'` = `'.$this->left_column.'` + '.$size.' WHERE `'.$this->left_column.'` >= '.$start);

		// Now the right.
		$this->db->query('UPDATE '.$this->table_name.' SET `'.$this->right_column.'` = `'.$this->right_column.'` + '.$size.' WHERE `'.$this->right_column.'` >= '.$start);
	}
	
	/**
	 * Closes a gap in a tree. Mainly used after a node has
	 * been removed.
	 *
	 * @access private
	 * @param $start integer Start position.
	 * @param $size integer The size of the gap (default is 2)
	 * @return void
	 **/
	private function delete_space($start, $size = 2)
	{
		// Update the left values.
		$this->db->query('UPDATE '.$this->table_name.' SET `'.$this->left_column.'` = `'.$this->left_column.'` - '.$size.' WHERE `'.$this->left_column.'` >= '.$start);
		
		// Now the right.
		$this->db->query('UPDATE '.$this->table_name.' SET `'.$this->right_column.'` = `'.$this->right_column.'` - '.$size.' WHERE `'.$this->right_column.'` >= '.$start);
	}
	
	/**
	 * Inserts a new node to the left of the first node.
	 *
	 * $parent = 12;
	 *
	 * $new = ORM::factory('table');
	 * $new->name = 'New Node';
	 * $new->insert_as_first_child($parent);
	 *
	 * @access public
	 * @param $target object | integer Node object or ID.
	 * @return void
	 **/
	public function insert_as_first_child($target)
	{
		// Insert should only work on new nodes.. if its already it the tree it needs to be moved!
		if ($this->loaded)
			return FALSE;
		
		// Lock the table.
		$this->lock();
		
		// If $target isn't an object we find the node with the ID
		if (!is_a($target, get_class($this)))
		{
			$target = self::factory($this->object_name, $target);
		}
		else
		{
			// Ensure we're using the latest version of $target
			$target->reload();
		}
		
		// Example : left = 1, right = 32

		// Values for the new node
		// Example : left = 2, right = 3
		$this->{$this->left_column}  = $target->{$this->left_column} + 1;
		$this->{$this->right_column} = $this->{$this->left_column} + 1;
		$this->{$this->level_column} = $target->{$this->level_column} + 1;
		
		// Create some space for the new node.
		$this->create_space($this->{$this->left_column});
		
		// Save the new node.
		parent::save();
		
		// Unlock the table.
		$this->unlock();
		
		return $this;
	}
	
	/**
	 * Inserts a new node to the right of the last node.
	 *
	 * $parent = 12;
	 *
	 * $new = ORM::factory('table');
	 * $new->name = 'New Node';
	 * $new->insert_as_last_child($parent);
	 *
	 * @access public
	 * @param $target object | integer Node object or ID.
	 * @return void
	 **/
	public function insert_as_last_child($target)
	{
		// Insert should only work on new nodes.. if its already it the tree it needs to be moved!
		if ($this->loaded)
			return FALSE;
			
		// Lock the table.
		$this->lock();
		
		// If $target isn't an object we find the node with the ID
		if (!is_a($target, get_class($this)))
		{
			$target = self::factory($this->object_name, $target);
		}
		else
		{
			// Ensure we're using the latest version of $target
			$target->reload();
		}
		
		// Example : left = 1, right = 32

		// Values for the new node
		// Example : left = 32, right = 33
		$this->{$this->left_column}  = $target->{$this->right_column};
		$this->{$this->right_column} = $this->{$this->left_column} + 1;
		$this->{$this->level_column} = $target->{$this->level_column} + 1;
		
		// Create some space for the new node.
		$this->create_space($this->{$this->left_column});
		
		// Save the new node.
		parent::save();
		
		// Unlock the table.
		$this->unlock();
		
		return $this;
	}

	/**
	 * Inserts a new node as a previous sibling
	 *
	 * $target = 12;
	 *
	 * $new = ORM::factory('table');
	 * $new->name = 'New Node';
	 * $new->insert_as_prev_sibling($target);
	 *
	 * @access public
	 * @param $target object | integer Node object or ID.
	 * @return void
	 **/
	public function insert_as_prev_sibling($target)
	{
		// Insert should only work on new nodes.. if its already it the tree it needs to be moved!
		if ($this->loaded)
			return FALSE;
		
		// Lock the table.
		$this->lock();
		
		// If $target isn't an object we find the node with the ID
		if (!is_a($target, get_class($this)))
		{
			$target = self::factory($this->object_name, $target);
		}
		else
		{
			// Ensure we're using the latest version of $target
			$target->reload();
		}

		$this->{$this->left_column}  = $target->{$this->left_column};
		$this->{$this->right_column} = $this->{$this->left_column} + 1;
		$this->{$this->level_column} = $target->{$this->level_column};
		
		// Create some space for the new node.
		$this->create_space($this->{$this->left_column});
		
		// Save the new node.
		parent::save();
		
		// Unlock the table.
		$this->unlock();
		
		return $this;
	}

	/**
	 * Inserts a new node as a next sibling
	 *
	 * $target = 12;
	 *
	 * $new = ORM::factory('table');
	 * $new->name = 'New Node';
	 * $new->insert_as_next_sibling($target);
	 *
	 * @access public
	 * @param $target object | integer Node object or ID.
	 * @return void
	 **/
	public function insert_as_next_sibling($target)
	{
		// Insert should only work on new nodes.. if its already it the tree it needs to be moved!
		if ($this->loaded)
			return FALSE;
		
		// Lock the table.
		$this->lock();
		
		// If $target isn't an object we find the node with the ID
		if (!is_a($target, get_class($this)))
		{
			$target = self::factory($this->object_name, $target);
		}
		else
		{
			// Ensure we're using the latest version of $target
			$target->reload();
		}

		$this->{$this->left_column}  = $target->{$this->right_column} + 1;
		$this->{$this->right_column} = $this->{$this->left_column} + 1;
		$this->{$this->level_column} = $target->{$this->level_column};
		
		// Create some space for the new node.
		$this->create_space($this->{$this->left_column});
		
		// Save the new node.
		parent::save();
		
		// Unlock the table.
		$this->unlock();
		
		return $this;
	}
	
	/**
	 * Overloaded save method
	 *
	 * @return void
	 **/
	public function save()
	{
		if ($this->loaded === TRUE)
		{
			return parent::save();
		}
		
		return FALSE;
	}
	
	/**
	 * Removes a node and descendants if specified.
	 *
	 * $
	 *
	 * @access public
	 * @param $descendants boolean Remove the descendants?
	 * @return void
	 **/
	public function delete($descendants = TRUE)
	{
		// Lock the table
		$this->lock();
		
		// The descendants need to be removed.
		if ($descendants)
		{
			// Delete the node and it's descendants.
			$this->db->delete($this->table_name, '`'.$this->left_column.'` BETWEEN '.$this->{$this->left_column}.' AND '.$this->{$this->right_column});

			// Close the gap
			$this->delete_space($this->{$this->left_column}, $this->get_size());
		}
		// The descendants need to be moved up a level.
		else
		{
			/* Im sure theres a better way to do this...
			 * But i can't think of a good reason to do this,
			 * so no time or effort is being put into it!
			 * Patches accepted :P */
			
			$children = $this->children('DESC');
			
			foreach ($children as $child)
			{
				$child->move_to_next_sibling($this);
			}
			
			$this->delete();
		}

		// Unlock the table.
		$this->unlock();
	}

	/**
	 * Overloads the select_list method to
	 * support indenting.
	 *
	 * @param $key string First table column
	 * @param $val string Second table column
	 * @param $indent string Use this character for indenting
	 * @return void
	 **/
	public function select_list($key = NULL, $val = NULL, $indent = NULL)
	{
		if (is_string($indent))
		{
			if ($key === NULL)
			{
				// Use the default key
				$key = $this->primary_key;
			}
	
			if ($val === NULL)
			{
				// Use the default value
				$val = $this->primary_val;
			}
			
			$result = $this->load_result(TRUE);
			
			$array = array();
			foreach ($result as $row)
			{
				$array[$row->$key] = str_repeat($indent, $row->{$this->level_column}).$row->$val;
			}
			
			return $array;
		}

		return parent::select_list($key, $val);
	}
	

	
	/**
	 * Move to First Child
	 *
	 * This moves the current node to the first child of the target node.
	 *
	 * @param $target object | integer Target Node
	 * @return void
	 **/
	public function move_to_first_child($target)
	{
		// Lock the table
		$this->lock();	
		
		// Move should only work on nodes that are already in the tree.. if its not already it the tree it needs to be inserted!
		if (!$this->loaded)
			return FALSE;

		// Make sure we have the most uptodate version of this AFTER we lock
		$this->reload(); // This should *probably* go into $this->lock();
		
		// Find the target node properties.
		if (!is_a($target, get_class($this)))
		{
			$target = self::factory($this->table_name, $target);
		}
		
		// New Left Value.
		$new_left = $target->{$this->left_column} + 1;
		
		// Determine the level difference between source and target.
		$level_offset = $target->{$this->level_column} - $this->{$this->level_column} + 1;
		
		// Move
		$this->move($new_left, $level_offset);
		
		// Unlock the table.
		$this->unlock();

		return $this;
	}
	
	/**
	 * Move to Last Child
	 *
	 * This moves the current node to the last child of the target node.
	 *
	 * @param $target object | integer Target Node
	 * @return void
	 **/
	public function move_to_last_child($target)
	{
		// Lock the table
		$this->lock();
		
		// Move should only work on nodes that are already in the tree.. if its not already it the tree it needs to be inserted!
		if (!$this->loaded)
			return FALSE;
			
		// Make sure we have the most uptodate version of this AFTER we lock
		$this->reload(); // This should *probably* go into $this->lock();
		
		// Find the target node properties.
		if (!is_a($target, get_class($this)))
		{
			$target = self::factory($this->table_name, $target);
		}
		
		// New Left Value.
		$new_left = $target->{$this->right_column};
		
		// Determine the level difference between source and target.
		$level_offset = $target->{$this->level_column} - $this->{$this->level_column} + 1;
		
		// Move
		$this->move($new_left, $level_offset);
		
		// Unlock the table.
		$this->unlock();
		
		return $this;
	}
	
	/**
	 * Move to Previous Sibling.
	 *
	 * This moves the current node to the previous sibling of the target node.
	 *
	 * @param $target object | integer Target Node
	 * @return void
	 **/
	public function move_to_prev_sibling($target)
	{
		// Move should only work on nodes that are already in the tree.. if its not already it the tree it needs to be inserted!
		if (!$this->loaded)
			return FALSE;
		
		// Lock the table
		$this->lock();
		
		// Make sure we have the most upto date version of this AFTER we lock
		$this->reload(); // This should *probably* go into $this->lock();
		
		// Find the target node properties.
		if (!is_a($target, get_class($this)))
		{
			$target = self::factory($this->table_name, $target);
		}
		
		// New Left Value.
		$new_left = $target->{$this->left_column};
		
		// Determine the level difference between source and target.
		$level_offset = $target->{$this->level_column} - $this->{$this->level_column};
		
		// Move
		$this->move($new_left, $level_offset);
		
		// Unlock the table.
		$this->unlock();
		
		return $this;
	}
	
	/**
	 * Move to Next Sibling.
	 *
	 * This moves the current node to the next sibling of the target node.
	 *
	 * @param $target object | integer Target Node
	 * @return void
	 **/
	public function move_to_next_sibling($target)
	{
		// Move should only work on nodes that are already in the tree.. if its not already it the tree it needs to be inserted!
		if (!$this->loaded)
			return FALSE;
		
		// Lock the table
		$this->lock();
		
		// Make sure we have the most upto date version of this AFTER we lock
		$this->reload(); // This should *probably* go into $this->lock();
		
		// Find the target node properties.
		if (!is_a($target, get_class($this)))
		{
			$target = self::factory($this->table_name, $target);
		}
		
		// New Left Value.
		$new_left = $target->{$this->right_column} + 1;
		
		// Determine the level difference between source and target.
		$level_offset = $target->{$this->level_column} - $this->{$this->level_column};
		
		// Move
		$this->move($new_left, $level_offset);
		
		// Unlock the table.
		$this->unlock();
		
		return $this;
	}
	
	/**
	 * Move
	 *
	 * @param $new_left integer The value for the new left.
	 * @return void
	 **/
	protected function move($new_left, $level_offset)
	{
		// Lock the table
		$this->lock();		
		
		// Size of current node.
		// For example left = 5, right = 6
		// (right - left) + 1
		// Result = 2
		$size = $this->get_size();
		
		// New right value
		$new_right = ($new_left + $size) - 1;

		// Now we create the new gap
		$this->create_space($new_left, $size);

		$this->reload();
		
		// This is how much we move our current node by.
		// This needs checking.
		$offset = ($new_left - $this->{$this->left_column});
		
		// Update the values.
		// UPDATE `$this->table_name` SET `left` = `left` + $offset, `right` = `right` + $offset WHERE `left` >= $this->{$this->left_column} AND `right` <= $this->{$this->right_column}
		$this->db->query('UPDATE '.$this->table_name.' SET `'.$this->left_column.'` = `'.$this->left_column.'` + '.$offset.', `'.$this->right_column.'` = `'.$this->right_column.'` + '.$offset.'

		, `'.$this->level_column.'` = `'.$this->level_column.'` + '.$level_offset.'
		
		WHERE `'.$this->left_column.'` >= '.$this->{$this->left_column}.' AND `'.$this->right_column.'` <= '.$this->{$this->right_column});
		
		// Now we close the old gap
		$this->delete_space($this->{$this->left_column}, $size);
		
		// Unlock the table.
		$this->unlock();
	}
	
	/**
	 *
	 * @access public
	 * @param $column - Which field to get.
	 * @return mixed
	 **/
	public function __get($column)
	{
		switch ($column)
		{
			case 'parent':
				return $this->parent();
			case 'parents':
				return $this->parents()->find_all();
			case 'children':
				return $this->children()->find_all();
			case 'first_child':
				return $this->children()->find();
			case 'last_child':
				return $this->children('DESC')->find();
			case 'siblings':
				return $this->siblings()->find_all();
			case 'root':
				return $this->root();
			case 'leaves':
				return $this->leaves()->find_all();
			case 'descendants':
				return $this->descendants()->find_all();
			default:
				return parent::__get($column);
		}
	}
	
	/**
	 * Verify the tree is in good order 
	 * 
	 * This functions speed is irrelevant - its really only for debugging and unit tests
	 * 
	 * @todo Look for any nodes no longer contained by the root node.
	 * @todo Ensure every node has a path to the root via ->parents();
	 * @access public
	 * @return boolean
	 **/
	public function verify_tree()
	{
		if ( ! $this->is_root())
			throw new Exception('verify_tree() can only be used on root nodes');
		
		$end = $this->{$this->right_column};

		// Look for nodes no longer contained by the root node.
		$extra_nodes = self::factory($this->object_name)->where($this->left_column.' > ', $end)->orwhere($this->right_column.' > ', $end)->find_all();
		
		// Out of bounds.
		if ($extra_nodes->count() > 0)
			return FALSE;
		
		$i = 0;
		
		while ($i < $end)
		{
			$i++;
			$nodes = self::factory($this->object_name)->where($this->left_column, $i)->orwhere($this->right_column, $i)->find_all();
			
			// 2 or more nodes have the same left or right value.
			if ($nodes->count() != 1)
				return FALSE;
			
			// The left value is bigger than the right, impossible!
			if ($nodes->current()->{$this->left_column} >= $nodes->current()->{$this->right_column})
				return FALSE;
			
			// Tests that only apply to non root nodes. 
			if ( ! $nodes->current()->is_root())
			{
				$parent_level = $nodes->current()->parent->{$this->level_column};
				$our_level = $nodes->current()->{$this->level_column};
				
				if ($parent_level + 1 != $our_level)
					return FALSE;
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Generates the HTML for this node's descendants
	 *
	 * @param   string  pagination style
	 * @param   boolean include this node or not.
	 * @return  string  pagination html
	 */
	public function render_descendants($style = NULL, $self = FALSE, $direction = 'ASC')
	{
		$nodes = $this->descendants($self, $direction)->find_all();
		
		if ($style === NULL)
		{
			// Use default style
			$style = $this->style;
		}

		// Return rendered pagination view
		return View::factory($this->directory.$style, array('nodes' => $nodes,'level_column' => $this->level_column))->render();
	}
	
	/**
	 * Generates the HTML for this node's children
	 *
	 * @param   string  pagination style
	 * @param   string  direction
	 * @return  string  pagination html
	 */
	public function render_children($style = NULL, $self = FALSE, $direction = 'ASC')
	{
		$nodes = $this->children($self, $direction)->find_all();
		
		if ($style === NULL)
		{
			// Use default style
			$style = $this->style;
		}

		// Return rendered pagination view
		return View::factory($this->directory.$style, array('nodes' => $nodes,'level_column' => $this->level_column))->render();
	}
} // END class ORM_MPTT_Core