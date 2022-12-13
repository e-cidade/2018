<?php
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

//MODULO: agua
//CLASSE DA ENTIDADE aguaisencaocgm
class cl_aguaisencaocgm {
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
   var $x56_sequencial = 0;
   var $x56_aguaisencaotipo = 0;
   var $x56_cgm = 0;
   var $x56_datainicial_dia = null;
   var $x56_datainicial_mes = null;
   var $x56_datainicial_ano = null;
   var $x56_datainicial = null;
   var $x56_datafinal_dia = null;
   var $x56_datafinal_mes = null;
   var $x56_datafinal_ano = null;
   var $x56_datafinal = null;
   var $x56_processo = null;
   var $x56_observacoes = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 x56_sequencial = int4 = Código
                 x56_aguaisencaotipo = int4 = Tipo de Isenção
                 x56_cgm = int4 = Nome/Razão Social
                 x56_datainicial = date = Data Inicial
                 x56_datafinal = date = Data Final
                 x56_processo = varchar(30) = Número do Processo
                 x56_observacoes = text = Observações
                 ";
   //funcao construtor da classe
   function cl_aguaisencaocgm() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguaisencaocgm");
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

   // funcao para Inclusão
   function incluir ($x56_sequencial){
     if($this->x56_aguaisencaotipo == null ){
       $this->erro_sql = " Campo Tipo de Isenção não informado.";
       $this->erro_campo = "x56_aguaisencaotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x56_cgm == null ){
       $this->erro_sql = " Campo Nome/Razão Social não informado.";
       $this->erro_campo = "x56_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x56_datainicial == null ){
       $this->erro_sql = " Campo Data Inicial não informado.";
       $this->erro_campo = "x56_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x56_sequencial == "" || $x56_sequencial == null ){
       $result = db_query("select nextval('aguaisencaocgm_x56_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguaisencaocgm_x56_sequencial_seq do campo: x56_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->x56_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from aguaisencaocgm_x56_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $x56_sequencial)){
         $this->erro_sql = " Campo x56_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x56_sequencial = $x56_sequencial;
       }
     }
     if(($this->x56_sequencial == null) || ($this->x56_sequencial == "") ){
       $this->erro_sql = " Campo x56_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguaisencaocgm(
                                       x56_sequencial
                                      ,x56_aguaisencaotipo
                                      ,x56_cgm
                                      ,x56_datainicial
                                      ,x56_datafinal
                                      ,x56_processo
                                      ,x56_observacoes
                       )
                values (
                                $this->x56_sequencial
                               ,$this->x56_aguaisencaotipo
                               ,$this->x56_cgm
                               ,".($this->x56_datainicial == "null" || $this->x56_datainicial == ""?"null":"'".$this->x56_datainicial."'")."
                               ,".($this->x56_datafinal == "null" || $this->x56_datafinal == ""?"null":"'".$this->x56_datafinal."'")."
                               ,'$this->x56_processo'
                               ,'$this->x56_observacoes'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Isenções por CGM ($this->x56_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Isenções por CGM já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Isenções por CGM ($this->x56_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x56_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x56_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22080,'$this->x56_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3977,22080,'','".AddSlashes(pg_result($resaco,0,'x56_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3977,22081,'','".AddSlashes(pg_result($resaco,0,'x56_aguaisencaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3977,22082,'','".AddSlashes(pg_result($resaco,0,'x56_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3977,22083,'','".AddSlashes(pg_result($resaco,0,'x56_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3977,22084,'','".AddSlashes(pg_result($resaco,0,'x56_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3977,22085,'','".AddSlashes(pg_result($resaco,0,'x56_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3977,22086,'','".AddSlashes(pg_result($resaco,0,'x56_observacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($x56_sequencial=null) {
     $sql = " update aguaisencaocgm set ";
     $virgula = "";
     if(trim($this->x56_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x56_sequencial"])){
       $sql  .= $virgula." x56_sequencial = $this->x56_sequencial ";
       $virgula = ",";
       if(trim($this->x56_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "x56_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x56_aguaisencaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x56_aguaisencaotipo"])){
       $sql  .= $virgula." x56_aguaisencaotipo = $this->x56_aguaisencaotipo ";
       $virgula = ",";
       if(trim($this->x56_aguaisencaotipo) == null ){
         $this->erro_sql = " Campo Tipo de Isenção não informado.";
         $this->erro_campo = "x56_aguaisencaotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x56_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x56_cgm"])){
       $sql  .= $virgula." x56_cgm = $this->x56_cgm ";
       $virgula = ",";
       if(trim($this->x56_cgm) == null ){
         $this->erro_sql = " Campo Nome/Razão Social não informado.";
         $this->erro_campo = "x56_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x56_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x56_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x56_datainicial_dia"] !="") ){
       $sql  .= $virgula." x56_datainicial = '$this->x56_datainicial' ";
       $virgula = ",";
       if(trim($this->x56_datainicial) == null ){
         $this->erro_sql = " Campo Data Inicial não informado.";
         $this->erro_campo = "x56_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["x56_datainicial_dia"])){
         $sql  .= $virgula." x56_datainicial = null ";
         $virgula = ",";
         if(trim($this->x56_datainicial) == null ){
           $this->erro_sql = " Campo Data Inicial não informado.";
           $this->erro_campo = "x56_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }

     if($this->x56_datafinal !== null){
       $sql  .= $virgula." x56_datafinal = '$this->x56_datafinal' ";
       $virgula = ",";
     } else {
       $sql  .= $virgula." x56_datafinal = null ";
     }

     if($this->x56_processo !== null) {
       $sql  .= $virgula." x56_processo = '$this->x56_processo' ";
       $virgula = ",";
     }

     if($this->x56_observacoes !== null) {
       $sql  .= $virgula." x56_observacoes = '$this->x56_observacoes' ";
       $virgula = ",";
     }

     $sql .= " where ";
     if($x56_sequencial!=null){
       $sql .= " x56_sequencial = $this->x56_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x56_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22080,'$this->x56_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x56_sequencial"]) || $this->x56_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3977,22080,'".AddSlashes(pg_result($resaco,$conresaco,'x56_sequencial'))."','$this->x56_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x56_aguaisencaotipo"]) || $this->x56_aguaisencaotipo != "")
             $resac = db_query("insert into db_acount values($acount,3977,22081,'".AddSlashes(pg_result($resaco,$conresaco,'x56_aguaisencaotipo'))."','$this->x56_aguaisencaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x56_cgm"]) || $this->x56_cgm != "")
             $resac = db_query("insert into db_acount values($acount,3977,22082,'".AddSlashes(pg_result($resaco,$conresaco,'x56_cgm'))."','$this->x56_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x56_datainicial"]) || $this->x56_datainicial != "")
             $resac = db_query("insert into db_acount values($acount,3977,22083,'".AddSlashes(pg_result($resaco,$conresaco,'x56_datainicial'))."','$this->x56_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x56_datafinal"]) || $this->x56_datafinal != "")
             $resac = db_query("insert into db_acount values($acount,3977,22084,'".AddSlashes(pg_result($resaco,$conresaco,'x56_datafinal'))."','$this->x56_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x56_processo"]) || $this->x56_processo != "")
             $resac = db_query("insert into db_acount values($acount,3977,22085,'".AddSlashes(pg_result($resaco,$conresaco,'x56_processo'))."','$this->x56_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x56_observacoes"]) || $this->x56_observacoes != "")
             $resac = db_query("insert into db_acount values($acount,3977,22086,'".AddSlashes(pg_result($resaco,$conresaco,'x56_observacoes'))."','$this->x56_observacoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Isenções por CGM não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x56_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Isenções por CGM não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($x56_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($x56_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22080,'$x56_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3977,22080,'','".AddSlashes(pg_result($resaco,$iresaco,'x56_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3977,22081,'','".AddSlashes(pg_result($resaco,$iresaco,'x56_aguaisencaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3977,22082,'','".AddSlashes(pg_result($resaco,$iresaco,'x56_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3977,22083,'','".AddSlashes(pg_result($resaco,$iresaco,'x56_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3977,22084,'','".AddSlashes(pg_result($resaco,$iresaco,'x56_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3977,22085,'','".AddSlashes(pg_result($resaco,$iresaco,'x56_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3977,22086,'','".AddSlashes(pg_result($resaco,$iresaco,'x56_observacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from aguaisencaocgm
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($x56_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " x56_sequencial = $x56_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Isenções por CGM não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x56_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Isenções por CGM não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:aguaisencaocgm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($x56_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from aguaisencaocgm ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguaisencaocgm.x56_cgm";
     $sql .= "      inner join aguaisencaotipo  on  aguaisencaotipo.x29_codisencaotipo = aguaisencaocgm.x56_aguaisencaotipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x56_sequencial)) {
         $sql2 .= " where aguaisencaocgm.x56_sequencial = $x56_sequencial ";
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
   // funcao do sql
   public function sql_query_file ($x56_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from aguaisencaocgm ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x56_sequencial)){
         $sql2 .= " where aguaisencaocgm.x56_sequencial = $x56_sequencial ";
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
