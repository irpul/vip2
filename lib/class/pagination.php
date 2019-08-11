<?php

// This is a helper class to make paginating
// records easy.
class Pagination
{

	public $current_page;

	public $per_page;

	public $total_count;

	public function __construct ($page = 1, $per_page = 20, $total_count = 0)
	{
		$this->current_page = (int) $page;
		$this->per_page = (int) $per_page;
		$this->total_count = (int) $total_count;
	}

	public function offset ()
	{
		// Assuming 20 items per page:
		// page 1 has an offset of 0 (1-1) * 20
		// page 2 has an offset of 20 (2-1) * 20
		// in other words, page 2 starts with item 21
		return ($this->current_page - 1) * $this->per_page;
	}

	public function total_pages ()
	{
		return ceil($this->total_count / $this->per_page);
	}

	public function previous_page ()
	{
		return $this->current_page - 1;
	}

	public function next_page ()
	{
		return $this->current_page + 1;
	}

	public function has_previous_page ()
	{
		return $this->previous_page() >= 1 ? true : false;
	}

	public function has_next_page ()
	{
		return $this->next_page() <= $this->total_pages() ? true : false;
	}
	public function get_pagination($get=null)
	{
		$self = $_SERVER['PHP_SELF'];
		if($this->total_pages() <= 1)
			return null;
		if($get)
			$get = "&".$get;
		$page =  '<span id="ajax">';
		if($this->has_previous_page())
			$page .= '<a style="COLOR: #008000; TEXT-DECORATION: none" href="'.$self.'?page='.$this->previous_page().$get.'"><font class="pagination">&lt;</font></a>';
		
		$page .= '<a style="COLOR: #008000; TEXT-DECORATION: none" href="'.$self.'?page=1'.$get.'"><font class="pagination">1</font></a>';
		
		if($this->current_page > 4)
		{
			$page .= '...';
		}
		
		$i = $this->current_page-2;
		for($i = $i > 1 ? $i : 2; $i<$this->current_page+3 && $i <= $this->total_pages(); $i++)
		{
			$page .= '<a style="COLOR: #008000; TEXT-DECORATION: none" href="'.$self.'?page='.$i.$get.'"><font class="pagination">'.$i.'</font></a>';
		}
		if($this->current_page+2 < $this->total_pages())
		{
			if($this->current_page+3 != $this->total_pages())
				$page .= '...';
			$page .= '<a style="COLOR: #008000; TEXT-DECORATION: none" href="'.$self.'?page='.$this->total_pages().$get.'"><font class="pagination">'.$this->total_pages().'</font></a>';
		}
		if($this->has_next_page())
			$page .= '<a style="COLOR: #008000; TEXT-DECORATION: none" href="'.$self.'?page='.$this->next_page().$get.'"><font class="pagination">&gt;</font></a>';
		
		$page .= '</span>';
		return $page;
	}
}

?>