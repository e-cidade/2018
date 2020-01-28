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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_menu
class cl_db_menu { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $id_item = 0; 
   var $id_item_filho = 0; 
   var $menusequencia = 0; 
   var $modulo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 id_item = int4 = Código do ítem 
                 id_item_filho = int4 = Item filho 
                 menusequencia = int4 = Seqüência 
                 modulo = int4 = Módulo 
                 ";
   //funcao construtor da classe 
   function cl_db_menu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_menu"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->id_item = ($this->id_item == ""?@$GLOBALS["HTTP_POST_VARS"]["id_item"]:$this->id_item);
       $this->id_item_filho = ($this->id_item_filho == ""?@$GLOBALS["HTTP_POST_VARS"]["id_item_filho"]:$this->id_item_filho);
       $this->menusequencia = ($this->menusequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["menusequencia"]:$this->menusequencia);
       $this->modulo = ($this->modulo == ""?@$GLOBALS["HTTP_POST_VARS"]["modulo"]:$this->modulo);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->id_item == null ){ 
       $this->erro_sql = " Campo Código do ítem nao Informado.";
       $this->erro_campo = "id_item";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->id_item_filho == null ){ 
       $this->erro_sql = " Campo Item filho nao Informado.";
       $this->erro_campo = "id_item_filho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->menusequencia == null ){ 
       $this->erro_sql = " Campo Seqüência nao Informado.";
       $this->erro_campo = "menusequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->modulo == null ){ 
       $this->erro_sql = " Campo Módulo nao Informado.";
       $this->erro_campo = "modulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_menu(
                                       id_item 
                                      ,id_item_filho 
                                      ,menusequencia 
                                      ,modulo 
                       )
                values (
                                $this->id_item 
                               ,$this->id_item_filho 
                               ,$this->menusequencia 
                               ,$this->modulo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Menu () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Menu já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Menu () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update db_menu set ";
     $virgula = "";
     if(trim($this->id_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_item"])){ 
       $sql  .= $virgula." id_item = $this->id_item ";
       $virgula = ",";
       if(trim($this->id_item) == null ){ 
         $this->erro_sql = " Campo Código do ítem nao Informado.";
         $this->erro_campo = "id_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_item_filho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_item_filho"])){ 
       $sql  .= $virgula." id_item_filho = $this->id_item_filho ";
       $virgula = ",";
       if(trim($this->id_item_filho) == null ){ 
         $this->erro_sql = " Campo Item filho nao Informado.";
         $this->erro_campo = "id_item_filho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->menusequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["menusequencia"])){ 
       $sql  .= $virgula." menusequencia = $this->menusequencia ";
       $virgula = ",";
       if(trim($this->menusequencia) == null ){ 
         $this->erro_sql = " Campo Seqüência nao Informado.";
         $this->erro_campo = "menusequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->modulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["modulo"])){ 
       $sql  .= $virgula." modulo = $this->modulo ";
       $virgula = ",";
       if(trim($this->modulo) == null ){ 
         $this->erro_sql = " Campo Módulo nao Informado.";
         $this->erro_campo = "modulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Menu nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Menu nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from db_menu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Menu nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Menu nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:db_menu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="db_menu.oid,*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_menu ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where db_menu.oid = '$oid'";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_menu ";
     $sql2 = "";
     if($dbwhere==""){
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_menus ( $oid = null,$campos="db_menu.oid,*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_menu m";
     $sql .= "      inner join db_itensmenu i on i.id_item = m.id_item_filho";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where db_menu.oid = '$oid'";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  /**
   * Busca todos os itens de menus
   *
   * @param String $sCampos
   * @param String $sOrdem
   * @param String $sWhere
   * @return String
   */
  function sqlItensMenuDBSeller($sCampos = "*", $sOrdem = "", $sWhere = "") {

    $sSql  = " select {$sCampos}                                               \n";
    $sSql .= "   from db_menu m                                                \n";
    $sSql .= "        inner join db_itensmenu i on i.id_item = m.id_item_filho \n";

    if (!empty($sWhere)) {
      $sSql .= "  where {$sWhere} \n";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem}";
    }

    return $sSql;
  }

  /**
   * Busca os menus com as permissões dos usuarios
   *
   * @param String $sCampos
   * @param String $sOrdem
   * @param String $sWhere
   * @param integer $iIdUsuario
   * @param integer $iAnoUsu
   * @param integer $iInstituicao
   * @param integer $iModulo
   * @return string
   */
  function sqlItensMenuUsuario($sCampos = "*", $sOrdem = "", $sWhere = "", $iIdUsuario, $iAnoUsu, $iInstituicao, $iModulo = null) {

    $sSql  = "  select {$sCampos}                                               \n";
    $sSql .= "    from db_menu m                                                \n";
    $sSql .= "         inner join db_permissao p on p.id_item = m.id_item_filho \n";
    $sSql .= "         inner join db_itensmenu i on i.id_item = m.id_item_filho \n";
    $sSql .= "                                  and p.permissaoativa = '1'      \n";
    $sSql .= "                                  and p.anousu = $iAnoUsu         \n";
    $sSql .= "                                  and p.id_instit = $iInstituicao \n";

    if (!empty($iModulo)) {
      $sSql .= "                                  and p.id_modulo = $iModulo      \n";
    }

    $sSql .= "   where p.id_usuario = $iIdUsuario                               \n";

    if (!empty($sWhere)) {
      $sSql .= " and {$sWhere} ";
    }    

    $sSql .= " union                                                                   \n";
    $sSql .= "  select {$sCampos}                                                      \n";
    $sSql .= "    from db_menu m                                                       \n";
    $sSql .= "         inner join db_permherda h on h.id_usuario     = $iIdUsuario     \n";
    $sSql .= "         inner join db_usuarios  u on u.id_usuario     = h.id_perfil     \n";
    $sSql .= "                                  and u.usuarioativo   = '1'             \n";
    $sSql .= "         inner join db_permissao p on p.id_item        = m.id_item_filho \n";
    $sSql .= "         inner join db_itensmenu i on i.id_item        = m.id_item_filho \n";
    $sSql .= "                                  and p.permissaoativa = '1'             \n";
    $sSql .= "                                  and p.anousu         = $iAnoUsu        \n";
    $sSql .= "                                  and p.id_instit      = $iInstituicao   \n";

    if (!empty($iModulo)) {
      $sSql .= "                                  and p.id_modulo      = $iModulo        \n";
    }

    $sSql .= "  where p.id_usuario = h.id_perfil                                       \n";
    
    if (!empty($sWhere)) {
      $sSql .= " and {$sWhere} ";
    }
        
    if (!empty($sOrdem)) {
      $sSql .= "  order by $sOrdem "; 
    }

    return $sSql;
  }
}
?>