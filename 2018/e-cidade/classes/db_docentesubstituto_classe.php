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

//MODULO: escola
//CLASSE DA ENTIDADE docentesubstituto
class cl_docentesubstituto {
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
   var $ed322_sequencial = 0;
   var $ed322_docenteausente = 0;
   var $ed322_rechumano = 0;
   var $ed322_regencia = 0;
   var $ed322_tipovinculo = 0;
   var $ed322_periodoinicial_dia = null;
   var $ed322_periodoinicial_mes = null;
   var $ed322_periodoinicial_ano = null;
   var $ed322_periodoinicial = null;
   var $ed322_periodofinal_dia = null;
   var $ed322_periodofinal_mes = null;
   var $ed322_periodofinal_ano = null;
   var $ed322_periodofinal = null;
   var $ed322_usuario = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed322_sequencial = int4 = Código
                 ed322_docenteausente = int4 = Docente Ausente
                 ed322_rechumano = int4 = Substituto
                 ed322_regencia = int4 = Regencia
                 ed322_tipovinculo = int4 = Tipo de Vínculo
                 ed322_periodoinicial = date = Período Inicial
                 ed322_periodofinal = date = Período Final
                 ed322_usuario = int4 = Usuário
                 ";
   //funcao construtor da classe
   function cl_docentesubstituto() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("docentesubstituto");
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
       $this->ed322_sequencial = ($this->ed322_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_sequencial"]:$this->ed322_sequencial);
       $this->ed322_docenteausente = ($this->ed322_docenteausente == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_docenteausente"]:$this->ed322_docenteausente);
       $this->ed322_rechumano = ($this->ed322_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_rechumano"]:$this->ed322_rechumano);
       $this->ed322_regencia = ($this->ed322_regencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_regencia"]:$this->ed322_regencia);
       $this->ed322_tipovinculo = ($this->ed322_tipovinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_tipovinculo"]:$this->ed322_tipovinculo);
       if($this->ed322_periodoinicial == ""){
         $this->ed322_periodoinicial_dia = ($this->ed322_periodoinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_periodoinicial_dia"]:$this->ed322_periodoinicial_dia);
         $this->ed322_periodoinicial_mes = ($this->ed322_periodoinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_periodoinicial_mes"]:$this->ed322_periodoinicial_mes);
         $this->ed322_periodoinicial_ano = ($this->ed322_periodoinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_periodoinicial_ano"]:$this->ed322_periodoinicial_ano);
         if($this->ed322_periodoinicial_dia != ""){
            $this->ed322_periodoinicial = $this->ed322_periodoinicial_ano."-".$this->ed322_periodoinicial_mes."-".$this->ed322_periodoinicial_dia;
         }
       }
       if($this->ed322_periodofinal == ""){
         $this->ed322_periodofinal_dia = ($this->ed322_periodofinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_periodofinal_dia"]:$this->ed322_periodofinal_dia);
         $this->ed322_periodofinal_mes = ($this->ed322_periodofinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_periodofinal_mes"]:$this->ed322_periodofinal_mes);
         $this->ed322_periodofinal_ano = ($this->ed322_periodofinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_periodofinal_ano"]:$this->ed322_periodofinal_ano);
         if($this->ed322_periodofinal_dia != ""){
            $this->ed322_periodofinal = $this->ed322_periodofinal_ano."-".$this->ed322_periodofinal_mes."-".$this->ed322_periodofinal_dia;
         }
       }
       $this->ed322_usuario = ($this->ed322_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_usuario"]:$this->ed322_usuario);
     }else{
       $this->ed322_sequencial = ($this->ed322_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed322_sequencial"]:$this->ed322_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed322_sequencial){
      $this->atualizacampos();
     if($this->ed322_docenteausente == null ){
       $this->erro_sql = " Campo Docente Ausente nao Informado.";
       $this->erro_campo = "ed322_docenteausente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed322_rechumano == null ){
       $this->erro_sql = " Campo Substituto nao Informado.";
       $this->erro_campo = "ed322_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed322_regencia == null ){
       $this->erro_sql = " Campo Regencia nao Informado.";
       $this->erro_campo = "ed322_regencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed322_tipovinculo == null ){
       $this->erro_sql = " Campo Tipo de Vínculo nao Informado.";
       $this->erro_campo = "ed322_tipovinculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed322_periodoinicial == null ){
       $this->erro_sql = " Campo Período Inicial nao Informado.";
       $this->erro_campo = "ed322_periodoinicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed322_periodofinal == null ){
       $this->ed322_periodofinal = "null";
     }
     if($this->ed322_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed322_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed322_sequencial == "" || $ed322_sequencial == null ){
       $result = db_query("select nextval('docentesubstituto_ed322_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: docentesubstituto_ed322_sequencial_seq do campo: ed322_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed322_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from docentesubstituto_ed322_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed322_sequencial)){
         $this->erro_sql = " Campo ed322_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed322_sequencial = $ed322_sequencial;
       }
     }
     if(($this->ed322_sequencial == null) || ($this->ed322_sequencial == "") ){
       $this->erro_sql = " Campo ed322_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into docentesubstituto(
                                       ed322_sequencial
                                      ,ed322_docenteausente
                                      ,ed322_rechumano
                                      ,ed322_regencia
                                      ,ed322_tipovinculo
                                      ,ed322_periodoinicial
                                      ,ed322_periodofinal
                                      ,ed322_usuario
                       )
                values (
                                $this->ed322_sequencial
                               ,$this->ed322_docenteausente
                               ,$this->ed322_rechumano
                               ,$this->ed322_regencia
                               ,$this->ed322_tipovinculo
                               ,".($this->ed322_periodoinicial == "null" || $this->ed322_periodoinicial == ""?"null":"'".$this->ed322_periodoinicial."'")."
                               ,".($this->ed322_periodofinal == "null" || $this->ed322_periodofinal == ""?"null":"'".$this->ed322_periodofinal."'")."
                               ,$this->ed322_usuario
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Substitudo de um docente ausente ($this->ed322_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Substitudo de um docente ausente já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Substitudo de um docente ausente ($this->ed322_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed322_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed322_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19759,'$this->ed322_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3542,19759,'','".AddSlashes(pg_result($resaco,0,'ed322_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3542,19760,'','".AddSlashes(pg_result($resaco,0,'ed322_docenteausente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3542,19762,'','".AddSlashes(pg_result($resaco,0,'ed322_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3542,19761,'','".AddSlashes(pg_result($resaco,0,'ed322_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3542,19763,'','".AddSlashes(pg_result($resaco,0,'ed322_tipovinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3542,19764,'','".AddSlashes(pg_result($resaco,0,'ed322_periodoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3542,19765,'','".AddSlashes(pg_result($resaco,0,'ed322_periodofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3542,19766,'','".AddSlashes(pg_result($resaco,0,'ed322_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed322_sequencial=null) {
      $this->atualizacampos();
     $sql = " update docentesubstituto set ";
     $virgula = "";
     if(trim($this->ed322_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed322_sequencial"])){
       $sql  .= $virgula." ed322_sequencial = $this->ed322_sequencial ";
       $virgula = ",";
       if(trim($this->ed322_sequencial) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed322_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed322_docenteausente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed322_docenteausente"])){
       $sql  .= $virgula." ed322_docenteausente = $this->ed322_docenteausente ";
       $virgula = ",";
       if(trim($this->ed322_docenteausente) == null ){
         $this->erro_sql = " Campo Docente Ausente nao Informado.";
         $this->erro_campo = "ed322_docenteausente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed322_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed322_rechumano"])){
       $sql  .= $virgula." ed322_rechumano = $this->ed322_rechumano ";
       $virgula = ",";
       if(trim($this->ed322_rechumano) == null ){
         $this->erro_sql = " Campo Substituto nao Informado.";
         $this->erro_campo = "ed322_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed322_regencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed322_regencia"])){
       $sql  .= $virgula." ed322_regencia = $this->ed322_regencia ";
       $virgula = ",";
       if(trim($this->ed322_regencia) == null ){
         $this->erro_sql = " Campo Regencia nao Informado.";
         $this->erro_campo = "ed322_regencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed322_tipovinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed322_tipovinculo"])){
       $sql  .= $virgula." ed322_tipovinculo = $this->ed322_tipovinculo ";
       $virgula = ",";
       if(trim($this->ed322_tipovinculo) == null ){
         $this->erro_sql = " Campo Tipo de Vínculo nao Informado.";
         $this->erro_campo = "ed322_tipovinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed322_periodoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed322_periodoinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed322_periodoinicial_dia"] !="") ){
       $sql  .= $virgula." ed322_periodoinicial = '$this->ed322_periodoinicial' ";
       $virgula = ",";
       if(trim($this->ed322_periodoinicial) == null ){
         $this->erro_sql = " Campo Período Inicial nao Informado.";
         $this->erro_campo = "ed322_periodoinicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed322_periodoinicial_dia"])){
         $sql  .= $virgula." ed322_periodoinicial = null ";
         $virgula = ",";
         if(trim($this->ed322_periodoinicial) == null ){
           $this->erro_sql = " Campo Período Inicial nao Informado.";
           $this->erro_campo = "ed322_periodoinicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if (empty($this->ed322_periodofinal)) {
	     $sql  .= $virgula." ed322_periodofinal = null ";
     }
     if(trim($this->ed322_periodofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed322_periodofinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed322_periodofinal_dia"] !="") ){
       $sql  .= $virgula." ed322_periodofinal = '$this->ed322_periodofinal' ";
       $virgula = ",";
     }

     if(trim($this->ed322_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed322_usuario"])){
       $sql  .= $virgula." ed322_usuario = $this->ed322_usuario ";
       $virgula = ",";
       if(trim($this->ed322_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed322_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed322_sequencial!=null){
       $sql .= " ed322_sequencial = $this->ed322_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed322_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19759,'$this->ed322_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed322_sequencial"]) || $this->ed322_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3542,19759,'".AddSlashes(pg_result($resaco,$conresaco,'ed322_sequencial'))."','$this->ed322_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed322_docenteausente"]) || $this->ed322_docenteausente != "")
           $resac = db_query("insert into db_acount values($acount,3542,19760,'".AddSlashes(pg_result($resaco,$conresaco,'ed322_docenteausente'))."','$this->ed322_docenteausente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed322_rechumano"]) || $this->ed322_rechumano != "")
           $resac = db_query("insert into db_acount values($acount,3542,19762,'".AddSlashes(pg_result($resaco,$conresaco,'ed322_rechumano'))."','$this->ed322_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed322_regencia"]) || $this->ed322_regencia != "")
           $resac = db_query("insert into db_acount values($acount,3542,19761,'".AddSlashes(pg_result($resaco,$conresaco,'ed322_regencia'))."','$this->ed322_regencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed322_tipovinculo"]) || $this->ed322_tipovinculo != "")
           $resac = db_query("insert into db_acount values($acount,3542,19763,'".AddSlashes(pg_result($resaco,$conresaco,'ed322_tipovinculo'))."','$this->ed322_tipovinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed322_periodoinicial"]) || $this->ed322_periodoinicial != "")
           $resac = db_query("insert into db_acount values($acount,3542,19764,'".AddSlashes(pg_result($resaco,$conresaco,'ed322_periodoinicial'))."','$this->ed322_periodoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed322_periodofinal"]) || $this->ed322_periodofinal != "")
           $resac = db_query("insert into db_acount values($acount,3542,19765,'".AddSlashes(pg_result($resaco,$conresaco,'ed322_periodofinal'))."','$this->ed322_periodofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed322_usuario"]) || $this->ed322_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3542,19766,'".AddSlashes(pg_result($resaco,$conresaco,'ed322_usuario'))."','$this->ed322_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Substitudo de um docente ausente nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed322_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Substitudo de um docente ausente nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed322_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed322_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed322_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed322_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19759,'$ed322_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3542,19759,'','".AddSlashes(pg_result($resaco,$iresaco,'ed322_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3542,19760,'','".AddSlashes(pg_result($resaco,$iresaco,'ed322_docenteausente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3542,19762,'','".AddSlashes(pg_result($resaco,$iresaco,'ed322_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3542,19761,'','".AddSlashes(pg_result($resaco,$iresaco,'ed322_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3542,19763,'','".AddSlashes(pg_result($resaco,$iresaco,'ed322_tipovinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3542,19764,'','".AddSlashes(pg_result($resaco,$iresaco,'ed322_periodoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3542,19765,'','".AddSlashes(pg_result($resaco,$iresaco,'ed322_periodofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3542,19766,'','".AddSlashes(pg_result($resaco,$iresaco,'ed322_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from docentesubstituto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed322_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed322_sequencial = $ed322_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Substitudo de um docente ausente nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed322_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Substitudo de um docente ausente nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed322_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed322_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:docentesubstituto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed322_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from docentesubstituto ";
     $sql .= "      inner join db_usuarios as db_substituto on db_substituto.id_usuario         = docentesubstituto.ed322_usuario";
     $sql .= "      inner join docenteausencia              on docenteausencia.ed321_sequencial = docentesubstituto.ed322_docenteausente";
     $sql .= "      inner join tipovinculo                  on tipovinculo.ed324_sequencial     = docentesubstituto.ed322_tipovinculo";
     $sql .= "      inner join regencia                     on regencia.ed59_i_codigo           = docentesubstituto.ed322_regencia";
     $sql .= "      inner join rechumano                    on rechumano.ed20_i_codigo          = docentesubstituto.ed322_rechumano";
     $sql .= "      inner join db_usuarios as db_ausencia   on db_ausencia.id_usuario           = docenteausencia.ed321_usuario";
     $sql .= "      inner join tipoausencia                 on tipoausencia.ed320_sequencial    = docenteausencia.ed321_tipoausencia";
     $sql .= "      inner join rechumano  as a              on a.ed20_i_codigo                  = docenteausencia.ed321_rechumano";
     $sql .= "      inner join disciplina                   on disciplina.ed12_i_codigo         = regencia.ed59_i_disciplina";
     $sql .= "      inner join serie                        on serie.ed11_i_codigo              = regencia.ed59_i_serie";
     $sql .= "      inner join turma                        on turma.ed57_i_codigo              = regencia.ed59_i_turma";
     $sql .= "      left  join rhregime                     on rhregime.rh30_codreg             = rechumano.ed20_i_rhregime";
     $sql .= "      inner join pais                         on pais.ed228_i_codigo              = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf                      on censouf.ed260_i_codigo           = rechumano.ed20_i_censoufcert";
     $sql .= "                                             and censouf.ed260_i_codigo           = rechumano.ed20_i_censoufender ";
     $sql .= "                                             and censouf.ed260_i_codigo           = rechumano.ed20_i_censoufnat ";
     $sql .= "                                             and censouf.ed260_i_codigo           = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censomunic                   on censomunic.ed261_i_codigo        = rechumano.ed20_i_censomunicnat";
     $sql .= "                                             and censomunic.ed261_i_codigo        = rechumano.ed20_i_censomunicender";
     $sql .= "      left  join censoorgemissrg              on censoorgemissrg.ed132_i_codigo   = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join censocartorio                on censocartorio.ed291_i_codigo     = rechumano.ed20_i_censocartorio";
     $sql .= "      left  join rechumano  as b              on b.ed20_i_codigo                  = rechumano.ed20_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed322_sequencial!=null ){
         $sql2 .= " where docentesubstituto.ed322_sequencial = $ed322_sequencial ";
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
   function sql_query_file ( $ed322_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from docentesubstituto ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed322_sequencial!=null ){
         $sql2 .= " where docentesubstituto.ed322_sequencial = $ed322_sequencial ";
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

  function sql_query_docente_cgm ($ed322_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

  	$sql = "select ";
  	if($campos != "*" ) {

  		$campos_sql = split("#",$campos);
  		$virgula = "";
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {

  			$sql .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}
  	}else{
  		$sql .= $campos;
  	}
  	$sql .= " from docentesubstituto ";
  	/**
     * CGM da rhpessoal
     */
    $sql .= "   left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = docentesubstituto.ed322_rechumano \n";
    $sql .= "   left join rhpessoal    on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal       \n";
    $sql .= "   left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm                         \n";
    /**
     * CGM do RH
     */
    $sql .= "   left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = docentesubstituto.ed322_rechumano \n";
    $sql .= "   left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm                    \n";
  	$sql2 = "";
  	if($dbwhere == "") {

  		if($ed322_sequencial != null) {
  			$sql2 .= " where docentesubstituto.ed322_sequencial = $ed322_sequencial ";
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


  /**
   * Busca as informações do docente que foi subistituido através do docente que o subistituiu
   */
  public function sql_query_dadosdocenteausente( $ed322_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql  = " select {$campos} ";
    $sql .= "  from docentesubstituto    ";
    $sql .= " inner join rechumano        as rechumanosubistituto         on rechumanosubistituto.ed20_i_codigo            = docentesubstituto.ed322_rechumano";
    $sql .= " inner join rechumanoescola  as rechumanoescolasubistituto   on rechumanoescolasubistituto.ed75_i_rechumano   = rechumanosubistituto.ed20_i_codigo";
    $sql .= "  left join rechumanopessoal as rechumanopessoalsubistituto  on rechumanopessoalsubistituto.ed284_i_rechumano = rechumanosubistituto.ed20_i_codigo";
    $sql .= "  left join rhpessoal        as rhpessoalsubistituto         on rhpessoalsubistituto.rh01_regist              = rechumanopessoalsubistituto.ed284_i_rhpessoal";
    $sql .= "  left join cgm              as cgmrhsubistituto             on cgmrhsubistituto.z01_numcgm                   = rhpessoalsubistituto.rh01_numcgm";
    $sql .= "  left join rechumanocgm     as rechumanocgmsubistituto      on rechumanocgmsubistituto.ed285_i_rechumano     = rechumanosubistituto.ed20_i_codigo";
    $sql .= "  left join cgm              as cgmcgmsubistituto            on cgmcgmsubistituto.z01_numcgm                  = rechumanocgmsubistituto.ed285_i_cgm";
    $sql .= " inner join regencia                                         on regencia.ed59_i_codigo                        = docentesubstituto.ed322_regencia";
    $sql .= " inner join turma                                            on turma.ed57_i_codigo                           = regencia.ed59_i_turma";
    $sql .= " inner join calendario                                       on calendario.ed52_i_codigo                      = turma.ed57_i_calendario";

    $sql .= " inner join docenteausencia                              on docenteausencia.ed321_sequencial          = docentesubstituto.ed322_docenteausente";
    $sql .= " inner join rechumano        as rechumanoausente         on rechumanoausente.ed20_i_codigo            = docenteausencia.ed321_rechumano";
    $sql .= " inner join rechumanoescola  as rechumanoescolaausente   on rechumanoescolaausente.ed75_i_rechumano   = rechumanoausente.ed20_i_codigo      ";
    $sql .= "  left join rechumanopessoal as rechumanopessoalausente  on rechumanopessoalausente.ed284_i_rechumano = rechumanoausente.ed20_i_codigo";
    $sql .= "  left join rhpessoal        as rhpessoalausente         on rhpessoalausente.rh01_regist              = rechumanopessoalausente.ed284_i_rhpessoal";
    $sql .= "  left join cgm              as cgmrhausente             on cgmrhausente.z01_numcgm                   = rhpessoalausente.rh01_numcgm";
    $sql .= "  left join rechumanocgm     as rechumanocgmausente      on rechumanocgmausente.ed285_i_rechumano     = rechumanoausente.ed20_i_codigo";
    $sql .= "  left join cgm              as cgmcgmausente            on cgmcgmausente.z01_numcgm                  = rechumanocgmausente.ed285_i_cgm";

    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ed322_sequencial)){
        $sql2 .= " where docenteausencia.ed322_sequencial = $ed322_sequencial ";
      }
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }


}
?>