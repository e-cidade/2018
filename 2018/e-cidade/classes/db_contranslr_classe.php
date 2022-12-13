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
//CLASSE DA ENTIDADE contranslr
class cl_contranslr {
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
   var $c47_seqtranslr = 0;
   var $c47_seqtranslan = 0;
   var $c47_debito = 0;
   var $c47_credito = 0;
   var $c47_obs = null;
   var $c47_ref = 0;
   var $c47_anousu = 0;
   var $c47_instit = 0;
   var $c47_compara = 0;
   var $c47_tiporesto = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c47_seqtranslr = int4 = Sequ. Translr
                 c47_seqtranslan = int4 = Sequência
                 c47_debito = int4 = Débito
                 c47_credito = int4 = Crédito
                 c47_obs = text = Observações
                 c47_ref = int4 = Campo referência
                 c47_anousu = int4 = Ano
                 c47_instit = int4 = Instituição
                 c47_compara = int4 = Compara
                 c47_tiporesto = int4 = tipo resto
                 ";
   //funcao construtor da classe
   function cl_contranslr() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contranslr");
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
       $this->c47_seqtranslr = ($this->c47_seqtranslr == ""?@$GLOBALS["HTTP_POST_VARS"]["c47_seqtranslr"]:$this->c47_seqtranslr);
       $this->c47_seqtranslan = ($this->c47_seqtranslan == ""?@$GLOBALS["HTTP_POST_VARS"]["c47_seqtranslan"]:$this->c47_seqtranslan);
       $this->c47_debito = ($this->c47_debito == ""?@$GLOBALS["HTTP_POST_VARS"]["c47_debito"]:$this->c47_debito);
       $this->c47_credito = ($this->c47_credito == ""?@$GLOBALS["HTTP_POST_VARS"]["c47_credito"]:$this->c47_credito);
       $this->c47_obs = ($this->c47_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["c47_obs"]:$this->c47_obs);
       $this->c47_ref = ($this->c47_ref == ""?@$GLOBALS["HTTP_POST_VARS"]["c47_ref"]:$this->c47_ref);
       $this->c47_anousu = ($this->c47_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c47_anousu"]:$this->c47_anousu);
       $this->c47_instit = ($this->c47_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c47_instit"]:$this->c47_instit);
       $this->c47_compara = ($this->c47_compara == ""?@$GLOBALS["HTTP_POST_VARS"]["c47_compara"]:$this->c47_compara);
       $this->c47_tiporesto = ($this->c47_tiporesto == ""?@$GLOBALS["HTTP_POST_VARS"]["c47_tiporesto"]:$this->c47_tiporesto);
     }else{
       $this->c47_seqtranslr = ($this->c47_seqtranslr == ""?@$GLOBALS["HTTP_POST_VARS"]["c47_seqtranslr"]:$this->c47_seqtranslr);
     }
   }
   // funcao para inclusao
   function incluir ($c47_seqtranslr){
      $this->atualizacampos();
     if($this->c47_seqtranslan == null ){
       $this->erro_sql = " Campo Sequência nao Informado.";
       $this->erro_campo = "c47_seqtranslan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c47_debito == null ){
       $this->erro_sql = " Campo Débito nao Informado.";
       $this->erro_campo = "c47_debito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c47_credito == null ){
       $this->erro_sql = " Campo Crédito nao Informado.";
       $this->erro_campo = "c47_credito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c47_ref == null ){
       $this->c47_ref = "0";
     }
     if($this->c47_anousu == null ){
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "c47_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c47_instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "c47_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c47_compara == null ){
       $this->erro_sql = " Campo Compara nao Informado.";
       $this->erro_campo = "c47_compara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c47_tiporesto == null ){
       $this->erro_sql = " Campo tipo resto nao Informado.";
       $this->erro_campo = "c47_tiporesto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c47_seqtranslr == "" || $c47_seqtranslr == null ){
       $result = db_query("select nextval('contranslr_c47_seqtranslr_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: contranslr_c47_seqtranslr_seq do campo: c47_seqtranslr";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c47_seqtranslr = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from contranslr_c47_seqtranslr_seq");
       if(($result != false) && (pg_result($result,0,0) < $c47_seqtranslr)){
         $this->erro_sql = " Campo c47_seqtranslr maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c47_seqtranslr = $c47_seqtranslr;
       }
     }
     if(($this->c47_seqtranslr == null) || ($this->c47_seqtranslr == "") ){
       $this->erro_sql = " Campo c47_seqtranslr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contranslr(
                                       c47_seqtranslr
                                      ,c47_seqtranslan
                                      ,c47_debito
                                      ,c47_credito
                                      ,c47_obs
                                      ,c47_ref
                                      ,c47_anousu
                                      ,c47_instit
                                      ,c47_compara
                                      ,c47_tiporesto
                       )
                values (
                                $this->c47_seqtranslr
                               ,$this->c47_seqtranslan
                               ,$this->c47_debito
                               ,$this->c47_credito
                               ,'$this->c47_obs'
                               ,$this->c47_ref
                               ,$this->c47_anousu
                               ,$this->c47_instit
                               ,$this->c47_compara
                               ,$this->c47_tiporesto
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contas dos Lançamentos ($this->c47_seqtranslr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contas dos Lançamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contas dos Lançamentos ($this->c47_seqtranslr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c47_seqtranslr;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c47_seqtranslr));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6022,'$this->c47_seqtranslr','I')");
       $resac = db_query("insert into db_acount values($acount,966,6022,'','".AddSlashes(pg_result($resaco,0,'c47_seqtranslr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,966,6023,'','".AddSlashes(pg_result($resaco,0,'c47_seqtranslan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,966,6024,'','".AddSlashes(pg_result($resaco,0,'c47_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,966,6025,'','".AddSlashes(pg_result($resaco,0,'c47_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,966,6026,'','".AddSlashes(pg_result($resaco,0,'c47_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,966,6098,'','".AddSlashes(pg_result($resaco,0,'c47_ref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,966,6131,'','".AddSlashes(pg_result($resaco,0,'c47_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,966,6139,'','".AddSlashes(pg_result($resaco,0,'c47_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,966,6241,'','".AddSlashes(pg_result($resaco,0,'c47_compara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,966,6242,'','".AddSlashes(pg_result($resaco,0,'c47_tiporesto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c47_seqtranslr=null) {
      $this->atualizacampos();
     $sql = " update contranslr set ";
     $virgula = "";
     if(trim($this->c47_seqtranslr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c47_seqtranslr"])){
       $sql  .= $virgula." c47_seqtranslr = $this->c47_seqtranslr ";
       $virgula = ",";
       if(trim($this->c47_seqtranslr) == null ){
         $this->erro_sql = " Campo Sequ. Translr nao Informado.";
         $this->erro_campo = "c47_seqtranslr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c47_seqtranslan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c47_seqtranslan"])){
       $sql  .= $virgula." c47_seqtranslan = $this->c47_seqtranslan ";
       $virgula = ",";
       if(trim($this->c47_seqtranslan) == null ){
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "c47_seqtranslan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c47_debito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c47_debito"])){
       $sql  .= $virgula." c47_debito = $this->c47_debito ";
       $virgula = ",";
       if(trim($this->c47_debito) == null ){
         $this->erro_sql = " Campo Débito nao Informado.";
         $this->erro_campo = "c47_debito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c47_credito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c47_credito"])){
       $sql  .= $virgula." c47_credito = $this->c47_credito ";
       $virgula = ",";
       if(trim($this->c47_credito) == null ){
         $this->erro_sql = " Campo Crédito nao Informado.";
         $this->erro_campo = "c47_credito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c47_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c47_obs"])){
       $sql  .= $virgula." c47_obs = '$this->c47_obs' ";
       $virgula = ",";
     }
     if(trim($this->c47_ref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c47_ref"])){
        if(trim($this->c47_ref)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c47_ref"])){
           $this->c47_ref = "0" ;
        }
       $sql  .= $virgula." c47_ref = $this->c47_ref ";
       $virgula = ",";
     }
     if(trim($this->c47_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c47_anousu"])){
       $sql  .= $virgula." c47_anousu = $this->c47_anousu ";
       $virgula = ",";
       if(trim($this->c47_anousu) == null ){
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "c47_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c47_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c47_instit"])){
       $sql  .= $virgula." c47_instit = $this->c47_instit ";
       $virgula = ",";
       if(trim($this->c47_instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "c47_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c47_compara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c47_compara"])){
       $sql  .= $virgula." c47_compara = $this->c47_compara ";
       $virgula = ",";
       if(trim($this->c47_compara) == null ){
         $this->erro_sql = " Campo Compara nao Informado.";
         $this->erro_campo = "c47_compara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c47_tiporesto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c47_tiporesto"])){
       $sql  .= $virgula." c47_tiporesto = $this->c47_tiporesto ";
       $virgula = ",";
       if(trim($this->c47_tiporesto) == null ){
         $this->erro_sql = " Campo tipo resto nao Informado.";
         $this->erro_campo = "c47_tiporesto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c47_seqtranslr!=null){
       if (empty($this->c47_seqtranslr)){
         $this->c47_seqtranslr = $c47_seqtranslr;
       }
       $sql .= " c47_seqtranslr = $this->c47_seqtranslr";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c47_seqtranslr));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6022,'$this->c47_seqtranslr','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c47_seqtranslr"]))
           $resac = db_query("insert into db_acount values($acount,966,6022,'".AddSlashes(pg_result($resaco,$conresaco,'c47_seqtranslr'))."','$this->c47_seqtranslr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c47_seqtranslan"]))
           $resac = db_query("insert into db_acount values($acount,966,6023,'".AddSlashes(pg_result($resaco,$conresaco,'c47_seqtranslan'))."','$this->c47_seqtranslan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c47_debito"]))
           $resac = db_query("insert into db_acount values($acount,966,6024,'".AddSlashes(pg_result($resaco,$conresaco,'c47_debito'))."','$this->c47_debito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c47_credito"]))
           $resac = db_query("insert into db_acount values($acount,966,6025,'".AddSlashes(pg_result($resaco,$conresaco,'c47_credito'))."','$this->c47_credito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c47_obs"]))
           $resac = db_query("insert into db_acount values($acount,966,6026,'".AddSlashes(pg_result($resaco,$conresaco,'c47_obs'))."','$this->c47_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c47_ref"]))
           $resac = db_query("insert into db_acount values($acount,966,6098,'".AddSlashes(pg_result($resaco,$conresaco,'c47_ref'))."','$this->c47_ref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c47_anousu"]))
           $resac = db_query("insert into db_acount values($acount,966,6131,'".AddSlashes(pg_result($resaco,$conresaco,'c47_anousu'))."','$this->c47_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c47_instit"]))
           $resac = db_query("insert into db_acount values($acount,966,6139,'".AddSlashes(pg_result($resaco,$conresaco,'c47_instit'))."','$this->c47_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c47_compara"]))
           $resac = db_query("insert into db_acount values($acount,966,6241,'".AddSlashes(pg_result($resaco,$conresaco,'c47_compara'))."','$this->c47_compara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c47_tiporesto"]))
           $resac = db_query("insert into db_acount values($acount,966,6242,'".AddSlashes(pg_result($resaco,$conresaco,'c47_tiporesto'))."','$this->c47_tiporesto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas dos Lançamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c47_seqtranslr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contas dos Lançamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c47_seqtranslr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c47_seqtranslr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c47_seqtranslr=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c47_seqtranslr));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6022,'$c47_seqtranslr','E')");
         $resac = db_query("insert into db_acount values($acount,966,6022,'','".AddSlashes(pg_result($resaco,$iresaco,'c47_seqtranslr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,966,6023,'','".AddSlashes(pg_result($resaco,$iresaco,'c47_seqtranslan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,966,6024,'','".AddSlashes(pg_result($resaco,$iresaco,'c47_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,966,6025,'','".AddSlashes(pg_result($resaco,$iresaco,'c47_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,966,6026,'','".AddSlashes(pg_result($resaco,$iresaco,'c47_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,966,6098,'','".AddSlashes(pg_result($resaco,$iresaco,'c47_ref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,966,6131,'','".AddSlashes(pg_result($resaco,$iresaco,'c47_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,966,6139,'','".AddSlashes(pg_result($resaco,$iresaco,'c47_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,966,6241,'','".AddSlashes(pg_result($resaco,$iresaco,'c47_compara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,966,6242,'','".AddSlashes(pg_result($resaco,$iresaco,'c47_tiporesto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contranslr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c47_seqtranslr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c47_seqtranslr = $c47_seqtranslr ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas dos Lançamentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c47_seqtranslr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contas dos Lançamentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c47_seqtranslr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c47_seqtranslr;
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
        $this->erro_sql   = "Record Vazio na Tabela:contranslr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c47_seqtranslr=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from contranslr ";
     $sql .= "      inner join contranslan  on  contranslan.c46_seqtranslan = contranslr.c47_seqtranslan";
     $sql .= "      inner join conhist  on  conhist.c50_codhist = contranslan.c46_codhist";
     $sql .= "      inner join contrans  on  contrans.c45_seqtrans = contranslan.c46_seqtrans";
     $sql .= "      left  join contranslrelemento  on  contranslrelemento.c114_contranslr = contranslr.c47_seqtranslr ";
     $sql2 = "";
     if($dbwhere==""){
       if($c47_seqtranslr!=null ){
         $sql2 .= " where contranslr.c47_seqtranslr = $c47_seqtranslr ";
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
   function sql_query_file ( $c47_seqtranslr=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from contranslr ";
     $sql2 = "";
     if($dbwhere==""){
       if($c47_seqtranslr!=null ){
         $sql2 .= " where contranslr.c47_seqtranslr = $c47_seqtranslr ";
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

  public function sql_query_lancamento_contabil($c47_seqtranslr=null,$campos="*",$ordem=null,$dbwhere="") {

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
  	$sql .= " from contranslr ";
  	$sql .= "      inner join contranslan  on  contranslan.c46_seqtranslan = contranslr.c47_seqtranslan";
  	$sql .= "      inner join conhist  on  conhist.c50_codhist = contranslan.c46_codhist";
  	$sql .= "      inner join contrans  on  contrans.c45_seqtrans = contranslan.c46_seqtrans";
  	$sql .= "      inner join conlancamlr on  conlancamlr.c81_seqtranslr = contranslr.c47_seqtranslr";
  	$sql .= "      inner join conlancamval on  conlancamval.c69_sequen = conlancamlr.c81_sequen";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($c47_seqtranslr!=null ){
  			$sql2 .= " where contranslr.c47_seqtranslr = $c47_seqtranslr ";
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