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

//MODULO: empenho
//CLASSE DA ENTIDADE empautitem
class cl_empautitem {
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
   var $e55_autori = 0;
   var $e55_item = 0;
   var $e55_sequen = 0;
   var $e55_quant = 0;
   var $e55_vltot = 0;
   var $e55_descr = null;
   var $e55_codele = 0;
   var $e55_vlrun = 0;
   var $e55_servicoquantidade = 'false';
   var $e55_matunid = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e55_autori = int4 = Autorização
                 e55_item = int4 = Item
                 e55_sequen = int4 = Sequencia
                 e55_quant = float8 = Quantidade
                 e55_vltot = float8 = Valor total
                 e55_descr = text = Descrição
                 e55_codele = int4 = Elemento
                 e55_vlrun = float8 = Valor Unitário
                 e55_servicoquantidade = bool = Serviço Controlado por Quantidade
                 e55_matunid = int4 = Unidade
                 ";
   //funcao construtor da classe
   function cl_empautitem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empautitem");
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
       $this->e55_autori = ($this->e55_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e55_autori"]:$this->e55_autori);
       $this->e55_item = ($this->e55_item == ""?@$GLOBALS["HTTP_POST_VARS"]["e55_item"]:$this->e55_item);
       $this->e55_sequen = ($this->e55_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["e55_sequen"]:$this->e55_sequen);
       $this->e55_quant = ($this->e55_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["e55_quant"]:$this->e55_quant);
       $this->e55_vltot = ($this->e55_vltot == ""?@$GLOBALS["HTTP_POST_VARS"]["e55_vltot"]:$this->e55_vltot);
       $this->e55_descr = ($this->e55_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["e55_descr"]:$this->e55_descr);
       $this->e55_codele = ($this->e55_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["e55_codele"]:$this->e55_codele);
       $this->e55_vlrun = ($this->e55_vlrun == ""?@$GLOBALS["HTTP_POST_VARS"]["e55_vlrun"]:$this->e55_vlrun);
       $this->e55_servicoquantidade = ($this->e55_servicoquantidade == "f"?@$GLOBALS["HTTP_POST_VARS"]["e55_servicoquantidade"]:$this->e55_servicoquantidade);
       $this->e55_matunid = ($this->e55_matunid == ""?@$GLOBALS["HTTP_POST_VARS"]["e55_matunid"]:$this->e55_matunid);
     }else{
       $this->e55_autori = ($this->e55_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e55_autori"]:$this->e55_autori);
       $this->e55_sequen = ($this->e55_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["e55_sequen"]:$this->e55_sequen);
     }
   }
   // funcao para Inclusão
   function incluir ($e55_autori,$e55_sequen){
      $this->atualizacampos();
     if($this->e55_item == null ){
       $this->erro_sql = " Campo Item não informado.";
       $this->erro_campo = "e55_item";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e55_quant == null ){
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "e55_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e55_vltot == null ){
       $this->erro_sql = " Campo Valor total não informado.";
       $this->erro_campo = "e55_vltot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e55_codele == null ){
       $this->erro_sql = " Campo Elemento não informado.";
       $this->erro_campo = "e55_codele";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e55_vlrun == null ){
       $this->erro_sql = " Campo Valor Unitário não informado.";
       $this->erro_campo = "e55_vlrun";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e55_servicoquantidade == null ){
       $this->e55_servicoquantidade = "false";
     }
     if($this->e55_matunid == null ){
       $this->e55_matunid = "null";
     }
       $this->e55_autori = $e55_autori;
       $this->e55_sequen = $e55_sequen;
     if(($this->e55_autori == null) || ($this->e55_autori == "") ){
       $this->erro_sql = " Campo e55_autori não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e55_sequen == null) || ($this->e55_sequen == "") ){
       $this->erro_sql = " Campo e55_sequen não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empautitem(
                                       e55_autori
                                      ,e55_item
                                      ,e55_sequen
                                      ,e55_quant
                                      ,e55_vltot
                                      ,e55_descr
                                      ,e55_codele
                                      ,e55_vlrun
                                      ,e55_servicoquantidade
                                      ,e55_matunid
                       )
                values (
                                $this->e55_autori
                               ,$this->e55_item
                               ,$this->e55_sequen
                               ,$this->e55_quant
                               ,$this->e55_vltot
                               ,'$this->e55_descr'
                               ,$this->e55_codele
                               ,$this->e55_vlrun
                               ,'$this->e55_servicoquantidade'
                               ,$this->e55_matunid
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens empenho ($this->e55_autori."-".$this->e55_sequen) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens empenho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens empenho ($this->e55_autori."-".$this->e55_sequen) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e55_autori."-".$this->e55_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e55_autori,$this->e55_sequen  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5467,'$this->e55_autori','I')");
         $resac = db_query("insert into db_acountkey values($acount,5468,'$this->e55_sequen','I')");
         $resac = db_query("insert into db_acount values($acount,811,5467,'','".AddSlashes(pg_result($resaco,0,'e55_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,811,5469,'','".AddSlashes(pg_result($resaco,0,'e55_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,811,5468,'','".AddSlashes(pg_result($resaco,0,'e55_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,811,5470,'','".AddSlashes(pg_result($resaco,0,'e55_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,811,5471,'','".AddSlashes(pg_result($resaco,0,'e55_vltot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,811,5472,'','".AddSlashes(pg_result($resaco,0,'e55_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,811,5473,'','".AddSlashes(pg_result($resaco,0,'e55_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,811,7426,'','".AddSlashes(pg_result($resaco,0,'e55_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,811,19698,'','".AddSlashes(pg_result($resaco,0,'e55_servicoquantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,811,21868,'','".AddSlashes(pg_result($resaco,0,'e55_matunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($e55_autori=null,$e55_sequen=null) {
      $this->atualizacampos();
     $sql = " update empautitem set ";
     $virgula = "";
     if(trim($this->e55_autori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e55_autori"])){
       $sql  .= $virgula." e55_autori = $this->e55_autori ";
       $virgula = ",";
       if(trim($this->e55_autori) == null ){
         $this->erro_sql = " Campo Autorização não informado.";
         $this->erro_campo = "e55_autori";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e55_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e55_item"])){
       $sql  .= $virgula." e55_item = $this->e55_item ";
       $virgula = ",";
       if(trim($this->e55_item) == null ){
         $this->erro_sql = " Campo Item não informado.";
         $this->erro_campo = "e55_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e55_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e55_sequen"])){
       $sql  .= $virgula." e55_sequen = $this->e55_sequen ";
       $virgula = ",";
       if(trim($this->e55_sequen) == null ){
         $this->erro_sql = " Campo Sequencia não informado.";
         $this->erro_campo = "e55_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e55_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e55_quant"])){
       $sql  .= $virgula." e55_quant = $this->e55_quant ";
       $virgula = ",";
       if(trim($this->e55_quant) == null ){
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "e55_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e55_vltot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e55_vltot"])){
       $sql  .= $virgula." e55_vltot = $this->e55_vltot ";
       $virgula = ",";
       if(trim($this->e55_vltot) == null ){
         $this->erro_sql = " Campo Valor total não informado.";
         $this->erro_campo = "e55_vltot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e55_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e55_descr"])){
       $sql  .= $virgula." e55_descr = '$this->e55_descr' ";
       $virgula = ",";
     }
     if(trim($this->e55_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e55_codele"])){
       $sql  .= $virgula." e55_codele = $this->e55_codele ";
       $virgula = ",";
       if(trim($this->e55_codele) == null ){
         $this->erro_sql = " Campo Elemento não informado.";
         $this->erro_campo = "e55_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e55_vlrun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e55_vlrun"])){
       $sql  .= $virgula." e55_vlrun = $this->e55_vlrun ";
       $virgula = ",";
       if(trim($this->e55_vlrun) == null ){
         $this->erro_sql = " Campo Valor Unitário não informado.";
         $this->erro_campo = "e55_vlrun";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e55_servicoquantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e55_servicoquantidade"])){
       $sql  .= $virgula." e55_servicoquantidade = '$this->e55_servicoquantidade' ";
       $virgula = ",";
     }
     if(trim($this->e55_matunid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e55_matunid"])){
        if(trim($this->e55_matunid)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e55_matunid"])){
           $this->e55_matunid = "0" ;
        }
       $sql  .= $virgula." e55_matunid = $this->e55_matunid ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e55_autori!=null){
       $sql .= " e55_autori = $this->e55_autori";
     }
     if($e55_sequen!=null){
       $sql .= " and  e55_sequen = $this->e55_sequen";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e55_autori,$this->e55_sequen));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5467,'$this->e55_autori','A')");
           $resac = db_query("insert into db_acountkey values($acount,5468,'$this->e55_sequen','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e55_autori"]) || $this->e55_autori != "")
             $resac = db_query("insert into db_acount values($acount,811,5467,'".AddSlashes(pg_result($resaco,$conresaco,'e55_autori'))."','$this->e55_autori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e55_item"]) || $this->e55_item != "")
             $resac = db_query("insert into db_acount values($acount,811,5469,'".AddSlashes(pg_result($resaco,$conresaco,'e55_item'))."','$this->e55_item',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e55_sequen"]) || $this->e55_sequen != "")
             $resac = db_query("insert into db_acount values($acount,811,5468,'".AddSlashes(pg_result($resaco,$conresaco,'e55_sequen'))."','$this->e55_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e55_quant"]) || $this->e55_quant != "")
             $resac = db_query("insert into db_acount values($acount,811,5470,'".AddSlashes(pg_result($resaco,$conresaco,'e55_quant'))."','$this->e55_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e55_vltot"]) || $this->e55_vltot != "")
             $resac = db_query("insert into db_acount values($acount,811,5471,'".AddSlashes(pg_result($resaco,$conresaco,'e55_vltot'))."','$this->e55_vltot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e55_descr"]) || $this->e55_descr != "")
             $resac = db_query("insert into db_acount values($acount,811,5472,'".AddSlashes(pg_result($resaco,$conresaco,'e55_descr'))."','$this->e55_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e55_codele"]) || $this->e55_codele != "")
             $resac = db_query("insert into db_acount values($acount,811,5473,'".AddSlashes(pg_result($resaco,$conresaco,'e55_codele'))."','$this->e55_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e55_vlrun"]) || $this->e55_vlrun != "")
             $resac = db_query("insert into db_acount values($acount,811,7426,'".AddSlashes(pg_result($resaco,$conresaco,'e55_vlrun'))."','$this->e55_vlrun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e55_servicoquantidade"]) || $this->e55_servicoquantidade != "")
             $resac = db_query("insert into db_acount values($acount,811,19698,'".AddSlashes(pg_result($resaco,$conresaco,'e55_servicoquantidade'))."','$this->e55_servicoquantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e55_matunid"]) || $this->e55_matunid != "")
             $resac = db_query("insert into db_acount values($acount,811,21868,'".AddSlashes(pg_result($resaco,$conresaco,'e55_matunid'))."','$this->e55_matunid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens empenho não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e55_autori."-".$this->e55_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Itens empenho não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e55_autori."-".$this->e55_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e55_autori."-".$this->e55_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($e55_autori=null,$e55_sequen=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($e55_autori,$e55_sequen));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,5467,'$e55_autori','E')");
           $resac  = db_query("insert into db_acountkey values($acount,5468,'$e55_sequen','E')");
           $resac  = db_query("insert into db_acount values($acount,811,5467,'','".AddSlashes(pg_result($resaco,$iresaco,'e55_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,811,5469,'','".AddSlashes(pg_result($resaco,$iresaco,'e55_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,811,5468,'','".AddSlashes(pg_result($resaco,$iresaco,'e55_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,811,5470,'','".AddSlashes(pg_result($resaco,$iresaco,'e55_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,811,5471,'','".AddSlashes(pg_result($resaco,$iresaco,'e55_vltot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,811,5472,'','".AddSlashes(pg_result($resaco,$iresaco,'e55_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,811,5473,'','".AddSlashes(pg_result($resaco,$iresaco,'e55_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,811,7426,'','".AddSlashes(pg_result($resaco,$iresaco,'e55_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,811,19698,'','".AddSlashes(pg_result($resaco,$iresaco,'e55_servicoquantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,811,21868,'','".AddSlashes(pg_result($resaco,$iresaco,'e55_matunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empautitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e55_autori != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e55_autori = $e55_autori ";
        }
        if($e55_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e55_sequen = $e55_sequen ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens empenho não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e55_autori."-".$e55_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Itens empenho não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e55_autori."-".$e55_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e55_autori."-".$e55_sequen;
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
     if($result==false){
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
        $this->erro_sql   = "Record Vazio na Tabela:empautitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= "  from empautitem ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = empautitem.e55_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautitem.e55_autori";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empautitem.e55_item";
     $sql .= "      left  join matunid  on  matunid.m61_codmatunid = empautitem.e55_matunid";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empautoriza.e54_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empautoriza.e54_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empautoriza.e54_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = empautoriza.e54_depto";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empautoriza.e54_codcom";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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
   function sql_query_anuaut ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= "  from empautitem ";
     $sql .= "      inner join empautoriza          on  empautoriza.e54_autori         = empautitem.e55_autori";
     $sql .= "      inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
     $sql .= "                                     and empautitempcprocitem.e73_autori = empautitem.e55_autori";
     $sql .= "      inner join pcprocitem           on  pcprocitem.pc81_codprocitem    = empautitempcprocitem.e73_pcprocitem";
     $sql .= "      inner join solicitem            on  solicitem.pc11_codigo          = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pcmater              on  pcmater.pc01_codmater          = empautitem.e55_item";
     $sql .= "      inner join cgm                  on  cgm.z01_numcgm                 = empautoriza.e54_numcgm";
     $sql .= "      inner join empautidot           on  empautidot.e56_autori          = empautitem.e55_autori ";
     $sql .= "      left  join empempaut            on  empempaut.e61_autori           = empautitem.e55_autori ";
     $sql .= "      left  join empempenho           on  empempenho.e60_numemp          = empempaut.e61_numemp";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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

   function sql_query_autoridot ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautitem ";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautitem.e55_autori";
     $sql .= "      inner join empautidot           on empautidot.e56_autori           = empautoriza.e54_autori";
     $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen ";
     $sql .= "                                     and empautitempcprocitem.e73_autori = empautitem.e55_autori";
     $sql .= "      left  join empempaut            on e61_autori                      = empautoriza.e54_autori ";
     $sql .= "      left  join empempenho           on e60_numemp                      = empempaut.e61_numemp ";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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
   function sql_query_autoriza ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautitem ";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautitem.e55_autori";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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
   function sql_query_buscae54 ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautitem ";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautitem.e55_autori";
     $sql .= "      inner join empautidot             on empautidot.e56_autori           = empautoriza.e54_autori";
     $sql .= "      left  join empautitempcprocitem   on empautitempcprocitem.e73_autori = empautitem.e55_autori";
     $sql .= "                                       and empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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
   function sql_query_buscaritemdot ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautitem ";
     $sql .= "      inner join empautidot  on  empautidot.e56_autori = empautitem.e55_autori";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautitem.e55_autori";
     $sql .= "      left  join empautitempcprocitem   on empautitempcprocitem.e73_autori = empautitem.e55_autori";
     $sql .= "                                       and empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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
   function sql_query_elemento ( $e55_autori=null,$e55_sequen=null,$ordem=null,$dbwhere=""){
     $sql = "select ";

     $campos = "e55_codele,sum(e55_vltot) as e55_vltot";
     $sql .= $campos;
     $sql .= " from empautitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
           $sql2 .= " and ";
         }else{
           $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     $sql .="group by e55_codele";
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
   function sql_query_file ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautitem ";
     // $sql .= "      inner join empempaut    on  empempaut.e61_numemp   = empautitem.e55_numemp";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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
   function sql_query_itemdot ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautitem ";
     $sql .= "      inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
     $sql .= "                                     and empautitempcprocitem.e73_autori = empautitem.e55_autori";
     $sql .= "      inner join pcprocitem           on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
     $sql .= "      inner join empautidot           on empautidot.e56_autori           = empautitem.e55_autori";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautitem.e55_autori";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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
   function sql_query_lic ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautitem ";
     $sql .= "      inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
     $sql .= "                                     and empautitempcprocitem.e73_autori = empautitem.e55_autori";
     $sql .= "      inner join liclicitem           on liclicitem.l21_codpcprocitem    = empautitempcprocitem.e73_pcprocitem";
     $sql .= "      inner join liclicita            on liclicitem.l21_codliclicita     = liclicita.l20_codigo";
     $sql .= "      inner join cflicita             on liclicita.l20_codtipocom        = cflicita.l03_codigo";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautitem.e55_autori";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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
   function sql_query_processocompras ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from empautitem ";
     $sql .= "      inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
     $sql .= "                                     and empautitempcprocitem.e73_autori = empautitem.e55_autori";
     $sql .= "      inner join pcprocitem           on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori      = empautitem.e55_autori";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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
   function sql_query_pcmaterele ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empautitem ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = empautitem.e55_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join empautoriza          on empautoriza.e54_autori          = empautitem.e55_autori";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empautitem.e55_item";
     $sql .= "      inner join pcmaterele  on  pcmater.pc01_codmater = pcmaterele.pc07_codmater and empautitem.e55_codele=pcmaterele.pc07_codele";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empautoriza.e54_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empautoriza.e54_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empautoriza.e54_login";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empautoriza.e54_codcom";
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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
   function sql_query_item_pacto ( $e55_autori=null,$e55_sequen=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from empautitem ";
     $sql .= "      inner join empautidot              on empautidot.e56_autori           = empautitem.e55_autori";
     $sql .= "      inner join empautoriza             on empautoriza.e54_autori          = empautitem.e55_autori";
     $sql .= "      inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
     $sql .= "                                     and empautitempcprocitem.e73_autori = empautitem.e55_autori";
     $sql .= "      inner join pcprocitem           on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
     $sql .= "      inner join solicitem               on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pactovalormovsolicitem  on pc11_codigo                     = o101_solicitem";
     $sql .= "      inner join pactovalormov           on o101_pactovalormov              = o88_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
       }
       if($e55_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
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
