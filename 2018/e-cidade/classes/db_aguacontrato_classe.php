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
class cl_aguacontrato {

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

   var $x54_sequencial = 0;
   var $x54_aguabase = 'null';
   var $x54_diavencimento = 'null';
   var $x54_datavalidadecadastro_dia = null;
   var $x54_datavalidadecadastro_mes = null;
   var $x54_datavalidadecadastro_ano = null;
   var $x54_datavalidadecadastro = null;
   var $x54_datainicial_dia = null;
   var $x54_datainicial_mes = null;
   var $x54_datainicial_ano = null;
   var $x54_datainicial = null;
   var $x54_datafinal_dia = null;
   var $x54_datafinal_mes = null;
   var $x54_datafinal_ano = null;
   var $x54_datafinal = null;
   var $x54_nis = null;
   var $x54_cgm = 0;
   var $x54_aguacategoriaconsumo = 'null';
   var $x54_condominio = 'f';
   var $x54_aguatipocontrato = 'null';
   var $x54_responsavelpagamento = 0;
   var $x54_emitiroutrosdebitos = 'f';

   var $campos = "
                 x54_sequencial = int4 = Código 
                 x54_aguabase = int4 = Matrícula 
                 x54_diavencimento = int4 = Dia de Vencimento 
                 x54_datavalidadecadastro = date = Validade do Cadastro Social 
                 x54_datainicial = date = Data Inicial 
                 x54_datafinal = date = Data FInal 
                 x54_nis = varchar(20) = NIS 
                 x54_cgm = int4 = CGM 
                 x54_aguacategoriaconsumo = int4 = Código da Categoria de Consumo 
                 x54_condominio = bool = Condomínio 
                 x54_aguatipocontrato = int4 = Tipo de Contrato 
                 x54_responsavelpagamento = int4 = Responsável pelo pagamento 
                 x54_emitiroutrosdebitos = bool = Emitir Outros Débitos 
                 ";

   function cl_aguacontrato() {

     $this->rotulo = new rotulo("aguacontrato");
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
   function incluir ($x54_sequencial){

     if($this->x54_datainicial == null ){
       $this->erro_sql = " Campo Data Inicial não informado.";
       $this->erro_campo = "x54_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x54_cgm == null ){
       $this->erro_sql = " Campo CGM não informado.";
       $this->erro_campo = "x54_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x54_responsavelpagamento == null ){
       $this->x54_responsavelpagamento = "null";
     }
     if($this->x54_emitiroutrosdebitos === null ){
       $this->erro_sql = " Campo Emitir Outros Débitos não informado.";
       $this->erro_campo = "x54_emitiroutrosdebitos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x54_sequencial == "" || $x54_sequencial == null ){
       $result = db_query("select nextval('aguacontrato_x54_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacontrato_x54_sequencial_seq do campo: x54_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->x54_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from aguacontrato_x54_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $x54_sequencial)){
         $this->erro_sql = " Campo x54_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x54_sequencial = $x54_sequencial;
       }
     }
     if(($this->x54_sequencial == null) || ($this->x54_sequencial == "") ){
       $this->erro_sql = " Campo x54_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacontrato(
                                       x54_sequencial 
                                      ,x54_aguabase 
                                      ,x54_diavencimento 
                                      ,x54_datavalidadecadastro 
                                      ,x54_datainicial 
                                      ,x54_datafinal 
                                      ,x54_nis 
                                      ,x54_cgm 
                                      ,x54_aguacategoriaconsumo 
                                      ,x54_condominio 
                                      ,x54_aguatipocontrato 
                                      ,x54_responsavelpagamento 
                                      ,x54_emitiroutrosdebitos 
                       )
                values (
                                $this->x54_sequencial 
                               ,$this->x54_aguabase 
                               ,$this->x54_diavencimento 
                               ,".($this->x54_datavalidadecadastro == "null" || $this->x54_datavalidadecadastro == ""?"null":"'".$this->x54_datavalidadecadastro."'")." 
                               ,".($this->x54_datainicial == "null" || $this->x54_datainicial == ""?"null":"'".$this->x54_datainicial."'")." 
                               ,".($this->x54_datafinal == "null" || $this->x54_datafinal == ""?"null":"'".$this->x54_datafinal."'")." 
                               ,'$this->x54_nis' 
                               ,$this->x54_cgm 
                               ,$this->x54_aguacategoriaconsumo 
                               ,'$this->x54_condominio' 
                               ,$this->x54_aguatipocontrato 
                               ,$this->x54_responsavelpagamento 
                               ,'$this->x54_emitiroutrosdebitos' 
                      )";

     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contrato ($this->x54_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contrato já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contrato ($this->x54_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->x54_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x54_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22031,'$this->x54_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3966,22031,'','".AddSlashes(pg_result($resaco,0,'x54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,22032,'','".AddSlashes(pg_result($resaco,0,'x54_aguabase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,22033,'','".AddSlashes(pg_result($resaco,0,'x54_diavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,22034,'','".AddSlashes(pg_result($resaco,0,'x54_datavalidadecadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,22035,'','".AddSlashes(pg_result($resaco,0,'x54_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,22036,'','".AddSlashes(pg_result($resaco,0,'x54_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,22040,'','".AddSlashes(pg_result($resaco,0,'x54_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,22041,'','".AddSlashes(pg_result($resaco,0,'x54_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,22074,'','".AddSlashes(pg_result($resaco,0,'x54_aguacategoriaconsumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,22122,'','".AddSlashes(pg_result($resaco,0,'x54_condominio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,22123,'','".AddSlashes(pg_result($resaco,0,'x54_aguatipocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,22419,'','".AddSlashes(pg_result($resaco,0,'x54_responsavelpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3966,1009271,'','".AddSlashes(pg_result($resaco,0,'x54_emitiroutrosdebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

   public function alterar ($x54_sequencial=null) {

     $sql = " update aguacontrato set ";
     $virgula = "";
     if($this->x54_sequencial !== null) {
       $sql  .= $virgula." x54_sequencial = $this->x54_sequencial ";
       $virgula = ",";
       if(trim($this->x54_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "x54_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if ($this->x54_aguabase !== null) {

        if(trim($this->x54_aguabase)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x54_aguabase"])){
           $this->x54_aguabase = "0" ;
        }
       $sql  .= $virgula." x54_aguabase = $this->x54_aguabase ";
       $virgula = ",";
     }

     if ($this->x54_diavencimento !== null) {

       $sql  .= $virgula." x54_diavencimento = $this->x54_diavencimento ";
       $virgula = ",";
     } else {
       $sql  .= $virgula." x54_diavencimento = null ";
       $virgula = ",";
     }

     if ($this->x54_datavalidadecadastro !== null) {

       $sql  .= $virgula." x54_datavalidadecadastro = '$this->x54_datavalidadecadastro' ";
       $virgula = ",";
     }     else{
         $sql  .= $virgula." x54_datavalidadecadastro = null ";
         $virgula = ",";
       }

     if ($this->x54_datainicial !== null) {

       $sql  .= $virgula." x54_datainicial = '$this->x54_datainicial' ";
       $virgula = ",";
       if(trim($this->x54_datainicial) == null ){

         $this->erro_sql = " Campo Data Inicial não informado.";
         $this->erro_campo = "x54_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{

       if(isset($GLOBALS["HTTP_POST_VARS"]["x54_datainicial_dia"])){

         $sql  .= $virgula." x54_datainicial = null ";
         $virgula = ",";
         if(trim($this->x54_datainicial) == null ){

           $this->erro_sql = " Campo Data Inicial não informado.";
           $this->erro_campo = "x54_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }

     if ($this->x54_datafinal !== null) {

       $sql  .= $virgula." x54_datafinal = '$this->x54_datafinal' ";
       $virgula = ",";
     } else{

         $sql  .= $virgula." x54_datafinal = null ";
         $virgula = ",";
       }

     if ($this->x54_nis !== null) {
       $sql  .= $virgula." x54_nis = '$this->x54_nis' ";
       $virgula = ",";
     }

     if ($this->x54_cgm !== null) {
       $sql  .= $virgula." x54_cgm = $this->x54_cgm ";
       $virgula = ",";
       if(trim($this->x54_cgm) == null ){
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "x54_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if($this->x54_aguacategoriaconsumo !== null) {
        if(trim($this->x54_aguacategoriaconsumo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x54_aguacategoriaconsumo"])){
           $this->x54_aguacategoriaconsumo = "0" ;
        }
       $sql  .= $virgula." x54_aguacategoriaconsumo = $this->x54_aguacategoriaconsumo ";
       $virgula = ",";
     }

     if($this->x54_condominio !== null) {
       $sql  .= $virgula." x54_condominio = '$this->x54_condominio' ";
       $virgula = ",";
     }

     if($this->x54_aguatipocontrato !== null) {
        if(trim($this->x54_aguatipocontrato)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x54_aguatipocontrato"])){
           $this->x54_aguatipocontrato = "0" ;
        }
       $sql  .= $virgula." x54_aguatipocontrato = $this->x54_aguatipocontrato ";
       $virgula = ",";
     }

     if ($this->x54_responsavelpagamento !== null) {
        if(trim($this->x54_responsavelpagamento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x54_responsavelpagamento"])){
           $this->x54_responsavelpagamento = "0" ;
        }
       $sql  .= $virgula." x54_responsavelpagamento = $this->x54_responsavelpagamento ";
       $virgula = ",";
     }

     if($this->x54_emitiroutrosdebitos !== null){
       $sql  .= $virgula." x54_emitiroutrosdebitos = '$this->x54_emitiroutrosdebitos' ";
       $virgula = ",";

       if($this->x54_emitiroutrosdebitos === null ){
         $this->erro_sql = " Campo Emitir Outros Débitos não informado.";
         $this->erro_campo = "x54_emitiroutrosdebitos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     $sql .= " where ";
     if($x54_sequencial!=null){
       $sql .= " x54_sequencial = $this->x54_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->x54_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22031,'$this->x54_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_sequencial"]) || $this->x54_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3966,22031,'".AddSlashes(pg_result($resaco,$conresaco,'x54_sequencial'))."','$this->x54_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_aguabase"]) || $this->x54_aguabase != "")
             $resac = db_query("insert into db_acount values($acount,3966,22032,'".AddSlashes(pg_result($resaco,$conresaco,'x54_aguabase'))."','$this->x54_aguabase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_diavencimento"]) || $this->x54_diavencimento != "")
             $resac = db_query("insert into db_acount values($acount,3966,22033,'".AddSlashes(pg_result($resaco,$conresaco,'x54_diavencimento'))."','$this->x54_diavencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_datavalidadecadastro"]) || $this->x54_datavalidadecadastro != "")
             $resac = db_query("insert into db_acount values($acount,3966,22034,'".AddSlashes(pg_result($resaco,$conresaco,'x54_datavalidadecadastro'))."','$this->x54_datavalidadecadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_datainicial"]) || $this->x54_datainicial != "")
             $resac = db_query("insert into db_acount values($acount,3966,22035,'".AddSlashes(pg_result($resaco,$conresaco,'x54_datainicial'))."','$this->x54_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_datafinal"]) || $this->x54_datafinal != "")
             $resac = db_query("insert into db_acount values($acount,3966,22036,'".AddSlashes(pg_result($resaco,$conresaco,'x54_datafinal'))."','$this->x54_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_nis"]) || $this->x54_nis != "")
             $resac = db_query("insert into db_acount values($acount,3966,22040,'".AddSlashes(pg_result($resaco,$conresaco,'x54_nis'))."','$this->x54_nis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_cgm"]) || $this->x54_cgm != "")
             $resac = db_query("insert into db_acount values($acount,3966,22041,'".AddSlashes(pg_result($resaco,$conresaco,'x54_cgm'))."','$this->x54_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_aguacategoriaconsumo"]) || $this->x54_aguacategoriaconsumo != "")
             $resac = db_query("insert into db_acount values($acount,3966,22074,'".AddSlashes(pg_result($resaco,$conresaco,'x54_aguacategoriaconsumo'))."','$this->x54_aguacategoriaconsumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_condominio"]) || $this->x54_condominio != "")
             $resac = db_query("insert into db_acount values($acount,3966,22122,'".AddSlashes(pg_result($resaco,$conresaco,'x54_condominio'))."','$this->x54_condominio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_aguatipocontrato"]) || $this->x54_aguatipocontrato != "")
             $resac = db_query("insert into db_acount values($acount,3966,22123,'".AddSlashes(pg_result($resaco,$conresaco,'x54_aguatipocontrato'))."','$this->x54_aguatipocontrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_responsavelpagamento"]) || $this->x54_responsavelpagamento != "")
             $resac = db_query("insert into db_acount values($acount,3966,22419,'".AddSlashes(pg_result($resaco,$conresaco,'x54_responsavelpagamento'))."','$this->x54_responsavelpagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["x54_emitiroutrosdebitos"]) || $this->x54_emitiroutrosdebitos != "")
             $resac = db_query("insert into db_acount values($acount,3966,1009271,'".AddSlashes(pg_result($resaco,$conresaco,'x54_emitiroutrosdebitos'))."','$this->x54_emitiroutrosdebitos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {

       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contrato não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {

       if (pg_affected_rows($result) == 0) {

         $this->erro_banco = "";
         $this->erro_sql = "Contrato não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {

         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

   public function excluir ($x54_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($x54_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22031,'$x54_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3966,22031,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,22032,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_aguabase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,22033,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_diavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,22034,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_datavalidadecadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,22035,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,22036,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,22040,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,22041,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,22074,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_aguacategoriaconsumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,22122,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_condominio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,22123,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_aguatipocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,22419,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_responsavelpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3966,1009271,'','".AddSlashes(pg_result($resaco,$iresaco,'x54_emitiroutrosdebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from aguacontrato
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($x54_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " x54_sequencial = $x54_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {

       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contrato não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {

       if (pg_affected_rows($result) == 0) {

         $this->erro_banco = "";
         $this->erro_sql = "Contrato não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {

         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }

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
        $this->erro_sql   = "Record Vazio na Tabela:aguacontrato";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   public function sql_query ($x54_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from aguacontrato ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguacontrato.x54_cgm";
     $sql .= "      left  join aguacategoriaconsumo  on  aguacategoriaconsumo.x13_sequencial = aguacontrato.x54_aguacategoriaconsumo";
     $sql .= "      left join aguabase  on  aguabase.x01_matric = aguacontrato.x54_aguabase";
     $sql .= "      left join bairro  on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      left join ruas  on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x54_sequencial)) {
         $sql2 .= " where aguacontrato.x54_sequencial = $x54_sequencial ";
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

   public function sql_query_file ($x54_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from aguacontrato ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($x54_sequencial)){
         $sql2 .= " where aguacontrato.x54_sequencial = $x54_sequencial ";
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

  public function sql_query_hidrometros($sCampos = '*', $sWhere = null, $sOrder = null) {

    $sSql  = "select {$sCampos} ";
    $sSql .= "from aguacontrato ";
    $sSql .= "inner join aguacontratoligacao on x55_aguacontrato = x54_sequencial ";

    if ($sWhere) {
      $sSql .= "where {$sWhere} ";
    }

    if ($sOrder) {
      $sSql .= "order by {$sOrder} ";
    }

    return $sSql;
  }

}
