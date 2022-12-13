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
//CLASSE DA ENTIDADE sepulthist
class cl_sepulthist {
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
   var $cm21_i_codigo = 0;
   var $cm21_i_sepultamento = 0;
   var $cm21_i_usuario = 0;
   var $cm21_d_data_dia = null;
   var $cm21_d_data_mes = null;
   var $cm21_d_data_ano = null;
   var $cm21_d_data = null;
   var $cm21_c_localnovo = null;
   var $cm21_c_localant = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm21_i_codigo = int4 = Código
                 cm21_i_sepultamento = int4 = Sepultamento
                 cm21_i_usuario = int4 = Usuário
                 cm21_d_data = date = Data
                 cm21_c_localnovo = char(200) = Local Novo
                 cm21_c_localant = char(200) = Local Anterior
                 ";
   //funcao construtor da classe
   function cl_sepulthist() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sepulthist");
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
       $this->cm21_i_codigo = ($this->cm21_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm21_i_codigo"]:$this->cm21_i_codigo);
       $this->cm21_i_sepultamento = ($this->cm21_i_sepultamento == ""?@$GLOBALS["HTTP_POST_VARS"]["cm21_i_sepultamento"]:$this->cm21_i_sepultamento);
       $this->cm21_i_usuario = ($this->cm21_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["cm21_i_usuario"]:$this->cm21_i_usuario);
       if($this->cm21_d_data == ""){
         $this->cm21_d_data_dia = ($this->cm21_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm21_d_data_dia"]:$this->cm21_d_data_dia);
         $this->cm21_d_data_mes = ($this->cm21_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm21_d_data_mes"]:$this->cm21_d_data_mes);
         $this->cm21_d_data_ano = ($this->cm21_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm21_d_data_ano"]:$this->cm21_d_data_ano);
         if($this->cm21_d_data_dia != ""){
            $this->cm21_d_data = $this->cm21_d_data_ano."-".$this->cm21_d_data_mes."-".$this->cm21_d_data_dia;
         }
       }
       $this->cm21_c_localnovo = ($this->cm21_c_localnovo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm21_c_localnovo"]:$this->cm21_c_localnovo);
       $this->cm21_c_localant = ($this->cm21_c_localant == ""?@$GLOBALS["HTTP_POST_VARS"]["cm21_c_localant"]:$this->cm21_c_localant);
     }else{
       $this->cm21_i_codigo = ($this->cm21_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm21_i_codigo"]:$this->cm21_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm21_i_codigo){
      $this->atualizacampos();
     if($this->cm21_i_sepultamento == null ){
       $this->erro_sql = " Campo Sepultamento nao Informado.";
       $this->erro_campo = "cm21_i_sepultamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm21_i_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "cm21_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm21_d_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "cm21_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm21_c_localnovo == null ){
       $this->erro_sql = " Campo Local Novo nao Informado.";
       $this->erro_campo = "cm21_c_localnovo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm21_c_localant == null ){
       $this->erro_sql = " Campo Local Anterior nao Informado.";
       $this->erro_campo = "cm21_c_localant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm21_i_codigo == "" || $cm21_i_codigo == null ){
       $result = db_query("select nextval('sepulthist_cm21_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sepulthist_cm21_i_codigo_seq do campo: cm21_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm21_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from sepulthist_cm21_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm21_i_codigo)){
         $this->erro_sql = " Campo cm21_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm21_i_codigo = $cm21_i_codigo;
       }
     }
     if(($this->cm21_i_codigo == null) || ($this->cm21_i_codigo == "") ){
       $this->erro_sql = " Campo cm21_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sepulthist(
                                       cm21_i_codigo
                                      ,cm21_i_sepultamento
                                      ,cm21_i_usuario
                                      ,cm21_d_data
                                      ,cm21_c_localnovo
                                      ,cm21_c_localant
                       )
                values (
                                $this->cm21_i_codigo
                               ,$this->cm21_i_sepultamento
                               ,$this->cm21_i_usuario
                               ,".($this->cm21_d_data == "null" || $this->cm21_d_data == ""?"null":"'".$this->cm21_d_data."'")."
                               ,'$this->cm21_c_localnovo'
                               ,'$this->cm21_c_localant'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico do Sepultamento ($this->cm21_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico do Sepultamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico do Sepultamento ($this->cm21_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm21_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm21_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10435,'$this->cm21_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1804,10435,'','".AddSlashes(pg_result($resaco,0,'cm21_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1804,10436,'','".AddSlashes(pg_result($resaco,0,'cm21_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1804,10437,'','".AddSlashes(pg_result($resaco,0,'cm21_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1804,10438,'','".AddSlashes(pg_result($resaco,0,'cm21_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1804,10439,'','".AddSlashes(pg_result($resaco,0,'cm21_c_localnovo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1804,10440,'','".AddSlashes(pg_result($resaco,0,'cm21_c_localant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm21_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update sepulthist set ";
     $virgula = "";
     if(trim($this->cm21_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm21_i_codigo"])){
       $sql  .= $virgula." cm21_i_codigo = $this->cm21_i_codigo ";
       $virgula = ",";
       if(trim($this->cm21_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm21_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm21_i_sepultamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm21_i_sepultamento"])){
       $sql  .= $virgula." cm21_i_sepultamento = $this->cm21_i_sepultamento ";
       $virgula = ",";
       if(trim($this->cm21_i_sepultamento) == null ){
         $this->erro_sql = " Campo Sepultamento nao Informado.";
         $this->erro_campo = "cm21_i_sepultamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm21_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm21_i_usuario"])){
       $sql  .= $virgula." cm21_i_usuario = $this->cm21_i_usuario ";
       $virgula = ",";
       if(trim($this->cm21_i_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "cm21_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm21_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm21_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm21_d_data_dia"] !="") ){
       $sql  .= $virgula." cm21_d_data = '$this->cm21_d_data' ";
       $virgula = ",";
       if(trim($this->cm21_d_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "cm21_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm21_d_data_dia"])){
         $sql  .= $virgula." cm21_d_data = null ";
         $virgula = ",";
         if(trim($this->cm21_d_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "cm21_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm21_c_localnovo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm21_c_localnovo"])){
       $sql  .= $virgula." cm21_c_localnovo = '$this->cm21_c_localnovo' ";
       $virgula = ",";
       if(trim($this->cm21_c_localnovo) == null ){
         $this->erro_sql = " Campo Local Novo nao Informado.";
         $this->erro_campo = "cm21_c_localnovo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm21_c_localant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm21_c_localant"])){
       $sql  .= $virgula." cm21_c_localant = '$this->cm21_c_localant' ";
       $virgula = ",";
       if(trim($this->cm21_c_localant) == null ){
         $this->erro_sql = " Campo Local Anterior nao Informado.";
         $this->erro_campo = "cm21_c_localant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cm21_i_codigo!=null){
       $sql .= " cm21_i_codigo = $this->cm21_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm21_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10435,'$this->cm21_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm21_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1804,10435,'".AddSlashes(pg_result($resaco,$conresaco,'cm21_i_codigo'))."','$this->cm21_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm21_i_sepultamento"]))
           $resac = db_query("insert into db_acount values($acount,1804,10436,'".AddSlashes(pg_result($resaco,$conresaco,'cm21_i_sepultamento'))."','$this->cm21_i_sepultamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm21_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1804,10437,'".AddSlashes(pg_result($resaco,$conresaco,'cm21_i_usuario'))."','$this->cm21_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm21_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1804,10438,'".AddSlashes(pg_result($resaco,$conresaco,'cm21_d_data'))."','$this->cm21_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm21_c_localnovo"]))
           $resac = db_query("insert into db_acount values($acount,1804,10439,'".AddSlashes(pg_result($resaco,$conresaco,'cm21_c_localnovo'))."','$this->cm21_c_localnovo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm21_c_localant"]))
           $resac = db_query("insert into db_acount values($acount,1804,10440,'".AddSlashes(pg_result($resaco,$conresaco,'cm21_c_localant'))."','$this->cm21_c_localant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico do Sepultamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm21_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico do Sepultamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm21_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm21_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm21_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm21_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10435,'$cm21_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1804,10435,'','".AddSlashes(pg_result($resaco,$iresaco,'cm21_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1804,10436,'','".AddSlashes(pg_result($resaco,$iresaco,'cm21_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1804,10437,'','".AddSlashes(pg_result($resaco,$iresaco,'cm21_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1804,10438,'','".AddSlashes(pg_result($resaco,$iresaco,'cm21_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1804,10439,'','".AddSlashes(pg_result($resaco,$iresaco,'cm21_c_localnovo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1804,10440,'','".AddSlashes(pg_result($resaco,$iresaco,'cm21_c_localant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sepulthist
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm21_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm21_i_codigo = $cm21_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico do Sepultamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm21_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico do Sepultamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm21_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm21_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sepulthist";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm21_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sepulthist ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sepulthist.cm21_i_usuario";
     $sql .= "      inner join sepultamentos  on  sepultamentos.cm01_i_codigo = sepulthist.cm21_i_sepultamento";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = sepultamentos.cm01_i_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sepultamentos.cm01_i_funcionario";
     $sql .= "      left  join medicos  on  medicos. = sepultamentos.cm01_i_medico";
     $sql .= "      inner join causa  on  causa.cm04_i_codigo = sepultamentos.cm01_i_causa";
     $sql .= "      inner join cemiterio  on  cemiterio.cm14_i_codigo = sepultamentos.cm01_i_cemiterio";
     $sql .= "      left  join funerarias  on  funerarias.cm17_i_funeraria = sepultamentos.cm01_i_funeraria";
     $sql .= "      left  join hospitais  on  hospitais.cm18_i_hospital = sepultamentos.cm01_i_hospital";
     $sql2 = "";
     if($dbwhere==""){
       if($cm21_i_codigo!=null ){
         $sql2 .= " where sepulthist.cm21_i_codigo = $cm21_i_codigo ";
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
   function sql_query_file ( $cm21_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sepulthist ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm21_i_codigo!=null ){
         $sql2 .= " where sepulthist.cm21_i_codigo = $cm21_i_codigo ";
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
