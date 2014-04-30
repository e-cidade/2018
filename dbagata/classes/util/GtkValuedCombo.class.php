<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

class GtkValuedCombo extends GtkCombo
{
    function GtkValuedCombo()
    {
        GtkCombo::GtkCombo();
        $entry = $this->entry;
        $entry->set_editable(FALSE);
    }

    function set_popdown_strings($strings) {
        return $this->set_popdown_items($strings); // Ok, o set_popdown_items pode cuidar disso
    }

    // Essa funзгo tem que refazer o trabalho da funзгo set_popdown_strings do GTK+.
    // Como essa funзгo nгo й implementada no PHP-GTK, ja que isso й apenas um bind,
    // o cуdigo dessa funзгo estб baseado no cуdigo C do GTK+
    function set_popdown_items($items)
    {
        if(is_array($items))
        {
            $list = $this->list;
            $list->clear_items(0,-1);
            foreach($items as $item)
            {
                $this->add($item);
            }
        }
    }

    function add($item)
    {
        if(is_array($item)) // if it is a key -> value
        {
            $list_item = new GtkListItem($item[0]);
            $list_item->set_data(0, $item[1]);
            $this->items_list[$item[1]] = $list_item;
        }
        else
        {
            $list_item = new GtkValuedListItem($item);
            $list_item->set_data(0, $item);
            $this->items_list[$item] = $list_item;
        }
        
        $list_item->show();
        $list = $this->list;
        $list->add($list_item);
    }

    function get_value() {
        $list = $this->list;
        $selection = $list->selection;
        if ($selection)
        {
            return $selection[0]->get_data(0);
        }
    }
    
    function get_text()
    {
        //var_dump($this->get_value());
        return $this->get_value();
    }
    
    // BC reasons
    function set_text($value)
    {
        $list = $this->list;
        if (isset($this->items_list[$value]))
        {
            $list->select_child($this->items_list[$value]);
        }
    }
    
    // BC reasons
    function set_editable($bool)
    {
        $entry = $this->entry;
        $entry->set_editable($bool);
    }
}
?>