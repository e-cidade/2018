<?php
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_certid_classe.php"));
require_once(modification("libs/db_sql.php"));

$clcertid = new cl_certid;
$clrotulo = new rotulocampo;
$clrotulo->label("v13_certid");
$clrotulo->label("v13_dtemis");
$clrotulo->label("v13_login");

if(isset($matric)){

  $tabela = 'arrematric';
  $campo  = 'k00_matric';
  $valor  = $matric;
}else if(isset($inscr)){

  $tabela = 'arreinscr';
  $campo  = 'k00_inscr';
  $valor  = $inscr;
}else if(isset($numcgm)){

  $tabela = 'arrenumcgm';
  $campo  = 'arrenumcgm.k00_numcgm';
  $valor  = $numcgm;
}else if(isset($Parcelamento)){

	$tabela = 'arrenumcgm';
	$campo  = 'arrenumcgm.k00_numcgm';
	$valor  = pg_result(db_query("select v07_numcgm from termo where v07_parcel = $Parcelamento"),0,0);
}

$iInstituicao = db_getsession('DB_instit');

if (isset($inicial)) {

  $sql = " select v14_certid as certid,
                  'd' as modo
			       from inicial
			 	 				inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial
			 	 				inner join inicialcert   on inicialcert.v51_inicial   = inicial.v50_inicial
			 	 				inner join certid        on inicialcert.v51_certidao  = certid.v13_certid
			 	 				inner join certdiv       on certid.v13_certid         = certdiv.v14_certid
			 	 				inner join divida        on divida.v01_coddiv         = certdiv.v14_coddiv
			      where v50_inicial = $inicial
              and v50_instit  = {$iInstituicao}
		        union
			     select v14_certid as certter, 'p' as modo
			 	 	 from inicial
			 	 				inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial
			 	 				inner join inicialcert   on inicialcert.v51_inicial   = inicial.v50_inicial
			 	 				inner join certid        on inicialcert.v51_certidao  = certid.v13_certid
			 	 				inner join certter       on certter.v14_certid        = certid.v13_certid
			 	 				inner join termo         on termo.v07_parcel          = certter.v14_parcel
			 	 	where v50_inicial = $inicial
            and v50_instit  = {$iInstituicao} ";
} else {

   $sql = " select v14_certid as certid, 'd' as modo
			 	 	  from $tabela
			 	 	       inner join arrecad    on $tabela.k00_numpre    = arrecad.k00_numpre
			 	 	       inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
			                            		and arreinstit.k00_instit = {$iInstituicao}
			 	 	       inner join arretipo   on arrecad.k00_tipo      = arretipo.k00_tipo
			 	 	       inner join cadtipo    on arretipo.k03_tipo     = cadtipo.k03_tipo
			 	 	       inner join divida     on divida.v01_numpre     = arrecad.k00_numpre
                                      and divida.v01_numpar     = arrecad.k00_numpar
			 	 	       inner join certdiv    on divida.v01_coddiv = certdiv.v14_coddiv
			 	 	 where $campo           = $valor
               and cadtipo.k03_tipo = 15
			  	   union
			 	   select v14_certid as certter, 'p' as tipo
			        from $tabela
			 	 	       inner join arrecad    on $tabela.k00_numpre    = arrecad.k00_numpre
			 	 	     	 inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
			                           		  and arreinstit.k00_instit = {$iInstituicao}
			 	 	       inner join arretipo   on arrecad.k00_tipo      = arretipo.k00_tipo
			 	 	       inner join cadtipo    on arretipo.k03_tipo     = cadtipo.k03_tipo
			 	 	       inner join termo      on termo.v07_numpre      = arrecad.k00_numpre
			 	 	       inner join certter    on certter.v14_parcel    = termo.v07_parcel
			 	 	 where $campo           = $valor
             and cadtipo.k03_tipo = 15  ";

}

$result  = db_query($sql) or die($sql);
$numrows = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" src="scripts/scripts.js"></script>
  <style type="text/css">
  .borda {
  	border-right-width: 1px;
  	border-right-style: solid;
  	border-right-color: #000000;
  }
  </style>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
</head>
<body onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">

<form name="form1" method="post" action="">
<table align="center">
<?php

  $sMensagemErro = '';

  if($numrows > 0){

    try{

      echo "
            <tr bgcolor=\"#FFCC66\">   \n
              <th class=\"borda\" style=\"font-size:11px\" nowrap>O</td>\n
              <th class=\"borda\" style=\"font-size:11px\" nowrap>$Lv13_certid</th>\n
              <th class=\"borda\" style=\"font-size:11px\" nowrap>$Lv13_dtemis</th>\n
              <th class=\"borda\" style=\"font-size:11px\" nowrap>$Lv13_login</th>\n
              <th class=\"borda\" style=\"font-size:11px\" nowrap align='center'><b>Tipo</b></th>\n
              <th class=\"borda\" title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap>Val His.</th>\n
              <th class=\"borda\" title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap>Val Cor.</th>\n
              <th class=\"borda\" title=\"Valor Juros\" style=\"font-size:11px\" nowrap>Jur.</th>\n
              <th class=\"borda\" title=\"Valor Multa\" style=\"font-size:11px\" nowrap>Mul.</th>\n
              <th class=\"borda\" title=\"Valor Desconto\" style=\"font-size:11px\" nowrap>Desc.</th>\n
              <th class=\"borda\" title=\"Total a Pagar\" style=\"font-size:11px\" nowrap>Tot.</th>\n
            </tr> ";

      for($i=0; $i < $numrows; $i++){

        $color = '"#E4F471"';
        if( $i%2 == 0){
          $color = '"#EFE029"';
        }

        db_fieldsmemory($result,$i);

        $SqlNumpreCertidao = "";
        if($modo == "d"){

  				$mododescr         = "Dívida ativa";
  				$funcao            = "js_certdiv('$certid');";
          $SqlNumpreCertidao = "select distinct v01_numpre            as numpre,
                                                array_agg(v01_numpar) as numpar
                                  from ( select v01_numpre, v01_numpar
                                           from certdiv
                                                inner join divida  on divida.v01_coddiv = certdiv.v14_coddiv
                                                                  and divida.v01_instit = {$iInstituicao}
                                 where v14_certid = {$certid}) as sub_query
                              group by v01_numpre";
        }else if($modo == "p"){

  				$mododescr         = "Parcelamento";
  				$funcao            = "js_certter('$certid');";
          $SqlNumpreCertidao = "select distinct v07_numpre as numpre
                                  from certter
                                       inner join termo  on termo.v07_parcel = certter.v14_parcel
                                                        and termo.v07_instit = {$iInstituicao}
                                 where v14_certid = {$certid}";
        }

        $sSqlDadosCertidao = $clcertid->sql_query("","*",null," certid.v13_instit = {$iInstituicao} and v13_certid = {$certid} ");
        $rsDadosCertidao   = $clcertid->sql_record($sSqlDadosCertidao);
        if(!$rsDadosCertidao){
          throw new Exception("Erro ao pesquisar dados da certidão");
        }

        db_fieldsmemory($rsDadosCertidao, 0);

        /**
         * Calcular valores da certidão
         */
        $rsNumpreCertidao = db_query($SqlNumpreCertidao);
        if(!$rsNumpreCertidao){
          throw new Exception("Erro ao pesquisar numpres da certidão");
        }
        $iTotalNumpresCertidao = pg_numrows($rsNumpreCertidao);

        $lrhis      = '';
        $lrcor      = '';
        $lrjuros    = '';
        $lrmulta    = '';
        $lrdesconto = '';
        $otal       = '';
        $sDataUsu   = db_getsession("DB_datausu");
        $iAnousu    = db_getsession("DB_anousu");

        /**
         * Percorre os numpres vinculados a certidao
         */
        for ($Indice = 0; $Indice < $iTotalNumpresCertidao; $Indice++) {

          $oNumpreNumparDebito = db_utils::fieldsMemory( $rsNumpreCertidao, $Indice );
          $iNumpre             = $oNumpreNumparDebito->numpre;
          $sWhere              = '';

          /**
           * Se for cda de dvida deve considerar apenas os numpars do numpre e nao o numpre inteiro
           */
          if($modo == 'd'){

            $sNumpar = str_replace("}", '', str_replace("{", '', $oNumpreNumparDebito->numpar));
            $sWhere  = " and y.k00_numpre = {$iNumpre} and y.k00_numpar in ($sNumpar) ";
          }

          $rsDebitosNumpre = debitos_numpre($iNumpre, 0, 0, $sDataUsu, $iAnousu, 0, "", "", " and y.k00_hist <> 918 $sWhere ");

          if($rsDebitosNumpre){

            $iTotalQuantidadeDebitos = pg_numrows($rsDebitosNumpre);
            for($iIndiceDebito = 0; $iIndiceDebito < $iTotalQuantidadeDebitos; $iIndiceDebito++){

              $oRegistroNumpre = db_utils::fieldsMemory($rsDebitosNumpre, $iIndiceDebito);
      			  db_fieldsmemory($rsDebitosNumpre, $iIndiceDebito);

      			  $lrhis      += $vlrhis;
      			  $lrcor      += $vlrcor;
      			  $lrjuros    += $vlrjuros;
      			  $lrmulta    += $vlrmulta;
      			  $lrdesconto += $vlrdesconto;
      			  $otal       += $total;
            }
          }

       }

       /**
        * Dados do usuario
        */
       $oDaoUsuarios     = new cl_db_usuarios();
       $sSqlDadosUsuario = $oDaoUsuarios->sql_query( $v13_login, "login" );
       $rsDadosUsuario   = $oDaoUsuarios->sql_record( $sSqlDadosUsuario );
       $sLogin = '';
       if( $rsDadosUsuario ){
        $sLogin = db_utils::fieldsMemory( $rsDadosUsuario, 0 )->login;
       }
       $v13_dtemis = db_formatar($v13_dtemis, "d");

       echo "
          <tr bgcolor=$color>\n
            <td class=\"borda\" style=\"font-size:11px\" nowrap><a href='#' onclick=\"$funcao return false;\">MI</a></td>\n
            <td class=\"borda\" style=\"font-size:11px\" nowrap>$certid</td>\n
            <td class=\"borda\" style=\"font-size:11px\" nowrap>$v13_dtemis</td>\n
            <td class=\"borda\" style=\"font-size:11px\" nowrap>$sLogin</td>\n
            <td class=\"borda\" style=\"font-size:11px\" nowrap>$mododescr</td>\n
				    <td class=\"borda\" title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap>".db_formatar($lrhis, 'f')."</td>\n
				    <td class=\"borda\" title=\"Valor Corrigido\" style=\"font-size:11px\" nowrap>".db_formatar($lrcor, 'f')."</td>\n
				    <td class=\"borda\" title=\"Valor Juros\" style=\"font-size:11px\" nowrap>$lrjuros</td>\n
				    <td class=\"borda\" title=\"Valor Multa\" style=\"font-size:11px\" nowrap>$lrmulta</td>\n
				    <td class=\"borda\" title=\"Valor Desconto\" style=\"font-size:11px\" nowrap>".db_formatar($lrdesconto,'f')."</td>\n
				    <td class=\"borda\" title=\"Total a Pagar\" style=\"font-size:11px\" nowrap>".db_formatar($otal,'f')."</td>\n
				  </tr> ";

     }

    } catch (Exception $eErro){
      $sMensagemErro = $eErro->getMessage();
    }
  }
?>
  <tr>
    <td><small><?php echo $sMensagemErro; ?></small></td>
  </tr>
</table>
</form>
</body>
</html>
<script type="text/javascript">
  function js_certter(certid){
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe2', 'cai3_gerfinanc042.php?certid='+certid+'&tipo=<?=$tipo?>', 'Pesquisa', true);
  }
  function js_certdiv(certid){
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe2', 'cai3_gerfinanc041.php?certid='+certid+'&tipo=<?=$tipo?>', 'Pesquisa', true);
  }
</script>