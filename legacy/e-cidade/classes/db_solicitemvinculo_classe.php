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

//MODULO: Compras
//CLASSE DA ENTIDADE solicitemvinculo
class cl_solicitemvinculo {
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
   var $pc55_sequencial = 0;
   var $pc55_solicitempai = 0;
   var $pc55_solicitemfilho = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 pc55_sequencial = int4 = Código 
                 pc55_solicitempai = int4 = Código ítem Pai 
                 pc55_solicitemfilho = int4 = Codigo Ítem Filho 
                 ";
   //funcao construtor da classe
   function cl_solicitemvinculo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solicitemvinculo");
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
       $this->pc55_sequencial = ($this->pc55_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc55_sequencial"]:$this->pc55_sequencial);
       $this->pc55_solicitempai = ($this->pc55_solicitempai == ""?@$GLOBALS["HTTP_POST_VARS"]["pc55_solicitempai"]:$this->pc55_solicitempai);
       $this->pc55_solicitemfilho = ($this->pc55_solicitemfilho == ""?@$GLOBALS["HTTP_POST_VARS"]["pc55_solicitemfilho"]:$this->pc55_solicitemfilho);
     }else{
       $this->pc55_sequencial = ($this->pc55_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc55_sequencial"]:$this->pc55_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc55_sequencial){
      $this->atualizacampos();
     if($this->pc55_solicitempai == null ){
       $this->erro_sql = " Campo Código ítem Pai nao Informado.";
       $this->erro_campo = "pc55_solicitempai";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc55_solicitemfilho == null ){
       $this->erro_sql = " Campo Codigo Ítem Filho nao Informado.";
       $this->erro_campo = "pc55_solicitemfilho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc55_sequencial == "" || $pc55_sequencial == null ){
       $result = db_query("select nextval('solicitemvinculo_pc55_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solicitemvinculo_pc55_sequencial_seq do campo: pc55_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->pc55_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from solicitemvinculo_pc55_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc55_sequencial)){
         $this->erro_sql = " Campo pc55_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc55_sequencial = $pc55_sequencial;
       }
     }
     if(($this->pc55_sequencial == null) || ($this->pc55_sequencial == "") ){
       $this->erro_sql = " Campo pc55_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solicitemvinculo(
                                       pc55_sequencial 
                                      ,pc55_solicitempai 
                                      ,pc55_solicitemfilho 
                       )
                values (
                                $this->pc55_sequencial 
                               ,$this->pc55_solicitempai 
                               ,$this->pc55_solicitemfilho 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Solicitação Ítem Vinculo ($this->pc55_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Solicitação Ítem Vinculo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Solicitação Ítem Vinculo ($this->pc55_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc55_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc55_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15217,'$this->pc55_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2681,15217,'','".AddSlashes(pg_result($resaco,0,'pc55_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2681,15218,'','".AddSlashes(pg_result($resaco,0,'pc55_solicitempai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2681,15219,'','".AddSlashes(pg_result($resaco,0,'pc55_solicitemfilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($pc55_sequencial=null) {
      $this->atualizacampos();
     $sql = " update solicitemvinculo set ";
     $virgula = "";
     if(trim($this->pc55_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc55_sequencial"])){
       $sql  .= $virgula." pc55_sequencial = $this->pc55_sequencial ";
       $virgula = ",";
       if(trim($this->pc55_sequencial) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "pc55_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc55_solicitempai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc55_solicitempai"])){
       $sql  .= $virgula." pc55_solicitempai = $this->pc55_solicitempai ";
       $virgula = ",";
       if(trim($this->pc55_solicitempai) == null ){
         $this->erro_sql = " Campo Código ítem Pai nao Informado.";
         $this->erro_campo = "pc55_solicitempai";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc55_solicitemfilho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc55_solicitemfilho"])){
       $sql  .= $virgula." pc55_solicitemfilho = $this->pc55_solicitemfilho ";
       $virgula = ",";
       if(trim($this->pc55_solicitemfilho) == null ){
         $this->erro_sql = " Campo Codigo Ítem Filho nao Informado.";
         $this->erro_campo = "pc55_solicitemfilho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc55_sequencial!=null){
       $sql .= " pc55_sequencial = $this->pc55_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc55_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15217,'$this->pc55_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc55_sequencial"]) || $this->pc55_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2681,15217,'".AddSlashes(pg_result($resaco,$conresaco,'pc55_sequencial'))."','$this->pc55_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc55_solicitempai"]) || $this->pc55_solicitempai != "")
           $resac = db_query("insert into db_acount values($acount,2681,15218,'".AddSlashes(pg_result($resaco,$conresaco,'pc55_solicitempai'))."','$this->pc55_solicitempai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc55_solicitemfilho"]) || $this->pc55_solicitemfilho != "")
           $resac = db_query("insert into db_acount values($acount,2681,15219,'".AddSlashes(pg_result($resaco,$conresaco,'pc55_solicitemfilho'))."','$this->pc55_solicitemfilho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitação Ítem Vinculo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc55_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitação Ítem Vinculo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($pc55_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc55_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15217,'$pc55_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2681,15217,'','".AddSlashes(pg_result($resaco,$iresaco,'pc55_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2681,15218,'','".AddSlashes(pg_result($resaco,$iresaco,'pc55_solicitempai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2681,15219,'','".AddSlashes(pg_result($resaco,$iresaco,'pc55_solicitemfilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solicitemvinculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc55_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc55_sequencial = $pc55_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitação Ítem Vinculo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc55_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitação Ítem Vinculo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc55_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:solicitemvinculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $pc55_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitemvinculo ";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = solicitemvinculo.pc55_solicitempai";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql2 = "";
     if($dbwhere==""){
       if($pc55_sequencial!=null ){
         $sql2 .= " where solicitemvinculo.pc55_sequencial = $pc55_sequencial ";
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
   // funcao do sql
   function sql_query_file ( $pc55_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitemvinculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc55_sequencial!=null ){
         $sql2 .= " where solicitemvinculo.pc55_sequencial = $pc55_sequencial ";
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

  public function sql_query_item_licitacon($sCampos = "*", $sWhere = null, $sGroup = null) {

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from pcproc ";
    $sSql .= "        inner join pcprocitem        on pcprocitem.pc81_codproc = pcproc.pc80_codproc";
    $sSql .= "        inner join acordopcprocitem  on acordopcprocitem.ac23_pcprocitem = pcprocitem.pc81_codprocitem";
    $sSql .= "        inner join acordoitem        on acordoitem.ac20_sequencial = acordopcprocitem.ac23_acordoitem";
    $sSql .= "        inner join acordoposicao     on acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
    $sSql .= "        inner join acordo            on acordo.ac16_sequencial = acordoposicao.ac26_acordo";
    $sSql .= "        inner join solicitem         on solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
    $sSql .= "        inner join solicitemvinculo  on solicitemvinculo.pc55_solicitemfilho = pcprocitem.pc81_solicitem";
    $sSql .= "        inner join solicitem itempai on itempai.pc11_codigo = solicitemvinculo.pc55_solicitempai";
    $sSql .= "        inner join pcprocitem processopai on processopai.pc81_solicitem = itempai.pc11_codigo";
    $sSql .= "        inner join liclicitem     on liclicitem.l21_codpcprocitem = processopai.pc81_codprocitem";
    $sSql .= "        inner join liclicita      on liclicita.l20_codigo = liclicitem.l21_codliclicita";
    $sSql .= "        inner join cflicita             on cflicita.l03_codigo = liclicita.l20_codtipocom ";
    $sSql .= "        inner join pctipocompratribunal on pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal ";
    $sSql .= "        inner join liclicitemlote on liclicitemlote.l04_liclicitem  = liclicitem.l21_codigo";
    $sSql .= "        inner join pcorcamitemlic on pcorcamitemlic.pc26_liclicitem = liclicitem.l21_codigo";
    $sSql .= "        inner join pcorcamitem    on pcorcamitem.pc22_orcamitem     = pcorcamitemlic.pc26_orcamitem";
    $sSql .= "        inner join pcorcamval     on pcorcamval.pc23_orcamitem      = pcorcamitem.pc22_orcamitem";
    $sSql .= "        left  join pcorcamjulg    on pcorcamjulg.pc24_orcamitem     = pcorcamval.pc23_orcamitem";
    $sSql .= "                                 and pcorcamjulg.pc24_orcamforne    = pcorcamval.pc23_orcamforne";
    $sSql .= "        left  join acordoencerramentolicitacon on acordoencerramentolicitacon.ac58_acordo = acordo.ac16_sequencial";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sGroup)) {
      $sSql .= " group by {$sGroup} ";
    }

    return $sSql;
  }
}
?>