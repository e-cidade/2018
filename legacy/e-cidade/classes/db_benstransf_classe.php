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

//MODULO: patrim
//CLASSE DA ENTIDADE benstransf
class cl_benstransf {
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
   var $t93_codtran = 0;
   var $t93_data_dia = null;
   var $t93_data_mes = null;
   var $t93_data_ano = null;
   var $t93_data = null;
   var $t93_depart = 0;
   var $t93_id_usuario = 0;
   var $t93_obs = null;
   var $t93_instit = 0;
   var $t93_clabens = 0;
   var $t93_divisao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 t93_codtran = int8 = Transferência
                 t93_data = date = Data da transferência
                 t93_depart = int4 = Departamento origem
                 t93_id_usuario = int4 = Cod. Usuário
                 t93_obs = text = Observação
                 t93_instit = int4 = Instituição
                 t93_clabens = int4 = Classificação
                 t93_divisao = int4 = Divisão
                 ";
   //funcao construtor da classe
   function cl_benstransf() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benstransf");
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
       $this->t93_codtran = ($this->t93_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["t93_codtran"]:$this->t93_codtran);
       if($this->t93_data == ""){
         $this->t93_data_dia = ($this->t93_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t93_data_dia"]:$this->t93_data_dia);
         $this->t93_data_mes = ($this->t93_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t93_data_mes"]:$this->t93_data_mes);
         $this->t93_data_ano = ($this->t93_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t93_data_ano"]:$this->t93_data_ano);
         if($this->t93_data_dia != ""){
            $this->t93_data = $this->t93_data_ano."-".$this->t93_data_mes."-".$this->t93_data_dia;
         }
       }
       $this->t93_depart = ($this->t93_depart == ""?@$GLOBALS["HTTP_POST_VARS"]["t93_depart"]:$this->t93_depart);
       $this->t93_id_usuario = ($this->t93_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t93_id_usuario"]:$this->t93_id_usuario);
       $this->t93_obs = ($this->t93_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["t93_obs"]:$this->t93_obs);
       $this->t93_instit = ($this->t93_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["t93_instit"]:$this->t93_instit);
       $this->t93_clabens = ($this->t93_clabens == ""?@$GLOBALS["HTTP_POST_VARS"]["t93_clabens"]:$this->t93_clabens);
       $this->t93_divisao = ($this->t93_divisao == ""?@$GLOBALS["HTTP_POST_VARS"]["t93_divisao"]:$this->t93_divisao);
     }else{
       $this->t93_codtran = ($this->t93_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["t93_codtran"]:$this->t93_codtran);
     }
   }
   // funcao para inclusao
   function incluir ($t93_codtran){
      $this->atualizacampos();
     if($this->t93_data == null ){
       $this->erro_sql = " Campo Data da transferência nao Informado.";
       $this->erro_campo = "t93_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t93_depart == null ){
       $this->erro_sql = " Campo Departamento origem nao Informado.";
       $this->erro_campo = "t93_depart";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t93_id_usuario == null ){
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "t93_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t93_instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "t93_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t93_clabens == null ){
       $this->t93_clabens = "0";
     }
     if($t93_codtran == "" || $t93_codtran == null ){
       $result = db_query("select nextval('benstransf_t93_codtran_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: benstransf_t93_codtran_seq do campo: t93_codtran";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->t93_codtran = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from benstransf_t93_codtran_seq");
       if(($result != false) && (pg_result($result,0,0) < $t93_codtran)){
         $this->erro_sql = " Campo t93_codtran maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t93_codtran = $t93_codtran;
       }
     }
     if(($this->t93_codtran == null) || ($this->t93_codtran == "") ){
       $this->erro_sql = " Campo t93_codtran nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if (empty($this->t93_divisao)) {
       $this->t93_divisao = 'null';
     }
     $sql = "insert into benstransf(
                                       t93_codtran
                                      ,t93_data
                                      ,t93_depart
                                      ,t93_id_usuario
                                      ,t93_obs
                                      ,t93_instit
                                      ,t93_clabens
                                      ,t93_divisao
                       )
                values (
                                $this->t93_codtran
                               ,".($this->t93_data == "null" || $this->t93_data == ""?"null":"'".$this->t93_data."'")."
                               ,$this->t93_depart
                               ,$this->t93_id_usuario
                               ,'$this->t93_obs'
                               ,$this->t93_instit
                               ,$this->t93_clabens
                               ,$this->t93_divisao
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Transferência de bens ($this->t93_codtran) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Transferência de bens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Transferência de bens ($this->t93_codtran) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t93_codtran;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t93_codtran));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5823,'$this->t93_codtran','I')");
       $resac = db_query("insert into db_acount values($acount,930,5823,'','".AddSlashes(pg_result($resaco,0,'t93_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,930,5824,'','".AddSlashes(pg_result($resaco,0,'t93_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,930,5825,'','".AddSlashes(pg_result($resaco,0,'t93_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,930,5826,'','".AddSlashes(pg_result($resaco,0,'t93_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,930,9562,'','".AddSlashes(pg_result($resaco,0,'t93_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,930,9822,'','".AddSlashes(pg_result($resaco,0,'t93_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,930,13772,'','".AddSlashes(pg_result($resaco,0,'t93_clabens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,930,13773,'','".AddSlashes(pg_result($resaco,0,'t93_divisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($t93_codtran=null) {
      $this->atualizacampos();
     $sql = " update benstransf set ";
     $virgula = "";
     if(trim($this->t93_codtran)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t93_codtran"])){
       $sql  .= $virgula." t93_codtran = $this->t93_codtran ";
       $virgula = ",";
       if(trim($this->t93_codtran) == null ){
         $this->erro_sql = " Campo Transferência nao Informado.";
         $this->erro_campo = "t93_codtran";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t93_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t93_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t93_data_dia"] !="") ){
       $sql  .= $virgula." t93_data = '$this->t93_data' ";
       $virgula = ",";
       if(trim($this->t93_data) == null ){
         $this->erro_sql = " Campo Data da transferência nao Informado.";
         $this->erro_campo = "t93_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["t93_data_dia"])){
         $sql  .= $virgula." t93_data = null ";
         $virgula = ",";
         if(trim($this->t93_data) == null ){
           $this->erro_sql = " Campo Data da transferência nao Informado.";
           $this->erro_campo = "t93_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t93_depart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t93_depart"])){
       $sql  .= $virgula." t93_depart = $this->t93_depart ";
       $virgula = ",";
       if(trim($this->t93_depart) == null ){
         $this->erro_sql = " Campo Departamento origem nao Informado.";
         $this->erro_campo = "t93_depart";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t93_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t93_id_usuario"])){
       $sql  .= $virgula." t93_id_usuario = $this->t93_id_usuario ";
       $virgula = ",";
       if(trim($this->t93_id_usuario) == null ){
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "t93_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t93_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t93_obs"])){
       $sql  .= $virgula." t93_obs = '$this->t93_obs' ";
       $virgula = ",";
     }
     if(trim($this->t93_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t93_instit"])){
       $sql  .= $virgula." t93_instit = $this->t93_instit ";
       $virgula = ",";
       if(trim($this->t93_instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "t93_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t93_clabens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t93_clabens"])){
        if(trim($this->t93_clabens)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t93_clabens"])){
           $this->t93_clabens = "0" ;
        }
       $sql  .= $virgula." t93_clabens = $this->t93_clabens ";
       $virgula = ",";
     }
     if(trim($this->t93_divisao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t93_divisao"])){
        if(trim($this->t93_divisao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t93_divisao"])){
           $this->t93_divisao = null ;
        }
       $sql  .= $virgula." t93_divisao = $this->t93_divisao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($t93_codtran!=null){
       $sql .= " t93_codtran = $this->t93_codtran";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t93_codtran));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5823,'$this->t93_codtran','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t93_codtran"]))
           $resac = db_query("insert into db_acount values($acount,930,5823,'".AddSlashes(pg_result($resaco,$conresaco,'t93_codtran'))."','$this->t93_codtran',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t93_data"]))
           $resac = db_query("insert into db_acount values($acount,930,5824,'".AddSlashes(pg_result($resaco,$conresaco,'t93_data'))."','$this->t93_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t93_depart"]))
           $resac = db_query("insert into db_acount values($acount,930,5825,'".AddSlashes(pg_result($resaco,$conresaco,'t93_depart'))."','$this->t93_depart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t93_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,930,5826,'".AddSlashes(pg_result($resaco,$conresaco,'t93_id_usuario'))."','$this->t93_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t93_obs"]))
           $resac = db_query("insert into db_acount values($acount,930,9562,'".AddSlashes(pg_result($resaco,$conresaco,'t93_obs'))."','$this->t93_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t93_instit"]))
           $resac = db_query("insert into db_acount values($acount,930,9822,'".AddSlashes(pg_result($resaco,$conresaco,'t93_instit'))."','$this->t93_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t93_clabens"]))
           $resac = db_query("insert into db_acount values($acount,930,13772,'".AddSlashes(pg_result($resaco,$conresaco,'t93_clabens'))."','$this->t93_clabens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t93_divisao"]))
           $resac = db_query("insert into db_acount values($acount,930,13773,'".AddSlashes(pg_result($resaco,$conresaco,'t93_divisao'))."','$this->t93_divisao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferência de bens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t93_codtran;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Transferência de bens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t93_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t93_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($t93_codtran=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t93_codtran));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5823,'$t93_codtran','E')");
         $resac = db_query("insert into db_acount values($acount,930,5823,'','".AddSlashes(pg_result($resaco,$iresaco,'t93_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,930,5824,'','".AddSlashes(pg_result($resaco,$iresaco,'t93_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,930,5825,'','".AddSlashes(pg_result($resaco,$iresaco,'t93_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,930,5826,'','".AddSlashes(pg_result($resaco,$iresaco,'t93_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,930,9562,'','".AddSlashes(pg_result($resaco,$iresaco,'t93_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,930,9822,'','".AddSlashes(pg_result($resaco,$iresaco,'t93_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,930,13772,'','".AddSlashes(pg_result($resaco,$iresaco,'t93_clabens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,930,13773,'','".AddSlashes(pg_result($resaco,$iresaco,'t93_divisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benstransf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t93_codtran != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t93_codtran = $t93_codtran ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferência de bens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t93_codtran;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Transferência de bens nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t93_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t93_codtran;
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
        $this->erro_sql   = "Record Vazio na Tabela:benstransf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $t93_codtran=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from benstransf ";

     $sql .= "      inner join db_config  on  db_config.codigo = benstransf.t93_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benstransf.t93_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = benstransf.t93_depart";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      left outer join benstransfdes on benstransf.t93_codtran = benstransfdes.t94_codtran";
     $sql .= "      left outer join benstransfcodigo on benstransf.t93_codtran = benstransfcodigo.t95_codtran";
     $sql2 = "";
     if($dbwhere==""){
       if($t93_codtran!=null ){
         $sql2 .= " where benstransf.t93_codtran = $t93_codtran ";
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

   function sql_query_departamento_destino ( $t93_codtran=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from benstransf ";

     $sql .= "      inner join db_config  on  db_config.codigo = benstransf.t93_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benstransf.t93_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = benstransf.t93_depart";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      left outer join benstransfdes on benstransf.t93_codtran = benstransfdes.t94_codtran";
     $sql .= "      left outer join benstransfcodigo on benstransf.t93_codtran = benstransfcodigo.t95_codtran";
     $sql .= "      left join db_depart departdestino on departdestino.coddepto = benstransfdes.t94_depart";
     $sql2 = "";
     if($dbwhere==""){
       if($t93_codtran!=null ){
         $sql2 .= " where benstransf.t93_codtran = $t93_codtran ";
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
   function sql_query_file ( $t93_codtran=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from benstransf ";
     $sql2 = "";
     if($dbwhere==""){
       if($t93_codtran!=null ){
         $sql2 .= " where benstransf.t93_codtran = $t93_codtran ";
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
