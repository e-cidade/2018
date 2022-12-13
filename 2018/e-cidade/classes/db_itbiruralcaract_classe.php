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

//MODULO: itbi
//CLASSE DA ENTIDADE itbiruralcaract
class cl_itbiruralcaract {
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
   var $it19_guia = 0;
   var $it19_codigo = 0;
   var $it19_valor = 0;
   var $it19_tipocaract = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 it19_guia = int8 = Número da guia de ITBI
                 it19_codigo = int4 = Caracteristica
                 it19_valor = float8 = Valor
                 it19_tipocaract = int4 = Tipo de Característica
                 ";
   //funcao construtor da classe
   function cl_itbiruralcaract() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbiruralcaract");
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
       $this->it19_guia = ($this->it19_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it19_guia"]:$this->it19_guia);
       $this->it19_codigo = ($this->it19_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["it19_codigo"]:$this->it19_codigo);
       $this->it19_valor = ($this->it19_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["it19_valor"]:$this->it19_valor);
       $this->it19_tipocaract = ($this->it19_tipocaract == ""?@$GLOBALS["HTTP_POST_VARS"]["it19_tipocaract"]:$this->it19_tipocaract);
     }else{
       $this->it19_guia = ($this->it19_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it19_guia"]:$this->it19_guia);
       $this->it19_codigo = ($this->it19_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["it19_codigo"]:$this->it19_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($it19_guia,$it19_codigo){
      $this->atualizacampos();
     if($this->it19_valor == null ){
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "it19_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it19_tipocaract == null ){
       $this->erro_sql = " Campo Tipo de Característica nao Informado.";
       $this->erro_campo = "it19_tipocaract";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->it19_guia = $it19_guia;
       $this->it19_codigo = $it19_codigo;
     if(($this->it19_guia == null) || ($this->it19_guia == "") ){
       $this->erro_sql = " Campo it19_guia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->it19_codigo == null) || ($this->it19_codigo == "") ){
       $this->erro_sql = " Campo it19_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbiruralcaract(
                                       it19_guia
                                      ,it19_codigo
                                      ,it19_valor
                                      ,it19_tipocaract
                       )
                values (
                                $this->it19_guia
                               ,$this->it19_codigo
                               ,$this->it19_valor
                               ,$this->it19_tipocaract
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "características do itbi rural ($this->it19_guia."-".$this->it19_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "características do itbi rural já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "características do itbi rural ($this->it19_guia."-".$this->it19_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it19_guia."-".$this->it19_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->it19_guia,$this->it19_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5847,'$this->it19_guia','I')");
       $resac = db_query("insert into db_acountkey values($acount,5848,'$this->it19_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,936,5847,'','".AddSlashes(pg_result($resaco,0,'it19_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,936,5848,'','".AddSlashes(pg_result($resaco,0,'it19_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,936,5849,'','".AddSlashes(pg_result($resaco,0,'it19_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,936,13631,'','".AddSlashes(pg_result($resaco,0,'it19_tipocaract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($it19_guia=null,$it19_codigo=null,$it19_tipocaract=null) {
      $this->atualizacampos();
     $sql = " update itbiruralcaract set ";
     $virgula = "";
     if(trim($this->it19_guia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it19_guia"])){
       $sql  .= $virgula." it19_guia = $this->it19_guia ";
       $virgula = ",";
       if(trim($this->it19_guia) == null ){
         $this->erro_sql = " Campo Número da guia de ITBI nao Informado.";
         $this->erro_campo = "it19_guia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it19_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it19_codigo"])){
       $sql  .= $virgula." it19_codigo = $this->it19_codigo ";
       $virgula = ",";
       if(trim($this->it19_codigo) == null ){
         $this->erro_sql = " Campo Caracteristica nao Informado.";
         $this->erro_campo = "it19_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it19_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it19_valor"])){
       $sql  .= $virgula." it19_valor = $this->it19_valor ";
       $virgula = ",";
       if(trim($this->it19_valor) == null ){
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "it19_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it19_tipocaract)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it19_tipocaract"])){
       $sql  .= $virgula." it19_tipocaract = $this->it19_tipocaract ";
       $virgula = ",";
       if(trim($this->it19_tipocaract) == null ){
         $this->erro_sql = " Campo Tipo de Característica nao Informado.";
         $this->erro_campo = "it19_tipocaract";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     $sAnd = "";

     if($it19_guia!=null){

       $sql .= " $sAnd it19_guia = $it19_guia";
       $sAnd = "and";
     }

     if($it19_codigo!=null){

       $sql .= " and  it19_codigo = $it19_codigo";
       $sAnd = "and";
     }

     if($it19_tipocaract!=null){

       $sql .= " and  it19_tipocaract = $it19_tipocaract";
       $sAnd = "and";
     }

     $resaco = $this->sql_record($this->sql_query_file($this->it19_guia,$this->it19_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5847,'$this->it19_guia','A')");
         $resac = db_query("insert into db_acountkey values($acount,5848,'$this->it19_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it19_guia"]))
           $resac = db_query("insert into db_acount values($acount,936,5847,'".AddSlashes(pg_result($resaco,$conresaco,'it19_guia'))."','$this->it19_guia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it19_codigo"]))
           $resac = db_query("insert into db_acount values($acount,936,5848,'".AddSlashes(pg_result($resaco,$conresaco,'it19_codigo'))."','$this->it19_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it19_valor"]))
           $resac = db_query("insert into db_acount values($acount,936,5849,'".AddSlashes(pg_result($resaco,$conresaco,'it19_valor'))."','$this->it19_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it19_tipocaract"]))
           $resac = db_query("insert into db_acount values($acount,936,13631,'".AddSlashes(pg_result($resaco,$conresaco,'it19_tipocaract'))."','$this->it19_tipocaract',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "características do itbi rural nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it19_guia."-".$this->it19_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "características do itbi rural nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it19_guia."-".$this->it19_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it19_guia."-".$this->it19_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($it19_guia=null,$it19_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($it19_guia,$it19_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5847,'$it19_guia','E')");
         $resac = db_query("insert into db_acountkey values($acount,5848,'$it19_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,936,5847,'','".AddSlashes(pg_result($resaco,$iresaco,'it19_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,936,5848,'','".AddSlashes(pg_result($resaco,$iresaco,'it19_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,936,5849,'','".AddSlashes(pg_result($resaco,$iresaco,'it19_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,936,13631,'','".AddSlashes(pg_result($resaco,$iresaco,'it19_tipocaract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itbiruralcaract
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it19_guia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it19_guia = $it19_guia ";
        }
        if($it19_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it19_codigo = $it19_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "características do itbi rural nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it19_guia."-".$it19_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "características do itbi rural nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it19_guia."-".$it19_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it19_guia."-".$it19_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbiruralcaract";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $it19_guia=null,$it19_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from itbiruralcaract ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = itbiruralcaract.it19_codigo";
     $sql .= "      inner join itbi  on  itbi.it01_guia = itbiruralcaract.it19_guia";
     $sql .= "      inner join itbitipocaract  on  itbitipocaract.it31_sequencial = itbiruralcaract.it19_tipocaract";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql .= "      inner join itbitransacao  on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql2 = "";
     if($dbwhere==""){
       if($it19_guia!=null ){
         $sql2 .= " where itbiruralcaract.it19_guia = $it19_guia ";
       }
       if($it19_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " itbiruralcaract.it19_codigo = $it19_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     if($sql2!=""){
	$sql2 .= " and ";
     }else{
	$sql2 .= " where ";
     }
     $sql2 .= " it19_valor > 0";
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
   function sql_query_file ( $it19_guia=null,$it19_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from itbiruralcaract ";
     $sql2 = "";
     if($dbwhere==""){
       if($it19_guia!=null ){
         $sql2 .= " where itbiruralcaract.it19_guia = $it19_guia ";
       }
       if($it19_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " itbiruralcaract.it19_codigo = $it19_codigo ";
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
?>