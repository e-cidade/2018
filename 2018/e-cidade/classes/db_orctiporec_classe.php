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

//MODULO: orcamento
//CLASSE DA ENTIDADE orctiporec
class cl_orctiporec {
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
   var $o15_codigo = 0;
   var $o15_descr = null;
   var $o15_codtri = null;
   var $o15_finali = null;
   var $o15_tipo = 0;
   var $o15_datalimite_dia = null;
   var $o15_datalimite_mes = null;
   var $o15_datalimite_ano = null;
   var $o15_datalimite = null;
   var $o15_db_estruturavalor = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 o15_codigo = int4 = Recurso
                 o15_descr = varchar(60) = Descricao do Tipo de Recurso
                 o15_codtri = varchar(10) = Código Tribunal
                 o15_finali = text = Finalidade do Recurso
                 o15_tipo = int4 = Tipo do Recurso
                 o15_datalimite = date = Data Limite
                 o15_db_estruturavalor = int4 = Código da Estrutura
                 ";
   //funcao construtor da classe
   function cl_orctiporec() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orctiporec");
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
       $this->o15_codigo = ($this->o15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o15_codigo"]:$this->o15_codigo);
       $this->o15_descr = ($this->o15_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o15_descr"]:$this->o15_descr);
       $this->o15_codtri = ($this->o15_codtri == ""?@$GLOBALS["HTTP_POST_VARS"]["o15_codtri"]:$this->o15_codtri);
       $this->o15_finali = ($this->o15_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["o15_finali"]:$this->o15_finali);
       $this->o15_tipo = ($this->o15_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["o15_tipo"]:$this->o15_tipo);
       if($this->o15_datalimite == ""){
         $this->o15_datalimite_dia = ($this->o15_datalimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o15_datalimite_dia"]:$this->o15_datalimite_dia);
         $this->o15_datalimite_mes = ($this->o15_datalimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o15_datalimite_mes"]:$this->o15_datalimite_mes);
         $this->o15_datalimite_ano = ($this->o15_datalimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o15_datalimite_ano"]:$this->o15_datalimite_ano);
         if($this->o15_datalimite_dia != ""){
            $this->o15_datalimite = $this->o15_datalimite_ano."-".$this->o15_datalimite_mes."-".$this->o15_datalimite_dia;
         }
       }
       $this->o15_db_estruturavalor = ($this->o15_db_estruturavalor == ""?@$GLOBALS["HTTP_POST_VARS"]["o15_db_estruturavalor"]:$this->o15_db_estruturavalor);
     }else{
       $this->o15_codigo = ($this->o15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o15_codigo"]:$this->o15_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($o15_codigo){
      $this->atualizacampos();
     if($this->o15_descr == null ){
       $this->erro_sql = " Campo Descricao do Tipo de Recurso nao Informado.";
       $this->erro_campo = "o15_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o15_codtri == null ){
       $this->erro_sql = " Campo Código Tribunal nao Informado.";
       $this->erro_campo = "o15_codtri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o15_finali == null ){
       $this->erro_sql = " Campo Finalidade do Recurso nao Informado.";
       $this->erro_campo = "o15_finali";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o15_tipo == null ){
       $this->erro_sql = " Campo Tipo do Recurso nao Informado.";
       $this->erro_campo = "o15_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o15_datalimite == null ){
       $this->o15_datalimite = "null";
     }
     if($this->o15_db_estruturavalor == null ){
       $this->erro_sql = " Campo Código da Estrutura nao Informado.";
       $this->erro_campo = "o15_db_estruturavalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o15_codigo = $o15_codigo;
     if(($this->o15_codigo == null) || ($this->o15_codigo == "") ){
       $this->erro_sql = " Campo o15_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orctiporec(
                                       o15_codigo
                                      ,o15_descr
                                      ,o15_codtri
                                      ,o15_finali
                                      ,o15_tipo
                                      ,o15_datalimite
                                      ,o15_db_estruturavalor
                       )
                values (
                                $this->o15_codigo
                               ,'$this->o15_descr'
                               ,'$this->o15_codtri'
                               ,'$this->o15_finali'
                               ,$this->o15_tipo
                               ,".($this->o15_datalimite == "null" || $this->o15_datalimite == ""?"null":"'".$this->o15_datalimite."'")."
                               ,$this->o15_db_estruturavalor
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de Recursos ($this->o15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de Recursos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de Recursos ($this->o15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o15_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o15_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3347,'$this->o15_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,749,3347,'','".AddSlashes(pg_result($resaco,0,'o15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,749,3348,'','".AddSlashes(pg_result($resaco,0,'o15_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,749,3350,'','".AddSlashes(pg_result($resaco,0,'o15_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,749,3351,'','".AddSlashes(pg_result($resaco,0,'o15_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,749,11898,'','".AddSlashes(pg_result($resaco,0,'o15_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,749,13689,'','".AddSlashes(pg_result($resaco,0,'o15_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,749,18132,'','".AddSlashes(pg_result($resaco,0,'o15_db_estruturavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($o15_codigo=null) {
      $this->atualizacampos();
     $sql = " update orctiporec set ";
     $virgula = "";
     if(trim($this->o15_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o15_codigo"])){
       $sql  .= $virgula." o15_codigo = $this->o15_codigo ";
       $virgula = ",";
       if(trim($this->o15_codigo) == null ){
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "o15_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o15_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o15_descr"])){
       $sql  .= $virgula." o15_descr = '$this->o15_descr' ";
       $virgula = ",";
       if(trim($this->o15_descr) == null ){
         $this->erro_sql = " Campo Descricao do Tipo de Recurso nao Informado.";
         $this->erro_campo = "o15_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o15_codtri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o15_codtri"])){
       $sql  .= $virgula." o15_codtri = '$this->o15_codtri' ";
       $virgula = ",";
       if(trim($this->o15_codtri) == null ){
         $this->erro_sql = " Campo Código Tribunal nao Informado.";
         $this->erro_campo = "o15_codtri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o15_finali)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o15_finali"])){
       $sql  .= $virgula." o15_finali = '$this->o15_finali' ";
       $virgula = ",";
       if(trim($this->o15_finali) == null ){
         $this->erro_sql = " Campo Finalidade do Recurso nao Informado.";
         $this->erro_campo = "o15_finali";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o15_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o15_tipo"])){
       $sql  .= $virgula." o15_tipo = $this->o15_tipo ";
       $virgula = ",";
       if(trim($this->o15_tipo) == null ){
         $this->erro_sql = " Campo Tipo do Recurso nao Informado.";
         $this->erro_campo = "o15_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o15_datalimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o15_datalimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o15_datalimite_dia"] !="") ){
       $sql  .= $virgula." o15_datalimite = '$this->o15_datalimite' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["o15_datalimite_dia"])){
         $sql  .= $virgula." o15_datalimite = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o15_db_estruturavalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o15_db_estruturavalor"])){
       $sql  .= $virgula." o15_db_estruturavalor = $this->o15_db_estruturavalor ";
       $virgula = ",";
       if(trim($this->o15_db_estruturavalor) == null ){
         $this->erro_sql = " Campo Código da Estrutura nao Informado.";
         $this->erro_campo = "o15_db_estruturavalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o15_codigo!=null||$o15_codigo==0){
       $sql .= " o15_codigo = {$o15_codigo}";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o15_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3347,'$this->o15_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o15_codigo"]) || $this->o15_codigo != "")
           $resac = db_query("insert into db_acount values($acount,749,3347,'".AddSlashes(pg_result($resaco,$conresaco,'o15_codigo'))."','$this->o15_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o15_descr"]) || $this->o15_descr != "")
           $resac = db_query("insert into db_acount values($acount,749,3348,'".AddSlashes(pg_result($resaco,$conresaco,'o15_descr'))."','$this->o15_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o15_codtri"]) || $this->o15_codtri != "")
           $resac = db_query("insert into db_acount values($acount,749,3350,'".AddSlashes(pg_result($resaco,$conresaco,'o15_codtri'))."','$this->o15_codtri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o15_finali"]) || $this->o15_finali != "")
           $resac = db_query("insert into db_acount values($acount,749,3351,'".AddSlashes(pg_result($resaco,$conresaco,'o15_finali'))."','$this->o15_finali',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o15_tipo"]) || $this->o15_tipo != "")
           $resac = db_query("insert into db_acount values($acount,749,11898,'".AddSlashes(pg_result($resaco,$conresaco,'o15_tipo'))."','$this->o15_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o15_datalimite"]) || $this->o15_datalimite != "")
           $resac = db_query("insert into db_acount values($acount,749,13689,'".AddSlashes(pg_result($resaco,$conresaco,'o15_datalimite'))."','$this->o15_datalimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o15_db_estruturavalor"]) || $this->o15_db_estruturavalor != "")
           $resac = db_query("insert into db_acount values($acount,749,18132,'".AddSlashes(pg_result($resaco,$conresaco,'o15_db_estruturavalor'))."','$this->o15_db_estruturavalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Recursos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Recursos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($o15_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o15_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3347,'$o15_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,749,3347,'','".AddSlashes(pg_result($resaco,$iresaco,'o15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,749,3348,'','".AddSlashes(pg_result($resaco,$iresaco,'o15_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,749,3350,'','".AddSlashes(pg_result($resaco,$iresaco,'o15_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,749,3351,'','".AddSlashes(pg_result($resaco,$iresaco,'o15_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,749,11898,'','".AddSlashes(pg_result($resaco,$iresaco,'o15_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,749,13689,'','".AddSlashes(pg_result($resaco,$iresaco,'o15_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,749,18132,'','".AddSlashes(pg_result($resaco,$iresaco,'o15_db_estruturavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orctiporec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o15_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o15_codigo = $o15_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Recursos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Recursos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o15_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:orctiporec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $o15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orctiporec ";
     $sql2 = "";
     if($dbwhere==""){
       if($o15_codigo!=null ){
         $sql2 .= " where orctiporec.o15_codigo = $o15_codigo ";
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
   function sql_query_file ( $o15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orctiporec ";
     $sql2 = "";
     if($dbwhere==""){
       if($o15_codigo!=null ){
         $sql2 .= " where orctiporec.o15_codigo = $o15_codigo ";
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
   function sql_query_convenios ($sDataini, $sDataFim = null, $campos="*",$ordem="o15_codigo",$dbwhere=""){

   if (empty($sDataini)) {
     return false;
   }
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
     $sql .= " from orctiporec ";
     $sql .= "      inner join orctiporecconvenio   on o15_codigo = o16_orctiporec";
     $sql2 = " where ";
     if ($sDataFim == null) {

        $sql2 .= "(('{$sDataini}' between o16_dtvigenciaini and o16_dtvigenciafim) or ";
        $sql2 .= "('{$sDataini}' between o16_dtprorrogacaoini and o16_dtprorrogacaofim))";

     } else {

       $sql2 .= "((o16_dtvigenciaini <= '{$sDataini}' and o16_dtvigenciafim >= '{$sDataFim}') or ";
       $sql2 .= "(o16_dtprorrogacaoini <= '{$sDataini}' and o16_dtprorrogacaofim >= '{$sDataFim}'))";

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
   function sql_query_emp( $o15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orctiporec ";
     $sql .= "   inner join orcdotacao on o58_codigo = o15_codigo ";
     $sql .= "   inner join empempenho on e60_coddot = o58_coddot and e60_anousu=o58_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($o15_codigo!=null ){
         $sql2 .= " where orctiporec.o15_codigo = $o15_codigo ";
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
   function sql_query_orcamento($campos="*",$ordem=null,$dbwhere="") {
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

     $sql_campos = $sql;

     $sql .= " from orctiporec ";
     $sql .= "   inner join orcdotacao on o58_codigo = o15_codigo ";
     $sql .= "   where o58_anousu = " . db_getsession("DB_anousu") . " and o58_instit = " . db_getsession("DB_instit");
     $sql .= " union $sql_campos ";
     $sql .= " from orctiporec ";
     $sql .= "   inner join orcreceita on o70_codigo = o15_codigo ";
     $sql .= "   where o70_anousu = " . db_getsession("DB_anousu") . " and o70_instit = " . db_getsession("DB_instit");

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
   * @param string $sCampos
   * @param null   $sWhere
   *
   * @return string
   */
  public function sql_recurso_despesa($sCampos = "*", $sWhere = null) {

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from orctiporec ";
    $sSql .= "        inner join orcdotacao on orcdotacao.o58_codigo = orctiporec.o15_codigo ";

    if (!empty($sWhere)) {
      $sSql .= " where = {$sWhere} ";
    }
    return $sSql;
  }

  public function sql_query_contacorrentedetalhe($sCampos = "*", $sWhere = null) {
    
    $sSql  = " select {$sCampos} ";
    $sSql .= " from orctiporec ";
    $sSql .= "    inner join contacorrentedetalhe on c19_orctiporec = o15_codigo ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }
}