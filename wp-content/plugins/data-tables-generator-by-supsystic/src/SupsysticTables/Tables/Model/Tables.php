<?php


class SupsysticTables_Tables_Model_Tables extends SupsysticTables_Core_BaseModel
{
	private $tableHistoryModel = null;
	private $allowedHtml = array();

	/**
     * Returns table column by index.
     * @param int $id Table id
     * @param int $index Column index
     * @return stdClass|null
     */
    public function getColumn($id, $index)
    {
        $query = $this->getColumnQuery($id)
            ->where($this->getField('columns', 'index'), '=', (int)$index);

        $column = $this->db->get_row($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }

        return $column;
    }

    /**
     * Returns the array of the NOT extended tables
     *
     * @return null|array
     */
    public function getList()
    {
        $query = $this->getQueryBuilder()->select('*')
            ->from($this->db->prefix . 'supsystic_tbl_tables')
            ->orderBy('id')
            ->order('DESC');

        return $this->db->get_results($query->build());
    }

	public function getListTbl($params)
	{
		global $wpdb;
		$dbTable = $this->db->prefix . 'supsystic_tbl_tables';
		$textLike = !empty($params['search']['text_like']) ? sanitize_text_field($params['search']['text_like']) : '';
		$orderBy = !empty($params['orderBy']) ? sanitize_text_field($params['orderBy']) : '';
		$sortOrder = !empty($params['sortOrder']) ? sanitize_text_field($params['sortOrder']) : '';
		$rowsLimit = !empty($params['rowsLimit']) ? sanitize_text_field($params['rowsLimit']) : '';
		$limitStart = !empty($params['limitStart']) ? sanitize_text_field($params['limitStart']) : 0;

		$wild = '%';
		$textLike = $wild . $wpdb->esc_like( $textLike ) . $wild;

		$prepare = $wpdb->prepare( "SELECT * FROM $dbTable WHERE `id` LIKE %s OR `title` LIKE %s ORDER BY %s ASC LIMIT %d OFFSET %d",
			$textLike,
			$textLike,
			$orderBy,
			(int)$rowsLimit,
			(int)$limitStart
	 	);
		$results = $wpdb->get_results($prepare, ARRAY_A);
		return $results;
	}

	public function getTablesCount()
	{
		$query = $this->getQueryBuilder()->select('*')
			->from($this->db->prefix . 'supsystic_tbl_tables');

		$tables =  $this->db->get_results($query->build(), ARRAY_A);
		return count($tables);
	}

    /**
     * Returns an array of the table columns.
     * @param int $id Table id
     * @return string[]
     */
    public function getColumns($id)
    {
        $query = $this->getColumnQuery($id)
            ->orderBy($this->getField('columns', 'index'));

        $columns = $this->db->get_results($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }

        if (count($columns) > 0) {
            foreach ($columns as $index => $column) {
                $columns[$index] = $column->title;
            }
        }

        return $columns;
    }

    /**
     * Adds a new column to the table.
     * @param int $id Table id
     * @param array|object $column Column data (index, title)
     */
    public function addColumn($id, $column)
    {
        if (!is_array($column) && !is_object($column)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Second parameter must be an array or an instance of the stdClass, %s given.',
                    gettype($column)
                )
            );
        }

        $column = (array)$column;
        if (!array_key_exists('table_id', $column)) {
            $column['table_id'] = (int)$id;
        }

        foreach ((array)$column as $key => $value) {
            unset($column[$key]);
            $column[$this->getField('columns', $key)] = $value;
        }

        $query = $this->getQueryBuilder()
            ->insertInto($this->getTable('columns'))
            ->fields(array_keys($column))
            ->values(array_values($column));

        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }
    }

    /**
     * Updates column data.
     * @param int $id Table id
     * @param int $index Column index
     * @param array|object $column Column data
     */
    public function setColumn($id, $index, $column)
    {
        if (!is_array($column) && !is_object($column)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Second parameter must be an array or an instance of the stdClass, %s given.',
                    gettype($column)
                )
            );
        }

        $column = (array)$column;

        $query = $this->getQueryBuilder()
            ->update($this->getTable('columns'))
            ->fields(array_keys($column))
            ->values(array_values($column))
            ->where($this->getField('columns', 'table_id'), '=', (int)$id)
            ->andWhere($this->getField('columns', 'index'), '=', (int)$index);

        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }
    }

    /**
     * Removes old columns and set a net columns for the table.
     * @param int $id Table id
     * @param array $columns An array of the columns with data.
     */
    public function setColumns($id, array $columns)
    {
        if (count($columns) === 0) {
            throw new InvalidArgumentException('Too few columns.');
        }

        try {
            $this->removeColumns($id);

            foreach ($columns as $index => $column) {
                if (is_string($column)) {
                    $column = array('title' => $column);
                }

                $column = (array)$column;

                if (is_array($column) && !array_key_exists('index', $column)) {
                    $column['index'] = $index;
                }

                $this->addColumn($id, (array)$column);
            }
        } catch (Exception $e) {
            throw new RuntimeException(
                sprintf(
                    'Failed to set columns: %s',
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * Removes table columns.
     * @param int $id Table id
     */
    public function removeColumns($id)
    {
        $query = $this->getQueryBuilder()
            ->deleteFrom($this->getTable('columns'))
            ->where('table_id', '=', (int)$id);

        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }
    }

    /**
     * Adds a row to the table.
     * @param int $id Table id
     * @param array $data An array of the row data
     * @return int
     */
    public function addRow($id, array $data)
    {
        $data = $this->prepareRowsData($data);

				$query = $this->getQueryBuilder()
            ->insertInto($this->getTable('rows'))
            ->fields(
                $this->getField('rows', 'table_id'),
                $this->getField('rows', 'data')
            )
            ->values((int)$id, serialize($data));

        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }

        return $this->db->insert_id;
    }

	public function stripos_arr($haystack, $needle) {
	    if(!is_array($needle)) $needle = array($needle);
	    foreach($needle as $what) {
	        if(is_string($haystack) && ($pos = stripos($haystack, $what))!==false) return $pos;
	    }
	    return false;
	}

	public function sanitizeString($str) {
			$allowedHtml = $this->getAllowedHtml();
			if (!empty($str) && is_string($str)) {
				$str = htmlspecialchars_decode($str);
				$str = wp_kses($str, $allowedHtml);
				$str = str_replace('"&#039;', "&#39;", $str);
				$str = str_replace('&#039;"', "&#39;", $str);
				$str = html_entity_decode($str);
				//error_log('KSES: '.$str);
			}
			return $str;
	}

	private function getAllowedHtml() {
		if(empty($this->allowedHtml)) {
			$allowedHtml = wp_kses_allowed_html();
			$newAllowedHtml = array(
				'li' => array(
					'style' => 1,
					'class' => 1,
					'id' => 1,
				),
				'ul' => array(
					'style' => 1,
					'class' => 1,
					'id' => 1,
				),
				'ol' => array(
					'style' => 1,
					'class' => 1,
					'id' => 1,
				),
				'i' => array(
					'style' => 1,
					'class' => 1,
					'id' => 1,
				),
				'img' => array(
					'src' => 1,
					'style' => 1,
					'width' => 1,
					'height' => 1,
					'id' => 1,
					'class' => 1,
					'alt' => 1,
					'class' => 1,
					'alignnone' => 1,
					'size-full' => 1,
					'wp-image-3300' => 1,
				),
				'video' => array(
					'src' => 1,
					'style' => 1,
					'width' => 1,
					'height' => 1,
					'id' => 1,
					'class' => 1,
					'poster' => 1,
					'autoplay' => 1,
					'controls' => 1,
					'crossorigin' => 1,
					'autobuffer' => 1,
					'buffered' => 1,
					'played' => 1,
					'loop' => 1,
					'muted' => 1,
					'preload' => 1,
				),
				'track' => array(
					'src' => 1,
					'kind' => 1,
					'label' => 1,
					'srclang' => 1,
				),
				'source' => array(
					'src' => 1,
					'type' => 1,
				),
				'audio' => array(
					'src' => 1,
					'style' => 1,
					'width' => 1,
					'height' => 1,
					'id' => 1,
					'class' => 1,
					'autoplay' => 1,
					'controls' => 1,
					'crossorigin' => 1,
					'loop' => 1,
					'muted' => 1,
					'preload' => 1,
				),
				'iframe' => array(
					'src' => 1,
					'style' => 1,
					'width' => 1,
					'height' => 1,
					'id' => 1,
					'class' => 1,
					'title' => 1,
					'allow' => 1,
					'allowfullscreen' => 1,
					'allowpaymentrequest' => 1,
					'csp' => 1,
					'height' => 1,
					'loading' => 1,
					'name' => 1,
					'referrerpolicy' => 1,
					'sandbox' => 1,
					'srcdoc' => 1,
				),
				'br' => array(),
				'a' => array(
					'target' => 1,
					'href' => 1,
					'download' => 1,
					'hreflang' => 1,
					'media' => 1,
					'rel' => 1,
					'type' => 1,
					'link' => 1,
					'style' => 1,
                    'class' => 1,
                    'data-quantity' => 1,
                    'data-product_id' => 1,
        
				),
				'hr' => array(
					'align' => 1,
					'style' => 1,
					'class' => 1,
					'id' => 1,
				),
				'p' => array(
					'style' => 1,
					'class' => 1,
					'id' => 1,
				),
				'sup' => array(
				),
				'sub' => array(
				),
                'button' => array(
					'autocomplete' => 1,
					'style' => 1,
					'class' => 1,
					'autofocus' => 1,
                    'id' => 1,
                    'utocomplete' => 1,
                    'disabled' => 1,
                    'form' => 1,
                    'formenctype' => 1,
                    'formmethod' => 1,
                    'formnovalidate' => 1,
                    'name' => 1,
                    'formtarget' => 1,
                    'type' => 1,
                    'value' => 1,
                    'onclick' =>1,
				),
			);
			$allowedDiv = array(
            'div' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'title' => 1,
               'id' => 1,

            ) ,
            'small' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'span' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'pre' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'p' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'br' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'hr' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'hgroup' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'h1' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'h2' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'h3' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'h4' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'h5' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'h6' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'ul' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'ol' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'li' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'dl' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'dt' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'dd' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'strong' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'em' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'b' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'i' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'u' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'img' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,
							 'src' => 1,
							 'alt' => 1,
            ) ,
            'a' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,
							 'href' => 1,

            ) ,
            'abbr' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'address' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'blockquote' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,
            ) ,
            'area' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'audio' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'video' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'form' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'fieldset' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'label' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'input' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'value' => 1,
               'type' => 1,
               'id' => 1,

            ) ,
            'textarea' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'caption' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'table' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'tbody' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'td' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'tfoot' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'th' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'thead' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'tr' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'iframe' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'select' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

            ) ,
            'option' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

               'selected' => 1,
               'data-number' => 1,
               'value' => 1,
            ),
						'alt' => array(
               'style' => 1,
               'title' => 1,
               'align' => 1,
               'class' => 1,
               'width' => 1,
               'height' => 1,
               'id' => 1,

               'selected' => 1,
               'data-number' => 1,
               'value' => 1,
            )
         );
				 $ar1 = array_merge($allowedHtml, $newAllowedHtml);
				 $ar2 = array_merge($allowedDiv, $ar1);

			$this->allowedHtml = $ar2;
		}
		return $this->allowedHtml;
	}

	public function prepareRowsData($data, $compress = true)
	{
		if (!empty($data['cells'])) {
			$keys = array(
				'd' => 'data',
				'cv' => 'calculatedValue',
				'fv' => 'formattedValue',
				'h' => 'hidden',
				'hc' => 'hiddenCell',
				'ic' => 'invisibleCell',
				't' => 'type',
				'f' => 'format',
				'bt' => 'baseType',
				'ft' => 'formatType',
				'do' => 'dateOrder',
				'm' => 'meta',
				'c' => 'comment',
			);

			if($compress) {
				foreach ($data['cells'] as &$cell) {
					if (isset($cell['comment']) && isset($cell['comment']['value'])) {
						$cell['comment']['value'] = htmlspecialchars($cell['comment']['value'], ENT_QUOTES);
					}
					if (!empty($cell['calculatedValue'])) {
						$cell['calculatedValue'] = htmlspecialchars((string)$cell['calculatedValue'], ENT_QUOTES);
					}
					if (!empty($cell['formattedValue'])) {
						$cell['formattedValue'] = htmlspecialchars((string)$cell['formattedValue'], ENT_QUOTES);
					}
					$cell['data'] = htmlspecialchars((string)$cell['data'], ENT_QUOTES);
                    if (isset($cell['source']) && is_array($cell['source'])) {
                        foreach ($cell['source'] as $i => $v) {
                            $cell['source'][$i] = htmlspecialchars((string)$v, ENT_QUOTES);
                        }
                    }
				}
				foreach ($data['cells'] as &$cell) {
					foreach ($keys as $key => $val) {
						if(array_key_exists($val, $cell)) {
							$cell[$key] = $this->sanitizeString($cell[$val]);
							unset($cell[$val]);
						}
					}
				}
			} else {
				foreach ($data['cells'] as &$cell) {
					foreach ($keys as $key => $val) {
						if(array_key_exists($key, $cell)) {
							$cell[$val] = $this->sanitizeString($cell[$key]);
							unset($cell[$key]);
						}
					}
				}
				foreach ($data['cells'] as &$cell) {
					if (isset($cell['comment']) && isset($cell['comment']['value'])) {
						$cell['comment']['value'] = htmlspecialchars_decode($cell['comment']['value'], ENT_QUOTES);
					}
					if (!empty($cell['calculatedValue'])) {
						$cell['calculatedValue'] = htmlspecialchars_decode($cell['calculatedValue'], ENT_QUOTES);
					}
					if (!empty($cell['formattedValue'])) {
						$cell['formattedValue'] = htmlspecialchars_decode((string)$cell['formattedValue'], ENT_QUOTES);
					}
					$cell['data'] = htmlspecialchars_decode($cell['data'], ENT_QUOTES);
                    if (isset($cell['source']) && is_array($cell['source'])) {
                        foreach ($cell['source'] as $i => $v) {
                            $cell['source'][$i] = htmlspecialchars_decode((string)$v, ENT_QUOTES);
                        }
                    }
				}
			}
		} else {
			$data['cells'] = array();
		}

		return $data;
	}

    /**
     * Returns all table rows
     * @param int $id Table id
     * @param int $limit limit
     * @param bool $asc
     * @param int $offset offset
     * @return array
     */
    public function getRows($id, $limit = 0, $asc = true, $offset = 0)
    {
        $query = $this->getQueryBuilder()
            ->select($this->getField('rows', 'data'))
            ->from($this->getTable('rows'))
            ->where('table_id', '=', (int)$id)
            ->orderBy($this->getField('rows', 'id'));

        if ($limit != 0){
            $query->order($asc ? 'ASC' : 'DESC')->limit((int)$limit)->offset((int)$offset);
        }

        $rows = $this->db->get_results($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }

        if (count($rows) > 0) {
            foreach ($rows as $index => $row) {
                $rows[$index] = @unserialize($row->data);
            }
        }

        foreach ($rows as &$row) {
			$row = $this->prepareRowsData($row, false);

        }

        return $rows;
    }

    /**
     * Returns table rows with Id
     * @return array
     */
    public function getRowsWithId($id, $limit = 0, $asc = true, $offset = 0, $raw = false)
    {
        $query = $this->getQueryBuilder()
            ->select('id, data')
            ->from($this->getTable('rows'))
            ->where('table_id', '=', (int)$id);

        if ($limit != 0){
            $query->order($asc ? 'ASC' : 'DESC')->limit((int)$limit)->offset((int)$offset);
        }
        $result = $this->db->get_results($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }

        $rows = array();
        if (count($result) > 0) {
            foreach ($result as $index => $row) {
                $rows[$row->id] = @unserialize($row->data);
            }
        }
        if (!$raw) {
            foreach ($rows as &$row) {
                $row = $this->prepareRowsData($row, false);
            }
        }

        return $rows;
    }

    /**
     * Returns needed table rows
     * @return array
     */
    public function getNeededRows($id, &$settings, $isSSP, $attributes = false, $export = false)
    {
        $source = ($this->environment->isPro() && isset($settings['source']) ? $settings['source'] : '');
        if (isset($source['database']) && $source['database'] == 'on' && isset($source['dbTable'])){
            $core = $this->environment->getModule('core');
            $dbTableModel = $core->getModelsFactory()->get('DBTables', 'tables');
            return $dbTableModel->getRowsData($settings, false, $attributes);
        }
		if($this->environment->isWooPro()){
			$table = $this->getWooSettings($id);
			$tableSettings = unserialize($table);
			if(!empty($tableSettings['woocommerce']['enable']) && $tableSettings['woocommerce']['enable'] === 'on'){
                if($this->environment->getModule('woocommerce')->getController()){
					return $this->environment->getModule('woocommerce')->getController()->getRows($id, $settings, false, false, false, $export) ;
				}
			}
		}
        if ($isSSP) {
            $cntHead = 1;
            $footers = array();
			if (isset($settings['elements']['head']) && $settings['elements']['head'] == 'on' &&
				isset($settings['headerRowsCount']) && $settings['headerRowsCount'] > 0) {
				$headers = $this->getRows($id, $settings['headerRowsCount']);
			} else {
				$headers = $this->getRows($id, $cntHead);
			}
            if (isset($settings['elements']['foot']) && $settings['elements']['foot'] == 'on' &&
                isset($settings['customFooter']) && $settings['customFooter'] == 'on' &&
                isset($settings['footerRowsCount']) && $settings['footerRowsCount'] > 0) {
                $footers = $this->getRows($id, $settings['footerRowsCount'], false);
            }
            return array_merge($headers, $footers);
        }

        return $this->getRows($id);
    }

    /**
     * Returns needed table rows
     * @return array
     */
    public function getRowsByIds($id, &$settings, $ids, $attributes = false)
    {
        $source = ($this->environment->isPro() && isset($settings['source']) ? $settings['source'] : '');

        if (isset($source['database']) && $source['database'] == 'on' && isset($source['dbTable'])){
            $core = $this->environment->getModule('core');
            $dbTableModel = $core->getModelsFactory()->get('DBTables', 'tables');
            return $dbTableModel->getRowsData($settings, $ids, $attributes);
        }

        $bodyStop = $this->getCountRows($id);
        $bodyStart = 0;
        $offset = $bodyStart;
        $limit = 1000;
        $rows = array();
        $cnt = sizeof($ids);

        $query = $this->getQueryBuilder()
            ->select($this->getField('rows', 'data'))
            ->from($this->getTable('rows'))
            ->where('table_id', '=', (int)$id)
            ->orderBy($this->getField('rows', 'id'))
            ->limit($limit);
        do {
            $query->offset($offset);
            $results = $this->db->get_results($query->build());
            if($this->db->last_error) {
                throw new RuntimeException($this->db->last_error);
            }

            foreach ($results as $i => $row) {
                $data = @unserialize($row->data);
                $index = array_search($data['cells'][0]['y'], $ids);
                if($index !== false) {
                    $rows[$index] = $this->prepareRowsData($data, false);
                }
            }
            unset($results);
            $offset += $limit;
            if(sizeof($rows) >= $cnt) break;
        } while ($offset < $bodyStop);
        ksort($rows);
        return $rows;
    }

    /**
     * Returns count table rows
     * @return int
     */
    public function getCountRows($id)
    {
        $query = $this->getQueryBuilder()
                ->select('count(*)')
                ->from($this->getTable('rows'))
                ->where('table_id', '=', (int)$id);

        $count = $this->db->get_row($query->build(), ARRAY_N);

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }

        return $count[0];
    }

    /**
     * Calc rows for AJAX page
     * @return array
     */
    public function getRowsByPart($id, $searchAll, $searchCols, $orderCol, $orderAsc, $start, $length, $header, $footer, $searchParams, $table)
    {
        $sort = $orderCol !== false;
        $sorter = array();
        $search = (sizeof($searchCols) > 0 || $searchAll !== false);
        $isWord = (isset($searchParams['strictMatching']) && ($searchParams['strictMatching'] == 'on'));
        if ($isWord) {
            $searchAll = '~\b'.$searchAll.'~i';
        }

        $rawData = (!$sort && !$search);

        $recordsTotal = $this->getCountRows($id);
        $bodyStart = $header;
        $bodyStop = $recordsTotal - $footer - 1;
        $recordsTotal = $recordsTotal - $header - $footer;

        $query = $this->getQueryBuilder()
            ->select('id, data')
            ->from($this->getTable('rows'))
            ->where('table_id', '=', (int)$id)
            ->orderBy($this->getField('rows', 'id'));

        if ($rawData) {
            $recordsFiltered = $recordsTotal;
            $offset = $header + $start;

			if( (int)$length === -1 ){
				//for all option in pagination
				$limit = $bodyStop;
			}else{
				$limit = ($offset + $length - 1 > $bodyStop ? $bodyStop - $offset + 1: $length);
			}

            $query->limit($limit)->offset($offset);
        } else {
            $offset = $bodyStart;
            $limit = 1000;
            $query->limit($limit);
            do {
                $query->offset($offset);
                $rows = $this->db->get_results($query->build());

                if($this->db->last_error) {
                    throw new RuntimeException($this->db->last_error);
                }

                foreach ($rows as $i => $row) {
                    $values = $this->prepareRowsData(@unserialize($row->data), false);

                    $cells = $values['cells'];
                    $filterCols = true;

                    foreach ($searchCols as $j => $s) {
						if($table->settings['autoIndex'] === 'new'){
							$j = $j - 1;
						}
                        if (!$this->searchInValue($cells[$j]['data'], $s)) {
                            $filterCols = false;
                            break;
                        }
                    }
                    if (!$filterCols) continue;

                    $filterAll = $searchAll === false;
                    if (!$filterAll) {
                        foreach ($cells as $j => $cell) {
                            $filterAll = ($isWord ? preg_match($searchAll, $cell['data']) == 1 : stripos($cell['data'], $searchAll) !== false);
                            if ($filterAll) break;
                        }
                    }
                    if ($filterAll) {
                        $sorter[$row->id] = $cells[$orderCol]['data'];
                    }
                }
                unset($rows);
                $offset += $limit;
            } while ($offset < $bodyStop);

            $recordsFiltered = count($sorter);
            if ($sort) {
                if ($orderAsc) {
                    asort($sorter);
                } else {
                    arsort($sorter);
                }
            }
            if ($start > 0 || $length > 0) {
                $sorter = array_slice($sorter, $start, $length, true);
            }
            $list = '0,';
            $num = 0;
            foreach ($sorter as $i => $v) {
                $list .= $i.',';
                $sorter[$i] = $num;
                $num++;
            }

            $query = $this->getQueryBuilder()
                ->select('id, data')
                ->from($this->getTable('rows'))
                ->where('table_id', '=', (int)$id)
                ->andWhere('id', 'IN', substr($list, 0, -1));
        }

        $rows = $this->db->get_results($query->build());
        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }

        $num = 0;
        $data = array();
        foreach ($rows as $i => $row) {
            $values = $this->prepareRowsData(@unserialize($row->data), false);

            $rowId = $row->id;
            $n = ($sort ? $sorter[$rowId] : $num++);
            $data[$n] = $values;
        }

        if ($sort) {
            ksort($data);
        }
        return array('data' => $data, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered);
    }
    private function searchInValue($value, $searchs)
    {
        foreach($searchs as $i => $search) {
            if(stripos($value, $search) !== false) {
                return true;
            }
        }
        return false;
    }

	/**
	 * Sets the part of rows for the table
	 * @param int $id Table id
	 * @param array $rows An array of the rows
	 */
	public function setRowsByPart($id, array $rows, $step, $last)
	{
		if (count($rows) === 0) {
			throw new InvalidArgumentException('Too few rows.');
		}
		$option_name = $this->environment->getConfig()->get('db_prefix') . 'last_row_id_' . $id;

		try {
			if(!$lastRowId = get_option($option_name)) {
				$query = $this->getQueryBuilder()
					->select('MAX(' . $this->getField('rows', 'id') . ') as max')
					->from($this->getTable('rows'))
					->where($this->getField('rows', 'table_id'), '=', (int)$id);

				$lastRowId = $this->db->get_results($query->build());
				$lastRowId = $lastRowId[0]->max;
				update_option($option_name, $lastRowId);
			}
			$this->removeRowsByPart($id, $lastRowId, $step, $last);

			foreach ($rows as $row) {
				$this->addRow($id, $row);
			}

			if(!empty($last)) {
				$this->removeRowsByPart($id, $lastRowId, $last);
				delete_option($option_name, $lastRowId);
			}

		} catch (Exception $e) {
			throw new RuntimeException(
				sprintf('Failed to set rows: %s', $e->getMessage())
			);
		}
	}

	public function removeRowsByPart($id, $lastRowId, $step, $last = false)
	{
		$query = $this->getQueryBuilder()
			->deleteFrom($this->getTable('rows'))
			->where($this->getField('rows', 'table_id'), '=', (int)$id)
			->andWhere($this->getField('rows', 'id'), '<=', (int)$lastRowId);

		if(!$last) {
			$query->limit((int)$step);
		}

		$this->db->query($query->build());

		if ($this->db->last_error) {
			throw new RuntimeException($this->db->last_error);
		}
	}

    public function removeLastRows($id, $count)
    {
        if ($count <= 0) return;

        $query = $this->getQueryBuilder()
            ->select('MAX(' . $this->getField('rows', 'id') . ') as max')
            ->from($this->getTable('rows'))
            ->where($this->getField('rows', 'table_id'), '=', (int)$id);

        $lastRowId = $this->db->get_row($query->build(), ARRAY_N);

        $query = $this->getQueryBuilder()
            ->deleteFrom($this->getTable('rows'))
            ->where($this->getField('rows', 'table_id'), '=', (int)$id)
            ->andWhere($this->getField('rows', 'id'), '>', (int)($lastRowId[0] - $count));

        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }
    }

    public function removeLastColumns($id, $from)
    {
        if ($from <= 0) return;

        $totalRows = $this->getCountRows($id);

        $query = $this->getQueryBuilder()
            ->select('id, data')
            ->from($this->getTable('rows'))
            ->where('table_id', '=', (int)$id)
            ->orderBy($this->getField('rows', 'id'));

        $limit = 400;
        $offset = 0;
        do {
            $query->limit($limit)->offset($offset);
            $rows = $this->db->get_results($query->build());

            if ($this->db->last_error) {
                throw new RuntimeException($this->db->last_error);
            }

            foreach ($rows as $i => $row) {
                $values = @unserialize($row->data);
                array_splice($values['cells'], $from);
                $this->updateRow($row->id, $values);
            }
            unset($rows);
            $offset += $limit;
        } while ($offset < $totalRows);
    }

    /**
     * Sets the rows for the table
     * @param int $id Table id
     * @param array $rows An array of the rows
     */
    public function setRows($id, array $rows, $remove = true)
    {
        if (count($rows) === 0) {
            throw new InvalidArgumentException('Too few rows.');
        }

        try {
			if($remove) {
				$this->removeRows($id);
			}

            foreach ($rows as $row) {
                $this->addRow($id, $row);
            }
        } catch (Exception $e) {
            throw new RuntimeException(
                sprintf('Failed to set rows: %s', $e->getMessage())
            );
        }
    }

		public function setRowsImport($id, array $rows)
    {
        if (count($rows) === 0) {
            throw new InvalidArgumentException('Too few rows.');
        }

        try {
			// if($remove) {
			// 	$this->removeRows($id);
			// }

            foreach ($rows as $row) {
                $this->addRow($id, $row);
            }
        } catch (Exception $e) {
            throw new RuntimeException(
                sprintf('Failed to set rows: %s', $e->getMessage())
            );
        }
    }

    /**
     * Update the rows by id
     */
    public function updateRow($id, $data, $raw = false)
    {
        if($raw) {
            $data = $this->prepareRowsData($data);
        }
        $update = $this->getQueryBuilder()
            ->update($this->getTable('rows'))
            ->where('id', '=', (int)$id)
            ->set(array('data' => serialize($data)));

        $this->db->query($update->build());
        if ($this->db->last_error) {
                throw new RuntimeException($this->db->last_error);
        }
    }


    /**
     * Removes all table rows.
     * @param int $id Table id
     */
    public function removeRows($id)
    {
        $query = $this->getQueryBuilder()
            ->deleteFrom($this->getTable('rows'))
            ->where($this->getField('rows', 'table_id'), '=', (int)$id);


        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }
    }

    public function setMeta($id, array $meta)
    {
		$query = $this->getQueryBuilder()
            ->update($this->getTable())
            ->where('id', '=', (int)$id)
            ->set(array('meta' => htmlspecialchars(serialize($meta), ENT_QUOTES)));


        $this->db->query($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }
    }

    /**
     * Callback for SupsysticTables_Tables_Model_Tables::get()
     * @see SupsysticTables_Tables_Model_Tables::get()
     * @param object|null $table Table data
     * @return object|null
     */
    public function onTablesGet($table)
    {
        if (null === $table) {
            return $table;
        }

		$table->view_id = $table->id . '_' . mt_rand(1, 99999);
        $table->columns = $this->getColumns($table->id);

				$table->settings = htmlspecialchars_decode($table->settings, ENT_QUOTES);
				$table->settings = $this->fixIncorrectSerialize($table->settings);
				$table->settings = unserialize($table->settings);

        // rev 41
        if (property_exists($table, 'meta')) {
						$table->meta = htmlspecialchars_decode($table->meta, ENT_QUOTES);
						$table->meta = $this->fixIncorrectSerialize($table->meta);
						$table->meta = unserialize($table->meta);
        }

        return $table;
    }

	public function onTablesGetPro($table)
	{
		// This method load twice all rows in backend second call go via ajax.
		// Need to fix.
		if (null === $table) {
			return $table;
		}
		if(!empty($table->history_settings)) {
			$table->historySettings = htmlspecialchars_decode($table->history_settings);
			$table->historySettings = $this->fixIncorrectSerialize($table->historySettings);
			$table->historySettings = unserialize($table->historySettings);
		}
		if(!empty($table->woo_settings)) {
			$table->woo_settings = htmlspecialchars_decode($table->woo_settings);
			$table->woo_settings = $this->fixIncorrectSerialize($table->woo_settings);
			$table->woo_settings = unserialize($table->woo_settings);
		}

		return $table;
	}

    /**
     * Filter for SupsysticTables_Tables_Model_Tables::getAll()
     * @see SupsysticTables_Tables_Model_Tables::getAll()
     * @param object[] $tables An array of the tables data
     * @return object[]
     */
    public function onTablesGetAll($tables)
    {
        if (null === $tables || (is_array($tables) && count($tables) === 0)) {
            return $tables;
        }

        return array_map(array($this, 'onTablesGet'), $tables);
    }

	/**
	 * Callback for SupsysticTables_Core_BaseModel::removeById()
	 * @see SupsysticTables_Core_BaseModel::removeById()
	 * @param int $id Table id
	 * @return object|null
	 */
	public function onTablesRemove($id)
	{
		if (empty($id)) {
			return null;
		}

		$this->removeRows($id);

		return null;
	}

    /**
     * {@inheritdoc}
     *
     * Adds filters for the methods get() and getAll().
     */
    public function onInstanceReady()
    {
        parent::onInstanceReady();

        $dispatcher = $this->environment->getDispatcher();

        $dispatcher->on('tables_get', array($this, 'onTablesGet'));
        $dispatcher->on('tables_get', array($this, 'onTablesGetPro'));
        $dispatcher->on('tables_remove', array($this, 'onTablesRemove'));
        // No reason to fetch all data from all tables when we need only tables list
        // $dispatcher->on('tables_get_all', array($this, 'onTablesGetAll'));
    }

    protected function getColumnQuery($id)
    {
        return $this->getQueryBuilder()
            ->select($this->getField('columns', 'title'))
            ->from($this->getTable('columns'))
            ->where('table_id', '=', (int)$id);
    }
		public function fixIncorrectSerialize($string) {
	    // at first, check if "fixing" is really needed at all. After that, security checkup.
	    if ( @!unserialize($string) &&  preg_match('/^[aOs]:/', $string) ) {
	         $string = preg_replace_callback( '/s\:(\d+)\:\"(.*?)\";/s',    function($matches){return 's:'.strlen($matches[2]).':"'.$matches[2].'";'; },   $string );
	    }
	    return $string;
		}
    public function getSettings($id)
    {
		$query = $this->getQueryBuilder()
			->select($this->getField('tables', 'settings'))
			->from($this->getTable('tables'))
			->where('id', '=', (int)$id);

		$result = $this->db->get_results($query->build());

		if ($this->db->last_error) {
			throw new RuntimeException($this->db->last_error);
		}
		if(!empty($result)) {
			$result = $result[0]->settings;
			$resultWithSlashes = htmlspecialchars_decode($result, ENT_QUOTES);
			$resultWithSlashes = $this->fixIncorrectSerialize($resultWithSlashes);
			$result = unserialize($resultWithSlashes);
		}
		return $result;
    }

    public function getMeta($id)
    {
        $query = $this->getQueryBuilder()
            ->select($this->getField('tables', 'meta'))
            ->from($this->getTable('tables'))
            ->where('id', '=', (int)$id);

        $result = $this->db->get_results($query->build());

        if ($this->db->last_error) {
            throw new RuntimeException($this->db->last_error);
        }
        if (!empty($result)) {
			$result = $result[0]->meta;
			$resultWithSlashes = htmlspecialchars_decode($result, ENT_QUOTES);
			$resultWithSlashes = $this->fixIncorrectSerialize($resultWithSlashes);
			$result = unserialize($resultWithSlashes);
        }

        return $result;
    }

	/**
	 * @param $tableIds
	 *
	 * @return array
	 */
	public function getPostIdsByTableIds($tableIds) {
		$postIds = array();
		$query = $this->getQueryBuilder()->select('*')
			->from($this->db->prefix . 'posts')
			->where( 'post_content', 'LIKE', '%[supsystic-tables%' )
			->andWhere( 'post_type', '=', 'post' )
			->andWhere( 'post_status', '=', 'publish' )
			->orWhere( 'post_type', '=', 'page' )
			->andWhere( 'post_status', '=', 'publish' )
			->andWhere( 'post_content', 'LIKE', '%[supsystic-tables%' )
			->orderBy('id')
			->order('ASC');
		$postsWithTables = $this->db->get_results($query->build());

		if($postsWithTables) {
			foreach($postsWithTables as $parsingPost) {
				$content = $parsingPost->post_content;

				if(preg_match_all('|\[supsystic-tables\s+id=[\'"]?(\d+)[\'"]?\s*?\]|',$content,$matches)) {
					array_shift( $matches );
					foreach($matches[0] as $matchedTableId) {
						if (in_array( $matchedTableId, $tableIds)) {
							$postIds[ $parsingPost->ID ]['tables'][] = $matchedTableId;
						}
					}
				}
			}
		}
		return $postIds;
	}

	/**
	 * @param array $tokens
	 *
	 * @return array
	 */
	public function getTableIdsBySearchTokens($tokens)
	{
		$tempIds = array();
		$step = 0;
		foreach($tokens as $token) {
			$step++;
			$query = $this->getQueryBuilder()
				->select('DISTINCT table_id')
				->from($this->getTable('rows'))
				->where('data', 'LIKE', "%{$token}%");
			$tables = $this->db->get_results($query->build());

			if($this->db->last_error) {
				throw new RuntimeException( $this->db->last_error );
			}

			foreach($tables as $table) {
				$id = $table->table_id;
				if($step == 1) {
					$tempIds[$id] = 1;
				} elseif(isset($tempIds[$id])) {
					$tempIds[$id]++;
				}
			}
		}

		$tableIds = array();
		foreach($tempIds as $id => $flag) {
			if($flag == $step) {
				array_push($tableIds, $id);
			}
		}
		return $tableIds;
	}

	/**
	 * Returns all table rows where data LIKE
	 * @param int $id Table id
	 * @return array
	 */
	public function getRowsLike($id, $tokens) {
		$firstToken = array_shift($tokens);
		$query = $this->getQueryBuilder()
			->select($this->getField('rows', '*'))
			->from($this->getTable('rows'))
			->where('data', 'LIKE', "%{$firstToken}%")
			->orderBy($this->getField( 'rows', 'id' ));

		foreach($tokens as $token) {
			$token = trim($token);
			$query->orWhere( 'data', 'LIKE', "%{$token}%" );
		}
		$query->andWhere('table_id', '=', (int) $id);
		$rows = $this->db->get_results( $query->build() );

		if($this->db->last_error) {
			throw new RuntimeException( $this->db->last_error );
		}
		return $rows;
	}

	// Fix for compatibility with old pro versions
	private function getTableHistoryModel() {
		if(!$this->tableHistoryModel) {
			$core = $this->environment->getModule('core');
			$this->tableHistoryModel = $core->getModelsFactory()->get('history', 'tables');
		}
		return $this->tableHistoryModel;
	}

	public function getAllTableHistory($tableId)
	{
		return $this->getTableHistoryModel()->getAllTableHistory($tableId);
	}

	public function getUserTableHistory($userId, $tableId, $period = null)
	{
		return $this->getTableHistoryModel()->getUserTableHistory($userId, $tableId, $period = null);
	}

	public function _checkUserTableHistoryByPeriod($tableId, $history, $period = null)
	{
		return $this->getTableHistoryModel()->_checkUserTableHistoryByPeriod($tableId, $history, $period = null);
	}

	public function updateUserTableHistory($userId, $tableId, $data, $period = null)
	{
		return $this->getTableHistoryModel()->updateUserTableHistory($userId, $tableId, $data, $period = null);
	}

	public function createUserTableHistory($userId, $tableId)
	{
		return $this->getTableHistoryModel()->createUserTableHistory($userId, $tableId);
	}

	public function _afterSimpleGet($historyTable)
	{
		return $this->getTableHistoryModel()->_afterSimpleGet($historyTable);
	}

	public function getHistorySettings($id)
	{
		return $this->getTableHistoryModel()->getHistorySettings($id);
	}

	public function setHistorySettings($id, $settings)
	{
		return $this->getTableHistoryModel()->setHistorySettings($id, $settings);
	}

	public function getWooSettings($id)
	{
		$query = $this->getQueryBuilder()
			->select($this->getField('tables', 'woo_settings'))
			->from($this->getTable('tables'))
			->where('id', '=', (int)$id);

		$rows = $this->db->get_var( $query->build() );
		return $rows;
	}

}
