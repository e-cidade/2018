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
//CLASSE DA ENTIDADE pcorcam
class cl_pcorcam {
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
   var $pc20_codorc = 0;
   var $pc20_dtate_dia = null;
   var $pc20_dtate_mes = null;
   var $pc20_dtate_ano = null;
   var $pc20_dtate = null;
   var $pc20_hrate = null;
   var $pc20_obs = null;
   var $pc20_prazoentrega = 0;
   var $pc20_validadeorcamento = 0;
   var $pc20_cotacaoprevia = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 pc20_codorc = int4 = Código do orçamento
                 pc20_dtate = date = Prazo limite para entrega do orçamento
                 pc20_hrate = char(5) = Hora limite para entrega do orçamento
                 pc20_obs = text = Observação
                 pc20_prazoentrega = int4 = Prazo de Entrega de Produto
                 pc20_validadeorcamento = int4 = Validade do Orcamento
                 pc20_cotacaoprevia = int4 = Cotação Prévia
                 ";
   //funcao construtor da classe
   function cl_pcorcam() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcam");
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
       $this->pc20_codorc = ($this->pc20_codorc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc20_codorc"]:$this->pc20_codorc);
       if($this->pc20_dtate == ""){
         $this->pc20_dtate_dia = ($this->pc20_dtate_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc20_dtate_dia"]:$this->pc20_dtate_dia);
         $this->pc20_dtate_mes = ($this->pc20_dtate_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc20_dtate_mes"]:$this->pc20_dtate_mes);
         $this->pc20_dtate_ano = ($this->pc20_dtate_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc20_dtate_ano"]:$this->pc20_dtate_ano);
         if($this->pc20_dtate_dia != ""){
            $this->pc20_dtate = $this->pc20_dtate_ano."-".$this->pc20_dtate_mes."-".$this->pc20_dtate_dia;
         }
       }
       $this->pc20_hrate = ($this->pc20_hrate == ""?@$GLOBALS["HTTP_POST_VARS"]["pc20_hrate"]:$this->pc20_hrate);
       $this->pc20_obs = ($this->pc20_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["pc20_obs"]:$this->pc20_obs);
       $this->pc20_prazoentrega = ($this->pc20_prazoentrega == ""?@$GLOBALS["HTTP_POST_VARS"]["pc20_prazoentrega"]:$this->pc20_prazoentrega);
       $this->pc20_validadeorcamento = ($this->pc20_validadeorcamento == ""?@$GLOBALS["HTTP_POST_VARS"]["pc20_validadeorcamento"]:$this->pc20_validadeorcamento);
       $this->pc20_cotacaoprevia = ($this->pc20_cotacaoprevia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc20_cotacaoprevia"]:$this->pc20_cotacaoprevia);
     }else{
       $this->pc20_codorc = ($this->pc20_codorc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc20_codorc"]:$this->pc20_codorc);
     }
   }
   // funcao para inclusao
   function incluir ($pc20_codorc){
      $this->atualizacampos();
     if($this->pc20_dtate == null ){
       $this->erro_sql = " Campo Prazo limite para entrega do orçamento nao Informado.";
       $this->erro_campo = "pc20_dtate_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc20_hrate == null ){
       $this->erro_sql = " Campo Hora limite para entrega do orçamento nao Informado.";
       $this->erro_campo = "pc20_hrate";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc20_prazoentrega == null ){
       $this->pc20_prazoentrega = "0";
     }
     if($this->pc20_validadeorcamento == null ){
       $this->pc20_validadeorcamento = "0";
     }
     if($this->pc20_cotacaoprevia == null ){
       $this->pc20_cotacaoprevia = "0";
     }
     if($pc20_codorc == "" || $pc20_codorc == null ){
       $result = db_query("select nextval('pcorcam_pc20_codorc_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcorcam_pc20_codorc_seq do campo: pc20_codorc";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->pc20_codorc = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from pcorcam_pc20_codorc_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc20_codorc)){
         $this->erro_sql = " Campo pc20_codorc maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc20_codorc = $pc20_codorc;
       }
     }
     if(($this->pc20_codorc == null) || ($this->pc20_codorc == "") ){
       $this->erro_sql = " Campo pc20_codorc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcam(
                                       pc20_codorc
                                      ,pc20_dtate
                                      ,pc20_hrate
                                      ,pc20_obs
                                      ,pc20_prazoentrega
                                      ,pc20_validadeorcamento
                                      ,pc20_cotacaoprevia
                       )
                values (
                                $this->pc20_codorc
                               ,".($this->pc20_dtate == "null" || $this->pc20_dtate == ""?"null":"'".$this->pc20_dtate."'")."
                               ,'$this->pc20_hrate'
                               ,'$this->pc20_obs'
                               ,$this->pc20_prazoentrega
                               ,$this->pc20_validadeorcamento
                               ,$this->pc20_cotacaoprevia
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Orçamentos de compras ($this->pc20_codorc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Orçamentos de compras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Orçamentos de compras ($this->pc20_codorc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc20_codorc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc20_codorc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5509,'$this->pc20_codorc','I')");
       $resac = db_query("insert into db_acount values($acount,857,5509,'','".AddSlashes(pg_result($resaco,0,'pc20_codorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,857,5510,'','".AddSlashes(pg_result($resaco,0,'pc20_dtate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,857,5511,'','".AddSlashes(pg_result($resaco,0,'pc20_hrate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,857,9439,'','".AddSlashes(pg_result($resaco,0,'pc20_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,857,10962,'','".AddSlashes(pg_result($resaco,0,'pc20_prazoentrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,857,10963,'','".AddSlashes(pg_result($resaco,0,'pc20_validadeorcamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,857,10964,'','".AddSlashes(pg_result($resaco,0,'pc20_cotacaoprevia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($pc20_codorc=null) {
      $this->atualizacampos();
     $sql = " update pcorcam set ";
     $virgula = "";
     if(trim($this->pc20_codorc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc20_codorc"])){
       $sql  .= $virgula." pc20_codorc = $this->pc20_codorc ";
       $virgula = ",";
       if(trim($this->pc20_codorc) == null ){
         $this->erro_sql = " Campo Código do orçamento nao Informado.";
         $this->erro_campo = "pc20_codorc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc20_dtate)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc20_dtate_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc20_dtate_dia"] !="") ){
       $sql  .= $virgula." pc20_dtate = '$this->pc20_dtate' ";
       $virgula = ",";
       if(trim($this->pc20_dtate) == null ){
         $this->erro_sql = " Campo Prazo limite para entrega do orçamento nao Informado.";
         $this->erro_campo = "pc20_dtate_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc20_dtate_dia"])){
         $sql  .= $virgula." pc20_dtate = null ";
         $virgula = ",";
         if(trim($this->pc20_dtate) == null ){
           $this->erro_sql = " Campo Prazo limite para entrega do orçamento nao Informado.";
           $this->erro_campo = "pc20_dtate_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc20_hrate)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc20_hrate"])){
       $sql  .= $virgula." pc20_hrate = '$this->pc20_hrate' ";
       $virgula = ",";
       if(trim($this->pc20_hrate) == null ){
         $this->erro_sql = " Campo Hora limite para entrega do orçamento nao Informado.";
         $this->erro_campo = "pc20_hrate";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc20_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc20_obs"])){
       $sql  .= $virgula." pc20_obs = '$this->pc20_obs' ";
       $virgula = ",";
     }
     if(trim($this->pc20_prazoentrega)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc20_prazoentrega"])){
        if(trim($this->pc20_prazoentrega)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc20_prazoentrega"])){
           $this->pc20_prazoentrega = "0" ;
        }
       $sql  .= $virgula." pc20_prazoentrega = $this->pc20_prazoentrega ";
       $virgula = ",";
     }
     if(trim($this->pc20_validadeorcamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc20_validadeorcamento"])){
        if(trim($this->pc20_validadeorcamento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc20_validadeorcamento"])){
           $this->pc20_validadeorcamento = "0" ;
        }
       $sql  .= $virgula." pc20_validadeorcamento = $this->pc20_validadeorcamento ";
       $virgula = ",";
     }
     if(trim($this->pc20_cotacaoprevia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc20_cotacaoprevia"])){
        if(trim($this->pc20_cotacaoprevia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc20_cotacaoprevia"])){
           $this->pc20_cotacaoprevia = "0" ;
        }
       $sql  .= $virgula." pc20_cotacaoprevia = $this->pc20_cotacaoprevia ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc20_codorc!=null){
       $sql .= " pc20_codorc = $this->pc20_codorc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc20_codorc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5509,'$this->pc20_codorc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc20_codorc"]))
           $resac = db_query("insert into db_acount values($acount,857,5509,'".AddSlashes(pg_result($resaco,$conresaco,'pc20_codorc'))."','$this->pc20_codorc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc20_dtate"]))
           $resac = db_query("insert into db_acount values($acount,857,5510,'".AddSlashes(pg_result($resaco,$conresaco,'pc20_dtate'))."','$this->pc20_dtate',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc20_hrate"]))
           $resac = db_query("insert into db_acount values($acount,857,5511,'".AddSlashes(pg_result($resaco,$conresaco,'pc20_hrate'))."','$this->pc20_hrate',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc20_obs"]))
           $resac = db_query("insert into db_acount values($acount,857,9439,'".AddSlashes(pg_result($resaco,$conresaco,'pc20_obs'))."','$this->pc20_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc20_prazoentrega"]))
           $resac = db_query("insert into db_acount values($acount,857,10962,'".AddSlashes(pg_result($resaco,$conresaco,'pc20_prazoentrega'))."','$this->pc20_prazoentrega',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc20_validadeorcamento"]))
           $resac = db_query("insert into db_acount values($acount,857,10963,'".AddSlashes(pg_result($resaco,$conresaco,'pc20_validadeorcamento'))."','$this->pc20_validadeorcamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc20_cotacaoprevia"]))
           $resac = db_query("insert into db_acount values($acount,857,10964,'".AddSlashes(pg_result($resaco,$conresaco,'pc20_cotacaoprevia'))."','$this->pc20_cotacaoprevia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Orçamentos de compras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc20_codorc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Orçamentos de compras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc20_codorc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc20_codorc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($pc20_codorc=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc20_codorc));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5509,'$pc20_codorc','E')");
         $resac = db_query("insert into db_acount values($acount,857,5509,'','".AddSlashes(pg_result($resaco,$iresaco,'pc20_codorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,857,5510,'','".AddSlashes(pg_result($resaco,$iresaco,'pc20_dtate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,857,5511,'','".AddSlashes(pg_result($resaco,$iresaco,'pc20_hrate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,857,9439,'','".AddSlashes(pg_result($resaco,$iresaco,'pc20_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,857,10962,'','".AddSlashes(pg_result($resaco,$iresaco,'pc20_prazoentrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,857,10963,'','".AddSlashes(pg_result($resaco,$iresaco,'pc20_validadeorcamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,857,10964,'','".AddSlashes(pg_result($resaco,$iresaco,'pc20_cotacaoprevia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcorcam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc20_codorc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc20_codorc = $pc20_codorc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Orçamentos de compras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc20_codorc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Orçamentos de compras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc20_codorc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc20_codorc;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc20_codorc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcam ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc20_codorc!=null ){
         $sql2 .= " where pcorcam.pc20_codorc = $pc20_codorc ";
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
   function sql_query_file ( $pc20_codorc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcam ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc20_codorc!=null ){
         $sql2 .= " where pcorcam.pc20_codorc = $pc20_codorc ";
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
   function sql_query_gercons ( $pc20_codorc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcam ";
     $sql .= "      inner join pcorcamitem on pcorcamitem.pc22_codorc = pcorcam.pc20_codorc ";
     $sql .= "      left  join pcorcamforne on pcorcamforne.pc21_codorc = pcorcam.pc20_codorc ";
     $sql .= "      left  join cgm on cgm.z01_numcgm = pcorcamforne.pc21_numcgm ";
     $sql .= "      inner join pcorcamitemsol on pcorcamitemsol.pc29_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql .= "      inner join solicitem on solicitem.pc11_codigo= pcorcamitemsol.pc29_solicitem ";
     $sql .= "      left  join pcdotac on pc13_codigo=solicitem.pc11_codigo ";
     $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem= solicitem.pc11_codigo ";
     $sql .= "      left  join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
     $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql .= "      left  join solicitemele on solicitemele.pc18_solicitem= solicitem.pc11_codigo ";
     $sql .= "      left  join solicitemunid on solicitemunid.pc17_codigo= solicitem.pc11_codigo ";
     $sql .= "      left  join matunid on matunid.m61_codmatunid= solicitemunid.pc17_unid ";
     $sql .= "      left  join pcorcamjulg on pcorcamjulg.pc24_orcamforne = pcorcamforne.pc21_orcamforne
                           and pcorcamjulg.pc24_orcamitem=pcorcamitem.pc22_orcamitem ";
     $sql .= "      left  join pcorcamval on pcorcamval.pc23_orcamforne=pcorcamforne.pc21_orcamforne
                           and pcorcamval.pc23_orcamitem=pcorcamitem.pc22_orcamitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc20_codorc!=null ){
         $sql2 .= " where pcorcam.pc20_codorc = $pc20_codorc ";
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
   function sql_query_gerconspc ( $pc20_codorc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcam ";
     $sql .= "      inner join pcorcamitem on pcorcamitem.pc22_codorc = pcorcam.pc20_codorc ";
     $sql .= "      left  join pcorcamforne on pcorcamforne.pc21_codorc = pcorcam.pc20_codorc ";
     $sql .= "      left  join cgm on cgm.z01_numcgm = pcorcamforne.pc21_numcgm ";
     $sql .= "      inner join pcorcamitemproc on pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem ";
     $sql .= "      inner join pcprocitem on pcprocitem.pc81_codprocitem= pcorcamitemproc.pc31_pcprocitem ";
     $sql .= "      inner join solicitem on solicitem.pc11_codigo= pcprocitem.pc81_solicitem ";
     $sql .= "      inner join pcdotac on pc13_codigo=solicitem.pc11_codigo ";
     $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem= solicitem.pc11_codigo ";
     $sql .= "      left  join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
     $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql .= "      left  join solicitemele on solicitemele.pc18_solicitem= solicitem.pc11_codigo ";
     $sql .= "      left  join solicitemunid on solicitemunid.pc17_codigo= solicitem.pc11_codigo ";
     $sql .= "      left  join matunid on matunid.m61_codmatunid= solicitemunid.pc17_unid ";
     $sql .= "      left  join pcorcamjulg on pcorcamjulg.pc24_orcamforne = pcorcamforne.pc21_orcamforne
                           and pcorcamjulg.pc24_orcamitem=pcorcamitem.pc22_orcamitem ";
     $sql .= "      left  join pcorcamval on pcorcamval.pc23_orcamforne=pcorcamforne.pc21_orcamforne
                           and pcorcamval.pc23_orcamitem=pcorcamitem.pc22_orcamitem ";
    if($dbwhere==""){
       if($pc20_codorc!=null ){
         $sql2 .= " where pcorcam.pc20_codorc = $pc20_codorc ";
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
   function sql_query_pcmaterproc ( $pc22_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitem ";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      inner join pcorcamitemproc  on  pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = pcorcamitemproc.pc31_pcprocitem";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql2 = "";
     if($dbwhere==""){
       if($pc22_orcamitem!=null ){
         $sql2 .= " where pcorcamitem.pc22_orcamitem = $pc22_orcamitem ";
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
   function sql_query_pcmatersol ( $pc22_orcamitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitem ";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      inner join pcorcamitemsol  on  pcorcamitemsol.pc29_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcorcamitemsol.pc29_solicitem";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql2 = "";
     if($dbwhere==""){
       if($pc22_orcamitem!=null ){
         $sql2 .= " where pcorcamitem.pc22_orcamitem = $pc22_orcamitem ";
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
   function sql_query_prazo ( $pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){
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

     $sql .= " from pcprocitem ";
     $sql .= "  inner join pcorcamitemsol on pc81_solicitem=pc29_solicitem     ";
     $sql .= "  inner join pcorcamitem on pc22_orcamitem= pc29_orcamitem    ";
     $sql .= "  inner join pcorcam on pc20_codorc=pc22_codorc    ";
     $sql .= "  inner join solicitem on pc11_codigo=pc29_solicitem    ";
     $sql .= "  inner join solicita on pc10_numero=pc11_numero    ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc80_codproc!=null ){
         $sql2 .= " where pc81_codproc= $pc80_codproc ";
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
   function sql_query_soldepto ( $pc20_codorc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcam ";
     $sql .= "      inner join pcorcamitem     on pcorcamitem.pc22_codorc = pcorcam.pc20_codorc";
     $sql .= "      inner join pcorcamitemproc on pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql .= "      inner join pcprocitem      on pcprocitem.pc81_codprocitem = pcorcamitemproc.pc31_pcprocitem";
     $sql .= "      inner join solicitem       on solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita        on solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_depart       on db_depart.coddepto  = solicita.pc10_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($pc20_codorc!=null ){
         $sql2 .= " where pcorcam.pc20_codorc = $pc20_codorc ";
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
   function sql_query_solproc ( $pc20_codorc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcam ";
     $sql .= "      left  join pcorcamitem     on pcorcamitem.pc22_codorc = pcorcam.pc20_codorc";
     $sql .= "      left  join pcorcamval      on pcorcamitem.pc22_orcamitem = pcorcamval.pc23_orcamitem";
     $sql .= "      left  join pcorcamitemsol  on pcorcamitemsol.pc29_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql .= "      left  join solicitem a     on a.pc11_codigo = pcorcamitemsol.pc29_solicitem";
     $sql .= "      left  join solicita  c     on c.pc10_numero = a.pc11_numero";
     $sql .= "      left  join db_depart e     on c.pc10_depto  = e.coddepto";
     $sql .= "      left  join pcorcamitemproc on pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem";
     $sql .= "      left  join pcprocitem      on pcprocitem.pc81_codprocitem = pcorcamitemproc.pc31_pcprocitem";
     $sql .= "      left  join pcproc          on pcprocitem.pc81_codproc     = pcproc.pc80_codproc";
     $sql .= "      left  join solicitem b     on b.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      left  join solicita  d     on d.pc10_numero = b.pc11_numero";
     $sql .= "      left  join db_depart f     on d.pc10_depto  = f.coddepto";
     $sql2 = "";
     if($dbwhere==""){
       if($pc20_codorc!=null ){
         $sql2 .= " where pcorcam.pc20_codorc = $pc20_codorc ";
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
   function sql_query_vallancados ( $pc20_codorc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcam ";
     $sql .= "      inner join pcorcamitem  on pcorcamitem.pc22_codorc     = pcorcam.pc20_codorc";
     $sql .= "      inner join pcorcamforne on pcorcamforne.pc21_codorc    = pcorcam.pc20_codorc";
     $sql .= "      inner join pcorcamval   on pcorcamval.pc23_orcamitem   = pcorcamitem.pc22_orcamitem
                                           and pcorcamval.pc23_orcamforne  = pcorcamforne.pc21_orcamforne ";
     $sql .= "       left join pcorcamitemproc on pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem ";
     $sql .= "       left join pcorcamjulg on pcorcamjulg.pc24_orcamitem   = pcorcamitem.pc22_orcamitem
                                           and pcorcamjulg.pc24_orcamforne = pcorcamforne.pc21_orcamforne ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc20_codorc!=null ){
         $sql2 .= " where pcorcam.pc20_codorc = $pc20_codorc ";
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
   function sql_query_proc ( $pc20_codorc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcam 																					   ";
     $sql .= "      left  join pcorcamitem     on pcorcamitem.pc22_codorc 		 = pcorcam.pc20_codorc			   ";
     $sql .= "      left  join pcorcamitemproc on pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem	   ";
     $sql .= "      left  join pcprocitem      on pcprocitem.pc81_codprocitem 	 = pcorcamitemproc.pc31_pcprocitem ";
     $sql .= "      left  join pcproc          on pcproc.pc80_codproc			 = pcprocitem.pc81_codproc     	   ";
     $sql .= "      left  join solicitem       on solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      left  join solicita        on solicita.pc10_numero = solicitem.pc11_numero";
     $sql2 = "";
     if($dbwhere==""){
       if($pc20_codorc!=null ){
         $sql2 .= " where pcorcam.pc20_codorc = $pc20_codorc ";
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

  function sql_query_valitemjulglic($pc20_codorc=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from pcorcam ";
     $sql .= " inner join pcorcamitem         on pcorcamitem.pc22_codorc        = pcorcam.pc20_codorc            ";
     $sql .= " inner join pcorcamforne        on pcorcamforne.pc21_codorc       = pcorcam.pc20_codorc            ";
     $sql .= " inner join pcorcamval          on pcorcamval.pc23_orcamitem      = pcorcamitem.pc22_orcamitem     ";
     $sql .= "                               and pcorcamval.pc23_orcamforne     = pcorcamforne.pc21_orcamforne   ";
     $sql .= " left join pcorcamitemlic       on pcorcamitemlic.pc26_orcamitem  = pcorcamitem.pc22_orcamitem     ";
     $sql .= " left join liclicitem           on pcorcamitemlic.pc26_liclicitem = liclicitem.l21_codigo          ";
     $sql .= " left join pcorcamjulg          on pcorcamjulg.pc24_orcamitem     = pcorcamitem.pc22_orcamitem     ";
     $sql .= "                               and pcorcamjulg.pc24_orcamforne    = pcorcamforne.pc21_orcamforne   ";
     $sql .= " left join pcprocitem           on liclicitem.l21_codpcprocitem   = pcprocitem.pc81_codprocitem    ";
     $sql .= " left join solicitem            on pcprocitem.pc81_solicitem      = solicitem.pc11_codigo   ";
     $sql .= " left join pcdotac              on solicitem.pc11_codigo          = pcdotac.pc13_codigo            ";
     $sql .= " left join pcdotaccontrapartida on pcdotac.pc13_sequencial        = pcdotaccontrapartida.pc19_pcdotac";
     $sql2 = "";
     if($dbwhere==""){
       if($pc20_codorc!=null ){
         $sql2 .= " where pcorcam.pc20_codorc = $pc20_codorc ";
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
  function sql_query_valitemjulgsol($pc20_codorc=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from pcorcam ";
     $sql .= " inner join pcorcamitem         on pcorcamitem.pc22_codorc        = pcorcam.pc20_codorc            ";
     $sql .= " inner join pcorcamforne        on pcorcamforne.pc21_codorc       = pcorcam.pc20_codorc            ";
     $sql .= " inner join pcorcamval          on pcorcamval.pc23_orcamitem      = pcorcamitem.pc22_orcamitem     ";
     $sql .= "                               and pcorcamval.pc23_orcamforne     = pcorcamforne.pc21_orcamforne   ";
     $sql .= " left join pcorcamitemsol       on pcorcamitemsol.pc29_orcamitem  = pcorcamitem.pc22_orcamitem     ";
     $sql .= " left join solicitem           on solicitem.pc11_codigo = pcorcamitemsol.pc29_solicitem";
     $sql .= " left join pcorcamjulg          on pcorcamjulg.pc24_orcamitem     = pcorcamitem.pc22_orcamitem     ";
     $sql .= "                               and pcorcamjulg.pc24_orcamforne    = pcorcamforne.pc21_orcamforne   ";
     $sql .= " left join pcprocitem           on pcprocitem.pc81_solicitem      = solicitem.pc11_codigo   ";
     $sql .= " left join pcdotac              on solicitem.pc11_codigo          = pcdotac.pc13_codigo            ";
     $sql .= " left join pcdotaccontrapartida on pcdotac.pc13_sequencial        = pcdotaccontrapartida.pc19_pcdotac";
     $sql2 = "";
     if($dbwhere==""){
       if($pc20_codorc!=null ){
         $sql2 .= " where pcorcam.pc20_codorc = $pc20_codorc ";
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


function sql_query_valor_item_julgado_processocompra($pc20_codorc=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from pcorcam ";
     $sql .= " inner join pcorcamitem         on pcorcamitem.pc22_codorc        = pcorcam.pc20_codorc            ";
     $sql .= " inner join pcorcamforne        on pcorcamforne.pc21_codorc       = pcorcam.pc20_codorc            ";
     $sql .= " inner join pcorcamval          on pcorcamval.pc23_orcamitem      = pcorcamitem.pc22_orcamitem     ";
     $sql .= "                               and pcorcamval.pc23_orcamforne     = pcorcamforne.pc21_orcamforne   ";
     $sql .= " left join pcorcamitemproc      on pcorcamitemproc.pc31_orcamitem  = pcorcamitem.pc22_orcamitem     ";
     $sql .= " left join pcorcamjulg          on pcorcamjulg.pc24_orcamitem     = pcorcamitem.pc22_orcamitem     ";
     $sql .= "                               and pcorcamjulg.pc24_orcamforne    = pcorcamforne.pc21_orcamforne   ";
     $sql .= " left join pcprocitem           on pcprocitem.pc81_codprocitem    = pcorcamitemproc.pc31_pcprocitem  ";
     $sql .= " left join solicitem            on solicitem.pc11_codigo          = pcprocitem.pc81_solicitem";
     $sql .= " left join pcdotac              on solicitem.pc11_codigo          = pcdotac.pc13_codigo            ";
     $sql .= " left join pcdotaccontrapartida on pcdotac.pc13_sequencial        = pcdotaccontrapartida.pc19_pcdotac";
     $sql2 = "";
     if($dbwhere==""){
       if($pc20_codorc!=null ){
         $sql2 .= " where pcorcam.pc20_codorc = $pc20_codorc ";
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