<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


//MODULO: pessoal
$clinssirf->rotulo->label();
?>
<form name="form1" method="post" action="pes1_inssirf002.php">
<center>
<table border="0">
  <tr>
    <td nowrap title="Selecione a tabela">
      <b><?=$Lr33_codtab?></b>
    </td>
    <td>
      <?php
			$arr_tabelas    = Array("1" => "IRRF",
															"2" => "IRRF mês anterior",
															"3" => "INSS",
															"4" => "Previdência 2",
															"5" => "Previdência 3",
															"6" => "Previdência 4");

			$sSqlTabelas = $clinssirf->sql_query_file(null, 
																								null,
																								"distinct r33_codtab as codtab,r33_nome", 
																								"r33_codtab", 
																								"r33_anousu     = ".db_anofolha()." 
																								 and r33_mesusu = ".db_mesfolha()." 
																								 and r33_codtab::text::int > 2 
																								 and r33_instit =".db_getsession("DB_instit"));

      $result_tabelas = $clinssirf->sql_record($sSqlTabelas);

			for($i = 0; $i < $clinssirf->numrows; $i++) {

				db_fieldsmemory($result_tabelas, $i);
				$arr_tabelas[$codtab] = $r33_nome;
      }

      $codtab = 1;
      db_select("codtab", $arr_tabelas, true, 1);
      ?>
    </td>
  </tr>
</table>
</center>
<input name="enviar" type="submit" id="db_opcao" value="Enviar" onblur='js_tabulacaoforms("form1","codtab",true,1,"codtab",true);'>
</form>