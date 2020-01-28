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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE ossoariojazigo
class cl_ossoariojazigo {
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
   var $cm25_i_codigo = 0;
   var $cm25_c_numero = null;
   var $cm25_i_lotecemit = 0;
   var $cm25_f_comprimento = 0;
   var $cm25_f_largura = 0;
   var $cm25_c_tipo = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm25_i_codigo = int4 = Código Ossário/ Jazigo
                 cm25_c_numero = varchar(12) = Numero
                 cm25_i_lotecemit = int4 = Lote Cemiterio
                 cm25_f_comprimento = float4 = Comprimento
                 cm25_f_largura = float4 = Largura
                 cm25_c_tipo = char(1) = Tipo
                 ";
   //funcao construtor da classe
   function cl_ossoariojazigo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ossoariojazigo");
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
       $this->cm25_i_codigo = ($this->cm25_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm25_i_codigo"]:$this->cm25_i_codigo);
       $this->cm25_c_numero = ($this->cm25_c_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["cm25_c_numero"]:$this->cm25_c_numero);
       $this->cm25_i_lotecemit = ($this->cm25_i_lotecemit == ""?@$GLOBALS["HTTP_POST_VARS"]["cm25_i_lotecemit"]:$this->cm25_i_lotecemit);
       $this->cm25_f_comprimento = ($this->cm25_f_comprimento == ""?@$GLOBALS["HTTP_POST_VARS"]["cm25_f_comprimento"]:$this->cm25_f_comprimento);
       $this->cm25_f_largura = ($this->cm25_f_largura == ""?@$GLOBALS["HTTP_POST_VARS"]["cm25_f_largura"]:$this->cm25_f_largura);
       $this->cm25_c_tipo = ($this->cm25_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm25_c_tipo"]:$this->cm25_c_tipo);
     }else{
       $this->cm25_i_codigo = ($this->cm25_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm25_i_codigo"]:$this->cm25_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm25_i_codigo){
      $this->atualizacampos();
     if($this->cm25_c_numero == null ){
       $this->erro_sql = " Campo Numero nao Informado.";
       $this->erro_campo = "cm25_c_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm25_i_lotecemit == null ){
       $this->erro_sql = " Campo Lote Cemiterio nao Informado.";
       $this->erro_campo = "cm25_i_lotecemit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm25_f_comprimento == null ){
       $this->erro_sql = " Campo Comprimento nao Informado.";
       $this->erro_campo = "cm25_f_comprimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm25_f_largura == null ){
       $this->erro_sql = " Campo Largura nao Informado.";
       $this->erro_campo = "cm25_f_largura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm25_c_tipo == null ){
       $this->cm25_c_tipo = "0";
     }
     if($cm25_i_codigo == "" || $cm25_i_codigo == null ){
       $result = db_query("select nextval('ossoariojazigo_cm25_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ossoariojazigo_cm25_i_codigo_seq do campo: cm25_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm25_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from ossoariojazigo_cm25_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm25_i_codigo)){
         $this->erro_sql = " Campo cm25_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm25_i_codigo = $cm25_i_codigo;
       }
     }
     if(($this->cm25_i_codigo == null) || ($this->cm25_i_codigo == "") ){
       $this->erro_sql = " Campo cm25_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ossoariojazigo(
                                       cm25_i_codigo
                                      ,cm25_c_numero
                                      ,cm25_i_lotecemit
                                      ,cm25_f_comprimento
                                      ,cm25_f_largura
                                      ,cm25_c_tipo
                       )
                values (
                                $this->cm25_i_codigo
                               ,'$this->cm25_c_numero'
                               ,$this->cm25_i_lotecemit
                               ,$this->cm25_f_comprimento
                               ,$this->cm25_f_largura
                               ,'$this->cm25_c_tipo'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ossário Jazigo ($this->cm25_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ossário Jazigo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ossário Jazigo ($this->cm25_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm25_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm25_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10354,'$this->cm25_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1792,10354,'','".AddSlashes(pg_result($resaco,0,'cm25_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1792,12080,'','".AddSlashes(pg_result($resaco,0,'cm25_c_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1792,10355,'','".AddSlashes(pg_result($resaco,0,'cm25_i_lotecemit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1792,10356,'','".AddSlashes(pg_result($resaco,0,'cm25_f_comprimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1792,10357,'','".AddSlashes(pg_result($resaco,0,'cm25_f_largura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1792,10358,'','".AddSlashes(pg_result($resaco,0,'cm25_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm25_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update ossoariojazigo set ";
     $virgula = "";
     if(trim($this->cm25_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm25_i_codigo"])){
       $sql  .= $virgula." cm25_i_codigo = $this->cm25_i_codigo ";
       $virgula = ",";
       if(trim($this->cm25_i_codigo) == null ){
         $this->erro_sql = " Campo Código Ossário/ Jazigo nao Informado.";
         $this->erro_campo = "cm25_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm25_c_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm25_c_numero"])){
       $sql  .= $virgula." cm25_c_numero = '$this->cm25_c_numero' ";
       $virgula = ",";
       if(trim($this->cm25_c_numero) == null ){
         $this->erro_sql = " Campo Numero nao Informado.";
         $this->erro_campo = "cm25_c_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm25_i_lotecemit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm25_i_lotecemit"])){
       $sql  .= $virgula." cm25_i_lotecemit = $this->cm25_i_lotecemit ";
       $virgula = ",";
       if(trim($this->cm25_i_lotecemit) == null ){
         $this->erro_sql = " Campo Lote Cemiterio nao Informado.";
         $this->erro_campo = "cm25_i_lotecemit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm25_f_comprimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm25_f_comprimento"])){
       $sql  .= $virgula." cm25_f_comprimento = $this->cm25_f_comprimento ";
       $virgula = ",";
       if(trim($this->cm25_f_comprimento) == null ){
         $this->erro_sql = " Campo Comprimento nao Informado.";
         $this->erro_campo = "cm25_f_comprimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm25_f_largura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm25_f_largura"])){
       $sql  .= $virgula." cm25_f_largura = $this->cm25_f_largura ";
       $virgula = ",";
       if(trim($this->cm25_f_largura) == null ){
         $this->erro_sql = " Campo Largura nao Informado.";
         $this->erro_campo = "cm25_f_largura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm25_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm25_c_tipo"])){
       $sql  .= $virgula." cm25_c_tipo = '$this->cm25_c_tipo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($cm25_i_codigo!=null){
       $sql .= " cm25_i_codigo = $this->cm25_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm25_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10354,'$this->cm25_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm25_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1792,10354,'".AddSlashes(pg_result($resaco,$conresaco,'cm25_i_codigo'))."','$this->cm25_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm25_c_numero"]))
           $resac = db_query("insert into db_acount values($acount,1792,12080,'".AddSlashes(pg_result($resaco,$conresaco,'cm25_c_numero'))."','$this->cm25_c_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm25_i_lotecemit"]))
           $resac = db_query("insert into db_acount values($acount,1792,10355,'".AddSlashes(pg_result($resaco,$conresaco,'cm25_i_lotecemit'))."','$this->cm25_i_lotecemit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm25_f_comprimento"]))
           $resac = db_query("insert into db_acount values($acount,1792,10356,'".AddSlashes(pg_result($resaco,$conresaco,'cm25_f_comprimento'))."','$this->cm25_f_comprimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm25_f_largura"]))
           $resac = db_query("insert into db_acount values($acount,1792,10357,'".AddSlashes(pg_result($resaco,$conresaco,'cm25_f_largura'))."','$this->cm25_f_largura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm25_c_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1792,10358,'".AddSlashes(pg_result($resaco,$conresaco,'cm25_c_tipo'))."','$this->cm25_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ossário Jazigo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm25_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ossário Jazigo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm25_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm25_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10354,'$cm25_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1792,10354,'','".AddSlashes(pg_result($resaco,$iresaco,'cm25_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1792,12080,'','".AddSlashes(pg_result($resaco,$iresaco,'cm25_c_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1792,10355,'','".AddSlashes(pg_result($resaco,$iresaco,'cm25_i_lotecemit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1792,10356,'','".AddSlashes(pg_result($resaco,$iresaco,'cm25_f_comprimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1792,10357,'','".AddSlashes(pg_result($resaco,$iresaco,'cm25_f_largura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1792,10358,'','".AddSlashes(pg_result($resaco,$iresaco,'cm25_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ossoariojazigo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm25_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm25_i_codigo = $cm25_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ossário Jazigo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm25_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ossário Jazigo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm25_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:ossoariojazigo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm25_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
$sql .= " from ossoariojazigo ";
$sql .= " inner join lotecemit      on  lotecemit.cm23_i_codigo            =  ossoariojazigo.cm25_i_lotecemit ";
$sql .= " inner join quadracemit    on  quadracemit.cm22_i_codigo          =  lotecemit.cm23_i_quadracemit    ";
$sql .= " inner join cemiterio      on  cemiterio.cm14_i_codigo            =  quadracemit.cm22_i_cemiterio    ";
$sql .= "  left join cemiteriorural on  cm14_i_codigo                      =  cm16_i_cemiterio                ";
$sql .= "  left join cemiteriocgm   on  cm14_i_codigo                      =  cm15_i_cemiterio                ";
$sql .= "  left join cgm            on  z01_numcgm                         =  cm15_i_cgm                      ";
$sql .= "  left join propricemit    on  propricemit.cm28_i_ossoariojazigo  =  ossoariojazigo.cm25_i_codigo    ";
$sql .= "  left join cgm cgmpropri  on  cgmpropri.z01_numcgm               =  propricemit.cm28_i_proprietario ";
$sql .= "  left join protprocesso   on  protprocesso.p58_codproc           =  propricemit.cm28_i_processo     ";
//$sql .= "  left join cgm            on  cgm.z01_numcgm                     =  protprocesso.p58_numcgm         ";
//$sql .= "  left join db_usuarios    on  db_usuarios.id_usuario             =  protprocesso.p58_id_usuario     ";
//$sql .= "  left join db_depart      on  db_depart.coddepto                 =  protprocesso.p58_coddepto       ";
//$sql .= "  left join tipoproc       on  tipoproc.p51_codigo                =  protprocesso.p58_codigo         ";


     $sql2 = "";
     if($dbwhere==""){
       if($cm25_i_codigo!=null ){
         $sql2 .= " where ossoariojazigo.cm25_i_codigo = $cm25_i_codigo ";
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
   function sql_query_file ( $cm25_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ossoariojazigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm25_i_codigo!=null ){
         $sql2 .= " where ossoariojazigo.cm25_i_codigo = $cm25_i_codigo ";
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