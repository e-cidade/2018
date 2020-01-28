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
?>
<script type="text/javascript" src="skins/js.php?file=jquery-1.7.1.js" ></script>
<script>     
  function loadframe() {
    var j = jQuery.noConflict();
    
    j(function(){
      var tdArray = document.getElementsByTagName("td"); 
      for (var i=0; i<tdArray.length; i++){
        tdArray[i].removeAttribute("bgcolor");
      }
    });
  }

</script>
<frameset id="quadroprincipal" rows="100,*,19" cols="*" framespacing="0" frameborder="NO" border="0" onUnload="js_fechaJanela();">
  <frame src="topo.php?uso=<?=$uso?>" name="topo" scrolling="no" noresize id="topo">
  <frame src="instit.php" name="corpo" frameborder="no" scrolling="auto" noresize id="corpo" onload="loadframe">
  <frame src="status.php" name="bstatus" frameborder="yes" scrolling="NO" noresize bordercolor="#000000" id="bstatus">
</frameset>
<noframes>
  <body>
  </body>
</noframes>