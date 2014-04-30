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

	class cl_tarefasolic {
		var $numrows;
		var $erro_banco;
		var $erro_sql;
		var $erro_msg;
		var $erro_status;
		
		function sql_record ($sql) {
		     $result = @pg_query($sql);
		     if($result==false){
		       $this->numrows    = 0;
		       $this->erro_banco = str_replace("\n","",@pg_last_error());
		       $this->erro_sql   = "Erro ao selecionar os registros.";
		       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
		       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
		       $this->erro_status = "0";
		       return false;
		     }
		     $this->numrows = pg_numrows($result);
		      if($this->numrows==0){
		        $this->erro_banco = "";
		        $this->erro_sql   = "Record Vazio na Tabela:db_usuclientes";
		        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
		        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
		        $this->erro_status = "0";
		        return false;
		      }
		     return $result;
		}
		function sql_query_file ($db_opcao,$dbwhere="1") {
			 $sql = "select at20_usuario, at10_nome, 'S' as tipo 
                     from atendimentousu
        			 	  inner join atenditem   on at05_codatend = at20_codatend
			              inner join atendimento on at02_codatend = at20_codatend ";
             if($db_opcao==2||$db_opcao==3||$db_opcao==22||$db_opcao==33) {
		         $sql .= "inner join tarefaitem     on at44_atenditem = at05_seq
					      inner join tarefaclientes on at70_tarefa    = at44_tarefa
        				  inner join db_usuclientes on at10_codcli    = at70_cliente and 
                                                       at10_usuario   = at20_usuario ";
             }                                          
             else {
             	 $sql .= "inner join db_usuclientes on at10_codcli  = at02_codcli and 
                                                       at10_usuario = at20_usuario ";
             }
        	 $sql .= "where ".$dbwhere."
					  union
					  select at21_usuario, at10_nome, 'E' as tipo
                      from atenditemusu
			               inner join atendimento on at02_codatend = at21_codatend ";
             if($db_opcao==2||$db_opcao==3||$db_opcao==22||$db_opcao==33) {
                 $sql .= "inner join tarefaitem     on at44_atenditem = at21_atenditem
					      inner join tarefaclientes on at70_tarefa    = at44_tarefa
                          inner join db_usuclientes on at10_codcli    = at70_cliente and 
                                                       at10_usuario = at21_usuario ";
             }
             else {
             	 $sql .= "inner join atenditem      on at05_codatend = at21_codatend
                          inner join db_usuclientes on at10_codcli  = at02_codcli and 
                                                       at10_usuario = at21_usuario ";
             }                                          
        	 $sql .= "where ".$dbwhere;

		     return $sql;
		}
	}
?>