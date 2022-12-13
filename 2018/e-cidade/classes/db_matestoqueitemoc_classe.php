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

//MODULO: material
//CLASSE DA ENTIDADE matestoqueitemoc
class cl_matestoqueitemoc {
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
   var $m73_codmatestoqueitem = 0;
   var $m73_codmatordemitem = 0;
   var $m73_cancelado = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 m73_codmatestoqueitem = int8 = Código sequencial do lançamento
                 m73_codmatordemitem = int8 = Código sequencial do lançamento
                 m73_cancelado = bool = Movimento Cancelado
                 ";
   //funcao construtor da classe
   function cl_matestoqueitemoc() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueitemoc");
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
       $this->m73_codmatestoqueitem = ($this->m73_codmatestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m73_codmatestoqueitem"]:$this->m73_codmatestoqueitem);
       $this->m73_codmatordemitem = ($this->m73_codmatordemitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m73_codmatordemitem"]:$this->m73_codmatordemitem);
       $this->m73_cancelado = ($this->m73_cancelado == "f"?@$GLOBALS["HTTP_POST_VARS"]["m73_cancelado"]:$this->m73_cancelado);
     }else{
       $this->m73_codmatestoqueitem = ($this->m73_codmatestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m73_codmatestoqueitem"]:$this->m73_codmatestoqueitem);
       $this->m73_codmatordemitem = ($this->m73_codmatordemitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m73_codmatordemitem"]:$this->m73_codmatordemitem);
     }
   }
   // funcao para inclusao
   function incluir ($m73_codmatestoqueitem,$m73_codmatordemitem){
      $this->atualizacampos();
     if($this->m73_cancelado == null ){
       $this->m73_cancelado = "false";
     }
       $this->m73_codmatestoqueitem = $m73_codmatestoqueitem;
       $this->m73_codmatordemitem = $m73_codmatordemitem;
     if(($this->m73_codmatestoqueitem == null) || ($this->m73_codmatestoqueitem == "") ){
       $this->erro_sql = " Campo m73_codmatestoqueitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->m73_codmatordemitem == null) || ($this->m73_codmatordemitem == "") ){
       $this->erro_sql = " Campo m73_codmatordemitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueitemoc(
                                       m73_codmatestoqueitem
                                      ,m73_codmatordemitem
                                      ,m73_cancelado
                       )
                values (
                                $this->m73_codmatestoqueitem
                               ,$this->m73_codmatordemitem
                               ,'$this->m73_cancelado'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ordens de compra por item do estoque ($this->m73_codmatestoqueitem."-".$this->m73_codmatordemitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ordens de compra por item do estoque já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ordens de compra por item do estoque ($this->m73_codmatestoqueitem."-".$this->m73_codmatordemitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m73_codmatestoqueitem."-".$this->m73_codmatordemitem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m73_codmatestoqueitem,$this->m73_codmatordemitem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6282,'$this->m73_codmatestoqueitem','I')");
       $resac = db_query("insert into db_acountkey values($acount,6283,'$this->m73_codmatordemitem','I')");
       $resac = db_query("insert into db_acount values($acount,1022,6282,'','".AddSlashes(pg_result($resaco,0,'m73_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1022,6283,'','".AddSlashes(pg_result($resaco,0,'m73_codmatordemitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1022,14498,'','".AddSlashes(pg_result($resaco,0,'m73_cancelado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($m73_codmatestoqueitem = null, $m73_codmatordemitem = null) {
      $this->atualizacampos();
     $sql = " update matestoqueitemoc set ";
     $virgula = "";
     if(trim($this->m73_codmatestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m73_codmatestoqueitem"])){
       $sql  .= $virgula." m73_codmatestoqueitem = $this->m73_codmatestoqueitem ";
       $virgula = ",";
       if(trim($this->m73_codmatestoqueitem) == null ){
         $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
         $this->erro_campo = "m73_codmatestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m73_codmatordemitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m73_codmatordemitem"])){
       $sql  .= $virgula." m73_codmatordemitem = $this->m73_codmatordemitem ";
       $virgula = ",";
       if(trim($this->m73_codmatordemitem) == null ){
         $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
         $this->erro_campo = "m73_codmatordemitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m73_cancelado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m73_cancelado"])){
       $sql  .= $virgula." m73_cancelado = '$this->m73_cancelado' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m73_codmatestoqueitem!=null){
       $sql .= " m73_codmatestoqueitem = $this->m73_codmatestoqueitem";
     }
     if($m73_codmatordemitem!=null){

       $this->m73_codmatordemitem = $m73_codmatordemitem;
       $sAnd = " ";
       if (!empty($m73_codmatestoqueitem)) {
         $sAnd = " and ";
       }
       $sql .= " {$sAnd}  m73_codmatordemitem = {$m73_codmatordemitem}";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m73_codmatestoqueitem,$this->m73_codmatordemitem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6282,'$this->m73_codmatestoqueitem','A')");
         $resac = db_query("insert into db_acountkey values($acount,6283,'$this->m73_codmatordemitem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m73_codmatestoqueitem"]) || $this->m73_codmatestoqueitem != "")
           $resac = db_query("insert into db_acount values($acount,1022,6282,'".AddSlashes(pg_result($resaco,$conresaco,'m73_codmatestoqueitem'))."','$this->m73_codmatestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m73_codmatordemitem"]) || $this->m73_codmatordemitem != "")
           $resac = db_query("insert into db_acount values($acount,1022,6283,'".AddSlashes(pg_result($resaco,$conresaco,'m73_codmatordemitem'))."','$this->m73_codmatordemitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m73_cancelado"]) || $this->m73_cancelado != "")
           $resac = db_query("insert into db_acount values($acount,1022,14498,'".AddSlashes(pg_result($resaco,$conresaco,'m73_cancelado'))."','$this->m73_cancelado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordens de compra por item do estoque nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m73_codmatestoqueitem."-".$this->m73_codmatordemitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordens de compra por item do estoque nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m73_codmatestoqueitem."-".$this->m73_codmatordemitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m73_codmatestoqueitem."-".$this->m73_codmatordemitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($m73_codmatestoqueitem=null,$m73_codmatordemitem=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m73_codmatestoqueitem,$m73_codmatordemitem));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6282,'$m73_codmatestoqueitem','E')");
         $resac = db_query("insert into db_acountkey values($acount,6283,'$m73_codmatordemitem','E')");
         $resac = db_query("insert into db_acount values($acount,1022,6282,'','".AddSlashes(pg_result($resaco,$iresaco,'m73_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1022,6283,'','".AddSlashes(pg_result($resaco,$iresaco,'m73_codmatordemitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1022,14498,'','".AddSlashes(pg_result($resaco,$iresaco,'m73_cancelado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueitemoc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m73_codmatestoqueitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m73_codmatestoqueitem = $m73_codmatestoqueitem ";
        }
        if($m73_codmatordemitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m73_codmatordemitem = $m73_codmatordemitem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordens de compra por item do estoque nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m73_codmatestoqueitem."-".$m73_codmatordemitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordens de compra por item do estoque nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m73_codmatestoqueitem."-".$m73_codmatordemitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m73_codmatestoqueitem."-".$m73_codmatordemitem;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueitemoc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $m73_codmatestoqueitem=null,$m73_codmatordemitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoqueitemoc ";
     $sql .= "      inner join matordemitem  on  matordemitem.m52_codlanc = matestoqueitemoc.m73_codmatordemitem";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemoc.m73_codmatestoqueitem";
     $sql .= "      inner join matordem  on  matordem.m51_codordem = matordemitem.m52_codordem";
     $sql .= "      inner join matestoque  as a on   a.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql2 = "";
     if($dbwhere==""){
       if($m73_codmatestoqueitem!=null ){
         $sql2 .= " where matestoqueitemoc.m73_codmatestoqueitem = $m73_codmatestoqueitem ";
       }
       if($m73_codmatordemitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " matestoqueitemoc.m73_codmatordemitem = $m73_codmatordemitem ";
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
   function sql_query_file ( $m73_codmatestoqueitem=null,$m73_codmatordemitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoqueitemoc ";
     $sql2 = "";
     if($dbwhere==""){
       if($m73_codmatestoqueitem!=null ){
         $sql2 .= " where matestoqueitemoc.m73_codmatestoqueitem = $m73_codmatestoqueitem ";
       }
       if($m73_codmatordemitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " matestoqueitemoc.m73_codmatordemitem = $m73_codmatordemitem ";
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
   function sql_query_OC_Nota ( $m73_codmatestoqueitem=null,$m73_codmatordemitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoqueitemoc ";
     $sql .= "      inner join matordemitem  on  matordemitem.m52_codlanc = matestoqueitemoc.m73_codmatordemitem";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemoc.m73_codmatestoqueitem";
     $sql .= "      inner join empempitem  on  empempitem.e62_numemp = matordemitem.m52_numemp and  empempitem.e62_sequen = matordemitem.m52_sequen";
     $sql .= "      inner join matordem  on  matordem.m51_codordem = matordemitem.m52_codordem";
     $sql .= "      inner join matestoque  as a on   a.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join matestoqueitemnota   on   m73_codmatestoqueitem = m74_codmatestoqueitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($m73_codmatestoqueitem!=null ){
         $sql2 .= " where matestoqueitemoc.m73_codmatestoqueitem = $m73_codmatestoqueitem ";
       }
       if($m73_codmatordemitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " matestoqueitemoc.m73_codmatordemitem = $m73_codmatordemitem ";
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

  public function sql_query_nota_ordem ($sCampos = "*", $sWhere = null){

    $sql  = " select {$sCampos}";
    $sql .= "  from matestoqueitemoc ";
    $sql .= "       inner join matordemitem  on  matordemitem.m52_codlanc = matestoqueitemoc.m73_codmatordemitem";
    $sql .= "       inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemoc.m73_codmatestoqueitem";
    $sql .= "       inner join empempitem  on  empempitem.e62_numemp = matordemitem.m52_numemp and  empempitem.e62_sequen = matordemitem.m52_sequen";
    $sql .= "       inner join matordem  on  matordem.m51_codordem = matordemitem.m52_codordem";
    $sql .= "       inner join matestoque  as a on   a.m70_codigo = matestoqueitem.m71_codmatestoque";
    $sql .= "       inner join matestoqueitemnota   on   m73_codmatestoqueitem = m74_codmatestoqueitem ";

    $sWhere = trim($sWhere);
    if (!empty($sWhere)) {
      $sql .= " where {$sWhere} ";
    }
    return $sql;
  }
}
?>