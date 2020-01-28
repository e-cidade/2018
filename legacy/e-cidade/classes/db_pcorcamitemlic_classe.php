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

//MODULO: licitação
//CLASSE DA ENTIDADE pcorcamitemlic
class cl_pcorcamitemlic {
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
   var $pc26_orcamitem = 0;
   var $pc26_liclicitem = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 pc26_orcamitem = int4 = Código sequencial do item no orçamento
                 pc26_liclicitem = int8 = Cod. Sequencial
                 ";
   //funcao construtor da classe
   function cl_pcorcamitemlic() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamitemlic");
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
       $this->pc26_orcamitem = ($this->pc26_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc26_orcamitem"]:$this->pc26_orcamitem);
       $this->pc26_liclicitem = ($this->pc26_liclicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc26_liclicitem"]:$this->pc26_liclicitem);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){
      $this->atualizacampos();
     if($this->pc26_orcamitem == null ){
       $this->erro_sql = " Campo Código sequencial do item no orçamento nao Informado.";
       $this->erro_campo = "pc26_orcamitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc26_liclicitem == null ){
       $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
       $this->erro_campo = "pc26_liclicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamitemlic(
                                       pc26_orcamitem
                                      ,pc26_liclicitem
                       )
                values (
                                $this->pc26_orcamitem
                               ,$this->pc26_liclicitem
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pcorcamitemlic () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pcorcamitemlic já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pcorcamitemlic () nao Incluído. Inclusao Abortada.";
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
     $sql = " update pcorcamitemlic set ";
     $virgula = "";
     if(trim($this->pc26_orcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc26_orcamitem"])){
       $sql  .= $virgula." pc26_orcamitem = $this->pc26_orcamitem ";
       $virgula = ",";
       if(trim($this->pc26_orcamitem) == null ){
         $this->erro_sql = " Campo Código sequencial do item no orçamento nao Informado.";
         $this->erro_campo = "pc26_orcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc26_liclicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc26_liclicitem"])){
       $sql  .= $virgula." pc26_liclicitem = $this->pc26_liclicitem ";
       $virgula = ",";
       if(trim($this->pc26_liclicitem) == null ){
         $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
         $this->erro_campo = "pc26_liclicitem";
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
       $this->erro_sql   = "pcorcamitemlic nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pcorcamitemlic nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from pcorcamitemlic
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
       $this->erro_sql   = "pcorcamitemlic nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pcorcamitemlic nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamitemlic";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="pcorcamitemlic.oid,*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitemlic ";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamitemlic.pc26_orcamitem";
     $sql .= "      inner join liclicitem  on  liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitem.l21_codliclicita";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where pcorcamitemlic.oid = '$oid'";
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
     $sql .= " from pcorcamitemlic ";
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

  public function sql_query_fornecedores( $oid = null,$campos="*",$ordem=null,$dbwhere="") {

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
    $sql .= " from pcorcamitemlic ";
    $sql .= "      inner join pcorcamitem     on pc22_orcamitem = pc26_orcamitem   ";
    $sql .= "      inner join pcorcamforne    on pc21_codorc    = pc22_codorc      ";
    $sql .= "      inner join cgm             on z01_numcgm     = pc21_numcgm      ";
    $sql .= "      inner join liclicitem      on l21_codigo     = pc26_liclicitem  ";
    $sql .= "      inner join liclicita       on l20_codigo     = l21_codliclicita ";
    $sql2 = "";
    if($dbwhere==""){
      if( $oid != "" && $oid != null){
        $sql2 = " where pcorcamitemlic.oid = '$oid'";
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

  public function sql_query_fornecedores_licitacon($sCampos, $sWhere) {

    if (empty($sCampos)) {
      $sCampos = " * ";
    }

    $sSql = "select {$sCampos} ";
    $sSql .= " from pcorcamitemlic ";
    $sSql .= "      inner join pcorcamitem                    on pc22_orcamitem = pc26_orcamitem   ";
    $sSql .= "      inner join pcorcamforne                   on pc21_codorc    = pc22_codorc      ";
    $sSql .= "      inner join cgm                            on z01_numcgm     = pc21_numcgm      ";
    $sSql .= "      inner join liclicitem                     on l21_codigo     = pc26_liclicitem  ";
    $sSql .= "      inner join liclicita                      on l20_codigo     = l21_codliclicita ";
    $sSql .= "      left  join liclicitaencerramentolicitacon on l18_liclicita  = l20_codigo       ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }

  public function sql_query_representantes_fornecedores_licitacon($sCampos, $sWhere) {

    if (empty($sCampos)) {
      $sCampos = " * ";
    }

    $sSql = "select {$sCampos} ";
    $sSql .= " from pcorcamitemlic ";
    $sSql .= "      inner join pcorcamitem                    on pc22_orcamitem = pc26_orcamitem   ";
    $sSql .= "      inner join pcorcamforne                   on pc21_codorc    = pc22_codorc      ";
    $sSql .= "       left join pcfornereprlegal               on pc81_cgmforn   = pc21_numcgm      ";
    $sSql .= "      inner join cgm                            on z01_numcgm     = pc81_cgmresp     ";
    $sSql .= "      inner join liclicitem                     on l21_codigo     = pc26_liclicitem  ";
    $sSql .= "      inner join liclicita                      on l20_codigo     = l21_codliclicita ";
    $sSql .= "      left  join liclicitaencerramentolicitacon on l18_liclicita  = l20_codigo       ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }

  public function sql_query_fornecedor_valores($sCampos, $sWhere) {

    if (empty($sCampos)) {
      $sCampos = " * ";
    }

    $sSql = "select {$sCampos} ";
    $sSql .= " from pcorcamitemlic ";
    $sSql .= "      inner join pcorcamitem                    on pc22_orcamitem = pc26_orcamitem   ";
    $sSql .= "      inner join pcorcamforne                   on pc21_codorc    = pc22_codorc      ";
    $sSql .= "      inner join cgm                            on z01_numcgm     = pc21_numcgm      ";
    $sSql .= "      inner join liclicitem                     on l21_codigo     = pc26_liclicitem  ";
    $sSql .= "      inner join liclicita                      on l20_codigo     = l21_codliclicita ";
    $sSql .= "      left  join pcorcamval                     on pcorcamval.pc23_orcamitem = pcorcamitem.pc22_orcamitem";
    $sSql .= "      left  join pcorcamdescla                  on pcorcamdescla.pc32_orcamitem = pcorcamitem.pc22_orcamitem ";
    $sSql .= "                                               and pcorcamdescla.pc32_orcamforne = pcorcamforne.pc21_orcamforne ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }

}