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

//MODULO: compras
//CLASSE DA ENTIDADE solicitaregistropreco
class cl_solicitaregistropreco {
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
   var $pc54_sequencial = 0;
   var $pc54_solicita = 0;
   var $pc54_datainicio_dia = null;
   var $pc54_datainicio_mes = null;
   var $pc54_datainicio_ano = null;
   var $pc54_datainicio = null;
   var $pc54_datatermino_dia = null;
   var $pc54_datatermino_mes = null;
   var $pc54_datatermino_ano = null;
   var $pc54_datatermino = null;
   var $pc54_liberado = 'f';
   var $pc54_formacontrole = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 pc54_sequencial = int4 = Código Sequencial
                 pc54_solicita = int4 = numero da solicitacao
                 pc54_datainicio = date = Data de Inicio
                 pc54_datatermino = date = Data do Termino
                 pc54_liberado = bool = Liberado
                 pc54_formacontrole = int4 = Forma de Controle
                 ";
   //funcao construtor da classe
   function cl_solicitaregistropreco() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solicitaregistropreco");
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
       $this->pc54_sequencial = ($this->pc54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc54_sequencial"]:$this->pc54_sequencial);
       $this->pc54_solicita = ($this->pc54_solicita == ""?@$GLOBALS["HTTP_POST_VARS"]["pc54_solicita"]:$this->pc54_solicita);
       if($this->pc54_datainicio == ""){
         $this->pc54_datainicio_dia = ($this->pc54_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc54_datainicio_dia"]:$this->pc54_datainicio_dia);
         $this->pc54_datainicio_mes = ($this->pc54_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc54_datainicio_mes"]:$this->pc54_datainicio_mes);
         $this->pc54_datainicio_ano = ($this->pc54_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc54_datainicio_ano"]:$this->pc54_datainicio_ano);
         if($this->pc54_datainicio_dia != ""){
            $this->pc54_datainicio = $this->pc54_datainicio_ano."-".$this->pc54_datainicio_mes."-".$this->pc54_datainicio_dia;
         }
       }
       if($this->pc54_datatermino == ""){
         $this->pc54_datatermino_dia = ($this->pc54_datatermino_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc54_datatermino_dia"]:$this->pc54_datatermino_dia);
         $this->pc54_datatermino_mes = ($this->pc54_datatermino_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc54_datatermino_mes"]:$this->pc54_datatermino_mes);
         $this->pc54_datatermino_ano = ($this->pc54_datatermino_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc54_datatermino_ano"]:$this->pc54_datatermino_ano);
         if($this->pc54_datatermino_dia != ""){
            $this->pc54_datatermino = $this->pc54_datatermino_ano."-".$this->pc54_datatermino_mes."-".$this->pc54_datatermino_dia;
         }
       }
       $this->pc54_liberado = ($this->pc54_liberado == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc54_liberado"]:$this->pc54_liberado);
       $this->pc54_formacontrole = ($this->pc54_formacontrole == ""?@$GLOBALS["HTTP_POST_VARS"]["pc54_formacontrole"]:$this->pc54_formacontrole);
     }else{
       $this->pc54_sequencial = ($this->pc54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc54_sequencial"]:$this->pc54_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc54_sequencial){
      $this->atualizacampos();
     if($this->pc54_solicita == null ){
       $this->erro_sql = " Campo numero da solicitacao não informado.";
       $this->erro_campo = "pc54_solicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc54_datainicio == null ){
       $this->erro_sql = " Campo Data de Inicio não informado.";
       $this->erro_campo = "pc54_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc54_datatermino == null ){
       $this->erro_sql = " Campo Data do Termino não informado.";
       $this->erro_campo = "pc54_datatermino_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc54_liberado == null ){
       $this->pc54_liberado = "f";
     }
     if($this->pc54_formacontrole == null ){
       $this->erro_sql = " Campo Forma de Controle não informado.";
       $this->erro_campo = "pc54_formacontrole";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc54_sequencial == "" || $pc54_sequencial == null ){
       $result = db_query("select nextval('solicitaregistropreco_pc54_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solicitaregistropreco_pc54_sequencial_seq do campo: pc54_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->pc54_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from solicitaregistropreco_pc54_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc54_sequencial)){
         $this->erro_sql = " Campo pc54_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc54_sequencial = $pc54_sequencial;
       }
     }
     if(($this->pc54_sequencial == null) || ($this->pc54_sequencial == "") ){
       $this->erro_sql = " Campo pc54_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solicitaregistropreco(
                                       pc54_sequencial
                                      ,pc54_solicita
                                      ,pc54_datainicio
                                      ,pc54_datatermino
                                      ,pc54_liberado
                                      ,pc54_formacontrole
                       )
                values (
                                $this->pc54_sequencial
                               ,$this->pc54_solicita
                               ,".($this->pc54_datainicio == "null" || $this->pc54_datainicio == ""?"null":"'".$this->pc54_datainicio."'")."
                               ,".($this->pc54_datatermino == "null" || $this->pc54_datatermino == ""?"null":"'".$this->pc54_datatermino."'")."
                               ,'$this->pc54_liberado'
                               ,$this->pc54_formacontrole
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados da Abertura de preços ($this->pc54_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados da Abertura de preços já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados da Abertura de preços ($this->pc54_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc54_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc54_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15206,'$this->pc54_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2679,15206,'','".AddSlashes(pg_result($resaco,0,'pc54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2679,15207,'','".AddSlashes(pg_result($resaco,0,'pc54_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2679,15208,'','".AddSlashes(pg_result($resaco,0,'pc54_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2679,15209,'','".AddSlashes(pg_result($resaco,0,'pc54_datatermino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2679,15210,'','".AddSlashes(pg_result($resaco,0,'pc54_liberado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2679,20853,'','".AddSlashes(pg_result($resaco,0,'pc54_formacontrole'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($pc54_sequencial=null) {
      $this->atualizacampos();
     $sql = " update solicitaregistropreco set ";
     $virgula = "";
     if(trim($this->pc54_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc54_sequencial"])){
       $sql  .= $virgula." pc54_sequencial = $this->pc54_sequencial ";
       $virgula = ",";
       if(trim($this->pc54_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "pc54_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc54_solicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc54_solicita"])){
       $sql  .= $virgula." pc54_solicita = $this->pc54_solicita ";
       $virgula = ",";
       if(trim($this->pc54_solicita) == null ){
         $this->erro_sql = " Campo numero da solicitacao não informado.";
         $this->erro_campo = "pc54_solicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc54_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc54_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc54_datainicio_dia"] !="") ){
       $sql  .= $virgula." pc54_datainicio = '$this->pc54_datainicio' ";
       $virgula = ",";
       if(trim($this->pc54_datainicio) == null ){
         $this->erro_sql = " Campo Data de Inicio não informado.";
         $this->erro_campo = "pc54_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc54_datainicio_dia"])){
         $sql  .= $virgula." pc54_datainicio = null ";
         $virgula = ",";
         if(trim($this->pc54_datainicio) == null ){
           $this->erro_sql = " Campo Data de Inicio não informado.";
           $this->erro_campo = "pc54_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc54_datatermino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc54_datatermino_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc54_datatermino_dia"] !="") ){
       $sql  .= $virgula." pc54_datatermino = '$this->pc54_datatermino' ";
       $virgula = ",";
       if(trim($this->pc54_datatermino) == null ){
         $this->erro_sql = " Campo Data do Termino não informado.";
         $this->erro_campo = "pc54_datatermino_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc54_datatermino_dia"])){
         $sql  .= $virgula." pc54_datatermino = null ";
         $virgula = ",";
         if(trim($this->pc54_datatermino) == null ){
           $this->erro_sql = " Campo Data do Termino não informado.";
           $this->erro_campo = "pc54_datatermino_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc54_liberado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc54_liberado"])){
       $sql  .= $virgula." pc54_liberado = '$this->pc54_liberado' ";
       $virgula = ",";
     }
     if(trim($this->pc54_formacontrole)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc54_formacontrole"])){
       $sql  .= $virgula." pc54_formacontrole = $this->pc54_formacontrole ";
       $virgula = ",";
       if(trim($this->pc54_formacontrole) == null ){
         $this->erro_sql = " Campo Forma de Controle não informado.";
         $this->erro_campo = "pc54_formacontrole";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc54_sequencial!=null){
       $sql .= " pc54_sequencial = $this->pc54_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc54_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,15206,'$this->pc54_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc54_sequencial"]) || $this->pc54_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2679,15206,'".AddSlashes(pg_result($resaco,$conresaco,'pc54_sequencial'))."','$this->pc54_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc54_solicita"]) || $this->pc54_solicita != "")
             $resac = db_query("insert into db_acount values($acount,2679,15207,'".AddSlashes(pg_result($resaco,$conresaco,'pc54_solicita'))."','$this->pc54_solicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc54_datainicio"]) || $this->pc54_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,2679,15208,'".AddSlashes(pg_result($resaco,$conresaco,'pc54_datainicio'))."','$this->pc54_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc54_datatermino"]) || $this->pc54_datatermino != "")
             $resac = db_query("insert into db_acount values($acount,2679,15209,'".AddSlashes(pg_result($resaco,$conresaco,'pc54_datatermino'))."','$this->pc54_datatermino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc54_liberado"]) || $this->pc54_liberado != "")
             $resac = db_query("insert into db_acount values($acount,2679,15210,'".AddSlashes(pg_result($resaco,$conresaco,'pc54_liberado'))."','$this->pc54_liberado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc54_formacontrole"]) || $this->pc54_formacontrole != "")
             $resac = db_query("insert into db_acount values($acount,2679,20853,'".AddSlashes(pg_result($resaco,$conresaco,'pc54_formacontrole'))."','$this->pc54_formacontrole',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados da Abertura de preços nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados da Abertura de preços nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($pc54_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($pc54_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,15206,'$pc54_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2679,15206,'','".AddSlashes(pg_result($resaco,$iresaco,'pc54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2679,15207,'','".AddSlashes(pg_result($resaco,$iresaco,'pc54_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2679,15208,'','".AddSlashes(pg_result($resaco,$iresaco,'pc54_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2679,15209,'','".AddSlashes(pg_result($resaco,$iresaco,'pc54_datatermino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2679,15210,'','".AddSlashes(pg_result($resaco,$iresaco,'pc54_liberado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2679,20853,'','".AddSlashes(pg_result($resaco,$iresaco,'pc54_formacontrole'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from solicitaregistropreco
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($pc54_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " pc54_sequencial = $pc54_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados da Abertura de preços nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados da Abertura de preços nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc54_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:solicitaregistropreco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($pc54_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from solicitaregistropreco ";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitaregistropreco.pc54_solicita";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join solicitacaotipo  on  solicitacaotipo.pc52_sequencial = solicita.pc10_solicitacaotipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc54_sequencial)) {
         $sql2 .= " where solicitaregistropreco.pc54_sequencial = $pc54_sequencial ";
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
   public function sql_query_file ($pc54_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from solicitaregistropreco ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc54_sequencial)){
         $sql2 .= " where solicitaregistropreco.pc54_sequencial = $pc54_sequencial ";
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

   function sql_query_solicitaanulada ( $pc54_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitaregistropreco ";
     $sql .= "      inner join solicita         on  solicita.pc10_numero = solicitaregistropreco.pc54_solicita ";
     $sql .= "      inner join db_config        on  db_config.codigo = solicita.pc10_instit      ";
     $sql .= "      inner join db_usuarios      on  db_usuarios.id_usuario = solicita.pc10_login ";
     $sql .= "      inner join db_depart        on  db_depart.coddepto = solicita.pc10_depto     ";
     $sql .= "      inner join solicitacaotipo  on  solicitacaotipo.pc52_sequencial = solicita.pc10_solicitacaotipo ";
     $sql .= "      left  join solicitaanulada  on  solicitaanulada.pc67_solicita = solicita.pc10_numero ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc54_sequencial!=null ){
         $sql2 .= " where solicitaregistropreco.pc54_sequencial = $pc54_sequencial ";
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
   function sql_query_origem ( $pc54_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitaregistropreco ";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitaregistropreco.pc54_solicita";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join solicitacaotipo  on  solicitacaotipo.pc52_sequencial = solicita.pc10_solicitacaotipo";
     $sql .= "      inner join solicitavinculo  on  solicita.pc10_numero = solicitavinculo.pc53_solicitafilho";
     $sql .= "      left  join solicitaanulada  on  solicitaanulada.pc67_solicita = solicita.pc10_numero ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc54_sequencial!=null ){
         $sql2 .= " where solicitaregistropreco.pc54_sequencial = $pc54_sequencial ";
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

  function sql_query_registro_licitacao ( $pc54_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitaregistropreco ";
    $sql .= "      inner join solicita          on solicita.pc10_numero  = solicitaregistropreco.pc54_solicita ";
    $sql .= "      inner join solicitem         on solicita.pc10_numero  = solicitem.pc11_numero ";
    $sql .= "      inner join solicitempcmater  on solicitempcmater.pc16_solicitem  = solicitem.pc11_codigo ";
    $sql .= "      inner join pcprocitem        on solicitem.pc11_codigo = pcprocitem.pc81_solicitem  ";
    $sql .= "      inner join liclicitem        on pcprocitem.pc81_codprocitem =  l21_codpcprocitem  ";
    $sql .= "      inner join liclicita         on l21_codliclicita = l20_codigo  ";
    $sql .= "      left  join pcorcamitemlic    on pcorcamitemlic.pc26_liclicitem = liclicitem.l21_codigo ";
    $sql .= "      left  join pcorcamjulg       on pcorcamjulg.pc24_orcamitem = pcorcamitemlic.pc26_orcamitem ";
    $sql .= "      left  join pcorcamforne      on pcorcamforne.pc21_orcamforne = pcorcamjulg.pc24_orcamforne ";
    $sql .= "      inner join db_config         on db_config.codigo = solicita.pc10_instit      ";
    $sql .= "      inner join db_usuarios       on db_usuarios.id_usuario = solicita.pc10_login ";
    $sql .= "      inner join db_depart         on db_depart.coddepto = solicita.pc10_depto     ";
    $sql .= "      inner join solicitacaotipo   on solicitacaotipo.pc52_sequencial = solicita.pc10_solicitacaotipo ";
    $sql .= "      left  join solicitaanulada   on solicitaanulada.pc67_solicita = solicita.pc10_numero ";
    $sql2 = "";
    if($dbwhere==""){
      if($pc54_sequencial!=null ){
        $sql2 .= " where solicitaregistropreco.pc54_sequencial = $pc54_sequencial ";
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
