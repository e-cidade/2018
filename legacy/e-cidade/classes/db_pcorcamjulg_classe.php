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

//MODULO: compras
//CLASSE DA ENTIDADE pcorcamjulg
class cl_pcorcamjulg {
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
   var $pc24_orcamitem = 0;
   var $pc24_pontuacao = 0;
   var $pc24_orcamforne = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 pc24_orcamitem = int4 = Código sequencial do item no orçamento
                 pc24_pontuacao = int4 = Pontuação
                 pc24_orcamforne = int8 = Código do orcamento deste fornecedor
                 ";
   //funcao construtor da classe
   function cl_pcorcamjulg() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamjulg");
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
       $this->pc24_orcamitem = ($this->pc24_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc24_orcamitem"]:$this->pc24_orcamitem);
       $this->pc24_pontuacao = ($this->pc24_pontuacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc24_pontuacao"]:$this->pc24_pontuacao);
       $this->pc24_orcamforne = ($this->pc24_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc24_orcamforne"]:$this->pc24_orcamforne);
     }else{
       $this->pc24_orcamitem = ($this->pc24_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc24_orcamitem"]:$this->pc24_orcamitem);
       $this->pc24_orcamforne = ($this->pc24_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc24_orcamforne"]:$this->pc24_orcamforne);
     }
   }
   // funcao para inclusao
   function incluir ($pc24_orcamitem,$pc24_orcamforne){
      $this->atualizacampos();
     if($this->pc24_pontuacao == null ){
       $this->erro_sql = " Campo Pontuação nao Informado.";
       $this->erro_campo = "pc24_pontuacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->pc24_orcamitem = $pc24_orcamitem;
       $this->pc24_orcamforne = $pc24_orcamforne;
     if(($this->pc24_orcamitem == null) || ($this->pc24_orcamitem == "") ){
       $this->erro_sql = " Campo pc24_orcamitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->pc24_orcamforne == null) || ($this->pc24_orcamforne == "") ){
       $this->erro_sql = " Campo pc24_orcamforne nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamjulg(
                                       pc24_orcamitem
                                      ,pc24_pontuacao
                                      ,pc24_orcamforne
                       )
                values (
                                $this->pc24_orcamitem
                               ,$this->pc24_pontuacao
                               ,$this->pc24_orcamforne
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Julgamento dos valores dos itens dos orçamentos ($this->pc24_orcamitem."-".$this->pc24_orcamforne) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Julgamento dos valores dos itens dos orçamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Julgamento dos valores dos itens dos orçamentos ($this->pc24_orcamitem."-".$this->pc24_orcamforne) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc24_orcamitem."-".$this->pc24_orcamforne;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc24_orcamitem,$this->pc24_orcamforne));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5519,'$this->pc24_orcamitem','I')");
       $resac = db_query("insert into db_acountkey values($acount,6496,'$this->pc24_orcamforne','I')");
       $resac = db_query("insert into db_acount values($acount,860,5519,'','".AddSlashes(pg_result($resaco,0,'pc24_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,860,5520,'','".AddSlashes(pg_result($resaco,0,'pc24_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,860,6496,'','".AddSlashes(pg_result($resaco,0,'pc24_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($pc24_orcamitem=null,$pc24_orcamforne=null,$dbwhere=null) {
      $this->atualizacampos();
     $sql = " update pcorcamjulg set ";
     $virgula = "";
     if(trim($this->pc24_orcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc24_orcamitem"])){
       $sql  .= $virgula." pc24_orcamitem = $this->pc24_orcamitem ";
       $virgula = ",";
       if(trim($this->pc24_orcamitem) == null ){
         $this->erro_sql = " Campo Código sequencial do item no orçamento nao Informado.";
         $this->erro_campo = "pc24_orcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc24_pontuacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc24_pontuacao"])){
       $sql  .= $virgula." pc24_pontuacao = $this->pc24_pontuacao ";
       $virgula = ",";
       if(trim($this->pc24_pontuacao) == null ){
         $this->erro_sql = " Campo Pontuação nao Informado.";
         $this->erro_campo = "pc24_pontuacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc24_orcamforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc24_orcamforne"])){
       $sql  .= $virgula." pc24_orcamforne = $this->pc24_orcamforne ";
       $virgula = ",";
       if(trim($this->pc24_orcamforne) == null ){
         $this->erro_sql = " Campo Código do orcamento deste fornecedor nao Informado.";
         $this->erro_campo = "pc24_orcamforne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if ($dbwhere != null) {
          $sql .= $dbwhere;
     } else {
     if($pc24_orcamitem!=null){
       $sql .= " pc24_orcamitem = $this->pc24_orcamitem";
     }
     if($pc24_orcamforne!=null){
       $sql .= " and  pc24_orcamforne = $this->pc24_orcamforne";
     }
     }

     $resaco = $this->sql_record($this->sql_query_file($this->pc24_orcamitem,$this->pc24_orcamforne));

     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5519,'$this->pc24_orcamitem','A')");
         $resac = db_query("insert into db_acountkey values($acount,6496,'$this->pc24_orcamforne','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc24_orcamitem"]) || $this->pc24_orcamitem != "")
           $resac = db_query("insert into db_acount values($acount,860,5519,'".AddSlashes(pg_result($resaco,$conresaco,'pc24_orcamitem'))."','$this->pc24_orcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc24_pontuacao"]) || $this->pc24_pontuacao != "")
           $resac = db_query("insert into db_acount values($acount,860,5520,'".AddSlashes(pg_result($resaco,$conresaco,'pc24_pontuacao'))."','$this->pc24_pontuacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc24_orcamforne"]) || $this->pc24_orcamforne != "")
           $resac = db_query("insert into db_acount values($acount,860,6496,'".AddSlashes(pg_result($resaco,$conresaco,'pc24_orcamforne'))."','$this->pc24_orcamforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Julgamento dos valores dos itens dos orçamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc24_orcamitem."-".$this->pc24_orcamforne;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Julgamento dos valores dos itens dos orçamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc24_orcamitem."-".$this->pc24_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc24_orcamitem."-".$this->pc24_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($pc24_orcamitem=null,$pc24_orcamforne=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc24_orcamitem,$pc24_orcamforne));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5519,'$pc24_orcamitem','E')");
         $resac = db_query("insert into db_acountkey values($acount,6496,'$pc24_orcamforne','E')");
         $resac = db_query("insert into db_acount values($acount,860,5519,'','".AddSlashes(pg_result($resaco,$iresaco,'pc24_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,860,5520,'','".AddSlashes(pg_result($resaco,$iresaco,'pc24_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,860,6496,'','".AddSlashes(pg_result($resaco,$iresaco,'pc24_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcorcamjulg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc24_orcamitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc24_orcamitem = $pc24_orcamitem ";
        }
        if($pc24_orcamforne != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc24_orcamforne = $pc24_orcamforne ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Julgamento dos valores dos itens dos orçamentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc24_orcamitem."-".$pc24_orcamforne;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Julgamento dos valores dos itens dos orçamentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc24_orcamitem."-".$pc24_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc24_orcamitem."-".$pc24_orcamforne;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamjulg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $pc24_orcamitem=null,$pc24_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamjulg ";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = pcorcamjulg.pc24_orcamforne";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamforne.pc21_codorc";
     $sql .= "      inner join pcorcam a on  a.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      left  join pcorcamitemsol on  pcorcamitemsol.pc29_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql2 = "";
     if($dbwhere==""){
       if($pc24_orcamitem!=null ){
         $sql2 .= " where pcorcamjulg.pc24_orcamitem = $pc24_orcamitem ";
       }
       if($pc24_orcamforne!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " pcorcamjulg.pc24_orcamforne = $pc24_orcamforne ";
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
   function sql_query_file ( $pc24_orcamitem=null,$pc24_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamjulg ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc24_orcamitem!=null ){
         $sql2 .= " where pcorcamjulg.pc24_orcamitem = $pc24_orcamitem ";
       }
       if($pc24_orcamforne!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " pcorcamjulg.pc24_orcamforne = $pc24_orcamforne ";
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
   function sql_query_geraut( $pc24_orcamitem=null,$pc24_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamjulg ";
     $sql .= "      inner join pcorcamforne on pcorcamforne.pc21_orcamforne = pcorcamjulg.pc24_orcamforne ";
     $sql .= "      inner join pcorcamitem on pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem ";
     $sql .= "      inner join cgm on cgm.z01_numcgm = pcorcamforne.pc21_numcgm ";
     $sql .= "      inner join pcorcam on pcorcam.pc20_codorc = pcorcamforne.pc21_codorc ";
     $sql .= "      inner join pcorcam a on a.pc20_codorc = pcorcamitem.pc22_codorc ";
     $sql .= "      inner join pcorcamitemproc on pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem ";
     $sql .= "      inner join pcprocitem on pcprocitem.pc81_codprocitem = pcorcamitemproc.pc31_pcprocitem ";
     $sql .= "      inner join pcproc on pcproc.pc80_codproc=pcprocitem.pc81_codproc ";
     $sql .= "      inner join pcdotac on pc13_codigo=pcprocitem.pc81_solicitem ";
     $sql .= "      left join pcdotaccontrapartida on pc13_sequencial=pc19_pcdotac ";
     $sql .= "      inner join pcorcamval on pcorcamval.pc23_orcamforne=pcorcamjulg.pc24_orcamforne
                           and pcorcamval.pc23_orcamitem=pcorcamitem.pc22_orcamitem ";
     $sql .= "      inner join solicitem on solicitem.pc11_codigo= pcprocitem.pc81_solicitem ";
     $sql .= "      inner join solicita on solicita.pc10_numero = solicitem.pc11_numero  ";
     $sql .= "      inner join solicitempcmater on solicitempcmater.pc16_solicitem= solicitem.pc11_codigo ";
     $sql .= "      inner join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
     $sql .= "      inner join solicitemele on solicitemele.pc18_solicitem= solicitem.pc11_codigo ";
     $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc24_orcamitem!=null ){
         $sql2 .= " where pcorcamjulg.pc24_orcamitem = $pc24_orcamitem ";
       }
       if($pc24_orcamforne!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " pcorcamjulg.pc24_orcamforne = $pc24_orcamforne ";
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
   function sql_query_gerautlic( $pc24_orcamitem=null,$pc24_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamjulg ";
     $sql .= "      inner join pcorcamforne on pcorcamforne.pc21_orcamforne = pcorcamjulg.pc24_orcamforne ";
     $sql .= "      inner join pcorcamitem on pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem ";
     $sql .= "      inner join cgm on cgm.z01_numcgm = pcorcamforne.pc21_numcgm ";
     $sql .= "      inner join pcorcam on pcorcam.pc20_codorc = pcorcamforne.pc21_codorc ";
     $sql .= "      inner join pcorcam a on a.pc20_codorc = pcorcamitem.pc22_codorc ";
     $sql .= "      inner join pcorcamitemlic on pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem ";
     //$sql .= "      inner join pcorcamitemproc on pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem ";
     $sql .= "      inner join liclicitem on liclicitem.l21_codigo =  pcorcamitemlic.pc26_liclicitem";
     $sql .= "      inner join pcprocitem on pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem ";
     $sql .= "      inner join pcproc on pcproc.pc80_codproc=pcprocitem.pc81_codproc ";
     $sql .= "      inner join liclicita on liclicita.l20_codigo = liclicitem.l21_codliclicita ";
     $sql .= "      inner join pcdotac on pc13_codigo=pcprocitem.pc81_solicitem ";
     $sql .= "      left join pcdotaccontrapartida  on pc13_sequencial=pc19_pcdotac ";
     $sql .= "      left join pcorcamval on pcorcamval.pc23_orcamforne=pcorcamjulg.pc24_orcamforne
                           and pcorcamval.pc23_orcamitem=pcorcamitem.pc22_orcamitem ";
     $sql .= "      inner join solicitem on solicitem.pc11_codigo= pcprocitem.pc81_solicitem ";
     $sql .= "      inner join solicita on solicita.pc10_numero = solicitem.pc11_numero  ";
     $sql .= "      inner join solicitempcmater on solicitempcmater.pc16_solicitem= solicitem.pc11_codigo ";
     $sql .= "      inner join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
     $sql .= "      inner join solicitemele on solicitemele.pc18_solicitem= solicitem.pc11_codigo ";
     $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql .= "      left  join orcelemento on pc18_codele = o56_codele";
     $sql .= "                         and o56_anousu = ".db_getsession("DB_anousu");
     $sql2 = "";
     if($dbwhere==""){
       if($pc24_orcamitem!=null ){
         $sql2 .= " where pcorcamjulg.pc24_orcamitem = $pc24_orcamitem ";
       }
       if($pc24_orcamforne!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " pcorcamjulg.pc24_orcamforne = $pc24_orcamforne ";
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
   function sql_query_relmotivo ( $pc24_orcamitem=null,$pc24_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamjulg ";
     $sql .= "      inner join pcorcamitem     on pcorcamitem.pc22_orcamitem     = pcorcamjulg.pc24_orcamitem";
     $sql .= "      inner join pcorcam         on pcorcam.pc20_codorc            = pcorcamitem.pc22_codorc";
     $sql .= "      left  join pcorcamitemsol  on pcorcamitemsol.pc29_orcamitem  = pcorcamitem.pc22_orcamitem";
     $sql .= "      left  join pcorcamitemproc on pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql2 = "";
     if($dbwhere==""){
       if($pc24_orcamitem!=null ){
         $sql2 .= " where pcorcamjulg.pc24_orcamitem = $pc24_orcamitem ";
       }
       if($pc24_orcamforne!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " pcorcamjulg.pc24_orcamforne = $pc24_orcamforne ";
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
   function sql_query_verifica_global ($pc20_codorc=null,$campos="*"){
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
     $sql .= " from pcorcam ";
     $sql .= "      inner join pcorcamitem on pc22_codorc=pc20_codorc";
     $sql .= "      inner join pcorcamforne on pc21_codorc= pc20_codorc";
     $sql .= "      left  join pcorcamval   on pc22_orcamitem = pc23_orcamitem and pc23_orcamforne=pc21_orcamforne";
     $sql2 = "";
     if ($pc20_codorc!=null){
       $sql2 = " where pc20_codorc= $pc20_codorc ";
     }
     $sql .= $sql2;
     return $sql;
 }

  function sql_query_adjudicacao( $pc24_orcamitem=null,$pc24_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pcorcamjulg ";
    $sql .= "      inner join pcorcamforne on pcorcamforne.pc21_orcamforne = pcorcamjulg.pc24_orcamforne ";
    $sql .= "      inner join pcorcamitem on pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem ";
    $sql .= "      inner join cgm on cgm.z01_numcgm = pcorcamforne.pc21_numcgm ";
    $sql .= "      inner join pcorcam on pcorcam.pc20_codorc = pcorcamforne.pc21_codorc ";
    $sql .= "      inner join pcorcam a on a.pc20_codorc = pcorcamitem.pc22_codorc ";
    $sql .= "      inner join pcorcamitemlic on pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem ";
    $sql .= "      inner join liclicitem on liclicitem.l21_codigo =  pcorcamitemlic.pc26_liclicitem";
    $sql .= "      inner join pcprocitem on pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem ";
    $sql .= "      inner join pcproc on pcproc.pc80_codproc=pcprocitem.pc81_codproc ";
    $sql .= "      inner join liclicita on liclicita.l20_codigo = liclicitem.l21_codliclicita ";
    $sql .= "      inner join pcorcamval on pcorcamval.pc23_orcamforne=pcorcamjulg.pc24_orcamforne
                           and pcorcamval.pc23_orcamitem=pcorcamitem.pc22_orcamitem ";
    $sql .= "      inner join solicitem on solicitem.pc11_codigo= pcprocitem.pc81_solicitem ";
    $sql .= "      inner join solicita on solicita.pc10_numero = solicitem.pc11_numero  ";
    $sql .= "      inner join solicitempcmater on solicitempcmater.pc16_solicitem= solicitem.pc11_codigo ";
    $sql .= "      inner join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
    $sql2 = "";
    if($dbwhere==""){
      if($pc24_orcamitem!=null ){
        $sql2 .= " where pcorcamjulg.pc24_orcamitem = $pc24_orcamitem ";
      }
      if($pc24_orcamforne!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " pcorcamjulg.pc24_orcamforne = $pc24_orcamforne ";
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

  function sql_query_participantes_licitacao( $pc24_orcamitem=null,$pc24_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from  pcorcamforne";
    $sql .= "      inner join pcorcamval on pcorcamval.pc23_orcamforne=pcorcamforne.pc21_orcamforne";
    $sql .= "      inner join pcorcamitem on pcorcamitem.pc22_orcamitem = pcorcamval.pc23_orcamitem ";
    $sql .= "      inner join cgm on cgm.z01_numcgm = pcorcamforne.pc21_numcgm ";
    $sql .= "      inner join pcorcam on pcorcam.pc20_codorc = pcorcamforne.pc21_codorc ";
    $sql .= "      inner join pcorcam a on a.pc20_codorc = pcorcamitem.pc22_codorc ";
    $sql .= "      inner join pcorcamitemlic on pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem ";
    $sql .= "      inner join liclicitem on liclicitem.l21_codigo =  pcorcamitemlic.pc26_liclicitem";
    $sql .= "      inner join pcprocitem on pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem ";
    $sql .= "      inner join pcproc on pcproc.pc80_codproc=pcprocitem.pc81_codproc ";
    $sql .= "      inner join liclicita on liclicita.l20_codigo = liclicitem.l21_codliclicita ";
    $sql .= "      inner join pcdotac on pc13_codigo=pcprocitem.pc81_solicitem ";
    $sql .= "      left join pcdotaccontrapartida  on pc13_sequencial=pc19_pcdotac ";
    $sql .= "      inner join solicitem on solicitem.pc11_codigo= pcprocitem.pc81_solicitem ";
    $sql .= "      inner join solicita on solicita.pc10_numero = solicitem.pc11_numero  ";
    $sql .= "      inner join solicitempcmater on solicitempcmater.pc16_solicitem= solicitem.pc11_codigo ";
    $sql .= "      inner join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
    $sql .= "      inner join solicitemele on solicitemele.pc18_solicitem= solicitem.pc11_codigo ";
    $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
    $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
    $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
    $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
    $sql .= "      left  join orcelemento on pc18_codele = o56_codele";
    $sql .= "                         and o56_anousu = ".db_getsession("DB_anousu");
    $sql2 = "";
    if($dbwhere==""){
      if($pc24_orcamitem!=null ){
        $sql2 .= " where pcorcamjulg.pc24_orcamitem = $pc24_orcamitem ";
      }
      if($pc24_orcamforne!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " pcorcamjulg.pc24_orcamforne = $pc24_orcamforne ";
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

  public function sql_query_orcamento_licitacao($sCampos, $sWhere) {

    if (empty($sCampos)) {
      $sCampos = " * ";
    }

    $sSql  = " select                                                                                                 ";
    $sSql .= "    {$sCampos}                                                                                          ";
    $sSql .= " from liclicitem                                                                                        ";
    $sSql .= "  inner join pcorcamitemlic on liclicitem.l21_codigo      = pcorcamitemlic.pc26_liclicitem              ";
    $sSql .= "  inner join pcorcamitem    on pcorcamitem.pc22_orcamitem = pcorcamitemlic.pc26_orcamitem               ";
    $sSql .= "  inner join pcorcamval     on pcorcamitem.pc22_orcamitem = pcorcamval.pc23_orcamitem                   ";
    $sSql .= "  inner join pcorcamforne   on pcorcamval.pc23_orcamforne = pcorcamforne.pc21_orcamforne                ";
    $sSql .= "  inner join pcorcamjulg    on pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem                  ";
    $sSql .= "                              and pcorcamforne.pc21_orcamforne = pcorcamjulg.pc24_orcamforne            ";
    $sSql .= "  inner join cgm            on pcorcamforne.pc21_numcgm    = cgm.z01_numcgm                             ";
    $sSql .= "  left join liclicitemlote on l04_liclicitem = l21_codigo ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    return $sSql;
  }

  public function sql_query_orcamento_licitacao_contrato($sCampos, $sWhere) {

    if (empty($sCampos)) {
      $sCampos = " * ";
    }

    $sSql  = " select ";
    $sSql .= "    {$sCampos} ";
    $sSql .= " from liclicitem ";
    $sSql .= "  inner join pcorcamitemlic  on liclicitem.l21_codigo            = pcorcamitemlic.pc26_liclicitem ";
    $sSql .= "  inner join pcorcamitem     on pcorcamitem.pc22_orcamitem       = pcorcamitemlic.pc26_orcamitem ";
    $sSql .= "  inner join pcorcamval      on pcorcamitem.pc22_orcamitem       = pcorcamval.pc23_orcamitem ";
    $sSql .= "  inner join pcorcamforne    on pcorcamval.pc23_orcamforne       = pcorcamforne.pc21_orcamforne                ";
    $sSql .= "  inner join pcorcamjulg     on pcorcamitem.pc22_orcamitem       = pcorcamjulg.pc24_orcamitem ";
    $sSql .= "                                and pcorcamforne.pc21_orcamforne = pcorcamjulg.pc24_orcamforne ";
    $sSql .= "  inner join cgm             on pcorcamforne.pc21_numcgm         = cgm.z01_numcgm ";
    $sSql .= "  left join liclicitemlote   on l04_liclicitem                   = l21_codigo ";
    $sSql .= "  left join acordoliclicitem on ac24_liclicitem                  = l21_codigo ";
    $sSql .= "  left join acordoitem       on ac24_acordoitem                  = ac20_sequencial ";
    $sSql .= "  left join acordoposicao    on ac20_acordoposicao               = ac26_sequencial ";
    $sSql .= "  left join acordo           on ac26_acordo                      = ac16_sequencial ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    return $sSql;
  }

}
