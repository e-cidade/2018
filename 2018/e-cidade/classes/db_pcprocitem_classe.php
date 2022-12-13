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
//CLASSE DA ENTIDADE pcprocitem
class cl_pcprocitem {
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
   var $pc81_codprocitem = 0;
   var $pc81_codproc = 0;
   var $pc81_solicitem = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 pc81_codprocitem = int8 = Código sequencial do item no processo
                 pc81_codproc = int8 = Código do processo de compras
                 pc81_solicitem = int8 = Código do registro
                 ";
   //funcao construtor da classe
   function cl_pcprocitem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcprocitem");
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
       $this->pc81_codprocitem = ($this->pc81_codprocitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_codprocitem"]:$this->pc81_codprocitem);
       $this->pc81_codproc = ($this->pc81_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_codproc"]:$this->pc81_codproc);
       $this->pc81_solicitem = ($this->pc81_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_solicitem"]:$this->pc81_solicitem);
     }else{
       $this->pc81_codprocitem = ($this->pc81_codprocitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_codprocitem"]:$this->pc81_codprocitem);
       $this->pc81_codproc = ($this->pc81_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_codproc"]:$this->pc81_codproc);
     }
   }
   // funcao para inclusao
   function incluir ($pc81_codprocitem){
      $this->atualizacampos();
     if($this->pc81_solicitem == null ){
       $this->erro_sql = " Campo Código do registro nao Informado.";
       $this->erro_campo = "pc81_solicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc81_codprocitem == "" || $pc81_codprocitem == null ){
       $result = db_query("select nextval('pcprocitem_pc81_codprocitem_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcprocitem_pc81_codprocitem_seq do campo: pc81_codprocitem";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->pc81_codprocitem = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from pcprocitem_pc81_codprocitem_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc81_codprocitem)){
         $this->erro_sql = " Campo pc81_codprocitem maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc81_codprocitem = $pc81_codprocitem;
       }
     }
     if(($this->pc81_codprocitem == null) || ($this->pc81_codprocitem == "") ){
       $this->erro_sql = " Campo pc81_codprocitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcprocitem(
                                       pc81_codprocitem
                                      ,pc81_codproc
                                      ,pc81_solicitem
                       )
                values (
                                $this->pc81_codprocitem
                               ,$this->pc81_codproc
                               ,$this->pc81_solicitem
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens de um processo de compra ($this->pc81_codprocitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens de um processo de compra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens de um processo de compra ($this->pc81_codprocitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc81_codprocitem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc81_codprocitem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6385,'$this->pc81_codprocitem','I')");
       $resac = db_query("insert into db_acount values($acount,1043,6385,'','".AddSlashes(pg_result($resaco,0,'pc81_codprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1043,6386,'','".AddSlashes(pg_result($resaco,0,'pc81_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1043,6387,'','".AddSlashes(pg_result($resaco,0,'pc81_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($pc81_codprocitem=null) {
      $this->atualizacampos();
     $sql = " update pcprocitem set ";
     $virgula = "";
     if(trim($this->pc81_codprocitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc81_codprocitem"])){
       $sql  .= $virgula." pc81_codprocitem = $this->pc81_codprocitem ";
       $virgula = ",";
       if(trim($this->pc81_codprocitem) == null ){
         $this->erro_sql = " Campo Código sequencial do item no processo nao Informado.";
         $this->erro_campo = "pc81_codprocitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc81_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc81_codproc"])){
       $sql  .= $virgula." pc81_codproc = $this->pc81_codproc ";
       $virgula = ",";
       if(trim($this->pc81_codproc) == null ){
         $this->erro_sql = " Campo Código do processo de compras nao Informado.";
         $this->erro_campo = "pc81_codproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc81_solicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc81_solicitem"])){
       $sql  .= $virgula." pc81_solicitem = $this->pc81_solicitem ";
       $virgula = ",";
       if(trim($this->pc81_solicitem) == null ){
         $this->erro_sql = " Campo Código do registro nao Informado.";
         $this->erro_campo = "pc81_solicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc81_codprocitem!=null){
       $sql .= " pc81_codprocitem = $this->pc81_codprocitem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc81_codprocitem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6385,'$this->pc81_codprocitem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc81_codprocitem"]))
           $resac = db_query("insert into db_acount values($acount,1043,6385,'".AddSlashes(pg_result($resaco,$conresaco,'pc81_codprocitem'))."','$this->pc81_codprocitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc81_codproc"]))
           $resac = db_query("insert into db_acount values($acount,1043,6386,'".AddSlashes(pg_result($resaco,$conresaco,'pc81_codproc'))."','$this->pc81_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc81_solicitem"]))
           $resac = db_query("insert into db_acount values($acount,1043,6387,'".AddSlashes(pg_result($resaco,$conresaco,'pc81_solicitem'))."','$this->pc81_solicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens de um processo de compra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc81_codprocitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens de um processo de compra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc81_codprocitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc81_codprocitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($pc81_codprocitem=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc81_codprocitem));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6385,'$pc81_codprocitem','E')");
         $resac = db_query("insert into db_acount values($acount,1043,6385,'','".AddSlashes(pg_result($resaco,$iresaco,'pc81_codprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1043,6386,'','".AddSlashes(pg_result($resaco,$iresaco,'pc81_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1043,6387,'','".AddSlashes(pg_result($resaco,$iresaco,'pc81_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcprocitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc81_codprocitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc81_codprocitem = $pc81_codprocitem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens de um processo de compra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc81_codprocitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens de um processo de compra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc81_codprocitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc81_codprocitem;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcprocitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_atualiza_sequencia ($valor=100000){
   	 $sql = "select setval('pcprocitem_pc81_codprocitem_seq', ".$valor.");";
   	 return $sql;
  }
   function sql_query ( $pc81_codprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcprocitem ";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcproc.pc80_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($pc81_codprocitem!=null ){
         $sql2 .= " where pcprocitem.pc81_codprocitem = $pc81_codprocitem ";
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
   function sql_query_autitem ( $pc81_codprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
//    inner join  inner join   where pc81_codproc=2 and pc22_codorc=1;
     $sql .= " from pcprocitem ";
     $sql .= "      inner join pcorcamitemproc on pcorcamitemproc.pc31_pcprocitem=pcprocitem.pc81_codprocitem";
     $sql .= "      inner join pcorcamitem on pcorcamitem.pc22_orcamitem=pcorcamitemproc.pc31_orcamitem";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
   function sql_query_dotac ( $pc81_codprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcprocitem ";
     $sql .= "      inner join solicitem            on  solicitem.pc11_codigo  = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pcproc               on  pcproc.pc80_codproc    = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicita             on  solicita.pc10_numero   = solicitem.pc11_numero";
     $sql .= "      inner join db_usuarios          on  db_usuarios.id_usuario = pcproc.pc80_usuario";
     $sql .= "      inner join db_depart            on  db_depart.coddepto     = pcproc.pc80_depto";
     $sql .= "       left join pcdotac              on pc13_codigo             = pc11_codigo";
     $sql .= "       left join pcdotaccontrapartida on pc13_sequencial         = pc19_pcdotac";
     $sql .= "       left join solicitaprotprocesso on solicitaprotprocesso.pc90_solicita  = solicitem.pc11_numero ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc81_codprocitem!=null ){
         $sql2 .= " where pcprocitem.pc81_codprocitem = $pc81_codprocitem ";
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
   function sql_query_file ( $pc81_codprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcprocitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc81_codprocitem!=null ){
         $sql2 .= " where pcprocitem.pc81_codprocitem = $pc81_codprocitem ";
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
   function sql_query_itememautoriza ( $pc81_codprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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

     $sql .= " from pcprocitem";
     $sql .= "      inner join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
     $sql .= "      inner join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
     $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
     $sql .= "      inner join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
   function sql_query_orcam ( $pc81_codprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
//    inner join  inner join   where pc81_codproc=2 and pc22_codorc=1;
     $sql .= " from pcprocitem ";
     $sql .= "      inner join pcorcamitemproc on pcorcamitemproc.pc31_pcprocitem=pcprocitem.pc81_codprocitem";
     $sql .= "      inner join pcorcamitem on pcorcamitem.pc22_orcamitem=pcorcamitemproc.pc31_orcamitem";
     $sql .= "      inner join solicitem on solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita on solicita.pc10_numero = solicitem.pc11_numero";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where pcprocitem.pc81_codprocitem = $pc81_codprocitem ";
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
   function sql_query_pcmater ( $pc81_codprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcprocitem ";
     $sql .= "      inner join pcproc                 on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicitem              on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita               on solicita.pc10_numero                = solicitem.pc11_numero";
     $sql .= "      inner join db_depart              on db_depart.coddepto                  = solicita.pc10_depto";
     $sql .= "      left  join solicitemunid          on solicitemunid.pc17_codigo           = solicitem.pc11_codigo";
     $sql .= "      left  join matunid                on matunid.m61_codmatunid              = solicitemunid.pc17_unid";
     $sql .= "      left  join db_usuarios            on pcproc.pc80_usuario                 = db_usuarios.id_usuario";
     $sql .= "      left  join solicitempcmater       on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater                on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater";
     $sql .= "      left  join pcsubgrupo             on pcsubgrupo.pc04_codsubgrupo         = pcmater.pc01_codsubgrupo";
     $sql .= "      left  join pctipo                 on pctipo.pc05_codtipo                 = pcsubgrupo.pc04_codtipo";
     $sql .= "      left  join solicitemele           on solicitemele.pc18_solicitem         = solicitem.pc11_codigo";
     $sql .= "      left  join orcelemento            on orcelemento.o56_codele              = solicitemele.pc18_codele";
     $sql .= "                                       and orcelemento.o56_anousu              = ".db_getsession("DB_anousu");
     $sql .= "      left  join empautitempcprocitem   on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
     $sql .= "      left  join empautitem             on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
     $sql .= "                                       and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
     $sql .= "      left  join empautoriza            on empautoriza.e54_autori              = empautitem.e55_autori ";
     $sql .= "      left  join cgm                    on empautoriza.e54_numcgm              = cgm.z01_numcgm ";
     $sql .= "      left  join empempaut              on empempaut.e61_autori                = empautitem.e55_autori ";
     $sql .= "      left  join empempenho             on empempenho.e60_numemp               = empempaut.e61_numemp ";
     $sql .= "      left join liclicitem              on liclicitem.l21_codpcprocitem        = pcprocitem.pc81_codprocitem";
     $sql .= "      left join processocompraloteitem  on processocompraloteitem.pc69_pcprocitem = pcprocitem.pc81_codprocitem";
     $sql .= "      left join processocompralote      on processocompralote.pc68_sequencial = processocompraloteitem.pc69_processocompralote";
     $sql2 = "";
     if($dbwhere==""){
       if($pc81_codprocitem!=null ){
         $sql2 .= " where pcprocitem.pc81_codprocitem = $pc81_codprocitem ";
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
   function sql_query_solprot ( $pc81_codprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcprocitem ";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcproc.pc80_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto";
     $sql .= "      inner join solicitemprot on  solicitem.pc11_codigo=solicitemprot.pc49_solicitem";
     $sql2 = "";
     if($dbwhere==""){
       if($pc81_codprocitem!=null ){
         $sql2 .= " where pcprocitem.pc81_codprocitem = $pc81_codprocitem ";
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

  function sql_query_soljulg ($codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from pcprocitem ";
     $sql .= "      inner join pcproc       on pc81_codproc= pc80_codproc";
     $sql .= "      inner join solicitem    on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita     on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join pcorcamitemproc on pc81_codprocitem = pc31_pcprocitem ";
     $sql .= "      inner join pcorcamval     on pc31_orcamitem = pc23_orcamitem ";
     $sql .= "      inner join pcorcamjulg    on pc23_orcamitem = pc24_orcamitem ";
     $sql .= "                               and pc23_orcamforne = pc24_orcamforne";
     $sql .= "                               and pc24_pontuacao  = 1";
     $sql .= "      inner join pcorcamforne   on pc23_orcamforne = pc21_orcamforne ";
     $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      left  join solicitemele  on  solicitemele.pc18_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join acordopcprocitem  on  pc81_codprocitem = ac23_pcprocitem";

     $sql2 = "";
     if($dbwhere==""){
       if($codigo!=null ){
         $sql2 .= " where pcprocitem.pc81_codprocitem = $codigo ";
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

  function sql_query_dotacao_reserva ( $pc81_codprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcprocitem ";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcproc.pc80_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto";
     $sql .= "      left join  pcdotac      on pc13_codigo = pc11_codigo";
     $sql .= "      left join  orcdotacao   on o58_coddot  = pc13_coddot";
     $sql .= "                             and o58_anousu  = pc13_anousu";
     $sql .= "      left  join orcelemento  on orcdotacao.o58_codele  = orcelemento.o56_codele";
     $sql .= "                             and orcdotacao.o58_anousu =  orcelemento.o56_anousu";
     $sql .= "      left  join orcunidade   on  orcdotacao.o58_orgao = orcunidade.o41_orgao ";
     $sql .= "                             and  orcdotacao.o58_anousu = orcunidade.o41_anousu ";
     $sql .= "                             and orcdotacao.o58_unidade = orcunidade.o41_unidade ";
     $sql .= "      left  join orcprojativ  on  orcprojativ.o55_projativ = orcdotacao.o58_projativ ";
     $sql .= "                             and  orcprojativ.o55_anousu = orcdotacao.o58_anousu";
     $sql .= "      left join  pcdotaccontrapartida  on pc13_sequencial = pc19_pcdotac";
     $sql .= "      left join  orcreservasol  on pc13_sequencial = o82_pcdotac";
     $sql .= "      left join  orcreserva     on o82_codres      = o80_codres";
     $sql .= "      left  join orctiporec   on  orctiporec.o15_codigo = orcdotacao.o58_codigo";

     $sql2 = "";
     if($dbwhere==""){
       if($pc81_codprocitem!=null ){
         $sql2 .= " where pcprocitem.pc81_codprocitem = $pc81_codprocitem ";
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
  * Retorna uma query do vínculo entre o Item e o Lote
  *
  * @param Integer $iCodigoItem
  * @param String $sCampos
  * @param String $sOrdemBy
  * @param String $sWhere
  * @return String
  */
  public function sql_query_item_lote($iCodigoItem = null, $sCampos="*", $sOrdemBy=null, $sWhere="") {

    $sSql      = "select {$sCampos}";
    $sSql     .= "  from pcprocitem";
    $sSql     .= "  left join processocompraloteitem ON pc81_codprocitem = pc69_pcprocitem";

    $sWhereSql = "";
    if (!empty($iCodigoItem)) {
      $sWhereSql .= " where pc81_codprocitem = {$iCodigoItem}";
    }

    if (!empty($sWhere)) {
      $sWhereSql .= (empty($sWhereSql) ? " where " : " ") . $sWhere;
    }

    $sSql .= $sWhereSql;

    if(!empty($sOrdemBy)) {
      $sSql .= " order by {$sOrdemBy}";
    }

    return $sSql;
  }

}
