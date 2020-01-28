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
//CLASSE DA ENTIDADE pcorcamitem
class cl_pcorcamitem {
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
   var $pc22_orcamitem = 0;
   var $pc22_codorc = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 pc22_orcamitem = int4 = Código sequencial do item no orçamento
                 pc22_codorc = int4 = Código do orçamento
                 ";
   //funcao construtor da classe
   function cl_pcorcamitem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamitem");
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
       $this->pc22_orcamitem = ($this->pc22_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc22_orcamitem"]:$this->pc22_orcamitem);
       $this->pc22_codorc = ($this->pc22_codorc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc22_codorc"]:$this->pc22_codorc);
     }else{
       $this->pc22_orcamitem = ($this->pc22_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc22_orcamitem"]:$this->pc22_orcamitem);
     }
   }
   // funcao para inclusao
   function incluir ($pc22_orcamitem){
      $this->atualizacampos();
     if($this->pc22_codorc == null ){
       $this->erro_sql = " Campo Código do orçamento nao Informado.";
       $this->erro_campo = "pc22_codorc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc22_orcamitem == "" || $pc22_orcamitem == null ){
       $result = db_query("select nextval('pcorcamitem_pc22_orcamitem_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcorcamitem_pc22_orcamitem_seq do campo: pc22_orcamitem";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->pc22_orcamitem = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from pcorcamitem_pc22_orcamitem_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc22_orcamitem)){
         $this->erro_sql = " Campo pc22_orcamitem maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc22_orcamitem = $pc22_orcamitem;
       }
     }
     if(($this->pc22_orcamitem == null) || ($this->pc22_orcamitem == "") ){
       $this->erro_sql = " Campo pc22_orcamitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamitem(
                                       pc22_orcamitem
                                      ,pc22_codorc
                       )
                values (
                                $this->pc22_orcamitem
                               ,$this->pc22_codorc
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens do orçamento ($this->pc22_orcamitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens do orçamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens do orçamento ($this->pc22_orcamitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc22_orcamitem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc22_orcamitem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5514,'$this->pc22_orcamitem','I')");
       $resac = db_query("insert into db_acount values($acount,859,5514,'','".AddSlashes(pg_result($resaco,0,'pc22_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,859,5515,'','".AddSlashes(pg_result($resaco,0,'pc22_codorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($pc22_orcamitem=null) {
      $this->atualizacampos();
     $sql = " update pcorcamitem set ";
     $virgula = "";
     if(trim($this->pc22_orcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc22_orcamitem"])){
       $sql  .= $virgula." pc22_orcamitem = $this->pc22_orcamitem ";
       $virgula = ",";
       if(trim($this->pc22_orcamitem) == null ){
         $this->erro_sql = " Campo Código sequencial do item no orçamento nao Informado.";
         $this->erro_campo = "pc22_orcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc22_codorc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc22_codorc"])){
       $sql  .= $virgula." pc22_codorc = $this->pc22_codorc ";
       $virgula = ",";
       if(trim($this->pc22_codorc) == null ){
         $this->erro_sql = " Campo Código do orçamento nao Informado.";
         $this->erro_campo = "pc22_codorc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc22_orcamitem!=null){
       $sql .= " pc22_orcamitem = $this->pc22_orcamitem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc22_orcamitem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5514,'$this->pc22_orcamitem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc22_orcamitem"]))
           $resac = db_query("insert into db_acount values($acount,859,5514,'".AddSlashes(pg_result($resaco,$conresaco,'pc22_orcamitem'))."','$this->pc22_orcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc22_codorc"]))
           $resac = db_query("insert into db_acount values($acount,859,5515,'".AddSlashes(pg_result($resaco,$conresaco,'pc22_codorc'))."','$this->pc22_codorc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens do orçamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc22_orcamitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens do orçamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc22_orcamitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc22_orcamitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($pc22_orcamitem=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc22_orcamitem));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5514,'$pc22_orcamitem','E')");
         $resac = db_query("insert into db_acount values($acount,859,5514,'','".AddSlashes(pg_result($resaco,$iresaco,'pc22_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,859,5515,'','".AddSlashes(pg_result($resaco,$iresaco,'pc22_codorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcorcamitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc22_orcamitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc22_orcamitem = $pc22_orcamitem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens do orçamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc22_orcamitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens do orçamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc22_orcamitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc22_orcamitem;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc22_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitem ";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql2 = "";
     if($dbwhere==""){
       if($pc22_orcamitem!=null ){
         $sql2 .= " where pcorcamitem.pc22_orcamitem = $pc22_orcamitem ";
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
   function sql_query_file ( $pc22_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc22_orcamitem!=null ){
         $sql2 .= " where pcorcamitem.pc22_orcamitem = $pc22_orcamitem ";
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
   function sql_query_itens ($pc22_codorc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc22_codorc!=null ){
         $sql2 .= " where pcorcamitem.pc22_codorc = $pc22_codorc ";
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
   function sql_query_pcmaterlic ( $pc22_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitem ";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      left  join pcorcamforne  on  pcorcamforne.pc21_codorc = pcorcam.pc20_codorc";
     $sql .= "      inner join pcorcamitemlic  on  pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql .= "      inner join liclicitem  on  pcorcamitemlic.pc26_liclicitem = liclicitem.l21_codigo";
     $sql .= "      inner join liclicita   on liclicita.l20_codigo = liclicitem.l21_codliclicita";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita  on solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "       left join solicitaregistropreco on solicitaregistropreco.pc54_solicita = solicita.pc10_numero";
     $sql .= "      left join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      left  join pcorcamval  on  pcorcamval.pc23_orcamitem = pcorcamitem.pc22_orcamitem
                                          and  pcorcamval.pc23_orcamforne = pcorcamforne.pc21_orcamforne ";
     $sql .= "      left  join pcorcamdescla  on  pcorcamdescla.pc32_orcamitem = pcorcamitem.pc22_orcamitem
                                          and  pcorcamdescla.pc32_orcamforne = pcorcamforne.pc21_orcamforne ";
     $sql .= "      left  join liclicitemlote on liclicitemlote.l04_liclicitem = liclicitem.l21_codigo ";
     $sql .= "      left  join licsituacao on  liclicita.l20_licsituacao = licsituacao.l08_sequencial ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc22_orcamitem!=null ){
         $sql2 .= " where pcorcamitem.pc22_orcamitem = $pc22_orcamitem ";
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
   function sql_query_pcmaterproc ( $pc22_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitem ";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      inner join pcorcamitemproc  on  pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = pcorcamitemproc.pc31_pcprocitem";
     $sql .= "      inner join pcproc      on  pcprocitem.pc81_codproc     = pcproc.pc80_codproc";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      left  join processocompraloteitem  on  pc69_pcprocitem = pcprocitem.pc81_codprocitem";
     $sql .= "      left  join processocompralote      on  pc68_sequencial = pc69_processocompralote";
     $sql .= "      left join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql2 = "";
     if($dbwhere==""){
       if($pc22_orcamitem!=null ){
         $sql2 .= " where pcorcamitem.pc22_orcamitem = $pc22_orcamitem ";
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
   function sql_query_pcmatersol ( $pc22_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitem ";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      inner join pcorcamitemsol  on  pcorcamitemsol.pc29_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcorcamitemsol.pc29_solicitem";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      left join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql2 = "";
     if($dbwhere==""){
       if($pc22_orcamitem!=null ){
         $sql2 .= " where pcorcamitem.pc22_orcamitem = $pc22_orcamitem ";
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
  function sql_query_pcmaterregistro ( $pc22_orcamitem=null,$campos="*",$ordem=null,$dbwhere="") {

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
     $sql .= " from pcorcamitem ";
     $sql .= "      inner join pcorcamitemlic  on  pc26_orcamitem = pc22_orcamitem";
     $sql .= "      inner join liclicitem      on  l21_codigo     = pc26_liclicitem";
     $sql .= "      inner join pcprocitem      on  l21_codpcprocitem     = pc81_codprocitem";
     $sql .= "      inner join solicitem       on  solicitem.pc11_codigo = pc81_solicitem";
     $sql .= "      inner  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      inner  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      left  join solicitemregistropreco  on   solicitem.pc11_codigo = pc57_solicitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc22_orcamitem!=null ){
         $sql2 .= " where pcorcamitem.pc22_orcamitem = $pc22_orcamitem ";
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
   * query para verificar saldo da modalidade
   */
  function sql_query_saldoModalidade( $pc22_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pcorcamitem ";

    $sql .= "     inner join pcorcamitemlic   on pcorcamitemlic.pc26_orcamitem   = pcorcamitem.pc22_orcamitem        ";
    $sql .= "     inner join liclicitem       on liclicitem.l21_codigo           = pcorcamitemlic.pc26_liclicitem    ";
    $sql .= "     inner join pcprocitem       on pcprocitem.pc81_codprocitem     = liclicitem.l21_codpcprocitem      ";
    $sql .= "     inner join liclicita        on liclicita.l20_codigo            = liclicitem.l21_codliclicita       ";
    $sql .= "     inner join cflicita         on liclicita.l20_codtipocom        = cflicita.l03_codigo               ";
    $sql .= "     inner join cflicitavalores  on cflicita.l03_codigo             = cflicitavalores.l40_codfclicita   ";
    $sql .= "     inner join solicitem        on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem         ";
    $sql .= "     inner join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo             ";
    $sql .= "     inner join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater    ";

    $sql2 = "";
    if($dbwhere==""){
      if($pc22_orcamitem!=null ){
        $sql2 .= " where pcorcamitem.pc22_orcamitem = $pc22_orcamitem ";
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

}