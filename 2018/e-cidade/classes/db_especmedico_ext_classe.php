<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE especmedico
include("classes/db_agendamentos_ext_classe.php");
require("classes/db_especmedico_classe.php");


class cl_especmedico_ext extends cl_especmedico  {
 	// funcao para alteracao
 	function alterar_ext ($sd27_i_codigo=null) {
 		$clagendamentos = new cl_agendamentos_ext;
 		$data = "'".date("Y/m/d", time())."'";
 		$result_agenda = db_query($clagendamentos->sql_query_ext(null, "count(*) as total_agendado",null,"sd27_i_codigo = $sd27_i_codigo and sd23_i_situacao = 1 and sd23_d_consulta >= $data"));
 		
 		if( pg_result($result_agenda,0,"total_agendado") > 0 ){
 			/*
 			$this->erro_sql = " Profissional possui agendamentos para data posterior. Alteraчуo nуo efetuada.\\n";
 			$this->erro_campo = "sd27_i_codigo";
 			$this->erro_banco = "";
 			$this->erro_msg   = "Usuсrio: \\n\\n ".$this->erro_sql." \\n\\n";
 			$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
 			$this->erro_status = "0";
 			return false;
			*/
 			db_msgbox("Profissional possui agendamentos para data posterior."); 			 			
 		}else{
 		}
 		$this->alterar($sd27_i_codigo);
 	} 
}
?>