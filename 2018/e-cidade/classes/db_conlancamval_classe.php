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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancamval
class cl_conlancamval {
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
   var $c69_sequen = 0;
   var $c69_anousu = 0;
   var $c69_codlan = 0;
   var $c69_codhist = 0;
   var $c69_credito = 0;
   var $c69_debito = 0;
   var $c69_valor = 0;
   var $c69_data_dia = null;
   var $c69_data_mes = null;
   var $c69_data_ano = null;
   var $c69_data = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c69_sequen = int4 = Sequen
                 c69_anousu = int4 = Exercício
                 c69_codlan = int4 = Cód Lan
                 c69_codhist = int4 = Histórico
                 c69_credito = int4 = Conta Credito
                 c69_debito = int4 = Conta Debito
                 c69_valor = float8 = Valor
                 c69_data = date = Data Lanc
                 ";
   //funcao construtor da classe
   function cl_conlancamval() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancamval");
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
       $this->c69_sequen = ($this->c69_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["c69_sequen"]:$this->c69_sequen);
       $this->c69_anousu = ($this->c69_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c69_anousu"]:$this->c69_anousu);
       $this->c69_codlan = ($this->c69_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c69_codlan"]:$this->c69_codlan);
       $this->c69_codhist = ($this->c69_codhist == ""?@$GLOBALS["HTTP_POST_VARS"]["c69_codhist"]:$this->c69_codhist);
       $this->c69_credito = ($this->c69_credito == ""?@$GLOBALS["HTTP_POST_VARS"]["c69_credito"]:$this->c69_credito);
       $this->c69_debito = ($this->c69_debito == ""?@$GLOBALS["HTTP_POST_VARS"]["c69_debito"]:$this->c69_debito);
       $this->c69_valor = ($this->c69_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["c69_valor"]:$this->c69_valor);
       if($this->c69_data == ""){
         $this->c69_data_dia = ($this->c69_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c69_data_dia"]:$this->c69_data_dia);
         $this->c69_data_mes = ($this->c69_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c69_data_mes"]:$this->c69_data_mes);
         $this->c69_data_ano = ($this->c69_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c69_data_ano"]:$this->c69_data_ano);
         if($this->c69_data_dia != ""){
            $this->c69_data = $this->c69_data_ano."-".$this->c69_data_mes."-".$this->c69_data_dia;
         }
       }
     }else{
       $this->c69_sequen = ($this->c69_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["c69_sequen"]:$this->c69_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($c69_sequen){
      $this->atualizacampos();
     if($this->c69_anousu == null ){
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "c69_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c69_codlan == null ){
       $this->erro_sql = " Campo Cód Lan nao Informado.";
       $this->erro_campo = "c69_codlan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c69_codhist == null ){
       $this->erro_sql = " Campo Histórico nao Informado.";
       $this->erro_campo = "c69_codhist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c69_credito == null ){
       $this->erro_sql = " Campo Conta Credito nao Informado.";
       $this->erro_campo = "c69_credito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c69_debito == null ){
       $this->erro_sql = " Campo Conta Debito nao Informado.";
       $this->erro_campo = "c69_debito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c69_valor == null ){
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "c69_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c69_data == null ){
       $this->erro_sql = " Campo Data Lanc nao Informado.";
       $this->erro_campo = "c69_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c69_sequen == "" || $c69_sequen == null ){
       $result = db_query("select nextval('conlancamval_c69_sequen_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conlancamval_c69_sequen_seq do campo: c69_sequen";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c69_sequen = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conlancamval_c69_sequen_seq");
       if(($result != false) && (pg_result($result,0,0) < $c69_sequen)){
         $this->erro_sql = " Campo c69_sequen maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c69_sequen = $c69_sequen;
       }
     }
     if(($this->c69_sequen == null) || ($this->c69_sequen == "") ){
       $this->erro_sql = " Campo c69_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancamval(
                                       c69_sequen
                                      ,c69_anousu
                                      ,c69_codlan
                                      ,c69_codhist
                                      ,c69_credito
                                      ,c69_debito
                                      ,c69_valor
                                      ,c69_data
                       )
                values (
                                $this->c69_sequen
                               ,$this->c69_anousu
                               ,$this->c69_codlan
                               ,$this->c69_codhist
                               ,$this->c69_credito
                               ,$this->c69_debito
                               ,$this->c69_valor
                               ,".($this->c69_data == "null" || $this->c69_data == ""?"null":"'".$this->c69_data."'")."
                      )";
     $result = @db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores lançamentos ($this->c69_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores lançamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores lançamentos ($this->c69_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c69_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c69_sequen));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5234,'$this->c69_sequen','I')");
         $resac = db_query("insert into db_acount values($acount,790,5234,'','".AddSlashes(pg_result($resaco,0,'c69_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,790,5235,'','".AddSlashes(pg_result($resaco,0,'c69_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,790,5236,'','".AddSlashes(pg_result($resaco,0,'c69_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,790,5237,'','".AddSlashes(pg_result($resaco,0,'c69_codhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,790,5238,'','".AddSlashes(pg_result($resaco,0,'c69_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,790,5243,'','".AddSlashes(pg_result($resaco,0,'c69_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,790,5244,'','".AddSlashes(pg_result($resaco,0,'c69_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,790,5245,'','".AddSlashes(pg_result($resaco,0,'c69_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c69_sequen=null) {
      $this->atualizacampos();
     $sql = " update conlancamval set ";
     $virgula = "";
     if(trim($this->c69_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c69_sequen"])){
       $sql  .= $virgula." c69_sequen = $this->c69_sequen ";
       $virgula = ",";
       if(trim($this->c69_sequen) == null ){
         $this->erro_sql = " Campo Sequen nao Informado.";
         $this->erro_campo = "c69_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c69_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c69_anousu"])){
       $sql  .= $virgula." c69_anousu = $this->c69_anousu ";
       $virgula = ",";
       if(trim($this->c69_anousu) == null ){
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c69_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c69_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c69_codlan"])){
       $sql  .= $virgula." c69_codlan = $this->c69_codlan ";
       $virgula = ",";
       if(trim($this->c69_codlan) == null ){
         $this->erro_sql = " Campo Cód Lan nao Informado.";
         $this->erro_campo = "c69_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c69_codhist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c69_codhist"])){
       $sql  .= $virgula." c69_codhist = $this->c69_codhist ";
       $virgula = ",";
       if(trim($this->c69_codhist) == null ){
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "c69_codhist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c69_credito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c69_credito"])){
       $sql  .= $virgula." c69_credito = $this->c69_credito ";
       $virgula = ",";
       if(trim($this->c69_credito) == null ){
         $this->erro_sql = " Campo Conta Credito nao Informado.";
         $this->erro_campo = "c69_credito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c69_debito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c69_debito"])){
       $sql  .= $virgula." c69_debito = $this->c69_debito ";
       $virgula = ",";
       if(trim($this->c69_debito) == null ){
         $this->erro_sql = " Campo Conta Debito nao Informado.";
         $this->erro_campo = "c69_debito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c69_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c69_valor"])){
       $sql  .= $virgula." c69_valor = $this->c69_valor ";
       $virgula = ",";
       if(trim($this->c69_valor) == null ){
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "c69_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c69_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c69_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c69_data_dia"] !="") ){
       $sql  .= $virgula." c69_data = '$this->c69_data' ";
       $virgula = ",";
       if(trim($this->c69_data) == null ){
         $this->erro_sql = " Campo Data Lanc nao Informado.";
         $this->erro_campo = "c69_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["c69_data_dia"])){
         $sql  .= $virgula." c69_data = null ";
         $virgula = ",";
         if(trim($this->c69_data) == null ){
           $this->erro_sql = " Campo Data Lanc nao Informado.";
           $this->erro_campo = "c69_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($c69_sequen!=null){
       $sql .= " c69_sequen = $this->c69_sequen";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c69_sequen));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5234,'$this->c69_sequen','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c69_sequen"]))
             $resac = db_query("insert into db_acount values($acount,790,5234,'".AddSlashes(pg_result($resaco,$conresaco,'c69_sequen'))."','$this->c69_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c69_anousu"]))
             $resac = db_query("insert into db_acount values($acount,790,5235,'".AddSlashes(pg_result($resaco,$conresaco,'c69_anousu'))."','$this->c69_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c69_codlan"]))
             $resac = db_query("insert into db_acount values($acount,790,5236,'".AddSlashes(pg_result($resaco,$conresaco,'c69_codlan'))."','$this->c69_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c69_codhist"]))
             $resac = db_query("insert into db_acount values($acount,790,5237,'".AddSlashes(pg_result($resaco,$conresaco,'c69_codhist'))."','$this->c69_codhist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c69_credito"]))
             $resac = db_query("insert into db_acount values($acount,790,5238,'".AddSlashes(pg_result($resaco,$conresaco,'c69_credito'))."','$this->c69_credito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c69_debito"]))
             $resac = db_query("insert into db_acount values($acount,790,5243,'".AddSlashes(pg_result($resaco,$conresaco,'c69_debito'))."','$this->c69_debito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c69_valor"]))
             $resac = db_query("insert into db_acount values($acount,790,5244,'".AddSlashes(pg_result($resaco,$conresaco,'c69_valor'))."','$this->c69_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c69_data"]))
             $resac = db_query("insert into db_acount values($acount,790,5245,'".AddSlashes(pg_result($resaco,$conresaco,'c69_data'))."','$this->c69_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores lançamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c69_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores lançamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c69_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c69_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c69_sequen=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($c69_sequen));
       }else{
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5234,'$c69_sequen','E')");
           $resac = db_query("insert into db_acount values($acount,790,5234,'','".AddSlashes(pg_result($resaco,$iresaco,'c69_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,790,5235,'','".AddSlashes(pg_result($resaco,$iresaco,'c69_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,790,5236,'','".AddSlashes(pg_result($resaco,$iresaco,'c69_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,790,5237,'','".AddSlashes(pg_result($resaco,$iresaco,'c69_codhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,790,5238,'','".AddSlashes(pg_result($resaco,$iresaco,'c69_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,790,5243,'','".AddSlashes(pg_result($resaco,$iresaco,'c69_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,790,5244,'','".AddSlashes(pg_result($resaco,$iresaco,'c69_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,790,5245,'','".AddSlashes(pg_result($resaco,$iresaco,'c69_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from conlancamval
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c69_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c69_sequen = $c69_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores lançamentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c69_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores lançamentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c69_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c69_sequen;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancamval";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function excluir_codlan($codlan){
    // pesquisa todos os sequenc do codlan informado
    $res  = $this->sql_record($this->sql_query_file(null,"c69_sequen",null,"c69_codlan=$codlan"));
    $rows = $this->numrows;
    if ($rows  > 0){
       for($x=0;$x< $rows;$x++){
           $seq = pg_result($res,$x,0);
           $this->excluir($seq);
       }
    }
    return true;
  }
   function sql_query ( $c69_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamval ";
     $sql .= "      left join conhist            on c50_codhist = c69_codhist ";
     $sql .= "      inner join conlancam         on c70_codlan  = c69_codlan ";
     $sql .= "      left  join conlancaminstit   on c02_codlan  = c70_codlan ";
     $sql .= "      left outer join conlancamdig on c78_codlan  = c70_codlan ";
     $sql2 = "";
     if($dbwhere==""){
       if($c69_sequen!=null ){
         $sql2 .= " where conlancamval.c69_sequen = $c69_sequen ";
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


   function sql_query_contacorrentedetalhe( $c69_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamval ";
     $sql .= " inner join contacorrentedetalheconlancamval on c69_sequen = c28_conlancamval";
     $sql .= " inner join contacorrentedetalhe on c28_contacorrentedetalhe = c19_sequencial";

     $sql2 = "";
     if($dbwhere==""){
       if($c69_sequen!=null ){
         $sql2 .= " where conlancamval.c69_sequen = $c69_sequen ";
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

   function sql_query_file ( $c69_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamval ";
     $sql2 = "";
     if($dbwhere==""){
       if($c69_sequen!=null ){
         $sql2 .= " where conlancamval.c69_sequen = $c69_sequen ";
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

  /**
   * @param  string $sCampos
   * @param  string $sOrdem
   * @param  string $sWhere
   * @return string
   */
  public function sql_query_contacorrentedetalhe_tce($sCampos = '*', $sTipo = 'D', $sOrdem = null, $sWhere = null) {

    $sCampoComparar = $sTipo == 'D' ? 'c69_debito' : 'c69_credito';

    $sSql  = " select {$sCampos}                                                                   \n";
    $sSql .= "   from conlancamval                                                                 \n";
    $sSql .= "        left join contacorrentedetalheconlancamval on c69_sequen = c28_conlancamval \n";
    $sSql .= "                                      and  c28_tipo = '{$sTipo}' \n";
    $sSql .= "        left join contacorrentedetalhe on c28_contacorrentedetalhe = c19_sequencial \n";
    $sSql .= "        inner join conlancaminstit on c02_codlan = c69_codlan                        \n";
    $sSql .= "        inner join conlancamdoc on c71_codlan   = c69_codlan                           \n";
    $sSql .= "        inner join conplanoreduz  on c61_reduz  = {$sCampoComparar}                     \n";
    $sSql .= "                                 and c61_instit = c02_instit                         \n";
    $sSql .= "                                 and c61_anousu = c69_anousu                         \n";
    $sSql .= "        inner join conplano  on c60_codcon = c61_codcon                              \n";
    $sSql .= "                            and c60_anousu = c61_anousu                              \n";
    $sSql .= "        left  join vinculoeventoscontabeis on c115_conhistdocestorno = c71_coddoc    \n";

    $sSql .= "        left  join conlancamemp on c75_codlan = c71_codlan                           \n";
    $sSql .= "        left  join conlancamcorrente on c86_conlancam = c71_codlan                   \n";
    $sSql .= "        left  join conlancamcorgrupocorrente on c23_conlancam = c71_codlan           \n";
    $sSql .= "        left  join corgrupocorrente on k105_sequencial = c23_corgrupocorrente        \n";
    $sSql .= "        left  join corrente  on c86_id = corrente.k12_id                             \n";
    $sSql .= "                            and c86_data = corrente.k12_data                         \n";
    $sSql .= "                            and c86_autent = corrente.k12_autent                     \n";

    $sSql .= "        left  join conlancamslip on c84_conlancam = c71_codlan                       \n";
    $sSql .= "        left  join empageslip on c84_slip = e89_codigo                               \n";
    $sSql .= "        left  join empagemov on e81_codmov = e89_codmov                              \n";
    $sSql .= "        left  join empageconfche on e91_codmov = e89_codmov                          \n";

    $sSql .= "        left  join corplacaixa  on k82_id = corrente.k12_id                          \n";
    $sSql .= "                               and k82_data = corrente.k12_data                      \n";
    $sSql .= "                               and k82_autent = corrente.k12_autent                  \n";
    $sSql .= "        left  join placaixarec on k82_seqpla = k81_seqpla                            \n";

    $sSql .= "        left  join coremp  on coremp.k12_id = k105_id                                \n";
    $sSql .= "                          and coremp.k12_data = k105_data                            \n";
    $sSql .= "                          and coremp.k12_autent = k105_autent                        \n";


    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }

    return $sSql;
  }


  public function sql_query_conta_documento($sCampos = "*", $sWhere = null, $sOrder = null) {

    $sql  = " select {$sCampos} ";
    $sql .= "   from conlancamval ";
    $sql .= "        inner join conlancamdoc on c71_codlan = c69_codlan ";

    if (!empty($sWhere)) {
      $sql .= " where {$sWhere} ";
    }

    if (!empty($sOrder)) {
      $sql .= " order by {$sOrder} ";
    }
    return $sql;
  }

}
