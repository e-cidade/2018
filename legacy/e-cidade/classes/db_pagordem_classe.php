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

//MODULO: empenho
//CLASSE DA ENTIDADE pagordem
class cl_pagordem {
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
   var $e50_codord = 0;
   var $e50_numemp = 0;
   var $e50_data_dia = null;
   var $e50_data_mes = null;
   var $e50_data_ano = null;
   var $e50_data = null;
   var $e50_obs = null;
   var $e50_id_usuario = 0;
   var $e50_hora = null;
   var $e50_anousu = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e50_codord = int4 = Ordem
                 e50_numemp = int4 = Empenho
                 e50_data = date = Emissão
                 e50_obs = text = Observação
                 e50_id_usuario = int4 = Usuario
                 e50_hora = char(5) = Hora
                 e50_anousu = int4 = Ano da Ordem
                 ";
   //funcao construtor da classe
   function cl_pagordem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pagordem");
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
       $this->e50_codord = ($this->e50_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["e50_codord"]:$this->e50_codord);
       $this->e50_numemp = ($this->e50_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e50_numemp"]:$this->e50_numemp);
       if($this->e50_data == ""){
         $this->e50_data_dia = ($this->e50_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e50_data_dia"]:$this->e50_data_dia);
         $this->e50_data_mes = ($this->e50_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e50_data_mes"]:$this->e50_data_mes);
         $this->e50_data_ano = ($this->e50_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e50_data_ano"]:$this->e50_data_ano);
         if($this->e50_data_dia != ""){
            $this->e50_data = $this->e50_data_ano."-".$this->e50_data_mes."-".$this->e50_data_dia;
         }
       }
       $this->e50_obs = ($this->e50_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["e50_obs"]:$this->e50_obs);
       $this->e50_id_usuario = ($this->e50_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["e50_id_usuario"]:$this->e50_id_usuario);
       $this->e50_hora = ($this->e50_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["e50_hora"]:$this->e50_hora);
       $this->e50_anousu = ($this->e50_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["e50_anousu"]:$this->e50_anousu);
     }else{
       $this->e50_codord = ($this->e50_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["e50_codord"]:$this->e50_codord);
     }
   }
   // funcao para inclusao
   function incluir ($e50_codord){
      $this->atualizacampos();
     if($this->e50_numemp == null ){
       $this->erro_sql = " Campo Empenho nao Informado.";
       $this->erro_campo = "e50_numemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e50_data == null ){
       $this->erro_sql = " Campo Emissão nao Informado.";
       $this->erro_campo = "e50_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e50_id_usuario == null ){
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "e50_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e50_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "e50_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e50_anousu == null ){
       $this->erro_sql = " Campo Ano da Ordem nao Informado.";
       $this->erro_campo = "e50_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e50_codord == "" || $e50_codord == null ){
       $result = db_query("select nextval('pagordem_e50_codord_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pagordem_e50_codord_seq do campo: e50_codord";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e50_codord = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from pagordem_e50_codord_seq");
       if(($result != false) && (pg_result($result,0,0) < $e50_codord)){
         $this->erro_sql = " Campo e50_codord maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e50_codord = $e50_codord;
       }
     }
     if(($this->e50_codord == null) || ($this->e50_codord == "") ){
       $this->erro_sql = " Campo e50_codord nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pagordem(
                                       e50_codord
                                      ,e50_numemp
                                      ,e50_data
                                      ,e50_obs
                                      ,e50_id_usuario
                                      ,e50_hora
                                      ,e50_anousu
                       )
                values (
                                $this->e50_codord
                               ,$this->e50_numemp
                               ,".($this->e50_data == "null" || $this->e50_data == ""?"null":"'".$this->e50_data."'")."
                               ,'$this->e50_obs'
                               ,$this->e50_id_usuario
                               ,'$this->e50_hora'
                               ,$this->e50_anousu
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ordens de pagamento ($this->e50_codord) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ordens de pagamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ordens de pagamento ($this->e50_codord) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e50_codord;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e50_codord));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5437,'$this->e50_codord','I')");
       $resac = db_query("insert into db_acount values($acount,808,5437,'','".AddSlashes(pg_result($resaco,0,'e50_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,808,5438,'','".AddSlashes(pg_result($resaco,0,'e50_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,808,5439,'','".AddSlashes(pg_result($resaco,0,'e50_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,808,5441,'','".AddSlashes(pg_result($resaco,0,'e50_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,808,9190,'','".AddSlashes(pg_result($resaco,0,'e50_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,808,9191,'','".AddSlashes(pg_result($resaco,0,'e50_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,808,11134,'','".AddSlashes(pg_result($resaco,0,'e50_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($e50_codord=null) {
      $this->atualizacampos();
     $sql = " update pagordem set ";
     $virgula = "";
     if(trim($this->e50_codord)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e50_codord"])){
       $sql  .= $virgula." e50_codord = $this->e50_codord ";
       $virgula = ",";
       if(trim($this->e50_codord) == null ){
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "e50_codord";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e50_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e50_numemp"])){
       $sql  .= $virgula." e50_numemp = $this->e50_numemp ";
       $virgula = ",";
       if(trim($this->e50_numemp) == null ){
         $this->erro_sql = " Campo Empenho nao Informado.";
         $this->erro_campo = "e50_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e50_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e50_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e50_data_dia"] !="") ){
       $sql  .= $virgula." e50_data = '$this->e50_data' ";
       $virgula = ",";
       if(trim($this->e50_data) == null ){
         $this->erro_sql = " Campo Emissão nao Informado.";
         $this->erro_campo = "e50_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["e50_data_dia"])){
         $sql  .= $virgula." e50_data = null ";
         $virgula = ",";
         if(trim($this->e50_data) == null ){
           $this->erro_sql = " Campo Emissão nao Informado.";
           $this->erro_campo = "e50_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e50_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e50_obs"])){
       $sql  .= $virgula." e50_obs = '$this->e50_obs' ";
       $virgula = ",";
     }
     if(trim($this->e50_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e50_id_usuario"])){
       $sql  .= $virgula." e50_id_usuario = $this->e50_id_usuario ";
       $virgula = ",";
       if(trim($this->e50_id_usuario) == null ){
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "e50_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e50_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e50_hora"])){
       $sql  .= $virgula." e50_hora = '$this->e50_hora' ";
       $virgula = ",";
       if(trim($this->e50_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "e50_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e50_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e50_anousu"])){
       $sql  .= $virgula." e50_anousu = $this->e50_anousu ";
       $virgula = ",";
       if(trim($this->e50_anousu) == null ){
         $this->erro_sql = " Campo Ano da Ordem nao Informado.";
         $this->erro_campo = "e50_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e50_codord!=null){
       $sql .= " e50_codord = $this->e50_codord";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e50_codord));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5437,'$this->e50_codord','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e50_codord"]) || $this->e50_codord != "")
           $resac = db_query("insert into db_acount values($acount,808,5437,'".AddSlashes(pg_result($resaco,$conresaco,'e50_codord'))."','$this->e50_codord',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e50_numemp"]) || $this->e50_numemp != "")
           $resac = db_query("insert into db_acount values($acount,808,5438,'".AddSlashes(pg_result($resaco,$conresaco,'e50_numemp'))."','$this->e50_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e50_data"]) || $this->e50_data != "")
           $resac = db_query("insert into db_acount values($acount,808,5439,'".AddSlashes(pg_result($resaco,$conresaco,'e50_data'))."','$this->e50_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e50_obs"]) || $this->e50_obs != "")
           $resac = db_query("insert into db_acount values($acount,808,5441,'".AddSlashes(pg_result($resaco,$conresaco,'e50_obs'))."','$this->e50_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e50_id_usuario"]) || $this->e50_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,808,9190,'".AddSlashes(pg_result($resaco,$conresaco,'e50_id_usuario'))."','$this->e50_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e50_hora"]) || $this->e50_hora != "")
           $resac = db_query("insert into db_acount values($acount,808,9191,'".AddSlashes(pg_result($resaco,$conresaco,'e50_hora'))."','$this->e50_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e50_anousu"]) || $this->e50_anousu != "")
           $resac = db_query("insert into db_acount values($acount,808,11134,'".AddSlashes(pg_result($resaco,$conresaco,'e50_anousu'))."','$this->e50_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordens de pagamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e50_codord;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordens de pagamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e50_codord;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e50_codord;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($e50_codord=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e50_codord));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5437,'$e50_codord','E')");
         $resac = db_query("insert into db_acount values($acount,808,5437,'','".AddSlashes(pg_result($resaco,$iresaco,'e50_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,808,5438,'','".AddSlashes(pg_result($resaco,$iresaco,'e50_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,808,5439,'','".AddSlashes(pg_result($resaco,$iresaco,'e50_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,808,5441,'','".AddSlashes(pg_result($resaco,$iresaco,'e50_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,808,9190,'','".AddSlashes(pg_result($resaco,$iresaco,'e50_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,808,9191,'','".AddSlashes(pg_result($resaco,$iresaco,'e50_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,808,11134,'','".AddSlashes(pg_result($resaco,$iresaco,'e50_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pagordem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e50_codord != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e50_codord = $e50_codord ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordens de pagamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e50_codord;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordens de pagamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e50_codord;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e50_codord;
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
        $this->erro_sql   = "Record Vazio na Tabela:pagordem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $e50_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pagordem ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pagordem.e50_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql .= "      left  join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
     $sql2 = "";
     if($dbwhere==""){
       if($e50_codord!=null ){
         $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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
   function sql_query_file ( $e50_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pagordem ";
     $sql2 = "";
     if($dbwhere==""){
       if($e50_codord!=null ){
         $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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
   function sql_query_cheques( $e50_codord=null,$campos="*",$ordem=null,$dbwhere="", $sJoin = ""){
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
     $sql .= " from pagordem ";
     $sql .= "      inner join pagordemele   on  pagordemele.e53_codord = pagordem.e50_codord";
     $sql .= "      inner join empempenho    on  empempenho.e60_numemp  = pagordem.e50_numemp";
     $sql .= "      inner join cgm           on  cgm.z01_numcgm   = empempenho.e60_numcgm";
     $sql .= "      inner join db_config     on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao    on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join orctiporec    on  orctiporec.o15_codigo =  orcdotacao.o58_codigo    ";
     $sql .= "      left join emptipo       on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql .= "      left join empord        on  empord.e82_codord   = pagordem.e50_codord";
     $sql .= "      left join empagemov     on  empagemov.e81_codmov   = empord.e82_codmov";
     $sql .= "      left join empagemovforma on  e81_codmov   = e97_codmov";
     $sql .= "      left join empage				 on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      left join corempagemov   on corempagemov.k12_codmov = empagemov.e81_codmov";
     $sql .= "      left join empageconf     on  empageconf.e86_codmov  = empord.e82_codmov";
     $sql .= "      left join empageconfche  on  empageconf.e86_codmov  = e91_codmov and e91_ativo is true ";
     $sql .= "      left join pagordemconta  on e49_codord   = e82_codord ";
     $sql .= "      left join cgm a          on a.z01_numcgm = e49_numcgm ";
     $sql .= "      left join empageconfgera on empageconfgera.e90_codmov = empagemov.e81_codmov ";
     if ($sJoin != "") {
      $sql .= $sJoin;
     }
     $sql2 = "";
     if($dbwhere==""){
       if($e50_codord!=null ){
         $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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
   function sql_query_emp ( $e50_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pagordem ";
    $sql .= "      inner join empempenho on  empempenho.e60_numemp = pagordem.e50_numemp";
    $sql .= "      inner join cgm        on  cgm.z01_numcgm = empempenho.e60_numcgm";
		$sql .= "      left join pagordemele on  pagordemele.e53_codord   = pagordem.e50_codord ";
    $sql2 = "";
    if($dbwhere==""){
      if($e50_codord!=null ){
        $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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
   function sql_query_empagemovforma( $e50_codord=null,$campos="*",$ordem=null,$dbwhere="", $sJoin = ""){
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

     $sql .= " from empage ";
     $sql .= "      inner join empagemov       on empagemov.e81_codage      = empage.e80_codage      ";
     $sql .= "      inner join empord          on empord.e82_codmov         = empagemov.e81_codmov   ";
     $sql .= "      inner join pagordem        on pagordem.e50_codord       = empord.e82_codord      ";
     $sql .= "      inner join pagordemele     on pagordemele.e53_codord    = pagordem.e50_codord    ";
     $sql .= "      inner join empempenho      on empempenho.e60_numemp     = pagordem.e50_numemp    ";
     $sql .= "      inner join cgm             on cgm.z01_numcgm            = empempenho.e60_numcgm  ";
     $sql .= "      inner join db_config       on db_config.codigo          = empempenho.e60_instit  ";
     $sql .= "      inner join orcdotacao      on orcdotacao.o58_anousu     = empempenho.e60_anousu  ";
     $sql .= "                                and orcdotacao.o58_coddot     = empempenho.e60_coddot  ";
     $sql .= "      inner join orctiporec      on orctiporec.o15_codigo     = orcdotacao.o58_codigo  ";
     $sql .= "      inner join emptipo         on emptipo.e41_codtipo       = empempenho.e60_codtipo ";
     $sql .= "      left join empageconcarpeculiar on empageconcarpeculiar.e79_empagemov = empagemov.e81_codmov ";
     $sql .= "      left join corempagemov     on corempagemov.k12_codmov   = empagemov.e81_codmov";
     $sql .= "      left join empagemovconta   on  empagemov.e81_codmov     = e98_codmov";
     $sql .= "      left join empageconf       on  empageconf.e86_codmov    = empord.e82_codmov";
     $sql .= "      left join empageconfche    on  empageconf.e86_codmov    = e91_codmov and e91_ativo is true ";
     $sql .= "      left join pagordemconta    on e49_codord                = e82_codord ";
     $sql .= "      left join empagemovforma   on e97_codmov                = e81_codmov ";
     $sql .= "      left join empagepag        on e85_codmov                = e81_codmov ";
     $sql .= "      left join empagetipo       on e85_codtipo               = e83_codtipo ";
     $sql .= "      left join  pagordemnota    on e71_codord                = e50_codord ";
     $sql .= "      left join  empnota         on e69_codnota               = e71_codnota ";
     $sql .= "      left join cgm a            on a.z01_numcgm              = e49_numcgm ";
     $sql .= "      left join empageconfgera   on empageconfgera.e90_codmov = empagemov.e81_codmov ";
     $sql .= "                                and  empageconfgera.e90_cancelado is false ";
     $sql .= "      left join corgrupocorrente on k105_data                 = corempagemov.k12_data ";
     $sql .= "                                and k105_autent               = corempagemov.k12_autent ";
     $sql .= "                                and k105_id                   = corempagemov.k12_id ";
     if ($sJoin != "") {
      $sql .= $sJoin;
     }
     $sql2 = "";
     if($dbwhere==""){
       if($e50_codord!=null ){
         $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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
   function sql_query_impconsulta ( $e50_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pagordem ";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
    $sql .= "      inner join empempitem  on  empempitem.e62_numemp = empempenho.e60_numemp";
    $sql .= "      inner join pcmater     on  pcmater.pc01_codmater = empempitem.e62_item";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql2 = "";
    if($dbwhere==""){
      if($e50_codord!=null ){
        $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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
   function sql_query_notaliquidacao ( $e50_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pagordem ";
    $sql .= "      left  join db_usuarios  on  db_usuarios.id_usuario = pagordem.e50_id_usuario";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
    $sql .= "      inner join pagordemnota  on  e71_codord = e50_codord";
    $sql .= "      inner join empnota       on  e71_codnota = e69_codnota";
    $sql2 = "";
    if($dbwhere==""){
      if($e50_codord!=null ){
        $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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
   function sql_query_pag ( $e50_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pagordem ";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      left join conlancamord on c80_codord = pagordem.e50_codord ";
    $sql .= "      left join conlancampag on c82_codlan = conlancamord.c80_codlan";
    $sql .= "      left join conlancam   on c70_codlan   = conlancamord.c80_codlan ";
    $sql .= "      left join conlancamdoc  on c71_codlan   = conlancam.c70_codlan ";
    $sql .= "      left join conhistdoc on c53_coddoc  = conlancamdoc.c71_coddoc ";
    $sql .= "      left join conplanoreduz on c61_reduz  = conlancampag.c82_reduz and c82_anousu = c61_anousu";
    $sql .= "      left join conplano  on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=c61_anousu";
    $sql .= "      left  join pagordemconta on pagordemconta.e49_codord = pagordem.e50_codord";

    $sql2 = "";
    if($dbwhere==""){
      if($e50_codord!=null ){
        $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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
   function sql_query_pagordemagenda ( $e50_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pagordem ";
    $sql .= "      inner join pagordemele  on pagordemele.e53_codord   = pagordem.e50_codord";
    $sql .= "      inner join empempenho   on empempenho.e60_numemp    = pagordem.e50_numemp";
    $sql .= "      inner join cgm          on cgm.z01_numcgm           = empempenho.e60_numcgm";
    $sql .= "      inner join db_config    on db_config.codigo         = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao   on orcdotacao.o58_anousu    = empempenho.e60_anousu and";
    $sql .= "                                 orcdotacao.o58_coddot    = empempenho.e60_coddot";
    $sql .= "      inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql .= "      inner join emptipo      on emptipo.e41_codtipo      = empempenho.e60_codtipo";
    $sql .= "      left  join empord       on empord.e82_codord        = pagordem.e50_codord";
    $sql .= "      left  join empagemov    on empagemov.e81_codmov     = empord.e82_codmov";
    $sql .= "      left  join empage       on empage.e80_codage        = empagemov.e81_codage";
    $sql2 = "";
    if($dbwhere==""){
      if($e50_codord!=null ){
        $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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
   function sql_query_pagordemele ( $e50_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pagordem ";
    $sql .= "      inner join pagordemele  on  pagordemele.e53_codord = pagordem.e50_codord";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join orctiporec on  orctiporec.o15_codigo =  orcdotacao.o58_codigo    ";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    /*
    //     $sql .= "      left join (select e82_codord,
    //                                      sum(e81_valor) as e81_valor,
    //				                              max(e81_codmov) as e81_codmov,
    //				                              (select empagemov2.e81_cancelado from empagemov empagemov2 where empagemov2.e81_codmov = max(empagemov.e81_codmov))
    //			                         from empord
    //			                              inner join empagemov on e82_codmov = e81_codmov
    //			                         group by e82_codord
    //			                         ) as xxx on xxx.e82_codord = pagordemele.e53_codord ";
    //     $sql .= "      left  join empord        on empord.e82_codord         = pagordem.e50_codord ";
    //     $sql .= "      left join empageconf     on empageconf.e86_codmov     = xxx.e81_codmov ";
    //     $sql .= "      left join empageconfgera on empageconfgera.e90_codmov = xxx.e81_codmov ";
     */
    $sql2 = "";
    if($dbwhere==""){
      if($e50_codord!=null ){
        $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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
    //     echo $sql."<br><br>";
    return $sql;
  }
   function sql_query_pagordemele2 ( $e50_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pagordem ";
    $sql .= "      inner join pagordemele  on  pagordemele.e53_codord = pagordem.e50_codord";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
    $sql .= "      left  join empord on empord.e82_codord = pagordem.e50_codord ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join orctiporec on  orctiporec.o15_codigo =  orcdotacao.o58_codigo    ";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      left join (
      select e82_codord,
             sum(e81_valor) as e81_valor,
             max(e81_codmov) as e81_codmov,
             e81_codage,
             e81_cancelado
               from   empord
               inner join empagemov on e82_codmov = e81_codmov
               group by
               e82_codord,
             e81_codage,
             e81_cancelado
               ) as xxx on xxx.e82_codord = pagordemele.e53_codord";
    $sql .= "      left join empageconf on empageconf.e86_codmov = xxx.e81_codmov ";
    $sql .= "      left join empageconfgera on empageconfgera.e90_codmov = xxx.e81_codmov";
    $sql2 = "";
    if($dbwhere==""){
      if($e50_codord!=null ){
        $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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
   function sql_query_pagordemeleempage ( $e50_codord=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from pagordem ";
    $sql .= "      inner join pagordemele  on  pagordemele.e53_codord = pagordem.e50_codord";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join orctiporec on  orctiporec.o15_codigo =  orcdotacao.o58_codigo    ";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      left join (select e82_codord as codord, sum(e81_valor) as e81_valor from empord inner join empagemov on e82_codmov = e81_codmov where e81_cancelado is null group by e82_codord) as xxx on xxx.codord = pagordemele.e53_codord";
    $sql .= "      inner join empord  on  empord.e82_codord = pagordem.e50_codord";
    $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empord.e82_codmov";
    $sql .= "      left join empageconf  on  empageconf.e86_codmov = empord.e82_codmov";
    $sql .= "      left join pagordemconta on e49_codord = e82_codord ";
    $sql .= "      left join cgm a on a.z01_numcgm = e49_numcgm ";
    $sql .= "      left join empageconfgera on empageconfgera.e90_codmov = empagemov.e81_codmov ";

    $sql2 = "";
    if($dbwhere==""){
      if($e50_codord!=null ){
        $sql2 .= " where pagordem.e50_codord = $e50_codord ";
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

  function sql_query_pagDiversos( $e50_codord=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from pagordem ";
    $sql .= "      inner join empempenho     on empempenho.e60_numemp     = pagordem.e50_numemp              ";
    $sql .= "      inner join cgm            on cgm.z01_numcgm 			      = empempenho.e60_numcgm            ";
    $sql .= "      inner join db_config      on db_config.codigo          = empempenho.e60_instit            ";
    $sql .= "      inner join orcdotacao     on orcdotacao.o58_anousu     = empempenho.e60_anousu            ";
    $sql .= "                               and orcdotacao.o58_coddot     = empempenho.e60_coddot            ";
    $sql .= "      inner join pctipocompra   on pctipocompra.pc50_codcom  = empempenho.e60_codcom            ";
    $sql .= "      inner join emptipo        on emptipo.e41_codtipo 		  = empempenho.e60_codtipo           ";
    $sql .= "      inner join conlancamord   on c80_codord                = pagordem.e50_codord              ";
    $sql .= "      inner join conlancampag   on c82_codlan                = conlancamord.c80_codlan          ";
    $sql .= "      inner join conlancam      on c70_codlan                = conlancamord.c80_codlan          ";
    $sql .= "      inner join conlancamdoc   on c71_codlan                = conlancam.c70_codlan             ";
    $sql .= "      inner join conhistdoc     on c53_coddoc                = conlancamdoc.c71_coddoc          ";
    $sql .= "      inner join conplanoreduz  on c61_reduz                 = conlancampag.c82_reduz           ";
    $sql .= "       											  and c82_anousu                = c61_anousu							         ";
    $sql .= "      inner join conplano       on c60_codcon                = conplanoreduz.c61_codcon         ";
    $sql .= "      												  and c60_anousu                = c61_anousu                       ";
    $sql .= "      left  join pagordemconta  on pagordemconta.e49_codord  = pagordem.e50_codord              ";
    $sql .= "      inner join pagordemele    on pagordemele.e53_codord    = pagordem.e50_codord              ";

    $sql2 = "";
    if($dbwhere=="") {
      if($e50_codord!=null ) {
        $sql2 .= " where pagordem.e50_codord = $e50_codord ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {

      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }


  function sql_query_movimento($e50_codord = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from pagordem ";
    $sql .= "      inner join  empord    on empord.e82_codord    = pagordem.e50_codord  ";
    $sql .= "      inner join  empagemov on empagemov.e81_codmov = empord.e82_codmov";

    $sql2 = "";
    if($dbwhere=="") {
      if($e50_codord!=null ) {
        $sql2 .= " where pagordem.e50_codord = $e50_codord ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {

      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

}
?>