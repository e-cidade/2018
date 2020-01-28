<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");

require_once("classes/db_inicial_classe.php");
require_once("classes/db_inicialmov_classe.php");
require_once("classes/db_inicialnumpre_classe.php");
require_once("classes/db_inicialcert_classe.php");
require_once("classes/db_parjuridico_classe.php");


$oDaoInicial       = new cl_inicial();
$oDaoInicialmov    = new cl_inicialmov();
$oDaoInicialnumpre = new cl_inicialnumpre();
$oDaoInicialcert   = new cl_inicialcert();
$oDaoParjuridico   = new cl_parjuridico();

$rsParjuridico = $oDaoParjuridico->sql_record($oDaoParjuridico->sql_query_file(db_getsession('DB_anousu'), db_getsession('DB_instit')));
$oParJuridico  = db_utils::fieldsMemory($rsParjuridico, 0);

if ($oParJuridico->v19_envolinicialiss == '') {
  db_redireciona("db_erros.php?db_erro=Verifique os parametros do juridico [Envolvidos na Inicial (Empresa)].");
}

$sCampos     = "a.z01_nome,                                                                       ";
$sCampos    .= "inicial.v50_inicial,                                                              ";
$sCampos    .= "inicial.v50_data,                                                                 ";
$sCampos    .= "case when inicial.v50_situacao = 1 then 'Ativa' else 'Anulada' end as v50_situacao";

$sSqlInicial = $oDaoInicial->sql_query($v50_inicial, $sCampos);
$rsInicial   = $oDaoInicial->sql_record($sSqlInicial);

if ( !$rsInicial || $oDaoInicial->numrows == 0) {
  db_redireciona("db_erros.php?db_erro=Erro ao consultar inicial.");
}

$oInicial = db_utils::fieldsMemory($rsInicial, 0, true);

?>
<html>
<head>
  <?php 
    db_app::load("estilos.css, grid.style.css, tab.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js, DBToogle.widget.js");
  ?>
<style>
table.linhaZebrada {
  width: 600px;
}

table.linhaZebrada tr td:nth-child(even) {
  background-color: #FFF;
}

table.linhaZebrada tr td:nth-child(odd) {
  font-weight:bold;
  width:150px;
}
</style> 
</head>
<body bgcolor=#CCCCCC>
<fieldset>
  <legend><strong>Inicial</strong></legend>
  
  <table class="linhaZebrada">
    <tr>
      <td>
        <strong>
          Número:
        </strong>
      </td>
      <td>
        <?php
          echo $oInicial->v50_inicial; 
        ?>
      </td>
    </tr>
    
    <tr>
      <td>
        <strong>
          Data da Inicial:
        </strong>
      </td>
      <td>
        <?php
          echo $oInicial->v50_data;
        ?>
      </td>
    </tr>
    
    <tr>
      <td>
        <strong>
          Advogado:
        </strong>
      </td>
      <td>
        <?php  
          echo $oInicial->z01_nome; 
        ?>
      </td>
    </tr>
    
    <tr>
      <td>
        <strong>
          Situacao:
        </strong>
      </td>
      <td>
        <?php  
          echo $oInicial->v50_situacao; 
        ?>
      </td>
    </tr>
    
  </table>
  
</fieldset>

<fieldset>
  <legend><strong>Dados da Inicial</strong></legend>
    <?php 
      $oTabDetalhesInicial = new verticalTab("consultaEnvolvidos", 250);
      
      $oTabDetalhesInicial->add("envolvidos",
                                "Envolvidos",
                                "func_inicialmovcertenvolvidos.php?inicial=" . $oInicial->v50_inicial);
      
      $oTabDetalhesInicial->add("numpres",
                                "Numpres Relacionados",
                                "func_inicialmovcertnumpres.php?inicial=" . $oInicial->v50_inicial);
      
      $oTabDetalhesInicial->add("certidoes",
                                "Certidões Emitidas",
                                "func_inicialmovcertcertidoes.php?inicial=" . $oInicial->v50_inicial);
      
      $oTabDetalhesInicial->add("movimentacoes",
                                "Movimentações da Inicial",
                                "func_inicialmovcertmovimentacoes.php?inicial=" . $oInicial->v50_inicial);
      
      
      $oTabDetalhesInicial->show();
    ?>
</fieldset>
<center>
  <br/>
  <input type="button" value="Fechar" onclick="parent.js_fecharConsulta()" />
</center>
</body>
</html>