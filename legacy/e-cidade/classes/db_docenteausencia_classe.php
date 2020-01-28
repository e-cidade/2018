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
//CLASSE DA ENTIDADE docenteausencia
class cl_docenteausencia {
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
   var $ed321_sequencial = 0;
   var $ed321_rechumano = 0;
   var $ed321_tipoausencia = 0;
   var $ed321_usuario = 0;
   var $ed321_inicio_dia = null;
   var $ed321_inicio_mes = null;
   var $ed321_inicio_ano = null;
   var $ed321_inicio = null;
   var $ed321_final_dia = null;
   var $ed321_final_mes = null;
   var $ed321_final_ano = null;
   var $ed321_final = null;
   var $ed321_observacao = null;
   var $ed321_escola = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed321_sequencial = int4 = Código
                 ed321_rechumano = int4 = Regente
                 ed321_tipoausencia = int4 = Tipo de Ausência
                 ed321_usuario = int4 = Usuário
                 ed321_inicio = date = Data de Inicio
                 ed321_final = date = Data Final
                 ed321_observacao = text = Observação
                 ed321_escola = int4 = Escola
                 ";
   //funcao construtor da classe
   function cl_docenteausencia() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("docenteausencia");
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
       $this->ed321_sequencial = ($this->ed321_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_sequencial"]:$this->ed321_sequencial);
       $this->ed321_rechumano = ($this->ed321_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_rechumano"]:$this->ed321_rechumano);
       $this->ed321_tipoausencia = ($this->ed321_tipoausencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_tipoausencia"]:$this->ed321_tipoausencia);
       $this->ed321_usuario = ($this->ed321_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_usuario"]:$this->ed321_usuario);
       if($this->ed321_inicio == ""){
         $this->ed321_inicio_dia = ($this->ed321_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_inicio_dia"]:$this->ed321_inicio_dia);
         $this->ed321_inicio_mes = ($this->ed321_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_inicio_mes"]:$this->ed321_inicio_mes);
         $this->ed321_inicio_ano = ($this->ed321_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_inicio_ano"]:$this->ed321_inicio_ano);
         if($this->ed321_inicio_dia != ""){
            $this->ed321_inicio = $this->ed321_inicio_ano."-".$this->ed321_inicio_mes."-".$this->ed321_inicio_dia;
         }
       }
       if($this->ed321_final == ""){
         $this->ed321_final_dia = ($this->ed321_final_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_final_dia"]:$this->ed321_final_dia);
         $this->ed321_final_mes = ($this->ed321_final_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_final_mes"]:$this->ed321_final_mes);
         $this->ed321_final_ano = ($this->ed321_final_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_final_ano"]:$this->ed321_final_ano);
         if($this->ed321_final_dia != ""){
            $this->ed321_final = $this->ed321_final_ano."-".$this->ed321_final_mes."-".$this->ed321_final_dia;
         }
       }
       $this->ed321_observacao = ($this->ed321_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_observacao"]:$this->ed321_observacao);
       $this->ed321_escola = ($this->ed321_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_escola"]:$this->ed321_escola);
     }else{
       $this->ed321_sequencial = ($this->ed321_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed321_sequencial"]:$this->ed321_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed321_sequencial){
      $this->atualizacampos();
     if($this->ed321_rechumano == null ){
       $this->erro_sql = " Campo Regente nao Informado.";
       $this->erro_campo = "ed321_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed321_tipoausencia == null ){
       $this->erro_sql = " Campo Tipo de Ausência nao Informado.";
       $this->erro_campo = "ed321_tipoausencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed321_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed321_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed321_inicio == null ){
       $this->erro_sql = " Campo Data de Inicio nao Informado.";
       $this->erro_campo = "ed321_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed321_final == null ){
       $this->ed321_final = "null";
     }
     if($this->ed321_escola == null ){
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed321_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed321_sequencial == "" || $ed321_sequencial == null ){
       $result = db_query("select nextval('docenteausencia_ed321_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: docenteausencia_ed321_sequencial_seq do campo: ed321_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed321_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from docenteausencia_ed321_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed321_sequencial)){
         $this->erro_sql = " Campo ed321_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed321_sequencial = $ed321_sequencial;
       }
     }
     if(($this->ed321_sequencial == null) || ($this->ed321_sequencial == "") ){
       $this->erro_sql = " Campo ed321_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into docenteausencia(
                                       ed321_sequencial
                                      ,ed321_rechumano
                                      ,ed321_tipoausencia
                                      ,ed321_usuario
                                      ,ed321_inicio
                                      ,ed321_final
                                      ,ed321_observacao
                                      ,ed321_escola
                       )
                values (
                                $this->ed321_sequencial
                               ,$this->ed321_rechumano
                               ,$this->ed321_tipoausencia
                               ,$this->ed321_usuario
                               ,".($this->ed321_inicio == "null" || $this->ed321_inicio == ""?"null":"'".$this->ed321_inicio."'")."
                               ,".($this->ed321_final == "null" || $this->ed321_final == ""?"null":"'".$this->ed321_final."'")."
                               ,'$this->ed321_observacao'
                               ,$this->ed321_escola
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Docente Ausente ($this->ed321_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Docente Ausente já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Docente Ausente ($this->ed321_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed321_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed321_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19752,'$this->ed321_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3541,19752,'','".AddSlashes(pg_result($resaco,0,'ed321_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3541,19753,'','".AddSlashes(pg_result($resaco,0,'ed321_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3541,19754,'','".AddSlashes(pg_result($resaco,0,'ed321_tipoausencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3541,19758,'','".AddSlashes(pg_result($resaco,0,'ed321_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3541,19755,'','".AddSlashes(pg_result($resaco,0,'ed321_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3541,19756,'','".AddSlashes(pg_result($resaco,0,'ed321_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3541,19757,'','".AddSlashes(pg_result($resaco,0,'ed321_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3541,19777,'','".AddSlashes(pg_result($resaco,0,'ed321_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed321_sequencial=null) {
      $this->atualizacampos();
     $sql = " update docenteausencia set ";
     $virgula = "";
     if(trim($this->ed321_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed321_sequencial"])){
       $sql  .= $virgula." ed321_sequencial = $this->ed321_sequencial ";
       $virgula = ",";
       if(trim($this->ed321_sequencial) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed321_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed321_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed321_rechumano"])){
       $sql  .= $virgula." ed321_rechumano = $this->ed321_rechumano ";
       $virgula = ",";
       if(trim($this->ed321_rechumano) == null ){
         $this->erro_sql = " Campo Regente nao Informado.";
         $this->erro_campo = "ed321_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed321_tipoausencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed321_tipoausencia"])){
       $sql  .= $virgula." ed321_tipoausencia = $this->ed321_tipoausencia ";
       $virgula = ",";
       if(trim($this->ed321_tipoausencia) == null ){
         $this->erro_sql = " Campo Tipo de Ausência nao Informado.";
         $this->erro_campo = "ed321_tipoausencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed321_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed321_usuario"])){
       $sql  .= $virgula." ed321_usuario = $this->ed321_usuario ";
       $virgula = ",";
       if(trim($this->ed321_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed321_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed321_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed321_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed321_inicio_dia"] !="") ){
       $sql  .= $virgula." ed321_inicio = '$this->ed321_inicio' ";
       $virgula = ",";
       if(trim($this->ed321_inicio) == null ){
         $this->erro_sql = " Campo Data de Inicio nao Informado.";
         $this->erro_campo = "ed321_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed321_inicio_dia"])){
         $sql  .= $virgula." ed321_inicio = null ";
         $virgula = ",";
         if(trim($this->ed321_inicio) == null ){
           $this->erro_sql = " Campo Data de Inicio nao Informado.";
           $this->erro_campo = "ed321_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if ( trim($this->ed321_final) != "" ) {

       $sql  .= $virgula." ed321_final = '$this->ed321_final' ";
       $virgula = ",";
     } else {

        $sql     .= $virgula." ed321_final = null ";
        $virgula  = ",";
     }
     if(trim($this->ed321_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed321_observacao"])){
       $sql  .= $virgula." ed321_observacao = '$this->ed321_observacao' ";
       $virgula = ",";
     }
     if(trim($this->ed321_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed321_escola"])){
       $sql  .= $virgula." ed321_escola = $this->ed321_escola ";
       $virgula = ",";
       if(trim($this->ed321_escola) == null ){
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed321_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed321_sequencial!=null){
       $sql .= " ed321_sequencial = $this->ed321_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed321_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19752,'$this->ed321_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed321_sequencial"]) || $this->ed321_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3541,19752,'".AddSlashes(pg_result($resaco,$conresaco,'ed321_sequencial'))."','$this->ed321_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed321_rechumano"]) || $this->ed321_rechumano != "")
           $resac = db_query("insert into db_acount values($acount,3541,19753,'".AddSlashes(pg_result($resaco,$conresaco,'ed321_rechumano'))."','$this->ed321_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed321_tipoausencia"]) || $this->ed321_tipoausencia != "")
           $resac = db_query("insert into db_acount values($acount,3541,19754,'".AddSlashes(pg_result($resaco,$conresaco,'ed321_tipoausencia'))."','$this->ed321_tipoausencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed321_usuario"]) || $this->ed321_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3541,19758,'".AddSlashes(pg_result($resaco,$conresaco,'ed321_usuario'))."','$this->ed321_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed321_inicio"]) || $this->ed321_inicio != "")
           $resac = db_query("insert into db_acount values($acount,3541,19755,'".AddSlashes(pg_result($resaco,$conresaco,'ed321_inicio'))."','$this->ed321_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed321_final"]) || $this->ed321_final != "")
           $resac = db_query("insert into db_acount values($acount,3541,19756,'".AddSlashes(pg_result($resaco,$conresaco,'ed321_final'))."','$this->ed321_final',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed321_observacao"]) || $this->ed321_observacao != "")
           $resac = db_query("insert into db_acount values($acount,3541,19757,'".AddSlashes(pg_result($resaco,$conresaco,'ed321_observacao'))."','$this->ed321_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed321_escola"]) || $this->ed321_escola != "")
           $resac = db_query("insert into db_acount values($acount,3541,19777,'".AddSlashes(pg_result($resaco,$conresaco,'ed321_escola'))."','$this->ed321_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Docente Ausente nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed321_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Docente Ausente nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed321_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed321_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed321_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed321_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19752,'$ed321_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3541,19752,'','".AddSlashes(pg_result($resaco,$iresaco,'ed321_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3541,19753,'','".AddSlashes(pg_result($resaco,$iresaco,'ed321_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3541,19754,'','".AddSlashes(pg_result($resaco,$iresaco,'ed321_tipoausencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3541,19758,'','".AddSlashes(pg_result($resaco,$iresaco,'ed321_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3541,19755,'','".AddSlashes(pg_result($resaco,$iresaco,'ed321_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3541,19756,'','".AddSlashes(pg_result($resaco,$iresaco,'ed321_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3541,19757,'','".AddSlashes(pg_result($resaco,$iresaco,'ed321_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3541,19777,'','".AddSlashes(pg_result($resaco,$iresaco,'ed321_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from docenteausencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed321_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed321_sequencial = $ed321_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Docente Ausente nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed321_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Docente Ausente nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed321_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed321_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:docenteausencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed321_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from docenteausencia ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = docenteausencia.ed321_usuario";
     $sql .= "      inner join tipoausencia  on  tipoausencia.ed320_sequencial = docenteausencia.ed321_tipoausencia";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = docenteausencia.ed321_escola";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = docenteausencia.ed321_rechumano";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join   on  .ed263_i_codigo = escola.ed18_i_censoorgreg";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql .= "      left  join rhregime  on  rhregime.rh30_codreg = rechumano.ed20_i_rhregime";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf  as a on   a.ed260_i_codigo = rechumano.ed20_i_censoufcert and   a.ed260_i_codigo = rechumano.ed20_i_censoufender and   a.ed260_i_codigo = rechumano.ed20_i_censoufnat and   a.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censomunic  as b on   b.ed261_i_codigo = rechumano.ed20_i_censomunicnat and   b. = rechumano.ed20_i_censomunicender";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join censocartorio  on  censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio";
     $sql .= "      left  join rechumano  as c on   c.ed20_i_codigo = rechumano.ed20_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed321_sequencial!=null ){
         $sql2 .= " where docenteausencia.ed321_sequencial = $ed321_sequencial ";
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
   function sql_query_file ( $ed321_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from docenteausencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed321_sequencial!=null ){
         $sql2 .= " where docenteausencia.ed321_sequencial = $ed321_sequencial ";
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
   function sql_query_docente_cgm ($ed321_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
  	$sql .= " from docenteausencia ";
  	$sql .= "      inner join escola on ed18_i_codigo = ed321_escola ";

  	/**
  	 * CGM da rhpessoal
  	 */
  	$sql .= "   left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = docenteausencia.ed321_rechumano ";
  	$sql .= "   left join rhpessoal    on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal       ";
  	$sql .= "   left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm                         ";
  	/**
  	 * CGM do RH
  	 */
  	$sql .= "   left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = docenteausencia.ed321_rechumano ";
  	$sql .= "   left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm                     ";
  	$sql2 = "";
  	if($dbwhere == "") {

  		if($ed321_sequencial != null) {
  			$sql2 .= " where docenteausencia.ed321_sequencial = $ed321_sequencial ";
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

   // funcao do sql
   function sql_query_tipoausencia ( $ed321_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from docenteausencia ";
     $sql .= "      left join tipoausencia on tipoausencia.ed320_sequencial =  docenteausencia.ed321_tipoausencia";
     $sql2 = "";
     if($dbwhere==""){
       if($ed321_sequencial!=null ){
         $sql2 .= " where docenteausencia.ed321_sequencial = $ed321_sequencial ";
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