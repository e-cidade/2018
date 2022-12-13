<?
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

//MODULO: contabilidade
$clconlancam->rotulo->label();

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
    $perini=$ini;
    $perfin=$fim; 
    //--- 
    // se informado estrutural, pesquisa se o estrutural tem reduzido,
    // se não tiver, pesquisa pelo estrutural
    //--
     if (isset($c61_reduz) && ($c61_reduz!="")) {
	$sql01 = db_planocontassaldo(db_getsession("DB_anousu"),$perini,$perfin,true,"c61_reduz=$c61_reduz ");
	$sql= "select c61_reduz,
		      saldo_anterior_debito as DL_Saldo_a_Debito,
		      saldo_anterior_credito as DL_Saldo_a_Credito,
		      saldo_final as DL_Saldo
	       from ($sql01) as X
	       where c61_reduz >0 ";

 	    $js_funcao="lancamentos|c61_reduz";
            db_lovrot($sql,18,"()","","$js_funcao");  

	 /*   $res = $clconlancamval->sql_record($sql);
	    if ($clconlancamval->numrows > 0){
	       db_criatabela($res);

	    }  
	   */

     } else if (isset($estrut) && $estrut !="") {
         // não implementado
         //  $r1 = $clconplanoreduz->sql_record($clconplanoreduz->sql_query(null,"c61_reduz",null,"c60_estrut=$estrut"));
    }


 }
?>