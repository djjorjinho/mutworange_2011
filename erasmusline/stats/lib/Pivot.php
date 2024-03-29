<?php
require_once("lib/System/Daemon.php");
class Pivot{
    /**
     * @param struct $recorset
     * @return Pivot
     */
    static function factory($recorset)
    {
        return new self($recorset);
    }

    protected function __construct($recorset)
    {
        $this->_recordset = $recorset;
    }

    private $_typeMark = false;
    /**
     * @param boolean $type
     * @return Pivot
     */
    public function typeMark($type = true)
    {
        $this->_typeMark = $type;
        return $this;
    }

    private $_pivotOn = null;

    /**
     * @param array $conf
     * @return Pivot
     */
    public function pivotOn($conf)
    {
        $this->_pivotOn = $conf;
        return $this;
    }

    private $_pivotTotal = false;
	
    public static function concatKey(){
    	$str = "";
    	$arr = func_get_args();
    	$num = func_num_args();
    	foreach(range(0,$num-1) as $idx){
    		$val = $arr[$idx];
    		if(!empty($val)){
    			if($idx>0) $str.=" | ";
    			$str .= $val;
    		}
    	}
    	return $str;
    }
    
    /**
     * @param boolean $bool
     * @return Pivot
     */
    public function pivotTotal($bool=true)
    {
        $this->_pivotTotal = $bool;
        return $this;
    }

    private $_lineTotal = false;
    /**
     * @param boolean $bool
     * @return Pivot
     */
    public function lineTotal($bool=true)
    {
        $this->_lineTotal = $bool;
        return $this;
    }

    private $_fullTotal = false;
    /**
     * @param boolean $bool
     * @return Pivot
     */
    public function fullTotal($bool=true)
    {
        $this->_fullTotal = $bool;
        return $this;
    }

    private $_column = null;
    private $_columnValues = null;
    /**
     * @param array $column
     * @param array $columnValues
     * @return Pivot
     */
    public function addColumn($column, $columnValues){
        $this->_column = $column;
        $this->_columnValues = $columnValues;
        return $this;
    }

    const TOTAL = "TOT";
    private $_splits = array();

    public function fetch($fetchType=null){
        $tmp = $tmpCount = array();
        $split  = $this->_column[0];
        $column = $this->_column[1];

        foreach ($this->_recordset as $reg) {
            switch (count($this->_pivotOn)) {
                case 1:
                    $k0 = $this->_getColumnItem($reg, 0);
                    foreach ($this->_columnValues as $item) {
                        if ($item instanceof Pivot_Callback) {
                            break;
                        } elseif ($item instanceof Pivot_Count) {
                            $tmpCount[$k0]
                            	[$reg[$split]]
                            	[$reg[$column]]
                            	[$item->getKey()]++;
                        }
                        $tmp[$k0]
                        	[$reg[$split]]
                        	[$reg[$column]]
                        	[$item] += $reg[$item];
                        
                        $this->_splits[$reg[$split]]
                        	[$reg[$column]]
                        	[$item] = $item;
                    }
                    break;
                case 2:
                    $k0 = $this->_getColumnItem($reg, 0);
                    $k1 = $this->_getColumnItem($reg, 1);
                    foreach ($this->_columnValues as $item) {
                        if ($item instanceof Pivot_Callback) {
                            break;
                        } elseif ($item instanceof Pivot_Count) {
                            $tmpCount[$k0]
                            	[$k1]
                            	[$reg[$split]]
                            	[$reg[$column]]
                            	[$item->getKey()] ++;
                        }
                        $tmp[$k0]
                        	[$k1]
                        	[$reg[$split]]
                        	[$reg[$column]]
                        	[$item] += $reg[$item];
                        $this->_splits[$reg[$split]]
                        	[$reg[$column]]
                        	[$item] = $item;
                    }
                    break;
                case 3:
                    $k0 = $this->_getColumnItem($reg, 0);
                    $k1 = $this->_getColumnItem($reg, 1);
                    $k2 = $this->_getColumnItem($reg, 2);
                    foreach ($this->_columnValues as $item) {
                        if ($item instanceof Pivot_Callback) {
                            break;
                        } elseif ($item instanceof Pivot_Count) {
                            $tmpCount[$k0]
                            	[$k1]
                            	[$k2]
                            	[$reg[$split]]
                            	[$reg[$column]]
                            	[$item->getKey()] ++;
                        }
                        $tmp[$k0]
                        	[$k1]
                        	[$k2]
                        	[$reg[$split]]
                        	[$reg[$column]]
                        	[$item] += $reg[$item];
                        
                        $this->_splits[$reg[$split]]
                        	[$reg[$column]]
                        	[$item] = $item;
                    }
                    break;
                 case 4:
                    	$k0 = $this->_getColumnItem($reg, 0);
                    	$k1 = $this->_getColumnItem($reg, 1);
                    	$k2 = $this->_getColumnItem($reg, 2);
                    	$k3 = $this->_getColumnItem($reg, 3);
                    	foreach ($this->_columnValues as $item) {
                    		if ($item instanceof Pivot_Callback) {
                    			break;
                    		} elseif ($item instanceof Pivot_Count) {
                    			$tmpCount[$k0]
                    			[$k1]
                    			[$k2]
                    			[$k3]
                    			[$reg[$split]]
                    			[$reg[$column]]
                    			[$item->getKey()] ++;
                    		}
                    		$tmp[$k0]
                    		[$k1]
                    		[$k2]
                    		[$k3]
                    		[$reg[$split]]
                    		[$reg[$column]]
                    		[$item] += $reg[$item];
                    		$this->_splits[$reg[$split]]
                    		[$reg[$column]]
                    		[$item] = $item;
                    	}
                    	break;
                    case 5:
                    		$k0 = $this->_getColumnItem($reg, 0);
                    		$k1 = $this->_getColumnItem($reg, 1);
                    		$k2 = $this->_getColumnItem($reg, 2);
                    		$k3 = $this->_getColumnItem($reg, 3);
                    		$k4 = $this->_getColumnItem($reg, 4);
                    		foreach ($this->_columnValues as $item) {
                    			if ($item instanceof Pivot_Callback) {
                    				break;
                    			} elseif ($item instanceof Pivot_Count) {
                    				$tmpCount[$k0]
                    				[$k1]
                    				[$k2]
                    				[$k3]
                    				[$k4]
                    				[$reg[$split]]
                    				[$reg[$column]]
                    				[$item->getKey()] ++;
                    			}
                    			$tmp[$k0]
                    			[$k1]
                    			[$k2]
                    			[$k3]
                    			[$k4]
                    			[$reg[$split]]
                    			[$reg[$column]]
                    			[$item] += $reg[$item];
                    			$this->_splits[$reg[$split]]
                    			[$reg[$column]]
                    			[$item] = $item;
                    		}
                    		break;
                   case 6:
                    			$k0 = $this->_getColumnItem($reg, 0);
                    			$k1 = $this->_getColumnItem($reg, 1);
                    			$k2 = $this->_getColumnItem($reg, 2);
                    			$k3 = $this->_getColumnItem($reg, 3);
                    			$k4 = $this->_getColumnItem($reg, 4);
                    			$k5 = $this->_getColumnItem($reg, 5);
                    			foreach ($this->_columnValues as $item) {
                    				if ($item instanceof Pivot_Callback) {
                    					break;
                    				} elseif ($item instanceof Pivot_Count) {
                    					$tmpCount[$k0]
                    					[$k1]
                    					[$k2]
                    					[$k3]
                    					[$k4]
                    					[$k5]
                    					[$reg[$split]]
                    					[$reg[$column]]
                    					[$item->getKey()] ++;
                    				}
                    				$tmp[$k0]
                    				[$k1]
                    				[$k2]
                    				[$k3]
                    				[$k4]
                    				[$k5]
                    				[$reg[$split]]
                    				[$reg[$column]]
                    				[$item] += $reg[$item];
                    				$this->_splits[$reg[$split]]
                    				[$reg[$column]]
                    				[$item] = $item;
                    			}
                    			break;
                
            }
        }
        
        //System_Daemon::debug("splits: ".print_r($this->_splits,true));
    	//System_Daemon::debug("tmp: ".print_r($tmp,true));
        $data = $this->_buildOutput($tmp, $fetchType, $tmpCount); 
        //System_Daemon::debug("data: ".print_r($data,true));
        return $data;
    }

    const TYPE_LINE = 0;
    const TYPE_PIVOT_TOTAL_LEVEL1 = 1;
    const TYPE_PIVOT_TOTAL_LEVEL2 = 2;
    const TYPE_FULL_TOTAL = 3;

    const _ID = 'no.';
    private function _buildOutput($tmp, $fetchType, $tmpCount)
    {
        $out = array();
        $cont = 0;
        $fullTotal  = array();
        switch (count($this->_pivotOn)) {
            case 1:
                foreach ($tmp as $p0 => $p0Values) {
                    $i++;
                    $_out = $_lineTotal = array();
                    //$_out[self::_ID] = ++$cont;
                    if ($this->_typeMark) {
                        $_out['type'] = self::TYPE_LINE;
                    }

                    $_out[$this->_pivotOn[0]] = $p0;

                    foreach (array_keys($this->_splits) as $split) {
                        $cols = $p0Values[$split];

                        foreach (array_keys($this->_splits[$split]) as $col) {
                            $colValues = $cols[$col];
                            foreach ($this->_columnValues as $_k) {
                                $k = ($_k instanceof Pivot_Callback || 
                                	$_k instanceof Pivot_Count) ? 
                                	$_k->getKey() : $_k;
                                
                                if ($_k instanceof Pivot_Count) {
                                    $value = $tmpCount[$p0]
                                    				[$split]
                                    				[$col]
                                    				[$k];
                                } elseif ($_k instanceof Pivot_Callback) {
                                    $value = $_k->cbk($colValues);
                                } else {
                                    $value = $colValues[$k];
                                }
                                $_out[self::concatKey($split,$col,$k)] = $value;
                                
                                // totals related
                                if ($this->_lineTotal) {
                                    $_lineTotal[$k] += $value;
                                }
                                if ($this->_fullTotal) {
                                    $fullTotal[$split]
                                    			[$col]
                                    			[$k] += $value;
                                }
                                ////////////////////////
                                
                            }
                        }
                    }
                    
                    // totals related
                    if ($this->_lineTotal) {
                        foreach ($this->_columnValues as $_k) {
                            $k = ($_k instanceof Pivot_Callback || $_k instanceof Pivot_Count) ? $_k->getKey() : $_k;
                            $value = ($_k instanceof Pivot_Callback) ? 
                                $_k->cbk($_lineTotal) : $_lineTotal[$k];
                            $_out[self::TOTAL . "_{$k}"] = $value;
                        }
                    }
                    
                    // set output array
                    $out[] = $_out;
                }
                break;
            case 2:
                foreach ($tmp as $p0 => $p0Values) {
                    $p0Total  = array();
                    foreach ($p0Values as $p1 => $p1Values) {
                        $_out = $_lineTotal = array();
                        //$_out[self::_ID] = ++$cont;
                        if ($this->_typeMark) {
                            $_out['type'] = self::TYPE_LINE;
                        }
                        $_out[$this->_pivotOn[0]] = $p0;
                        $_out[$this->_pivotOn[1]] = $p1;

                        foreach (array_keys($this->_splits) as $split) {
                            $cols = $p1Values[$split];

                            foreach (array_keys($this->_splits[$split]) as $col) {
                                $colValues = $cols[$col];
                                foreach ($this->_columnValues as $_k) {
                                    $k = ($_k instanceof Pivot_Callback || 
                                    	$_k instanceof Pivot_Count) ? 
                                    	$_k->getKey() : $_k;
                                    
                                    if ($_k instanceof Pivot_Count) {
                                        $value = $tmpCount[$p0]
                                        				[$p1]
                                        				[$split]
                                        				[$col]
                                        				[$k];
                                    } elseif ($_k instanceof Pivot_Callback) {
                                        $value = $_k->cbk($colValues);
                                    } else {
                                        $value = $colValues[$k];
                                    }
                                    $_out[self::concatKey($split,$col,$k)] = $value;
                                    if ($this->_lineTotal) {
                                        $_lineTotal[$k] += $value;
                                    }
                                    if ($this->_pivotTotal) {
                                        $p0Total[$split]
                                        		[$col]
                                        		[$k] += $value;
                                    }
                                    if ($this->_fullTotal) {
                                        $fullTotal[$split]
                                        			[$col]
                                        			[$k] += $value;
                                    }
                                }
                            }
                        }
                        if ($this->_lineTotal) {
                            foreach ($this->_columnValues as $_k) {
                                $k = ($_k instanceof Pivot_Callback || 
                                		$_k instanceof Pivot_Count) ? 
                                		$_k->getKey() : $_k;
                                $value = ($_k instanceof Pivot_Callback) ? 
                                    $_k->cbk($_lineTotal) : $_lineTotal[$k];
                                $_out[self::TOTAL . "_{$k}"] = $value;
                            }
                        }
                        $out[] = $_out;
                    }
                    if ($this->_pivotTotal) {
                        $_out = $_lineTotal = array();
                        //$_out[self::_ID] = ++$cont;
                        if ($this->_typeMark) {
                            $_out['type'] = self::TYPE_PIVOT_TOTAL_LEVEL1;
                        }
                        $i = 0;
                        foreach ($this->_pivotOn as $pivotOn) {
                            if ($i == 0) {
                                $_out[$pivotOn] = self::TOTAL . "({$this->_pivotOn[0]})";
                            } else {
                                $_out[$pivotOn] = null;
                            }
                            $i++;
                        }
                        foreach ($p0Total as $split => $values) {
                            foreach ($values as $col => $colValues) {
                                foreach ($this->_columnValues as $_k) {
                                    $k = ($_k instanceof Pivot_Callback || $_k instanceof Pivot_Count) ? $_k->getKey() : $_k;
                                    $value = ($_k instanceof Pivot_Callback) ? 
                                        $_k->cbk($p0Total[$split][$col]) : $p0Total[$split][$col][$k];
                                    $_out[self::concatKey($split,$col,$k)] = $value;
                                    $_lineTotal[$k] += $value;
                                }
                            }
                        }
                        if ($this->_lineTotal) {
                            foreach ($this->_columnValues as $_k) {
                                $k = ($_k instanceof Pivot_Callback || $_k instanceof Pivot_Count) ? $_k->getKey() : $_k;
                                $value = ($_k instanceof Pivot_Callback) ? 
                                    $_k->cbk($_lineTotal) : $_lineTotal[$k];
                                $_out[self::TOTAL . "_{$k}"] = $_lineTotal[$k];
                            }
                        }
                        $out[] = $_out;
                    }

                }
                break;
            case 3:
                foreach ($tmp as $p0 => $p0Values) {
                    $p0Total  = array();
                    foreach ($p0Values as $p1 => $p1Values) {
                        foreach ($p1Values as $p2 => $p2Values) {
                            $_out = $_lineTotal = array();
                            //$_out[self::_ID] = ++$cont;
                            if ($this->_typeMark) {
                                $_out['type'] = self::TYPE_LINE;
                            }
                            $_out[$this->_pivotOn[0]] = $p0;
                            $_out[$this->_pivotOn[1]] = $p1;
                            $_out[$this->_pivotOn[2]] = $p2;

                            foreach (array_keys($this->_splits) as $split) {
                                $cols = $p2Values[$split];

                                foreach (array_keys($this->_splits[$split]) as $col) {
                                    $colValues = $cols[$col];
                                    foreach ($this->_columnValues as $_k) {
                                        $k = ($_k instanceof Pivot_Callback || 
                                        	$_k instanceof Pivot_Count) ? 
                                        	$_k->getKey() : $_k;
                                        
                                        if ($_k instanceof Pivot_Count) {
                                            $value = $tmpCount[$p0]
                                            	[$p1]
                                            	[$p2]
                                            	[$split]
                                            	[$col]
                                            	[$k];
                                        } elseif ($_k instanceof Pivot_Callback) {
                                            $value = $_k->cbk($colValues);
                                        } else {
                                            $value = $colValues[$k];
                                        }
                                        $_out[self::concatKey($split,$col,$k)] = $value;
                                        // totals related
                                        if ($this->_lineTotal) {
                                            $_lineTotal[$k] += $value;
                                        }
                                        if ($this->_pivotTotal) {
                                            $p0Total[$split]
                                            	[$col]
                                            	[$k] += $value;
                                            $p1Total[$split]
                                            	[$col]
                                            	[$k] += $value;
                                        }
                                        if ($this->_fullTotal) {
                                            $fullTotal[$split]
                                            	[$col]
                                            	[$k] += $value;
                                        }
                                        
                                    }
                                }
                            }
                            
                            // totals related
                            if ($this->_lineTotal) {
                                foreach ($this->_columnValues as $_k) {
                                    $k = ($_k instanceof Pivot_Callback || 
                                    	$_k instanceof Pivot_Count) ? 
                                    	$_k->getKey() : $_k;
                                    $value = ($_k instanceof Pivot_Callback) ? 
                                        $_k->cbk($_lineTotal) : $_lineTotal[$k];
                                    $_out[self::TOTAL . "_{$k}"] = $value;
                                }
                            }
                            ////////////////////
                            
                            $out[] = $_out;
                        }
                    }
                    
                    // totals related
                    if ($this->_pivotTotal) {
                        $_out = $_lineTotal = array();
                        //$_out[self::_ID] = ++$cont;
                        if ($this->_typeMark) {
                            $_out['type'] = self::TYPE_PIVOT_TOTAL_LEVEL2;
                        }
                        $i = 0;
                        foreach ($this->_pivotOn as $pivotOn) {
                            if ($i == 0) {
                                $_out[$pivotOn] = self::TOTAL . "({$this->_pivotOn[0]})";
                            } else {
                                $_out[$pivotOn] = null;
                            }
                            $i++;
                        }
                        foreach ($p0Total as $split => $values) {
                            foreach ($values as $col => $colValues) {
                                foreach ($this->_columnValues as $_k) {
                                    $k = ($_k instanceof Pivot_Callback || $_k instanceof Pivot_Count) ? $_k->getKey() : $_k;
                                    $value = ($_k instanceof Pivot_Callback) ? 
                                        $_k->cbk($p0Total[$split][$col]) : $p0Total[$split][$col][$k];
                                    $_out[self::concatKey($split,$col,$k)] = $value;
                                    $_lineTotal[$k] += $value;
                                }
                            }
                        }
                        if ($this->_lineTotal) {
                            foreach ($this->_columnValues as $_k) {
                                $k = ($_k instanceof Pivot_Callback || $_k instanceof Pivot_Count) ? $_k->getKey() : $_k;
                                $value = ($_k instanceof Pivot_Callback) ? 
                                    $_k->cbk($_lineTotal) : $_lineTotal[$k];
                                $_out[self::TOTAL . "_{$k}"] = $value;
                            }
                        }
                        $out[] = $_out;
                    }
                    //////////////////
					
                    // totals related
                    if ($this->_pivotTotal) {
                        $_out = $_lineTotal = array();
                        //$_out[self::_ID] = ++$cont;
                        if ($this->_typeMark) {
                            $_out['type'] = self::TYPE_PIVOT_TOTAL_LEVEL1;
                        }
                        $i = 0;
                        foreach ($this->_pivotOn as $pivotOn) {
                            if ($i == 0) {
                                $_out[$pivotOn] = self::TOTAL . "({$this->_pivotOn[0]}, {$this->_pivotOn[1]})";
                            } else {
                                $_out[$pivotOn] = null;
                            }
                            $i++;
                        }
                        foreach ($p1Total as $split => $values) {
                            foreach ($values as $col => $colValues) {
                                foreach ($this->_columnValues as $_k) {
                                    $k = ($_k instanceof Pivot_Callback || $_k instanceof Pivot_Count) ? $_k->getKey() : $_k;
                                    $value = ($_k instanceof Pivot_Callback) ? 
                                        $_k->cbk($p1Total[$split][$col]) : $p1Total[$split][$col][$k];
                                    $_out[self::concatKey($split,$col,$k)] = $value;
                                    $_lineTotal[$k] += $value;
                                }
                            }
                        }
                        if ($this->_lineTotal) {
                            foreach ($this->_columnValues as $_k) {
                                $k = ($_k instanceof Pivot_Callback || $_k instanceof Pivot_Count) ? $_k->getKey() : $_k;
                                $value = ($_k instanceof Pivot_Callback) ? 
                                    $_k->cbk($_lineTotal) : $_lineTotal[$k];
                                $_out[self::TOTAL . "_{$k}"] = $value;
                            }
                        }
                        
                        $out[] = $_out;
                    }
                    ////////////////////////
                    
                }
                break;
            case 4:
            	foreach ($tmp as $p0 => $p0Values) {
            		$p0Total  = array();
            		foreach ($p0Values as $p1 => $p1Values) {
            			foreach ($p1Values as $p2 => $p2Values) {
            				foreach ($p2Values as $p3 => $p3Values) {
            				$_out = $_lineTotal = array();
            				
            				if ($this->_typeMark) {
            					$_out['type'] = self::TYPE_LINE;
            				}
            				$_out[$this->_pivotOn[0]] = $p0;
            				$_out[$this->_pivotOn[1]] = $p1;
            				$_out[$this->_pivotOn[2]] = $p2;
            				$_out[$this->_pivotOn[3]] = $p3;
            				
            				foreach (array_keys($this->_splits) as $split) {
            					$cols = $p3Values[$split];
            	
            					foreach (array_keys($this->_splits[$split]) as $col) {
            						$colValues = $cols[$col];
            						foreach ($this->_columnValues as $_k) {
            							$k = ($_k instanceof Pivot_Callback ||
            							$_k instanceof Pivot_Count) ?
            							$_k->getKey() : $_k;
            	
            							if ($_k instanceof Pivot_Count) {
            								$value = $tmpCount[$p0]
		            								[$p1]
		            								[$p2]
		            								[$p3]
		            								[$split]
		            								[$col]
		            								[$k];
            							} elseif ($_k instanceof Pivot_Callback) {
            								$value = $_k->cbk($colValues);
            							} else {
            								$value = $colValues[$k];
            							}
            							$_out[self::concatKey($split,$col,$k)] = $value;
            							// totals related
            							if ($this->_lineTotal) {
            								$_lineTotal[$k] += $value;
            							}
            							if ($this->_pivotTotal) {
            								$p0Total[$split]
            								[$col]
            								[$k] += $value;
            								$p1Total[$split]
            								[$col]
            								[$k] += $value;
            							}
            							if ($this->_fullTotal) {
            								$fullTotal[$split]
            								[$col]
            								[$k] += $value;
            							}
            	
            						}
            					}
            				}
            	
            				$out[] = $_out;
            			}
            			}
            		}
            	
            	}
            	break;
            	case 5:
            		foreach ($tmp as $p0 => $p0Values) {
            			$p0Total  = array();
            			foreach ($p0Values as $p1 => $p1Values) {
            				foreach ($p1Values as $p2 => $p2Values) {
            					foreach ($p2Values as $p3 => $p3Values) {
            						foreach ($p3Values as $p4 => $p4Values) {
            						$_out = $_lineTotal = array();
            	
            						if ($this->_typeMark) {
            							$_out['type'] = self::TYPE_LINE;
            						}
            						$_out[$this->_pivotOn[0]] = $p0;
            						$_out[$this->_pivotOn[1]] = $p1;
            						$_out[$this->_pivotOn[2]] = $p2;
            						$_out[$this->_pivotOn[3]] = $p3;
            						$_out[$this->_pivotOn[4]] = $p4;
            						
            						foreach (array_keys($this->_splits) as $split) {
            							$cols = $p4Values[$split];
            							 
            							foreach (array_keys($this->_splits[$split]) as $col) {
            								$colValues = $cols[$col];
            								foreach ($this->_columnValues as $_k) {
            									$k = ($_k instanceof Pivot_Callback ||
            									$_k instanceof Pivot_Count) ?
            									$_k->getKey() : $_k;
            									 
            									if ($_k instanceof Pivot_Count) {
            										$value = $tmpCount[$p0]
            											[$p1]
            											[$p2]
            											[$p3]
            											[$p4]
            											[$split]
            											[$col]
            											[$k];
            									} elseif ($_k instanceof Pivot_Callback) {
            										$value = $_k->cbk($colValues);
            									} else {
            										$value = $colValues[$k];
            									}
            									$_out[self::concatKey($split,$col,$k)] = $value;
            									// totals related
            									if ($this->_lineTotal) {
            										$_lineTotal[$k] += $value;
            									}
            									if ($this->_pivotTotal) {
            										$p0Total[$split]
            										[$col]
            										[$k] += $value;
            										$p1Total[$split]
            										[$col]
            										[$k] += $value;
            									}
            									if ($this->_fullTotal) {
            										$fullTotal[$split]
            										[$col]
            										[$k] += $value;
            									}
            									 
            								}
            							}
            						}
            						 
            						$out[] = $_out;
            					}}
            				}
            			}
            			 
            		}
            		break;
            case 6:
            			foreach ($tmp as $p0 => $p0Values) {
            				$p0Total  = array();
            				foreach ($p0Values as $p1 => $p1Values) {
            					foreach ($p1Values as $p2 => $p2Values) {
            						foreach ($p2Values as $p3 => $p3Values) {
            							foreach ($p3Values as $p4 => $p4Values) {
            								foreach ($p4Values as $p5 => $p5Values) {
            								$_out = $_lineTotal = array();
            								 
            								if ($this->_typeMark) {
            									$_out['type'] = self::TYPE_LINE;
            								}
            								$_out[$this->_pivotOn[0]] = $p0;
            								$_out[$this->_pivotOn[1]] = $p1;
            								$_out[$this->_pivotOn[2]] = $p2;
            								$_out[$this->_pivotOn[3]] = $p3;
            								$_out[$this->_pivotOn[4]] = $p4;
            								$_out[$this->_pivotOn[5]] = $p5;
            		
            								foreach (array_keys($this->_splits) as $split) {
            									$cols = $p5Values[$split];
            		
            									foreach (array_keys($this->_splits[$split]) as $col) {
            										$colValues = $cols[$col];
            										foreach ($this->_columnValues as $_k) {
            											$k = ($_k instanceof Pivot_Callback ||
            											$_k instanceof Pivot_Count) ?
            											$_k->getKey() : $_k;
            		
            											if ($_k instanceof Pivot_Count) {
            												$value = $tmpCount[$p0]
				            												[$p1]
				            												[$p2]
				            												[$p3]
				            												[$p4]
				            												[$p5]
				            												[$split]
				            												[$col]
				            												[$k];
            											} elseif ($_k instanceof Pivot_Callback) {
            												$value = $_k->cbk($colValues);
            											} else {
            												
            												
            												$value = $colValues[$k];
            											}
            											
            											$_out[self::concatKey($split,$col,$k)] = $value;
            											
            											// totals related
            											if ($this->_lineTotal) {
            												$_lineTotal[$k] += $value;
            											}
            											if ($this->_pivotTotal) {
            												$p0Total[$split]
            												[$col]
            												[$k] += $value;
            												$p1Total[$split]
            												[$col]
            												[$k] += $value;
            											}
            											if ($this->_fullTotal) {
            												$fullTotal[$split]
            												[$col]
            												[$k] += $value;
            											}
            		
            										}
            									}
            								}
            								 
            								$out[] = $_out;
            							}}
            						}
            					}
            				}
            		
            			}
            			break;
         // end switch
        }
        
        // totals related
        if ($this->_fullTotal) {
            $_out = $_lineTotal = array();
            //$_out[self::_ID] = ++$cont;
            if ($this->_typeMark) {
                $_out['type'] = self::TYPE_FULL_TOTAL;
            }
            $i = 0;
            foreach ($this->_pivotOn as $pivotOn) {
                if ($i == 0) {
                    $_out[$pivotOn] = self::TOTAL;
                } else {
                    $_out[$pivotOn] = null;
                }
                $i++;
            }
            foreach ($fullTotal as $split => $values) {
                foreach ($values as $col => $colValues) {
                    foreach ($this->_columnValues as $_k) {
                        $k = ($_k instanceof Pivot_Callback || $_k instanceof Pivot_Count) ? $_k->getKey() : $_k;
                        $value = ($_k instanceof Pivot_Callback) ? 
                            $_k->cbk($fullTotal[$split][$col]) : $fullTotal[$split][$col][$k];
                        $_out[self::concatKey($split,$col,$k)] = $value;
                        $_lineTotal[$k] += $value;
                    }
                }
            }
            if ($this->_lineTotal) {
                foreach ($this->_columnValues as $_k) {
                    $k = ($_k instanceof Pivot_Callback || $_k instanceof Pivot_Count) ? $_k->getKey() : $_k;
                    $value = ($_k instanceof Pivot_Callback) ? $_k->cbk($_lineTotal) : $_lineTotal[$k];
                    $_out[self::TOTAL . "_{$k}"] = $value;
                }
            }
            $out[] = $_out;
        }
        //

        $return = array();
        if (count($out) > 0) {
            switch ($fetchType) {
                case self::FETCH_STRUCT:
                    $return = array(
                        'splits'  => $this->_splits,
                        'data'    => array_map('array_values', $out),
                    );
                    break;
                default:
                    $return = $out;

            }
        }
        return $return;
    }

    const FETCH_STRUCT = 1;

    private function _getColumnItem($reg, $key){
        return $reg[$this->_pivotOn[$key]];
    }

    static function callback($key, $cbk)
    {
        return new Pivot_Callback($key, $cbk);
    }
    
    static function count($key)
    {
        return new Pivot_Count($key);
    }
    
    function newFetch($columns,$rows,$measures){
    	$this->_column = $columns;
    	$this->_columnValues = $measures;
    	$this->_pivotOn = $rows;
    	
    	
    	$tmp = $splits = $tmpCount = array();
    	$clen = count($columns)-1;
    	$rlen = count($rows)-1;
    	$mlen = count($measures)-1;
    	 
    	foreach ($this->_recordset as $reg) {
    
    		foreach ($measures as $item) {
    			$ref =& $tmp;
    			$sref =& $splits;
    			// assigning row split keys
    			foreach(range(0,$rlen) as $idx){
    				$k = $this->_getColumnItem($reg,$idx);
    				if(!isset($ref[$k]))
    				$ref[$k] = array();
    
    				$ref =& $ref[$k];
    			}
    	   
    			// assingning column split keys
    			foreach(range(0,$clen) as $idx){
    				$f = $columns[$idx];
    				$k = $reg[$f];
    
    				if(!isset($ref[$k]))
    				$ref[$k] = array();
    				$ref =& $ref[$k];
    
    				// only column splits assoc. array
    				if(!isset($sref[$reg[$f]]))
    				$sref[$reg[$f]] = array();
    				$sref =& $sref[$reg[$f]];
    			}
    	   
    			// assigning values
    			$ref[$item] += $reg[$item];
    			$sref[$item] = $item;
    		}
    	}
    	
    	$this->_splits = $splits;
    	System_Daemon::debug("new splits: ".print_r($splits,true));
    	System_Daemon::debug("new tmp: ".print_r($tmp,true));
    	$data = $this->newBuildOutput($tmp,$columns,$rows,$measures,$splits);
    	//System_Daemon::debug("new data: ".print_r($data,true));
    	return $data;
    }
    
    function newBuildOutput(&$tmp,&$columns,&$rows,&$measures,&$splits){
    	$out=array();
    	$clen = count($columns)-1;
    	$rlen = count($rows)-1;
    	$mlen = count($measures)-1;
    	
    	$code ="";
    	// begin row pivots
    	foreach(range(0,$rlen) as $idx){
    		if($idx==0){
    			$code .= 'foreach ($tmp as $p0 => $p0Values) {'."\n";
    		}else{
    			$i = $idx-1;
    			$code .= 'foreach ($p'.$i.'Values as $p'.$idx.' => $p'.$idx.'Values){'."\n";
    		}
    	}
    	// iteration array
    	$code.= '$_out=array();'."\n";
    	foreach(range(0,$rlen) as $idx){
    		$code.= '$_out[$rows['.$idx.']] = $p'.$idx.';'."\n";
    	}
    	
    	// column elements concatenation
    	$_aux=array();
    	foreach(range(0,$clen) as $idx){
    		if($idx==0){
    			$code.='foreach (array_keys($splits) as $s'.$idx.') {'."\n";

    			$code.='$spl'.$idx.'=$splits[$s'.$idx.'];'."\n";
    			$code.='$colValues = $p'.$rlen.'Values;'."\n";
    			array_push($_aux, '$s'.$idx);
    		}else{
    			$i=$idx-1;
    			$code.='foreach(array_keys($spl'.$i.') as $s'.$idx.'){'."\n";
    			$code.='$spl'.$idx.'=$spl'.$i.'[$s'.$idx.'];'."\n";
    			
    			array_push($_aux, '$s'.$idx);
    		}
    	}
    	
    	// measure concat
    	$code.='foreach ($measures as $k) {'."\n";
    	$_arraux="";
    	if(!empty($_aux)) $_arraux = "[".implode("][",$_aux)."]";
    	$code.='$value = $colValues'.$_arraux.'[$k];'."\n";
    	$code.='$_out[Pivot::concatKey('.implode(',',$_aux).',$k)] = $value;'."\n";
    	$code.='}'."\n";
    	
    	// close column elements
    	foreach(range(0,$clen) as $idx){
    		$code .='}'."\n";
    	}
    	
    	// new array item
    	$code.='$out[] = $_out;'."\n";
    	
    	// end row pivots
    	foreach(range(0,$rlen) as $idx){
    		$code .='}'."\n";
    	}
    	$code .= 'return $out;';
    	
    	$newfunc=create_function('&$tmp,&$out,&$columns,&$rows,&$measures,&$splits',$code);

    	return $newfunc($tmp,$out,$columns,$rows,$measures,$splits);
    }
    
    
}

class Pivot_Count
{
    private $_key = null;
    function __construct($key)
    {
        $this->_key = $key;
    }
    
    public function getKey()
    {
        return $this->_key;
    }
}

class Pivot_Callback
{
    private $_cbk = null;
    private $_key = null;
    function __construct($key, $cbk)
    {
        $this->_cbk = $cbk;
        $this->_key = $key;
    }
    
    public function getKey()
    {
        return $this->_key;
    }

    public function cbk()
    {
        return call_user_func_array($this->_cbk, func_get_args());
    }
}