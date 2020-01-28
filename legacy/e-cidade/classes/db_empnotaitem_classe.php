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
//CLASSE DA ENTIDADE empnotaitem
class cl_empnotaitem {
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
   var $e72_sequencial = 0;
   var $e72_codnota = 0;
   var $e72_empempitem = 0;
   var $e72_qtd = 0;
   var $e72_valor = 0;
   var $e72_vlrliq = 0;
   var $e72_vlranu = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e72_sequencial = int4 = Código Sequencial
                 e72_codnota = int4 = Código da Nota
                 e72_empempitem = int4 = Item do Empenho
                 e72_qtd = float4 = Quantidade
                 e72_valor = float4 = Valor
                 e72_vlrliq = float4 = Valor Liquidado da nota
                 e72_vlranu = float4 = Valor Anulado do Item
                 ";
   //funcao construtor da classe
   function cl_empnotaitem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empnotaitem");
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
       $this->e72_sequencial = ($this->e72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e72_sequencial"]:$this->e72_sequencial);
       $this->e72_codnota = ($this->e72_codnota == ""?@$GLOBALS["HTTP_POST_VARS"]["e72_codnota"]:$this->e72_codnota);
       $this->e72_empempitem = ($this->e72_empempitem == ""?@$GLOBALS["HTTP_POST_VARS"]["e72_empempitem"]:$this->e72_empempitem);
       $this->e72_qtd = ($this->e72_qtd == ""?@$GLOBALS["HTTP_POST_VARS"]["e72_qtd"]:$this->e72_qtd);
       $this->e72_valor = ($this->e72_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["e72_valor"]:$this->e72_valor);
       $this->e72_vlrliq = ($this->e72_vlrliq == ""?@$GLOBALS["HTTP_POST_VARS"]["e72_vlrliq"]:$this->e72_vlrliq);
       $this->e72_vlranu = ($this->e72_vlranu == ""?@$GLOBALS["HTTP_POST_VARS"]["e72_vlranu"]:$this->e72_vlranu);
     }else{
       $this->e72_sequencial = ($this->e72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e72_sequencial"]:$this->e72_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e72_sequencial){
      $this->atualizacampos();
     if($this->e72_codnota == null ){
       $this->erro_sql = " Campo Código da Nota nao Informado.";
       $this->erro_campo = "e72_codnota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e72_empempitem == null ){
       $this->erro_sql = " Campo Item do Empenho nao Informado.";
       $this->erro_campo = "e72_empempitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e72_qtd == null ){
       $this->e72_qtd = "0";
     }
     if($this->e72_valor == null ){
       $this->e72_valor = "0";
     }
     if($this->e72_vlrliq == null ){
       $this->e72_vlrliq = "0";
     }
     if($this->e72_vlranu == null ){
       $this->e72_vlranu = "0";
     }
     if($e72_sequencial == "" || $e72_sequencial == null ){
       $result = db_query("select nextval('empnotaitem_e72_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empnotaitem_e72_sequencial_seq do campo: e72_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e72_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from empnotaitem_e72_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e72_sequencial)){
         $this->erro_sql = " Campo e72_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e72_sequencial = $e72_sequencial;
       }
     }
     if(($this->e72_sequencial == null) || ($this->e72_sequencial == "") ){
       $this->erro_sql = " Campo e72_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empnotaitem(
                                       e72_sequencial
                                      ,e72_codnota
                                      ,e72_empempitem
                                      ,e72_qtd
                                      ,e72_valor
                                      ,e72_vlrliq
                                      ,e72_vlranu
                       )
                values (
                                $this->e72_sequencial
                               ,$this->e72_codnota
                               ,$this->e72_empempitem
                               ,$this->e72_qtd
                               ,$this->e72_valor
                               ,$this->e72_vlrliq
                               ,$this->e72_vlranu
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens das notas ($this->e72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens das notas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens das notas ($this->e72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e72_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e72_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11044,'$this->e72_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1903,11044,'','".AddSlashes(pg_result($resaco,0,'e72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1903,11045,'','".AddSlashes(pg_result($resaco,0,'e72_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1903,11046,'','".AddSlashes(pg_result($resaco,0,'e72_empempitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1903,11053,'','".AddSlashes(pg_result($resaco,0,'e72_qtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1903,11048,'','".AddSlashes(pg_result($resaco,0,'e72_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1903,11051,'','".AddSlashes(pg_result($resaco,0,'e72_vlrliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1903,11052,'','".AddSlashes(pg_result($resaco,0,'e72_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($e72_sequencial=null) {
      $this->atualizacampos();
     $sql = " update empnotaitem set ";
     $virgula = "";
     if(trim($this->e72_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e72_sequencial"])){
       $sql  .= $virgula." e72_sequencial = $this->e72_sequencial ";
       $virgula = ",";
       if(trim($this->e72_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e72_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e72_codnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e72_codnota"])){
       $sql  .= $virgula." e72_codnota = $this->e72_codnota ";
       $virgula = ",";
       if(trim($this->e72_codnota) == null ){
         $this->erro_sql = " Campo Código da Nota nao Informado.";
         $this->erro_campo = "e72_codnota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e72_empempitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e72_empempitem"])){
       $sql  .= $virgula." e72_empempitem = $this->e72_empempitem ";
       $virgula = ",";
       if(trim($this->e72_empempitem) == null ){
         $this->erro_sql = " Campo Item do Empenho nao Informado.";
         $this->erro_campo = "e72_empempitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e72_qtd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e72_qtd"])){
        if(trim($this->e72_qtd)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e72_qtd"])){
           $this->e72_qtd = "0" ;
        }
       $sql  .= $virgula." e72_qtd = $this->e72_qtd ";
       $virgula = ",";
     }
     if(trim($this->e72_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e72_valor"])){
        if(trim($this->e72_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e72_valor"])){
           $this->e72_valor = "0" ;
        }
       $sql  .= $virgula." e72_valor = $this->e72_valor ";
       $virgula = ",";
     }
     if(trim($this->e72_vlrliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e72_vlrliq"])){
        if(trim($this->e72_vlrliq)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e72_vlrliq"])){
           $this->e72_vlrliq = "0" ;
        }
       $sql  .= $virgula." e72_vlrliq = $this->e72_vlrliq ";
       $virgula = ",";
     }
     if(trim($this->e72_vlranu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e72_vlranu"])){
        if(trim($this->e72_vlranu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e72_vlranu"])){
           $this->e72_vlranu = "0" ;
        }
       $sql  .= $virgula." e72_vlranu = $this->e72_vlranu ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e72_sequencial!=null){
       $sql .= " e72_sequencial = $this->e72_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e72_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11044,'$this->e72_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e72_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1903,11044,'".AddSlashes(pg_result($resaco,$conresaco,'e72_sequencial'))."','$this->e72_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e72_codnota"]))
           $resac = db_query("insert into db_acount values($acount,1903,11045,'".AddSlashes(pg_result($resaco,$conresaco,'e72_codnota'))."','$this->e72_codnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e72_empempitem"]))
           $resac = db_query("insert into db_acount values($acount,1903,11046,'".AddSlashes(pg_result($resaco,$conresaco,'e72_empempitem'))."','$this->e72_empempitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e72_qtd"]))
           $resac = db_query("insert into db_acount values($acount,1903,11053,'".AddSlashes(pg_result($resaco,$conresaco,'e72_qtd'))."','$this->e72_qtd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e72_valor"]))
           $resac = db_query("insert into db_acount values($acount,1903,11048,'".AddSlashes(pg_result($resaco,$conresaco,'e72_valor'))."','$this->e72_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e72_vlrliq"]))
           $resac = db_query("insert into db_acount values($acount,1903,11051,'".AddSlashes(pg_result($resaco,$conresaco,'e72_vlrliq'))."','$this->e72_vlrliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e72_vlranu"]))
           $resac = db_query("insert into db_acount values($acount,1903,11052,'".AddSlashes(pg_result($resaco,$conresaco,'e72_vlranu'))."','$this->e72_vlranu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens das notas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens das notas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($e72_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e72_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11044,'$e72_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1903,11044,'','".AddSlashes(pg_result($resaco,$iresaco,'e72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1903,11045,'','".AddSlashes(pg_result($resaco,$iresaco,'e72_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1903,11046,'','".AddSlashes(pg_result($resaco,$iresaco,'e72_empempitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1903,11053,'','".AddSlashes(pg_result($resaco,$iresaco,'e72_qtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1903,11048,'','".AddSlashes(pg_result($resaco,$iresaco,'e72_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1903,11051,'','".AddSlashes(pg_result($resaco,$iresaco,'e72_vlrliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1903,11052,'','".AddSlashes(pg_result($resaco,$iresaco,'e72_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empnotaitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e72_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e72_sequencial = $e72_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens das notas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens das notas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e72_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empnotaitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empnotaitem ";
     $sql .= "      inner join empempitem  on  empempitem.e62_sequencial = empnotaitem.e72_empempitem";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = empnotaitem.e72_codnota";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empempitem.e62_numemp";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho  as a on   a.e60_numemp = empnota.e69_numemp";
     $sql2 = "";
     if($dbwhere==""){
       if($e72_sequencial!=null ){
         $sql2 .= " where empnotaitem.e72_sequencial = $e72_sequencial ";
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
   function sql_query_file ( $e72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empnotaitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($e72_sequencial!=null ){
         $sql2 .= " where empnotaitem.e72_sequencial = $e72_sequencial ";
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
   function sql_query_ordemCompra( $e72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empnotaitem ";
     $sql .= "      inner join empempitem  on  empempitem.e62_sequencial = empnotaitem.e72_empempitem";
     $sql .= "      inner join empnota     on  empnota.e69_codnota       = empnotaitem.e72_codnota";
     $sql .= "      inner join pcmater     on  pcmater.pc01_codmater     = empempitem.e62_item";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp     = empempitem.e62_numemp";
     $sql .= "      inner join db_usuarios on  db_usuarios.id_usuario    = empnota.e69_id_usuario";
     $sql .= "      inner join empnotaord  on  empnotaord.m72_codnota    = empnota.e69_codnota";
     $sql .= "      inner join matordem    on  empnotaord.m72_codordem   = matordem.m51_codordem";
     $sql2 = "";
     if($dbwhere==""){
       if($e72_sequencial!=null ){
         $sql2 .= " where empnotaitem.e72_sequencial = $e72_sequencial ";
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

  function sql_query_empenho_item ( $e72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empnotaitem ";
    $sql .= "      inner join empempitem  on  empempitem.e62_sequencial = empnotaitem.e72_empempitem";
    $sql .= "      inner join empnota  on  empnota.e69_codnota = empnotaitem.e72_codnota";
    $sql2 = "";
    if($dbwhere==""){
      if($e72_sequencial!=null ){
        $sql2 .= " where empnotaitem.e72_sequencial = $e72_sequencial ";
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