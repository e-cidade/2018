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
//CLASSE DA ENTIDADE matrequiitem
class cl_matrequiitem {
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
   var $m41_codigo = 0;
   var $m41_codmatrequi = 0;
   var $m41_codmatmater = 0;
   var $m41_codunid = 0;
   var $m41_quant = 0;
   var $m41_obs = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 m41_codigo = int8 = Codigo Sequencial
                 m41_codmatrequi = int8 = Codigo Sequencial
                 m41_codmatmater = int8 = Código do material
                 m41_codunid = int8 = Código da unidade
                 m41_quant = float8 = Quantidade
                 m41_obs = text = Observação
                 ";
   //funcao construtor da classe
   function cl_matrequiitem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matrequiitem");
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
       $this->m41_codigo = ($this->m41_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m41_codigo"]:$this->m41_codigo);
       $this->m41_codmatrequi = ($this->m41_codmatrequi == ""?@$GLOBALS["HTTP_POST_VARS"]["m41_codmatrequi"]:$this->m41_codmatrequi);
       $this->m41_codmatmater = ($this->m41_codmatmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m41_codmatmater"]:$this->m41_codmatmater);
       $this->m41_codunid = ($this->m41_codunid == ""?@$GLOBALS["HTTP_POST_VARS"]["m41_codunid"]:$this->m41_codunid);
       $this->m41_quant = ($this->m41_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["m41_quant"]:$this->m41_quant);
       $this->m41_obs = ($this->m41_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["m41_obs"]:$this->m41_obs);
     }else{
       $this->m41_codigo = ($this->m41_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m41_codigo"]:$this->m41_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m41_codigo){
      $this->atualizacampos();
     if($this->m41_codmatrequi == null ){
       $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
       $this->erro_campo = "m41_codmatrequi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m41_codmatmater == null ){
       $this->erro_sql = " Campo Código do material nao Informado.";
       $this->erro_campo = "m41_codmatmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m41_codunid == null ){
       $this->erro_sql = " Campo Código da unidade nao Informado.";
       $this->erro_campo = "m41_codunid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m41_quant == null ){
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "m41_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m41_codigo == "" || $m41_codigo == null ){
       $result = db_query("select nextval('matrequiitem_m41_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matrequiitem_m41_codigo_seq do campo: m41_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->m41_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from matrequiitem_m41_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m41_codigo)){
         $this->erro_sql = " Campo m41_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m41_codigo = $m41_codigo;
       }
     }
     if(($this->m41_codigo == null) || ($this->m41_codigo == "") ){
       $this->erro_sql = " Campo m41_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matrequiitem(
                                       m41_codigo
                                      ,m41_codmatrequi
                                      ,m41_codmatmater
                                      ,m41_codunid
                                      ,m41_quant
                                      ,m41_obs
                       )
                values (
                                $this->m41_codigo
                               ,$this->m41_codmatrequi
                               ,$this->m41_codmatmater
                               ,$this->m41_codunid
                               ,$this->m41_quant
                               ,'$this->m41_obs'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matrequiitem ($this->m41_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matrequiitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matrequiitem ($this->m41_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m41_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m41_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6870,'$this->m41_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1129,6870,'','".AddSlashes(pg_result($resaco,0,'m41_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1129,6871,'','".AddSlashes(pg_result($resaco,0,'m41_codmatrequi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1129,6872,'','".AddSlashes(pg_result($resaco,0,'m41_codmatmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1129,7432,'','".AddSlashes(pg_result($resaco,0,'m41_codunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1129,6873,'','".AddSlashes(pg_result($resaco,0,'m41_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1129,6874,'','".AddSlashes(pg_result($resaco,0,'m41_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($m41_codigo=null) {
      $this->atualizacampos();
     $sql = " update matrequiitem set ";
     $virgula = "";
     if(trim($this->m41_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m41_codigo"])){
       $sql  .= $virgula." m41_codigo = $this->m41_codigo ";
       $virgula = ",";
       if(trim($this->m41_codigo) == null ){
         $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
         $this->erro_campo = "m41_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m41_codmatrequi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m41_codmatrequi"])){
       $sql  .= $virgula." m41_codmatrequi = $this->m41_codmatrequi ";
       $virgula = ",";
       if(trim($this->m41_codmatrequi) == null ){
         $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
         $this->erro_campo = "m41_codmatrequi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m41_codmatmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m41_codmatmater"])){
       $sql  .= $virgula." m41_codmatmater = $this->m41_codmatmater ";
       $virgula = ",";
       if(trim($this->m41_codmatmater) == null ){
         $this->erro_sql = " Campo Código do material nao Informado.";
         $this->erro_campo = "m41_codmatmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m41_codunid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m41_codunid"])){
       $sql  .= $virgula." m41_codunid = $this->m41_codunid ";
       $virgula = ",";
       if(trim($this->m41_codunid) == null ){
         $this->erro_sql = " Campo Código da unidade nao Informado.";
         $this->erro_campo = "m41_codunid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m41_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m41_quant"])){
       $sql  .= $virgula." m41_quant = $this->m41_quant ";
       $virgula = ",";
       if(trim($this->m41_quant) == null ){
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "m41_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m41_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m41_obs"])){
       $sql  .= $virgula." m41_obs = '$this->m41_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m41_codigo!=null){
       $sql .= " m41_codigo = $this->m41_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m41_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6870,'$this->m41_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m41_codigo"]) || $this->m41_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1129,6870,'".AddSlashes(pg_result($resaco,$conresaco,'m41_codigo'))."','$this->m41_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m41_codmatrequi"]) || $this->m41_codmatrequi != "")
           $resac = db_query("insert into db_acount values($acount,1129,6871,'".AddSlashes(pg_result($resaco,$conresaco,'m41_codmatrequi'))."','$this->m41_codmatrequi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m41_codmatmater"]) || $this->m41_codmatmater != "")
           $resac = db_query("insert into db_acount values($acount,1129,6872,'".AddSlashes(pg_result($resaco,$conresaco,'m41_codmatmater'))."','$this->m41_codmatmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m41_codunid"]) || $this->m41_codunid != "")
           $resac = db_query("insert into db_acount values($acount,1129,7432,'".AddSlashes(pg_result($resaco,$conresaco,'m41_codunid'))."','$this->m41_codunid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m41_quant"]) || $this->m41_quant != "")
           $resac = db_query("insert into db_acount values($acount,1129,6873,'".AddSlashes(pg_result($resaco,$conresaco,'m41_quant'))."','$this->m41_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m41_obs"]) || $this->m41_obs != "")
           $resac = db_query("insert into db_acount values($acount,1129,6874,'".AddSlashes(pg_result($resaco,$conresaco,'m41_obs'))."','$this->m41_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matrequiitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m41_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matrequiitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m41_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m41_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($m41_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m41_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6870,'$m41_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1129,6870,'','".AddSlashes(pg_result($resaco,$iresaco,'m41_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1129,6871,'','".AddSlashes(pg_result($resaco,$iresaco,'m41_codmatrequi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1129,6872,'','".AddSlashes(pg_result($resaco,$iresaco,'m41_codmatmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1129,7432,'','".AddSlashes(pg_result($resaco,$iresaco,'m41_codunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1129,6873,'','".AddSlashes(pg_result($resaco,$iresaco,'m41_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1129,6874,'','".AddSlashes(pg_result($resaco,$iresaco,'m41_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matrequiitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m41_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m41_codigo = $m41_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matrequiitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m41_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matrequiitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m41_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m41_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matrequiitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $m41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matrequiitem ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matrequiitem.m41_codunid";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join matunid  as a on   a.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matrequi.m40_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matrequi.m40_depto";
     $sql .= "      inner join db_almox  on  db_almox.m91_codigo = matrequi.m40_almox";
     $sql2 = "";
     if($dbwhere==""){
       if($m41_codigo!=null ){
         $sql2 .= " where matrequiitem.m41_codigo = $m41_codigo ";
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
   function sql_query_file ( $m41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matrequiitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($m41_codigo!=null ){
         $sql2 .= " where matrequiitem.m41_codigo = $m41_codigo ";
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
   function sql_query_atend ( $m41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matrequiitem ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matrequi.m40_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matrequi.m40_depto";
     $sql .= "      left join atendrequiitem  on  atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo";
     $sql .= "      left join matanulitemrequi  on  matanulitemrequi.m102_matrequiitem = matrequiitem.m41_codigo";

     $sql2 = "";
     if($dbwhere==""){
       if($m41_codigo!=null ){
         $sql2 .= " where matrequiitem.m41_codigo = $m41_codigo ";
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
   function sql_query_estoque ( $m41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matrequiitem ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matrequi.m40_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matrequi.m40_depto";
     $sql .= "      left join atendrequiitem  on  atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo";
     $sql .= "      inner join matestoque on m60_codmater =  m70_codmatmater";
     $sql .= "      left  join matrequiitemcriteriocustorateio on cc13_matrequiitem = m41_codigo";
     $sql .= "      left  join custocriteriorateio on cc13_custocriteriorateio      = cc08_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($m41_codigo!=null ){
         $sql2 .= " where matrequiitem.m41_codigo = $m41_codigo ";
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
   function sql_query_estoque_atend_requi ( $m41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matrequiitem ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join matmaterunisai  on  matmaterunisai.m62_codmater = matmater.m60_codmater";
     $sql .= "      inner join matunid as unidade_saida  on unidade_saida.m61_codmatunid = matmaterunisai.m62_codmatunid";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matrequi.m40_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matrequi.m40_depto";
     $sql .= "      inner join db_almox   on  matrequi.m40_almox = m91_codigo";
     $sql .= "      left  join matrequiitemcriteriocustorateio on cc13_matrequiitem = m41_codigo";
     $sql .= "      left  join custocriteriorateio on cc13_custocriteriorateio      = cc08_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($m41_codigo!=null ){
         $sql2 .= " where matrequiitem.m41_codigo = $m41_codigo ";
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
   function sql_query_estoque_req ( $m41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matrequiitem ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matrequi.m40_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matrequi.m40_depto";
     $sql .= "      left join atendrequiitem  on  atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo";
     $sql .= "      left  join matrequiitemcriteriocustorateio on cc13_matrequiitem = m41_codigo";
     $sql .= "      left  join custocriteriorateio on cc13_custocriteriorateio      = cc08_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($m41_codigo!=null ){
         $sql2 .= " where matrequiitem.m41_codigo = $m41_codigo ";
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

   function sql_query_estoque_anul ( $m41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matrequiitem ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matrequi.m40_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matrequi.m40_depto";
     $sql .= "      inner join db_almox  on  db_almox.m91_codigo = matrequi.m40_almox";
     $sql .= "      left join atendrequiitem  on  atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo";
     $sql .= "      inner join matestoque on m60_codmater =  m70_codmatmater";
     $sql .= "      left  join matrequiitemcriteriocustorateio on cc13_matrequiitem = m41_codigo";
     $sql .= "      left  join custocriteriorateio on cc13_custocriteriorateio      = cc08_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($m41_codigo!=null ){
         $sql2 .= " where matrequiitem.m41_codigo = $m41_codigo ";
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
  function sql_query_matrequianul ( $m41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= " from matrequiitem ";
  	$sql .= "      inner join matmater  on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
  	$sql .= "      inner join matrequi  on  matrequi.m40_codigo = matrequiitem.m41_codmatrequi";
  	$sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
  	$sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matrequi.m40_login";
  	$sql .= "      inner join db_depart  on  db_depart.coddepto = matrequi.m40_depto";
  	$sql .= "      left join matanulitemrequi  on  matanulitemrequi.m102_matrequiitem = matrequiitem.m41_codigo";
  	$sql .= "      left join matanulitem  on  matanulitem.m103_codigo = matanulitemrequi.m102_matanulitem";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($m41_codigo!=null ){
  			$sql2 .= " where matrequiitem.m41_codigo = $m41_codigo ";
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