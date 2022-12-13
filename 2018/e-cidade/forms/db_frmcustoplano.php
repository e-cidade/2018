<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: custos
$clcustoplano->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cc01_estrutural");

?>
<form name="form1" method="post" action="<?$db_action?>">
  <fieldset>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Tcc01_sequencial?>">
          <?=@$Lcc01_sequencial?>
         </td>
         <td>
           <?
			db_input('cc01_sequencial',10,$Icc01_sequencial,true,'text',"3","")
		   ?>
         </td>
       </tr>
       <tr>
         <td nowrap>
         </td>
         <td>
         <?

		   $sWhere  = "cc09_anousu = ".db_getsession("DB_anousu");
		   $sWhere .= " and "; 
		   $sWhere .= "cc09_instit = ".db_getsession("DB_instit");
	   
		   $rsConsultaParam = $clparcustos->sql_record($clparcustos->sql_query(null, "cc09_mascaracustoplano", null, $sWhere));

		   if ($clparcustos->numrows > 0) {

		   	 $cldb_estrut->autocompletar = true;
		     $cldb_estrut->mascara  = true;
		     $cldb_estrut->reload   = false;
		     $cldb_estrut->input    = false;
	       $cldb_estrut->size     = 30;
		     $cldb_estrut->nome     = "cc01_estrutural";
		     $cldb_estrut->db_opcao = $db_opcao;

		     //retorna a máscara da conta
		     $oRetornoParam = db_utils::fieldsMemory($rsConsultaParam,0);
		     $cldb_estrut->db_mascara($oRetornoParam->cc09_mascaracustoplano);

		   /*
		   * avisa o usuário que ele precisa configurar o tipo de estrutural antes de cadastrar uma conta
		   * MENUS: Procedimentos > Manutenção de parâmetros
           */
     	   } elseif ($clparcustos->numrows == 0) {
     	   	
     	   	db_msgbox("Parâmetros de custos não configurados. Verifique!");
            $db_botao = false;
            
     	   }
     	   
		 ?>
        </td>
     </tr>
        <tr>
        <td>
          <b> Analítica: </b>
        </td>
        <td>
         <?

        if ( ($db_opcao == 22 or $db_opcao == 2) && ( isset($db_opcao) ) && isset($cc01_sequencial) ) {
        	
	    /*
	    * verifica se a conta é analitica, se for sintética o array recebe a ordem do select padrão,
		* se não for analítica o array recebe o valor de false e bloqueia a aba conforme o tipo de seleção 
		* (analítica ou sitética)
        */
   	    $clcustoplanoanalitica->sql_record($clcustoplanoanalitica->sql_query_file(null, "cc04_sequencial", null ,"cc04_custoplano = {$cc01_sequencial}"));
   	    
   	      // se maior que zero indica que é analítica
	      if ($clcustoplanoanalitica->numrows > 0) {
     
		    $aAnalitica = array("s"=>"Sim","n"=>"Não");
   	        echo "<script> js_db_libera(true); </script> \n";

   	      // se não conta não é analítica   
          } else {

            $aAnalitica = array("n"=>"Não","s"=>"Sim");
		    echo "<script> js_db_libera(false); </script> \n";
		   
          } 
         
	      // carrega opções conforme array   
 		  db_select("analitico", $aAnalitica,"true",$db_opcao,"onchange='js_esconder_campos();'");

 	   // se a conta estiver sendo incluída exibe valores padrões   
	   } else {	 	
	   	
		  $aAnalitica = array("s"=>"Sim","n"=>"Não");
		  db_select("analitico", $aAnalitica,"true",$db_opcao,"onchange='js_esconder_campos();'");
		  echo "<script> js_db_libera(false); </script> \n";
		  
	   }
	   
		 ?>
       </td>
     </tr>
     <tr id="depart">
       <td nowrap title="Departamento">
         <b>Departamento: </b>
       </td>
       <td>
          <?
            // retorna data da sessao formatada   
	 		$dateSession      = date("Y-m-d",db_getsession('DB_datausu'));
	 		$sSqlDepartamento = $cldb_depart->sql_query_file(null,"coddepto, descrdepto", null, "limite <= '$dateSession' or limite is null");
			$rsDepart         = $cldb_depart->sql_record($sSqlDepartamento);
			
			
			db_selectrecord("coddepto", $rsDepart, true, $db_opcao);
	      ?>
       </td>
     </tr>
     <tr id="ident">
       <td>
         <b>Ident. da conta:</b>
       </td>
       <td>
          <?
              
              /*
               * verifica se existe alguma descrição de conta associada a tabela custoplanotipoconta
               * e esconde o campo "ident. da conta" quando a conta for sintética, ou seja, só exibe
               * esse campo para o usuário, no form, se a conta for analítica.
               */

              $rsConsultaCustoTipo = $clcustotipoconta->sql_record($clcustotipoconta->sql_query_file(null, "cc02_sequencial, cc02_descricao"));
              
              if( ( ($db_opcao == 1 or $db_opcao == 11) or ($db_opcao == 2 or $db_opcao == 22) or ($db_opcao == 3 or $db_opcao == 33) ) and ($clcustotipoconta->numrows > 0) ) {

              	if (isset($cc01_sequencial) && $clcustoplanoanalitica->numrows > 0) {              	
              	
              	  // retorna o campo cc02_sequencial
              	  $sSql  = " select custoplanotipoconta.cc03_custotipoconta                                                                          ";
              	  $sSql .= "   from custoplanotipoconta                                                                                              ";
              	  $sSql .= "     left join custoplanoanalitica on custoplanotipoconta.cc03_custoplanoanalitica = custoplanoanalitica.cc04_sequencial ";
              	  $sSql .= "     left join custoplano          on custoplano.cc01_sequencial = custoplanoanalitica.cc04_custoplano                   ";
              	  $sSql .= " where cc04_custoplano = {$cc01_sequencial}                                                                              ";

              	  $rsConsultaCampo = db_query($sSql);
              	  $oRetornoSeqCustoTipo = db_utils::fieldsMemory($rsConsultaCampo,0);
              	  $cc02_sequencial= $oRetornoSeqCustoTipo->cc03_custotipoconta;
              	}

              	db_selectrecord("cc02_sequencial", $rsConsultaCustoTipo, true, $db_opcao);
              
              } else {
              	
                db_msgbox("É necessário inserir uma identificação para a conta antes de continuar!");
                $aOpcao = array("valorNulo"=>"");
		        db_select("ident", $aOpcao, "true", $db_opcao, "onchange='js_esconder_depart();'");
              	$db_botao = false;
              }
       		    
       		   ?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=@$Tcc01_descricao?>">
         <?=@$Lcc01_descricao?>
       </td>
       <td>
         <?
           db_input('cc01_descricao',50,$Icc01_descricao,true,'text',$db_opcao,"")
         ?>
       </td>
     </tr>
       <tr>
       <td nowrap title="<?=@$Tcc01_obs?>">
         <?=@$Lcc01_obs?>
       </td>
       <td>
         <?
           db_textarea('cc01_obs',5,48,$Icc01_obs,true,'text',$db_opcao,"");
         ?>
       </td>
     </tr>
   </table>
   </fieldset>
   <center>
     <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
        <?=($db_botao==false?"disabled":"")?>
        ><input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
        <?
        if ($db_opcao != 1) { 
          echo '<input name="novo" type="button" id="novo" value="novo" onclick="parent.location.href=\'cus1_planocustoaba001.php\'">';
        }
        ?>
  </center>
</form>
<script>

        function js_pesquisacc01_instit(mostra){
          if(mostra==true){
            js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
          }else{
             if(document.form1.cc01_instit.value != ''){ 
                js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.cc01_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
             }else{
               document.form1.nomeinst.value = ''; 
             }
          }
        }

        function js_mostradb_config(chave,erro){
          document.form1.nomeinst.value = chave; 
          if(erro==true){ 
            document.form1.cc01_instit.focus(); 
            document.form1.cc01_instit.value = ''; 
          }
        }

        function js_mostradb_config1(chave1,chave2){
          document.form1.cc01_instit.value = chave1;
          document.form1.nomeinst.value = chave2;
          db_iframe_db_config.hide();
        }

        function js_pesquisa(){
          js_OpenJanelaIframe('','db_iframe_custoplano','func_custoplano.php?funcao_js=parent.js_preenchepesquisa|cc01_sequencial','Pesquisa',true);
        }

        function js_preenchepesquisa(chave){
          db_iframe_custoplano.hide();
          <?
          if($db_opcao!=1){
            echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
          }
          ?>
        }
   
		function js_esconder_campos() {
			
  		  if (document.form1.analitico.value == "s"){
   		    document.getElementById("depart").style.display = "";
            document.getElementById("ident").style.display  = "";   	   	  
  	   	  } else {
  	   	    document.getElementById("ident").style.display  = "none";
   		    document.getElementById("depart").style.display = "none";
            document.form1.coddepto.value = "";
	      }
		}
		
		function js_aba() {
		  if (document.form1.analitico.value == "s") {
		    parent.document.formaba.custoanaliticabens.disabled=false;
		  } else {
		    parent.document.formaba.custoanaliticabens.disabled=true;	
		  }
		}	
		
</script>